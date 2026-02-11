<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Alat;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

        // Hitung statistik
        $totalPeminjaman = Peminjaman::where('user_id', Auth::id())->count();
        $menungguPeminjaman = Peminjaman::where('user_id', Auth::id())
            ->where('status', 'menunggu_peminjaman')
            ->count();
        $menungguVerifikasi = Peminjaman::where('user_id', Auth::id())
            ->where('status', 'menunggu_verifikasi')
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

        // CEK APAKAH ADA BARANG YANG SEDANG DIPINJAM
        $cekBarangDipinjam = Peminjaman::where('user_id', Auth::id())
            ->where('status', 'dipinjam')
            ->exists();

        return view('peminjam.peminjaman', compact(
            'peminjamans',
            'totalPeminjaman',
            'menungguPeminjaman',
            'menungguVerifikasi',
            'dipinjamPeminjaman',
            'selesaiPeminjaman',
            'ditolakPeminjaman',
            'ditegurPeminjaman',
            'cekBarangDipinjam' // KIRIM VARIABLE INI KE VIEW
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
            'status' => 'menunggu_peminjaman'
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
            
            // Pengembalian manual
            $peminjaman->status = 'menunggu_verifikasi';
            $peminjaman->jenis_pengembalian = 'manual';
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

    /**
     * Get daftar barang yang sedang dipinjam untuk pengembalian mandiri
     * Bisa filter berdasarkan status ditegur
     */
    public function getBarangDipinjam(Request $request)
    {
        try {
            $filterTeguran = $request->input('filter_teguran');
            
            $query = Peminjaman::where('user_id', Auth::id());
            
            if ($filterTeguran === 'ditegur') {
                // Ambil peminjaman yang ditegur dan masih dipinjam (belum diverifikasi)
                $query->where('status', 'ditegur')
                      ->where('jenis_pengembalian', 'mandiri');
            } else {
                // Ambil peminjaman yang sedang dipinjam
                $query->where('status', 'dipinjam');
            }
            
            $peminjamans = $query->orderBy('created_at', 'desc')->get();
            
            $result = [];
            foreach ($peminjamans as $peminjaman) {
                $result[] = [
                    'id' => $peminjaman->id,
                    'nama_alat' => $peminjaman->nama_alat,
                    'alat_ids' => $peminjaman->alat_ids,
                    'tanggal_peminjaman' => $peminjaman->tanggal_peminjaman,
                    'tanggal_peminjaman_formatted' => $peminjaman->tanggal_peminjaman->format('d/m/Y'),
                    'tanggal_pengembalian' => $peminjaman->tanggal_pengembalian,
                    'tanggal_pengembalian_formatted' => $peminjaman->tanggal_pengembalian->format('d/m/Y'),
                    'status' => $peminjaman->status,
                    'jenis_pengembalian' => $peminjaman->jenis_pengembalian,
                    'is_ditegur' => $peminjaman->status === 'ditegur',
                    'teks_teguran' => $peminjaman->getTeksTeguran(),
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $result,
                'total' => count($result)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Proses pengembalian mandiri - FOTO LANGSUNG KE TABLE PEMINJAMAN
     */
    public function pengembalianMandiri(Request $request)
    {
        try {
            $request->validate([
                'foto' => 'required',
                'barang' => 'required|json'
            ]);

            $barangDikembalikan = json_decode($request->barang, true);
            $fotoBase64 = $request->foto;
            
            // Decode base64 image
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $fotoBase64));
            
            // Group by peminjaman_id
            $groupedByPeminjaman = [];
            foreach ($barangDikembalikan as $item) {
                $peminjamanId = $item['peminjaman_id'];
                if (!isset($groupedByPeminjaman[$peminjamanId])) {
                    $groupedByPeminjaman[$peminjamanId] = [
                        'alat_ids' => []
                    ];
                }
                $groupedByPeminjaman[$peminjamanId]['alat_ids'][] = $item['alat_id'];
            }

            // Proses setiap peminjaman
            foreach ($groupedByPeminjaman as $peminjamanId => $data) {
                $peminjaman = Peminjaman::find($peminjamanId);
                
                if ($peminjaman && $peminjaman->user_id == Auth::id()) {
                    
                    // Generate unique filename
                    $fileName = 'bukti_pengembalian/' . Auth::id() . '/' . $peminjamanId . '_' . time() . '.jpg';
                    
                    // Simpan foto ke storage
                    Storage::disk('public')->put($fileName, $imageData);
                    
                    // UPDATE LANGSUNG KE TABLE PEMINJAMAN
                    $peminjaman->foto_bukti = $fileName;
                    $peminjaman->status = 'menunggu_verifikasi';
                    $peminjaman->jenis_pengembalian = 'mandiri';
                    $peminjaman->save();
                }
            }

            // Log aktivitas
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'role' => Auth::user()->role,
                'aktivitas' => 'Melakukan pengembalian mandiri untuk ' . count($barangDikembalikan) . ' barang',
                'modul' => 'pengembalian_mandiri',
                'data' => json_encode([
                    'barang' => $barangDikembalikan
                ])
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Foto bukti pengembalian berhasil dikirim. Menunggu verifikasi admin.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Mendapatkan detail teguran untuk peminjaman tertentu
     */
    public function getDetailTeguran($id)
    {
        try {
            $peminjaman = Peminjaman::with('petugasTeguran')
                ->where('user_id', Auth::id())
                ->findOrFail($id);
            
            if (!$peminjaman->hasTeguran()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada teguran'
                ]);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'teks_teguran' => $peminjaman->getTeksTeguran(),
                    'tanggal_teguran' => $peminjaman->teguran_dikirim_at ? 
                        $peminjaman->teguran_dikirim_at->format('d/m/Y H:i') : null,
                    'petugas' => $peminjaman->petugasTeguran ? $peminjaman->petugasTeguran->name : 'Petugas'
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat teguran: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Cek apakah user memiliki barang yang sedang dipinjam
     */
    public function cekBarangDipinjam()
    {
        try {
            $adaBarangDipinjam = Peminjaman::where('user_id', Auth::id())
                ->where('status', 'dipinjam')
                ->exists();
            
            $adaBarangDitegur = Peminjaman::where('user_id', Auth::id())
                ->where('status', 'ditegur')
                ->where('jenis_pengembalian', 'mandiri')
                ->exists();
            
            return response()->json([
                'success' => true,
                'ada_barang_dipinjam' => $adaBarangDipinjam,
                'ada_barang_ditegur' => $adaBarangDitegur,
                'bisa_pengembalian' => $adaBarangDipinjam || $adaBarangDitegur
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal cek status: ' . $e->getMessage()
            ], 500);
        }
    }
}