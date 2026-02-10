@extends('layouts-admin.admin')

@section('title', 'Dashboard Admin')

@section('content')

<!-- STAT CARD -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-10">

    <!-- Card -->
    <div class="group bg-white rounded-xl p-6 shadow hover:shadow-xl transition">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-slate-400 text-sm">Total Peminjaman</p>
                <h2 class="text-3xl font-bold text-slate-800 mt-1">120</h2>
            </div>
            <div class="w-12 h-12 flex items-center justify-center rounded-xl
                        bg-blue-100 text-blue-600 group-hover:scale-110 transition">
                <i class="fa-solid fa-file-lines"></i>
            </div>
        </div>
        <p class="text-xs text-green-500 mt-4 font-medium">
            +12% dari bulan lalu
        </p>
    </div>

    <!-- Card -->
    <div class="group bg-white rounded-xl p-6 shadow hover:shadow-xl transition">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-slate-400 text-sm">Menunggu</p>
                <h2 class="text-3xl font-bold text-slate-800 mt-1">8</h2>
            </div>
            <div class="w-12 h-12 flex items-center justify-center rounded-xl
                        bg-yellow-100 text-yellow-600 group-hover:scale-110 transition">
                <i class="fa-solid fa-hourglass-half"></i>
            </div>
        </div>
        <p class="text-xs text-yellow-500 mt-4 font-medium">
            Perlu persetujuan
        </p>
    </div>

    <!-- Card -->
    <div class="group bg-white rounded-xl p-6 shadow hover:shadow-xl transition">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-slate-400 text-sm">Disetujui</p>
                <h2 class="text-3xl font-bold text-slate-800 mt-1">92</h2>
            </div>
            <div class="w-12 h-12 flex items-center justify-center rounded-xl
                        bg-blue-100 text-blue-600 group-hover:scale-110 transition">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>
        <p class="text-xs text-blue-500 mt-4 font-medium">
            Sedang dipinjam
        </p>
    </div>

    <!-- Card -->
    <div class="group bg-white rounded-xl p-6 shadow hover:shadow-xl transition">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-slate-400 text-sm">Selesai</p>
                <h2 class="text-3xl font-bold text-slate-800 mt-1">20</h2>
            </div>
            <div class="w-12 h-12 flex items-center justify-center rounded-xl
                        bg-green-100 text-green-600 group-hover:scale-110 transition">
                <i class="fa-solid fa-box-archive"></i>
            </div>
        </div>
        <p class="text-xs text-green-500 mt-4 font-medium">
            Dikembalikan
        </p>
    </div>

</div>


<!-- GRID -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    <!-- CHART -->
    <div class="xl:col-span-2 bg-white rounded-xl p-6 shadow">

        <div class="flex justify-between mb-6">
            <h3 class="font-semibold text-slate-700">
                Statistik Peminjaman
            </h3>

            <select class="text-sm border rounded-lg px-3 pr-8 py-1 text-slate-500 bg-white">
                <option>2026</option>
                <option>2025</option>
            </select>

        </div>

        <canvas id="chartPeminjaman" height="100"></canvas>

    </div>


    <!-- RECENT -->
    <div class="bg-white rounded-xl p-6 shadow">

        <h3 class="font-semibold text-slate-700 mb-4">
            Aktivitas Terbaru
        </h3>

        <div class="space-y-4">

            <div class="flex items-center justify-between">
                <div>
                    <p class="font-medium text-slate-700">Ahmad</p>
                    <p class="text-xs text-slate-400">2 menit lalu</p>
                </div>
                <span class="px-3 py-1 bg-yellow-100 text-yellow-600 rounded-full text-xs">
                    Menunggu
                </span>
            </div>

            <div class="flex items-center justify-between">
                <div>
                    <p class="font-medium text-slate-700">Siti</p>
                    <p class="text-xs text-slate-400">10 menit lalu</p>
                </div>
                <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-xs">
                    Disetujui
                </span>
            </div>

            <div class="flex items-center justify-between">
                <div>
                    <p class="font-medium text-slate-700">Budi</p>
                    <p class="text-xs text-slate-400">1 jam lalu</p>
                </div>
                <span class="px-3 py-1 bg-green-100 text-green-600 rounded-full text-xs">
                    Selesai
                </span>
            </div>

        </div>

    </div>

</div>

@endsection
