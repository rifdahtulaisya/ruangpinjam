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
    $request->validate([
        'alat_ids' => 'required|array|min:1',
        'alat_ids.*' => 'exists:alat,id',
        'tanggal_peminjaman' => 'required|date|after_or_equal:today',
        'tanggal_pengembalian' => 'required|date|after:tanggal_peminjaman',
    ]);

    try {
        \DB::beginTransaction();

        $alatNames = [];
        $alatIdsValid = [];

        // VALIDASI STOK SEBELUM MENYIMPAN
        foreach ($request->alat_ids as $alatId) {
            $alat = Alat::findOrFail($alatId);

            // Cek stok tersedia
            if ($alat->stok <= 0) {
                \DB::rollBack();
                return back()->with('error', "Alat {$alat->nama_alat} stok habis! Silakan pilih alat lain.");
            }

            $alatNames[] = $alat->nama_alat;
            $alatIdsValid[] = $alatId;
        }

        // Simpan peminjaman
        $peminjaman = Peminjaman::create([
            'user_id' => Auth::id(),
            'alat_ids' => $alatIdsValid,
            'nama_alat' => implode(', ', $alatNames),
            'tanggal_peminjaman' => $request->tanggal_peminjaman,
            'tanggal_pengembalian' => $request->tanggal_pengembalian,
            'status' => 'menunggu_peminjaman',
            'keterangan' => $request->keterangan,
        ]);

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'role' => Auth::user()->role,
            'aktivitas' => 'Mengajukan peminjaman: ' . implode(', ', $alatNames),
            'modul' => 'peminjaman'
        ]);

        \DB::commit();

        return redirect()
            ->route('peminjam.peminjaman.index')
            ->with('success', 'Peminjaman berhasil diajukan! Menunggu persetujuan petugas.');

    } catch (\Exception $e) {
        \DB::rollBack();
        \Log::error('Error store peminjaman: ' . $e->getMessage());
        
        return back()
            ->withInput()
            ->with('error', 'Gagal mengajukan peminjaman: ' . $e->getMessage());
    }
}
}