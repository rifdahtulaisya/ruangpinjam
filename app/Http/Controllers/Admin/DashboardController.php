<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Alat;
use App\Models\User;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // ============ CEK KONEKSI DATABASE ============
        try {
            DB::connection()->getPdo();
            $dbConnected = true;
        } catch (\Exception $e) {
            $dbConnected = false;
        }

        // ============ STATISTIK CARD ============
        $totalPeminjaman = Peminjaman::count(); // Total semua peminjaman
        
        // BULAN INI (untuk card pertama)
        $peminjamanBulanIni = Peminjaman::whereMonth('created_at', Carbon::now()->month)
                                        ->whereYear('created_at', Carbon::now()->year)
                                        ->count();
        
        // Status sesuai model
        $menungguPeminjaman = Peminjaman::where('status', 'menunggu_peminjaman')->count();
        $dipinjam = Peminjaman::where('status', 'dipinjam')->count();
        $selesai = Peminjaman::where('status', 'selesai')->count();
        $ditolak = Peminjaman::where('status', 'ditolak')->count();
        $ditegur = Peminjaman::where('status', 'ditegur')->count();

        // ============ PERSENTASE PERUBAHAN ============
        $bulanIni = Peminjaman::whereMonth('created_at', Carbon::now()->month)
                              ->whereYear('created_at', Carbon::now()->year)
                              ->count();
        
        $bulanLalu = Peminjaman::whereMonth('created_at', Carbon::now()->subMonth()->month)
                               ->whereYear('created_at', Carbon::now()->subMonth()->year)
                               ->count();
        
        $persentasePerubahan = 0;
        if ($bulanLalu > 0) {
            $persentasePerubahan = round((($bulanIni - $bulanLalu) / $bulanLalu) * 100);
        }

        // ============ DATA CHART ============
        $tahun = $request->get('tahun', Carbon::now()->year);
        $chartData = $this->getChartData($tahun);

        // ============ LOG AKTIVITAS TERBARU ============
        $aktivitasTerbaru = LogAktivitas::with('user')
                                        ->orderBy('created_at', 'desc')
                                        ->limit(10)
                                        ->get();

        // ============ TOP ALAT ============
        $topAlat = $this->getTopAlat();

        // ============ STATISTIK PENGGUNA ============
        $totalUser = User::where('role', 'user')->count();
        
        $userAktif = Peminjaman::where('status', 'dipinjam')
                    ->distinct('user_id')
                    ->count('user_id');
                    
        $userBaru = User::where('role', 'user')
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->count();

        // ============ PEMINJAMAN TERLAMBAT ============
        $peminjamanTerlambat = Peminjaman::where('status', 'dipinjam')
                                ->whereDate('tanggal_pengembalian', '<', Carbon::now())
                                ->count();

        // ============ STATISTIK LOG ============
        $totalLogAktivitas = LogAktivitas::count();
        $logHariIni = LogAktivitas::whereDate('created_at', Carbon::today())->count();

        // ============ DEBUGGING ============
        // Uncomment baris di bawah untuk cek data
        // dd(compact('totalPeminjaman', 'peminjamanBulanIni', 'menungguPeminjaman', 'dipinjam', 'selesai'));

        return view('admin.dashboard', compact(
            'totalPeminjaman',
            'peminjamanBulanIni', // TAMBAHKAN INI
            'menungguPeminjaman',
            'dipinjam',
            'selesai',
            'ditolak',
            'ditegur',
            'persentasePerubahan',
            'chartData',
            'aktivitasTerbaru',
            'topAlat',
            'totalUser',
            'userAktif',
            'userBaru',
            'peminjamanTerlambat',
            'totalLogAktivitas',
            'logHariIni',
            'tahun',
            'dbConnected'
        ));
    }

    private function getChartData($tahun)
    {
        $bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $dataPeminjaman = [];
        $dataPengembalian = [];

        foreach (range(1, 12) as $month) {
            // Peminjaman bulan ini
            $countPeminjaman = Peminjaman::whereMonth('created_at', $month)
                               ->whereYear('created_at', $tahun)
                               ->count();
            $dataPeminjaman[] = $countPeminjaman;

            // Pengembalian bulan ini (status selesai)
            $countPengembalian = Peminjaman::whereMonth('tanggal_dikembalikan', $month)
                                ->whereYear('tanggal_dikembalikan', $tahun)
                                ->where('status', 'selesai')
                                ->count();
            $dataPengembalian[] = $countPengembalian;
        }

        return [
            'bulan' => $bulan,
            'peminjaman' => $dataPeminjaman,
            'pengembalian' => $dataPengembalian,
            'tahun' => $tahun
        ];
    }

    private function getTopAlat()
    {
        try {
            // Ambil semua peminjaman dengan status selesai
            $peminjamanSelesai = Peminjaman::where('status', 'selesai')->get();
            
            $alatCount = [];
            
            foreach ($peminjamanSelesai as $peminjaman) {
                if ($peminjaman->alat_ids && is_array($peminjaman->alat_ids)) {
                    foreach ($peminjaman->alat_ids as $alatId) {
                        if (!isset($alatCount[$alatId])) {
                            $alatCount[$alatId] = 0;
                        }
                        $alatCount[$alatId]++;
                    }
                }
            }

            // Urutkan dari yang terbesar
            arsort($alatCount);
            
            // Ambil top 5
            $topAlatIds = array_slice(array_keys($alatCount), 0, 5, true);
            $topAlat = [];
            
            foreach ($topAlatIds as $alatId) {
                $alat = Alat::find($alatId);
                if ($alat) {
                    $topAlat[] = [
                        'alat' => $alat,
                        'total' => $alatCount[$alatId]
                    ];
                }
            }

            return $topAlat;
        } catch (\Exception $e) {
            return [];
        }
    }
}