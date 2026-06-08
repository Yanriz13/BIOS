@extends('layouts.app')

@section('content')

    <div class="space-y-6">

        <!-- Header Section -->
        <div class="relative overflow-hidden rounded-[32px] border border-slate-200 bg-white shadow-sm p-6 md:p-8">
            <div class="absolute top-0 right-0 w-72 h-72 bg-indigo-100 rounded-full blur-3xl opacity-50"></div>
            <div class="absolute bottom-0 left-0 w-72 h-72 bg-slate-100 rounded-full blur-3xl opacity-50"></div>

            <div class="relative z-10">
                <div class="flex items-center justify-between gap-3">

                    {{-- LEFT --}}
                    <div class="min-w-0 flex-1">

                        <p class="text-[10px] md:text-sm
                                      uppercase
                                      tracking-[0.25em]
                                      text-indigo-600
                                      font-semibold">
                            Staff Projects
                        </p>

                        <h1 class="mt-2 md:mt-4
                                       text-lg sm:text-xl md:text-3xl
                                       font-black
                                       text-slate-900
                                       leading-tight">
                            Checklist Assignment Workspace
                        </h1>

                    </div>

                    {{-- RIGHT --}}
                    <div class="flex items-center gap-2 md:gap-3 shrink-0">

                        {{-- GLOBAL CHAT --}}
                        <button type="button" onclick="openStaffChat('global', null, 'Global Staff Chat')" class="inline-flex items-center justify-center
                                       rounded-2xl md:rounded-3xl
                                       bg-indigo-600
                                       px-3 md:px-5
                                       py-2 md:py-3
                                       text-[11px] md:text-sm
                                       font-semibold
                                       text-white
                                       shadow-lg shadow-indigo-100
                                       hover:bg-indigo-700
                                       transition
                                       whitespace-nowrap">

                            <span class="hidden sm:inline">
                                Global Chat
                            </span>

                            <span class="sm:hidden">
                                Chat
                            </span>

                        </button>

                        {{-- BACK --}}


                    </div>

                </div>

                <!-- Stats Grid -->
                <div class="mt-6 md:mt-8 grid grid-cols-3 gap-2 md:gap-4">

                    {{-- CARD --}}
                    <div class="rounded-[18px] md:rounded-[32px]
                                border border-slate-200
                                bg-slate-50
                                p-3 md:p-6
                                shadow-sm
                                min-w-0">

                        <p class="text-[10px] md:text-sm
                                  font-semibold
                                  text-slate-500
                                  truncate">
                            Assignments
                        </p>

                        <h2 class="mt-1 md:mt-3
                                   text-lg md:text-3xl
                                   font-black
                                   text-slate-900">
                            {{ $assignments->count() }}
                        </h2>

                    </div>

                    {{-- CARD --}}
                    <div class="rounded-[18px] md:rounded-[32px]
                                border border-slate-200
                                bg-white
                                p-3 md:p-6
                                shadow-sm
                                min-w-0">

                        <p class="text-[10px] md:text-sm
                                  font-semibold
                                  text-slate-500
                                  truncate">
                            Checklist
                        </p>

                        <h2 class="mt-1 md:mt-3
                                   text-lg md:text-3xl
                                   font-black
                                   text-slate-900">
                            {{ $totalChecklist }}
                        </h2>

                    </div>

                    {{-- CARD --}}
                    <div class="rounded-[18px] md:rounded-[32px]
                                border border-slate-200
                                bg-slate-50
                                p-3 md:p-6
                                shadow-sm
                                min-w-0">

                        <p class="text-[10px] md:text-sm
                                  font-semibold
                                  text-slate-500
                                  truncate">
                            Completed
                        </p>

                        <h2 class="mt-1 md:mt-3
                                   text-lg md:text-3xl
                                   font-black
                                   text-slate-900">
                            {{ $completedChecklist }}
                        </h2>

                    </div>

                </div>
            </div>
        </div>

        <!-- Table Section -->
        @if($assignments->count() > 0)
            <div class="rounded-[20px] md:rounded-[32px] border border-slate-200 bg-white shadow-sm overflow-hidden">
                <!-- Desktop Table View -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-4 md:px-6 py-4 text-left text-xs md:text-sm font-semibold text-slate-700">Task
                                </th>
                                <th class="px-4 md:px-6 py-4 text-left text-xs md:text-sm font-semibold text-slate-700">
                                    Description</th>
                                <th class="px-4 md:px-6 py-4 text-center text-xs md:text-sm font-semibold text-slate-700">
                                    Checklist</th>
                                <th class="px-4 md:px-6 py-4 text-center text-xs md:text-sm font-semibold text-slate-700">
                                    Progress</th>
                                <th class="px-4 md:px-6 py-4 text-center text-xs md:text-sm font-semibold text-slate-700">
                                    Deadline</th>
                                <th class="px-4 md:px-6 py-4 text-center text-xs md:text-sm font-semibold text-slate-700">Action
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($assignments as $index => $assignment)
                                                    <tr class="hover:bg-slate-50 transition">
                                                        <td class="px-4 md:px-6 py-4">
                                                            <p class="text-xs md:text-sm font-semibold text-slate-900">{{ $assignment->task->title }}
                                                            </p>
                                                            <p class="text-xs text-slate-400 mt-1">#{{ $assignment->task_id }}</p>
                                                        </td>
                                                        <td class="px-4 md:px-6 py-4">
                                                            <p class="text-xs md:text-sm text-slate-600 line-clamp-2">
                                                                {{ $assignment->description ?? '-' }}
                                                            </p>
                                                        </td>
                                                        <td class="px-4 md:px-6 py-4 text-center">
                                                            <span
                                                                class="inline-block rounded-full bg-indigo-100 px-3 py-1 text-xs md:text-sm font-semibold text-indigo-700">
                                                                {{ $assignment->checklists->count() }}
                                                            </span>
                                                        </td>
                                                        <td class="px-4 md:px-6 py-4">
                                                            <div class="flex flex-col items-center gap-2">
                                                                <div class="w-full bg-slate-200 rounded-full h-2 max-w-xs">
                                                                    <div class="bg-emerald-500 h-2 rounded-full"
                                                                        style="width: {{ $assignment->checklists->count() > 0 ? ($assignment->checklists->where('is_done', true)->count() / $assignment->checklists->count() * 100) : 0 }}%">
                                                                    </div>
                                                                </div>
                                                                <p class="text-xs text-slate-500">
                                                                    {{ $assignment->checklists->where('is_done', true)->count() }}/{{ $assignment->checklists->count() }}
                                                                </p>
                                                            </div>
                                                        </td>
                                                        <td class="px-4 md:px-6 py-4 text-center">
                                                            @if($assignment->deadline)
                                                                <span
                                                                    class="inline-block rounded-full bg-slate-100 px-3 py-1 text-xs md:text-sm font-semibold text-slate-600">
                                                                    {{ \Carbon\Carbon::parse($assignment->deadline)->format('d M Y') }}
                                                                </span>
                                                            @else
                                                                <span class="text-slate-400 text-xs">-</span>
                                                            @endif
                                                        </td>
                                                        <td class="px-4 md:px-6 py-4 text-center">
                                                            <button onclick="toggleDropdown('dropdown-{{ $index }}')"
                                                                class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-50 hover:bg-indigo-100 px-3 py-2 transition">
                                                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                        d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                                                </svg>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <!-- Dropdown Content -->
                                                    <tr id="dropdown-{{ $index }}" class="hidden bg-slate-50 border-t-2 border-indigo-200">
                                                        <td colspan="6" class="px-6 py-6">

                                        <div class="space-y-3">
                                            <p class="text-sm font-semibold text-slate-700 mb-4">
                                                Checklist Items:
                                            </p>

                                            @forelse($assignment->checklists as $checklist)
                                                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

                                                    {{-- Header Checklist --}}
                                                    <div
                                                        class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between p-4 border-b border-slate-100">

                                                        <label
                                                            class="flex items-start gap-3 flex-1 cursor-pointer"
                                                            onclick="handleChecklistClick(
                                                                event,
                                                                {{ $checklist->id }},
                                                                {{ $checklist->is_done ? 'true' : 'false' }},
                                                                {{ $checklist->file_path ? 'true' : 'false' }},
                                                                {{ $checklist->uncheck_reason ? json_encode($checklist->uncheck_reason) : 'null' }}
                                                            )">

                                                            {{-- Checkbox --}}
                                                            <div class="h-5 w-5 rounded border-2 flex-shrink-0 flex items-center justify-center mt-0.5
                                                                {{ $checklist->is_done
                                                ? 'bg-indigo-600 border-indigo-600'
                                                : 'border-slate-300 bg-white' }}"
                                                                id="checkbox-visual-{{ $checklist->id }}">

                                                                @if($checklist->is_done)
                                                                    <svg class="w-3 h-3 text-white"
                                                                        fill="none"
                                                                        stroke="currentColor"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round"
                                                                            stroke-width="3"
                                                                            d="M5 13l4 4L19 7">
                                                                        </path>
                                                                    </svg>
                                                                @endif
                                                            </div>

                                                            {{-- Content --}}
                                                            <div class="min-w-0 flex-1">

                                                                <div class="flex flex-wrap items-center gap-2">

                                                                    <p class="text-sm font-semibold
                                                                        {{ $checklist->is_done
                                                ? 'text-slate-500 line-through'
                                                : 'text-slate-800' }}">
                                                                        {{ $checklist->title }}
                                                                    </p>

                                                                    <span
                                                                        class="rounded-full px-2.5 py-1 text-[11px] font-semibold
                                                                        {{ $checklist->is_done
                                                ? 'bg-emerald-100 text-emerald-700'
                                                : 'bg-amber-100 text-amber-700' }}">
                                                                        {{ $checklist->is_done ? 'Selesai' : 'Menunggu' }}
                                                                    </span>

                                                                </div>

                                                                @if(!$checklist->is_done && $checklist->uncheck_reason)
                                                                    <div
                                                                        class="mt-2 flex items-start gap-2 rounded-xl border border-red-100 bg-red-50 px-3 py-2">
                                                                        <span class="text-red-400 text-xs mt-0.5">⚠</span>

                                                                        <div>
                                                                            <p class="text-xs font-semibold text-red-600">
                                                                                Alasan manager:
                                                                            </p>

                                                                            <p class="text-xs text-red-500 mt-0.5 leading-relaxed">
                                                                                {{ $checklist->uncheck_reason }}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </label>

                                                        {{-- Button --}}
                                                        <div class="flex items-center gap-2">

                                                            <button
                                                                type="button"
                                                                onclick="event.stopPropagation(); openStaffChat('task', {{ $assignment->task_id }}, '{{ addslashes($assignment->task->title) }}')"
                                                                class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-800 transition">
                                                                Chat
                                                            </button>

                                                            <span
                                                                class="rounded-full px-3 py-1.5 text-xs font-semibold
                                                                {{ $checklist->is_done
                                                ? 'bg-slate-100 text-slate-600'
                                                : 'bg-indigo-100 text-indigo-700' }}">
                                                                {{ $checklist->is_done ? 'Batalkan' : 'Selesaikan' }}
                                                            </span>

                                                        </div>
                                                    </div>

                                                    {{-- Table File --}}
                                                    <div class="overflow-x-auto">

                                                        <table class="min-w-full">
                                                            <thead class="bg-slate-50 border-b border-slate-200">
                                                                <tr>
                                                                    <th class="px-4 py-3 text-left text-[11px] font-bold uppercase text-slate-500">
                                                                        Nama File
                                                                    </th>

                                                                    <th class="px-4 py-3 text-left text-[11px] font-bold uppercase text-slate-500">
                                                                        Preview
                                                                    </th>

                                                                    <th class="px-4 py-3 text-left text-[11px] font-bold uppercase text-slate-500">
                                                                        Aksi
                                                                    </th>
                                                                </tr>
                                                            </thead>

                                                            <tbody>
                                                                @if($checklist->file_path)
                                                                    <tr class="border-b border-slate-100">

                                                                        {{-- Nama File --}}
                                                                        <td class="px-4 py-4 align-top">
                                                                            <div class="max-w-xs">
                                                                                <p class="text-sm font-medium text-slate-700 break-all">
                                                                                    {{ $checklist->file_name ?? 'File Upload' }}
                                                                                </p>

                                                                                <p class="text-xs text-slate-400 mt-1">
                                                                                    {{ $checklist->file_type ?? 'Unknown File' }}
                                                                                </p>
                                                                            </div>
                                                                        </td>

                                                                        {{-- Preview --}}
                                                                        <td class="px-4 py-4 align-top">

                                                                            @if(str_starts_with($checklist->file_type ?? '', 'image/'))

                                                                                <a href="{{ Storage::url($checklist->file_path) }}"
                                                                                    target="_blank"
                                                                                    onclick="event.stopPropagation()"
                                                                                    class="block rounded-xl overflow-hidden border border-slate-200 w-20 h-20 hover:opacity-90 transition">

                                                                                    <img src="{{ Storage::url($checklist->file_path) }}"
                                                                                        alt="Preview"
                                                                                        class="w-full h-full object-cover">
                                                                                </a>

                                                                            @else
                                                                                <div
                                                                                    class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-700">

                                                                                    @if($checklist->file_type === 'application/pdf')
                                                                                        📄 PDF
                                                                                    @elseif(
                                                                                            in_array($checklist->file_type, [
                                                                                                'application/vnd.ms-excel',
                                                                                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                                                                                            ])
                                                                                        )
                                                                                            📊 Excel
                                                                                    @else
                                                                                        📎 File
                                                                                    @endif

                                                                                </div>
                                                                            @endif
                                                                        </td>

                                                                        {{-- Action --}}
                                                                        <td class="px-4 py-4 align-top">
                                                                            <div class="flex flex-wrap items-center gap-2">

                                                                                <a href="{{ Storage::url($checklist->file_path) }}"
                                                                                    target="_blank"
                                                                                    onclick="event.stopPropagation()"
                                                                                    class="inline-flex items-center rounded-xl bg-indigo-600 px-4 py-2 text-xs font-semibold text-white hover:bg-indigo-700 transition">
                                                                                    Lihat File
                                                                                </a>

                                                                                <button
                                                                                    type="button"
                                                                                    onclick="event.stopPropagation(); deleteChecklistFile({{ $checklist->id }})"
                                                                                    class="inline-flex items-center rounded-xl bg-red-100 px-4 py-2 text-xs font-semibold text-red-600 hover:bg-red-200 transition">
                                                                                    Hapus
                                                                                </button>

                                                                            </div>
                                                                        </td>

                                                                    </tr>
                                                                @else
                                                                    <tr>
                                                                        <td colspan="3" class="px-4 py-5">

                                                                            <div
                                                                                class="flex items-center justify-between rounded-2xl border border-dashed border-amber-200 bg-amber-50 px-4 py-3">

                                                                                <div>
                                                                                    <p class="text-sm font-semibold text-amber-700">
                                                                                        Belum ada bukti file
                                                                                    </p>

                                                                                    <p class="text-xs text-amber-600 mt-1">
                                                                                        Klik checklist untuk upload file bukti.
                                                                                    </p>
                                                                                </div>

                                                                                <span
                                                                                    class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                                                                    Pending
                                                                                </span>

                                                                            </div>

                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            </tbody>
                                                        </table>

                                                    </div>

                                                    {{-- Hidden Form --}}
                                                    <form id="uncheck-form-{{ $checklist->id }}"
                                                        method="POST"
                                                        action="{{ route('project.checklist.toggle', $checklist->id) }}"
                                                        class="hidden">
                                                        @csrf
                                                    </form>

                                                </div>
                                            @empty
                                                <p class="text-sm text-slate-500 text-center py-4">
                                                    Tidak ada checklist untuk task ini
                                                </p>
                                            @endforelse
                                        </div>

                                    </td>
                                </tr>
                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Mobile Card View -->
                                <div class="md:hidden">
                                    <!-- Search & Filter Bar -->
                                    <div class="p-4 border-b border-slate-200 space-y-3">
                                        <div class="relative">
                                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"></path>
                                            </svg>
                                            <input type="text" id="mobileSearchInput" placeholder="Cari checklist atau task..."
                                                class="w-full pl-9 pr-4 py-2.5 rounded-2xl border border-slate-200 bg-slate-50 text-sm text-slate-800 placeholder-slate-400 outline-none focus:border-indigo-400 focus:bg-white transition"
                                                oninput="filterMobileCards()" />
                                        </div>
                                        <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-hide">
                                            <span class="text-xs font-semibold text-slate-500 shrink-0">Filter:</span>
                                            <button onclick="setMobileFilter('all')" id="filter-all"
                                                class="mobile-filter-btn shrink-0 rounded-full px-3 py-1.5 text-xs font-semibold transition bg-indigo-600 text-white">
                                                Semua
                                            </button>
                                            <button onclick="setMobileFilter('pending')" id="filter-pending"
                                                class="mobile-filter-btn shrink-0 rounded-full px-3 py-1.5 text-xs font-semibold transition bg-slate-100 text-slate-600 hover:bg-slate-200">
                                                Belum Selesai
                                            </button>
                                            <button onclick="setMobileFilter('done')" id="filter-done"
                                                class="mobile-filter-btn shrink-0 rounded-full px-3 py-1.5 text-xs font-semibold transition bg-slate-100 text-slate-600 hover:bg-slate-200">
                                                Selesai
                                            </button>
                                        </div>
                                        <p id="mobileFilterCount" class="text-xs text-slate-400"></p>
                                    </div>

                                    <div id="mobileCardList" class="divide-y divide-slate-200">
                                        @foreach($assignments as $index => $assignment)
                                            <div class="mobile-card p-4" data-task-title="{{ strtolower($assignment->task->title) }}"
                                                data-task-id="{{ $assignment->task_id }}"
                                                data-checklist-titles="{{ strtolower($assignment->checklists->pluck('title')->join(' ')) }}"
                                                data-done-count="{{ $assignment->checklists->where('is_done', true)->count() }}"
                                                data-total-count="{{ $assignment->checklists->count() }}">

                                                <button onclick="toggleMobileDropdown('mobile-dropdown-{{ $index }}')" class="w-full text-left">
                                                    <div class="flex items-start justify-between gap-3 mb-3">
                                                        <div class="min-w-0 flex-1">
                                                            <h3 class="text-sm font-bold text-slate-900">{{ $assignment->task->title }}</h3>
                                                            <p class="text-xs text-slate-400 mt-0.5">#{{ $assignment->task_id }}</p>
                                                        </div>
                                                        <svg id="mobile-icon-{{ $index }}"
                                                            class="w-5 h-5 text-slate-400 transition-transform flex-shrink-0 mt-0.5" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                                        </svg>
                                                    </div>

                                                    @if($assignment->description)
                                                        <p class="text-xs text-slate-600 mb-3 line-clamp-2">{{ $assignment->description }}</p>
                                                    @endif

                                                    <div class="flex flex-col gap-1.5 mb-3">
                                                        <div class="flex justify-between items-center">
                                                            <span class="text-xs font-semibold text-slate-600">Progress</span>
                                                            <span
                                                                class="text-xs text-slate-500">{{ $assignment->checklists->where('is_done', true)->count() }}/{{ $assignment->checklists->count() }}</span>
                                                        </div>
                                                        <div class="w-full bg-slate-200 rounded-full h-1.5">
                                                            <div class="bg-emerald-500 h-1.5 rounded-full transition-all"
                                                                style="width: {{ $assignment->checklists->count() > 0 ? ($assignment->checklists->where('is_done', true)->count() / $assignment->checklists->count() * 100) : 0 }}%">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="flex flex-wrap gap-2">
                                                        <span class="rounded-full bg-indigo-100 px-2 py-1 text-xs font-semibold text-indigo-700">
                                                            {{ $assignment->checklists->count() }} Items
                                                        </span>
                                                        @if($assignment->checklists->where('is_done', true)->count() === $assignment->checklists->count() && $assignment->checklists->count() > 0)
                                                            <span class="rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-700">✓
                                                                Selesai</span>
                                                        @endif
                                                        @if($assignment->deadline)
                                                            <span class="rounded-full bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-600">
                                                                {{ \Carbon\Carbon::parse($assignment->deadline)->format('d M Y') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </button>

                                                <!-- Expandable Checklist Section -->
                                                <div id="mobile-dropdown-{{ $index }}" class="hidden mt-4 pt-4 border-t border-slate-200">
                                                    <p class="text-xs font-semibold text-slate-700 mb-3">Checklist Items:</p>
                                                    <div class="space-y-2">
                                                        @forelse($assignment->checklists as $checklist)
                                                            <div class="flex flex-row items-center gap-2">

                                                                <label
                                                                    class="flex flex-1 items-center gap-2 p-3 rounded-xl bg-slate-50 border border-slate-200 hover:border-indigo-200 hover:bg-indigo-50 transition cursor-pointer min-w-0"
                                                                    onclick="handleChecklistClick(event, {{ $checklist->id }}, {{ $checklist->is_done ? 'true' : 'false' }}, {{ $checklist->file_path ? 'true' : 'false' }}, {{ $checklist->uncheck_reason ? json_encode($checklist->uncheck_reason) : 'null' }})">

                                                                    <div class="h-4 w-4 rounded border-2 flex-shrink-0 flex items-center justify-center
                                                                                        {{ $checklist->is_done ? 'bg-indigo-600 border-indigo-600' : 'border-slate-300 bg-white' }}"
                                                                        id="checkbox-visual-{{ $checklist->id }}">
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
                                                                                    {{ $checklist->uncheck_reason }}
                                                                                </p>
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
                                                                                    onclick="event.stopPropagation(); deleteChecklistFile({{ $checklist->id }})"
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
                                                                                        {{ $checklist->is_done ? 'bg-slate-100 text-slate-600' : 'bg-indigo-100 text-indigo-700' }}">
                                                                        {{ $checklist->is_done ? 'Batal' : 'Selesai' }}
                                                                    </span>
                                                                </label>

                                                                <form id="uncheck-form-{{ $checklist->id }}" method="POST"
                                                                    action="{{ route('project.checklist.toggle', $checklist->id) }}" class="hidden">
                                                                    @csrf
                                                                </form>

                                                                <button type="button"
                                                                    onclick="event.stopPropagation(); openStaffChat('task', {{ $assignment->task_id }}, '{{ addslashes($assignment->task->title) }}')"
                                                                    class="inline-flex items-center justify-center rounded-xl bg-slate-900 w-9 h-9 text-white hover:bg-slate-800 transition flex-shrink-0"
                                                                    title="Lihat Chat Task">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                                                        </path>
                                                                    </svg>
                                                                </button>

                                                            </div>
                                                        @empty
                                                            <p class="text-xs text-slate-500 text-center py-3">Tidak ada checklist</p>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Empty state saat filter tidak ada hasil -->
                                    <div id="mobileEmptyFilter" class="hidden p-10 text-center">
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
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                    <p class="text-lg font-semibold text-slate-800">Tidak ada checklist untuk saat ini</p>
                    <p class="mt-3 text-sm text-slate-500">Manager belum mengirimkan assignment checklist ke akun kamu.</p>
                </div>
            @endif

            </div>

            {{-- ============================================================ --}}
            {{-- MODAL UPLOAD BUKTI CHECKLIST --}}
            {{-- ============================================================ --}}
            <div id="uploadModal"
                class="fixed inset-0 z-[60] hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4"
                onclick="closeUploadModal(event)">
                <div class="w-full max-w-md rounded-[28px] bg-white shadow-2xl border border-slate-200 overflow-hidden"
                    onclick="event.stopPropagation()">

                    {{-- Header --}}
                    <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-indigo-50 to-slate-50">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Upload Bukti Pengerjaan</h3>
                                <p id="uploadModalSubtitle" class="mt-0.5 text-xs text-slate-500 line-clamp-1">—</p>
                            </div>
                            <button onclick="closeUploadModal()"
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

                        {{-- Alasan penolakan jika ada --}}
                        <div id="uploadReasonArea" class="hidden">
                            <div class="rounded-xl bg-red-50 border border-red-200 px-4 py-3">
                                <div class="flex items-start gap-2">
                                    <span class="text-red-400 text-lg shrink-0 mt-0.5">⚠️</span>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-semibold text-red-600 mb-1">Alasan Penolakan Manager:</p>
                                        <p id="uploadReasonText" class="text-xs text-red-600 leading-relaxed break-words">—</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Pilihan sumber file --}}
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Pilih Sumber File</p>
                        <div class="grid grid-cols-3 gap-3">

                            {{-- Dari galeri / file manager --}}
                            <button type="button" onclick="triggerFileInput('gallery')"
                                class="upload-source-btn flex flex-col items-center gap-2 rounded-2xl border-2 border-slate-200 bg-slate-50 p-4 hover:border-indigo-400 hover:bg-indigo-50 transition active:scale-95">
                                <span class="text-2xl"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                    </svg>
                                </span>
                                <span class="text-xs font-semibold text-slate-600">Galeri</span>
                            </button>

                            {{-- Kamera langsung --}}
                            <button type="button" onclick="triggerFileInput('camera')"
                                class="upload-source-btn flex flex-col items-center gap-2 rounded-2xl border-2 border-slate-200 bg-slate-50 p-4 hover:border-indigo-400 hover:bg-indigo-50 transition active:scale-95">
                                <span class="text-2xl"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                                    </svg>
                                </span>
                                <span class="text-xs font-semibold text-slate-600">Kamera</span>
                            </button>

                            {{-- Dokumen (PDF / Excel) --}}
                            <button type="button" onclick="triggerFileInput('document')"
                                class="upload-source-btn flex flex-col items-center gap-2 rounded-2xl border-2 border-slate-200 bg-slate-50 p-4 hover:border-indigo-400 hover:bg-indigo-50 transition active:scale-95">
                                <span class="text-2xl"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                    </svg>
                                </span>
                                <span class="text-xs font-semibold text-slate-600">Dokumen</span>
                            </button>
                        </div>
{{-- ── LOCATION STATUS ── --}}
<div id="locationStatus" class="hidden rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-3">
    <div class="flex items-center gap-3">
        <div id="locationIcon" class="text-lg flex-shrink-0">📍</div>
        <div class="min-w-0 flex-1">
            <p id="locationText" class="text-xs font-semibold text-slate-700">Mengambil lokasi...</p>
            <p id="locationSub"  class="text-xs text-slate-400 mt-0.5">Mohon izinkan akses lokasi</p>
        </div>
        <div id="locationSpinner"
             class="w-4 h-4 rounded-full border-2 border-indigo-400 border-t-transparent animate-spin flex-shrink-0">
        </div>
    </div>
