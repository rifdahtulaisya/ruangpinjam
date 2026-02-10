<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ruang Pinjam</title>
    
    @include('layouts.link')
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        :root {
            --primary-blue: #1e40af;
            --secondary-blue: #3b82f6;
            --accent-teal: #0d9488;
            --accent-orange: #f97316;
            --accent-purple: #7c3aed;
            --light-bg: #f8fafc;
            --dark-blue: #132440;
        }
        
        /* MOBILE NAVBAR SIDEBAR STYLES */
        .mobile-sidebar {
            position: fixed;
            top: 0;
            right: -100%;
            width: 85%;
            max-width: 320px;
            height: 100vh;
            background: white;
            box-shadow: -5px 0 25px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            transition: right 0.4s ease;
            overflow-y: auto;
            padding: 20px;
        }
        
        .mobile-sidebar.open {
            right: 0;
        }
        
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .sidebar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 20px;
        }
        
        .sidebar-logo {
            display: flex;
            align-items: center;
        }
        
        .sidebar-logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #1e40af, #3b82f6);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
        }
        
        .sidebar-logo-text {
            font-size: 1.25rem;
            font-weight: 700;
            color: #132440;
        }
        
        .sidebar-logo-text span {
            color: #1e40af;
        }
        
        .close-sidebar {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #6b7280;
            cursor: pointer;
            transition: color 0.2s;
        }
        
        .close-sidebar:hover {
            color: #132440;
        }
        
        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 30px;
        }
        
        .sidebar-nav a {
            display: flex;
            align-items: center;
            padding: 14px 16px;
            border-radius: 10px;
            color: #374151;
            font-weight: 500;
            transition: all 0.2s;
            text-decoration: none;
        }
        
        .sidebar-nav a:hover {
            background: #f3f4f6;
            color: #1e40af;
            transform: translateX(5px);
        }
        
        .sidebar-nav a i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }
        
        .sidebar-cta {
            padding: 25px 20px;
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            border-radius: 12px;
            text-align: center;
            margin-top: auto;
        }
        
        .sidebar-cta h4 {
            font-size: 1.1rem;
            font-weight: 600;
            color: #132440;
            margin-bottom: 10px;
        }
        
        .sidebar-cta p {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 20px;
        }
        
        .sidebar-cta-button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #1e40af, #3b82f6);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .sidebar-cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(30, 64, 175, 0.2);
        }
        
        .sidebar-cta-button i {
            margin-right: 8px;
        }
        
        /* Rest of your existing styles remain the same */
        .hero-bg {
            background: linear-gradient(135deg, rgba(19, 36, 64, 0.95) 0%, rgba(30, 64, 175, 0.9) 100%), 
                        url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            background-blend-mode: overlay;
        }
        
        .school-bg {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
        
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(30, 64, 175, 0.15);
        }
        
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        
        .animate-slide-in {
            animation: slideIn 0.8s ease-out forwards;
            opacity: 0;
            transform: translateY(30px);
        }
        
        @keyframes slideIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-pulse-slow {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .stat-number {
            transition: all 1s ease-out;
            opacity: 0;
            transform: translateY(20px);
        }
        
        .stat-number.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        .text-darkBlue {
            color: #132440 !important;
        }
        
        .bg-darkBlue {
            background-color: #132440 !important;
        }
        
        .border-darkBlue {
            border-color: #132440 !important;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            color: white;
            font-weight: 600;
            padding: 12px 28px;
            border-radius: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(30, 64, 175, 0.2);
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(30, 64, 175, 0.3);
        }
        
        .btn-secondary {
            background: white;
            color: var(--primary-blue);
            font-weight: 600;
            padding: 12px 28px;
            border-radius: 10px;
            border: 2px solid var(--primary-blue);
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background: var(--primary-blue);
            color: white;
        }
        
        .school-card {
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            background: white;
            position: relative;
        }
        
        .school-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 8px;
            height: 100%;
            background: linear-gradient(to bottom, var(--primary-blue), var(--accent-teal));
        }
        
        .category-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-right: 8px;
            margin-bottom: 8px;
        }
        
        .equipment-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 12px;
            margin-top: 16px;
        }
        
        .equipment-item {
            background: #f1f5f9;
            border-radius: 10px;
            padding: 12px 8px;
            text-align: center;
            transition: all 0.2s ease;
        }
        
        .equipment-item:hover {
            background: var(--primary-blue);
            color: white;
            transform: translateY(-3px);
        }
    </style>
