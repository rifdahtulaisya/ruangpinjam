<!-- SIDEBAR -->
<aside id="sidebar"
    class="fixed left-0 top-0 w-[300px] h-screen
           bg-gradient-to-b from-indigo-50 to-purple-50
           border-r border-indigo-200
           shadow-xl
           z-40
           flex flex-col

           transform -translate-x-full
           md:translate-x-0
           transition-transform duration-300">


    <!-- LOGO SECTION - IMPROVED -->
    <div class="px-8 py-8">
        <div class="flex items-center gap-4">
            <!-- Logo Image Container -->
            <div class="w-14 h-14 rounded-2xl overflow-hidden
                        bg-white
                        flex items-center justify-center
                        shadow-lg shadow-indigo-200/50
                        border-2 border-white">
                <!-- Image Logo -->
                <img 
                    src="{{ asset('assets-admin/img/2.svg') }}" 
                    alt="RuangPinjam Logo"
                    class="w-full h-full object-cover"
                    onerror="this.onerror=null; this.src='{{ asset('assets-admin/img/2.svg') }}'; this.className='w-10 h-10 object-contain'"
                />
            </div>

            <!-- Logo Text -->
            <div class="flex flex-col">
                <span class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                    RuangPinjam
                </span>
                <span class="text-xs text-slate-500 -mt-1 font-medium">
                    Peminjaman Fasilitas
                </span>
            </div>
        </div>

        <!-- GARIS -->
        <div class="mt-8 border-b border-indigo-200"></div>
    </div>

    <!-- MENU -->
    <nav class="px-6 text-sm space-y-2">

       <!-- DASHBOARD -->
        <a href="{{ route('peminjam.dashboard') }}"
        class="block -mx-6 transition

        {{ Route::is('peminjam.dashboard') 
            ? 'bg-indigo-100 border-r-4 border-indigo-600' 
            : 'border-r-4 border-transparent hover:bg-indigo-100/70' }}">

            <div class="flex items-center gap-4 px-6 py-3
            {{ Route::is('peminjam.dashboard') 
                ? 'text-indigo-600 font-semibold' 
                : 'text-slate-600 hover:text-indigo-600' }}">
                
                <i class="fa-solid fa-chart-line"></i>
                Dashboard

            </div>

        </a>

        <!-- DAFTAR ALAT -->
        <a href="{{ route('peminjam.daftaralat.index') }}"
        class="block -mx-6 transition

        {{ Route::is('peminjam.daftaralat.index') 
            ? 'bg-indigo-100 border-r-4 border-indigo-600' 
            : 'border-r-4 border-transparent hover:bg-indigo-100/70' }}">

            <div class="flex items-center gap-4 px-6 py-3
            {{ Route::is('peminjam.daftaralat.index') 
                ? 'text-indigo-600 font-semibold' 
                : 'text-slate-600 hover:text-indigo-600' }}">
                
                <i class="fa-solid fa-screwdriver-wrench"></i>
                Daftar Alat

            </div>

        </a>
    
        <!-- DAFTAR ALAT -->
        <a href="{{ route('peminjam.peminjaman.index') }}"
        class="block -mx-6 transition

        {{ Route::is('peminjam.peminjaman.index') 
            ? 'bg-indigo-100 border-r-4 border-indigo-600' 
            : 'border-r-4 border-transparent hover:bg-indigo-100/70' }}">

            <div class="flex items-center gap-4 px-6 py-3
            {{ Route::is('peminjam.peminjaman.index') 
                ? 'text-indigo-600 font-semibold' 
                : 'text-slate-600 hover:text-indigo-600' }}">
                
                <i class="fa-solid fa-screwdriver-wrench"></i>
                Riwayat Peminjaman

            </div>

        </a>
    </nav>

    <!-- Logout Button -->
    <div class="absolute bottom-6 w-full px-6 z-50">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                onclick="confirmLogout()"
                class="relative z-50 w-full flex items-center gap-4 px-5 py-3 rounded-xl
                    bg-gradient-to-r from-red-50 to-orange-50 
                    text-red-500 border border-red-200
                    hover:from-red-100 hover:to-orange-100 hover:border-red-300
                    hover:shadow-md hover:shadow-red-200/50
                    transition-all duration-300 font-medium cursor-pointer
                    group">

                <i class="fa-solid fa-right-from-bracket pointer-events-none 
                          group-hover:translate-x-1 transition-transform"></i>
                <span class="pointer-events-none">Keluar</span>

            </button>
        </form>
    </div>

</aside>