</div>
                        {{-- Hidden inputs untuk masing-masing sumber --}}
                        <input type="file" id="fileInputGallery" accept="image/*,application/pdf,.xls,.xlsx" class="hidden"
                            onchange="handleFileSelected(this)">
                        <input type="file" id="fileInputCamera" accept="image/*" capture="environment" class="hidden"
                            onchange="handleFileSelected(this)">
                        <input type="file" id="fileInputDocument"
                            accept="application/pdf,.xls,.xlsx,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                            class="hidden" onchange="handleFileSelected(this)">

                        {{-- Preview area --}}
                        <div id="uploadPreviewArea" class="hidden">
                            <div class="rounded-2xl border-2 border-indigo-200 bg-indigo-50 p-4">
                                <div class="flex items-center gap-3">
                                    <div id="uploadPreviewIcon" class="text-3xl flex-shrink-0">📎</div>
                                    <div class="min-w-0 flex-1">
                                        <p id="uploadPreviewName" class="text-sm font-semibold text-slate-800 truncate">—</p>
                                        <p id="uploadPreviewSize" class="text-xs text-slate-500 mt-0.5">—</p>
                                    </div>
                                    <button type="button" onclick="clearFileSelection()"
                                        class="rounded-full bg-white p-1.5 shadow-sm hover:bg-red-50 transition flex-shrink-0">
                                        <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>

                                {{-- Image preview --}}
                                <div id="imagePreviewWrapper" class="hidden mt-3">
                                    <img id="imagePreviewEl" src="" alt="Preview"
                                        class="w-full max-h-40 object-contain rounded-xl border border-slate-200">
                                </div>
                            </div>
                        </div>

                        {{-- Info tipe file yang didukung --}}
                        <p class="text-xs text-slate-400 text-center">
                            Mendukung: JPG, PNG, GIF, WEBP, PDF, XLS, XLSX · Maks 10 MB
                        </p>

                        {{-- Error message --}}
                        <div id="uploadErrorMsg" class="hidden rounded-xl bg-red-50 border border-red-200 px-4 py-3">
                            <p class="text-xs text-red-600 font-semibold" id="uploadErrorText">—</p>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex gap-3">
                        <button type="button" onclick="closeUploadModal()"
                            class="flex-1 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                            Batal
                        </button>
                        <button type="button" id="uploadSubmitBtn" onclick="submitChecklistFile()"
                            class="flex-1 rounded-2xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white hover:bg-indigo-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                            disabled>
                            <span id="uploadSubmitLabel">Upload & Selesaikan</span>
                            <span id="uploadSubmitSpinner" class="hidden">
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

            {{-- Chat Panel --}}
            <div id="staffChatPanel" class="fixed inset-0 z-50 hidden items-end justify-end bg-slate-900/40 p-4">
                <div
                    class="w-full max-w-2xl rounded-[32px] bg-white shadow-2xl border border-slate-200 overflow-hidden flex flex-col h-[80vh]">
                    <div class="flex items-center justify-between gap-4 border-b border-slate-200 px-5 py-4 bg-slate-50">
                        <div>
                            <h2 id="staffChatPanelTitle" class="text-lg font-semibold text-slate-900">Chat</h2>
                            <p id="staffChatPanelSubtitle" class="text-sm text-slate-500">Mulai chat untuk checklist atau global
                                chat.</p>
                        </div>
                        <button type="button" onclick="closeStaffChatPanel()"
                            class="rounded-3xl bg-slate-900 px-4 py-2 text-white text-xs font-semibold hover:bg-slate-800 transition">Tutup</button>
                    </div>
                    <div id="staffChatMessages" class="flex-1 overflow-y-auto px-5 py-5 space-y-4 bg-slate-50"></div>
                    <div class="border-t border-slate-200 bg-white px-5 py-4">
                        <div class="flex items-center gap-3">
                            <input id="staffChatInput" type="text" placeholder="Ketik pesan..."
                                class="flex-1 rounded-3xl border border-slate-200 px-4 py-3 outline-none focus:border-indigo-500" />
                            <button type="button" onclick="sendStaffChatMessage()"
                                class="rounded-3xl bg-indigo-600 px-5 py-3 text-white text-sm font-semibold hover:bg-indigo-700 transition">Kirim</button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Camera Modal --}}
            <div id="cameraModal" class="fixed inset-0 z-[70] hidden items-center justify-center bg-slate-900/80 p-4">
                <div class="w-full max-w-lg rounded-[28px] bg-slate-900 overflow-hidden shadow-2xl border border-slate-700">

                    {{-- Header --}}
                    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-700">
                        <h3 class="text-sm font-bold text-white">Ambil Foto Bukti</h3>
                        <button onclick="closeCameraModal()" class="rounded-full bg-slate-700 p-2 hover:bg-slate-600 transition">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                                </path>
                            </svg>
                        </button>
                    </div>

                    {{-- Viewfinder --}}
                    <div class="relative bg-black" style="aspect-ratio: 16/9;">
                        <video id="cameraVideo" autoplay playsinline muted class="w-full h-full object-cover"></video>

                        {{-- Crosshair overlay --}}
                        <div class="absolute inset-0 pointer-events-none flex items-center justify-center">
                            <div class="border-2 border-white/40 rounded-xl w-2/3 h-2/3"></div>
                        </div>
                    </div>

                    {{-- Hidden canvas for capture --}}
                    <canvas id="cameraCanvas" class="hidden"></canvas>

                    {{-- Shutter Button --}}
                    <div class="flex items-center justify-center py-6 gap-6 bg-slate-900">
                        <button onclick="closeCameraModal()"
                            class="text-xs font-semibold text-slate-400 hover:text-white transition px-4 py-2">
                            Batal
                        </button>
                        <button onclick="capturePhoto()"
                            class="w-16 h-16 rounded-full bg-white border-4 border-slate-400 hover:bg-slate-100 active:scale-95 transition flex items-center justify-center shadow-lg">
                            <div class="w-12 h-12 rounded-full bg-white border-2 border-slate-300"></div>
                        </button>
                        <div class="w-16"></div>{{-- spacer --}}
                    </div>
                </div>
            </div>
           <script>
