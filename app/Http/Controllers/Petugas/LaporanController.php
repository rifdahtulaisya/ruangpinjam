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
        $period = $request->input('period', 'monthly'); // daily, weekly, monthly, yearly
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        // Default: bulan ini
        if (!$startDate && !$endDate) {
            $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        }
        
        $data = $this->generateReportData($startDate, $endDate, $period);
        
        return view('petugas.laporan.index', compact('data', 'startDate', 'endDate', 'period'));
    }
    
    private function generateReportData($startDate, $endDate, $period)
    {
        return [
            // 1. Statistik Umum
            'general' => [
                'total_peminjaman' => Peminjaman::whereBetween('created_at', [$startDate, $endDate])->count(),
                'diproses' => Peminjaman::whereBetween('created_at', [$startDate, $endDate])
                    ->where('status', 'dipinjam')->count(),
                'selesai' => Peminjaman::whereBetween('created_at', [$startDate, $endDate])
                    ->where('status', 'selesai')->count(),
                'ditolak' => Peminjaman::whereBetween('created_at', [$startDate, $endDate])
                    ->where('status', 'ditolak')->count(),
                'ditegur' => Peminjaman::whereBetween('created_at', [$startDate, $endDate])
                    ->where('status', 'ditegur')->count(),
            ],
            
            // 2. Tren Harian/Bulanan
            'trend' => $this->getTrendData($startDate, $endDate, $period),
            
            // 3. Alat Populer
            'popular_tools' => $this->getPopularTools($startDate, $endDate),
            
            // 4. Peminjam Aktif
            'active_users' => $this->getActiveUsers($startDate, $endDate),
            
            // 5. Kinerja Petugas (berdasarkan log)
            'petugas_performance' => $this->getPetugasPerformance($startDate, $endDate),
            
            // 6. Statistik Waktu
            'time_stats' => $this->getTimeStatistics($startDate, $endDate),
        ];
    }
    
    private function getTrendData($startDate, $endDate, $period)
    {
        $query = Peminjaman::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN status = "selesai" THEN 1 ELSE 0 END) as selesai'),
            DB::raw('SUM(CASE WHEN status = "ditolak" THEN 1 ELSE 0 END) as ditolak'),
            DB::raw('SUM(CASE WHEN status = "ditegur" THEN 1 ELSE 0 END) as ditegur')
        )
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupBy('date')
        ->orderBy('date');
        
        return $query->get();
    }
    
    private function getPopularTools($startDate, $endDate)
    {
        // Menggunakan relasi alat pada peminjaman
        $peminjamans = Peminjaman::whereBetween('created_at', [$startDate, $endDate])
    ->whereIn('status', ['selesai', 'dipinjam'])
    ->get();
        
        $toolCount = [];

foreach ($peminjamans as $peminjaman) {

    $alats = Alat::whereIn('id', $peminjaman->alat_ids ?? [])->get();

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

return collect($toolCount)
    ->sortByDesc('count')
    ->take(10)
    ->values();

        
        // Urutkan berdasarkan jumlah peminjaman
        usort($toolCount, function($a, $b) {
            return $b['count'] <=> $a['count'];
        });
        
        return array_slice($toolCount, 0, 10); // Ambil 10 teratas
    }
    
    private function getActiveUsers($startDate, $endDate)
    {
        return Peminjaman::select(
            'user_id',
            DB::raw('COUNT(*) as total_peminjaman'),
            DB::raw('SUM(CASE WHEN status = "selesai" THEN 1 ELSE 0 END) as selesai'),
            DB::raw('SUM(CASE WHEN status = "ditolak" THEN 1 ELSE 0 END) as ditolak')
        )
        ->with('user:id,name,kelas_jurusan')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupBy('user_id')
        ->orderBy('total_peminjaman', 'desc')
        ->limit(10)
        ->get();
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
        ->whereBetween('created_at', [$startDate, $endDate])
        ->where('role', 'petugas')
        ->groupBy('user_id', 'role')
        ->with('user:id,name')
        ->orderBy('total_actions', 'desc')
        ->get();
    }
    
    private function getTimeStatistics($startDate, $endDate)
    {
        $peminjamans = Peminjaman::where('status', 'selesai')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('tanggal_dikembalikan')
            ->get();
        
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
        $format = $request->input('format', 'pdf'); // pdf, excel
        
        $data = $this->generateReportData($startDate, $endDate, 'monthly');
        
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'role' => Auth::user()->role,
            'aktivitas' => 'Mengekspor laporan periode ' . $startDate . ' - ' . $endDate,
            'modul' => 'laporan'
        ]);

        
        if ($format === 'excel') {
            return $this->exportExcel($data, $startDate, $endDate);
        }
        
        return $this->exportPDF($data, $startDate, $endDate);
    }
    
    private function exportPDF($data, $startDate, $endDate)
{
    $peminjamans = Peminjaman::with('user')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->orderBy('created_at', 'desc')
        ->get();

    $pdf = Pdf::loadView('petugas.laporan.pdf', [
        'peminjamans' => $peminjamans,
        'startDate' => $startDate,
        'endDate' => $endDate
    ]);

    return $pdf->download('laporan-peminjaman.pdf');
}

    
    private function exportExcel($data, $startDate, $endDate)
{
    $peminjamans = Peminjaman::with('user')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->get();

    $filename = 'laporan-peminjaman.csv';

    $headers = [
        "Content-type" => "text/csv",
        "Content-Disposition" => "attachment; filename=$filename",
    ];

    $callback = function () use ($peminjamans) {

        $file = fopen('php://output', 'w');

        fputcsv($file, [
            'ID',
            'Nama Peminjam',
            'Alat',
            'Tanggal Pinjam',
            'Tanggal Kembali',
            'Status'
        ]);

        foreach ($peminjamans as $p) {

            $alatNames = Alat::whereIn('id', $p->alat_ids ?? [])
                ->pluck('nama_alat')
                ->implode(', ');

            fputcsv($file, [
                $p->id,
                $p->user->name ?? '-',
                $alatNames,
                $p->tanggal_peminjaman,
                $p->tanggal_dikembalikan,
                $p->status
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

}