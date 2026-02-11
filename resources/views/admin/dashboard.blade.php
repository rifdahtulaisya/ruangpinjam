@extends('layouts-admin.admin')

@section('title', 'DASHBOARD')

@section('content')

@php
    // Set default values dengan pengecekan lebih ketat
    $totalPeminjaman = isset($totalPeminjaman) ? $totalPeminjaman : 0;
    $peminjamanBulanIni = isset($peminjamanBulanIni) ? $peminjamanBulanIni : 0; // TAMBAHKAN
    $menungguPeminjaman = isset($menungguPeminjaman) ? $menungguPeminjaman : 0;
    $dipinjam = isset($dipinjam) ? $dipinjam : 0;
    $selesai = isset($selesai) ? $selesai : 0;
    $ditolak = isset($ditolak) ? $ditolak : 0;
    $ditegur = isset($ditegur) ? $ditegur : 0;
    $persentasePerubahan = isset($persentasePerubahan) ? $persentasePerubahan : 0;
    $peminjamanTerlambat = isset($peminjamanTerlambat) ? $peminjamanTerlambat : 0;
    $totalUser = isset($totalUser) ? $totalUser : 0;
    $userAktif = isset($userAktif) ? $userAktif : 0;
    $userBaru = isset($userBaru) ? $userBaru : 0;
    $totalLogAktivitas = isset($totalLogAktivitas) ? $totalLogAktivitas : 0;
    $logHariIni = isset($logHariIni) ? $logHariIni : 0;
    
    if(!isset($chartData) || empty($chartData)) {
        $chartData = [
            'bulan' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            'peminjaman' => [0,0,0,0,0,0,0,0,0,0,0,0],
            'pengembalian' => [0,0,0,0,0,0,0,0,0,0,0,0],
            'tahun' => date('Y')
        ];
    }
    
    if(!isset($aktivitasTerbaru)) {
        $aktivitasTerbaru = collect([]);
    }
    
    if(!isset($topAlat)) {
        $topAlat = [];
    }
@endphp

<!-- DEBUG INFO (HAPUS SETELAH SELESAI) -->
@if(isset($dbConnected) && !$dbConnected)
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <strong>Error Database!</strong> Tidak dapat terhubung ke database.
    </div>
@endif

<!-- STATISTIK CARD - 4 CARD UTAMA -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
    <!-- Total Peminjaman BULAN INI (bukan total semua) -->
    <div class="group bg-white rounded-xl p-6 shadow hover:shadow-xl transition">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-slate-400 text-sm">Peminjaman Bulan Ini</p>
                <h2 class="text-3xl font-bold text-slate-800 mt-1">{{ number_format($peminjamanBulanIni) }}</h2>
                <p class="text-xs text-slate-500 mt-1">Total: {{ number_format($totalPeminjaman) }}</p>
            </div>
            <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-blue-100 text-blue-600 group-hover:scale-110 transition">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
        </div>
        <div class="flex justify-between items-center mt-4">
            <p class="text-xs {{ $persentasePerubahan >= 0 ? 'text-green-500' : 'text-red-500' }} font-medium">
                @if($persentasePerubahan > 0)
                    <i class="fa-solid fa-arrow-up mr-1"></i>{{ $persentasePerubahan }}%
                @elseif($persentasePerubahan < 0)
                    <i class="fa-solid fa-arrow-down mr-1"></i>{{ abs($persentasePerubahan) }}%
                @else
                    <span class="text-slate-400">0%</span>
                @endif
            </p>
            <p class="text-xs text-slate-400">dari bulan lalu</p>
        </div>
    </div>

    <!-- Menunggu Persetujuan -->
    <div class="group bg-white rounded-xl p-6 shadow hover:shadow-xl transition">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-slate-400 text-sm">Menunggu Persetujuan</p>
                <h2 class="text-3xl font-bold text-slate-800 mt-1">{{ number_format($menungguPeminjaman) }}</h2>
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

    <!-- Sedang Dipinjam -->
    <div class="group bg-white rounded-xl p-6 shadow hover:shadow-xl transition">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-slate-400 text-sm">Sedang Dipinjam</p>
                <h2 class="text-3xl font-bold text-slate-800 mt-1">{{ number_format($dipinjam) }}</h2>
            </div>
            <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-green-100 text-green-600 group-hover:scale-110 transition">
                <i class="fa-solid fa-play"></i>
            </div>
        </div>
        <div class="flex items-center justify-between mt-4">
            <span class="px-2 py-1 bg-red-100 text-red-600 rounded-full text-xs font-medium">
                {{ $peminjamanTerlambat }} Terlambat
            </span>
        </div>
    </div>

    <!-- Selesai -->
    <div class="group bg-white rounded-xl p-6 shadow hover:shadow-xl transition">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-slate-400 text-sm">Selesai</p>
                <h2 class="text-3xl font-bold text-slate-800 mt-1">{{ number_format($selesai) }}</h2>
            </div>
            <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-indigo-100 text-indigo-600 group-hover:scale-110 transition">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>
        <p class="text-xs text-slate-400 mt-4">
            <span class="text-red-500 font-medium">{{ number_format($ditolak) }}</span> Ditolak · 
            <span class="text-orange-500 font-medium">{{ number_format($ditegur) }}</span> Ditegur
        </p>
    </div>