// ============================================================
// STATE
// ============================================================
let uploadChecklistId    = null;
let uploadChecklistTitle = '';
let selectedFile         = null;
let cameraStream         = null;
let currentLatitude      = null;
let currentLongitude     = null;
let currentAddress       = null;
let locationWatchDone    = false;

// ============================================================
// GEOLOCATION
// ============================================================
function startLocationWatch() {
    currentLatitude   = null;
    currentLongitude  = null;
    currentAddress    = null;
    locationWatchDone = false;

    const statusEl  = document.getElementById('locationStatus');
    const iconEl    = document.getElementById('locationIcon');
    const textEl    = document.getElementById('locationText');
    const subEl     = document.getElementById('locationSub');
    const spinnerEl = document.getElementById('locationSpinner');

    if (statusEl) {
        statusEl.className = 'rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-3';
        statusEl.classList.remove('hidden');
        iconEl.textContent = '📍';
        textEl.textContent = 'Mengambil lokasi...';
        subEl.textContent  = 'Mohon izinkan akses lokasi';
        spinnerEl.classList.remove('hidden');
    }

    if (!navigator.geolocation) {
        locationWatchDone = true;
        if (statusEl) {
            iconEl.textContent = '⚠️';
            textEl.textContent = 'Lokasi tidak tersedia';
            subEl.textContent  = 'Browser tidak mendukung geolocation';
            spinnerEl.classList.add('hidden');
            statusEl.className = 'rounded-xl border border-amber-200 bg-amber-50 px-4 py-3';
        }
        return;
    }

    navigator.geolocation.getCurrentPosition(
        async function(pos) {
            currentLatitude  = pos.coords.latitude;
            currentLongitude = pos.coords.longitude;
            locationWatchDone = true;

            // Reverse geocode via Nominatim
            try {
                const res  = await fetch(
                    `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${currentLatitude}&lon=${currentLongitude}`,
                    { headers: { 'Accept-Language': 'id' } }
                );
                const data = await res.json();
                currentAddress = data.display_name ?? null;
            } catch(_) { currentAddress = null; }

            if (statusEl) {
                iconEl.textContent = '✅';
                textEl.textContent = currentAddress ?? `${currentLatitude.toFixed(5)}, ${currentLongitude.toFixed(5)}`;
                subEl.textContent  = 'Akurasi ±' + Math.round(pos.coords.accuracy) + 'm';
                spinnerEl.classList.add('hidden');
                statusEl.className = 'rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3';
            }
        },
        function(err) {
            locationWatchDone = true;
            if (statusEl) {
                iconEl.textContent = '❌';
                textEl.textContent = 'Gagal mendapatkan lokasi';
                subEl.textContent  = err.code === 1 ? 'Akses lokasi ditolak' : 'Periksa GPS Anda';
                spinnerEl.classList.add('hidden');
                statusEl.className = 'rounded-xl border border-red-200 bg-red-50 px-4 py-3';
            }
        },
        { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
    );
}

// ============================================================
// STAMP IMAGE (timestamp + lokasi)
// ============================================================
function stampImage(file) {
    return new Promise(function(resolve) {
        if (!file.type.startsWith('image/')) { resolve(file); return; }

        const img = new Image();
        const url = URL.createObjectURL(file);

        img.onload = function() {
            const canvas    = document.createElement('canvas');
            canvas.width    = img.naturalWidth;
            canvas.height   = img.naturalHeight;
            const ctx       = canvas.getContext('2d');

            ctx.drawImage(img, 0, 0);
            URL.revokeObjectURL(url);

            const now     = new Date();
            const dateStr = now.toLocaleDateString('id-ID', {
                weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
            });
            const timeStr = now.toLocaleTimeString('id-ID', {
                hour: '2-digit', minute: '2-digit', second: '2-digit'
            });
            const locStr  = currentAddress ?? 'Lokasi tidak tersedia';
            const coordStr = (currentLatitude && currentLongitude)
                ? currentLatitude.toFixed(5) + ', ' + currentLongitude.toFixed(5)
                : '';

            const lines    = [dateStr, timeStr, locStr, coordStr].filter(Boolean);
            const fontSize = Math.max(20, Math.round(canvas.width * 0.022));
            const padding  = Math.round(fontSize * 0.7);
            const lineH    = Math.round(fontSize * 1.55);
            const boxH     = padding * 2 + lineH * lines.length;
            const boxY     = canvas.height - boxH;

            ctx.fillStyle = 'rgba(0,0,0,0.55)';
            ctx.fillRect(0, boxY, canvas.width, boxH);

            ctx.font         = 'bold ' + fontSize + 'px Arial, sans-serif';
            ctx.fillStyle    = '#ffffff';
            ctx.shadowColor  = 'rgba(0,0,0,0.8)';
            ctx.shadowBlur   = 4;
            ctx.textBaseline = 'top';

            lines.forEach(function(line, i) {
                ctx.fillText(line, padding, boxY + padding + i * lineH);
            });

            canvas.toBlob(function(blob) {
                resolve(new File([blob], file.name, {
                    type: 'image/jpeg', lastModified: Date.now()
                }));
            }, 'image/jpeg', 0.92);
        };

        img.onerror = function() { resolve(file); };
        img.src = url;
    });
}

// ============================================================
// PROCESS + PREVIEW FILE
// ============================================================
async function processAndPreviewFile(file) {
    hideUploadError();
    const btn   = document.getElementById('uploadSubmitBtn');
    const label = document.getElementById('uploadSubmitLabel');
    btn.disabled      = true;
    label.textContent = 'Memproses...';

    const stamped = await stampImage(file);
    selectedFile  = stamped;

    label.textContent = 'Upload & Selesaikan';
    showFilePreview(stamped);
    btn.disabled = false;
}

// ============================================================
// CHECKLIST CLICK HANDLER
// ============================================================
function handleChecklistClick(event, checklistId, isDone, hasFile, uncheckReason = null) {
    event.preventDefault();
    event.stopPropagation();

    if (isDone) {
        document.getElementById('uncheck-form-' + checklistId).submit();
        return;
    }

    openUploadModal(
        checklistId,
        event.currentTarget.querySelector('p.font-semibold')?.textContent?.trim() || '',
        uncheckReason
    );
}

// ============================================================
// UPLOAD MODAL
// ============================================================
function openUploadModal(checklistId, title, uncheckReason = null) {
    uploadChecklistId    = checklistId;
    uploadChecklistTitle = title;
    selectedFile         = null;
    currentLatitude      = null;
    currentLongitude     = null;
    currentAddress       = null;

    document.getElementById('uploadModalSubtitle').textContent = title || 'Checklist item';
    document.getElementById('uploadPreviewArea').classList.add('hidden');
    document.getElementById('uploadErrorMsg').classList.add('hidden');
    document.getElementById('uploadSubmitBtn').disabled = true;
    document.getElementById('uploadSubmitLabel').textContent = 'Upload & Selesaikan';
    document.getElementById('uploadSubmitLabel').classList.remove('hidden');
    document.getElementById('uploadSubmitSpinner').classList.add('hidden');

    // Alasan penolakan
    const reasonArea = document.getElementById('uploadReasonArea');
    if (uncheckReason) {
        reasonArea.classList.remove('hidden');
        document.getElementById('uploadReasonText').textContent = uncheckReason;
    } else {
        reasonArea.classList.add('hidden');
    }

    ['fileInputGallery','fileInputCamera','fileInputDocument'].forEach(function(id) {
        document.getElementById(id).value = '';
    });

    // Mulai ambil lokasi
    startLocationWatch();

    const modal = document.getElementById('uploadModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeUploadModal(event) {
    if (event && event.target !== document.getElementById('uploadModal')) return;
    document.getElementById('uploadModal').classList.add('hidden');
    document.getElementById('uploadModal').classList.remove('flex');
    uploadChecklistId = null;
    selectedFile      = null;
}

// ============================================================
// FILE INPUT
// ============================================================
function triggerFileInput(source) {
    if (source === 'camera') { openCameraModal(); return; }
    const map = { gallery: 'fileInputGallery', document: 'fileInputDocument' };
    document.getElementById(map[source]).click();
}

async function handleFileSelected(input) {
    const file = input.files[0];
    if (!file) return;

    if (file.size > 10 * 1024 * 1024) {
        showUploadError('Ukuran file melebihi 10 MB.');
        input.value = '';
        return;
    }

    const allowed    = ['image/jpeg','image/png','image/gif','image/webp',
                        'application/pdf','application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
    const ext        = file.name.split('.').pop().toLowerCase();
    const allowedExt = ['jpg','jpeg','png','gif','webp','pdf','xls','xlsx'];

    if (!allowed.includes(file.type) && !allowedExt.includes(ext)) {
        showUploadError('Tipe file tidak didukung.');
        input.value = '';
        return;
    }

    await processAndPreviewFile(file);
}

// ============================================================
// CAMERA MODAL
// ============================================================
async function openCameraModal() {
    const modal = document.getElementById('cameraModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    try {
        cameraStream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: { ideal: 'environment' }, width: { ideal: 1280 }, height: { ideal: 720 } }
        });
        document.getElementById('cameraVideo').srcObject = cameraStream;
    } catch(err) {
        alert('Tidak dapat mengakses kamera: ' + err.message);
        closeCameraModal();
    }
}

