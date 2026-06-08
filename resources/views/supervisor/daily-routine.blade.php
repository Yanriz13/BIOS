@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- ══════════════════════════════════════════
         HEADER  (mirip staff, tapi untuk SPV)
    ══════════════════════════════════════════ --}}
    <div class="relative overflow-hidden rounded-[32px] border border-slate-200 bg-white shadow-sm p-6 md:p-8">
        <div class="absolute top-0 right-0 w-72 h-72 bg-violet-100 rounded-full blur-3xl opacity-50"></div>
        <div class="absolute bottom-0 left-0 w-72 h-72 bg-slate-100 rounded-full blur-3xl opacity-50"></div>

        <div class="relative z-10">
            <div class="flex items-center justify-between gap-3">
                <div class="min-w-0 flex-1">
                    <p class="text-[10px] md:text-sm uppercase tracking-[0.25em] text-violet-600 font-semibold">
                        Supervisor · Daily Routine
                    </p>
                    <h1 class="mt-2 md:mt-4 text-lg sm:text-xl md:text-3xl font-black text-slate-900 leading-tight">
                        Rutinitas Harian Tim
                    </h1>
                </div>

            </div>

            {{-- Stats Grid --}}
            <div class="mt-6 md:mt-8 grid grid-cols-3 gap-2 md:gap-4">
                <div class="rounded-[18px] md:rounded-[32px] border border-slate-200 bg-slate-50 p-3 md:p-6 shadow-sm min-w-0">
                    <p class="text-[10px] md:text-sm font-semibold text-slate-500 truncate">Staff</p>
                    <h2 class="mt-1 md:mt-3 text-lg md:text-3xl font-black text-slate-900">{{ $members->count() }}</h2>
                </div>
                <div class="rounded-[18px] md:rounded-[32px] border border-slate-200 bg-white p-3 md:p-6 shadow-sm min-w-0">
                    <p class="text-[10px] md:text-sm font-semibold text-slate-500 truncate">Total Routine</p>
                    <h2 class="mt-1 md:mt-3 text-lg md:text-3xl font-black text-slate-900">{{ $routinesByUser->flatten()->count() }}</h2>
                </div>
                <div class="rounded-[18px] md:rounded-[32px] border border-slate-200 bg-slate-50 p-3 md:p-6 shadow-sm min-w-0">
                    <p class="text-[10px] md:text-sm font-semibold text-slate-500 truncate">Checklist Selesai</p>
                    <h2 class="mt-1 md:mt-3 text-lg md:text-3xl font-black text-slate-900">
                        {{ $routinesByUser->flatten()->sum(fn($r) => $r->checklists->where('is_done', true)->count()) }}
                    </h2>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         PER-STAFF SECTIONS
    ══════════════════════════════════════════ --}}
    @forelse($members as $member)
        @php
            $memberRoutines = $routinesByUser->get($member->id, collect());
            $totalDone = $memberRoutines->sum(fn($r) => $r->checklists->where('is_done', true)->count());
            $totalCl   = $memberRoutines->sum(fn($r) => $r->checklists->count());
            $pctDone   = $totalCl > 0 ? round(($totalDone / $totalCl) * 100) : 0;
        @endphp

        <div class="rounded-[20px] md:rounded-[32px] border border-slate-200 bg-white shadow-sm overflow-hidden">

            {{-- Staff header --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-5 md:px-6 py-4 bg-slate-50 border-b border-slate-200">
                <div class="flex items-center gap-3">
                    <img src="https://i.pravatar.cc/100?u={{ $member->id }}"
                         class="w-9 h-9 rounded-full border-2 border-white shadow-sm shrink-0" alt="">
                    <div class="min-w-0">
                        <p class="font-bold text-slate-800 truncate">{{ $member->name }}</p>
                        <p class="text-xs text-slate-500 truncate">{{ $member->email }}</p>
                    </div>
                    <span class="ml-1 shrink-0 px-2.5 py-1 rounded-full bg-violet-100 text-violet-700 text-[11px] font-semibold">
                        {{ $memberRoutines->count() }} routine
                    </span>
                </div>
                <div class="flex items-center gap-3">
                    @if($totalCl > 0)
                        <div class="flex items-center gap-2">
                            <div class="w-28 h-1.5 bg-slate-200 rounded-full overflow-hidden">
                                <div class="h-full bg-emerald-400 rounded-full" style="width:{{ $pctDone }}%"></div>
                            </div>
                            <span class="text-xs text-slate-500 font-semibold">{{ $pctDone }}%</span>
                        </div>
                    @endif

                </div>
            </div>

            @if($memberRoutines->count())

                {{-- ── Desktop Table (sama persis dengan tabel staff) ── --}}
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
                                <th class="px-6 py-4 text-center text-sm font-semibold text-slate-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($memberRoutines as $index => $routine)
                                @php
                                    $clTotal = $routine->checklists->count();
                                    $clDone  = $routine->checklists->where('is_done', true)->count();
                                    $pct     = $clTotal > 0 ? round(($clDone / $clTotal) * 100) : 0;
                                    $statusColor = match($routine->status) {
                                        'progress' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'done'     => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        default    => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                    };
                                    $barClass = match($routine->status) {
                                        'progress' => 'bg-blue-400',
                                        'done'     => 'bg-emerald-400',
                                        default    => 'bg-violet-400',
                                    };
                                    $shortDays = ['senin'=>'Sen','selasa'=>'Sel','rabu'=>'Rab','kamis'=>'Kam','jumat'=>'Jum','sabtu'=>'Sab','minggu'=>'Min'];
                                    $dayColors = ['senin'=>'bg-blue-100 text-blue-700','selasa'=>'bg-emerald-100 text-emerald-700','rabu'=>'bg-violet-100 text-violet-700','kamis'=>'bg-amber-100 text-amber-700','jumat'=>'bg-rose-100 text-rose-700','sabtu'=>'bg-cyan-100 text-cyan-700','minggu'=>'bg-slate-200 text-slate-700'];
                                    $days = array_map('trim', explode(',', $routine->deadline ?? ''));
                                    $rowKey = 'spv-' . $member->id . '-' . $index;
                                @endphp

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
                                        <span class="inline-block rounded-full border px-3 py-1 text-xs font-semibold {{ $statusColor }}">
                                            {{ ucfirst($routine->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-block rounded-full bg-violet-100 px-3 py-1 text-sm font-semibold text-violet-700">
                                            {{ $clTotal }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col items-center gap-2">
                                            <div class="w-full bg-slate-200 rounded-full h-2 max-w-xs">
                                                <div class="{{ $barClass }} h-2 rounded-full" style="width:{{ $pct }}%"></div>
                                            </div>
                                            <p class="text-xs text-slate-500">{{ $clDone }}/{{ $clTotal }}</p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex flex-wrap justify-center gap-1 max-w-[140px] mx-auto">
                                            @foreach($days as $day)
                                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold {{ $dayColors[$day] ?? 'bg-slate-100 text-slate-600' }}">
                                                    {{ $shortDays[$day] ?? ucfirst($day) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            {{-- Expand checklist --}}
                                            <button onclick="toggleDropdown('{{ $rowKey }}')"
                                                title="Lihat Checklist"
                                                class="inline-flex items-center justify-center gap-1 rounded-xl bg-violet-50 hover:bg-violet-100 px-2.5 py-2 transition">
                                                <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                                </svg>
                                            </button>
                                            {{-- Status: Pending --}}
                                           
                                        </div>
                                    </td>
                                </tr>

                                {{-- Dropdown Checklist Row (sama dengan staff) --}}
                                <tr id="{{ $rowKey }}" class="hidden bg-slate-50 border-t-2 border-violet-200">
                                    <td colspan="7" class="px-6 py-6">
                                        <div class="space-y-3">
                                            <p class="text-sm font-semibold text-slate-700 mb-4">Checklist Items:</p>
                                            @forelse($routine->checklists as $cl)
                                                <div class="flex flex-col gap-2">
                                                    <label class="flex items-center gap-3 p-4 rounded-lg bg-white border border-slate-200 hover:border-violet-200 hover:bg-violet-50 transition cursor-pointer"
                                                        onclick="handleDrChecklistClick(event, {{ $cl->id }}, {{ $cl->is_done ? 'true' : 'false' }}, {{ $cl->file_path ? 'true' : 'false' }}, {{ $cl->uncheck_reason ? json_encode($cl->uncheck_reason) : 'null' }})">

                                                        <div class="h-5 w-5 rounded border-2 flex-shrink-0 flex items-center justify-center
                                                            {{ $cl->is_done ? 'bg-violet-600 border-violet-600' : 'border-slate-300 bg-white' }}"
                                                            id="dr-checkbox-visual-{{ $cl->id }}">
                                                            @if($cl->is_done)
                                                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                                                </svg>
                                                            @endif
                                                        </div>

                                                        <div class="min-w-0 flex-1">
                                                            <p class="text-sm font-semibold {{ $cl->is_done ? 'text-slate-500 line-through' : 'text-slate-800' }}">{{ $cl->title }}</p>
                                                            <p class="text-xs {{ $cl->is_done ? 'text-emerald-600' : 'text-slate-500' }}">{{ $cl->is_done ? 'Selesai' : 'Menunggu' }}</p>

                                                            @if(!$cl->is_done && $cl->uncheck_reason)
                                                                <div class="mt-1.5 flex items-start gap-1.5 rounded-xl bg-red-50 border border-red-100 px-3 py-2">
                                                                    <span class="text-red-400 text-xs shrink-0 mt-0.5">⚠</span>
                                                                    <div>
                                                                        <p class="text-xs font-semibold text-red-600">Alasan penolakan:</p>
                                                                        <p class="text-xs text-red-500 mt-0.5 leading-relaxed">{{ $cl->uncheck_reason }}</p>
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            @if($cl->file_path)
                                                                <div class="mt-2 flex items-center gap-2">
                                                                    @if(str_starts_with($cl->file_type ?? '', 'image/'))
                                                                        <a href="{{ Storage::url($cl->file_path) }}" target="_blank"
                                                                            class="block mt-1 rounded-lg overflow-hidden border border-slate-200 w-16 h-16"
                                                                            onclick="event.stopPropagation()">
                                                                            <img src="{{ Storage::url($cl->file_path) }}" alt="Bukti" class="w-full h-full object-cover">
                                                                        </a>
                                                                    @else
                                                                        <a href="{{ Storage::url($cl->file_path) }}" target="_blank" onclick="event.stopPropagation()"
                                                                            class="inline-flex items-center gap-1.5 rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition">
                                                                            @if($cl->file_type === 'application/pdf') 📄
                                                                            @elseif(in_array($cl->file_type, ['application/vnd.ms-excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])) 📊
                                                                            @else 📎
                                                                            @endif
                                                                            {{ $cl->file_name ?? 'Lihat File' }}
                                                                        </a>
                                                                    @endif
                                                                    <button type="button" onclick="event.stopPropagation(); deleteDrChecklistFile({{ $cl->id }})"
                                                                        class="rounded-full bg-red-100 p-1 text-red-500 hover:bg-red-200 transition">
                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                                        </svg>
                                                                    </button>
                                                                </div>
                                                            @else
                                                                <p class="text-xs text-amber-600 mt-1">⚠ Belum ada bukti</p>
                                                            @endif
                                                        </div>

                                                        {{-- SPV: tombol uncheck dengan alasan --}}
                                                        @if($cl->is_done)
                                                            <button type="button"
                                                                onclick="event.preventDefault(); event.stopPropagation(); openDrUncheckModal({{ $cl->id }}, {{ json_encode($cl->title) }})"
                                                                class="rounded-full px-2 py-1 text-xs font-semibold flex-shrink-0 bg-slate-100 text-slate-600 hover:bg-red-50 hover:text-red-600 transition">
                                                                Batalkan
                                                            </button>
                                                        @else
                                                            <span class="rounded-full px-2 py-1 text-xs font-semibold flex-shrink-0 bg-violet-100 text-violet-700">
                                                                Menunggu
                                                            </span>
                                                        @endif

                                                        <form id="dr-uncheck-form-{{ $cl->id }}" method="POST"
                                                            action="{{ route('daily-routine.checklist.toggle', $cl->id) }}" class="hidden">
                                                            @csrf @method('PATCH')
                                                        </form>
                                                    </label>
                                                </div>
                                            @empty
                                                <p class="text-sm text-slate-500 text-center py-4">Tidak ada checklist untuk routine ini.</p>
                                            @endforelse

                                            {{-- Tambah checklist item --}}

                                        </div>
                                    </td>
                                </tr>

                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- ── Mobile Card View (sama dengan staff) ── --}}
                <div class="md:hidden">

                    {{-- Search & Filter per-staff --}}
                    <div class="p-4 border-b border-slate-200 space-y-3">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                            </svg>
                            <input type="text"
                                placeholder="Cari routine..."
                                class="spv-mobile-search w-full pl-9 pr-4 py-2.5 rounded-2xl border border-slate-200 bg-slate-50 text-sm text-slate-800 placeholder-slate-400 outline-none focus:border-violet-400 focus:bg-white transition"
                                data-member="{{ $member->id }}"
                                oninput="filterSpvMobileCards({{ $member->id }})" />
                        </div>
                        <div class="flex items-center gap-2 overflow-x-auto pb-1">
                            <span class="text-xs font-semibold text-slate-500 shrink-0">Filter:</span>
                            @foreach(['all'=>'Semua','pending'=>'Pending','progress'=>'Progress','done'=>'Selesai'] as $val => $label)
                                <button onclick="setSpvMobileFilter({{ $member->id }}, '{{ $val }}')"
                                    id="spv-filter-{{ $member->id }}-{{ $val }}"
                                    class="spv-mobile-filter-btn-{{ $member->id }} shrink-0 rounded-full px-3 py-1.5 text-xs font-semibold transition
                                        {{ $val === 'all' ? 'bg-violet-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div id="spv-card-list-{{ $member->id }}" class="divide-y divide-slate-200">
                        @foreach($memberRoutines as $index => $routine)
                            @php
                                $clTotal = $routine->checklists->count();
                                $clDone  = $routine->checklists->where('is_done', true)->count();
                                $pct     = $clTotal > 0 ? round(($clDone / $clTotal) * 100) : 0;
                                $statusColor = match($routine->status) {
                                    'progress' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'done'     => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    default    => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                };
                                $shortDays = ['senin'=>'Sen','selasa'=>'Sel','rabu'=>'Rab','kamis'=>'Kam','jumat'=>'Jum','sabtu'=>'Sab','minggu'=>'Min'];
                                $dayColors = ['senin'=>'bg-blue-100 text-blue-700','selasa'=>'bg-emerald-100 text-emerald-700','rabu'=>'bg-violet-100 text-violet-700','kamis'=>'bg-amber-100 text-amber-700','jumat'=>'bg-rose-100 text-rose-700','sabtu'=>'bg-cyan-100 text-cyan-700','minggu'=>'bg-slate-200 text-slate-700'];
                                $days = array_map('trim', explode(',', $routine->deadline ?? ''));
                                $mobileKey = 'spv-m-' . $member->id . '-' . $index;
                            @endphp

                            <div class="spv-mobile-card-{{ $member->id }} p-4"
                                data-title="{{ strtolower($routine->title) }}"
                                data-status="{{ $routine->status }}"
                                data-checklist-titles="{{ strtolower($routine->checklists->pluck('title')->join(' ')) }}"
                                data-done-count="{{ $clDone }}"
                                data-total-count="{{ $clTotal }}">

                                <button onclick="toggleDrMobileDropdown('{{ $mobileKey }}')" class="w-full text-left">
                                    <div class="flex items-start justify-between gap-3 mb-3">
                                        <div class="min-w-0 flex-1">
                                            <h3 class="text-sm font-bold text-slate-900">{{ $routine->title }}</h3>
                                            <span class="inline-block mt-1 rounded-full border px-2 py-0.5 text-[11px] font-semibold {{ $statusColor }}">
                                                {{ ucfirst($routine->status) }}
                                            </span>
                                        </div>
                                        <svg id="{{ $mobileKey }}-icon" class="w-5 h-5 text-slate-400 transition-transform flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                        </svg>
                                    </div>

                                    @if($routine->description)
                                        <p class="text-xs text-slate-600 mb-3 line-clamp-2">{{ $routine->description }}</p>
                                    @endif

                                    <div class="flex flex-col gap-1.5 mb-3">
                                        <div class="flex justify-between items-center">
                                            <span class="text-xs font-semibold text-slate-600">Progress</span>
                                            <span class="text-xs text-slate-500">{{ $clDone }}/{{ $clTotal }}</span>
                                        </div>
                                        <div class="w-full bg-slate-200 rounded-full h-1.5">
                                            <div class="bg-emerald-500 h-1.5 rounded-full transition-all" style="width:{{ $pct }}%"></div>
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap gap-2 items-center">
                                        <span class="rounded-full bg-violet-100 px-2 py-1 text-xs font-semibold text-violet-700">{{ $clTotal }} Items</span>
                                        @if($clDone === $clTotal && $clTotal > 0)
                                            <span class="rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-700">✓ Selesai</span>
                                        @endif
                                        @if($routine->deadline)
                                            <div class="flex flex-wrap items-center gap-1">
                                                @foreach($days as $day)
                                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold {{ $dayColors[$day] ?? 'bg-slate-100 text-slate-600' }}">
                                                        {{ $shortDays[$day] ?? ucfirst($day) }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </button>



                                {{-- Expandable Checklist --}}
                                <div id="{{ $mobileKey }}" class="hidden mt-4 pt-4 border-t border-slate-200">
                                    <p class="text-xs font-semibold text-slate-700 mb-3">Checklist Items:</p>
                                    <div class="space-y-2">
                                        @forelse($routine->checklists as $cl)
                                            <div class="flex flex-row items-center gap-2">
                                                <label class="flex flex-1 items-center gap-2 p-3 rounded-xl bg-slate-50 border border-slate-200 hover:border-violet-200 hover:bg-violet-50 transition cursor-pointer min-w-0"
                                                    onclick="handleDrChecklistClick(event, {{ $cl->id }}, {{ $cl->is_done ? 'true' : 'false' }}, {{ $cl->file_path ? 'true' : 'false' }}, {{ $cl->uncheck_reason ? json_encode($cl->uncheck_reason) : 'null' }})">

                                                    <div class="h-4 w-4 rounded border-2 flex-shrink-0 flex items-center justify-center
                                                        {{ $cl->is_done ? 'bg-violet-600 border-violet-600' : 'border-slate-300 bg-white' }}"
                                                        id="dr-checkbox-visual-{{ $cl->id }}">
                                                        @if($cl->is_done)
                                                            <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                                            </svg>
                                                        @endif
                                                    </div>

                                                    <div class="min-w-0 flex-1">
                                                        <p class="text-xs font-semibold {{ $cl->is_done ? 'text-slate-500 line-through' : 'text-slate-800' }} truncate">{{ $cl->title }}</p>
                                                        <p class="text-xs {{ $cl->is_done ? 'text-emerald-600' : 'text-slate-500' }}">{{ $cl->is_done ? 'Selesai' : 'Menunggu' }}</p>

                                                        @if(!$cl->is_done && $cl->uncheck_reason)
                                                            <div class="mt-1 flex items-start gap-1 rounded-lg bg-red-50 border border-red-100 px-2 py-1.5">
                                                                <span class="text-red-400 text-xs shrink-0">⚠</span>
                                                                <p class="text-xs text-red-500 leading-relaxed">{{ $cl->uncheck_reason }}</p>
                                                            </div>
                                                        @endif

                                                        @if($cl->file_path)
                                                            <div class="mt-1.5 flex items-center gap-1.5">
                                                                @if(str_starts_with($cl->file_type ?? '', 'image/'))
                                                                    <a href="{{ Storage::url($cl->file_path) }}" target="_blank" onclick="event.stopPropagation()"
                                                                        class="block rounded overflow-hidden border border-slate-200 w-10 h-10 flex-shrink-0">
                                                                        <img src="{{ Storage::url($cl->file_path) }}" alt="Bukti" class="w-full h-full object-cover">
                                                                    </a>
                                                                @else
                                                                    <a href="{{ Storage::url($cl->file_path) }}" target="_blank" onclick="event.stopPropagation()"
                                                                        class="inline-flex items-center gap-1 rounded-lg bg-slate-100 px-2 py-1 text-xs font-medium text-slate-700 max-w-[90px] truncate">
                                                                        📎 {{ Str::limit($cl->file_name ?? 'File', 10) }}
                                                                    </a>
                                                                @endif
                                                                <button type="button" onclick="event.stopPropagation(); deleteDrChecklistFile({{ $cl->id }})"
                                                                    class="rounded-full bg-red-100 p-1 text-red-500 hover:bg-red-200 transition flex-shrink-0">
                                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        @else
                                                            <p class="text-xs text-amber-600 mt-0.5">⚠ Belum ada bukti</p>
                                                        @endif
                                                    </div>

                                                    @if($cl->is_done)
                                                        <button type="button"
                                                            onclick="event.preventDefault(); event.stopPropagation(); openDrUncheckModal({{ $cl->id }}, {{ json_encode($cl->title) }})"
                                                            class="rounded-full px-2 py-0.5 text-xs font-semibold flex-shrink-0 bg-slate-100 text-slate-600 hover:bg-red-50 hover:text-red-600 transition">
                                                            Batal
                                                        </button>
                                                    @else
                                                        <span class="rounded-full px-2 py-0.5 text-xs font-semibold flex-shrink-0 bg-violet-100 text-violet-700">
                                                            Tunggu
                                                        </span>
                                                    @endif

                                                    <form id="dr-uncheck-form-{{ $cl->id }}" method="POST"
                                                        action="{{ route('daily-routine.checklist.toggle', $cl->id) }}" class="hidden">
                                                        @csrf @method('PATCH')
                                                    </form>
                                                </label>
                                            </div>
                                        @empty
                                            <p class="text-xs text-slate-500 text-center py-3">Tidak ada checklist</p>
                                        @endforelse


                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div id="spv-empty-{{ $member->id }}" class="hidden p-10 text-center">
                        <p class="text-2xl mb-2">🔍</p>
                        <p class="text-sm font-semibold text-slate-700">Tidak ada hasil</p>
                        <p class="text-xs text-slate-400 mt-1">Coba kata kunci atau filter lain</p>
                    </div>
                </div>

            @else
                <div class="text-center py-12 text-slate-400">
                    <div class="text-4xl mb-3">🔁</div>
                    <p class="text-sm font-medium text-slate-500">Belum ada routine untuk {{ $member->name }} hari ini.</p>

                </div>
            @endif
        </div>

    @empty
        <div class="text-center py-20 text-slate-400">
            <div class="text-5xl mb-4">👥</div>
            <p class="text-lg font-bold text-slate-600">Belum ada staff diassign ke Anda</p>
            <p class="text-sm text-slate-400 mt-1">Hubungi manager untuk assign staff ke Anda.</p>
        </div>
    @endforelse

</div>

{{-- ══════════════════════════════════════════════════════
     MODAL: Create Daily Routine
══════════════════════════════════════════════════════ --}}
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
                <label class="text-sm font-semibold text-slate-700">Judul Routine <span class="text-red-500">*</span></label>
                <input type="text" id="drTitle" placeholder="Contoh: Laporan harian..."
                    class="mt-1.5 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-violet-500 focus:outline-none">
            </div>
            <div>
                <label class="text-sm font-semibold text-slate-700">Deskripsi</label>
                <textarea id="drDesc" rows="2" placeholder="Opsional..."
                    class="mt-1.5 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-violet-500 focus:outline-none resize-none"></textarea>
            </div>
            <div>
                <label class="text-sm font-semibold text-slate-700">Assign ke Staff <span class="text-red-500">*</span></label>
                <select id="drUserId"
                    class="mt-1.5 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-violet-500 focus:outline-none">
                    <option value="">— Pilih Staff —</option>
                    @foreach($members as $m)
                        <option value="{{ $m->id }}">{{ $m->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm font-semibold text-slate-700">Hari Berlaku</label>
                <div class="mt-1.5 flex flex-wrap gap-2">
                    @foreach(['senin','selasa','rabu','kamis','jumat','sabtu','minggu'] as $day)
                        <label class="flex items-center gap-1.5 cursor-pointer">
                            <input type="checkbox" class="dr-day-cb accent-violet-600" value="{{ $day }}">
                            <span class="text-sm font-medium text-slate-700 capitalize">{{ $day }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            <div>
                <label class="text-sm font-semibold text-slate-700">Checklist Items</label>
                <div id="drChecklistContainer" class="mt-2 space-y-2"></div>
                <button onclick="addDrChecklist()"
                    class="mt-2 inline-flex items-center gap-2 text-sm text-violet-600 font-semibold hover:text-violet-700 transition">
                    + Tambah Item
                </button>
            </div>
        </div>
        <div class="border-t border-slate-200 px-6 py-4 flex justify-end gap-3 shrink-0">
            <button onclick="hideDailyRoutineForm()"
                class="px-5 py-2.5 rounded-2xl border border-slate-200 text-slate-700 text-sm font-semibold hover:bg-slate-50 transition">Batal</button>
            <button onclick="submitDailyRoutine()"
                class="px-5 py-2.5 rounded-2xl bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold transition">Simpan</button>
        </div>
    </div>
</div>

{{-- ── Modal Uncheck ── --}}
<div id="drUncheckModal" onclick="closeDrUncheckModal()"
    class="fixed inset-0 z-[60] hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
    <div onclick="event.stopPropagation()"
        class="w-full max-w-md overflow-hidden rounded-[32px] bg-white shadow-2xl border border-slate-200">
        <div class="flex items-center justify-between gap-4 border-b border-slate-200 px-6 py-5">
            <h2 class="text-xl font-black text-slate-900">Batalkan Checklist</h2>
            <button onclick="closeDrUncheckModal()" class="w-10 h-10 rounded-2xl bg-slate-100 hover:bg-slate-200 transition text-xl flex items-center justify-center">✕</button>
        </div>
        <div class="p-6 space-y-4">
            <div class="bg-indigo-50 border border-indigo-100 rounded-2xl px-4 py-3">
                <p class="text-xs text-indigo-400 font-semibold uppercase tracking-widest mb-1">Item</p>
                <p id="drUncheckTitle" class="font-bold text-indigo-800 text-sm"></p>
            </div>
            <input type="hidden" id="drUncheckId">
            <div>
                <label class="text-sm font-semibold text-slate-700">Alasan <span class="text-red-500">*</span></label>
                <textarea id="drUncheckReason" rows="3" placeholder="Tulis alasan..."
                    class="mt-1.5 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-indigo-500 focus:outline-none resize-none"></textarea>
                <p id="drUncheckError" class="hidden text-xs text-red-500 mt-1">Alasan wajib diisi.</p>
            </div>
        </div>
        <div class="px-6 py-4 border-t border-slate-100 flex gap-3 justify-end">
            <button onclick="closeDrUncheckModal()"
                class="px-4 py-2.5 rounded-2xl border border-slate-200 text-slate-700 text-sm font-semibold hover:bg-slate-50 transition">Batal</button>
            <button onclick="submitDrUncheck()"
                class="px-5 py-2.5 rounded-2xl bg-red-500 hover:bg-red-600 text-white text-sm font-semibold transition">Batalkan</button>
        </div>
    </div>
</div>

<script>
// ── Desktop dropdown toggle
function toggleDropdown(id) {
    document.getElementById(id)?.classList.toggle('hidden');
}

// ── Mobile dropdown toggle
function toggleDrMobileDropdown(id) {
    const el   = document.getElementById(id);
    const icon = document.getElementById(id + '-icon');
    el?.classList.toggle('hidden');
    icon?.classList.toggle('rotate-180');
}

// ── Create routine modal
function showDailyRoutineForm(userId) {
    document.getElementById('drTitle').value = '';
    document.getElementById('drDesc').value = '';
    document.getElementById('drChecklistContainer').innerHTML = '';
    document.querySelectorAll('.dr-day-cb').forEach(cb => cb.checked = false);
    const sel = document.getElementById('drUserId');
    sel.value = userId || '';
    const wrapper = document.getElementById('dailyRoutineModalWrapper');
    wrapper.classList.remove('hidden');
    wrapper.classList.add('flex');
}
function hideDailyRoutineForm() {
    const wrapper = document.getElementById('dailyRoutineModalWrapper');
    wrapper.classList.add('hidden');
    wrapper.classList.remove('flex');
}
function addDrChecklist() {
    const container = document.getElementById('drChecklistContainer');
    const div = document.createElement('div');
    div.className = 'flex items-center gap-2';
    div.innerHTML = `
        <input type="text" class="dr-cl-input flex-1 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:border-violet-400 focus:outline-none" placeholder="Item checklist...">
        <button type="button" onclick="this.parentElement.remove()" class="w-9 h-9 flex items-center justify-center rounded-2xl bg-red-50 text-red-500 hover:bg-red-100 transition text-lg font-bold">×</button>
    `;
    container.appendChild(div);
}
async function submitDailyRoutine() {
    const title  = document.getElementById('drTitle').value.trim();
    const userId = document.getElementById('drUserId').value;
    if (!title)  { alert('Judul routine wajib diisi.'); return; }
    if (!userId) { alert('Pilih staff terlebih dahulu.'); return; }
    const days       = [...document.querySelectorAll('.dr-day-cb:checked')].map(c => c.value);
    const checklists = [...document.querySelectorAll('.dr-cl-input')].map(i => i.value.trim()).filter(Boolean);
    const resp = await fetch('{{ route("daily-routine.store") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
        body: JSON.stringify({ title, description: document.getElementById('drDesc').value.trim(), user_id: userId, deadline: days.join(','), checklists }),
    });
    const data = await resp.json();
    if (data.success) { hideDailyRoutineForm(); location.reload(); }
    else alert(data.message || 'Gagal menyimpan routine.');
}

// ── Update status
async function updateRoutineStatus(routineId, status) {
    const resp = await fetch(`/project/daily-routine/${routineId}/status`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
        body: JSON.stringify({ status }),
    });
    const data = await resp.json();
    if (data.success) location.reload();
    else alert(data.message || 'Gagal update status.');
}

// ── Delete routine
async function deleteRoutine(routineId) {
    if (!confirm('Hapus routine ini?')) return;
    const resp = await fetch(`/project/daily-routine/${routineId}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
    });
    const data = await resp.json();
    if (data.success) location.reload();
    else alert(data.message || 'Gagal hapus routine.');
}

// ── Add checklist item (support mobile suffix)
async function addChecklistItem(routineId, suffix) {
    const inputId = suffix ? `new-cl-${suffix}-${routineId}` : `new-cl-${routineId}`;
    const input = document.getElementById(inputId);
    const title = input?.value?.trim();
    if (!title) return;
    const resp = await fetch('/project/daily-routine/checklist/add', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
        body: JSON.stringify({ routine_id: routineId, title }),
    });
    const data = await resp.json();
    if (data.success) location.reload();
    else alert(data.message || 'Gagal menambah checklist.');
}

// ── Delete file
function deleteDrChecklistFile(clId) {
    if (!confirm('Hapus file bukti ini?')) return;
    fetch(`/project/daily-routine/checklist/${clId}/delete-file`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Content-Type': 'application/json' },
    }).then(r => r.json()).then(data => { if (data.success) location.reload(); else alert(data.message || 'Gagal hapus file.'); })
      .catch(() => alert('Terjadi kesalahan.'));
}

// ── Checklist click handler (SPV sama dengan staff: upload jika belum selesai, uncheck jika sudah)
function handleDrChecklistClick(event, checklistId, isDone, hasFile, uncheckReason) {
    event.preventDefault(); event.stopPropagation();
    if (isDone) {
        const title = event.currentTarget.querySelector('p.font-semibold')?.textContent?.trim() || '';
        openDrUncheckModal(checklistId, title);
        return;
    }
    openDrUploadModal(checklistId, event.currentTarget.querySelector('p.font-semibold')?.textContent?.trim() || '', uncheckReason);
}

// ── Uncheck modal
function openDrUncheckModal(clId, title) {
    document.getElementById('drUncheckId').value = clId;
    document.getElementById('drUncheckTitle').innerText = title;
    document.getElementById('drUncheckReason').value = '';
    document.getElementById('drUncheckError').classList.add('hidden');
    const m = document.getElementById('drUncheckModal');
    m.classList.remove('hidden'); m.classList.add('flex');
}
function closeDrUncheckModal() {
    const m = document.getElementById('drUncheckModal');
    m.classList.add('hidden'); m.classList.remove('flex');
}
async function submitDrUncheck() {
    const clId   = document.getElementById('drUncheckId').value;
    const reason = document.getElementById('drUncheckReason').value.trim();
    if (!reason) { document.getElementById('drUncheckError').classList.remove('hidden'); return; }
    const resp = await fetch(`/project/daily-routine/checklist/${clId}/manager-uncheck`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
        body: JSON.stringify({ reason }),
    });
    const data = await resp.json();
    if (data.success) { closeDrUncheckModal(); location.reload(); }
    else alert(data.message || 'Gagal membatalkan checklist.');
}

// ── Mobile Search & Filter (per member)
const spvMobileFilters = {};

function setSpvMobileFilter(memberId, filter) {
    spvMobileFilters[memberId] = filter;
    document.querySelectorAll(`.spv-mobile-filter-btn-${memberId}`).forEach(btn => {
        btn.classList.remove('bg-violet-600', 'text-white');
        btn.classList.add('bg-slate-100', 'text-slate-600');
    });
    const active = document.getElementById(`spv-filter-${memberId}-${filter}`);
    if (active) { active.classList.remove('bg-slate-100', 'text-slate-600'); active.classList.add('bg-violet-600', 'text-white'); }
    filterSpvMobileCards(memberId);
}

function filterSpvMobileCards(memberId) {
    const searchEl = document.querySelector(`.spv-mobile-search[data-member="${memberId}"]`);
    const query    = searchEl ? searchEl.value.toLowerCase().trim() : '';
    const filter   = spvMobileFilters[memberId] || 'all';
    const cards    = document.querySelectorAll(`.spv-mobile-card-${memberId}`);
    let visible    = 0;

    cards.forEach(card => {
        const title     = card.dataset.title || '';
        const clTitles  = card.dataset.checklistTitles || '';
        const status    = card.dataset.status || '';
        const done      = parseInt(card.dataset.doneCount) || 0;
        const total     = parseInt(card.dataset.totalCount) || 0;

        const matchSearch = !query || title.includes(query) || clTitles.includes(query);
        let matchFilter   = true;
        if      (filter === 'done')     matchFilter = total > 0 && done === total;
        else if (filter === 'pending')  matchFilter = status === 'pending';
        else if (filter === 'progress') matchFilter = status === 'progress';

        if (matchSearch && matchFilter) { card.classList.remove('hidden'); visible++; }
        else card.classList.add('hidden');
    });

    const emptyEl = document.getElementById(`spv-empty-${memberId}`);
    if (emptyEl) emptyEl.classList.toggle('hidden', visible > 0);
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') { hideDailyRoutineForm(); closeDrUncheckModal(); closeDrUploadModal(); } });

// ── State for upload
let drUploadChecklistId = null, drSelectedFile = null, drCameraStream = null;
let drLat = null, drLng = null, drAddress = null;

// ── Geolocation
function startDrLocationWatch() {
    drLat = drLng = drAddress = null;
    const s=document.getElementById('drLocationStatus'),icon=document.getElementById('drLocationIcon'),txt=document.getElementById('drLocationText'),sub=document.getElementById('drLocationSub'),spin=document.getElementById('drLocationSpinner');
    if(s){s.className='rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-3';s.classList.remove('hidden');icon.textContent='📍';txt.textContent='Mengambil lokasi...';sub.textContent='Mohon izinkan akses lokasi';spin.classList.remove('hidden');}
    if(!navigator.geolocation){if(s){icon.textContent='⚠️';txt.textContent='Lokasi tidak tersedia';sub.textContent='Browser tidak mendukung';spin.classList.add('hidden');}return;}
    navigator.geolocation.getCurrentPosition(
        async function(pos){
            drLat=pos.coords.latitude;drLng=pos.coords.longitude;
            try{const r=await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${drLat}&lon=${drLng}`,{headers:{'Accept-Language':'id'}});const d=await r.json();drAddress=d.display_name??null;}catch(_){}
            if(s){icon.textContent='✅';txt.textContent=drAddress??`${drLat.toFixed(5)}, ${drLng.toFixed(5)}`;sub.textContent='Akurasi ±'+Math.round(pos.coords.accuracy)+'m';spin.classList.add('hidden');s.className='rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3';}
        },
        function(err){if(s){icon.textContent='❌';txt.textContent='Gagal mendapatkan lokasi';sub.textContent=err.code===1?'Akses lokasi ditolak':'Periksa GPS Anda';spin.classList.add('hidden');s.className='rounded-xl border border-red-200 bg-red-50 px-4 py-3';}},
        {enableHighAccuracy:true,timeout:10000,maximumAge:0}
    );
}

// ── Stamp image
function drStampImage(file){return new Promise(function(resolve){if(!file.type.startsWith('image/')){resolve(file);return;}const img=new Image(),url=URL.createObjectURL(file);img.onload=function(){const canvas=document.createElement('canvas');canvas.width=img.naturalWidth;canvas.height=img.naturalHeight;const ctx=canvas.getContext('2d');ctx.drawImage(img,0,0);URL.revokeObjectURL(url);const now=new Date(),lines=[now.toLocaleDateString('id-ID',{weekday:'long',year:'numeric',month:'long',day:'numeric'}),now.toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit',second:'2-digit'}),drAddress??'Lokasi tidak tersedia',(drLat&&drLng)?drLat.toFixed(5)+', '+drLng.toFixed(5):''].filter(Boolean);const fs=Math.max(20,Math.round(canvas.width*0.022)),pad=Math.round(fs*0.7),lh=Math.round(fs*1.55),boxH=pad*2+lh*lines.length,boxY=canvas.height-boxH;ctx.fillStyle='rgba(0,0,0,0.55)';ctx.fillRect(0,boxY,canvas.width,boxH);ctx.font='bold '+fs+'px Arial,sans-serif';ctx.fillStyle='#ffffff';ctx.shadowColor='rgba(0,0,0,0.8)';ctx.shadowBlur=4;ctx.textBaseline='top';lines.forEach(function(l,i){ctx.fillText(l,pad,boxY+pad+i*lh);});canvas.toBlob(function(blob){resolve(new File([blob],file.name,{type:'image/jpeg',lastModified:Date.now()}));},'image/jpeg',0.92);};img.onerror=function(){resolve(file);};img.src=url;});}

async function drProcessAndPreview(file){drHideUploadError();const btn=document.getElementById('drUploadSubmitBtn'),label=document.getElementById('drUploadSubmitLabel');btn.disabled=true;label.textContent='Memproses...';drSelectedFile=await drStampImage(file);label.textContent='Upload & Selesaikan';drShowFilePreview(drSelectedFile);btn.disabled=false;}

// ── Upload modal
function openDrUploadModal(checklistId, title, uncheckReason) {
    drUploadChecklistId = checklistId; drSelectedFile = null; drLat = drLng = drAddress = null;
    document.getElementById('drUploadSubtitle').textContent = title || 'Checklist item';
    document.getElementById('drUploadPreviewArea').classList.add('hidden');
    document.getElementById('drUploadErrorMsg').classList.add('hidden');
    document.getElementById('drUploadSubmitBtn').disabled = true;
    document.getElementById('drUploadSubmitLabel').textContent = 'Upload & Selesaikan';
    document.getElementById('drUploadSubmitLabel').classList.remove('hidden');
    document.getElementById('drUploadSubmitSpinner').classList.add('hidden');
    const ra = document.getElementById('drUploadReasonArea');
    if (uncheckReason) { ra.classList.remove('hidden'); document.getElementById('drUploadReasonText').textContent = uncheckReason; }
    else ra.classList.add('hidden');
    ['drFileInputGallery','drFileInputCamera','drFileInputDocument'].forEach(function(id){ document.getElementById(id).value=''; });
    startDrLocationWatch();
    const modal = document.getElementById('drUploadModal'); modal.classList.remove('hidden'); modal.classList.add('flex');
}
function closeDrUploadModal(event) {
    if (event && event.target !== document.getElementById('drUploadModal')) return;
    document.getElementById('drUploadModal').classList.add('hidden'); document.getElementById('drUploadModal').classList.remove('flex');
    drUploadChecklistId = null; drSelectedFile = null;
}
function triggerDrFileInput(source) { if (source==='camera'){openDrCameraModal();return;} document.getElementById(source==='gallery'?'drFileInputGallery':'drFileInputDocument').click(); }
async function handleDrFileSelected(input) { const file=input.files[0];if(!file)return;if(file.size>10*1024*1024){drShowUploadError('Ukuran file melebihi 10 MB.');input.value='';return;}await drProcessAndPreview(file); }

// ── Camera
async function openDrCameraModal(){const modal=document.getElementById('drCameraModal');modal.classList.remove('hidden');modal.classList.add('flex');try{drCameraStream=await navigator.mediaDevices.getUserMedia({video:{facingMode:{ideal:'environment'}}});document.getElementById('drCameraVideo').srcObject=drCameraStream;}catch(err){alert('Tidak dapat mengakses kamera: '+err.message);closeDrCameraModal();}}
function closeDrCameraModal(){if(drCameraStream){drCameraStream.getTracks().forEach(function(t){t.stop();});drCameraStream=null;}document.getElementById('drCameraModal').classList.add('hidden');document.getElementById('drCameraModal').classList.remove('flex');}
function captureDrPhoto(){const video=document.getElementById('drCameraVideo'),canvas=document.getElementById('drCameraCanvas');canvas.width=video.videoWidth;canvas.height=video.videoHeight;canvas.getContext('2d').drawImage(video,0,0);canvas.toBlob(function(blob){const file=new File([blob],'foto-bukti.jpg',{type:'image/jpeg'});closeDrCameraModal();drProcessAndPreview(file);},'image/jpeg',0.92);}

// ── File preview helpers
function drShowFilePreview(file){const area=document.getElementById('drUploadPreviewArea'),nameEl=document.getElementById('drUploadPreviewName'),sizeEl=document.getElementById('drUploadPreviewSize'),iconEl=document.getElementById('drUploadPreviewIcon'),imgWrap=document.getElementById('drImagePreviewWrapper'),imgEl=document.getElementById('drImagePreviewEl');nameEl.textContent=file.name;sizeEl.textContent=drFormatBytes(file.size);const isImage=file.type.startsWith('image/'),isPdf=file.type==='application/pdf';iconEl.textContent=isImage?'🖼️':isPdf?'📄':'📊';if(isImage){const r=new FileReader();r.onload=function(e){imgEl.src=e.target.result;};r.readAsDataURL(file);imgWrap.classList.remove('hidden');}else imgWrap.classList.add('hidden');area.classList.remove('hidden');}
function drClearFileSelection(){drSelectedFile=null;['drFileInputGallery','drFileInputCamera','drFileInputDocument'].forEach(function(id){document.getElementById(id).value='';});document.getElementById('drUploadPreviewArea').classList.add('hidden');document.getElementById('drUploadSubmitBtn').disabled=true;}
function drShowUploadError(msg){document.getElementById('drUploadErrorText').textContent=msg;document.getElementById('drUploadErrorMsg').classList.remove('hidden');}
function drHideUploadError(){document.getElementById('drUploadErrorMsg').classList.add('hidden');}
function drFormatBytes(b){if(b<1024)return b+' B';if(b<1048576)return (b/1024).toFixed(1)+' KB';return (b/1048576).toFixed(1)+' MB';}

// ── Submit upload
function submitDrChecklistFile(){
    if(!drSelectedFile||!drUploadChecklistId)return;
    const btn=document.getElementById('drUploadSubmitBtn'),label=document.getElementById('drUploadSubmitLabel'),spinner=document.getElementById('drUploadSubmitSpinner');
    btn.disabled=true;label.classList.add('hidden');spinner.classList.remove('hidden');drHideUploadError();
    const formData=new FormData();formData.append('file',drSelectedFile);formData.append('_token',document.querySelector('meta[name="csrf-token"]').content);
    if(drLat!==null)formData.append('latitude',drLat);if(drLng!==null)formData.append('longitude',drLng);if(drAddress!==null)formData.append('address',drAddress);
    fetch(`/project/daily-routine/checklist/${drUploadChecklistId}/upload-file`,{method:'POST',body:formData})
    .then(function(r){return r.json();}).then(function(data){if(!data.success){drShowUploadError(data.message||'Gagal mengupload file.');btn.disabled=false;label.classList.remove('hidden');spinner.classList.add('hidden');return;}document.getElementById('drUploadModal').classList.add('hidden');document.getElementById('drUploadModal').classList.remove('flex');window.location.reload();})
    .catch(function(){drShowUploadError('Terjadi kesalahan.');btn.disabled=false;label.classList.remove('hidden');spinner.classList.add('hidden');});
}
</script>

{{-- ── UPLOAD MODAL ── --}}
<div id="drUploadModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" onclick="closeDrUploadModal(event)">
    <div class="w-full max-w-md rounded-[28px] bg-white shadow-2xl border border-slate-200 overflow-hidden" onclick="event.stopPropagation()">
        <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-violet-50 to-slate-50">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Upload Bukti Pengerjaan</h3>
                    <p id="drUploadSubtitle" class="mt-0.5 text-xs text-slate-500 line-clamp-1">—</p>
                </div>
                <button onclick="closeDrUploadModal()" class="rounded-full bg-slate-100 p-2 hover:bg-slate-200 transition">
                    <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
        <div class="px-6 py-5 space-y-4">
            <div id="drUploadReasonArea" class="hidden">
                <div class="rounded-xl bg-red-50 border border-red-200 px-4 py-3 flex items-start gap-2">
                    <span class="text-red-400 text-lg shrink-0 mt-0.5">⚠️</span>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-semibold text-red-600 mb-1">Alasan Penolakan:</p>
                        <p id="drUploadReasonText" class="text-xs text-red-600 leading-relaxed break-words">—</p>
                    </div>
                </div>
            </div>
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Pilih Sumber File</p>
            <div class="grid grid-cols-3 gap-3">
                <button type="button" onclick="triggerDrFileInput('gallery')" class="flex flex-col items-center gap-2 rounded-2xl border-2 border-slate-200 bg-slate-50 p-4 hover:border-violet-400 hover:bg-violet-50 transition"><span class="text-2xl">🖼️</span><span class="text-xs font-semibold text-slate-600">Galeri</span></button>
                <button type="button" onclick="triggerDrFileInput('camera')" class="flex flex-col items-center gap-2 rounded-2xl border-2 border-slate-200 bg-slate-50 p-4 hover:border-violet-400 hover:bg-violet-50 transition"><span class="text-2xl">📷</span><span class="text-xs font-semibold text-slate-600">Kamera</span></button>
                <button type="button" onclick="triggerDrFileInput('document')" class="flex flex-col items-center gap-2 rounded-2xl border-2 border-slate-200 bg-slate-50 p-4 hover:border-violet-400 hover:bg-violet-50 transition"><span class="text-2xl">📎</span><span class="text-xs font-semibold text-slate-600">Dokumen</span></button>
            </div>
            <div id="drLocationStatus" class="hidden rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-3">
                <div class="flex items-center gap-3">
                    <div id="drLocationIcon" class="text-lg flex-shrink-0">📍</div>
                    <div class="min-w-0 flex-1">
                        <p id="drLocationText" class="text-xs font-semibold text-slate-700">Mengambil lokasi...</p>
                        <p id="drLocationSub" class="text-xs text-slate-400 mt-0.5">Mohon izinkan akses lokasi</p>
                    </div>
                    <div id="drLocationSpinner" class="w-4 h-4 rounded-full border-2 border-indigo-400 border-t-transparent animate-spin flex-shrink-0"></div>
                </div>
            </div>
            <input type="file" id="drFileInputGallery" accept="image/*,application/pdf,.xls,.xlsx" class="hidden" onchange="handleDrFileSelected(this)">
            <input type="file" id="drFileInputCamera" accept="image/*" capture="environment" class="hidden" onchange="handleDrFileSelected(this)">
            <input type="file" id="drFileInputDocument" accept="application/pdf,.xls,.xlsx" class="hidden" onchange="handleDrFileSelected(this)">
            <div id="drUploadPreviewArea" class="hidden">
                <div class="rounded-2xl border-2 border-violet-200 bg-violet-50 p-4">
                    <div class="flex items-center gap-3">
                        <div id="drUploadPreviewIcon" class="text-3xl flex-shrink-0">📎</div>
                        <div class="min-w-0 flex-1">
                            <p id="drUploadPreviewName" class="text-sm font-semibold text-slate-800 truncate">—</p>
                            <p id="drUploadPreviewSize" class="text-xs text-slate-500 mt-0.5">—</p>
                        </div>
                        <button type="button" onclick="drClearFileSelection()" class="rounded-full bg-white p-1.5 shadow-sm hover:bg-red-50 transition flex-shrink-0">
                            <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <div id="drImagePreviewWrapper" class="hidden mt-3 rounded-xl overflow-hidden border border-violet-100">
                        <img id="drImagePreviewEl" src="" class="w-full max-h-48 object-cover">
                    </div>
                </div>
            </div>
            <p class="text-xs text-slate-400 text-center">Mendukung: JPG, PNG, GIF, WEBP, PDF, XLS, XLSX · Maks 10 MB</p>
            <div id="drUploadErrorMsg" class="hidden rounded-xl bg-red-50 border border-red-200 px-4 py-3">
                <p class="text-xs text-red-600 font-semibold" id="drUploadErrorText">—</p>
            </div>
        </div>
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex gap-3">
            <button type="button" onclick="closeDrUploadModal()" class="flex-1 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">Batal</button>
            <button type="button" id="drUploadSubmitBtn" onclick="submitDrChecklistFile()" class="flex-1 rounded-2xl bg-violet-600 px-4 py-3 text-sm font-semibold text-white hover:bg-violet-700 transition disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                <span id="drUploadSubmitLabel">Upload & Selesaikan</span>
                <span id="drUploadSubmitSpinner" class="hidden"><svg class="inline w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg> Mengupload...</span>
            </button>
        </div>
    </div>
</div>

{{-- ── CAMERA MODAL ── --}}
<div id="drCameraModal" class="fixed inset-0 z-[70] hidden items-center justify-center bg-slate-900/80 p-4">
    <div class="w-full max-w-lg rounded-[28px] bg-slate-900 overflow-hidden shadow-2xl border border-slate-700">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-700">
            <h3 class="text-sm font-bold text-white">Ambil Foto Bukti</h3>
            <button onclick="closeDrCameraModal()" class="rounded-full bg-slate-700 p-2 hover:bg-slate-600 transition"><svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        <div class="relative bg-black" style="aspect-ratio:16/9;"><video id="drCameraVideo" autoplay playsinline muted class="w-full h-full object-cover"></video></div>
        <canvas id="drCameraCanvas" class="hidden"></canvas>
        <div class="flex items-center justify-center py-6 gap-6 bg-slate-900">
            <button onclick="closeDrCameraModal()" class="text-xs font-semibold text-slate-400 hover:text-white transition px-4 py-2">Batal</button>
            <button onclick="captureDrPhoto()" class="w-16 h-16 rounded-full bg-white border-4 border-slate-400 hover:bg-slate-100 active:scale-95 transition flex items-center justify-center shadow-lg"><div class="w-12 h-12 rounded-full bg-white border-2 border-slate-300"></div></button>
            <div class="w-16"></div>
        </div>
    </div>
</div>

@endsection