</div>

<!-- SISANYA SAMA... (LANJUTKAN DARI VIEW SEBELUMNYA) -->

<!-- GRID UTAMA -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <!-- CHART PEMINJAMAN -->
    <div class="xl:col-span-2 bg-white rounded-xl p-6 shadow">
        <div class="flex flex-wrap justify-between items-center mb-6">
            <h3 class="font-semibold text-slate-700">
                Statistik Peminjaman & Pengembalian {{ $chartData['tahun'] }}
            </h3>
            <div class="flex items-center gap-4">
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
        <canvas id="chartPeminjaman" height="100"></canvas>
    </div>

    <!-- LOG AKTIVITAS TERBARU (bukan peminjaman) -->
    <div class="bg-white rounded-xl p-6 shadow">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-semibold text-slate-700">
                Log Aktivitas Terbaru
            </h3>
            <span class="text-xs bg-slate-100 px-2 py-1 rounded-full text-slate-600">
                {{ $aktivitasTerbaru->count() }} aktivitas
            </span>
        </div>

        <div class="space-y-3 max-h-[320px] overflow-y-auto pr-1">
            @forelse($aktivitasTerbaru as $aktivitas)
                <div class="flex items-start gap-3 p-2 hover:bg-slate-50 rounded-lg transition">
                    <div class="w-8 h-8 rounded-full bg-slate-200 flex-shrink-0 flex items-center justify-center">
                        <span class="text-xs font-medium text-slate-700">
                            {{ substr($aktivitas->user->name ?? 'S', 0, 1) }}
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start">
                            <p class="font-medium text-sm text-slate-800 truncate">
                                {{ $aktivitas->user->name ?? 'Sistem' }}
                            </p>
                            <span class="text-xs text-slate-400 flex-shrink-0 ml-2">
                                {{ $aktivitas->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">
                            {{ Str::limit($aktivitas->aktivitas, 50) }}
                        </p>
                        <p class="text-xs text-slate-400 mt-0.5">
                            {{ $aktivitas->modul ?? '-' }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <div class="text-slate-300 text-4xl mb-3">
                        <i class="fa-regular fa-clock"></i>
                    </div>
                    <p class="text-slate-500 text-sm">Belum ada aktivitas</p>
                </div>
            @endforelse
        </div>

        @if($aktivitasTerbaru->count() > 0)
            <div class="mt-4 pt-3 border-t border-slate-100">
                <a href="{{ route('admin.log-aktivitas.index') ?? '#' }}" 
                   class="text-sm text-blue-600 hover:text-blue-700 font-medium flex items-center justify-center gap-1">
                    Lihat Semua Log Aktivitas
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
        @endif
    </div>
</div>

<!-- GRID KEDUA -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
    <!-- TOP ALAT -->
    <div class="lg:col-span-2 bg-white rounded-xl p-6 shadow">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold text-slate-700">
                Alat Paling Sering Dipinjam
            </h3>
            <span class="text-xs text-slate-400">
                {{ count($topAlat) }} alat teratas
            </span>
        </div>
        
        <div class="space-y-4">
            @forelse($topAlat as $index => $item)
                <div class="flex items-center justify-between group hover:bg-slate-50 p-2 rounded-lg">
                    <div class="flex items-center gap-3 flex-1">
                        <span class="w-6 h-6 rounded-full 
                            @if($index == 0) bg-yellow-100 text-yellow-600
                            @elseif($index == 1) bg-slate-100 text-slate-600
                            @elseif($index == 2) bg-orange-100 text-orange-600
                            @else bg-slate-100 text-slate-500
                            @endif
                            flex items-center justify-center text-xs font-bold">
                            {{ $index + 1 }}
                        </span>
                        <div class="flex-1">
                            <p class="font-medium text-slate-800 text-sm">
                                {{ $item['alat']->nama_alat ?? 'Alat tidak ditemukan' }}
                            </p>
                            <p class="text-xs text-slate-400">
                                Kode: {{ $item['alat']->kode_alat ?? '-' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-semibold text-blue-600">
                            {{ $item['total'] }}x
                        </span>
                        <span class="text-xs text-slate-400">
                            dipinjam
                        </span>
                    </div>
                </div>
            @empty
                <div class="text-center py-6">
                    <div class="text-slate-300 text-3xl mb-2">
                        <i class="fa-solid fa-wrench"></i>
                    </div>
                    <p class="text-slate-400 text-sm">Belum ada data peminjaman</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- STATISTIK PENGGUNA & INFO ADMIN -->
    <div class="bg-white rounded-xl p-6 shadow">
        <h3 class="font-semibold text-slate-700 mb-4">
            Ringkasan Admin
        </h3>
        
        <div class="space-y-4">
            <!-- Log Aktivitas -->
            <div class="bg-slate-50 rounded-lg p-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                            <i class="fa-solid fa-history text-purple-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400">Total Log Aktivitas</p>
                            <p class="text-2xl font-bold text-slate-800">{{ number_format($totalLogAktivitas) }}</p>
                        </div>
                    </div>
                    <span class="text-xs text-purple-600 bg-purple-100 px-2 py-1 rounded-full">
                        {{ $logHariIni }} hari ini
                    </span>
                </div>
            </div>

            <!-- Total Users -->
            <div class="bg-slate-50 rounded-lg p-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                            <i class="fa-solid fa-users text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400">Total Pengguna</p>
                            <p class="text-2xl font-bold text-slate-800">{{ number_format($totalUser) }}</p>
                        </div>
                    </div>
                    <span class="text-xs text-slate-400">User</span>
                </div>
            </div>

            <!-- Active Users -->
            <div class="bg-slate-50 rounded-lg p-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                            <i class="fa-solid fa-user-check text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400">Sedang Meminjam</p>
                            <p class="text-2xl font-bold text-green-600">{{ number_format($userAktif) }}</p>
                        </div>
                    </div>
                    <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full">
                        Aktif
                    </span>
                </div>
            </div>

            <!-- New Users -->
            <div class="bg-slate-50 rounded-lg p-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center">
                            <i class="fa-solid fa-user-plus text-indigo-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400">Pengguna Baru</p>
                            <p class="text-2xl font-bold text-indigo-600">{{ number_format($userBaru) }}</p>
                        </div>
                    </div>
                    <span class="text-xs text-slate-400">Bulan ini</span>
                </div>
            </div>

            <!-- Status Summary -->
            <div class="border-t border-slate-100 pt-4 mt-2">
                <div class="grid grid-cols-2 gap-2">
                    <div class="text-center p-2 bg-red-50 rounded-lg">
                        <p class="text-xs text-red-400">Ditolak</p>
                        <p class="text-lg font-semibold text-red-600">{{ number_format($ditolak) }}</p>
                    </div>
                    <div class="text-center p-2 bg-orange-50 rounded-lg">
                        <p class="text-xs text-orange-400">Ditegur</p>
                        <p class="text-lg font-semibold text-orange-600">{{ number_format($ditegur) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('chartPeminjaman').getContext('2d');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData['bulan']) !!},
            datasets: [
                {
                    label: 'Peminjaman',
                    data: {!! json_encode($chartData['peminjaman']) !!},
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    tension: 0.3,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: 'white',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true
                },
                {
                    label: 'Pengembalian',
                    data: {!! json_encode($chartData['pengembalian']) !!},
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 2,
                    tension: 0.3,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: 'white',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true
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
                    cornerRadius: 6
                },
                legend: {
                    display: false
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
                        stepSize: 1,
                        color: '#64748b'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#64748b'
                    }
                }
            }
        }
    });
});
</script>
@endpush