function closeCameraModal() {
    if (cameraStream) {
        cameraStream.getTracks().forEach(function(t) { t.stop(); });
        cameraStream = null;
    }
    document.getElementById('cameraModal').classList.add('hidden');
    document.getElementById('cameraModal').classList.remove('flex');
}

function capturePhoto() {
    const video  = document.getElementById('cameraVideo');
    const canvas = document.getElementById('cameraCanvas');
    canvas.width  = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);
    canvas.toBlob(function(blob) {
        const file = new File([blob], 'foto-bukti.jpg', { type: 'image/jpeg' });
        closeCameraModal();
        processAndPreviewFile(file);
    }, 'image/jpeg', 0.92);
}

// ============================================================
// FILE PREVIEW HELPERS
// ============================================================
function showFilePreview(file) {
    const area    = document.getElementById('uploadPreviewArea');
    const nameEl  = document.getElementById('uploadPreviewName');
    const sizeEl  = document.getElementById('uploadPreviewSize');
    const iconEl  = document.getElementById('uploadPreviewIcon');
    const imgWrap = document.getElementById('imagePreviewWrapper');
    const imgEl   = document.getElementById('imagePreviewEl');

    nameEl.textContent = file.name;
    sizeEl.textContent = formatBytes(file.size);

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

function clearFileSelection() {
    selectedFile = null;
    ['fileInputGallery','fileInputCamera','fileInputDocument'].forEach(function(id) {
        document.getElementById(id).value = '';
    });
    document.getElementById('uploadPreviewArea').classList.add('hidden');
    document.getElementById('uploadSubmitBtn').disabled = true;
}

function showUploadError(msg) {
    document.getElementById('uploadErrorText').textContent = msg;
    document.getElementById('uploadErrorMsg').classList.remove('hidden');
}

function hideUploadError() {
    document.getElementById('uploadErrorMsg').classList.add('hidden');
}

function formatBytes(bytes) {
    if (bytes < 1024)    return bytes + ' B';
    if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
    return (bytes / 1048576).toFixed(1) + ' MB';
}

// ============================================================
// SUBMIT UPLOAD
// ============================================================
function submitChecklistFile() {
    if (!selectedFile || !uploadChecklistId) return;

    const btn     = document.getElementById('uploadSubmitBtn');
    const label   = document.getElementById('uploadSubmitLabel');
    const spinner = document.getElementById('uploadSubmitSpinner');

    btn.disabled = true;
    label.classList.add('hidden');
    spinner.classList.remove('hidden');
    hideUploadError();

    const formData = new FormData();
    formData.append('file',    selectedFile);
    formData.append('_token',  document.querySelector('meta[name="csrf-token"]').content);
    if (currentLatitude  !== null) formData.append('latitude',  currentLatitude);
    if (currentLongitude !== null) formData.append('longitude', currentLongitude);
    if (currentAddress   !== null) formData.append('address',   currentAddress);

    fetch(`/project/checklist/${uploadChecklistId}/upload-file`, {
        method: 'POST',
        body  : formData,
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (!data.success) {
            showUploadError(data.message || 'Gagal mengupload file.');
            btn.disabled = false;
            label.classList.remove('hidden');
            spinner.classList.add('hidden');
            return;
        }
        document.getElementById('uploadModal').classList.add('hidden');
        document.getElementById('uploadModal').classList.remove('flex');
        window.location.reload();
    })
    .catch(function() {
        showUploadError('Terjadi kesalahan. Silakan coba lagi.');
        btn.disabled = false;
        label.classList.remove('hidden');
        spinner.classList.add('hidden');
    });
}

// ============================================================
// DELETE FILE
// ============================================================
function deleteChecklistFile(checklistId) {
    if (!confirm('Hapus file bukti ini? Status checklist akan kembali ke belum selesai.')) return;
    fetch(`/project/checklist/${checklistId}/delete-file`, {
        method : 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.success) window.location.reload();
        else alert(data.message || 'Gagal menghapus file.');
    })
    .catch(function() { alert('Terjadi kesalahan.'); });
}