</head>
<body class="school-bg">
    <!-- Header & Navigation -->
    <header class="sticky top-0 z-50 shadow-lg bg-white">
        <nav class="container mx-auto px-4 sm:px-6 py-4">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <div class="flex items-center">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-gradient-to-r from-blue-600 to-teal-500 flex items-center justify-center shadow-md">
                        <i class="fas fa-school text-white text-lg sm:text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <span class="text-xl sm:text-2xl font-bold text-darkBlue">School<span class="text-blue-600">Loan</span>Sphere</span>
                        <p class="text-xs text-gray-500 -mt-1 hidden sm:block">Sistem Peminjaman Barang Sekolah</p>
                    </div>
                </div>
                
                <!-- Desktop Navigation & CTA Button -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#home" class="font-medium text-darkBlue hover:text-blue-600 transition-colors">Beranda</a>
                    <a href="#features" class="font-medium text-darkBlue hover:text-blue-600 transition-colors">Fitur</a>
                    <a href="#products" class="font-medium text-darkBlue hover:text-blue-600 transition-colors">Barang</a>
                    <a href="#process" class="font-medium text-darkBlue hover:text-blue-600 transition-colors">Proses</a>
                    <a href="#testimonials" class="font-medium text-darkBlue hover:text-blue-600 transition-colors">Testimoni</a>
                    
                    <div class="navbar-cta">
    @guest
        <a href="{{ route('login') }}" class="btn-primary animate-pulse-slow">
            Login
        </a>
    @endguest

    @auth
        <a href="{{ url(auth()->user()->role . '/dashboard') }}" class="btn-primary">
            Dashboard
        </a>
    @endauth
</div>



                </div>
                
                <!-- Mobile Menu Button -->
                <button id="mobile-menu-button" class="md:hidden text-darkBlue text-2xl">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </nav>
    </header>

    <!-- Mobile Sidebar -->
    <div class="sidebar-overlay" id="sidebar-overlay"></div>
    
    <div class="mobile-sidebar" id="mobile-sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <div class="sidebar-logo-icon">
                    <i class="fas fa-school text-white"></i>
                </div>
                <div class="sidebar-logo-text">
                    School<span>Loan</span>Sphere
                </div>
            </div>
            <button class="close-sidebar" id="close-sidebar">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="sidebar-nav">
            <a href="#home">
                <i class="fas fa-home"></i>
                Beranda
            </a>
            <a href="#features">
                <i class="fas fa-star"></i>
                Fitur
            </a>
            <a href="#products">
                <i class="fas fa-box-open"></i>
                Barang
            </a>
            <a href="#process">
                <i class="fas fa-list-ol"></i>
                Proses
            </a>
            <a href="#testimonials">
                <i class="fas fa-comment"></i>
                Testimoni
            </a>
            <a href="#">
                <i class="fas fa-question-circle"></i>
                Bantuan
            </a>
            <a href="#">
                <i class="fas fa-info-circle"></i>
                Tentang Kami
            </a>
        </div>
        
        <div class="sidebar-cta">
            <h4>Pinjam Barang Sekarang</h4>
            <p>Daftar dan mulai pinjam barang sekolah dengan mudah</p>
            @guest
    <a href="{{ route('login') }}" class="sidebar-cta-button">
        Login
    </a>
@endguest

@auth
    <a href="{{ url(auth()->user()->role . '/dashboard') }}" 
       class="sidebar-cta-button bg-green-600">
        Dashboard
    </a>
