<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Alat;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class KelolaPeminjamanController extends Controller
{
    public function index(Request $request)
    {
        // Filter berdasarkan status jika ada
        $status = $request->input('status');
        
        $peminjamans = Peminjaman::with('user')
            ->when($status && $status !== 'semua', function($query) use ($status) {
                return $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Hitung statistik
        $counts = [
            'menunggu' => Peminjaman::where('status', 'menunggu')->count(),
            'dipinjam' => Peminjaman::where('status', 'dipinjam')->count(),
            'selesai' => Peminjaman::where('status', 'selesai')->count(),
            'ditolak' => Peminjaman::where('status', 'ditolak')->count(),
            'ditegur' => Peminjaman::where('status', 'ditegur')->count(),
        ];
        
        return view('petugas.kelolapeminjaman', compact('peminjamans', 'counts'));
    }

    public function detail($id)
    {
        $peminjaman = Peminjaman::with(['user', 'alat'])->findOrFail($id);
        
        return response()->json([
            'id' => $peminjaman->id,
            'user' => [
                'name' => $peminjaman->user->name ?? 'Tidak diketahui',
                'email' => $peminjaman->user->email ?? '-',
                'kelas' => $peminjaman->user->kelas ?? '-',
                'phone' => $peminjaman->user->phone ?? '-',
            ],
            'alat' => $peminjaman->alat->map(function($alat) {
                return [
                    'id' => $alat->id,
                    'nama' => $alat->nama_alat,
                    'kode' => $alat->kode_alat,
                    'kondisi' => $alat->kondisi,
                    'lokasi' => $alat->lokasi,
                    'stok' => $alat->stok,
                ];
            }),
            'status' => $peminjaman->status,
            'metode_pengembalian' => $peminjaman->metode_pengembalian ?? 'langsung',
            'keterangan' => $peminjaman->keterangan,
            'foto_bukti' => $peminjaman->foto_bukti_url,
            'created_at' => $peminjaman->created_at->format('d/m/Y H:i'),
            'tanggal_peminjaman' => $peminjaman->tanggal_peminjaman->format('d/m/Y'),
            'tanggal_pengembalian' => $peminjaman->tanggal_pengembalian->format('d/m/Y'),
            'tanggal_dikembalikan' => $peminjaman->tanggal_dikembalikan ? $peminjaman->tanggal_dikembalikan->format('d/m/Y H:i') : null,
            'waktu_pengembalian' => $peminjaman->waktu_pengembalian ? Carbon::parse($peminjaman->waktu_pengembalian)->format('H:i') : null,
        ]);
    }

    public function konfirmasiPengembalian(Request $request, $id)
{
    try {
        $peminjaman = Peminjaman::findOrFail($id);
        
        // Validasi hanya yang menunggu konfirmasi pengembalian
        if ($peminjaman->status !== 'menunggu' || $peminjaman->metode_pengembalian !== 'menunggu_konfirmasi') {
            return response()->json([
                'success' => false,
                'message' => 'Peminjaman tidak dalam status menunggu konfirmasi pengembalian'
            ], 400);
        }
        
        // Konfirmasi pengembalian - ubah status ke selesai
        $peminjaman->update([
            'status' => 'selesai',
            'tanggal_dikembalikan' => now(),
            'metode_pengembalian' => 'langsung',
        ]);
        
        // Stok akan otomatis bertambah karena setter di model
        
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'role' => Auth::user()->role,
            'aktivitas' => 'Mengonfirmasi pengembalian peminjaman #' . $id,
            'modul' => 'pengembalian'
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Pengembalian berhasil dikonfirmasi. Stok alat telah dikembalikan.'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}

    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'kondisi' => 'required|in:baik,rusak',
            'catatan' => 'nullable|string|max:500',
        ]);
        
        $peminjaman = Peminjaman::findOrFail($id);
        
        // Update status ke selesai
        $peminjaman->update([
            'status' => 'selesai',
            'tanggal_dikembalikan' => now(),
            'waktu_pengembalian' => now(),
            'keterangan' => $request->catatan,
            'metode_pengembalian' => 'mandiri',
        ]);
        
        // Update kondisi alat jika rusak
        if ($request->kondisi === 'rusak' && $peminjaman->alat_ids) {
            foreach ($peminjaman->alat_ids as $alatId) {
                Alat::where('id', $alatId)->update(['kondisi' => 'rusak']);
            }
        }
        
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'role' => Auth::user()->role,
            'aktivitas' => 'Memverifikasi pengembalian mandiri #' . $id,
            'modul' => 'pengembalian'
        ]);
        
        return response()->json(['success' => true, 'message' => 'Pengembalian berhasil diverifikasi']);
    }

    public function tegur(Request $request, $id)
    {
        $request->validate([
            'alasan' => 'required|string',
            'deskripsi' => 'required|string|max:500',
        ]);
        
        $peminjaman = Peminjaman::findOrFail($id);
        
        // Update status ke ditegur
        $peminjaman->update([
            'status' => 'ditegur',
            'keterangan' => 'TEGURAN: ' . $request->alasan . ' - ' . $request->deskripsi,
        ]);
        
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'role' => Auth::user()->role,
            'aktivitas' => 'Memberi teguran pada peminjaman #' . $id,
            'modul' => 'peminjaman'
        ]);
        
        return response()->json(['success' => true, 'message' => 'Teguran berhasil dikirim']);
    }

    public function langsungKembali($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        // Untuk metode langsung, langsung selesaikan
        $peminjaman->update([
            'status' => 'selesai',
            'tanggal_dikembalikan' => now(),
            'waktu_pengembalian' => now(),
            'metode_pengembalian' => 'langsung',
        ]);
        
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'role' => Auth::user()->role,
            'aktivitas' => 'Mengonfirmasi pengembalian langsung #' . $id,
            'modul' => 'pengembalian'
        ]);
        
        return response()->json(['success' => true, 'message' => 'Pengembalian langsung berhasil dikonfirmasi']);
    }

    public function uploadFotoBukti(Request $request, $id)
    {
        $request->validate([
            'foto_bukti' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $peminjaman = Peminjaman::findOrFail($id);
        
        if ($request->hasFile('foto_bukti')) {
            $path = $request->file('foto_bukti')->store('foto-pengembalian', 'public');
            
            $peminjaman->update([
                'foto_bukti' => $path,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Foto bukti berhasil diupload',
                'foto_url' => asset('storage/' . $path)
            ]);
        }
        
        return response()->json(['success' => false, 'message' => 'Gagal upload foto'], 400);
    }

    public function export(Request $request)
    {
        $status = $request->input('status');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        $peminjamans = Peminjaman::with('user')
            ->when($status && $status !== 'semua', function($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($startDate, function($query) use ($startDate) {
                return $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function($query) use ($endDate) {
                return $query->whereDate('created_at', '<=', $endDate);
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Log eksport
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'role' => Auth::user()->role,
            'aktivitas' => 'Mengekspor data peminjaman',
            'modul' => 'peminjaman'
        ]);
        
        // Return data untuk di-download (bisa CSV, Excel, dll)
        return response()->json([
            'success' => true,
            'data' => $peminjamans,
            'count' => $peminjamans->count()
        ]);
    }
}