// ============================================================
// DROPDOWN & MOBILE FILTER (tidak berubah)
// ============================================================
function toggleDropdown(id) {
    document.getElementById(id).classList.toggle('hidden');
}

function toggleMobileDropdown(id) {
    const element = document.getElementById(id);
    const iconId  = id.replace('mobile-dropdown-', 'mobile-icon-');
    const icon    = document.getElementById(iconId);
    element.classList.toggle('hidden');
    icon.classList.toggle('rotate-180');
}

let staffChatRoomType = null;
let staffChatRoomId   = null;

function openStaffChat(roomType, roomId = null, title = 'Chat') {
    staffChatRoomType = roomType;
    staffChatRoomId   = roomId;
    document.getElementById('staffChatPanelTitle').innerText    = title;
    document.getElementById('staffChatPanelSubtitle').innerText = roomType === 'global' ? 'Global staff chat' : 'Chat tugas';
    document.getElementById('staffChatPanel').classList.remove('hidden');
    document.getElementById('staffChatPanel').classList.add('flex');
    document.getElementById('staffChatInput').value = '';
    clearStaffChatReply();
    loadStaffChatMessages();
}

function closeStaffChatPanel() {
    document.getElementById('staffChatPanel').classList.add('hidden');
}

function clearStaffChatReply() {
    const preview = document.getElementById('staffChatReplyPreview');
    if (preview) preview.classList.add('hidden');
    const text = document.getElementById('staffChatReplyText');
    if (text) text.innerText = '';
}

