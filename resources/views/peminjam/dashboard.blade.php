@extends('layouts-peminjam.peminjam')

@section('title', 'Dashboard Peminjam')

@section('content')
<div class="space-y-8 animate-slide-in">

   <!-- Welcome Section -->
<div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 shadow-lg border border-blue-100">
    <div class="flex flex-col md:flex-row items-center justify-between">
        <!-- Left Content -->
        <div class="text-center md:text-left mb-6 md:mb-0">
            <!-- Greeting -->
            <div class="flex items-center justify-center md:justify-start gap-3 mb-4">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-r from-blue-500 to-teal-500 flex items-center justify-center shadow-md">
                    <i class="fas fa-hand-holding text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Selamat Datang, {{ auth()->user()->name }}!</h1>
                    <p class="text-gray-600 mt-1 text-sm md:text-base">Sistem Peminjaman Barang Sekolah</p>
                </div>
            </div>
            
            <!-- Description -->
            <p class="text-gray-700 max-w-2xl mb-6">
                Kelola peminjaman barang sekolah dengan mudah dan efisien. 
                Pantau status peminjaman, lihat riwayat, dan ajukan peminjaman baru.
            </p>
            
            <!-- Button (Mobile & Desktop Left) -->
            <div class="flex justify-center md:justify-start">
                <a href="" 
                   class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-teal-500 text-white font-semibold px-6 py-3 rounded-xl hover:from-blue-700 hover:to-teal-600 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <i class="fas fa-plus-circle"></i>
                    Pinjam Barang Baru
                </a>
            </div>
        </div>
        
        <!-- Right Image (Hidden on Mobile) -->
        <div class="hidden md:block relative">
            <div class="w-64 h-64 relative">
                <!-- Background decorative circles -->
                <div class="absolute -top-4 -right-4 w-32 h-32 bg-gradient-to-r from-blue-200 to-teal-200 rounded-full opacity-50"></div>
                <div class="absolute -bottom-4 -left-4 w-24 h-24 bg-gradient-to-r from-purple-200 to-pink-200 rounded-full opacity-50"></div>
                
                <!-- Main illustration -->
                <div class="relative z-10 w-full h-full flex items-center justify-center">
                    <div class="w-48 h-48 bg-gradient-to-br from-blue-500 to-teal-500 rounded-2xl flex items-center justify-center shadow-xl">
                        <div class="w-40 h-40 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <div class="text-center">
                                <i class="fas fa-school text-white text-5xl mb-3"></i>
                                <div class="text-white font-bold text-sm">SchoolLoanSphere</div>
                                <div class="text-white/80 text-xs">Peminjam Dashboard</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Floating icons -->
                <div class="absolute top-0 -left-4 w-12 h-12 bg-white rounded-xl shadow-lg flex items-center justify-center">
                    <i class="fas fa-laptop text-blue-600 text-lg"></i>
                </div>
                <div class="absolute bottom-8 -right-4 w-12 h-12 bg-white rounded-xl shadow-lg flex items-center justify-center">
                    <i class="fas fa-book text-teal-600 text-lg"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Stats Row (Below on Mobile) -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8 pt-6 border-t border-blue-200">
        <div class="text-center">
            <div class="text-2xl font-bold text-blue-600">24</div>
            <div class="text-sm text-gray-600">Total Pinjaman</div>
        </div>
        <div class="text-center">
            <div class="text-2xl font-bold text-teal-600">3</div>
            <div class="text-sm text-gray-600">Sedang Dipinjam</div>
        </div>
        <div class="text-center">
            <div class="text-2xl font-bold text-yellow-600">2</div>
            <div class="text-sm text-gray-600">Menunggu</div>
        </div>
        <div class="text-center">
            <div class="text-2xl font-bold text-green-600">19</div>
            <div class="text-sm text-gray-600">Selesai</div>
        </div>
    </div>
</div>


</div>

@endsection