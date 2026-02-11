<!-- SIDEBAR PETUGAS -->
<aside id="sidebar"
    class="fixed left-0 top-0 w-[300px] h-screen
           bg-gradient-to-b from-emerald-50 to-teal-50
           border-r border-emerald-200
           shadow-xl z-40 flex flex-col
           transform -translate-x-full
           md:translate-x-0
           transition-transform duration-300">

    <!-- LOGO -->
    <div class="px-8 py-8">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white
                        flex items-center justify-center font-bold text-lg">
                <i class="fa-solid fa-user-gear"></i>
            </div>
            <div class="flex flex-col">
                <span class="text-xl font-bold text-emerald-600 tracking-wide">
                    RuangPinjam
                </span>
                <span class="text-xs text-slate-500 font-medium">
                    Petugas
                </span>
            </div>
        </div>
        <div class="mt-6 border-b border-emerald-200"></div>
    </div>

    <!-- MENU -->
    <nav class="px-6 text-sm space-y-2">
        <!-- DASHBOARD -->
        <a href="{{ route('petugas.dashboard') }}"
           class="flex items-center gap-4 px-5 py-3 rounded-xl transition
                  {{ Route::is('petugas.dashboard') 
                     ? 'bg-emerald-100 text-emerald-600 font-semibold shadow-sm' 
                     : 'text-slate-600 hover:bg-emerald-100/70 hover:text-emerald-600' }}">
            <i class="fa-solid fa-chart-line w-5 text-center"></i>
            Dashboard
        </a>

        <!-- Di sidebar petugas, tambahkan menu: -->
<a href="{{ route('petugas.kelolapeminjaman.index') }}"
   class="flex items-center gap-4 px-5 py-3 rounded-xl transition
          {{ Route::is('petugas.kelolapeminjaman.*') 
             ? 'bg-emerald-100 text-emerald-600 font-semibold shadow-sm' 
             : 'text-slate-600 hover:bg-emerald-100/70 hover:text-emerald-600' }}">
    <i class="fa-solid fa-clipboard-check w-5 text-center"></i>
    Kelola Peminjaman
</a>



        <!-- LAPORAN -->
        <a href="{{ route('petugas.laporan.index') }}"
           class="flex items-center gap-4 px-5 py-3 rounded-xl transition
                  {{ Route::is('petugas.laporan.*') 
                     ? 'bg-emerald-100 text-emerald-600 font-semibold shadow-sm' 
                     : 'text-slate-600 hover:bg-emerald-100/70 hover:text-emerald-600' }}">
            <i class="fa-solid fa-file-lines w-5 text-center"></i>
            Laporan
        </a>
    </nav>

    <!-- LOGOUT -->
    <div class="mt-auto px-6 pb-6">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-4 px-5 py-3 rounded-xl
                       bg-red-400/10 text-red-500
                       hover:bg-red-500/20
                       transition font-medium">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>