function loadStaffChatMessages() {
    const chatBody = document.getElementById('staffChatMessages');
    chatBody.innerHTML = '<div class="text-sm text-slate-500">Memuat percakapan...</div>';
    let url = '/chat/room/global';
    if (staffChatRoomType === 'task') url = `/chat/room/task/${staffChatRoomId}`;
    fetch(url)
        .then(function(r) { return r.json(); })
        .then(function(data) {
            chatBody.innerHTML = '';
            const messages = data.messages || [];
            if (!messages.length) {
                chatBody.innerHTML = '<div class="text-sm text-slate-500">Belum ada pesan.</div>';
                return;
            }
            messages.forEach(function(m) { appendStaffChatBubble(m); });
            chatBody.scrollTop = chatBody.scrollHeight;
        })
        .catch(function() {
            chatBody.innerHTML = '<div class="text-sm text-red-500">Gagal memuat percakapan.</div>';
        });
}

function appendStaffChatBubble(message) {
    const chatBody = document.getElementById('staffChatMessages');
    const isOwn    = message.from_user_id === {{ auth()->id() }};
    const container = document.createElement('div');
    container.className = `flex ${isOwn ? 'justify-end' : 'justify-start'}`;
    const bubble = document.createElement('div');
    bubble.className = `rounded-3xl p-4 shadow-sm max-w-[80%] ${isOwn ? 'bg-indigo-600 text-white' : 'bg-white text-slate-800 border border-slate-200'}`;
    if (message.reply_to) {
        const reply = document.createElement('div');
        reply.className = 'mb-3 rounded-3xl bg-slate-100 px-3 py-2 text-xs text-slate-600';
        reply.textContent = message.reply_to.body || 'Balasan';
        bubble.appendChild(reply);
    }
    const text = document.createElement('div');
    text.className   = 'text-sm';
    text.textContent = message.body;
    bubble.appendChild(text);
    const meta = document.createElement('div');
    meta.className   = 'mt-2 text-[11px] text-slate-400';
    meta.textContent = `${message.from_user?.name || 'Pengguna'} • ${new Date(message.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}`;
    bubble.appendChild(meta);
    container.appendChild(bubble);
    chatBody.appendChild(container);
}

