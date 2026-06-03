@extends('layouts.app')

@section('content')

    <div class="space-y-6">

        {{-- ========================= --}}
        {{-- HEADER --}}
        {{-- ========================= --}}
        <div class="relative overflow-hidden rounded-[32px] border border-slate-200 bg-white shadow-sm p-6 md:p-8">
            <div class="absolute top-0 right-0 w-72 h-72 bg-violet-100 rounded-full blur-3xl opacity-50"></div>
            <div class="absolute bottom-0 left-0 w-72 h-72 bg-slate-100 rounded-full blur-3xl opacity-50"></div>

            <div class="relative z-10">
                <div class="flex items-center justify-between gap-3">

                    {{-- LEFT --}}
                    <div class="min-w-0 flex-1">
                        <p class="text-[10px] md:text-sm uppercase tracking-[0.25em] text-violet-600 font-semibold">
                            Daily Routine
                        </p>
                        <h1 class="mt-2 md:mt-4 text-lg sm:text-xl md:text-3xl font-black text-slate-900 leading-tight">
                            Rutinitas Harianku
                        </h1>
                    </div>

                    {{-- RIGHT --}}
                    <div class="flex items-center gap-2 md:gap-3 shrink-0">
                        <a href="{{ route('staff.dashboard') }}"
                            class="inline-flex items-center justify-center rounded-2xl md:rounded-3xl bg-slate-900 px-3 md:px-5 py-2 md:py-3 text-[11px] md:text-sm font-semibold text-white shadow-lg shadow-slate-200 hover:bg-slate-800 transition whitespace-nowrap">
                            <span class="hidden sm:inline">← Back</span>
                            <span class="sm:hidden">←</span>
                        </a>
                    </div>

                </div>

                {{-- Stats Grid --}}
                <div class="mt-6 md:mt-8 grid grid-cols-3 gap-2 md:gap-4">

                    <div
                        class="rounded-[18px] md:rounded-[32px] border border-slate-200 bg-slate-50 p-3 md:p-6 shadow-sm min-w-0">
                        <p class="text-[10px] md:text-sm font-semibold text-slate-500 truncate">Routines</p>
                        <h2 class="mt-1 md:mt-3 text-lg md:text-3xl font-black text-slate-900">{{ $routines->count() }}</h2>
                    </div>

                    <div
                        class="rounded-[18px] md:rounded-[32px] border border-slate-200 bg-white p-3 md:p-6 shadow-sm min-w-0">
                        <p class="text-[10px] md:text-sm font-semibold text-slate-500 truncate">Checklist</p>
                        <h2 class="mt-1 md:mt-3 text-lg md:text-3xl font-black text-slate-900">
                            {{ $routines->sum(fn($r) => $r->checklists->count()) }}
                        </h2>
                    </div>

                    <div
                        class="rounded-[18px] md:rounded-[32px] border border-slate-200 bg-slate-50 p-3 md:p-6 shadow-sm min-w-0">
                        <p class="text-[10px] md:text-sm font-semibold text-slate-500 truncate">Completed</p>
                        <h2 class="mt-1 md:mt-3 text-lg md:text-3xl font-black text-slate-900">
                            {{ $routines->sum(fn($r) => $r->checklists->where('is_done', true)->count()) }}
                        </h2>
                    </div>

                </div>
            </div>
        </div>

        {{-- ========================= --}}
        {{-- TABLE / CARDS --}}
        {{-- ========================= --}}
        @if($routines->count() > 0)
            <div class="rounded-[20px] md:rounded-[32px] border border-slate-200 bg-white shadow-sm overflow-hidden">

                {{-- ── Desktop Table ── --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Judul Routine</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Deskripsi</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-slate-700">Status</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-slate-700">Checklist</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-slate-700">Progress</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-slate-700">Hari</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-slate-700">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($routines as $index => $routine)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-semibold text-slate-900">{{ $routine->title }}</p>
                                        @if($routine->notes)
                                            <p class="text-xs text-slate-400 mt-0.5 line-clamp-1">{{ $routine->notes }}</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-slate-600 line-clamp-2">{{ $routine->description ?? '-' }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="inline-block rounded-full border px-3 py-1 text-xs font-semibold {{ $routine->status_color }}">
                                            {{ ucfirst($routine->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="inline-block rounded-full bg-violet-100 px-3 py-1 text-sm font-semibold text-violet-700">
                                            {{ $routine->checklists->count() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col items-center gap-2">
                                            <div class="w-full bg-slate-200 rounded-full h-2 max-w-xs">
                                                @php
                                                    $total = $routine->checklists->count();
                                                    $done = $routine->checklists->where('is_done', true)->count();
                                                    $pct = $total > 0 ? round($done / $total * 100) : 0;
                                                @endphp
                                                <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $pct }}%"></div>
                                            </div>
                                            <p class="text-xs text-slate-500">{{ $done }}/{{ $total }}</p>
                                        </div>
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

                                                    <span
                                                        class="px-2 py-0.5 rounded-md text-[10px] font-bold {{ $dayColors[$day] ?? 'bg-slate-100 text-slate-600' }}">
                                                        {{ $shortDays[$day] ?? ucfirst($day) }}
                                                    </span>

                                                @endforeach

                                            </div>

                                        @else

                                            <span class="text-slate-400 text-xs">-</span>

                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button onclick="toggleDropdown('dr-dropdown-{{ $index }}')"
                                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-violet-50 hover:bg-violet-100 px-3 py-2 transition">
                                            <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>

                                {{-- Dropdown Checklist Row --}}
                                <tr id="dr-dropdown-{{ $index }}" class="hidden bg-slate-50 border-t-2 border-violet-200">
                                    <td colspan="7" class="px-6 py-6">
                                        <div class="space-y-3">
                                            <p class="text-sm font-semibold text-slate-700 mb-4">Checklist Items:</p>
                                            @forelse($routine->checklists as $checklist)
                                                <div class="flex flex-col gap-2">
                                                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

                                                        <div class="flex-1">
                                                            <label
                                                                class="flex items-center gap-3 p-4 rounded-lg bg-white border border-slate-200 hover:border-violet-200 hover:bg-violet-50 transition cursor-pointer"
                                                                onclick="handleDrChecklistClick(event, {{ $checklist->id }}, {{ $checklist->is_done ? 'true' : 'false' }}, {{ $checklist->file_path ? 'true' : 'false' }})">

                                                                {{-- Visual checkbox --}}
                                                                <div class="h-5 w-5 rounded border-2 flex-shrink-0 flex items-center justify-center
                                                                                {{ $checklist->is_done ? 'bg-violet-600 border-violet-600' : 'border-slate-300 bg-white' }}"
                                                                    id="dr-checkbox-visual-{{ $checklist->id }}">
                                                                    @if($checklist->is_done)
                                                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                                stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                                        </svg>
                                                                    @endif
                                                                </div>

                                                                <div class="min-w-0 flex-1">
                                                                    <p
                                                                        class="text-sm font-semibold {{ $checklist->is_done ? 'text-slate-500 line-through' : 'text-slate-800' }}">
                                                                        {{ $checklist->title }}
                                                                    </p>
                                                                    <p
                                                                        class="text-xs {{ $checklist->is_done ? 'text-emerald-600' : 'text-slate-500' }}">
                                                                        {{ $checklist->is_done ? 'Selesai' : 'Menunggu' }}
                                                                    </p>

                                                                    @if(!$checklist->is_done && $checklist->uncheck_reason)
                                                                        <div
                                                                            class="mt-1.5 flex items-start gap-1.5 rounded-xl bg-red-50 border border-red-100 px-3 py-2">
                                                                            <span class="text-red-400 text-xs shrink-0 mt-0.5">⚠</span>
                                                                            <div>
                                                                                <p class="text-xs font-semibold text-red-600">Alasan
                                                                                    manager:</p>
                                                                                <p class="text-xs text-red-500 mt-0.5 leading-relaxed">
                                                                                    {{ $checklist->uncheck_reason }}</p>
                                                                            </div>
                                                                        </div>
                                                                    @endif

                                                                    @if($checklist->file_path)
                                                                        <div class="mt-2 flex items-center gap-2">
                                                                            @if(str_starts_with($checklist->file_type ?? '', 'image/'))
                                                                                <a href="{{ Storage::url($checklist->file_path) }}"
                                                                                    target="_blank"
                                                                                    class="block mt-1 rounded-lg overflow-hidden border border-slate-200 w-16 h-16"
                                                                                    onclick="event.stopPropagation()">
                                                                                    <img src="{{ Storage::url($checklist->file_path) }}"
                                                                                        alt="Bukti" class="w-full h-full object-cover">
                                                                                </a>
                                                                            @else
                                                                                <a href="{{ Storage::url($checklist->file_path) }}"
                                                                                    target="_blank" onclick="event.stopPropagation()"
                                                                                    class="inline-flex items-center gap-1.5 rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition">
                                                                                    @if($checklist->file_type === 'application/pdf') 📄
                                                                                    @elseif(in_array($checklist->file_type, ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']))
                                                                                        📊
                                                                                    @else 📎
                                                                                    @endif
                                                                                    {{ $checklist->file_name ?? 'Lihat File' }}
                                                                                </a>
                                                                            @endif
                                                                            <button type="button"
                                                                                onclick="event.stopPropagation(); deleteDrChecklistFile({{ $checklist->id }})"
                                                                                class="rounded-full bg-red-100 p-1 text-red-500 hover:bg-red-200 transition"
                                                                                title="Hapus file">
                                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                                                    viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                                </svg>
                                                                            </button>
                                                                        </div>
                                                                    @else
                                                                        <p class="text-xs text-amber-600 mt-1">⚠ Belum ada bukti — klik
                                                                            untuk upload</p>
                                                                    @endif
                                                                </div>

                                                                <span
                                                                    class="rounded-full px-2 py-1 text-xs font-semibold flex-shrink-0
                                                                                {{ $checklist->is_done ? 'bg-slate-100 text-slate-600' : 'bg-violet-100 text-violet-700' }}">
                                                                    {{ $checklist->is_done ? 'Batalkan' : 'Selesaikan' }}
                                                                </span>
                                                            </label>

                                                            {{-- Hidden form for un-check --}}
                                                            <form id="dr-uncheck-form-{{ $checklist->id }}" method="POST"
                                                                action="{{ route('daily-routine.checklist.toggle', $checklist->id) }}"
                                                                class="hidden">
                                                                @csrf
                                                                @method('PATCH')
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <p class="text-sm text-slate-500 text-center py-4">Tidak ada checklist untuk routine
                                                    ini.</p>
                                            @endforelse
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- ── Mobile Card View ── --}}
                <div class="md:hidden">

                    {{-- Search & Filter --}}
                    <div class="p-4 border-b border-slate-200 space-y-3">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"></path>
                            </svg>
                            <input type="text" id="drMobileSearchInput" placeholder="Cari routine atau checklist..."
                                class="w-full pl-9 pr-4 py-2.5 rounded-2xl border border-slate-200 bg-slate-50 text-sm text-slate-800 placeholder-slate-400 outline-none focus:border-violet-400 focus:bg-white transition"
                                oninput="filterDrMobileCards()" />
                        </div>
                        <div class="flex items-center gap-2 overflow-x-auto pb-1">
                            <span class="text-xs font-semibold text-slate-500 shrink-0">Filter:</span>
                            <button onclick="setDrMobileFilter('all')" id="dr-filter-all"
                                class="dr-mobile-filter-btn shrink-0 rounded-full px-3 py-1.5 text-xs font-semibold transition bg-violet-600 text-white">
                                Semua
                            </button>
                            <button onclick="setDrMobileFilter('pending')" id="dr-filter-pending"
                                class="dr-mobile-filter-btn shrink-0 rounded-full px-3 py-1.5 text-xs font-semibold transition bg-slate-100 text-slate-600 hover:bg-slate-200">
                                Pending
                            </button>
                            <button onclick="setDrMobileFilter('progress')" id="dr-filter-progress"
                                class="dr-mobile-filter-btn shrink-0 rounded-full px-3 py-1.5 text-xs font-semibold transition bg-slate-100 text-slate-600 hover:bg-slate-200">
                                Progress
                            </button>
                            <button onclick="setDrMobileFilter('done')" id="dr-filter-done"
                                class="dr-mobile-filter-btn shrink-0 rounded-full px-3 py-1.5 text-xs font-semibold transition bg-slate-100 text-slate-600 hover:bg-slate-200">
                                Selesai
                            </button>
                        </div>
                        <p id="drMobileFilterCount" class="text-xs text-slate-400"></p>
                    </div>

                    <div id="drMobileCardList" class="divide-y divide-slate-200">
                        @foreach($routines as $index => $routine)
                            @php
                                $total = $routine->checklists->count();
                                $done = $routine->checklists->where('is_done', true)->count();
                                $pct = $total > 0 ? round($done / $total * 100) : 0;
                            @endphp
                            <div class="dr-mobile-card p-4" data-title="{{ strtolower($routine->title) }}"
                                data-status="{{ $routine->status }}"
                                data-checklist-titles="{{ strtolower($routine->checklists->pluck('title')->join(' ')) }}"
                                data-done-count="{{ $done }}" data-total-count="{{ $total }}">

                                <button onclick="toggleDrMobileDropdown('dr-mobile-dropdown-{{ $index }}')"
                                    class="w-full text-left">
                                    <div class="flex items-start justify-between gap-3 mb-3">
                                        <div class="min-w-0 flex-1">
                                            <h3 class="text-sm font-bold text-slate-900">{{ $routine->title }}</h3>
                                            <span
                                                class="inline-block mt-1 rounded-full border px-2 py-0.5 text-[11px] font-semibold {{ $routine->status_color }}">
                                                {{ ucfirst($routine->status) }}
                                            </span>
                                        </div>
                                        <svg id="dr-mobile-icon-{{ $index }}"
                                            class="w-5 h-5 text-slate-400 transition-transform flex-shrink-0 mt-0.5" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                        </svg>
                                    </div>

                                    @if($routine->description)
                                        <p class="text-xs text-slate-600 mb-3 line-clamp-2">{{ $routine->description }}</p>
                                    @endif

                                    <div class="flex flex-col gap-1.5 mb-3">
                                        <div class="flex justify-between items-center">
                                            <span class="text-xs font-semibold text-slate-600">Progress</span>
                                            <span class="text-xs text-slate-500">{{ $done }}/{{ $total }}</span>
                                        </div>
                                        <div class="w-full bg-slate-200 rounded-full h-1.5">
                                            <div class="bg-emerald-500 h-1.5 rounded-full transition-all"
                                                style="width: {{ $pct }}%"></div>
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap gap-2 items-center">

                                        <span class="rounded-full bg-violet-100 px-2 py-1 text-xs font-semibold text-violet-700">
                                            {{ $total }} Items
                                        </span>

                                        @if($done === $total && $total > 0)
                                            <span class="rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-700">
                                                ✓ Selesai
                                            </span>
                                        @endif

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

                                            <div class="flex flex-wrap items-center gap-1">

                                                @foreach($days as $day)

                                                    <span
                                                        class="px-2 py-0.5 rounded-md text-[10px] font-bold {{ $dayColors[$day] ?? 'bg-slate-100 text-slate-600' }}">
                                                        {{ $shortDays[$day] ?? ucfirst($day) }}
                                                    </span>

                                                @endforeach

                                            </div>

                                        @endif

                                    </div>
                                </button>

                                {{-- Expandable Checklist --}}
                                <div id="dr-mobile-dropdown-{{ $index }}" class="hidden mt-4 pt-4 border-t border-slate-200">
                                    <p class="text-xs font-semibold text-slate-700 mb-3">Checklist Items:</p>
                                    <div class="space-y-2">
                                        @forelse($routine->checklists as $checklist)
                                            <div class="flex flex-row items-center gap-2">

                                                <label
                                                    class="flex flex-1 items-center gap-2 p-3 rounded-xl bg-slate-50 border border-slate-200 hover:border-violet-200 hover:bg-violet-50 transition cursor-pointer min-w-0"
                                                    onclick="handleDrChecklistClick(event, {{ $checklist->id }}, {{ $checklist->is_done ? 'true' : 'false' }}, {{ $checklist->file_path ? 'true' : 'false' }})">

                                                    <div class="h-4 w-4 rounded border-2 flex-shrink-0 flex items-center justify-center
                                                                    {{ $checklist->is_done ? 'bg-violet-600 border-violet-600' : 'border-slate-300 bg-white' }}"
                                                        id="dr-checkbox-visual-{{ $checklist->id }}">
                                                        @if($checklist->is_done)
                                                            <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                                    d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                        @endif
                                                    </div>

                                                    <div
                                                        class="flex h-6 w-6 items-center justify-center rounded-full flex-shrink-0 text-xs
                                                                    {{ $checklist->is_done ? 'bg-emerald-600 text-white' : 'bg-slate-300 text-slate-400' }}">
                                                        @if($checklist->is_done) ✓ @else ○ @endif
                                                    </div>

                                                    <div class="min-w-0 flex-1">
                                                        <p
                                                            class="text-xs font-semibold {{ $checklist->is_done ? 'text-slate-500 line-through' : 'text-slate-800' }} truncate">
                                                            {{ $checklist->title }}
                                                        </p>
                                                        <p
                                                            class="text-xs {{ $checklist->is_done ? 'text-emerald-600' : 'text-slate-500' }}">
                                                            {{ $checklist->is_done ? 'Selesai' : 'Menunggu' }}
                                                        </p>

                                                        @if(!$checklist->is_done && $checklist->uncheck_reason)
                                                            <div
                                                                class="mt-1 flex items-start gap-1 rounded-lg bg-red-50 border border-red-100 px-2 py-1.5">
                                                                <span class="text-red-400 text-xs shrink-0">⚠</span>
                                                                <p class="text-xs text-red-500 leading-relaxed">
                                                                    {{ $checklist->uncheck_reason }}</p>
                                                            </div>
                                                        @endif

                                                        @if($checklist->file_path)
                                                            <div class="mt-1.5 flex items-center gap-1.5">
                                                                @if(str_starts_with($checklist->file_type ?? '', 'image/'))
                                                                    <a href="{{ Storage::url($checklist->file_path) }}" target="_blank"
                                                                        onclick="event.stopPropagation()"
                                                                        class="block rounded overflow-hidden border border-slate-200 w-10 h-10 flex-shrink-0">
                                                                        <img src="{{ Storage::url($checklist->file_path) }}" alt="Bukti"
                                                                            class="w-full h-full object-cover">
                                                                    </a>
                                                                @else
                                                                    <a href="{{ Storage::url($checklist->file_path) }}" target="_blank"
                                                                        onclick="event.stopPropagation()"
                                                                        class="inline-flex items-center gap-1 rounded-lg bg-slate-100 px-2 py-1 text-xs font-medium text-slate-700 max-w-[90px] truncate">
                                                                        📎 {{ Str::limit($checklist->file_name ?? 'File', 10) }}
                                                                    </a>
                                                                @endif
                                                                <button type="button"
                                                                    onclick="event.stopPropagation(); deleteDrChecklistFile({{ $checklist->id }})"
                                                                    class="rounded-full bg-red-100 p-1 text-red-500 hover:bg-red-200 transition flex-shrink-0">
                                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        @else
                                                            <p class="text-xs text-amber-600 mt-0.5">⚠ Belum ada bukti</p>
                                                        @endif
                                                    </div>

                                                    <span
                                                        class="rounded-full px-2 py-0.5 text-xs font-semibold flex-shrink-0
                                                                    {{ $checklist->is_done ? 'bg-slate-100 text-slate-600' : 'bg-violet-100 text-violet-700' }}">
                                                        {{ $checklist->is_done ? 'Batal' : 'Selesai' }}
                                                    </span>
                                                </label>

                                                <form id="dr-uncheck-form-{{ $checklist->id }}" method="POST"
                                                    action="{{ route('daily-routine.checklist.toggle', $checklist->id) }}"
                                                    class="hidden">
                                                    @csrf
                                                    @method('PATCH')
                                                </form>

                                            </div>
                                        @empty
                                            <p class="text-xs text-slate-500 text-center py-3">Tidak ada checklist</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Mobile empty filter state --}}
                    <div id="drMobileEmptyFilter" class="hidden p-10 text-center">
                        <p class="text-2xl mb-2">🔍</p>
                        <p class="text-sm font-semibold text-slate-700">Tidak ada hasil</p>
                        <p class="text-xs text-slate-400 mt-1">Coba kata kunci atau filter lain</p>
                    </div>
                </div>

            </div>
        @else
            <div class="rounded-[32px] border border-slate-200 bg-white p-10 text-center shadow-sm">
                <div class="flex justify-center mb-4">
                    <svg class="w-16 h-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                </div>
                <p class="text-lg font-semibold text-slate-800">Belum ada daily routine</p>
                <p class="mt-3 text-sm text-slate-500">Manager belum mengirimkan daily routine ke akun kamu.</p>
            </div>
        @endif

    </div>

    {{-- ============================================================ --}}
    {{-- UPLOAD MODAL --}}
    {{-- ============================================================ --}}
    <div id="drUploadModal"
        class="fixed inset-0 z-[60] hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4"
        onclick="closeDrUploadModal(event)">
        <div class="w-full max-w-md rounded-[28px] bg-white shadow-2xl border border-slate-200 overflow-hidden"
            onclick="event.stopPropagation()">

            {{-- Header --}}
            <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-violet-50 to-slate-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Upload Bukti Pengerjaan</h3>
                        <p id="drUploadModalSubtitle" class="mt-0.5 text-xs text-slate-500 line-clamp-1">—</p>
                    </div>
                    <button onclick="closeDrUploadModal()"
                        class="rounded-full bg-slate-100 p-2 hover:bg-slate-200 transition">
                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Body --}}
            <div class="px-6 py-5 space-y-4">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Pilih Sumber File</p>
                <div class="grid grid-cols-3 gap-3">
                    <button type="button" onclick="drTriggerFileInput('gallery')"
                        class="flex flex-col items-center gap-2 rounded-2xl border-2 border-slate-200 bg-slate-50 p-4 hover:border-violet-400 hover:bg-violet-50 transition active:scale-95">
                        <span class="text-2xl">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            </svg>
                        </span>
                        <span class="text-xs font-semibold text-slate-600">Galeri</span>
                    </button>
                    <button type="button" onclick="drTriggerFileInput('camera')"
                        class="flex flex-col items-center gap-2 rounded-2xl border-2 border-slate-200 bg-slate-50 p-4 hover:border-violet-400 hover:bg-violet-50 transition active:scale-95">
                        <span class="text-2xl">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                            </svg>
                        </span>
                        <span class="text-xs font-semibold text-slate-600">Kamera</span>
                    </button>
                    <button type="button" onclick="drTriggerFileInput('document')"
                        class="flex flex-col items-center gap-2 rounded-2xl border-2 border-slate-200 bg-slate-50 p-4 hover:border-violet-400 hover:bg-violet-50 transition active:scale-95">
                        <span class="text-2xl">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                        </span>
                        <span class="text-xs font-semibold text-slate-600">Dokumen</span>
                    </button>
                </div>

                {{-- Hidden file inputs --}}
                <input type="file" id="drFileInputGallery" accept="image/*,application/pdf,.xls,.xlsx" class="hidden"
                    onchange="drHandleFileSelected(this)">
                <input type="file" id="drFileInputCamera" accept="image/*" capture="environment" class="hidden"
                    onchange="drHandleFileSelected(this)">
                <input type="file" id="drFileInputDocument"
                    accept="application/pdf,.xls,.xlsx,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                    class="hidden" onchange="drHandleFileSelected(this)">

                {{-- ── LOCATION STATUS (NEW) ── --}}
                <div id="drLocationStatus" class="hidden rounded-xl border px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div id="drLocationIcon" class="text-lg flex-shrink-0">📍</div>
                        <div class="min-w-0 flex-1">
                            <p id="drLocationText" class="text-xs font-semibold text-slate-700">Mengambil lokasi...</p>
                            <p id="drLocationSub" class="text-xs text-slate-400 mt-0.5">Mohon izinkan akses lokasi</p>
                        </div>
                        <div id="drLocationSpinner"
                            class="w-4 h-4 rounded-full border-2 border-violet-400 border-t-transparent animate-spin flex-shrink-0">
                        </div>
                    </div>
                </div>

                {{-- Preview area --}}
                <div id="drUploadPreviewArea" class="hidden">
                    <div class="rounded-2xl border-2 border-violet-200 bg-violet-50 p-4">
                        <div class="flex items-center gap-3">
                            <div id="drUploadPreviewIcon" class="text-3xl flex-shrink-0">📎</div>
                            <div class="min-w-0 flex-1">
                                <p id="drUploadPreviewName" class="text-sm font-semibold text-slate-800 truncate">—</p>
                                <p id="drUploadPreviewSize" class="text-xs text-slate-500 mt-0.5">—</p>
                            </div>
                            <button type="button" onclick="drClearFileSelection()"
                                class="rounded-full bg-white p-1.5 shadow-sm hover:bg-red-50 transition flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <div id="drImagePreviewWrapper" class="hidden mt-3">
                            <img id="drImagePreviewEl" src="" alt="Preview"
                                class="w-full max-h-40 object-contain rounded-xl border border-slate-200">
                        </div>
                    </div>
                </div>

                <p class="text-xs text-slate-400 text-center">Mendukung: JPG, PNG, GIF, WEBP, PDF, XLS, XLSX · Maks 10 MB
                </p>

                <div id="drUploadErrorMsg" class="hidden rounded-xl bg-red-50 border border-red-200 px-4 py-3">
                    <p class="text-xs text-red-600 font-semibold" id="drUploadErrorText">—</p>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex gap-3">
                <button type="button" onclick="closeDrUploadModal()"
                    class="flex-1 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                    Batal
                </button>
                <button type="button" id="drUploadSubmitBtn" onclick="drSubmitChecklistFile()"
                    class="flex-1 rounded-2xl bg-violet-600 px-4 py-3 text-sm font-semibold text-white hover:bg-violet-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled>
                    <span id="drUploadSubmitLabel">Upload & Selesaikan</span>
                    <span id="drUploadSubmitSpinner" class="hidden">
                        <svg class="inline w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z">
                            </path>
                        </svg>
                        Mengupload...
                    </span>
                </button>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- CAMERA MODAL --}}
    {{-- ============================================================ --}}
    <div id="drCameraModal" class="fixed inset-0 z-[70] hidden items-center justify-center bg-slate-900/80 p-4">
            <div class="w-full max-w-lg rounded-[28px] bg-slate-900 overflow-hidden shadow-2xl border border-slate-700">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-700">
                    <h3 class="text-sm font-bold text-white">Ambil Foto Bukti</h3>
                    <button onclick="closeDrCameraModal()" class="rounded-full bg-slate-700 p-2 hover:bg-slate-600 transition">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>
                <div class="relative bg-black" style="aspect-ratio: 16/9;">
                    <video id="drCameraVideo" autoplay playsinline muted class="w-full h-full object-cover"></video>
                    <div class="absolute inset-0 pointer-events-none flex items-center justify-center">
                        <div class="border-2 border-white/40 rounded-xl w-2/3 h-2/3"></div>
                    </div>
                </div>
                <canvas id="drCameraCanvas" class="hidden"></canvas>
                <div class="flex items-center justify-center py-6 gap-6 bg-slate-900">
                    <button onclick="closeDrCameraModal()"
                        class="text-xs font-semibold text-slate-400 hover:text-white transition px-4 py-2">Batal</button>
                    <button onclick="drCapturePhoto()"
                        class="w-16 h-16 rounded-full bg-white border-4 border-slate-400 hover:bg-slate-100 active:scale-95 transition flex items-center justify-center shadow-lg">
                        <div class="w-12 h-12 rounded-full bg-white border-2 border-slate-300"></div>
                    </button>
                    <div class="w-16"></div>
                </div>
            </div>
        </div>

    {{-- ============================================================ --}}
    {{-- JAVASCRIPT --}}
    {{-- ============================================================ --}}
    <script>
        // ── State ────────────────────────────────────────────────────────
        let drUploadChecklistId   = null;
        let drUploadChecklistTitle = '';
        let drSelectedFile        = null;
        let drCameraStream        = null;
        let drCurrentLocation     = null; // { lat, lng, accuracy }

        // ── Location ──────────────────────────────────────────────────────
        function drStartLocationWatch() {
            
            drCurrentLocation = null;

            const statusEl  = document.getElementById('drLocationStatus');
            const iconEl    = document.getElementById('drLocationIcon');
            const textEl    = document.getElementById('drLocationText');
            const subEl     = document.getElementById('drLocationSub');
            const spinnerEl = document.getElementById('drLocationSpinner');

            // Reset ke state loading
            statusEl.className = 'rounded-xl border border-violet-200 bg-violet-50 px-4 py-3';
            statusEl.classList.remove('hidden');
            iconEl.textContent  = '📍';
            textEl.textContent  = 'Mengambil lokasi...';
            subEl.textContent   = 'Mohon izinkan akses lokasi';
            spinnerEl.classList.remove('hidden');

            if (!navigator.geolocation) {
                iconEl.textContent  = '⚠️';
                textEl.textContent  = 'Lokasi tidak tersedia';
                subEl.textContent   = 'Browser tidak mendukung geolocation';
                spinnerEl.classList.add('hidden');
                statusEl.className  = 'rounded-xl border border-amber-200 bg-amber-50 px-4 py-3';
                return;
            }

         navigator.geolocation.getCurrentPosition(
    async function(pos) {

        const locationName = await drGetLocationName(
            pos.coords.latitude,
            pos.coords.longitude
        );

        drCurrentLocation = {
            lat      : pos.coords.latitude,
            lng      : pos.coords.longitude,
            accuracy : Math.round(pos.coords.accuracy),
            address  : locationName
        };

        iconEl.textContent = '✅';

        // tampilkan nama lokasi
        textEl.textContent = locationName;

        subEl.textContent =
            'Akurasi ±' + drCurrentLocation.accuracy + 'm';

        spinnerEl.classList.add('hidden');
        statusEl.className =
            'rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3';
    },
    function(err) {
        iconEl.textContent = '❌';
        textEl.textContent = 'Gagal mendapatkan lokasi';
        subEl.textContent = err.code === 1
            ? 'Akses lokasi ditolak'
            : 'Coba lagi atau periksa GPS';

        spinnerEl.classList.add('hidden');
        statusEl.className =
            'rounded-xl border border-red-200 bg-red-50 px-4 py-3';
    },
    {
        enableHighAccuracy: true,
        timeout: 10000,
        maximumAge: 0
    }
);
        }

        // ── Stamp timestamp + lokasi ke gambar via Canvas ─────────────────
        // Hanya berlaku untuk file gambar; PDF/Excel dikembalikan as-is.
        async function drGetLocationName(lat, lng) {
    try {
        const response = await fetch(
            `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`
        );

        const data = await response.json();

        return data.display_name || 'Lokasi tidak diketahui';
    } catch (error) {
        console.error('Gagal mengambil nama lokasi:', error);
        return 'Lokasi tidak diketahui';
    }
}
        function drStampImage(file) {
            return new Promise(function(resolve) {
                if (!file.type.startsWith('image/')) {
                    resolve(file);
                    return;
                }

                var img = new Image();
                var url = URL.createObjectURL(file);

                img.onload = function() {
                    var canvas  = document.createElement('canvas');
                    canvas.width  = img.naturalWidth;
                    canvas.height = img.naturalHeight;
                    var ctx = canvas.getContext('2d');

                    // 1. Gambar foto asli
                    ctx.drawImage(img, 0, 0);
                    URL.revokeObjectURL(url);

                    // 2. Susun teks stamp
                    var now     = new Date();
                    var dateStr = now.toLocaleDateString('id-ID', {
                        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
                    });
                    var timeStr = now.toLocaleTimeString('id-ID', {
                        hour: '2-digit', minute: '2-digit', second: '2-digit'
                    });
       var locStr = drCurrentLocation
    ? drCurrentLocation.address
    : 'Lokasi tidak tersedia';

var coordStr = drCurrentLocation
    ? drCurrentLocation.lat.toFixed(5) + ', ' +
      drCurrentLocation.lng.toFixed(5)
    : '';

                   var lines = [
    dateStr,
    timeStr,
    locStr,
    coordStr
];

                    // 3. Ukuran font responsif terhadap resolusi gambar
                    var fontSize = Math.max(20, Math.round(canvas.width * 0.022));
                    var padding  = Math.round(fontSize * 0.7);
                    var lineH    = Math.round(fontSize * 1.55);
                    var boxH     = padding * 2 + lineH * lines.length;
                    var boxY     = canvas.height - boxH;

                    // 4. Background semi-transparan
                    ctx.fillStyle = 'rgba(0, 0, 0, 0.55)';
                    ctx.fillRect(0, boxY, canvas.width, boxH);

                    // 5. Teks putih dengan shadow
                    ctx.font         = 'bold ' + fontSize + 'px Arial, sans-serif';
                    ctx.fillStyle    = '#ffffff';
                    ctx.shadowColor  = 'rgba(0,0,0,0.8)';
                    ctx.shadowBlur   = 4;
                    ctx.textBaseline = 'top';

                    lines.forEach(function(line, i) {
                        ctx.fillText(line, padding, boxY + padding + i * lineH);
                    });

                    // 6. Export sebagai file baru
                    canvas.toBlob(function(blob) {
                        var stamped = new File([blob], file.name, {
                            type        : 'image/jpeg',
                            lastModified: Date.now(),
                        });
                        resolve(stamped);
                    }, 'image/jpeg', 0.92);
                };

                img.onerror = function() { resolve(file); }; // fallback
                img.src = url;
            });
        }

        // ── Camera ───────────────────────────────────────────────────────
       async function openDrCameraModal() {

    // lokasi masih belum ada
    if (!drCurrentLocation) {

        showLoading('Menunggu lokasi GPS...');

        const checkLocation = setInterval(() => {

            if (drCurrentLocation) {

                clearInterval(checkLocation);
                hideLoading();

                openDrCameraModal();
            }

        }, 500);

        setTimeout(() => {

            clearInterval(checkLocation);
            hideLoading();

            if (!drCurrentLocation) {
                alert('Lokasi belum berhasil diperoleh. Silakan tunggu beberapa saat.');
            }

        }, 10000);

        return;
    }

    const modal = document.getElementById('drCameraModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    try {

        drCameraStream = await navigator.mediaDevices.getUserMedia({
            video: {
                facingMode: {
                    ideal: 'environment'
                },
                width: {
                    ideal: 1280
                },
                height: {
                    ideal: 720
                }
            }
        });

        document.getElementById('drCameraVideo').srcObject = drCameraStream;

    } catch (err) {

        alert('Tidak dapat mengakses kamera: ' + err.message);
        closeDrCameraModal();
    }
}

        function closeDrCameraModal() {
            if (drCameraStream) {
                drCameraStream.getTracks().forEach(function(t) { t.stop(); });
                drCameraStream = null;
            }
            const modal = document.getElementById('drCameraModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function drCapturePhoto() {
            const video  = document.getElementById('drCameraVideo');
            const canvas = document.getElementById('drCameraCanvas');
            canvas.width  = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);
            canvas.toBlob(function(blob) {
                const file = new File([blob], 'foto-bukti.jpg', { type: 'image/jpeg' });
                closeDrCameraModal();
                drProcessAndPreviewFile(file);
            }, 'image/jpeg', 0.92);
        }

        // ── File processing: stamp lalu tampilkan preview ─────────────────
        async function drProcessAndPreviewFile(file) {
            drHideUploadError();

            // Sementara tombol disabled dan label berubah selama stamping
            const btn   = document.getElementById('drUploadSubmitBtn');
            const label = document.getElementById('drUploadSubmitLabel');
            btn.disabled      = true;
            label.textContent = 'Memproses...';

            var stamped = await drStampImage(file);
            drSelectedFile = stamped;

            label.textContent = 'Upload & Selesaikan';
            drShowFilePreview(stamped);
            btn.disabled = false;
        }

        // ── Checklist click handler ───────────────────────────────────────
        function handleDrChecklistClick(event, checklistId, isDone, hasFile) {
            event.preventDefault();
            event.stopPropagation();
            if (isDone) {
                document.getElementById('dr-uncheck-form-' + checklistId).submit();
                return;
            }
            openDrUploadModal(
                checklistId,
                event.currentTarget.querySelector('p.font-semibold')?.textContent?.trim() || ''
            );
        }

        // ── Upload modal ──────────────────────────────────────────────────
        function openDrUploadModal(checklistId, title) {
            drUploadChecklistId    = checklistId;
            drUploadChecklistTitle = title;
            drSelectedFile         = null;

            document.getElementById('drUploadModalSubtitle').textContent = title || 'Checklist item';
            document.getElementById('drUploadPreviewArea').classList.add('hidden');
            document.getElementById('drUploadErrorMsg').classList.add('hidden');
            document.getElementById('drLocationStatus').classList.add('hidden');
            document.getElementById('drUploadSubmitBtn').disabled = true;
            document.getElementById('drUploadSubmitLabel').textContent = 'Upload & Selesaikan';
            document.getElementById('drUploadSubmitLabel').classList.remove('hidden');
            document.getElementById('drUploadSubmitSpinner').classList.add('hidden');

            ['drFileInputGallery', 'drFileInputCamera', 'drFileInputDocument'].forEach(function(id) {
                document.getElementById(id).value = '';
            });

            // Mulai ambil lokasi begitu modal dibuka
            drStartLocationWatch();

            const modal = document.getElementById('drUploadModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDrUploadModal(event) {
            if (event && event.target !== document.getElementById('drUploadModal')) return;
            document.getElementById('drUploadModal').classList.add('hidden');
            document.getElementById('drUploadModal').classList.remove('flex');
            drUploadChecklistId = null;
            drSelectedFile      = null;
            drCurrentLocation   = null;
        }

        function drTriggerFileInput(source) {
            if (source === 'camera') { openDrCameraModal(); return; }
            const map = { gallery: 'drFileInputGallery', document: 'drFileInputDocument' };
            document.getElementById(map[source]).click();
        }

        async function drHandleFileSelected(input) {
            const file = input.files[0];
            if (!file) return;

            // Validasi ukuran (tidak berubah)
            if (file.size > 10 * 1024 * 1024) {
                drShowUploadError('Ukuran file melebihi 10 MB. Pilih file yang lebih kecil.');
                input.value = '';
                return;
            }

            // Validasi tipe (tidak berubah)
            const allowed    = [
                'image/jpeg', 'image/png', 'image/gif', 'image/webp',
                'application/pdf',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ];
            const ext        = file.name.split('.').pop().toLowerCase();
            const allowedExt = ['jpg','jpeg','png','gif','webp','pdf','xls','xlsx'];

            if (!allowed.includes(file.type) && !allowedExt.includes(ext)) {
                drShowUploadError('Tipe file tidak didukung. Gunakan JPG, PNG, GIF, WEBP, PDF, XLS, atau XLSX.');
                input.value = '';
                return;
            }

            // Proses stamp (gambar) atau langsung preview (dokumen)
            await drProcessAndPreviewFile(file);
        }

        function drShowFilePreview(file) {
            const area    = document.getElementById('drUploadPreviewArea');
            const nameEl  = document.getElementById('drUploadPreviewName');
            const sizeEl  = document.getElementById('drUploadPreviewSize');
            const iconEl  = document.getElementById('drUploadPreviewIcon');
            const imgWrap = document.getElementById('drImagePreviewWrapper');
            const imgEl   = document.getElementById('drImagePreviewEl');

            nameEl.textContent = file.name;
            sizeEl.textContent = drFormatBytes(file.size);

            const isImage = file.type.startsWith('image/');
            const isPdf   = file.type === 'application/pdf';
            const isExcel = ['application/vnd.ms-excel',
                             'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'].includes(file.type)
                         || ['xls','xlsx'].includes(file.name.split('.').pop().toLowerCase());

            iconEl.textContent = isImage ? '🖼️' : isPdf ? '📄' : isExcel ? '📊' : '📎';

            if (isImage) {
                const reader  = new FileReader();
                reader.onload = function(e) { imgEl.src = e.target.result; };
                reader.readAsDataURL(file);
                imgWrap.classList.remove('hidden');
            } else {
                imgWrap.classList.add('hidden');
            }

            area.classList.remove('hidden');
        }

        function drClearFileSelection() {
            drSelectedFile = null;
            ['drFileInputGallery','drFileInputCamera','drFileInputDocument'].forEach(function(id) {
                document.getElementById(id).value = '';
            });
            document.getElementById('drUploadPreviewArea').classList.add('hidden');
            document.getElementById('drUploadSubmitBtn').disabled = true;
        }

        function drShowUploadError(msg) {
            document.getElementById('drUploadErrorText').textContent = msg;
            document.getElementById('drUploadErrorMsg').classList.remove('hidden');
        }

        function drHideUploadError() {
            document.getElementById('drUploadErrorMsg').classList.add('hidden');
        }

        function drFormatBytes(bytes) {
            if (bytes < 1024)    return bytes + ' B';
            if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / 1048576).toFixed(1) + ' MB';
        }

        function drSubmitChecklistFile() {
            if (!drSelectedFile || !drUploadChecklistId) return;

            const btn     = document.getElementById('drUploadSubmitBtn');
            const label   = document.getElementById('drUploadSubmitLabel');
            const spinner = document.getElementById('drUploadSubmitSpinner');

            btn.disabled = true;
            label.classList.add('hidden');
            spinner.classList.remove('hidden');
            drHideUploadError();

            const formData = new FormData();
            formData.append('file', drSelectedFile);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            formData.append('latitude', drCurrentLocation.lat);
formData.append('longitude', drCurrentLocation.lng);
formData.append('address', drCurrentLocation.address);

            fetch('/project/daily-routine/checklist/' + drUploadChecklistId + '/upload-file', {
                method: 'POST',
                body  : formData,
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (!data.success) {
                    drShowUploadError(data.message || 'Gagal mengupload file.');
                    btn.disabled = false;
                    label.classList.remove('hidden');
                    spinner.classList.add('hidden');
                    return;
                }
                document.getElementById('drUploadModal').classList.add('hidden');
                document.getElementById('drUploadModal').classList.remove('flex');
                window.location.reload();
            })
            .catch(function() {
                drShowUploadError('Terjadi kesalahan. Silakan coba lagi.');
                btn.disabled = false;
                label.classList.remove('hidden');
                spinner.classList.add('hidden');
            });
        }

        function deleteDrChecklistFile(checklistId) {
            if (!confirm('Hapus file bukti ini? Status checklist akan kembali ke belum selesai.')) return;

            fetch('/project/daily-routine/checklist/' + checklistId + '/delete-file', {
                method : 'DELETE',
                headers: {
                    'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type' : 'application/json',
                },
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success) window.location.reload();
                else alert(data.message || 'Gagal menghapus file.');
            })
            .catch(function() { alert('Terjadi kesalahan.'); });
        }

        // ── Dropdown (desktop) ────────────────────────────────────────────
        function toggleDropdown(id) {
            document.getElementById(id).classList.toggle('hidden');
        }

        // ── Mobile dropdown ───────────────────────────────────────────────
        function toggleDrMobileDropdown(id) {
            const el   = document.getElementById(id);
            const icon = document.getElementById(id.replace('dr-mobile-dropdown-', 'dr-mobile-icon-'));
            el.classList.toggle('hidden');
            if (icon) icon.classList.toggle('rotate-180');
        }

        // ── Mobile search & filter ────────────────────────────────────────
        let drMobileActiveFilter = 'all';

        function setDrMobileFilter(filter) {
            drMobileActiveFilter = filter;
            document.querySelectorAll('.dr-mobile-filter-btn').forEach(function(btn) {
                btn.classList.remove('bg-violet-600', 'text-white');
                btn.classList.add('bg-slate-100', 'text-slate-600');
            });
            const activeBtn = document.getElementById('dr-filter-' + filter);
            if (activeBtn) {
                activeBtn.classList.remove('bg-slate-100', 'text-slate-600');
                activeBtn.classList.add('bg-violet-600', 'text-white');
            }
            filterDrMobileCards();
        }

        function filterDrMobileCards() {
            const query = document.getElementById('drMobileSearchInput').value.toLowerCase().trim();
            const cards = document.querySelectorAll('.dr-mobile-card');
            let visible = 0;

            cards.forEach(function(card) {
                const title           = card.dataset.title || '';
                const checklistTitles = card.dataset.checklistTitles || '';
                const status          = card.dataset.status || '';
                const doneCount       = parseInt(card.dataset.doneCount)  || 0;
                const totalCount      = parseInt(card.dataset.totalCount) || 0;

                const matchSearch = !query || title.includes(query) || checklistTitles.includes(query);

                let matchFilter = true;
                if      (drMobileActiveFilter === 'done')     matchFilter = totalCount > 0 && doneCount === totalCount;
                else if (drMobileActiveFilter === 'pending')  matchFilter = status === 'pending';
                else if (drMobileActiveFilter === 'progress') matchFilter = status === 'progress';

                if (matchSearch && matchFilter) {
                    card.classList.remove('hidden');
                    visible++;
                } else {
                    card.classList.add('hidden');
                }
            });

            const countEl = document.getElementById('drMobileFilterCount');
            countEl.textContent = (query || drMobileActiveFilter !== 'all')
                ? 'Menampilkan ' + visible + ' dari ' + cards.length + ' routine'
                : '';

            document.getElementById('drMobileEmptyFilter').classList.toggle('hidden', visible > 0);
        }
    </script>

@endsection