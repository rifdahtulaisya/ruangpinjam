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


    <!-- LOGO -->
    <div class="px-8 py-8">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-500 text-white
                        flex items-center justify-center font-bold text-lg">
                R
            </div>
            <span class="text-xl font-bold text-indigo-600 tracking-wide">
                RuangPinjam
            </span>
        </div>

        <!-- GARIS -->
        <div class="mt-6 border-b border-indigo-200"></div>
    </div>

    <!-- MENU -->
    <nav class="px-6 text-sm space-y-2">

        <!-- DASHBOARD -->
        <a href="{{ route('admin.dashboard') }}"
        class="flex items-center gap-4 px-5 py-3 rounded-xl transition
        {{ Route::is('admin.dashboard') 
                ? 'bg-indigo-100 text-indigo-600 font-semibold shadow-sm' 
                : 'text-slate-600 hover:bg-indigo-100/70 hover:text-indigo-600' }}">

            <i class="fa-solid fa-chart-line"></i>
            Dashboard

        </a>

        <a href="{{ route('admin.dataperkelas.index') }}"
        class="flex items-center gap-4 px-5 py-3 rounded-xl transition
        {{ Route::is('admin.dataperkelas.*') 
                ? 'bg-indigo-100 text-indigo-600 font-semibold shadow-sm' 
                : 'text-slate-600 hover:bg-indigo-100/70 hover:text-indigo-600' }}">

            <i class="fa-solid fa-chart-line"></i>
            Data Per Kelas

        </a>

        <!-- DATA PEMINJAM -->
        <a href="{{ route('admin.datapeminjam.index') }}"
        class="flex items-center gap-4 px-5 py-3 rounded-xl transition
        {{ Route::is('admin.datapeminjam.*') 
                ? 'bg-indigo-100 text-indigo-600 font-semibold shadow-sm' 
                : 'text-slate-600 hover:bg-indigo-100/70 hover:text-indigo-600' }}">

            <i class="fa-solid fa-users"></i>
            Data Peminjam

        </a>


        
        <!-- DATA KATEGORI -->
        <a href="{{ route('admin.datakategori.index') }}"
        class="flex items-center gap-4 px-5 py-3 rounded-xl transition
        {{ Route::is('admin.datakategori.*') 
                ? 'bg-indigo-100 text-indigo-600 font-semibold shadow-sm' 
                : 'text-slate-600 hover:bg-indigo-100/70 hover:text-indigo-600' }}">

            <i class="fa-solid fa-layer-group"></i>
            Data Kategori

        </a>

        <a href="{{ route('admin.dataalat.index') }}"
        class="flex items-center gap-4 px-5 py-3 rounded-xl transition
        {{ Route::is('admin.dataalat.*') 
                ? 'bg-indigo-100 text-indigo-600 font-semibold shadow-sm' 
                : 'text-slate-600 hover:bg-indigo-100/70 hover:text-indigo-600' }}">

            <i class="fa-solid fa-layer-group"></i>
            Data Alat

        </a>

        <a href="#"
           class="flex items-center gap-4 px-5 py-3 rounded-xl
                  text-slate-600 hover:bg-indigo-100/70 hover:text-indigo-600 transition">
            <i class="fa-solid fa-file-signature"></i>
            Data Peminjaman
        </a>

        <a href="#"
           class="flex items-center gap-4 px-5 py-3 rounded-xl
                  text-slate-600 hover:bg-indigo-100/70 hover:text-indigo-600 transition">
            <i class="fa-solid fa-rotate-left"></i>
            Data Pengembalian
        </a>

        <a href="#"
           class="flex items-center gap-4 px-5 py-3 rounded-xl
                  text-slate-600 hover:bg-indigo-100/70 hover:text-indigo-600 transition">
            <i class="fa-solid fa-clock-rotate-left"></i>
            Log Aktivitas
        </a>

    </nav>

    <div class="absolute bottom-6 w-full px-6 z-50">

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit"
                onclick="confirmLogout()"
                class="relative z-50 w-full flex items-center gap-4 px-5 py-3 rounded-xl
                    bg-red-400/10 text-red-500
                    hover:bg-red-500/20
                    transition font-medium cursor-pointer">

                <i class="fa-solid fa-right-from-bracket pointer-events-none"></i>
                <span class="pointer-events-none">Logout</span>

            </button>

        </form>

    </div>


</aside>