function sendStaffChatMessage() {
    const input = document.getElementById('staffChatInput');
    const body  = input.value.trim();
    if (!body) return;
    const payload = { body, room_type: staffChatRoomType, reply_to_id: null };
    if (staffChatRoomType === 'task') payload.task_id = staffChatRoomId;
    fetch('/chat/message', {
        method : 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body   : JSON.stringify(payload),
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (!data.success) { alert(data.message || 'Gagal mengirim pesan.'); return; }
        input.value = '';
        clearStaffChatReply();
        loadStaffChatMessages();
    })
    .catch(function() { alert('Gagal mengirim pesan.'); });
}

// Mobile search & filter
let mobileActiveFilter = 'all';

function setMobileFilter(filter) {
    mobileActiveFilter = filter;
    document.querySelectorAll('.mobile-filter-btn').forEach(function(btn) {
        btn.classList.remove('bg-indigo-600', 'text-white');
        btn.classList.add('bg-slate-100', 'text-slate-600');
    });
    const activeBtn = document.getElementById('filter-' + filter);
    if (activeBtn) {
        activeBtn.classList.remove('bg-slate-100', 'text-slate-600');
        activeBtn.classList.add('bg-indigo-600', 'text-white');
    }
    filterMobileCards();
}

function filterMobileCards() {
    const query = document.getElementById('mobileSearchInput').value.toLowerCase().trim();
    const cards = document.querySelectorAll('.mobile-card');
    let visible = 0;
    cards.forEach(function(card) {
        const taskTitle       = card.dataset.taskTitle || '';
        const checklistTitles = card.dataset.checklistTitles || '';
        const doneCount       = parseInt(card.dataset.doneCount)  || 0;
        const totalCount      = parseInt(card.dataset.totalCount) || 0;
        const matchSearch     = !query || taskTitle.includes(query) || checklistTitles.includes(query);
        let matchFilter       = true;
        if      (mobileActiveFilter === 'done')    matchFilter = totalCount > 0 && doneCount === totalCount;
        else if (mobileActiveFilter === 'pending') matchFilter = doneCount < totalCount;
        if (matchSearch && matchFilter) { card.classList.remove('hidden'); visible++; }
        else                            { card.classList.add('hidden'); }
    });
    const countEl = document.getElementById('mobileFilterCount');
    if (query || mobileActiveFilter !== 'all') {
        countEl.textContent = `Menampilkan ${visible} dari ${cards.length} assignment`;
    } else {
        countEl.textContent = '';
    }
    document.getElementById('mobileEmptyFilter').classList.toggle('hidden', visible > 0);
}
</script>

@endsection