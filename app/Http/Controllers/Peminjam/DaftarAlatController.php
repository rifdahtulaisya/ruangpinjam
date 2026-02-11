<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\LogAktivitas; // Jangan lupa import LogAktivitas
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DaftarAlatController extends Controller
{
    public function index(Request $request)
    {
        // Ambil filter dari request
        $search = $request->input('search');
        $kategoriId = $request->input('kategori_id');
        $perPage = $request->input('per_page', 12);
        
        // Query dengan filter hanya untuk alat yang tersedia (stok > 0)
        $dataalat = Alat::with('kategori')
            ->where('stok', '>', 0)
            ->when($search, function($query, $search) {
                return $query->where('nama_alat', 'like', '%' . $search . '%')
                            ->orWhere('lokasi', 'like', '%' . $search . '%')
                            ->orWhere('kode_alat', 'like', '%' . $search . '%');
            })
            ->when($kategoriId, function($query, $kategoriId) {
                return $query->where('kategori_id', $kategoriId);
            })
            ->orderBy('nama_alat')
            ->paginate($perPage)
            ->withQueryString();
        
        $kategori = Kategori::all();
        
        return view('peminjam.daftaralat', compact('dataalat', 'kategori'));
    }
    
    public function storePeminjaman(Request $request)
    {
        // Debug: lihat data yang dikirim
        \Log::info('Store Peminjaman Request:', $request->all());
        
        $request->validate([
            'alat_ids' => 'required|array|min:1',
            'alat_ids.*' => 'exists:alat,id',
            'tanggal_peminjaman' => 'required|date|after_or_equal:today',
            'tanggal_pengembalian' => 'required|date|after:tanggal_peminjaman',
            'keterangan' => 'nullable|string|max:500',
        ]);

        try {
            \DB::beginTransaction(); // Mulai transaction

            $alatNames = [];
            $alatIdsValid = [];

            foreach ($request->alat_ids as $alatId) {
                $alat = Alat::findOrFail($alatId);

                if ($alat->stok <= 0) {
                    return back()->with('error', "Alat {$alat->nama_alat} stok habis!");
                }

                $alatNames[] = $alat->nama_alat;
                $alatIdsValid[] = $alatId;
            }

            // PERBAIKAN: Gunakan status yang baru
            // SIMPAN PEMINJAMAN
            $peminjaman = Peminjaman::create([
                'user_id' => Auth::id(),
                'alat_ids' => $alatIdsValid,
                'nama_alat' => implode(', ', $alatNames),
                'tanggal_peminjaman' => $request->tanggal_peminjaman,
                'tanggal_pengembalian' => $request->tanggal_pengembalian,
                'status' => 'menunggu_peminjaman', // PERUBAHAN DI SINI
                'keterangan' => $request->keterangan,
                'created_at' => now(),
            ]);

            // PERBAIKAN: Jangan kurangi stok dulu karena belum disetujui
            // Stok akan otomatis dikurangi di model Peminjaman saat status berubah ke 'dipinjam'
            // (di method setStatusAttribute)

            // Simpan log aktivitas
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'role' => Auth::user()->role,
                'aktivitas' => 'Mengajukan peminjaman: ' . implode(', ', $alatNames),
                'modul' => 'peminjaman'
            ]);

            \DB::commit(); // Commit transaction

            \Log::info('Peminjaman berhasil disimpan:', [
                'id' => $peminjaman->id,
                'user_id' => $peminjaman->user_id,
                'alat_ids' => $peminjaman->alat_ids,
                'status' => $peminjaman->status
            ]);

            return redirect()
                ->route('peminjam.peminjaman.index')
                ->with('success', 'Peminjaman berhasil diajukan! Menunggu persetujuan petugas.');

        } catch (\Exception $e) {
            \DB::rollBack(); // Rollback jika error
            
            \Log::error('Error store peminjaman:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Gagal mengajukan peminjaman: ' . $e->getMessage());
        }
    }
}