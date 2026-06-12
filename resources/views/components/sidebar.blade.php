<aside id="sidebar"
    class="fixed lg:static z-40 w-72 h-screen bg-gradient-to-b from-[#0d1b3d] via-[#132850] to-[#091224] text-white shadow-2xl transform -translate-x-full lg:translate-x-0 transition duration-300 overflow-y-auto">

    {{-- ========================= --}}
    {{-- LOGO --}}
    {{-- ========================= --}}
    <div class="h-24 px-8 flex items-center border-b border-white/10">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-amber-400/20 flex items-center justify-center text-3xl">
                🏛️
            </div>
            <div>
                <h1 class="text-2xl font-black tracking-wide">BIOS</h1>
                <p class="text-xs text-slate-300">Integrated Operation System</p>
            </div>
        </div>
    </div>

    {{-- ========================= --}}
    {{-- USER PROFILE --}}
    {{-- ========================= --}}
    <div class="px-4 mt-6">
        <div class="bg-white/10 rounded-3xl p-4 border border-white/10">
            <div class="flex items-center gap-4">
                <img src="https://i.pravatar.cc/100" class="w-14 h-14 rounded-2xl object-cover">
                <div>
                    <h3 class="font-bold text-lg">{{ auth()->user()->name }}</h3>
                    <p class="text-slate-300 text-sm capitalize">{{ auth()->user()->role }}</p>
                    <p class="text-slate-400 text-xs">{{ auth()->user()->divisi }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ========================= --}}
    {{-- MENU --}}
    {{-- ========================= --}}
    <div class="px-4 mt-6">

        <p class="text-xs uppercase text-slate-400 px-4 mb-3 tracking-widest">Main Menu</p>

        <nav class="space-y-2">

            {{-- ====================================================== --}}
            {{-- SUPER ADMIN --}}
            {{-- ====================================================== --}}
            @if(auth()->user()->role == 'super_admin')

                <a href="{{ route('superadmin.users.index') }}"
                    class="group flex items-center gap-4 px-5 py-4 rounded-2xl transition-all duration-300
                                                                                   {{ request()->routeIs('superadmin.users.*') ? 'bg-white text-slate-800 shadow-xl' : 'hover:bg-white/10' }}">
                    <div
                        class="w-11 h-11 rounded-xl flex items-center justify-center {{ request()->routeIs('superadmin.users.*') ? 'bg-indigo-100' : 'bg-white/10' }}">
                        👥</div>
                    <div>
                        <p class="font-bold">User Management</p>
                        <small class="opacity-70">Manage all users</small>
                    </div>
                </a>

            @endif


            {{-- ====================================================== --}}
            {{-- DIREKSI, MANAGER & ADMIN DIVISI --}}
            {{-- ====================================================== --}}
            @if(in_array(auth()->user()->role, ['admin_divisi', 'manager', 'direksi']))


                {{-- DASHBOARD --}}
                <a href="{{ route('manager.dashboard') }}"
                    class="group flex items-center gap-4 px-5 py-4 rounded-2xl transition-all duration-300
                                                                                   {{ request()->routeIs('manager.dashboard') ? 'bg-white text-slate-800 shadow-xl' : 'hover:bg-white/10' }}">
                    <div
                        class="w-11 h-11 rounded-xl flex items-center justify-center {{ request()->routeIs('manager.dashboard') ? 'bg-indigo-100' : 'bg-white/10' }}">
                        🏠</div>
                    <div>
                        <p class="font-bold">Dashboard</p>
                        <small class="opacity-70">Division overview</small>
                    </div>
                </a>

                {{-- PROJECT --}}
                <a href="{{ route('project.index') }}"
                    class="group flex items-center gap-4 px-5 py-4 rounded-2xl transition-all duration-300
                                                                                   {{ request()->routeIs('project.*') ? 'bg-white text-slate-800 shadow-xl' : 'hover:bg-white/10' }}">
                    <div
                        class="w-11 h-11 rounded-xl flex items-center justify-center {{ request()->routeIs('project.*') ? 'bg-indigo-100' : 'bg-white/10' }}">
                        ✅</div>
                    <div>
                        <p class="font-bold">Project</p>
                        <small class="opacity-70">Task management</small>
                    </div>
                </a>

                {{-- ✅ DAILY ROUTINE — MANAGER --}}
                <a href="{{ route('daily-routine.index') }}"
                    class="group flex items-center gap-4 px-5 py-4 rounded-2xl transition-all duration-300
                                                                                   {{ request()->routeIs('daily-routine.index*') ? 'bg-white text-slate-800 shadow-xl' : 'hover:bg-white/10' }}">
                    <div
                        class="w-11 h-11 rounded-xl flex items-center justify-center {{ request()->routeIs('daily-routine.index*') ? 'bg-violet-100' : 'bg-white/10' }}">
                        🔁</div>
                    <div>
                        <p class="font-bold">Daily Routine</p>
                        <small class="opacity-70">Tugas rutin harian</small>
                    </div>
                </a>

                <a href="{{ route('daily-routine.history') }}"
                    class="group flex items-center gap-4 px-5 py-4 rounded-2xl transition-all duration-300
                                                                                   {{ request()->routeIs('daily-routine.history*') ? 'bg-white text-slate-800 shadow-xl' : 'hover:bg-white/10' }}">
                    <div
                        class="w-11 h-11 rounded-xl flex items-center justify-center {{ request()->routeIs('daily-routine.history*') ? 'bg-violet-100' : 'bg-white/10' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold">History Routine</p>
                        <small class="opacity-70">Riwayat Rutinitas </small>
                    </div>
                </a>

                {{-- MANAGEMENT TIM (MANAGER) --}}
                <a href="{{ route('manager.management.team') }}"
                    class="group flex items-center gap-4 px-5 py-4 rounded-2xl transition-all duration-300
                                                                                   {{ request()->routeIs('manager.management.team') ? 'bg-white text-slate-800 shadow-xl' : 'hover:bg-white/10' }}">
                    <div
                        class="w-11 h-11 rounded-xl flex items-center justify-center {{ request()->routeIs('manager.management.team') ? 'bg-indigo-100' : 'bg-white/10' }}">
                        👥</div>
                    <div>
                        <p class="font-bold">Management Tim</p>
                        <small class="opacity-70">Kelola Supervisor & Tim</small>
                    </div>
                </a>
            @endif

            {{-- ====================================================== --}}
            {{-- STAFF --}}
            {{-- ====================================================== --}}
            @if(auth()->user()->role == 'staff')

                {{-- MY TASK --}}
                <a href="{{ route('staff.project.index') }}"
                    class="group flex items-center gap-4 px-5 py-4 rounded-2xl transition-all duration-300
                                                                                   {{ request()->routeIs('staff.project.*') ? 'bg-white text-slate-800 shadow-xl' : 'hover:bg-white/10' }}">
                    <div
                        class="w-11 h-11 rounded-xl flex items-center justify-center {{ request()->routeIs('staff.project.*') ? 'bg-indigo-100' : 'bg-white/10' }}">
                        📝</div>
                    <div>
                        <p class="font-bold">My Project</p>
                        <small class="opacity-70">Todo List</small>
                    </div>
                </a>

                {{-- ✅ DAILY ROUTINE — STAFF --}}
                <a href="{{ route('daily-routine.index') }}"
                    class="group flex items-center gap-4 px-5 py-4 rounded-2xl transition-all duration-300
                                                                                   {{ request()->routeIs('daily-routine.index*') ? 'bg-white text-slate-800 shadow-xl' : 'hover:bg-white/10' }}">
                    <div
                        class="w-11 h-11 rounded-xl flex items-center justify-center {{ request()->routeIs('daily-routine.index*') ? 'bg-violet-100' : 'bg-white/10' }}">
                        🔁</div>
                    <div>
                        <p class="font-bold">Daily Routine</p>
                        <small class="opacity-70">Rutinitas harianku</small>
                    </div>
                    <a href="{{ route('daily-routine.history') }}"
                        class="group flex items-center gap-4 px-5 py-4 rounded-2xl transition-all duration-300
                                                                                   {{ request()->routeIs('daily-routine.history*') ? 'bg-white text-slate-800 shadow-xl' : 'hover:bg-white/10' }}">
                        <div
                            class="w-11 h-11 rounded-xl flex items-center justify-center {{ request()->routeIs('daily-routine.history*') ? 'bg-violet-100' : 'bg-white/10' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold">History Routine</p>
                            <small class="opacity-70">Riwayat Rutinitas </small>
                        </div>
                    </a>
                    <!-- <a href="{{ route('daily-routine.history') }}"
                                                                    class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-slate-100">

                                                                    <i class="ti ti-history text-lg"></i>

                                                                    <span>History Routine</span>

                                                                </a> -->
            @endif

            {{-- ====================================================== --}}
            {{-- SUPERVISOR --}}
            {{-- ====================================================== --}}
            @if(auth()->user()->role == 'supervisor')

                {{-- DASHBOARD (supervisor) --}}
                <!-- <a href="{{ route('supervisor.dashboard') }}"
                    class="group flex items-center gap-4 px-5 py-4 rounded-2xl transition-all duration-300
                                                                                   {{ request()->routeIs('supervisor.dashboard') ? 'bg-white text-slate-800 shadow-xl' : 'hover:bg-white/10' }}">
                    <div
                        class="w-11 h-11 rounded-xl flex items-center justify-center {{ request()->routeIs('supervisor.dashboard') ? 'bg-indigo-100' : 'bg-white/10' }}">
                        🧭</div>
                    <div>
                        <p class="font-bold">Supervisor Dashboard</p>
                        <small class="opacity-70">Overview divisi Anda</small>
                    </div>
                </a> -->

                {{-- MY PROJECT --}}
                <a href="{{ route('supervisor.project.index') }}"
                    class="group flex items-center gap-4 px-5 py-4 rounded-2xl transition-all duration-300
                                                                                   {{ request()->routeIs('supervisor.project.*') ? 'bg-white text-slate-800 shadow-xl' : 'hover:bg-white/10' }}">
                    <div
                        class="w-11 h-11 rounded-xl flex items-center justify-center {{ request()->routeIs('supervisor.project.*') ? 'bg-indigo-100' : 'bg-white/10' }}">
                        📝</div>
                    <div>
                        <p class="font-bold">My Project</p>
                        <small class="opacity-70">Task list divisi</small>
                    </div>
                </a>

                {{-- ✅ DAILY ROUTINE — SUPERVISOR --}}
                <a href="{{ route('daily-routine.index') }}"
                    class="group flex items-center gap-4 px-5 py-4 rounded-2xl transition-all duration-300
                                                                                   {{ request()->routeIs('daily-routine.index*') ? 'bg-white text-slate-800 shadow-xl' : 'hover:bg-white/10' }}">
                    <div
                        class="w-11 h-11 rounded-xl flex items-center justify-center {{ request()->routeIs('daily-routine.index*') ? 'bg-violet-100' : 'bg-white/10' }}">
                        🔁</div>
                    <div>
                        <p class="font-bold">Daily Routine</p>
                        <small class="opacity-70">Rutinitas divisi</small>
                    </div>
                </a>

                <a href="{{ route('daily-routine.history') }}"
                    class="group flex items-center gap-4 px-5 py-4 rounded-2xl transition-all duration-300
                                                                                   {{ request()->routeIs('daily-routine.history*') ? 'bg-white text-slate-800 shadow-xl' : 'hover:bg-white/10' }}">
                    <div
                        class="w-11 h-11 rounded-xl flex items-center justify-center {{ request()->routeIs('daily-routine.history*') ? 'bg-violet-100' : 'bg-white/10' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold">History Routine</p>
                        <small class="opacity-70">Riwayat Rutinitas</small>
                    </div>
                </a>

            @endif

        </nav>

    </div>

    {{-- ========================= --}}
    {{-- LIVE FEED --}}
    {{-- ========================= --}}
