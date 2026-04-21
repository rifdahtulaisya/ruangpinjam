@extends('layouts-admin.admin')

@section('title', 'DASHBOARD')

@section('content')

@php
    // Ambil data LANGSUNG dari database
    use App\Models\Peminjaman;
    use Carbon\Carbon;
    
    // Ambil tahun dari request (default tahun sekarang)
    $tahunDipilih = request()->get('tahun', Carbon::now()->year);
    
    // Total peminjaman bulan terbaru (bulan ini)
    $totalPeminjamanBulanIni = Peminjaman::whereMonth('created_at', Carbon::now()->month)
                                        ->whereYear('created_at', Carbon::now()->year)
                                        ->count();
    
    // Total seluruh peminjaman
    $totalSeluruhPeminjaman = Peminjaman::count();
    
    // Menunggu persetujuan
    $menungguPersetujuan = Peminjaman::where('status', 'menunggu_peminjaman')->count();
    
    // Sedang dipinjam
    $sedangDipinjam = Peminjaman::where('status', 'dipinjam')->count();
    
    // Terlambat (sedang dipinjam dan melewati tanggal pengembalian)
    $peminjamanTerlambat = Peminjaman::where('status', 'dipinjam')
                            ->whereDate('tanggal_pengembalian', '<', Carbon::now())
                            ->count();
    
    // Hitung persentase perubahan dari bulan lalu
    $bulanLalu = Peminjaman::whereMonth('created_at', Carbon::now()->subMonth()->month)
                           ->whereYear('created_at', Carbon::now()->subMonth()->year)
                           ->count();
    
    $persentasePerubahan = 0;
    if ($bulanLalu > 0) {
        $persentasePerubahan = round((($totalPeminjamanBulanIni - $bulanLalu) / $bulanLalu) * 100);
    }
    
    // Data chart per BULAN untuk tahun yang dipilih
    $bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    $dataPeminjaman = [];
    $dataPengembalian = [];
    
    for ($month = 1; $month <= 12; $month++) {
        // Peminjaman per bulan di tahun yang dipilih
        $countPeminjaman = Peminjaman::whereMonth('created_at', $month)
                           ->whereYear('created_at', $tahunDipilih)
                           ->count();
        $dataPeminjaman[] = $countPeminjaman;
        
        // Pengembalian per bulan (status selesai) di tahun yang dipilih
        $countPengembalian = Peminjaman::whereMonth('tanggal_dikembalikan', $month)
                            ->whereYear('tanggal_dikembalikan', $tahunDipilih)
                            ->where('status', 'selesai')
                            ->count();
        $dataPengembalian[] = $countPengembalian;
    }
    
    // Daftar tahun yang tersedia untuk dropdown (dari data peminjaman)
    $tahunTersedia = Peminjaman::selectRaw('YEAR(created_at) as tahun')
                        ->distinct()
                        ->orderBy('tahun', 'desc')
                        ->pluck('tahun')
                        ->toArray();
    
    // Jika tidak ada data, set default tahun sekarang
    if (empty($tahunTersedia)) {
        $tahunTersedia = [Carbon::now()->year];
    }
    
    // Cari nilai maksimal untuk skala chart
    $maxValue = max(max($dataPeminjaman), max($dataPengembalian));
    if ($maxValue == 0) $maxValue = 10;
    $stepSize = ceil($maxValue / 5);
    if ($stepSize < 1) $stepSize = 1;
@endphp

