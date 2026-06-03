@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 md:px-6 py-8 space-y-6">

        {{-- HEADER --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Daily Routine</h1>
                <p class="text-sm text-slate-500 mt-0.5">Kelola tugas rutin harian tim kamu</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <div class="bg-white border border-slate-200 rounded-2xl px-4 py-2.5 text-center shadow-sm min-w-[64px]">
                    <p class="text-xl font-black text-slate-800">{{ $routines->count() }}</p>
                    <p class="text-xs text-slate-400">Total</p>
                </div>
                <div class="bg-emerald-50 border border-emerald-200 rounded-2xl px-4 py-2.5 text-center min-w-[64px]">
                    <p class="text-xl font-black text-emerald-700">{{ $routines->where('status', 'done')->count() }}</p>
                    <p class="text-xs text-emerald-500">Done</p>
                </div>
                <div class="bg-blue-50 border border-blue-200 rounded-2xl px-4 py-2.5 text-center min-w-[64px]">
                    <p class="text-xl font-black text-blue-700">{{ $routines->where('status', 'progress')->count() }}</p>
                    <p class="text-xs text-blue-500">Progress</p>
                </div>
                <div class="bg-yellow-50 border border-yellow-200 rounded-2xl px-4 py-2.5 text-center min-w-[64px]">
                    <p class="text-xl font-black text-yellow-700">{{ $routines->where('status', 'pending')->count() }}</p>
                    <p class="text-xs text-yellow-500">Pending</p>
                </div>
            </div>
        </div>

        {{-- TOOLBAR --}}
        <div
            class="bg-white border border-slate-200 rounded-2xl px-5 py-4 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex flex-wrap gap-2">
                <button onclick="filterStatus('all')" id="filter-all"
                    class="filter-btn px-4 py-2 rounded-2xl text-sm font-semibold transition bg-violet-600 text-white">Semua</button>
                <button onclick="filterStatus('pending')" id="filter-pending"
                    class="filter-btn px-4 py-2 rounded-2xl text-sm font-semibold transition bg-slate-100 text-slate-700 hover:bg-slate-200">Pending</button>
                <button onclick="filterStatus('progress')" id="filter-progress"
                    class="filter-btn px-4 py-2 rounded-2xl text-sm font-semibold transition bg-slate-100 text-slate-700 hover:bg-slate-200">Progress</button>
                <button onclick="filterStatus('done')" id="filter-done"
                    class="filter-btn px-4 py-2 rounded-2xl text-sm font-semibold transition bg-slate-100 text-slate-700 hover:bg-slate-200">Done</button>
            </div>
            <div class="flex items-center gap-3">
                <input id="searchRoutine" type="search" placeholder="Cari routine..."
                    class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:border-violet-500 focus:bg-white focus:outline-none w-48">
                @if(auth()->user()->role === 'manager')
                    <button onclick="showDailyRoutineForm()"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold transition whitespace-nowrap shadow-lg shadow-violet-100">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Create Routine
                    </button>
                @endif
            </div>
        </div>

        {{-- TABLE --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            @if($routines->count() > 0)
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="w-10 px-4 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">#
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Judul
                                Routine</th>
                            <th class="w-36 px-4 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                Assigned To</th>
                            <th class="w-28 px-4 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                Hari</th>
                            <th class="w-36 px-4 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                Checklist</th>
                            <th class="w-24 px-4 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                Status</th>
                            <th
                                class="w-28 px-4 py-3 text-center text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="routineTbody">
                        @foreach($routines as $i => $routine)
                            @php
                                $total = $routine->checklists->count();
                                $doneCount = $routine->checklists->where('is_done', true)->count();
                                $pct = $total > 0 ? round(($doneCount / $total) * 100) : 0;
                                $statusClass = match ($routine->status) {
                                    'progress' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'done' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    default => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                };
                                $barClass = match ($routine->status) {
                                    'progress' => 'bg-blue-400',
                                    'done' => 'bg-emerald-400',
                                    default => 'bg-violet-400',
                                };
                                $today = strtolower(now()->locale('id')->translatedFormat('l'));

$overdue = strtolower($routine->deadline) === $today
    && $routine->status !== 'done';
                                $isManager = auth()->user()->role === 'manager';
                                $isStaff = auth()->user()->role === 'staff';
                            @endphp

                            {{-- MAIN ROW --}}
                            <tr class="routine-row border-b border-slate-100 hover:bg-slate-50/60 transition cursor-pointer"
                                data-status="{{ $routine->status }}" data-title="{{ strtolower($routine->title) }}"
                                onclick="toggleChecklist({{ $routine->id }})">

                                <td class="px-4 py-3 text-slate-400 text-xs text-center">{{ $i + 1 }}</td>

                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        {{-- Expand arrow --}}
                                        <span id="arrow-{{ $routine->id }}"
                                            class="text-slate-300 transition-transform duration-200 shrink-0 text-base leading-none">
                                            ›
                                        </span>
                                        <div class="min-w-0">
                                            <p class="font-semibold text-slate-800 truncate">{{ $routine->title }}</p>
                                            @if($routine->description)
                                                <p class="text-xs text-slate-400 truncate mt-0.5">{{ $routine->description }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-3" onclick="event.stopPropagation()">
                                    @if($routine->user)
                                        <div class="flex items-center gap-2">
                                            <img src="https://i.pravatar.cc/100?u={{ $routine->user_id }}"
                                                class="w-7 h-7 rounded-full border border-slate-200 shrink-0">
                                            <span class="text-xs text-slate-700 font-medium truncate">{{ $routine->user->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Belum assign</span>
                                    @endif
                                </td>

                              <td class="px-4 py-3 text-center">
    @if($routine->deadline)

        @php
            $days = array_map('trim', explode(',', $routine->deadline));

            $shortDays = [
                'senin' => 'Sen',
                'selasa' => 'Sel',
                'rabu' => 'Rab',
                'kamis' => 'Kam',
                'jumat' => 'Jum',
                'sabtu' => 'Sab',
                'minggu' => 'Min',
            ];

            $dayColors = [
                'senin' => 'bg-blue-100 text-blue-700',
                'selasa' => 'bg-emerald-100 text-emerald-700',
                'rabu' => 'bg-violet-100 text-violet-700',
                'kamis' => 'bg-amber-100 text-amber-700',
                'jumat' => 'bg-rose-100 text-rose-700',
                'sabtu' => 'bg-cyan-100 text-cyan-700',
                'minggu' => 'bg-slate-200 text-slate-700',
            ];
        @endphp

        <div class="flex flex-wrap justify-center gap-1 max-w-[140px] mx-auto">

            @foreach($days as $day)

                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold {{ $dayColors[$day] ?? 'bg-slate-100 text-slate-600' }}">
                    {{ $shortDays[$day] ?? ucfirst($day) }}
                </span>

            @endforeach

        </div>

    @else

        <span class="text-slate-400 text-xs">-</span>

    @endif
</td>

                                <td class="px-4 py-3" onclick="event.stopPropagation()">
                                    @if($total > 0)
                                        <div class="flex items-center justify-between text-xs text-slate-500 mb-1">
                                            <span>{{ $doneCount }}/{{ $total }}</span>
                                            <span>{{ $pct }}%</span>
                                        </div>
                                        <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full {{ $barClass }} rounded-full transition-all duration-500"
                                                style="width:{{ $pct }}%"></div>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-300">Tidak ada</span>
                                    @endif
                                </td>

                                <td class="px-4 py-3" onclick="event.stopPropagation()">

                                    @if($isManager)

                                                <div class="flex gap-2">

                                                    {{-- Pending --}}
                                                    <div class="tooltip-group">

                                                        <button onclick="updateRoutineStatus({{ $routine->id }},'pending')"
                                                            class="w-9 h-9 rounded-2xl border transition flex items-center justify-center
                                                                                                                                                                                                                                                                                                                                                                                                                        {{ $routine->status === 'pending'
                                        ? 'bg-yellow-50 text-yellow-700 border-yellow-300'
                                        : 'bg-white text-slate-400 border-slate-200 hover:bg-yellow-50 hover:text-yellow-600 hover:border-yellow-200' }}">
                                                            P

                                                        </button>

                                                        <span class="tooltip-text">
                                                            Set Pending
                                                        </span>

                                                    </div>

                                                    {{-- Progress --}}
                                                    <div class="tooltip-group">

                                                        <button onclick="updateRoutineStatus({{ $routine->id }},'progress')"
                                                            class="w-9 h-9 rounded-2xl border transition flex items-center justify-center
                                                                                                                                                                                                                                                                                                                                                                                                                        {{ $routine->status === 'progress'
                                        ? 'bg-blue-50 text-blue-700 border-blue-300'
                                        : 'bg-white text-slate-400 border-slate-200 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200' }}">

                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                                stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                                            </svg>

                                                        </button>

                                                        <span class="tooltip-text">
                                                            Set Progress
                                                        </span>

                                                    </div>

                                                    {{-- Done --}}
                                                    <div class="tooltip-group">

                                                        <button onclick="updateRoutineStatus({{ $routine->id }},'done')"
                                                            class="w-9 h-9 rounded-2xl border transition flex items-center justify-center
                                                                                                                                                                                                                                                                                                                                                                                                                        {{ $routine->status === 'done'
                                        ? 'bg-emerald-50 text-emerald-700 border-emerald-300'
                                        : 'bg-white text-slate-400 border-slate-200 hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200' }}">

                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                                stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                            </svg>

                                                        </button>

                                                        <span class="tooltip-text">
                                                            Set Done
                                                        </span>

                                                    </div>

                                                </div>

                                    @else

                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-semibold border {{ $statusClass }}">
                                            {{ ucfirst($routine->status) }}
                                        </span>

                                    @endif

                                </td>

                                <td class="px-4 py-3" onclick="event.stopPropagation()">
                                    <div class="flex items-center justify-center gap-1.5">
                                        @if($isManager)
                                            {{-- Reassign button --}}
                                            <button onclick="openReassignModal({{ $routine->id }}, {{ $routine->user_id ?? 'null' }})"
                                                class="w-8 h-8 rounded-xl border border-slate-200 hover:bg-violet-50 hover:border-violet-200 text-slate-400 hover:text-violet-600 transition flex items-center justify-center"
                                                title="Reassign">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </button>
                                            {{-- Delete button --}}
                                            <button onclick="deleteDailyRoutine({{ $routine->id }})"
                                                class="w-8 h-8 rounded-xl border border-red-100 hover:bg-red-50 text-red-400 hover:text-red-600 transition flex items-center justify-center"
                                                title="Hapus">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        @endif
                                        {{-- Notes indicator --}}
                                        @if($routine->notes)
                                            <span
                                                class="w-8 h-8 rounded-xl border border-amber-100 bg-amber-50 text-amber-500 flex items-center justify-center text-xs"
                                                title="{{ $routine->notes }}">
                                                📝
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            {{-- DROPDOWN CHECKLIST ROW --}}
                            <tr id="checklist-row-{{ $routine->id }}" class="hidden border-b border-slate-100"
                                onclick="event.stopPropagation()">

                                <td colspan="7" class="p-0">

                                    <div class="bg-slate-50/70 border-t border-slate-100 px-6 py-5">

                                        {{-- Header --}}


                                        {{-- TABLE --}}
                                        @if($routine->checklists->count() > 0)

                                            <div class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-sm">

                                                <div class="overflow-x-auto">

                                                    <table class="min-w-full text-sm">

                                                        {{-- HEAD --}}
                                                        <thead class="bg-slate-100 text-slate-700">

                                                            <tr>

                                                                <th class="w-[8%] px-5 py-4 text-center font-bold">
                                                                    Check
                                                                </th>

                                                                <th class="w-[38%] px-5 py-4 text-left font-bold">
                                                                    Checklist Item
                                                                </th>

                                                                <th class="w-[24%] px-5 py-4 text-left font-bold">
                                                                    File Bukti
                                                                </th>

                                                                <th class="w-[15%] px-5 py-4 text-center font-bold">
                                                                    Status
                                                                </th>
                                                                @unless(auth()->user()->role == 'direksi')
                                                                    <th class="w-[15%] px-5 py-4 text-center font-bold">
                                                                        Action
                                                                    </th>
                                                                @endunless
                                                            </tr>

                                                        </thead>

                                                        {{-- BODY --}}
                                                        <tbody class="divide-y divide-slate-100 bg-white">

                                                            @foreach($routine->checklists as $cl)

                                                                                        <tr class="hover:bg-slate-50 transition">

                                                                                            {{-- CHECKBOX --}}
                                                                                            <td class="px-5 py-5 text-center align-top">

                                                                                                <button {{ auth()->user()->role == 'direksi' ? 'disabled' : '' }}
                                                                                                    @if(auth()->user()->role !== 'direksi')
                                                                                                        onclick="toggleChecklistItem({{ $cl->id }}, {{ $routine->id }}, {{ $cl->is_done ? 'true' : 'false' }})"
                                                                                                    @endif class="mx-auto flex h-6 w-6 items-center justify-center rounded-lg border-2 transition-all

                                                                                                                                                                                                    {{ $cl->is_done
                                                                ? 'border-emerald-500 bg-emerald-500 text-white'
                                                                : 'border-slate-300 bg-white' }}

                                                                                                                                                                                                    {{ auth()->user()->role == 'direksi'
                                                                ? 'cursor-not-allowed opacity-70'
                                                                : 'hover:border-violet-400 cursor-pointer' }}">

                                                                                                    @if($cl->is_done)
                                                                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                                                            viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"
                                                                                                            class="w-3.5 h-3.5">

                                                                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                                                                d="M5 13l4 4L19 7" />

                                                                                                        </svg>
                                                                                                    @endif

                                                                                                </button>

                                                                                            </td>

                                                                                            {{-- TITLE --}}
                                                                                            <td class="px-5 py-5 align-top">

                                                                                                <div class="space-y-2">

                                                                                                    <p class="font-semibold leading-relaxed
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        {{ $cl->is_done
                                                                ? 'line-through text-slate-400'
                                                                : 'text-slate-800' }}">

                                                                                                        {{ $cl->title }}

                                                                                                    </p>

                                                                                                    @if($cl->uncheck_reason)

                                                                                                        <div
                                                                                                            class="rounded-2xl border border-red-100 bg-red-50 px-3 py-2">

                                                                                                            <p
                                                                                                                class="text-[11px] font-bold uppercase tracking-wide text-red-500">
                                                                                                                Dibatalkan Manager
                                                                                                            </p>

                                                                                                            <p class="mt-1 text-xs text-red-600 leading-relaxed">
                                                                                                                {{ $cl->uncheck_reason }}
                                                                                                            </p>

                                                                                                        </div>

                                                                                                    @endif

                                                                                                </div>

                                                                                            </td>

                                                                                            {{-- FILE --}}
                                                                                            <td class="px-5 py-5 align-top">

                                                                                                @if($cl->file_path)

                                                                                                    @if(str_starts_with($cl->file_type ?? '', 'image/'))

                                                                                                        <a href="{{ Storage::url($cl->file_path) }}" target="_blank"
                                                                                                            onclick="event.stopPropagation()"
                                                                                                            class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-3 py-3 hover:bg-slate-100 transition max-w-xs">

                                                                                                            <img src="{{ Storage::url($cl->file_path) }}"
                                                                                                                class="w-12 h-12 rounded-xl object-cover border border-slate-200">

                                                                                                            <div class="min-w-0">
                                                                                                                <p class="text-xs font-bold text-slate-700">
                                                                                                                    Lihat Gambar
                                                                                                                </p>

                                                                                                                <p class="text-[11px] text-slate-400 truncate">
                                                                                                                    {{ Str::limit($cl->file_name ?? 'image', 18) }}
                                                                                                                </p>
                                                                                                            </div>

                                                                                                        </a>

                                                                                                    @else

                                                                                                        <a href="{{ Storage::url($cl->file_path) }}" target="_blank"
                                                                                                            onclick="event.stopPropagation()"
                                                                                                            class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-3 py-3 hover:bg-slate-100 transition max-w-xs">

                                                                                                            <div
                                                                                                                class="w-11 h-11 rounded-xl border border-slate-200 bg-white flex items-center justify-center text-slate-500">

                                                                                                                @if($cl->file_type === 'application/pdf')

                                                                                                                    {{-- PDF --}}
                                                                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                                                                        viewBox="0 0 24 24" stroke-width="1.8"
                                                                                                                        stroke="currentColor" class="w-5 h-5">

                                                                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                                                                            d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5A3.375 3.375 0 0010.125 2.25H8.25m8.25 18H6.75A2.25 2.25 0 014.5 18V5.25A2.25 2.25 0 016.75 3h5.379a1.125 1.125 0 01.795.33l5.746 5.745a1.125 1.125 0 01.33.795V18a2.25 2.25 0 01-2.25 2.25z" />

                                                                                                                    </svg>

                                                                                                                @elseif(in_array($cl->file_type, ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']))

                                                                                                                    {{-- Excel --}}
                                                                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                                                                        viewBox="0 0 24 24" stroke-width="1.8"
                                                                                                                        stroke="currentColor" class="w-5 h-5">

                                                                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                                                                            d="M3 3h18v18H3V3zm5 5 8 8m0-8-8 8" />

                                                                                                                    </svg>

                                                                                                                @else

                                                                                                                    {{-- File --}}
                                                                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                                                                        viewBox="0 0 24 24" stroke-width="1.8"
                                                                                                                        stroke="currentColor" class="w-5 h-5">

                                                                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                                                                            d="M18.375 12.739 10.682 20.432a4.5 4.5 0 11-6.364-6.364l9.193-9.193a3 3 0 114.243 4.243l-9.193 9.193a1.5 1.5 0 11-2.121-2.121l8.486-8.485" />

                                                                                                                    </svg>

                                                                                                                @endif

                                                                                                            </div>

                                                                                                            <div class="min-w-0">
                                                                                                                <p class="text-xs font-bold text-slate-700">
                                                                                                                    Lihat File
                                                                                                                </p>

                                                                                                                <p class="text-[11px] text-slate-400 truncate">
                                                                                                                    {{ Str::limit($cl->file_name ?? 'file', 18) }}
                                                                                                                </p>
                                                                                                            </div>

                                                                                                        </a>

                                                                                                    @endif

                                                                                                @else

                                                                                                    <span
                                                                                                        class="inline-flex items-center rounded-xl border border-amber-200 bg-amber-50 px-3 py-2 text-xs font-semibold text-amber-600">

                                                                                                        Belum ada file

                                                                                                    </span>

                                                                                                @endif

                                                                                            </td>

                                                                                            {{-- STATUS --}}
                                                                                            <td class="px-5 py-5 text-center align-top">

                                                                                                @if($cl->is_done)

                                                                                                    <span
                                                                                                        class="inline-flex items-center rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs font-bold text-emerald-700">

                                                                                                        ✓ Done

                                                                                                    </span>

                                                                                                @else

                                                                                                    <span
                                                                                                        class="inline-flex items-center rounded-xl border border-slate-200 bg-slate-100 px-3 py-2 text-xs font-bold text-slate-500">

                                                                                                        Pending

                                                                                                    </span>

                                                                                                @endif

                                                                                            </td>

                                                                                            {{-- ACTION --}}
                                                                                            @unless(auth()->user()->role == 'direksi')
                                                                                                <td class="px-5 py-5 text-center align-top">

                                                                                                    <div class="flex items-center justify-center gap-2">

                                                                                                        @if($cl->is_done && $isManager)

                                                                                                            {{-- Uncheck --}}
                                                                                                            <button
                                                                                                                onclick="openDrUncheckModal({{ $cl->id }}, '{{ addslashes($cl->title) }}')"
                                                                                                                class="group relative flex h-10 w-10 items-center justify-center rounded-2xl border border-red-100 bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition">

                                                                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                                                                    viewBox="0 0 24 24" stroke-width="2"
                                                                                                                    stroke="currentColor" class="w-5 h-5">

                                                                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                                                                        d="M6 18L18 6M6 6l12 12" />

                                                                                                                </svg>

                                                                                                            </button>

                                                                                                        @endif

                                                                                                        @if($isManager)

                                                                                                            {{-- Delete --}}
                                                                                                            <button
                                                                                                                onclick="deleteChecklistItem({{ $cl->id }}, {{ $routine->id }})"
                                                                                                                class="group relative flex h-10 w-10 items-center justify-center rounded-2xl border border-red-100 bg-white text-red-400 hover:bg-red-50 hover:text-red-600 transition">

                                                                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                                                                    viewBox="0 0 24 24" stroke-width="2"
                                                                                                                    stroke="currentColor" class="w-5 h-5">

                                                                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />

                                                                                                                </svg>

                                                                                                            </button>

                                                                                                        @endif

                                                                                                    </div>

                                                                                                </td>
                                                                                            @endunless
                                                                                        </tr>

                                                            @endforeach

                                                        </tbody>

                                                    </table>

                                                </div>

                                            </div>

                                        @else

                                            <div
                                                class="rounded-[28px] border border-dashed border-slate-200 bg-white py-12 text-center">

                                                <div
                                                    class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-3xl bg-slate-100 text-slate-400">

                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                        stroke-width="1.5" stroke="currentColor" class="w-8 h-8">

                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z" />

                                                    </svg>

                                                </div>

                                                <p class="font-semibold text-slate-500">
                                                    Tidak ada checklist
                                                </p>

                                            </div>

                                        @endif

                                        {{-- ADD CHECKLIST --}}
                                        @if($isManager)

                                            <div class="mt-4 flex items-center gap-3">

                                                <input type="text" id="new-cl-{{ $routine->id }}"
                                                    placeholder="Tambah item checklist baru..."
                                                    class="flex-1 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-violet-400 focus:outline-none"
                                                    onkeydown="if(event.key==='Enter') addChecklistItem({{ $routine->id }})">

                                                <button onclick="addChecklistItem({{ $routine->id }})"
                                                    class="inline-flex items-center gap-2 rounded-2xl bg-violet-600 px-5 py-3 text-sm font-bold text-white hover:bg-violet-700 transition">

                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                        stroke-width="2" stroke="currentColor" class="w-4 h-4">

                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M12 4.5v15m7.5-7.5h-15" />

                                                    </svg>

                                                    Tambah

                                                </button>

                                            </div>

                                        @endif

                                    </div>

                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Empty after filter --}}
                <div id="emptyFilter" class="hidden px-4 py-14 text-center text-slate-400">
                    <div class="text-4xl mb-3">🔍</div>
                    <p class="text-sm font-medium text-slate-500">Tidak ada routine yang cocok.</p>
                </div>

            @else
                <div class="text-center py-20 text-slate-500">
                    <div class="text-5xl mb-4">🔁</div>
                    <p class="font-black text-slate-700 text-lg">Belum ada Daily Routine</p>
                    <p class="text-sm text-slate-400 mt-1 mb-5">
                        @if(auth()->user()->role === 'manager') Buat routine pertama untuk tim kamu.
                        @else Manager belum membuat routine untukmu. @endif
                    </p>
                    @if(auth()->user()->role === 'manager')
                        <button onclick="showDailyRoutineForm()"
                            class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-violet-600 hover:bg-violet-700 text-white font-semibold transition">
                            + Create Routine
                        </button>
                    @endif
                </div>
            @endif
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════
    MODAL: Create Daily Routine (Manager only)
    ══════════════════════════════════════════════════════ --}}
    @if(auth()->user()->role === 'manager')
        <div id="dailyRoutineModalWrapper" onclick="hideDailyRoutineForm()"
            class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
            <div onclick="event.stopPropagation()"
                class="w-full max-w-2xl bg-white rounded-[32px] shadow-2xl border border-slate-200 max-h-[90vh] flex flex-col overflow-hidden">
                <div class="flex items-center justify-between gap-4 border-b border-slate-200 px-6 py-5 shrink-0">
                    <div>
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Baru</p>
                        <h2 class="text-2xl font-black text-slate-900">Create Daily Routine</h2>
                    </div>
                    <button onclick="hideDailyRoutineForm()"
                        class="w-10 h-10 rounded-2xl bg-slate-100 hover:bg-slate-200 transition flex items-center justify-center text-slate-600 text-xl">✕</button>
                </div>
                <div class="overflow-y-auto flex-1 p-6 space-y-5">
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Judul Routine <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="drTitle"
                            class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-violet-500 focus:bg-white"
                            placeholder="Contoh: Daily standup, Weekly report...">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Description</label>
                        <textarea id="drDescription" rows="3"
                            class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-violet-500 focus:bg-white"
                            placeholder="Deskripsi detail routine ini..."></textarea>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Assign ke Member <span
                                class="text-slate-400 font-normal text-xs">(opsional)</span></label>
                        <select id="drUserId"
                            class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-violet-500 focus:bg-white">
                            <option value="">-- Pilih member --</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}">{{ $member->name }} — {{ $member->role }}</option>
                            @endforeach
                        </select>
                    </div>
                <div>
    <label class="text-sm font-semibold text-slate-700">Hari Aktif</label>
    <p class="text-xs text-slate-400 mt-0.5 mb-3">
        Pilih hari routine ini berjalan
    </p>

    <div class="grid grid-cols-7 gap-2" id="drDaysContainer">

        @foreach([
            ['senin', 'Senin'],
            ['selasa', 'Selasa'],
            ['rabu', 'Rabu'],
            ['kamis', 'Kamis'],
            ['jumat', 'Jumat'],
            ['sabtu', 'Sabtu'],
            ['minggu', 'Minggu']
        ] as [$val, $label])

            <label class="day-pill flex flex-col items-center gap-1 cursor-pointer select-none">

                <input 
                    type="checkbox"
                    value="{{ $val }}"
                    class="sr-only peer day-check"
                    onchange="setDeadlineDay(this)"
                >

                <span class="w-full text-center rounded-2xl border border-slate-200 bg-slate-50 py-2.5 text-xs font-bold text-slate-500 transition
                    hover:border-violet-300 hover:bg-violet-50 hover:text-violet-600
                    peer-checked:border-violet-500
                    peer-checked:bg-violet-500
                    peer-checked:text-white
                    peer-checked:shadow-lg
                    peer-checked:shadow-violet-200">
                    {{ $label }}
                </span>

            </label>

        @endforeach

    </div>
</div>

<!-- Value untuk DB -->
<input type="hidden" id="drDeadline" name="deadline">
                    <!-- <div>
                                <label class="text-sm font-semibold text-slate-700">Deadline</label>
                                <input type="date" id="drDeadline"
                                    class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-violet-500 focus:bg-white">
                            </div> -->
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Notes</label>
                        <textarea id="drNotes" rows="2"
                            class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-violet-500 focus:bg-white"
                            placeholder="Catatan tambahan..."></textarea>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Checklist Items</label>
                        <div id="drChecklistContainer" class="mt-3 space-y-3"></div>
                        <button onclick="addDrChecklist()"
                            class="mt-3 inline-flex items-center gap-2 rounded-2xl bg-violet-600 hover:bg-violet-700 px-4 py-2.5 text-sm font-semibold text-white transition">
                            + Tambah Item
                        </button>
                    </div>
                </div>
                <div class="shrink-0 border-t border-slate-200 px-6 py-4 bg-slate-50 flex gap-3 justify-end">
                    <button onclick="hideDailyRoutineForm()"
                        class="rounded-3xl border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">Batal</button>
                    <button onclick="submitDailyRoutine()"
                        class="rounded-3xl bg-violet-600 hover:bg-violet-700 px-6 py-3 text-sm font-semibold text-white transition shadow-lg shadow-violet-100">Simpan
                        Routine</button>
                </div>
            </div>
        </div>

        {{-- MODAL: Reassign --}}
        <div id="reassignModal" onclick="closeReassignModal()"
            class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
            <div onclick="event.stopPropagation()"
                class="w-full max-w-md bg-white rounded-[32px] shadow-2xl border border-slate-200">
                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
                    <h2 class="text-lg font-black text-slate-900">Reassign Routine</h2>
                    <button onclick="closeReassignModal()"
                        class="w-9 h-9 rounded-2xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600">✕</button>
                </div>
                <div class="p-6">
                    <input type="hidden" id="reassignRoutineId">
                    <label class="text-sm font-semibold text-slate-700">Pilih Member</label>
                    <select id="reassignUserId"
                        class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-violet-500 focus:bg-white">
                        <option value="">-- Unassign --</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}">{{ $member->name }} — {{ $member->role }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex gap-3">
                    <button onclick="closeReassignModal()"
                        class="flex-1 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">Batal</button>
                    <button onclick="submitReassign()"
                        class="flex-1 rounded-2xl bg-violet-600 hover:bg-violet-700 px-4 py-3 text-sm font-semibold text-white transition">Simpan</button>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL: Manager Uncheck --}}
    <div id="drUncheckModal" onclick="hideDrUncheckModal()"
        class="fixed inset-0 z-[60] hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div onclick="event.stopPropagation()"
            class="w-full max-w-md bg-white rounded-[32px] shadow-2xl border border-slate-200">
            <div class="flex items-center justify-between gap-4 border-b border-slate-200 px-6 py-5">
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-violet-400 font-semibold">Konfirmasi</p>
                    <h2 class="text-xl font-black text-slate-900">Batalkan Checklist</h2>
                </div>
                <button onclick="hideDrUncheckModal()"
                    class="rounded-full bg-slate-100 p-2 hover:bg-slate-200 transition">✕</button>
            </div>
            <div class="p-6 space-y-4">
                <div class="bg-violet-50 border border-violet-100 rounded-2xl px-4 py-3">
                    <p class="text-xs text-violet-400 font-semibold uppercase tracking-widest mb-1">Item</p>
                    <p id="drUncheckItemTitle" class="font-bold text-violet-800 text-sm"></p>
                </div>
                <input type="hidden" id="drUncheckChecklistId">
                <div>
                    <label class="text-sm font-semibold text-slate-700">Alasan <span class="text-red-500">*</span></label>
                    <textarea id="drUncheckReason" rows="4"
                        class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-violet-400 resize-none"
                        placeholder="Jelaskan alasan pembatalan..."></textarea>
                    <p id="drUncheckError" class="hidden mt-1.5 text-xs text-red-500 font-semibold">Alasan wajib diisi.</p>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex gap-3">
                <button onclick="hideDrUncheckModal()"
                    class="flex-1 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">Batal</button>
                <button onclick="submitDrUncheck()"
                    class="flex-1 rounded-2xl bg-violet-600 hover:bg-violet-700 px-4 py-3 text-sm font-semibold text-white transition">Ya,
                    Batalkan</button>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
    JAVASCRIPT
    ══════════════════════════════════════════════════════ --}}
    <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;
        let currentActiveFilter = 'all';
        const openRows = new Set();
 function setDeadlineDay() {

    const checkedDays = [];

    document.querySelectorAll('.day-check:checked').forEach(el => {
        checkedDays.push(el.value);
    });

    document.getElementById('drDeadline').value = checkedDays.join(',');
}
        // ─── Filter & Search ──────────────────────────────────
        function filterStatus(status) {
            currentActiveFilter = status;
            const rows = document.querySelectorAll('.routine-row');
            const query = document.getElementById('searchRoutine').value.trim().toLowerCase();
            let visible = 0;
            rows.forEach(row => {
                const matchS = status === 'all' || row.dataset.status === status;
                const matchQ = !query || row.dataset.title.includes(query);
                const show = matchS && matchQ;
                row.style.display = show ? '' : 'none';
                const rid = row.querySelector('[id^="arrow-"]')?.id.replace('arrow-', '');
                if (rid) document.getElementById('checklist-row-' + rid).style.display = show && openRows.has(+rid) ? '' : 'none';
                if (show) visible++;
            });
            const ef = document.getElementById('emptyFilter');
            if (ef) ef.style.display = visible === 0 ? '' : 'none';
            document.querySelectorAll('.filter-btn').forEach(b => {
                b.classList.remove('bg-violet-600', 'text-white');
                b.classList.add('bg-slate-100', 'text-slate-700');
            });
            const active = document.getElementById('filter-' + status);
            if (active) { active.classList.add('bg-violet-600', 'text-white'); active.classList.remove('bg-slate-100', 'text-slate-700'); }
        }
        document.getElementById('searchRoutine').addEventListener('input', () => filterStatus(currentActiveFilter));

        // ─── Toggle checklist dropdown ────────────────────────
        function toggleChecklist(routineId) {
            const clRow = document.getElementById('checklist-row-' + routineId);
            const arrow = document.getElementById('arrow-' + routineId);
            if (!clRow) return;
            const isOpen = !clRow.classList.contains('hidden');
            if (isOpen) {
                clRow.classList.add('hidden');
                arrow.style.transform = 'rotate(0deg)';
                arrow.style.color = '';
                openRows.delete(routineId);
            } else {
                clRow.classList.remove('hidden');
                arrow.style.transform = 'rotate(90deg)';
                arrow.style.color = '#7c3aed';
                openRows.add(routineId);
            }
        }

        // ─── Toggle checklist item done/undone ───────────────
        async function toggleChecklistItem(checklistId, routineId, isDone) {
            try {
                const res = await fetch(`/project/daily-routine/checklist/${checklistId}/toggle`, {
                    method: 'PATCH',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                    body: JSON.stringify({}),
                });
                const data = await res.json();
                if (data.success) {
                    setTimeout(() => location.reload(), 300);
                } else {
                    gModal.alert(data.message || 'Manager Tidak Bisa Melakukan Checklist Sendiri.', 'warning');
                }
            } catch (e) { gModal.alert('Terjadi kesalahan.', 'warning'); }
        }

        // ─── Add checklist item inline ────────────────────────
        async function addChecklistItem(routineId) {
            const input = document.getElementById('new-cl-' + routineId);
            const title = input.value.trim();
            if (!title) { input.focus(); return; }
            try {
                const res = await fetch(`/project/daily-routine/${routineId}/checklist`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                    body: JSON.stringify({ title }),
                });
                const data = await res.json();
                if (data.success) {
                    gToast.success('Ditambahkan', 'Checklist item berhasil ditambah.');
                    setTimeout(() => location.reload(), 500);
                } else {
                    gModal.alert(data.message || 'Gagal menambah.', 'warning');
                }
            } catch (e) { gModal.alert('Terjadi kesalahan.', 'warning'); }
        }

        // ─── Delete checklist item ────────────────────────────
        async function deleteChecklistItem(checklistId, routineId) {
            gModal.confirm({
                type: 'delete', title: 'Hapus Item?', message: 'Item checklist ini akan dihapus.',
                confirmLabel: 'Ya, Hapus',
                onConfirm: async () => {
                    try {
                        const res = await fetch(`/project/daily-routine/checklist/${checklistId}`, {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                        });
                        const data = await res.json();
                        if (data.success) { setTimeout(() => location.reload(), 300); }
                        else { gModal.alert(data.message || 'Gagal menghapus.', 'warning'); }
                    } catch (e) { gModal.alert('Terjadi kesalahan.', 'warning'); }
                }
            });
        }

        // ─── Update status ────────────────────────────────────
        async function updateRoutineStatus(routineId, status) {
            try {
                const res = await fetch(`/project/daily-routine/${routineId}/status`, {
                    method: 'PATCH',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                    body: JSON.stringify({ status }),
                });
                const data = await res.json();
                if (data.success) { gToast.success('Status diperbarui', ''); setTimeout(() => location.reload(), 800); }
                else { gModal.alert(data.message || 'Gagal.', 'warning'); }
            } catch (e) { gModal.alert('Terjadi kesalahan.', 'warning'); }
        }

        // ─── Delete routine ───────────────────────────────────
        async function deleteDailyRoutine(routineId) {
            gModal.confirm({
                type: 'delete', title: 'Hapus Routine Ini?',
                message: 'Seluruh checklist pada routine ini akan dihapus permanen.',
                confirmLabel: 'Ya, Hapus',
                onConfirm: async () => {
                    try {
                        const res = await fetch(`/project/daily-routine/${routineId}`, {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                        });
                        const data = await res.json();
                        if (data.success) { gToast.success('Dihapus', ''); setTimeout(() => location.reload(), 800); }
                        else { gModal.alert(data.message || 'Gagal.', 'warning'); }
                    } catch (e) { gModal.alert('Terjadi kesalahan.', 'warning'); }
                }
            });
        }

        // ─── Create Routine modal ─────────────────────────────
        function showDailyRoutineForm() {
            const m = document.getElementById('dailyRoutineModalWrapper');
            if (!m) return;
            m.classList.remove('hidden'); m.classList.add('flex');
            ['drTitle', 'drDescription', 'drDeadline', 'drNotes'].forEach(id => { const el = document.getElementById(id); if (el) el.value = ''; });
            document.getElementById('drUserId').value = '';
            document.getElementById('drChecklistContainer').innerHTML = '';
            document.body.style.overflow = 'hidden';
        }
        function hideDailyRoutineForm() {
            const m = document.getElementById('dailyRoutineModalWrapper');
            if (!m) return;
            m.classList.add('hidden'); m.classList.remove('flex');
            document.body.style.overflow = '';
        }
        function addDrChecklist() {
            const c = document.getElementById('drChecklistContainer');
            const idx = c.children.length + 1;
            const div = document.createElement('div');
            div.className = 'flex items-center gap-2';
            div.innerHTML = `<input type="text" class="flex-1 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none focus:border-violet-500 focus:bg-white" placeholder="Item checklist ${idx}...">
                                                                        <button onclick="this.parentElement.remove()" class="w-9 h-9 rounded-xl bg-red-50 hover:bg-red-100 text-red-500 font-bold transition">✕</button>`;
            c.appendChild(div);
        }
        async function submitDailyRoutine() {
            const title = document.getElementById('drTitle').value.trim();
            if (!title) { gModal.alert('Judul routine wajib diisi.', 'warning'); return; }
            const checklists = Array.from(document.getElementById('drChecklistContainer').querySelectorAll('input[type="text"]'))
                .map(i => i.value.trim()).filter(Boolean);
            const payload = {
                title,
                description: document.getElementById('drDescription').value.trim(),
                user_id: document.getElementById('drUserId').value || null,
                deadline: document.getElementById('drDeadline').value || null,
                notes: document.getElementById('drNotes').value.trim(),
                checklists,
            };
            try {
                const res = await fetch('/project/daily-routine', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                    body: JSON.stringify(payload),
                });
                const data = await res.json();
                if (data.success) { hideDailyRoutineForm(); gToast.success('Berhasil!', 'Daily routine dibuat.'); setTimeout(() => location.reload(), 1000); }
                else { gModal.alert(data.message || 'Gagal menyimpan.', 'warning'); }
            } catch (e) { gModal.alert('Terjadi kesalahan.', 'warning'); }
        }

        // ─── Reassign modal ───────────────────────────────────
        function openReassignModal(routineId, currentUserId) {
            document.getElementById('reassignRoutineId').value = routineId;
            const sel = document.getElementById('reassignUserId');
            sel.value = currentUserId || '';
            const m = document.getElementById('reassignModal');
            m.classList.remove('hidden'); m.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
        function closeReassignModal() {
            const m = document.getElementById('reassignModal');
            m.classList.add('hidden'); m.classList.remove('flex');
            document.body.style.overflow = '';
        }
        async function submitReassign() {
            const id = document.getElementById('reassignRoutineId').value;
            const userId = document.getElementById('reassignUserId').value || null;
            try {
                const res = await fetch(`/project/daily-routine/${id}/reassign`, {
                    method: 'PATCH',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                    body: JSON.stringify({ user_id: userId }),
                });
                const data = await res.json();
                if (data.success) { closeReassignModal(); gToast.success('Reassigned!', ''); setTimeout(() => location.reload(), 800); }
                else { gModal.alert(data.message || 'Gagal.', 'warning'); }
            } catch (e) { gModal.alert('Terjadi kesalahan.', 'warning'); }
        }

        // ─── Manager Uncheck ──────────────────────────────────
        function openDrUncheckModal(checklistId, title) {
            document.getElementById('drUncheckChecklistId').value = checklistId;
            document.getElementById('drUncheckItemTitle').innerText = title;
            document.getElementById('drUncheckReason').value = '';
            document.getElementById('drUncheckError').classList.add('hidden');
            const m = document.getElementById('drUncheckModal');
            m.classList.remove('hidden'); m.classList.add('flex');
            document.body.style.overflow = 'hidden';
            setTimeout(() => document.getElementById('drUncheckReason').focus(), 100);
        }
        function hideDrUncheckModal() {
            const m = document.getElementById('drUncheckModal');
            m.classList.add('hidden'); m.classList.remove('flex');
            document.body.style.overflow = '';
        }
        async function submitDrUncheck() {
            const id = document.getElementById('drUncheckChecklistId').value;
            const reason = document.getElementById('drUncheckReason').value.trim();
            if (!reason) { document.getElementById('drUncheckError').classList.remove('hidden'); return; }
            try {
                const res = await fetch(`/project/daily-routine/checklist/${id}/manager-uncheck`, {
                    method: 'PATCH',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                    body: JSON.stringify({ reason }),
                });
                const data = await res.json();
                if (data.success) { hideDrUncheckModal(); gToast.warning('Dibatalkan', ''); setTimeout(() => location.reload(), 800); }
                else { gModal.alert(data.message || 'Gagal.', 'warning'); }
            } catch (e) { gModal.alert('Terjadi kesalahan.', 'warning'); }
        }
    </script>
    {{-- Tooltip reusable style --}}
    <style>
        .tooltip-group {
            position: relative;
        }

        .tooltip-text {
            position: absolute;
            bottom: calc(100% + 10px);
            left: 50%;
            transform: translateX(-50%) translateY(6px);
            background: #0f172a;
            color: white;
            font-size: 11px;
            font-weight: 600;
            padding: 6px 10px;
            border-radius: 12px;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: all .2s ease;
            z-index: 60;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .15);
        }

        .tooltip-text::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border-width: 5px;
            border-style: solid;
            border-color: #0f172a transparent transparent transparent;
        }

        .tooltip-group:hover .tooltip-text {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
    </style>
@endsection