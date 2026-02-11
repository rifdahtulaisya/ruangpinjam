

<!-- SIDEBAR PETUGAS -->
<aside id="sidebar"
    class="fixed left-0 top-0 w-[300px] h-screen
           bg-gradient-to-b from-emerald-50 to-teal-50
           border-r border-emerald-200
           shadow-xl z-40 flex flex-col
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
                <span class="text-xl font-bold text-emerald-600 tracking-wide">
                    RuangPinjam
                </span>
                <span class="text-xs text-slate-500 font-medium">
                    Petugas
                </span>
            </div>
        </div>

        <!-- GARIS -->
        <div class="mt-8 border-b border-indigo-200"></div>
    </div>
    <!-- MENU -->
    <nav class="px-6 text-sm space-y-2 flex-1 overflow-y-auto">
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

    <!-- BAGIAN PROFIL & LOGOUT -->
    <div class="px-6 pb-6 space-y-3">
        
        <!-- PROFIL CARD - SELURUH DIV CLICKABLE -->
        <a href="{{ route('peminjam.profile.index') }}" 
           class="block bg-white/80 backdrop-blur-sm rounded-xl p-4 border border-emerald-200 shadow-sm 
                  hover:bg-emerald-50/80 hover:border-emerald-300 hover:shadow-md
                  transition-all duration-200 group">
            <div class="flex items-center gap-3">
                <!-- Avatar dengan badge role -->
                <div class="relative">
                    <img src="https://i.pravatar.cc/40?u={{ Auth::id() }}" 
                         class="w-12 h-12 rounded-full object-cover border-2 border-emerald-400 group-hover:border-emerald-500 transition-all"
                         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Peminjam') }}&background=10b981&color=fff'">
                    <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 border-2 border-white rounded-full"></span>
                </div>
                
                <!-- Info User -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-slate-800 truncate group-hover:text-emerald-700">
                            {{ Auth::user()->name ?? 'Peminjam' }}
                        </p>
                        <i class="fa-solid fa-chevron-right text-emerald-400 text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i>
                    </div>
                    <p class="text-xs text-slate-500 truncate flex items-center gap-1">
                        <i class="fa-regular fa-envelope text-emerald-500"></i>
                        {{ Auth::user()->email ?? 'peminjam@example.com' }}
                    </p>
                    <div class="flex items-center gap-1 mt-1">
                        <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-full text-[10px] font-medium group-hover:bg-emerald-200">
                            <i class="fa-solid fa-shield-alt mr-1"></i>Peminjam
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Tooltip hint (optional) -->
            <div class="text-[10px] text-emerald-500 text-right mt-1 opacity-0 group-hover:opacity-100 transition-opacity">
                <i class="fa-regular fa-eye mr-1"></i>Lihat Profil
            </div>
        </a>

        <!-- BUTTON LOGOUT -->
        <form method="POST" action="{{ route('logout') }}" id="logout-form">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-4 px-5 py-3 rounded-xl
                       bg-red-400/10 text-red-500
                       hover:bg-red-500 hover:text-white
                       transition-all duration-200 font-medium group">
                <i class="fa-solid fa-right-from-bracket group-hover:translate-x-1 transition-transform"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>