<!-- STATISTIK CARD - HANYA 3 CARD -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
    <!-- Card 1: Total Peminjaman Bulan Terbaru -->
    <div class="group bg-white rounded-xl p-6 shadow hover:shadow-xl transition">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-slate-400 text-sm">Peminjaman Bulan Ini</p>
                <h2 class="text-3xl font-bold text-slate-800 mt-1">{{ number_format($totalPeminjamanBulanIni) }}</h2>
                <p class="text-xs text-slate-500 mt-1">Total: {{ number_format($totalSeluruhPeminjaman) }}</p>
            </div>
            <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-blue-100 text-blue-600 group-hover:scale-110 transition">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
        </div>
    </div>

    <!-- Card 2: Menunggu Persetujuan -->
    <div class="group bg-white rounded-xl p-6 shadow hover:shadow-xl transition">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-slate-400 text-sm">Menunggu Persetujuan</p>
                <h2 class="text-3xl font-bold text-slate-800 mt-1">{{ number_format($menungguPersetujuan) }}</h2>
            </div>
            <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-yellow-100 text-yellow-600 group-hover:scale-110 transition">
                <i class="fa-solid fa-hourglass-half"></i>
            </div>
        </div>
        <div class="flex items-center mt-4">
            <span class="px-2 py-1 bg-yellow-100 text-yellow-600 rounded-full text-xs font-medium">
                Perlu persetujuan
            </span>
        </div>
    </div>

    <!-- Card 3: Sedang Dipinjam -->
    <div class="group bg-white rounded-xl p-6 shadow hover:shadow-xl transition">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-slate-400 text-sm">Sedang Dipinjam</p>
                <h2 class="text-3xl font-bold text-slate-800 mt-1">{{ number_format($sedangDipinjam) }}</h2>
            </div>
            <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-green-100 text-green-600 group-hover:scale-110 transition">
                <i class="fa-solid fa-play"></i>
            </div>
        </div>
        <div class="flex items-center justify-between mt-4">
            <span class="px-2 py-1 bg-red-100 text-red-600 rounded-full text-xs font-medium">
                {{ number_format($peminjamanTerlambat) }} Terlambat
            </span>
        </div>
    </div>
</div>

