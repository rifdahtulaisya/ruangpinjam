<?php

// Di App\Http\Controllers\Peminjam\PeminjamanController.php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Alat;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        // Filter berdasarkan status jika ada
        $status = $request->input('status');

        $peminjamans = Peminjaman::with('user')
            ->where('user_id', Auth::id())
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(5)
            ->withQueryString();

        // Hitung statistik sesuai dengan status yang baru
        $totalPeminjaman = Peminjaman::where('user_id', Auth::id())->count();
        $menungguPeminjaman = Peminjaman::where('user_id', Auth::id())
            ->where('status', 'menunggu_peminjaman')
            ->count();
        $menungguPengembalian = Peminjaman::where('user_id', Auth::id())
            ->where('status', 'menunggu_pengembalian')
            ->count();
        $dipinjamPeminjaman = Peminjaman::where('user_id', Auth::id())
            ->where('status', 'dipinjam')
            ->count();
        $selesaiPeminjaman = Peminjaman::where('user_id', Auth::id())
            ->where('status', 'selesai')
            ->count();
        $ditolakPeminjaman = Peminjaman::where('user_id', Auth::id())
            ->where('status', 'ditolak')
            ->count();
        $ditegurPeminjaman = Peminjaman::where('user_id', Auth::id())
            ->where('status', 'ditegur')
            ->count();

        return view('peminjam.peminjaman', compact(
            'peminjamans',
            'totalPeminjaman',
            'menungguPeminjaman',
            'menungguPengembalian',
            'dipinjamPeminjaman',
            'selesaiPeminjaman',
            'ditolakPeminjaman',
            'ditegurPeminjaman'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'alat_ids' => 'required|array'
        ]);

        // Ambil nama alat
        $alats = Alat::whereIn('id', $request->alat_ids)
            ->pluck('nama_alat')
            ->toArray();

        // Simpan peminjaman dengan status menunggu_peminjaman
        $peminjaman = Peminjaman::create([
            'user_id' => Auth::id(),
            'alat_ids' => $request->alat_ids,
            'nama_alat' => implode(', ', $alats),
            'tanggal_peminjaman' => now(),
            'status' => 'menunggu_peminjaman' // PERUBAHAN
        ]);

        // Simpan log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'role' => Auth::user()->role,
            'aktivitas' => 'Mengajukan peminjaman: ' . implode(', ', $alats),
            'modul' => 'peminjaman'
        ]);

        return redirect()->back()->with('success', 'Peminjaman berhasil diajukan');
    }

   public function kembalikan($id)
    {
        try {
            $peminjaman = Peminjaman::findOrFail($id);
            
            // Validasi hanya yang sedang dipinjam yang bisa dikembalikan
            if ($peminjaman->status !== 'dipinjam') {
                return response()->json([
                    'success' => false,
                    'message' => 'Peminjaman tidak dapat dikembalikan'
                ], 400);
            }
            
            // Ubah status ke menunggu_pengembalian (menunggu konfirmasi dari admin)
            $peminjaman->status = 'menunggu_pengembalian'; // PERUBAHAN
            $peminjaman->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Pengajuan pengembalian berhasil. Menunggu konfirmasi admin.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}