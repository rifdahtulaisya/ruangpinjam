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