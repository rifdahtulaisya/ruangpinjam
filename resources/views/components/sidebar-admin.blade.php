<!-- SIDEBAR ADMIN -->
<aside id="sidebar"
    class="fixed left-0 top-0 w-[300px] h-screen
           bg-gradient-to-b from-indigo-50 to-purple-50
           border-r border-indigo-200
           shadow-xl z-40 flex flex-col
           transform -translate-x-full
           md:translate-x-0
           transition-transform duration-300">

    <!-- LOGO -->
    <div class="px-8 py-8">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white
                        flex items-center justify-center font-bold text-lg">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div class="flex flex-col">
                <span class="text-xl font-bold text-indigo-600 tracking-wide">
                    RuangPinjam
                </span>
                <span class="text-xs text-slate-500 font-medium">
                    Administrator
                </span>
            </div>
        </div>
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
            <i class="fa-solid fa-chart-line w-5 text-center"></i>
            Dashboard
        </a>

        <!-- MANAJEMEN USER -->
        <div class="pt-2">
            <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">
                Manajemen User
            </p>
            
            <a href="{{ route('admin.datapeminjam.index') }}"
               class="flex items-center gap-4 px-5 py-3 rounded-xl transition ml-3
                      {{ Route::is('admin.datapeminjam.*') 
                         ? 'bg-indigo-100 text-indigo-600 font-semibold shadow-sm' 
                         : 'text-slate-600 hover:bg-indigo-100/70 hover:text-indigo-600' }}">
                <i class="fa-solid fa-users w-5 text-center"></i>
                Data Peminjam
            </a>

            <a href="{{ route('admin.datapetugas.index') }}"
               class="flex items-center gap-4 px-5 py-3 rounded-xl transition ml-3
                      {{ Route::is('admin.datapetugas.*') 
                         ? 'bg-indigo-100 text-indigo-600 font-semibold shadow-sm' 
                         : 'text-slate-600 hover:bg-indigo-100/70 hover:text-indigo-600' }}">
                <i class="fa-solid fa-user-tie w-5 text-center"></i>
                Data Petugas
            </a>
        </div>

        <!-- MANAJEMEN DATA -->
        <div class="pt-2">
            <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">
                Manajemen Data
            </p>
            
            <a href="{{ route('admin.dataperkelas.index') }}"
               class="flex items-center gap-4 px-5 py-3 rounded-xl transition ml-3
                      {{ Route::is('admin.dataperkelas.*') 
                         ? 'bg-indigo-100 text-indigo-600 font-semibold shadow-sm' 
                         : 'text-slate-600 hover:bg-indigo-100/70 hover:text-indigo-600' }}">
                <i class="fa-solid fa-school w-5 text-center"></i>
                Data Per Kelas
            </a>

            <a href="{{ route('admin.datakategori.index') }}"
               class="flex items-center gap-4 px-5 py-3 rounded-xl transition ml-3
                      {{ Route::is('admin.datakategori.*') 
                         ? 'bg-indigo-100 text-indigo-600 font-semibold shadow-sm' 
                         : 'text-slate-600 hover:bg-indigo-100/70 hover:text-indigo-600' }}">
                <i class="fa-solid fa-layer-group w-5 text-center"></i>
                Data Kategori
            </a>

            <a href="{{ route('admin.dataalat.index') }}"
               class="flex items-center gap-4 px-5 py-3 rounded-xl transition ml-3
                      {{ Route::is('admin.dataalat.*') 
                         ? 'bg-indigo-100 text-indigo-600 font-semibold shadow-sm' 
                         : 'text-slate-600 hover:bg-indigo-100/70 hover:text-indigo-600' }}">
                <i class="fa-solid fa-toolbox w-5 text-center"></i>
                Data Alat
            </a>
        </div>

        <!-- SISTEM -->
        <div class="pt-2">
            <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">
                Sistem
            </p>
            
            <a href="{{ route('admin.logaktivitas.index') }}"
               class="flex items-center gap-4 px-5 py-3 rounded-xl transition ml-3
                      {{ Route::is('admin.logaktivitas.*') 
                         ? 'bg-indigo-100 text-indigo-600 font-semibold shadow-sm' 
                         : 'text-slate-600 hover:bg-indigo-100/70 hover:text-indigo-600' }}">
                <i class="fa-solid fa-list-check w-5 text-center"></i>
                Log Aktivitas
            </a>
        </div>
    </nav>

    <!-- LOGOUT -->
    <div class="mt-auto px-6 pb-6">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-4 px-5 py-3 rounded-xl
                       bg-gradient-to-r from-red-400/10 to-pink-400/10
                       text-red-500 border border-red-200/50
                       hover:bg-gradient-to-r hover:from-red-400/20 hover:to-pink-400/20
                       transition-all duration-300 font-medium">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>