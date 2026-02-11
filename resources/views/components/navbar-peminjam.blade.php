<!-- NAVBAR -->
<header
    class="fixed top-0 left-0 md:left-[300px] right-0 h-20
           bg-gradient-to-b from-indigo-50 to-purple-50 shadow-lg
           flex items-center justify-between
           px-4 md:px-8 z-30">

    <!-- LEFT SECTION - BURGER & TITLE -->
    <div class="flex items-center gap-3">
        <!-- BURGER BUTTON (mobile only) -->
        <button id="burgerBtn"
            class="md:hidden w-10 h-10 rounded-xl
                   bg-slate-100 hover:bg-slate-200
                   flex items-center justify-center
                   transition-colors">
            <i class="fa-solid fa-bars text-slate-700"></i>
        </button>

        <!-- PAGE TITLE -->
        <div>
            <h1 class="text-xl font-bold text-slate-800">
                @yield('title', '')
            </h1>
            <p class="text-xs text-slate-500 mt-0.5 hidden sm:block">
                @yield('subtitle', '')
            </p>
        </div>
    </div>

    <!-- RIGHT SECTION - NOTIFICATION & WELCOME -->
    <div class="flex items-center gap-4">
        <!-- NOTIFICATION -->
        <button
            class="relative w-10 h-10 rounded-xl
                   bg-slate-100 hover:bg-slate-200
                   flex items-center justify-center transition-all
                   hover:scale-105 active:scale-95">
            <i class="fa-regular fa-bell text-slate-600 text-lg"></i>
            <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white"></span>
        </button>
    </div>
</header>