<div class="px-4 mt-10 mb-6 space-y-4">

    {{-- LIVE ACTIVITY --}}
    <div class="bg-white/5 rounded-3xl p-4 border border-white/10">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold">Live Activity</h3>
            <span class="w-3 h-3 rounded-full bg-green-400 animate-pulse"></span>
        </div>

        <div class="space-y-4">
            <div class="bg-white/5 rounded-2xl p-3">
                <p class="font-semibold text-sm">Task updated successfully</p>
                <small class="text-slate-400">2 minutes ago</small>
            </div>

            <div class="bg-white/5 rounded-2xl p-3">
                <p class="font-semibold text-sm">New report uploaded</p>
                <small class="text-slate-400">5 minutes ago</small>
            </div>

            <div class="bg-white/5 rounded-2xl p-3">
                <p class="font-semibold text-sm">KPI updated</p>
                <small class="text-slate-400">10 minutes ago</small>
            </div>
        </div>
    </div>

    {{-- LOGOUT CARD --}}
    <div class="bg-white/5 rounded-3xl border border-white/10 p-2">
        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button
                type="submit"
                class="w-full flex items-center justify-between gap-3 px-4 py-3 rounded-2xl
                       text-red-500 hover:bg-red-500/10 transition
                       font-medium"
            >
                <div class="flex items-center gap-3">
                    <span class="text-lg"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-door-enter">
	<path stroke="none" d="M0 0h24v24H0z" fill="none" />
	<path d="M13 12v.01" />
	<path d="M3 21h18" />
	<path d="M5 21v-16a2 2 0 0 1 2 -2h6m4 10.5v7.5" />
	<path d="M21 7h-7m3 -3l-3 3l3 3" />
</svg></span>
                </div>

                <span class="text-xs text-red-400">Sign out</span>
            </button>
        </form>
    </div>

</div>
</aside>