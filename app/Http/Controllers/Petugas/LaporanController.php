<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Alat;
use App\Models\User;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->input('period', 'monthly');
        $userId = $request->input('user_id');
        $alatId = $request->input('alat_id');
        
        // Default: bulan ini
        if (!$request->filled('start_date') || !$request->filled('end_date')) {
            $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        } else {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
        }
        
        $data = $this->generateReportData($startDate, $endDate, $period, $userId, $alatId);
        
        // Ambil data untuk dropdown
        $users = $this->getUsersWithPeminjaman();
        $alats = $this->getAlatsWithPeminjaman();
        
        return view('petugas.laporan.index', compact(
            'data', 
            'startDate', 
            'endDate', 
            'period',
            'userId',
            'alatId',
            'users',
            'alats'
        ));
    }
    
    private function getUsersWithPeminjaman()
    {
        // Ambil semua user_id yang ADA di tabel peminjaman
        $userIds = Peminjaman::whereIn('status', ['selesai', 'dipinjam', 'ditegur'])
            ->pluck('user_id')
            ->unique()
            ->filter()
            ->toArray();
        
        // Ambil data user berdasarkan id tersebut
        return User::whereIn('id', $userIds)
            ->where('role', 'peminjam')
            ->select('id', 'name', 'kelas_jurusan')
            ->orderBy('name')
            ->get();
    }
    
    private function getAlatsWithPeminjaman()
    {
        $alatIds = Peminjaman::whereIn('status', ['selesai', 'dipinjam', 'ditegur'])
            ->get()
            ->pluck('alat_ids')
            ->flatten()
            ->unique()
            ->filter()
            ->toArray();
        
        // HAPUS kolom kode_alat, hanya ambil id dan nama_alat
        return Alat::whereIn('id', $alatIds)
            ->select('id', 'nama_alat')
            ->orderBy('nama_alat')
            ->get();
    }
    
    private function generateReportData($startDate, $endDate, $period, $userId = null, $alatId = null)
    {
        $peminjamanQuery = Peminjaman::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        
        if ($userId) {
            $peminjamanQuery->where('user_id', $userId);
        }
        
        if ($alatId) {
            $peminjamanQuery->whereJsonContains('alat_ids', (string) $alatId);
        }
        
        return [
            'general' => [
                'total_peminjaman' => (clone $peminjamanQuery)->count(),
                'diproses' => (clone $peminjamanQuery)->where('status', 'dipinjam')->count(),
                'selesai' => (clone $peminjamanQuery)->where('status', 'selesai')->count(),
                'ditolak' => (clone $peminjamanQuery)->where('status', 'ditolak')->count(),
                'ditegur' => (clone $peminjamanQuery)->where('status', 'ditegur')->count(),
            ],
            
            'trend' => $this->getTrendData($startDate, $endDate, $period, $userId, $alatId),
            'popular_tools' => $this->getPopularTools($startDate, $endDate, $alatId),
            'active_users' => $this->getActiveUsers($startDate, $endDate, $userId),
            'petugas_performance' => $this->getPetugasPerformance($startDate, $endDate),
            'time_stats' => $this->getTimeStatistics($startDate, $endDate, $userId, $alatId),
            'filtered_peminjamans' => $this->getFilteredPeminjamans($startDate, $endDate, $userId, $alatId),
        ];
    }
    
    private function getFilteredPeminjamans($startDate, $endDate, $userId = null, $alatId = null)
    {
        $query = Peminjaman::with('user')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        
        if ($userId) {
            $query->where('user_id', $userId);
        }
        
        if ($alatId) {
            $query->whereJsonContains('alat_ids', (string) $alatId);
        }
        
        return $query->orderBy('created_at', 'desc')->get();
    }
    
    private function getTrendData($startDate, $endDate, $period, $userId = null, $alatId = null)
    {
        $query = Peminjaman::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN status = "selesai" THEN 1 ELSE 0 END) as selesai'),
            DB::raw('SUM(CASE WHEN status = "ditolak" THEN 1 ELSE 0 END) as ditolak'),
            DB::raw('SUM(CASE WHEN status = "ditegur" THEN 1 ELSE 0 END) as ditegur')
        )
        ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
        ->groupBy('date')
        ->orderBy('date');
        
        if ($userId) {
            $query->where('user_id', $userId);
        }
        
        if ($alatId) {
            $query->whereJsonContains('alat_ids', (string) $alatId);
        }
        
        return $query->get();
    }
    
    private function getPopularTools($startDate, $endDate, $alatId = null)
    {
        $peminjamans = Peminjaman::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereIn('status', ['selesai', 'dipinjam', 'ditegur'])
            ->get();
        
        if ($alatId) {
            $peminjamans = $peminjamans->filter(function($peminjaman) use ($alatId) {
                return in_array($alatId, $peminjaman->alat_ids ?? []);
            });
        }
        
        $toolCount = [];
        
        foreach ($peminjamans as $peminjaman) {
            if (!empty($peminjaman->alat_ids)) {
                $alats = Alat::whereIn('id', $peminjaman->alat_ids)->get();
                
                foreach ($alats as $alat) {
                    if (!isset($toolCount[$alat->id])) {
                        $toolCount[$alat->id] = [
                            'alat' => $alat,
                            'count' => 0
                        ];
                    }
                    $toolCount[$alat->id]['count']++;
                }
            }
        }
        
        return collect($toolCount)
            ->sortByDesc('count')
            ->take(10)
            ->values();
    }
    
    private function getActiveUsers($startDate, $endDate, $userId = null)
    {
        $query = Peminjaman::select(
            'user_id',
            DB::raw('COUNT(*) as total_peminjaman'),
            DB::raw('SUM(CASE WHEN status = "selesai" THEN 1 ELSE 0 END) as selesai'),
            DB::raw('SUM(CASE WHEN status = "ditolak" THEN 1 ELSE 0 END) as ditolak')
        )
        ->with('user:id,name,kelas_jurusan')
        ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
        ->groupBy('user_id')
        ->orderBy('total_peminjaman', 'desc')
        ->limit(10);
        
        if ($userId) {
            $query->where('user_id', $userId);
        }
        
        return $query->get();
    }
    
    private function getPetugasPerformance($startDate, $endDate)
    {
        return LogAktivitas::select(
            'user_id',
            'role',
            DB::raw('COUNT(*) as total_actions'),
            DB::raw('SUM(CASE WHEN aktivitas LIKE "%Menyetujui%" THEN 1 ELSE 0 END) as setujui'),
            DB::raw('SUM(CASE WHEN aktivitas LIKE "%Menolak%" THEN 1 ELSE 0 END) as tolak'),
            DB::raw('SUM(CASE WHEN aktivitas LIKE "%Mengkonfirmasi%" THEN 1 ELSE 0 END) as konfirmasi'),
            DB::raw('SUM(CASE WHEN aktivitas LIKE "%Memberi teguran%" THEN 1 ELSE 0 END) as tegur')
        )
        ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
        ->where('role', 'petugas')
        ->groupBy('user_id', 'role')
        ->with('user:id,name')
        ->orderBy('total_actions', 'desc')
        ->get();
    }
    
    private function getTimeStatistics($startDate, $endDate, $userId = null, $alatId = null)
    {
        $query = Peminjaman::where('status', 'selesai')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereNotNull('tanggal_dikembalikan');
        
        if ($userId) {
            $query->where('user_id', $userId);
        }
        
        if ($alatId) {
            $query->whereJsonContains('alat_ids', (string) $alatId);
        }
        
        $peminjamans = $query->get();
        
        $totalDays = 0;
        $count = 0;
        
        foreach ($peminjamans as $peminjaman) {
            $borrowDate = Carbon::parse($peminjaman->tanggal_peminjaman);
            $returnDate = Carbon::parse($peminjaman->tanggal_dikembalikan);
            $days = $returnDate->diffInDays($borrowDate);
            
            $totalDays += $days;
            $count++;
        }
        
        return [
            'avg_borrow_days' => $count > 0 ? round($totalDays / $count, 2) : 0,
            'total_completed' => $count,
        ];
    }
    
    public function export(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $format = $request->input('format', 'pdf');
        $userId = $request->input('user_id');
        $alatId = $request->input('alat_id');
        
        $peminjamans = Peminjaman::with('user')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc');
        
        if ($userId) {
            $peminjamans->where('user_id', $userId);
        }
        
        if ($alatId) {
            $peminjamans->whereJsonContains('alat_ids', (string) $alatId);
        }
        
        $peminjamans = $peminjamans->get();
        
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'role' => Auth::user()->role,
            'aktivitas' => 'Mengekspor laporan periode ' . $startDate . ' - ' . $endDate,
            'modul' => 'laporan'
        ]);
        
        $fileName = 'laporan-peminjaman-' . $startDate . '-to-' . $endDate;
        
        if ($userId) {
            $user = User::find($userId);
            $fileName .= '-user-' . ($user->name ?? $userId);
        }
        
        if ($alatId) {
            $alat = Alat::find($alatId);
            $fileName .= '-alat-' . ($alat->nama_alat ?? $alatId);
        }
        
        if ($format === 'excel') {
            return $this->exportExcel($peminjamans, $startDate, $endDate, $fileName);
        }
        
        return $this->exportPDF($peminjamans, $startDate, $endDate, $fileName);
    }
    
    private function exportPDF($peminjamans, $startDate, $endDate, $fileName)
    {
        $pdf = Pdf::loadView('petugas.laporan.pdf', [
            'peminjamans' => $peminjamans,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
        
        return $pdf->download($fileName . '.pdf');
    }
    
    private function exportExcel($peminjamans, $startDate, $endDate, $fileName)
    {
        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=" . $fileName . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];
        
        $callback = function () use ($peminjamans) {
            $file = fopen('php://output', 'w');
            
            // BOM untuk UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header CSV - HAPUS kolom KODE ALAT
            fputcsv($file, [
                'NO',
                'ID TRANSAKSI',
                'NAMA PEMINJAM',
                'KELAS/JURUSAN',
                'NAMA ALAT',
                'TANGGAL PINJAM',
                'TANGGAL KEMBALI',
                'STATUS',
                'CATATAN'
            ]);
            
            $no = 1;
            foreach ($peminjamans as $p) {
                if (!empty($p->alat_ids) && is_array($p->alat_ids)) {
                    $alats = Alat::whereIn('id', $p->alat_ids)->get();
                    
                    if ($alats->isNotEmpty()) {
                        foreach ($alats as $alat) {
                            fputcsv($file, [
                                $no++,
                                $p->kode_peminjaman ?? 'PMJ-' . str_pad($p->id, 5, '0', STR_PAD_LEFT),
                                $p->user->name ?? '-',
                                $p->user->kelas_jurusan ?? '-',
                                $alat->nama_alat,
                                Carbon::parse($p->tanggal_peminjaman)->format('d/m/Y'),
                                $p->tanggal_dikembalikan ? Carbon::parse($p->tanggal_dikembalikan)->format('d/m/Y') : '-',
                                $this->formatStatus($p->status),
                                $p->catatan ?? '-'
                            ]);
                        }
                    } else {
                        fputcsv($file, [
                            $no++,
                            $p->kode_peminjaman ?? 'PMJ-' . str_pad($p->id, 5, '0', STR_PAD_LEFT),
                            $p->user->name ?? '-',
                            $p->user->kelas_jurusan ?? '-',
                            '-',
                            Carbon::parse($p->tanggal_peminjaman)->format('d/m/Y'),
                            $p->tanggal_dikembalikan ? Carbon::parse($p->tanggal_dikembalikan)->format('d/m/Y') : '-',
                            $this->formatStatus($p->status),
                            $p->catatan ?? '-'
                        ]);
                    }
                } else {
                    fputcsv($file, [
                        $no++,
                        $p->kode_peminjaman ?? 'PMJ-' . str_pad($p->id, 5, '0', STR_PAD_LEFT),
                        $p->user->name ?? '-',
                        $p->user->kelas_jurusan ?? '-',
                        '-',
                        Carbon::parse($p->tanggal_peminjaman)->format('d/m/Y'),
                        $p->tanggal_dikembalikan ? Carbon::parse($p->tanggal_dikembalikan)->format('d/m/Y') : '-',
                        $this->formatStatus($p->status),
                        $p->catatan ?? '-'
                    ]);
                }
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    private function formatStatus($status)
    {
        $statuses = [
            'dipinjam' => 'Sedang Dipinjam',
            'selesai' => 'Selesai',
            'ditolak' => 'Ditolak',
            'ditegur' => 'Ditegur',
            'menunggu' => 'Menunggu Konfirmasi'
        ];
        
        return $statuses[$status] ?? ucfirst($status);
    }
}