<header
    class="fixed top-0 left-0 md:left-[300px] right-0 h-20
           bg-gradient-to-b from-indigo-50 to-purple-50 shadow-lg
           flex items-center
           px-4 md:px-8 z-30
           rounded-b-2xl md:rounded-b-3xl
           mx-2 md:mx-4">

    <!-- LEFT -->
    <div class="flex items-center gap-3 ml-2 md:ml-4">

        <!-- BURGER BUTTON (mobile only) -->
        <button id="burgerBtn"
            class="md:hidden w-10 h-10 rounded-xl
                   bg-slate-100 hover:bg-slate-200
                   flex items-center justify-center
                   transition-colors">

            <i class="fa-solid fa-bars text-slate-700"></i>

        </button>

        <div class="flex flex-col">
            <h1 class="text-xl font-semibold text-slate-700">
                @yield('title', '')
            </h1>
            <p class="text-sm text-slate-600 mt-0.5">
                @yield('subtitle', '')
            </p>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="flex items-center gap-3 md:gap-6 ml-auto mr-2 md:mr-4">

        <!-- NOTIFICATION -->
        <button
            class="relative w-10 h-10 rounded-xl
                   bg-slate-100 hover:bg-slate-200
                   flex items-center justify-center 
                   transition-colors
                   focus:outline-none focus:ring-2 focus:ring-indigo-300">

            <i class="fa-regular fa-bell text-slate-600"></i>

            <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full"></span>

        </button>
        <!-- PROFILE -->
        <div class="flex items-center gap-3 bg-slate-100 px-3 py-2 rounded-xl cursor-pointer">

            <img src="https://i.pravatar.cc/40"
                 class="w-9 h-9 rounded-full object-cover">

            <div class="leading-tight hidden sm:block">
                <p class="text-sm font-semibold text-slate-700">
                    Admin
                </p>
                <p class="text-xs text-slate-500">
                    Administrator
                </p>
            </div>

        </div>
    </div>

</header>