<!-- DIAGRAM BULANAN (BAR CHART) dengan Pilihan Tahun -->
<div class="bg-white rounded-xl p-6 shadow">
    <div class="flex flex-wrap justify-between items-center mb-6">
        <div>
            <h3 class="font-semibold text-slate-700 text-lg">
                Statistik Peminjaman & Pengembalian Per Bulan
            </h3>
            <p class="text-xs text-slate-400 mt-1">Data peminjaman dan pengembalian per bulan</p>
        </div>
        <div class="flex items-center gap-4">
            <!-- Dropdown Pilih Tahun -->
            <form method="GET" action="{{ url()->current() }}" id="formTahun" class="flex items-center gap-2">
                <label class="text-sm text-slate-600 font-medium">Tahun:</label>
                <select name="tahun" onchange="this.form.submit()" 
                        class="px-3 pr-8 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white cursor-pointer">
                    @foreach($tahunTersedia as $tahun)
                        <option value="{{ $tahun }}" {{ $tahunDipilih == $tahun ? 'selected' : '' }}>
                            {{ $tahun }}
                        </option>
                    @endforeach
                </select>
            </form>
            <div class="flex items-center gap-3 text-xs">
                <div class="flex items-center">
                    <span class="w-3 h-3 bg-blue-500 rounded-full mr-1"></span>
                    <span class="text-slate-600">Peminjaman</span>
                </div>
                <div class="flex items-center">
                    <span class="w-3 h-3 bg-green-500 rounded-full mr-1"></span>
                    <span class="text-slate-600">Pengembalian</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Canvas untuk Bar Chart Bulanan -->
    <canvas id="chartBulanan" height="120"></canvas>
    
    <!-- Tabel ringkasan data per bulan -->
    <div class="mt-6 overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50">
                    <th class="text-left py-2 px-3 text-slate-500 font-medium rounded-l-lg">Bulan</th>
                    <th class="text-right py-2 px-3 text-slate-500 font-medium">Peminjaman</th>
                    <th class="text-right py-2 px-3 text-slate-500 font-medium">Pengembalian</th>
                    <th class="text-right py-2 px-3 text-slate-500 font-medium rounded-r-lg">Selisih</th>
                 </tr>
            </thead>
            <tbody>
                @foreach($bulanLabels as $index => $bulan)
                @php
                    $peminjaman = $dataPeminjaman[$index];
                    $pengembalian = $dataPengembalian[$index];
                    $selisih = $peminjaman - $pengembalian;
                @endphp
                <tr class="border-b border-slate-100 hover:bg-slate-50 transition">
                    <td class="py-2 px-3 font-medium text-slate-700">{{ $bulan }}</td>
                    <td class="text-right py-2 px-3 text-blue-600 font-semibold">{{ number_format($peminjaman) }}</td>
                    <td class="text-right py-2 px-3 text-green-600 font-semibold">{{ number_format($pengembalian) }}</td>
                    <td class="text-right py-2 px-3 {{ $selisih > 0 ? 'text-orange-500' : ($selisih < 0 ? 'text-green-500' : 'text-slate-400') }}">
                        {{ $selisih > 0 ? '+' : '' }}{{ number_format($selisih) }}
                     </td>
                 </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-slate-100 font-semibold">
                <tr>
                    <td class="py-2 px-3 text-slate-700">TOTAL</td>
                    <td class="text-right py-2 px-3 text-blue-700">{{ number_format(array_sum($dataPeminjaman)) }}</td>
                    <td class="text-right py-2 px-3 text-green-700">{{ number_format(array_sum($dataPengembalian)) }}</td>
                    <td class="text-right py-2 px-3 text-orange-700">{{ number_format(array_sum($dataPeminjaman) - array_sum($dataPengembalian)) }}</td>
                 </tr>
            </tfoot>
         </table>
    </div>
    
    <!-- Keterangan -->
    <div class="mt-4 text-xs text-slate-400 border-t border-slate-100 pt-3 text-center">
        <i class="fa-regular fa-chart-bar mr-1"></i> Menampilkan data peminjaman dan pengembalian (status selesai) per bulan di tahun {{ $tahunDipilih }}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('chartBulanan').getContext('2d');
    
    // Simpan chart instance ke window agar bisa di-destroy saat reload
    if (window.bulananChart) {
        window.bulananChart.destroy();
    }
    
    window.bulananChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($bulanLabels) !!},
            datasets: [
                {
                    label: 'Peminjaman',
                    data: {!! json_encode($dataPeminjaman) !!},
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                    borderColor: '#3b82f6',
                    borderWidth: 1,
                    borderRadius: 6,
                    barPercentage: 0.65,
                    categoryPercentage: 0.8
                },
                {
                    label: 'Pengembalian',
                    data: {!! json_encode($dataPengembalian) !!},
                    backgroundColor: 'rgba(16, 185, 129, 0.7)',
                    borderColor: '#10b981',
                    borderWidth: 1,
                    borderRadius: 6,
                    barPercentage: 0.65,
                    categoryPercentage: 0.8
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleColor: '#f1f5f9',
                    bodyColor: '#e2e8f0',
                    padding: 10,
                    cornerRadius: 6,
                    callbacks: {
                        label: function(context) {
                            return `${context.dataset.label}: ${context.raw.toLocaleString()}`;
                        }
                    }
                },
                legend: {
                    position: 'top',
                    labels: {
                        boxWidth: 12,
                        padding: 15,
                        font: { size: 11 }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#e2e8f0',
                        drawBorder: false
                    },
                    ticks: {
                        stepSize: {{ $stepSize }},
                        color: '#64748b',
                        callback: function(value) {
                            return value.toLocaleString();
                        }
                    },
                    title: {
                        display: true,
                        text: 'Jumlah',
                        color: '#64748b',
                        font: { size: 11 }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#64748b',
                        font: { weight: '500', size: 11 }
                    },
                    title: {
                        display: true,
                        text: 'Bulan',
                        color: '#64748b',
                        font: { size: 11 }
                    }
                }
            }
        }
    });
});
</script>
@endsection