@endauth



        </div>
    </div>

    <!-- Hero Section -->
    <section id="home" class="hero-bg text-white py-12 md:py-24">
        <div class="container mx-auto px-4 sm:px-6">
            <div class="flex flex-col md:flex-row items-center">
                <!-- Hero Content -->
                <div class="md:w-1/2 mb-10 md:mb-0 animate-slide-in hero-content">
                    <div class="inline-flex items-center bg-white/20 backdrop-blur-sm rounded-full px-3 py-1.5 sm:px-4 sm:py-2 mb-4 sm:mb-6 text-sm">
                        <i class="fas fa-graduation-cap mr-2"></i>
                        <span>Sistem Peminjaman Barang Sekolah Terintegrasi</span>
                    </div>
                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold leading-tight mb-4 sm:mb-6">
                        Peminjaman Barang Sekolah <span class="text-orange-400">Lebih Mudah</span> & Terorganisir
                    </h1>
                    <p class="text-base sm:text-lg mb-6 sm:mb-8 text-white/90">
                        Kelola peminjaman peralatan sekolah, laboratorium, olahraga, dan multimedia dengan sistem digital yang modern dan efisien.
                    </p>
                    <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
                        <button class="btn-primary text-base sm:text-lg">
                            <i class="fas fa-rocket mr-2"></i>Mulai Pinjam
                        </button>
                        <button class="btn-secondary text-base sm:text-lg">
                            <i class="fas fa-play-circle mr-2"></i>Lihat Demo
                        </button>
                    </div>
                    
                    <!-- Quick Stats -->
                    <div class="flex flex-wrap gap-4 sm:gap-6 mt-8 sm:mt-10">
                        <div class="flex items-center">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-blue-500/20 flex items-center justify-center mr-2 sm:mr-3">
                                <i class="fas fa-check-circle text-blue-300 text-sm sm:text-base"></i>
                            </div>
                            <div>
                                <div class="font-bold text-lg sm:text-xl">100%</div>
                                <div class="text-xs sm:text-sm text-white/80">Digital</div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-green-500/20 flex items-center justify-center mr-2 sm:mr-3">
                                <i class="fas fa-clock text-green-300 text-sm sm:text-base"></i>
                            </div>
                            <div>
                                <div class="font-bold text-lg sm:text-xl">5 Menit</div>
                                <div class="text-xs sm:text-sm text-white/80">Proses</div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-purple-500/20 flex items-center justify-center mr-2 sm:mr-3">
                                <i class="fas fa-shield-alt text-purple-300 text-sm sm:text-base"></i>
                            </div>
                            <div>
                                <div class="font-bold text-lg sm:text-xl">Aman</div>
                                <div class="text-xs sm:text-sm text-white/80">Terjamin</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Hero Image/Illustration -->
                <div class="md:w-1/2 flex justify-center animate-float mt-8 md:mt-0">
                    <div class="relative">
                        <div class="w-64 h-64 sm:w-72 sm:h-72 md:w-80 md:h-80 bg-gradient-to-br from-blue-600/20 to-teal-500/20 rounded-xl sm:rounded-2xl flex items-center justify-center shadow-2xl">
                            <div class="w-52 h-52 sm:w-56 sm:h-56 md:w-64 md:h-64 bg-gradient-to-tr from-blue-500/30 to-teal-400/30 rounded-xl sm:rounded-2xl flex items-center justify-center">
                                <div class="w-40 h-40 sm:w-44 sm:h-44 md:w-48 md:h-48 bg-white/10 rounded-xl sm:rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/20">
                                    <i class="fas fa-laptop-projector text-white text-5xl sm:text-6xl md:text-7xl"></i>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Floating elements -->
                        <div class="absolute -top-2 -left-2 sm:-top-4 sm:-left-4 w-12 h-12 sm:w-16 sm:h-16 md:w-20 md:h-20 bg-orange-500 rounded-lg sm:rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-basketball-ball text-white text-lg sm:text-2xl md:text-3xl"></i>
                        </div>
                        <div class="absolute -bottom-2 -right-2 sm:-bottom-4 sm:-right-4 w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 bg-white rounded-lg sm:rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-microscope text-blue-600 text-xl sm:text-3xl md:text-4xl"></i>
                        </div>
                        <div class="absolute top-8 -right-4 sm:top-10 sm:-right-8 w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 bg-purple-500 rounded-lg sm:rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-book text-white text-base sm:text-xl md:text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Features Section -->
    <section id="features" class="py-12 sm:py-16 bg-gradient-to-b from-white to-slate-50">
        <div class="container mx-auto px-4 sm:px-6">
            <div class="text-center mb-10 sm:mb-16">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-darkBlue mb-3 sm:mb-4">
                    Sistem <span class="text-blue-600">Peminjaman Digital</span> untuk Sekolah
                </h2>
                <p class="text-base sm:text-lg text-gray-600 max-w-3xl mx-auto">
                    Kelola inventaris dan peminjaman barang sekolah dengan sistem yang terintegrasi, efisien, dan modern.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
                <!-- Feature 1 -->
                <div class="school-card p-6 sm:p-8 card-hover">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-r from-blue-600 to-blue-400 rounded-lg sm:rounded-xl flex items-center justify-center mb-4 sm:mb-6">
                        <i class="fas fa-search text-white text-xl sm:text-2xl"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-darkBlue mb-3 sm:mb-4">Pencarian Cepat</h3>
                    <p class="text-sm sm:text-base text-gray-600">
                        Temukan barang yang dibutuhkan dengan sistem pencarian yang cerdas berdasarkan kategori, status, dan lokasi.
                    </p>
                </div>
                
                <!-- Feature 2 -->
                <div class="school-card p-6 sm:p-8 card-hover">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-r from-teal-600 to-teal-400 rounded-lg sm:rounded-xl flex items-center justify-center mb-4 sm:mb-6">
                        <i class="fas fa-calendar-check text-white text-xl sm:text-2xl"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-darkBlue mb-3 sm:mb-4">Jadwal Terintegrasi</h3>
                    <p class="text-sm sm:text-base text-gray-600">
                        Sistem kalender yang memungkinkan peminjaman terjadwal dan menghindari konflik penggunaan barang.
                    </p>
                </div>
                
                <!-- Feature 3 -->
                <div class="school-card p-6 sm:p-8 card-hover">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-r from-purple-600 to-purple-400 rounded-lg sm:rounded-xl flex items-center justify-center mb-4 sm:mb-6">
                        <i class="fas fa-qrcode text-white text-xl sm:text-2xl"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-darkBlue mb-3 sm:mb-4">Scan QR Code</h3>
                    <p class="text-sm sm:text-base text-gray-600">
                        Setiap barang memiliki QR Code unik untuk memudahkan proses peminjaman dan pengembalian.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Products/Items Section -->
    <section id="products" class="py-12 sm:py-16 bg-white">
        <div class="container mx-auto px-4 sm:px-6">
            <div class="text-center mb-10 sm:mb-16">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-darkBlue mb-3 sm:mb-4">
                    Katalog <span class="text-blue-600">Barang Sekolah</span>
                </h2>
                <p class="text-base sm:text-lg text-gray-600 max-w-3xl mx-auto">
                    Berbagai jenis peralatan sekolah yang dapat dipinjam oleh siswa, guru, dan staf.
                </p>
            </div>
            
            <!-- Category Filter -->
            <div class="flex flex-wrap justify-center gap-2 sm:gap-3 mb-8 sm:mb-12">
                <button class="category-badge bg-blue-100 text-blue-800 hover:bg-blue-200 text-xs">Semua</button>
                <button class="category-badge bg-teal-100 text-teal-800 hover:bg-teal-200 text-xs">Laboratorium</button>
                <button class="category-badge bg-orange-100 text-orange-800 hover:bg-orange-200 text-xs">Olahraga</button>
                <button class="category-badge bg-purple-100 text-purple-800 hover:bg-purple-200 text-xs">Multimedia</button>
                <button class="category-badge bg-green-100 text-green-800 hover:bg-green-200 text-xs">Perpustakaan</button>
                <button class="category-badge bg-red-100 text-red-800 hover:bg-red-200 text-xs">Kesenian</button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
                <!-- Item 1 -->
                <div class="school-card overflow-hidden card-hover">
                    <div class="h-40 sm:h-48 bg-gradient-to-r from-blue-500 to-blue-300 flex items-center justify-center">
                        <i class="fas fa-laptop-code text-white text-5xl sm:text-6xl md:text-7xl"></i>
                    </div>
                    <div class="p-6 sm:p-8">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-xl sm:text-2xl font-bold text-darkBlue mb-1 sm:mb-2">Laptop & Tablet</h3>
                                <p class="text-sm text-gray-600">Peralatan komputer untuk pembelajaran</p>
                            </div>
                            <div class="text-right">
                                <div class="text-xs sm:text-sm text-gray-500">Tersedia</div>
                                <div class="text-xl sm:text-2xl font-bold text-green-600">15</div>
                            </div>
                        </div>
                        
                        <div class="equipment-grid">
                            <div class="equipment-item">
                                <i class="fas fa-laptop text-blue-600 text-lg sm:text-xl mb-1 sm:mb-2"></i>
                                <div class="text-xs sm:text-sm font-medium">Laptop</div>
                            </div>
                            <div class="equipment-item">
                                <i class="fas fa-tablet-alt text-blue-600 text-lg sm:text-xl mb-1 sm:mb-2"></i>
                                <div class="text-xs sm:text-sm font-medium">Tablet</div>
                            </div>
                            <div class="equipment-item">
                                <i class="fas fa-projector text-blue-600 text-lg sm:text-xl mb-1 sm:mb-2"></i>
                                <div class="text-xs sm:text-sm font-medium">Proyektor</div>
                            </div>
                        </div>
                        
                        <button class="w-full mt-4 sm:mt-6 btn-primary text-sm sm:text-base">
                            <i class="fas fa-hand-holding mr-2"></i>Pinjam Barang
                        </button>
                    </div>
                </div>
                
                <!-- Item 2 (Featured) -->
                <div class="school-card overflow-hidden card-hover transform md:-translate-y-4 relative border-2 border-blue-500">
                    <div class="absolute top-3 right-3 sm:top-4 sm:right-4 bg-gradient-to-r from-blue-600 to-teal-500 text-white text-xs font-bold px-3 py-1.5 sm:px-4 sm:py-2 rounded-full shadow-md">
                        <i class="fas fa-star mr-1"></i> POPULER
                    </div>
                    <div class="h-40 sm:h-48 bg-gradient-to-r from-teal-500 to-teal-300 flex items-center justify-center">
                        <i class="fas fa-flask text-white text-5xl sm:text-6xl md:text-7xl"></i>
                    </div>
                    <div class="p-6 sm:p-8">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-xl sm:text-2xl font-bold text-darkBlue mb-1 sm:mb-2">Alat Laboratorium</h3>
                                <p class="text-sm text-gray-600">Peralatan praktikum sains</p>
                            </div>
                            <div class="text-right">
                                <div class="text-xs sm:text-sm text-gray-500">Tersedia</div>
                                <div class="text-xl sm:text-2xl font-bold text-green-600">42</div>
                            </div>
                        </div>
                        
                        <div class="equipment-grid">
                            <div class="equipment-item">
                                <i class="fas fa-microscope text-teal-600 text-lg sm:text-xl mb-1 sm:mb-2"></i>
                                <div class="text-xs sm:text-sm font-medium">Mikroskop</div>
                            </div>
                            <div class="equipment-item">
                                <i class="fas fa-vial text-teal-600 text-lg sm:text-xl mb-1 sm:mb-2"></i>
                                <div class="text-xs sm:text-sm font-medium">Peralatan Kimia</div>
                            </div>
                            <div class="equipment-item">
                                <i class="fas fa-weight-scale text-teal-600 text-lg sm:text-xl mb-1 sm:mb-2"></i>
                                <div class="text-xs sm:text-sm font-medium">Timbangan</div>
                            </div>
                        </div>
                        
                        <button class="w-full mt-4 sm:mt-6 bg-gradient-to-r from-teal-600 to-teal-500 text-white font-semibold py-3 rounded-lg hover:from-teal-700 hover:to-teal-600 transition-all text-sm sm:text-base">
                            <i class="fas fa-hand-holding mr-2"></i>Pinjam Barang
                        </button>
                    </div>
                </div>
                
                <!-- Item 3 -->
                <div class="school-card overflow-hidden card-hover">
                    <div class="h-40 sm:h-48 bg-gradient-to-r from-orange-500 to-orange-300 flex items-center justify-center">
                        <i class="fas fa-basketball-ball text-white text-5xl sm:text-6xl md:text-7xl"></i>
                    </div>
                    <div class="p-6 sm:p-8">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-xl sm:text-2xl font-bold text-darkBlue mb-1 sm:mb-2">Peralatan Olahraga</h3>
                                <p class="text-sm text-gray-600">Alat olahraga untuk kegiatan ekstrakurikuler</p>
                            </div>
                            <div class="text-right">
                                <div class="text-xs sm:text-sm text-gray-500">Tersedia</div>
                                <div class="text-xl sm:text-2xl font-bold text-green-600">78</div>
                            </div>
                        </div>
                        
                        <div class="equipment-grid">
                            <div class="equipment-item">
                                <i class="fas fa-futbol text-orange-600 text-lg sm:text-xl mb-1 sm:mb-2"></i>
                                <div class="text-xs sm:text-sm font-medium">Bola</div>
                            </div>
                            <div class="equipment-item">
                                <i class="fas fa-dumbbell text-orange-600 text-lg sm:text-xl mb-1 sm:mb-2"></i>
                                <div class="text-xs sm:text-sm font-medium">Fitness</div>
                            </div>
                            <div class="equipment-item">
                                <i class="fas fa-stopwatch text-orange-600 text-lg sm:text-xl mb-1 sm:mb-2"></i>
                                <div class="text-xs sm:text-sm font-medium">Alat Ukur</div>
                            </div>
                        </div>
                        
                        <button class="w-full mt-4 sm:mt-6 btn-primary text-sm sm:text-base">
                            <i class="fas fa-hand-holding mr-2"></i>Pinjam Barang
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-8 sm:mt-12">
                <button class="btn-secondary text-sm sm:text-base">
                    <i class="fas fa-list mr-2"></i>Lihat Semua Barang
                </button>
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section id="process" class="py-12 sm:py-16 bg-gradient-to-b from-slate-50 to-white">
        <div class="container mx-auto px-4 sm:px-6">
            <div class="text-center mb-10 sm:mb-16">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-darkBlue mb-3 sm:mb-4">
                    Cara <span class="text-blue-600">Meminjam</span> Barang
                </h2>
                <p class="text-base sm:text-lg text-gray-600 max-w-3xl mx-auto">
                    Hanya dengan 4 langkah sederhana, barang yang Anda butuhkan siap digunakan.
                </p>
            </div>
            
            <div class="relative">
                <!-- Process line (hidden on mobile) -->
                <div class="hidden md:block absolute top-1/2 left-0 right-0 h-2 bg-gradient-to-r from-blue-500 via-teal-500 to-orange-500 transform -translate-y-1/2 rounded-full"></div>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 sm:gap-8 md:gap-4 relative">
                    <!-- Step 1 -->
                    <div class="bg-white p-6 sm:p-8 rounded-xl sm:rounded-2xl shadow-lg text-center z-10 border border-slate-200 process-step">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-r from-blue-600 to-blue-400 text-white rounded-full flex items-center justify-center text-xl sm:text-2xl font-bold mx-auto mb-4 sm:mb-6 shadow-md">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-darkBlue mb-3 sm:mb-4">Cari Barang</h3>
                        <p class="text-sm sm:text-base text-gray-600">
                            Cari barang yang dibutuhkan melalui katalog online atau scan QR Code.
                        </p>
                    </div>
                    
                    <!-- Step 2 -->
                    <div class="bg-white p-6 sm:p-8 rounded-xl sm:rounded-2xl shadow-lg text-center z-10 border border-slate-200 process-step">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-r from-teal-600 to-teal-400 text-white rounded-full flex items-center justify-center text-xl sm:text-2xl font-bold mx-auto mb-4 sm:mb-6 shadow-md">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-darkBlue mb-3 sm:mb-4">Atur Jadwal</h3>
                        <p class="text-sm sm:text-base text-gray-600">
                            Pilih tanggal peminjaman dan pengembalian di kalender digital.
                        </p>
                    </div>
                    
                    <!-- Step 3 -->
                    <div class="bg-white p-6 sm:p-8 rounded-xl sm:rounded-2xl shadow-lg text-center z-10 border border-slate-200 process-step">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-r from-orange-600 to-orange-400 text-white rounded-full flex items-center justify-center text-xl sm:text-2xl font-bold mx-auto mb-4 sm:mb-6 shadow-md">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-darkBlue mb-3 sm:mb-4">Konfirmasi</h3>
                        <p class="text-sm sm:text-base text-gray-600">
                            Tunggu konfirmasi dari admin atau guru penanggung jawab.
                        </p>
                    </div>
                    
                    <!-- Step 4 -->
                    <div class="bg-white p-6 sm:p-8 rounded-xl sm:rounded-2xl shadow-lg text-center z-10 border border-slate-200 process-step">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-r from-purple-600 to-purple-400 text-white rounded-full flex items-center justify-center text-xl sm:text-2xl font-bold mx-auto mb-4 sm:mb-6 shadow-md">
                            <i class="fas fa-box-open"></i>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-darkBlue mb-3 sm:mb-4">Ambil Barang</h3>
                        <p class="text-sm sm:text-base text-gray-600">
                            Ambil barang di lokasi yang ditentukan dengan menunjukkan kode peminjaman.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section id="testimonials" class="py-12 sm:py-16 bg-white">
        <div class="container mx-auto px-4 sm:px-6">
            <div class="text-center mb-10 sm:mb-16">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-darkBlue mb-3 sm:mb-4">
                    Apa Kata <span class="text-blue-600">Pengguna</span> Kami?
                </h2>
                <p class="text-base sm:text-lg text-gray-600 max-w-3xl mx-auto">
                    Siswa, guru, dan staf sekolah telah merasakan kemudahan dalam meminjam barang dengan sistem kami.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-gradient-to-br from-white to-blue-50 p-6 sm:p-8 rounded-xl sm:rounded-2xl card-hover border border-blue-100">
                    <div class="flex items-center mb-4 sm:mb-6">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-r from-blue-500 to-teal-400 rounded-full flex items-center justify-center text-white font-bold text-lg sm:text-xl">
                            RD
                        </div>
                        <div class="ml-3 sm:ml-4">
                            <h4 class="font-bold text-darkBlue text-base sm:text-lg">Rina Dewi</h4>
                            <p class="text-gray-600 text-sm">Guru Fisika</p>
                        </div>
                    </div>
                    <p class="text-gray-700 mb-4 sm:mb-6 text-sm sm:text-base">
                        "Sangat memudahkan untuk meminjam alat laboratorium. Tidak perlu lagi mengisi formulir manual yang bertele-tele. Prosesnya cepat dan terorganisir dengan baik."
                    </p>
                    <div class="flex justify-between items-center">
                        <div class="flex text-yellow-400 text-sm sm:text-base">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="text-xs sm:text-sm text-gray-500">2 hari lalu</div>
                    </div>
                </div>
                
                <!-- Testimonial 2 -->
                <div class="bg-gradient-to-br from-white to-teal-50 p-6 sm:p-8 rounded-xl sm:rounded-2xl card-hover border border-teal-100">
                    <div class="flex items-center mb-4 sm:mb-6">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-r from-orange-500 to-red-400 rounded-full flex items-center justify-center text-white font-bold text-lg sm:text-xl">
                            BS
                        </div>
                        <div class="ml-3 sm:ml-4">
                            <h4 class="font-bold text-darkBlue text-base sm:text-lg">Budi Santoso</h4>
                            <p class="text-gray-600 text-sm">Ketua OSIS</p>
                        </div>
                    </div>
                    <p class="text-gray-700 mb-4 sm:mb-6 text-sm sm:text-base">
                        "Sistem ini sangat membantu kegiatan OSIS. Meminjam peralatan multimedia untuk acara sekolah jadi lebih mudah dan teratur. Tidak ada lagi barang yang hilang!"
                    </p>
                    <div class="flex justify-between items-center">
                        <div class="flex text-yellow-400 text-sm sm:text-base">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <div class="text-xs sm:text-sm text-gray-500">1 minggu lalu</div>
                    </div>
                </div>
                
                <!-- Testimonial 3 -->
                <div class="bg-gradient-to-br from-white to-purple-50 p-6 sm:p-8 rounded-xl sm:rounded-2xl card-hover border border-purple-100">
                    <div class="flex items-center mb-4 sm:mb-6">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-r from-purple-500 to-pink-400 rounded-full flex items-center justify-center text-white font-bold text-lg sm:text-xl">
                            SD
                        </div>
                        <div class="ml-3 sm:ml-4">
                            <h4 class="font-bold text-darkBlue text-base sm:text-lg">Sari Damayanti</h4>
                            <p class="text-gray-600 text-sm">Siswa Kelas 12</p>
                        </div>
                    </div>
                    <p class="text-gray-700 mb-4 sm:mb-6 text-sm sm:text-base">
                        "Sebagai siswa, saya bisa dengan mudah meminjam laptop untuk mengerjakan tugas kelompok. Aplikasinya user-friendly dan notifikasinya sangat membantu."
                    </p>
                    <div class="flex justify-between items-center">
                        <div class="flex text-yellow-400 text-sm sm:text-base">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="text-xs sm:text-sm text-gray-500">3 hari lalu</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-12 sm:py-20 bg-gradient-to-r from-[#132440] to-blue-800 text-white">
        <div class="container mx-auto px-4 sm:px-6 text-center">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-4 sm:mb-6">Siap Meminjam Barang Sekolah?</h2>
            <p class="text-lg sm:text-xl mb-6 sm:mb-8 max-w-3xl mx-auto text-white/90">
                Bergabunglah dengan ratusan sekolah yang telah menggunakan sistem peminjaman digital kami.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-3 sm:gap-4 cta-buttons">
                <button class="bg-gradient-to-r from-orange-500 to-orange-400 text-white px-6 sm:px-10 py-3 sm:py-4 rounded-lg font-semibold hover:from-orange-600 hover:to-orange-500 transition-all text-base sm:text-lg shadow-lg animate-pulse-slow">
                    <i class="fas fa-hand-holding mr-2"></i>Ajukan Peminjaman
                </button>
                <button class="bg-white/20 backdrop-blur-sm text-white px-6 sm:px-10 py-3 sm:py-4 rounded-lg font-semibold hover:bg-white/30 transition-all text-base sm:text-lg border border-white/30">
                    <i class="fas fa-school mr-2"></i>Demo untuk Sekolah
                </button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#132440] text-white pt-8 sm:pt-12 pb-6 sm:pb-8">
        <div class="container mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 sm:gap-8 mb-6 sm:mb-8">
                <!-- Company Info -->
                <div>
                    <div class="flex items-center mb-4 sm:mb-6">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-gradient-to-r from-blue-600 to-teal-500 flex items-center justify-center shadow-md">
                            <i class="fas fa-school text-white text-lg sm:text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <span class="text-xl sm:text-2xl font-bold">School<span class="text-blue-400">Loan</span>Sphere</span>
                            <p class="text-xs text-gray-300 -mt-1 hidden sm:block">Sistem Peminjaman Barang Sekolah</p>
                        </div>
                    </div>
                    <p class="text-white/80 mb-4 sm:mb-6 text-sm">
                        Solusi digital untuk pengelolaan peminjaman barang sekolah yang efisien, transparan, dan modern.
                    </p>
                    <div class="flex space-x-3 sm:space-x-4">
                        <a href="#" class="w-8 h-8 sm:w-10 sm:h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-blue-500 transition-colors">
                            <i class="fab fa-facebook-f text-sm"></i>
                        </a>
                        <a href="#" class="w-8 h-8 sm:w-10 sm:h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-blue-400 transition-colors">
                            <i class="fab fa-twitter text-sm"></i>
                        </a>
                        <a href="#" class="w-8 h-8 sm:w-10 sm:h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-pink-600 transition-colors">
                            <i class="fab fa-instagram text-sm"></i>
                        </a>
                        <a href="#" class="w-8 h-8 sm:w-10 sm:h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors">
                            <i class="fab fa-linkedin-in text-sm"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg sm:text-xl font-bold mb-3 sm:mb-6 text-white">Tautan Cepat</h3>
                    <ul class="space-y-2 sm:space-y-3">
                        <li><a href="#home" class="text-white/80 hover:text-blue-300 transition-colors text-sm sm:text-base">Beranda</a></li>
                        <li><a href="#features" class="text-white/80 hover:text-blue-300 transition-colors text-sm sm:text-base">Fitur</a></li>
                        <li><a href="#products" class="text-white/80 hover:text-blue-300 transition-colors text-sm sm:text-base">Barang</a></li>
                        <li><a href="#process" class="text-white/80 hover:text-blue-300 transition-colors text-sm sm:text-base">Proses</a></li>
                        <li><a href="#testimonials" class="text-white/80 hover:text-blue-300 transition-colors text-sm sm:text-base">Testimoni</a></li>
                    </ul>
                </div>
                
                <!-- Categories -->
                <div>
                    <h3 class="text-lg sm:text-xl font-bold mb-3 sm:mb-6 text-white">Kategori Barang</h3>
                    <ul class="space-y-2 sm:space-y-3">
                        <li><a href="#" class="text-white/80 hover:text-blue-300 transition-colors text-sm sm:text-base">Laboratorium</a></li>
                        <li><a href="#" class="text-white/80 hover:text-blue-300 transition-colors text-sm sm:text-base">Olahraga</a></li>
                        <li><a href="#" class="text-white/80 hover:text-blue-300 transition-colors text-sm sm:text-base">Multimedia</a></li>
                        <li><a href="#" class="text-white/80 hover:text-blue-300 transition-colors text-sm sm:text-base">Perpustakaan</a></li>
                        <li><a href="#" class="text-white/80 hover:text-blue-300 transition-colors text-sm sm:text-base">Kesenian</a></li>
                    </ul>
                </div>
                
                <!-- Contact -->
                <div>
                    <h3 class="text-lg sm:text-xl font-bold mb-3 sm:mb-6 text-white">Kontak Sekolah</h3>
                    <ul class="space-y-3 sm:space-y-4">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt text-blue-300 mt-0.5 mr-2 sm:mr-3 text-sm"></i>
                            <span class="text-white/80 text-sm sm:text-base">Jl. Pendidikan No. 123, Jakarta Selatan</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone-alt text-blue-300 mr-2 sm:mr-3 text-sm"></i>
                            <span class="text-white/80 text-sm sm:text-base">+62 21 1234 5678</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope text-blue-300 mr-2 sm:mr-3 text-sm"></i>
                            <span class="text-white/80 text-sm sm:text-base">info@schoolloansphere.sch.id</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-clock text-blue-300 mr-2 sm:mr-3 text-sm"></i>
                            <span class="text-white/80 text-sm sm:text-base">Senin-Jumat: 07.00 - 16.00 WIB</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-white/20 pt-6 sm:pt-8 text-center">
                <p class="text-white/70 text-sm sm:text-base">&copy; <span id="current-year">2023</span> SchoolLoanSphere. Hak cipta dilindungi undang-undang.</p>
                <p class="text-white/50 text-xs sm:text-sm mt-1 sm:mt-2">Dikembangkan untuk mendukung pendidikan digital di Indonesia</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile sidebar functionality
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const closeSidebarButton = document.getElementById('close-sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const mobileSidebar = document.getElementById('mobile-sidebar');
        
        // Open sidebar
        mobileMenuButton.addEventListener('click', () => {
            mobileSidebar.classList.add('open');
            sidebarOverlay.classList.add('active');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        });
        
        // Close sidebar
        closeSidebarButton.addEventListener('click', closeSidebar);
        sidebarOverlay.addEventListener('click', closeSidebar);
        
        // Close sidebar with escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeSidebar();
            }
        });
        
        // Close sidebar function
        function closeSidebar() {
            mobileSidebar.classList.remove('open');
            sidebarOverlay.classList.remove('active');
            document.body.style.overflow = ''; // Restore scrolling
        }
        
        // Close sidebar when clicking on a link
        document.querySelectorAll('.sidebar-nav a').forEach(link => {
            link.addEventListener('click', () => {
                closeSidebar();
            });
        });
        
        // Animate numbers in stats section (from your existing code)
        function animateStats() {
            const statNumbers = document.querySelectorAll('.stat-number');
            
            statNumbers.forEach(stat => {
                const target = parseInt(stat.getAttribute('data-target'));
                const increment = target / 100;
                let current = 0;
                
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        stat.textContent = target;
                        clearInterval(timer);
                        stat.classList.add('visible');
                    } else {
                        stat.textContent = Math.floor(current);
                    }
                }, 20);
            });
        }
        
        // Check if element is in viewport
        function isInViewport(element) {
            const rect = element.getBoundingClientRect();
            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        }
        
        // Animate elements on scroll
        function animateOnScroll() {
            const animatedElements = document.querySelectorAll('.animate-slide-in');
            
            animatedElements.forEach(el => {
                if (isInViewport(el) && !el.classList.contains('animated')) {
                    el.style.animationDelay = '0.2s';
                    el.classList.add('animated');
                }
            });
            
            // Animate stats when in viewport
            const statsSection = document.querySelector('.stat-item');
            if (statsSection && isInViewport(statsSection)) {
                const statNumbers = document.querySelectorAll('.stat-number');
                if (!statNumbers[0].classList.contains('visible')) {
                    animateStats();
                }
            }
        }
        
        // Set current year in footer
        document.getElementById('current-year').textContent = new Date().getFullYear();
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                    
                    // Close sidebar if open
                    closeSidebar();
                }
            });
        });
        
        // Initialize animations on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Add animated class to elements already in viewport
            animateOnScroll();
            
            // Add scroll listener for animations
            window.addEventListener('scroll', animateOnScroll);
            
            // Add hover effect to cards
            const cards = document.querySelectorAll('.card-hover');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-10px)';
                    this.style.boxShadow = '0 20px 40px rgba(30, 64, 175, 0.15)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '';
                });
            });
            
            // Category filter buttons
            const categoryButtons = document.querySelectorAll('.category-badge');
            categoryButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    categoryButtons.forEach(btn => {
                        btn.classList.remove('ring-2', 'ring-blue-500');
                    });
                    // Add active class to clicked button
                    this.classList.add('ring-2', 'ring-blue-500');
                });
            });
            
            // Equipment item hover effect
            const equipmentItems = document.querySelectorAll('.equipment-item');
            equipmentItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    const icon = this.querySelector('i');
                    if (icon) {
                        icon.style.color = 'white';
                    }
                });
                
                item.addEventListener('mouseleave', function() {
                    const icon = this.querySelector('i');
                    if (icon) {
                        // Restore original color based on parent card
                        const parentCard = this.closest('.school-card');
                        if (parentCard.querySelector('.fa-laptop-code')) {
                            icon.style.color = '#2563eb'; // blue
                        } else if (parentCard.querySelector('.fa-flask')) {
                            icon.style.color = '#0d9488'; // teal
                        } else if (parentCard.querySelector('.fa-basketball-ball')) {
                            icon.style.color = '#f97316'; // orange
                        }
                    }
                });
            });
            
            document.querySelectorAll('.coming-soon').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    alert('Terima kasih! Fitur ini akan segera tersedia.');
                });
            });
        });
    </script>
</body>
</html>