<header class="h-16 lg:h-20 bg-white/80 backdrop-blur-xl border-b border-slate-200 flex items-center justify-between px-3 lg:px-8">

    {{-- LEFT --}}
    <div class="flex items-center gap-3 lg:gap-4">

        {{-- MOBILE BUTTON --}}
        <button
            id="menuButton"
            class="lg:hidden w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-lg"
        >
            ☰
        </button>

        <div class="leading-tight">

            <h1 class="text-lg lg:text-2xl font-bold text-slate-800">
                @if(auth()->user()->role == 'admin')
                    Admin Dashboard
                @else
                    User Dashboard
                @endif
            </h1>

            <p class="text-slate-500 text-xs lg:text-sm hidden sm:block">
                Welcome back, {{ auth()->user()->name }} 👋
            </p>

        </div>

    </div>

    {{-- RIGHT --}}
    <div class="flex items-center gap-2 lg:gap-4">

        {{-- SEARCH --}}
        <div class="hidden lg:flex items-center bg-slate-100 rounded-xl px-3 h-10 w-64">

            <svg xmlns="http://www.w3.org/2000/svg"
                 class="w-4 h-4 text-slate-400 mr-2"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 105.5 5.5a7.5 7.5 0 0011.15 11.15z" />

            </svg>

            <input
                type="text"
                placeholder="Search..."
                class="bg-transparent outline-none w-full text-sm text-slate-700 placeholder:text-slate-400"
            >

        </div>

        {{-- NOTIFICATION --}}
        <button class="relative">

            <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-slate-100 hover:bg-slate-200 transition flex items-center justify-center text-lg lg:text-xl">
                🔔
            </div>

            <span class="absolute -top-1 -right-1 min-w-[1rem] h-4 lg:h-5 bg-red-500 text-white text-[10px] rounded-full flex items-center justify-center shadow px-1">
                {{ $chatNotificationsCount ?? 0 }}
            </span>

        </button>

        {{-- USER DROPDOWN --}}
        <div class="relative">

            <button
                id="userDropdownBtn"
                class="flex items-center gap-2 bg-slate-100 hover:bg-slate-200 transition rounded-xl p-2 pr-3 cursor-pointer"
                onclick="toggleUserDropdown()"
            >

                <img
                    src="https://i.pravatar.cc/100"
                    class="w-9 h-9 lg:w-12 lg:h-12 rounded-lg object-cover"
                >

                <div class="hidden lg:block leading-tight">

                    <h4 class="font-semibold text-slate-700 text-sm lg:text-base">
                        {{ auth()->user()->name }}
                    </h4>

                    <p class="text-xs text-slate-500 capitalize">
                        @if(auth()->user()->role == 'admin')
                            Super Admin
                        @else
                            General User
                        @endif
                    </p>

                </div>


            </button>

            {{-- DROPDOWN MENU --}}
            <div
                id="userDropdownMenu"
                class="hidden absolute top-full right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden z-50"
                onclick="event.stopPropagation()"
            >

                {{-- USER INFO (mobile only) --}}
                <div class="lg:hidden px-4 py-3 border-b border-slate-100">
                    <h4 class="font-semibold text-slate-700 text-sm">
                        {{ auth()->user()->name }}
                    </h4>
                    <p class="text-xs text-slate-500 capitalize mt-1">
                        @if(auth()->user()->role == 'admin')
                            Super Admin
                        @else
                            General User
                        @endif
                    </p>
                </div>

                {{-- MENU ITEMS --}}
                <div class="py-2">

                    {{-- PROFILE --}}
                    <a href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-100 transition">
                        <span class="text-lg">👤</span>
                        <span>Profile</span>
                    </a>

                    {{-- SETTINGS --}}
                    <a href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-100 transition">
                        <span class="text-lg">⚙️</span>
                        <span>Settings</span>
                    </a>

                    <hr class="my-1 border-slate-200">

                    {{-- LOGOUT --}}
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button
                            type="submit"
                            class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition"
                        >
                            <span class="text-lg">🚪</span>
                            <span>Logout</span>
                        </button>
                    </form>

                </div>

            </div>

        </div>

    </div>

</header>