<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class KelolaPeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');
        
        $peminjamans = Peminjaman::with('user')
            ->when($status && $status !== 'semua', function($query) use ($status) {
                return $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(5)
            ->withQueryString();

        $counts = [
            'menunggu_peminjaman' => Peminjaman::where('status', 'menunggu_peminjaman')->count(),
            'menunggu_verifikasi' => Peminjaman::where('status', 'menunggu_verifikasi')->count(),
            'dipinjam' => Peminjaman::where('status', 'dipinjam')->count(),
            'selesai' => Peminjaman::where('status', 'selesai')->count(),
            'ditolak' => Peminjaman::where('status', 'ditolak')->count(),
            'ditegur' => Peminjaman::where('status', 'ditegur')->count(),
        ];
        
        return view('petugas.kelolapeminjaman', compact('peminjamans', 'counts'));
    }

    public function detail($id)
    {
        $peminjaman = Peminjaman::with(['user', 'petugasTeguran'])->findOrFail($id);
        
        return response()->json([
            'id' => $peminjaman->id,
            'user' => [
                'name' => $peminjaman->user->name ?? 'Tidak diketahui',
                'email' => $peminjaman->user->email ?? '-',
                'kelas' => $peminjaman->user->kelas ?? '-',
                'phone' => $peminjaman->user->phone ?? '-',
            ],
            'alat' => $peminjaman->alatList->map(function($alat) {
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
            'keterangan' => $peminjaman->keterangan,
            'foto_bukti' => $peminjaman->foto_bukti_url,
            'jenis_pengembalian' => $peminjaman->jenis_pengembalian,
            'created_at' => $peminjaman->created_at->format('d/m/Y H:i'),
            'tanggal_peminjaman' => $peminjaman->tanggal_peminjaman->format('d/m/Y'),
            'tanggal_pengembalian' => $peminjaman->tanggal_pengembalian->format('d/m/Y'),
            'tanggal_dikembalikan' => $peminjaman->tanggal_dikembalikan 
                ? $peminjaman->tanggal_dikembalikan->format('d/m/Y H:i') 
                : null,
            'teguran' => $peminjaman->hasTeguran() ? [
                'teks' => $peminjaman->getTeksTeguran(),
                'dikirim_at' => $peminjaman->teguran_dikirim_at ? 
                    $peminjaman->teguran_dikirim_at->format('d/m/Y H:i') : null,
                'petugas' => $peminjaman->petugasTeguran ? $peminjaman->petugasTeguran->name : null
            ] : null
        ]);
    }

    public function konfirmasiPengembalian($id)
{
    try {
        $peminjaman = Peminjaman::findOrFail($id);
        
        if (!in_array($peminjaman->status, ['dipinjam', 'menunggu_verifikasi', 'ditegur'])) {
            return response()->json([
                'success' => false,
                'message' => 'Peminjaman tidak dalam status yang dapat dikonfirmasi'
            ], 400);
        }
        
        $user = $peminjaman->user;
        
        $peminjaman->status = 'selesai';
        $peminjaman->save();
        
        // ✅ CEK DAN UNBLOCK OTOMATIS JIKA SEMUA PEMINJAMAN TELAT SUDAH SELESAI
        if ($user && $user->is_blocked) {
            $masihTelat = Peminjaman::where('user_id', $user->id)
                ->where('status', 'dipinjam')
                ->whereDate('tanggal_pengembalian', '<', Carbon::today())
                ->exists();
            
            if (!$masihTelat) {
                $user->unblock("Akun dipulihkan otomatis - Semua peminjaman telat telah dikonfirmasi oleh petugas");
                
                // HAPUS baris ini karena $this->command hanya tersedia di Console
                // $this->command?->info("✅ User {$user->name} otomatis di-unblock!");
            }
        }
        
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'role' => Auth::user()->role,
            'aktivitas' => 'Mengkonfirmasi pengembalian peminjaman #' . $id,
            'modul' => 'pengembalian'
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Pengembalian berhasil dikonfirmasi.' . 
                        ($user->is_blocked ? ' Status blokir akun akan diperiksa.' : '')
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}

    public function setujuiPeminjaman($id)
    {
        try {
            $peminjaman = Peminjaman::findOrFail($id);
            
            if ($peminjaman->status !== 'menunggu_peminjaman') {
                return response()->json([
                    'success' => false,
                    'message' => 'Peminjaman tidak dalam status menunggu persetujuan'
                ], 400);
            }
            
            $peminjaman->status = 'dipinjam';
            $peminjaman->save();
            
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'role' => Auth::user()->role,
                'aktivitas' => 'Menyetujui peminjaman #' . $id,
                'modul' => 'peminjaman'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Peminjaman berhasil disetujui.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function tolakPeminjaman(Request $request, $id)
    {
        try {
            $request->validate([
                'alasan' => 'required|string|max:500',
            ]);

            $peminjaman = Peminjaman::findOrFail($id);
            
            if ($peminjaman->status !== 'menunggu_peminjaman') {
                return response()->json([
                    'success' => false,
                    'message' => 'Peminjaman tidak dalam status menunggu persetujuan'
                ], 400);
            }
            
            $peminjaman->status = 'ditolak';
            $peminjaman->keterangan = $request->alasan;
            $peminjaman->save();
            
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'role' => Auth::user()->role,
                'aktivitas' => 'Menolak peminjaman #' . $id,
                'modul' => 'peminjaman'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Peminjaman berhasil ditolak'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Kirim teguran untuk pengembalian mandiri
     * Button teguran hanya muncul jika peminjaman adalah pengembalian mandiri
     */
    public function tegur(Request $request, $id)
    {
        try {
            $request->validate([
                'alasan' => 'required|string',
                'deskripsi' => 'required|string|max:500',
            ]);
            
            $peminjaman = Peminjaman::findOrFail($id);
            
            // Cek apakah ini pengembalian mandiri?
            // Button teguran hanya muncul untuk pengembalian mandiri
            if ($peminjaman->jenis_pengembalian !== 'mandiri') {
                return response()->json([
                    'success' => false,
                    'message' => 'Teguran hanya dapat diberikan untuk pengembalian mandiri'
                ], 400);
            }
            
            // Simpan teguran
            $peminjaman->status = 'ditegur';
            $peminjaman->keterangan = 'TEGURAN: ' . $request->alasan . ' - ' . $request->deskripsi;
            $peminjaman->teguran_dikirim_at = now();
            $peminjaman->petugas_id_teguran = Auth::id();
            $peminjaman->save();
            
            // Log aktivitas
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'role' => Auth::user()->role,
                'aktivitas' => 'Memberi teguran pada peminjaman #' . $id . ' (Pengembalian Mandiri)',
                'modul' => 'pengembalian_mandiri'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Teguran berhasil dikirim. Peminjam akan melihat teguran dan diminta foto ulang.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload foto bukti (manual dari petugas)
     */
    public function uploadFotoBukti(Request $request, $id)
    {
        $request->validate([
            'foto_bukti' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $peminjaman = Peminjaman::findOrFail($id);
        
        $path = $request->file('foto_bukti')->store('foto-pengembalian', 'public');
        
        $peminjaman->foto_bukti = $path;
        $peminjaman->save();
            
        return response()->json([
            'success' => true,
            'message' => 'Foto bukti berhasil diupload',
            'foto_url' => asset('storage/' . $path)
        ]);
    }

    /**
     * Verifikasi pengembalian mandiri dan konfirmasi
     */
    public function verifikasiMandiri(Request $request, $id)
    {
        try {
            $peminjaman = Peminjaman::findOrFail($id);
            
            if ($peminjaman->status !== 'menunggu_verifikasi') {
                return response()->json([
                    'success' => false,
                    'message' => 'Peminjaman tidak dalam status menunggu verifikasi'
                ], 400);
            }
            
            // Jika ini pengembalian mandiri yang ditegur, reset status
            if ($peminjaman->jenis_pengembalian === 'mandiri' && $peminjaman->status === 'ditegur') {
                $peminjaman->status = 'dipinjam';
                $peminjaman->save();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Status dikembalikan ke Dipinjam. Peminjam dapat melakukan foto ulang.'
                ]);
            }
            
            // Verifikasi biasa
            $peminjaman->status = 'selesai';
            $peminjaman->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Pengembalian mandiri berhasil diverifikasi'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
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
        
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'role' => Auth::user()->role,
            'aktivitas' => 'Mengekspor data peminjaman',
            'modul' => 'peminjaman'
        ]);
        
        return response()->json([
            'success' => true,
            'data' => $peminjamans,
            'count' => $peminjamans->count()
        ]);
    }
}