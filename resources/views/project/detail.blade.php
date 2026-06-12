@extends('layouts.app')

@section('content')

    <div class="max-w-7xl mx-auto px-4 md:px-6 py-8 space-y-8">

        {{-- HEADER --}}
        <div class="relative overflow-hidden rounded-[32px] border border-slate-200 bg-white shadow-sm">

            <div class="absolute top-0 right-0 w-72 h-72 bg-indigo-50 rounded-full blur-3xl opacity-60"></div>
            <div class="absolute bottom-0 left-0 w-60 h-60 bg-purple-50 rounded-full blur-3xl opacity-60"></div>

            <div class="relative z-10 p-7">

                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

                    <div class="flex items-center gap-5">

                        <a href="{{ url()->previous() }}"
                            class="w-14 h-14 rounded-3xl bg-slate-100 hover:bg-slate-200 transition flex items-center justify-center text-xl font-bold shadow-sm">
                            ←
                        </a>

                        <div>
                            <h1 class="text-3xl md:text-4xl font-black tracking-tight text-slate-800">
                                {{ $task->title }}
                            </h1>

                            <p class="text-slate-500 mt-2">
                                Task Detail & Member Assignment
                            </p>
                        </div>

                    </div>

                    <div class="flex flex-wrap gap-3">

                        @php
                            $statusColor = match ($task->status) {
                                'progress' => 'bg-blue-100 text-blue-700 border border-blue-200',
                                'done' => 'bg-green-100 text-green-700 border border-green-200',
                                default => 'bg-yellow-100 text-yellow-700 border border-yellow-200'
                            };

                            $priorityColor = match ($task->priority) {
                                'high' => 'bg-red-100 text-red-700 border border-red-200',
                                'medium' => 'bg-yellow-100 text-yellow-700 border border-yellow-200',
                                default => 'bg-green-100 text-green-700 border border-green-200'
                            };
                        @endphp

                        <span class="{{ $priorityColor }} px-4 py-2 rounded-2xl text-sm font-semibold shadow-sm">
                            {{ ucfirst($task->priority) }}
                        </span>

                        <span class="{{ $statusColor }} px-4 py-2 rounded-2xl text-sm font-semibold shadow-sm">
                            {{ ucfirst($task->status) }}
                        </span>


                        <button type="button" onclick="openGroupChat({{ $task->id }}, '{{ addslashes($task->title) }}')"
                            class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-semibold transition shadow-lg shadow-indigo-100">
                            Chat Semua
                        </button>

                    </div>

                </div>

            </div>

        </div>

        {{-- TABS --}}
        <div class="mt-8 bg-white border border-slate-200 rounded-[32px] p-4 shadow-sm">
            <div class="flex flex-wrap gap-3">
                <button type="button" onclick="switchTab('overview')" id="tab-button-overview"
                    class="tab-button px-4 py-2 rounded-2xl bg-indigo-600 text-white font-semibold shadow-sm">
                    Overview
                </button>
                <button type="button" onclick="switchTab('supervisor')" id="tab-button-supervisor"
                    class="tab-button px-4 py-2 rounded-2xl bg-slate-100 text-slate-700 font-semibold hover:bg-slate-200 transition">
                    Team
                </button>
                @unless(in_array(auth()->user()->role, ['direksi', 'manager']))
                    <button type="button" onclick="switchTab('addtask')" id="tab-button-addtask"
                        class="tab-button px-4 py-2 rounded-2xl bg-slate-100 text-slate-700 font-semibold hover:bg-slate-200 transition">
                        Add Member
                    </button>
                @endunless
                <button type="button" onclick="switchTab('assignments')" id="tab-button-assignments"
                    class="tab-button px-4 py-2 rounded-2xl bg-slate-100 text-slate-700 font-semibold hover:bg-slate-200 transition">
                    Assignments
                </button>

            </div>
        </div>

        <div id="tab-overview" class="tab-content mt-6">
            {{-- INFO --}}
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">

                <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs text-slate-400 font-medium mb-1">Task</p>
                        <p class="font-semibold text-slate-800 text-sm leading-snug truncate">{{ $task->title }}</p>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium mb-1">Members</p>
                        <p class="font-semibold text-slate-800 text-2xl leading-none">{{ $task->users->count() }}</p>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs text-slate-400 font-medium mb-1">Description</p>
                        <p class="text-sm text-slate-600 leading-relaxed line-clamp-2">{{ $task->description ?? '-' }}</p>
                    </div>
                </div>



            </div>


            {{-- TABLE FOR DRAFTS --}}
            {{-- TABLE FOR DRAFTS --}}
            <div class="bg-white border border-slate-200 rounded-[32px] overflow-hidden shadow-sm mt-6">

                {{-- Header --}}
                <div
                    class="flex flex-col gap-4 px-8 py-6 border-b border-slate-200 sm:flex-row sm:items-center sm:justify-between bg-slate-50/50">
                    <div>
                        <h2 class="text-2xl font-black text-slate-800">Task Drafts</h2>
                        <p class="text-sm text-slate-500 mt-1">Semua draft task beserta status assignment.</p>
                    </div>
                    <div
                        class="flex flex-col sm:flex-row sm:items-center gap-4 bg-white border border-slate-200 rounded-3xl px-5 py-4 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 flex items-center justify-center rounded-2xl bg-indigo-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-wide text-slate-400 font-semibold">Total Draft</p>
                                <h3 class="text-lg font-bold text-slate-800">{{ $allDrafts->count() }} Drafts</h3>
                            </div>
                        </div>
                        @unless(in_array(auth()->user()->role, ['direksi', 'manager']))
                            <button type="button" onclick="showTaskAssignForm()"
                                class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold transition-all duration-200 shadow-lg shadow-indigo-100 hover:scale-[1.02]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Create Task Draft
                            </button>
                        @endunless
                    </div>
                </div>

                @if($allDrafts->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200">
                                    <th class="px-8 py-5 text-xs font-bold text-slate-500 uppercase tracking-wider">Description
                                    </th>
                                    <th class="px-8 py-5 text-xs font-bold text-slate-500 uppercase tracking-wider">Deadline
                                    </th>
                                    <th class="px-8 py-5 text-xs font-bold text-slate-500 uppercase tracking-wider">Notes</th>
                                    <th class="px-8 py-5 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                                    <th class="px-8 py-5 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allDrafts as $draft)
                                    <tr class="border-b border-slate-100 hover:bg-slate-50/70 transition cursor-pointer"
                                        onclick="toggleDraftChecklist({{ $draft->id }})">

                                        {{-- Description --}}
                                        <td class="px-8 py-6">
                                            <p class="font-bold text-slate-800 text-sm leading-relaxed">
                                                {{ $draft->description ?: 'No Description' }}
                                            </p>
                                        </td>

                                        {{-- Deadline --}}
                                        <td class="px-8 py-6">
                                            @if($draft->deadline)
                                                @php
                                                    $deadline = \Carbon\Carbon::parse($draft->deadline);
                                                    $now = now();
                                                    $diffSeconds = $now->diffInSeconds($deadline, false);
                                                    $isExpired = $diffSeconds < 0;
                                                    $diffSeconds = abs($diffSeconds);
                                                    $days = floor($diffSeconds / 86400);
                                                    $hours = floor(($diffSeconds % 86400) / 3600);
                                                    $minutes = floor(($diffSeconds % 3600) / 60);
                                                @endphp
                                                <div class="flex flex-col gap-2">
                                                    <span
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold border w-fit
                                                            {{ $isExpired ? 'bg-red-50 text-red-600 border-red-200' : 'bg-slate-100 text-slate-600 border-slate-200' }}">
                                                        📅 {{ $deadline->format('d M Y H:i') }}
                                                    </span>
                                                    @if($isExpired)
                                                        <span
                                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-red-100 text-red-700 border border-red-200 text-xs font-bold w-fit">
                                                            ⛔ Deadline Terlewat
                                                        </span>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold w-fit">
                                                            ⏳ {{ $days }}h {{ $hours }}j {{ $minutes }}m lagi
                                                        </span>
                                                    @endif
                                                </div>
                                            @else
                                                <span
                                                    class="px-3 py-1.5 bg-slate-100 rounded-xl text-xs font-semibold text-slate-500 border border-slate-200">
                                                    No Deadline
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Notes --}}
                                        <td class="px-8 py-6">
                                            <p class="text-sm text-slate-500 max-w-[200px] truncate">
                                                {{ $draft->notes ?: '—' }}
                                            </p>
                                        </td>

                                        {{-- Status --}}
                                        <td class="px-8 py-6">
                                            @if($draft->user_id)
                                                <div class="flex items-center gap-2.5">
                                                    <img src="https://i.pravatar.cc/100?u={{ $draft->user_id }}"
                                                        class="w-8 h-8 rounded-xl border border-slate-200 shrink-0">
                                                    <div>
                                                        <p class="text-xs font-semibold text-slate-700 leading-tight">
                                                            {{ $draft->user->name ?? '-' }}</p>
                                                        <p class="text-[11px] text-emerald-600 font-medium mt-0.5">Assigned</p>
                                                    </div>
                                                </div>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-yellow-50 text-yellow-700 border border-yellow-200 rounded-xl text-xs font-semibold">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-400"></span>
                                                    Unassigned
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Actions --}}
                                        <td class="px-8 py-6 text-right">
                                            <div class="flex items-center justify-end gap-2" onclick="event.stopPropagation()">
                                                @unless(in_array(auth()->user()->role, ['direksi', 'manager']))
                                                    <div class="relative group">
                                                        <button type="button"
                                                            onclick="event.stopPropagation(); openEditDataModal('draft', { id: {{ $draft->id }}, userId: @js($draft->user_id ?? ''), description: @js($draft->description ?? ''), deadline: @js($draft->deadline ? \Carbon\Carbon::parse($draft->deadline)->format('Y-m-d') : ''), notes: @js($draft->notes ?? '') })"
                                                            class="flex h-10 w-10 items-center justify-center rounded-2xl border border-slate-200 bg-slate-100 text-slate-700 hover:bg-indigo-600 hover:text-white transition">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.75 20.25H3v-3.75L16.862 4.487Z" />
                                                            </svg>
                                                        </button>
                                                        <div class="absolute -top-11 left-1/2 z-20 -translate-x-1/2 whitespace-nowrap rounded-xl bg-slate-900 px-3 py-1.5 text-[11px] font-medium text-white opacity-0 shadow-lg transition-all duration-200 group-hover:opacity-100">
                                                            Edit Data
                                                        </div>
                                                    </div>
                                                @endunless
                                                <button type="button"
                                                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold text-xs transition">
                                                    Checklist ({{ $draft->checklists->count() }})
                                                </button>
                                                @if(auth()->user()->role != 'direksi')
                                                    <button type="button" onclick="deleteDraft({{ $draft->id }})"
                                                        class="px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 rounded-xl font-semibold text-xs transition">
                                                        🗑 Hapus
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- DROPDOWN CHECKLIST ROW --}}
                                    <tr id="draft-checklist-{{ $draft->id }}" class="hidden bg-slate-50/40">
                                        <td colspan="5" class="px-8 py-6 border-b border-slate-100">
                                            <div class="rounded-2xl border border-slate-200 overflow-hidden">
                                                <table class="min-w-full text-sm">
                                                    <thead class="bg-slate-100">
                                                        <tr>
                                                            <th
                                                                class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">
                                                                Nama Checklist</th>
                                                            <th
                                                                class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">
                                                                File Bukti</th>
                                                            <th
                                                                class="px-6 py-4 text-center text-xs font-bold text-slate-600 uppercase tracking-wider">
                                                                Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-slate-100 bg-white">
                                                        @foreach($draft->checklists as $checklist)
                                                            <tr class="hover:bg-slate-50 transition">

                                                                {{-- Nama --}}
                                                                <td class="px-6 py-5">
                                                                    <p class="font-semibold text-slate-800 text-sm leading-relaxed">
                                                                        {{ $checklist->title }}
                                                                    </p>
                                                                </td>

                                                                {{-- File --}}
                                                                <td class="px-6 py-5">
                                                                    @if($checklist->file_path)
                                                                        @if(str_starts_with($checklist->file_type ?? '', 'image/'))
                                                                            <a href="{{ Storage::url($checklist->file_path) }}" target="_blank"
                                                                                class="inline-flex items-center gap-2.5 px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 transition">
                                                                                <img src="{{ Storage::url($checklist->file_path) }}"
                                                                                    class="w-9 h-9 rounded-lg object-cover border border-slate-200">
                                                                                <div>
                                                                                    <p class="text-xs font-semibold text-slate-700">Lihat Gambar
                                                                                    </p>
                                                                                    <p class="text-[11px] text-slate-400">
                                                                                        {{ Str::limit($checklist->file_name ?? 'image', 18) }}
                                                                                    </p>
                                                                                </div>
                                                                            </a>
                                                                        @else
                                                                            <a href="{{ Storage::url($checklist->file_path) }}" target="_blank"
                                                                                class="inline-flex items-center gap-2.5 px-3 py-2 rounded-xl bg-slate-100 border border-slate-200 hover:bg-slate-200 transition">
                                                                                <span class="text-base">
                                                                                    @if($checklist->file_type === 'application/pdf') 📄
                                                                                    @elseif(str_contains($checklist->file_type ?? '', 'sheet'))
                                                                                        📊
                                                                                    @else 📎
                                                                                    @endif
                                                                                </span>
                                                                                <div>
                                                                                    <p class="text-xs font-semibold text-slate-700">Lihat File
                                                                                    </p>
                                                                                    <p class="text-[11px] text-slate-400">
                                                                                        {{ Str::limit($checklist->file_name ?? 'file', 18) }}
                                                                                    </p>
                                                                                </div>
                                                                            </a>
                                                                        @endif
                                                                    @else
                                                                        <span
                                                                            class="inline-flex items-center px-3 py-1.5 rounded-lg bg-amber-50 text-amber-600 border border-amber-200 text-xs font-medium">
                                                                            ⚠ Belum ada file
                                                                        </span>
                                                                    @endif
                                                                </td>

                                                                {{-- Action --}}
                                                                <td class="px-6 py-5">
                                                                    <div class="flex items-center justify-center gap-2">

                                                                        {{-- Assign button --}}
                                                                        @if(!$checklist->assignment || !$checklist->assignment->user_id)
                                                                            @if(!$checklist->assigned_user_id)
                                                                                <div class="relative group">
                                                                                    @unless(in_array(auth()->user()->role, ['direksi', 'manager']))
                                                                                        <button type="button"
                                                                                            onclick="openAssignChecklistModal({{ $checklist->id }}, '{{ addslashes($checklist->title) }}', '{{ $draft->deadline ?? '' }}', '{{ addslashes($draft->description ?? '') }}', '{{ addslashes($draft->notes ?? '') }}')"
                                                                                            class="flex h-10 w-10 items-center justify-center rounded-2xl border border-slate-200 bg-slate-100 text-slate-700 hover:bg-slate-800 hover:text-white transition">
                                                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                                                viewBox="0 0 24 24" stroke-width="1.8"
                                                                                                stroke="currentColor" class="h-5 w-5">
                                                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                                                    d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                                                                                            </svg>
                                                                                        </button>
                                                                                    @endunless
                                                                                    <div
                                                                                        class="absolute -top-11 left-1/2 z-20 -translate-x-1/2 whitespace-nowrap rounded-xl bg-slate-900 px-3 py-1.5 text-[11px] font-medium text-white opacity-0 shadow-lg transition-all duration-200 group-hover:opacity-100">
                                                                                        Assign Checklist
                                                                                    </div>
                                                                                </div>
                                                                            @else
                                                                                <div class="relative group">
                                                                                    <div
                                                                                        class="flex h-10 w-10 items-center justify-center rounded-2xl border border-emerald-200 bg-emerald-50 text-emerald-600">
                                                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                                            viewBox="0 0 24 24" stroke-width="2"
                                                                                            stroke="currentColor" class="h-5 w-5">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                                                d="M4.5 12.75l6 6 9-13.5" />
                                                                                        </svg>
                                                                                    </div>
                                                                                    <div
                                                                                        class="absolute -top-11 left-1/2 z-20 -translate-x-1/2 whitespace-nowrap rounded-xl bg-emerald-600 px-3 py-1.5 text-[11px] font-medium text-white opacity-0 shadow-lg transition-all duration-200 group-hover:opacity-100">
                                                                                        Sudah Assigned
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                        @else
                                                                            <div class="relative group">
                                                                                <div
                                                                                    class="flex h-10 w-10 items-center justify-center rounded-2xl border border-emerald-200 bg-emerald-50 text-emerald-600">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                                        viewBox="0 0 24 24" stroke-width="2"
                                                                                        stroke="currentColor" class="h-5 w-5">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                                            d="M4.5 12.75l6 6 9-13.5" />
                                                                                    </svg>
                                                                                </div>
                                                                                <div
                                                                                    class="absolute -top-11 left-1/2 z-20 -translate-x-1/2 whitespace-nowrap rounded-xl bg-emerald-600 px-3 py-1.5 text-[11px] font-medium text-white opacity-0 shadow-lg transition-all duration-200 group-hover:opacity-100">
                                                                                    Sudah Assigned
                                                                                </div>
                                                                            </div>
                                                                        @endif

                                                                        {{-- Delete --}}
                                                                        <div class="relative group">
                                                                            @unless(in_array(auth()->user()->role, ['direksi', 'manager']))
                                                                                <button type="button"
                                                                                    onclick="event.stopPropagation(); openEditDataModal('checklist', { id: {{ $checklist->id }}, title: @js($checklist->title) })"
                                                                                    class="flex h-10 w-10 items-center justify-center rounded-2xl border border-indigo-100 bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-5 w-5">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.75 20.25H3v-3.75L16.862 4.487Z" />
                                                                                    </svg>
                                                                                </button>
                                                                            @endunless
                                                                            <div
                                                                                class="absolute -top-11 left-1/2 z-20 -translate-x-1/2 whitespace-nowrap rounded-xl bg-indigo-600 px-3 py-1.5 text-[11px] font-medium text-white opacity-0 shadow-lg transition-all duration-200 group-hover:opacity-100">
                                                                                Edit Nama Checklist
                                                                            </div>

                                                                        </div>

                                                                        <div class="relative group">
                                                                            @unless(in_array(auth()->user()->role, ['direksi', 'manager']))
                                                                                <button type="button"
                                                                                    onclick="deleteChecklist({{ $checklist->id }})"
                                                                                    class="flex h-10 w-10 items-center justify-center rounded-2xl border border-red-100 bg-red-50 text-red-500 hover:bg-red-600 hover:text-white transition">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                                        viewBox="0 0 24 24" stroke-width="1.8"
                                                                                        stroke="currentColor" class="h-5 w-5">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0V4.5A2.25 2.25 0 0 0 13.5 2.25h-3A2.25 2.25 0 0 0 8.25 4.5v1.397" />
                                                                                    </svg>
                                                                                </button>
                                                                            @endunless
                                                                                <div
                                                                                    class="absolute -top-11 left-1/2 z-20 -translate-x-1/2 whitespace-nowrap rounded-xl bg-red-600 px-3 py-1.5 text-[11px] font-medium text-white opacity-0 shadow-lg transition-all duration-200 group-hover:opacity-100">
                                                                                    Hapus Checklist
                                                                                </div>
                                                                        </div>

                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-16 text-slate-500">
                        <div class="text-5xl mb-4">📑</div>
                        <p class="font-semibold text-slate-600">Belum ada task draft.</p>
                        <p class="text-xs text-slate-400 mt-2">Gunakan tombol "Create Task Draft" di atas untuk menambahkan
                            draft.</p>
                    </div>
                @endif
            </div>

            {{-- STAFF - SUPERVISOR PANEL --}}
            <!-- <div class="bg-white border border-slate-200 rounded-[32px] overflow-hidden shadow-sm mt-6">

                {{-- Header --}}
                <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-black text-slate-800 text-xl">Daftar Staff & Supervisor</h3>
                        <p class="text-sm text-slate-400 mt-1">Seluruh anggota task beserta supervisor masing-masing</p>
                    </div>
                    <span
                        class="px-4 py-2 bg-indigo-50 text-indigo-700 border border-indigo-100 rounded-2xl text-sm font-semibold">
                        {{ $task->users->count() }} Staff
                    </span>
                </div>

                @if($task->users->count())
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-100">
                                    <th class="px-8 py-5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                        Staff</th>
                                    <th class="px-8 py-5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                        Supervisor</th>
                                    <th class="px-8 py-5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                        Status SPV</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($task->users as $user)
                                    @php $spv = optional($user->supervisor); @endphp
                                    <tr class="hover:bg-slate-50/70 transition">

                                        {{-- Staff --}}
                                        <td class="px-8 py-6">
                                            <div class="flex items-center gap-3.5">
                                                <img src="https://i.pravatar.cc/100?u={{ $user->id }}"
                                                    class="w-10 h-10 rounded-2xl object-cover border border-slate-200 shrink-0">
                                                <div>
                                                    <p class="font-semibold text-slate-800 text-sm">{{ $user->name }}</p>
                                                    <p class="text-xs text-slate-400 mt-0.5">{{ $user->email }}</p>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Role --}}
                                        {{-- Supervisor --}}
                                        <td class="px-8 py-6">
                                            @if($spv->name)
                                                <div class="flex items-center gap-3">
                                                    <img src="https://i.pravatar.cc/100?u={{ $user->supervisor->id }}"
                                                        class="w-10 h-10 rounded-2xl object-cover border border-indigo-100 shrink-0">
                                                    <div>
                                                        <p class="font-semibold text-slate-800 text-sm">{{ $spv->name }}</p>
                                                        <p class="text-xs text-slate-400 mt-0.5">{{ $spv->email ?? '-' }}</p>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-sm text-amber-500 font-medium italic">— Belum diassign</span>
                                            @endif
                                        </td>

                                        {{-- Status --}}
                                        <td class="px-8 py-6">
                                            @if($spv->name)
                                                <span
                                                    class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold">
                                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                                    Assigned
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-amber-50 text-amber-600 border border-amber-200 text-xs font-semibold">
                                                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                                                    Unassigned
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-16 text-slate-400">
                        <p class="text-4xl mb-3">👥</p>
                        <p class="text-sm font-semibold text-slate-500">Belum ada staff di task ini.</p>
                    </div>
                @endif
            </div> -->
        </div>

        <div id="tab-addtask" class="tab-content hidden mt-6">

            <div class="overflow-hidden rounded-[32px] border border-slate-200 bg-white shadow-sm">

                {{-- Header --}}
                <div
                    class="flex flex-col gap-4 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white px-6 py-6 md:flex-row md:items-center md:justify-between">

                    <div>
                        <h2 class="text-2xl font-black text-slate-800">
                            Add Member
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Tambahkan user ke detail task ini
                        </p>
                    </div>

                    <div
                        class="inline-flex items-center gap-2 rounded-2xl border border-indigo-100 bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-700">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                            stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                        </svg>

                        Pilih member yang ingin ditambahkan

                    </div>

                </div>

                <form action="{{ route('project.users.add', $task->id) }}" method="POST">

                    @csrf

                    @php
                        $availableUsers = $users->whereNotIn('id', $task->users->pluck('id'));
                    @endphp

                    @if($availableUsers->count())

                        <div class="overflow-x-auto">

                            <table class="min-w-full table-fixed">

                                <thead class="bg-slate-100 text-slate-700">

                                    <tr>

                                        <th class="w-[8%] px-6 py-4 text-center text-xs font-black uppercase tracking-wider">
                                            Assign
                                        </th>

                                        <th class="w-[42%] px-6 py-4 text-left text-xs font-black uppercase tracking-wider">
                                            User
                                        </th>

                                        <th class="w-[25%] px-6 py-4 text-left text-xs font-black uppercase tracking-wider">
                                            Role
                                        </th>

                                        <th class="w-[25%] px-6 py-4 text-left text-xs font-black uppercase tracking-wider">
                                            Status
                                        </th>

                                    </tr>

                                </thead>

                                <tbody class="divide-y divide-slate-100 bg-white">

                                    @foreach($availableUsers as $user)

                                        <tr class="group hover:bg-slate-50 transition">

                                            {{-- Checkbox --}}
                                            <td class="px-6 py-5 text-center">

                                                <input type="checkbox" name="user_ids[]" value="{{ $user->id }}"
                                                    class="h-5 w-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">

                                            </td>

                                            {{-- User --}}
                                            <td class="px-6 py-5">

                                                <div class="flex items-center gap-4">

                                                    <img src="https://i.pravatar.cc/100?u={{ $user->id }}" alt="{{ $user->name }}"
                                                        class="h-14 w-14 rounded-2xl border border-slate-200 object-cover shadow-sm">

                                                    <div class="min-w-0">

                                                        <p class="truncate text-sm font-bold text-slate-800">
                                                            {{ $user->name }}
                                                        </p>

                                                        <p class="mt-1 text-xs text-slate-500">
                                                            ID User #{{ $user->id }}
                                                        </p>

                                                    </div>

                                                </div>

                                            </td>

                                            {{-- Role --}}
                                            <td class="px-6 py-5">

                                                <div
                                                    class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-700">

                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                        stroke-width="1.8" stroke="currentColor" class="h-4 w-4 text-slate-500">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15.75 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                                    </svg>

                                                    {{ $user->role }}

                                                </div>

                                            </td>

                                            {{-- Status --}}
                                            <td class="px-6 py-5">

                                                <div
                                                    class="inline-flex items-center gap-2 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700">

                                                    <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>

                                                    Ready to assign

                                                </div>

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                        {{-- Footer --}}
                        <div
                            class="flex flex-col gap-4 border-t border-slate-100 bg-slate-50 px-6 py-5 md:flex-row md:items-center md:justify-between">

                            <div class="text-sm text-slate-500">
                                Pilih satu atau lebih user untuk ditambahkan ke task
                            </div>

                            <button type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-2xl bg-indigo-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-indigo-200 transition hover:bg-indigo-700">

                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                                    stroke="currentColor" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                                </svg>

                                Add Selected Member

                            </button>

                        </div>

                    @else

                        <div class="flex flex-col items-center justify-center px-6 py-20 text-center">

                            <div
                                class="mb-5 flex h-24 w-24 items-center justify-center rounded-full bg-yellow-50 text-yellow-500">

                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="h-12 w-12">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M18 18.72a9.094 9.094 0 0 0 3.742-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.204-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.75m12-6.75a4.5 4.5 0 0 0-4.5-4.5m4.5 4.5a4.5 4.5 0 0 1-4.5 4.5m4.5-4.5h-13.5m9 0a4.5 4.5 0 0 0-4.5-4.5m4.5 4.5a4.5 4.5 0 0 1-4.5 4.5" />
                                </svg>

                            </div>

                            <h3 class="text-lg font-black text-slate-800">
                                Semua User Sudah Ditambahkan
                            </h3>

                            <p class="mt-2 max-w-md text-sm leading-relaxed text-slate-500">
                                Tidak ada user lain yang tersedia untuk ditambahkan ke task ini.
                            </p>

                        </div>

                    @endif

                </form>

            </div>

        </div>

        <div id="tab-assignments" class="tab-content hidden mt-6">
            {{-- TABLE --}}
            <div class="bg-white border border-slate-200 rounded-[32px] overflow-hidden shadow-sm">
                <div
                    class="flex flex-col gap-4 px-6 py-5 border-b border-slate-200 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <div class="flex items-center gap-2">
                            <label for="assignTablePageSize" class="text-sm font-semibold text-slate-700">Show</label>
                            <select id="assignTablePageSize"
                                class="rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-700 focus:border-indigo-500 focus:outline-none">
                                <option value="5">5</option>
                                <option value="10" selected>10</option>
                                <option value="20">20</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <label for="assignTableSearch" class="text-sm font-semibold text-slate-700">Search</label>
                            <input id="assignTableSearch" type="search" placeholder="Search assignments..."
                                class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-700 focus:border-indigo-500 focus:bg-white focus:outline-none">
                        </div>
                    </div>
                    <div id="assignTableSummary" class="text-sm text-slate-500">Showing 0 of 0 records</div>
                </div>

                <table class="w-full" id="assignmentsTable">

                    <tbody>

                        @foreach($task->users as $user)

                            @php
                                $assignment = $assignments->get($user->id);
                                $userAssignments = $assignments->get($user->id);
                            @endphp

                            <tr data-record-id="user-{{ $user->id }}"
                                class="border-b border-slate-100 hover:bg-slate-50 transition cursor-pointer"
                                onclick="toggleTask({{ $user->id }})">

                                <td class="p-6">

                                    <div class="flex items-center gap-4">

                                        <div class="relative">

                                            <img src="https://i.pravatar.cc/100?u={{ $user->id }}"
                                                class="w-14 h-14 rounded-2xl object-cover ring-4 ring-slate-100">

                                            <div
                                                class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full">
                                            </div>

                                        </div>

                                        <div>
                                            <p class="font-black text-slate-800 text-lg">
                                                {{ $user->name }}
                                            </p>

                                            <p class="text-sm text-slate-400 mt-1">
                                                {{ $user->email }}
                                            </p>
                                        </div>

                                    </div>

                                </td>

                                <td class="p-6">

                                    <span class="px-4 py-2 bg-slate-100 rounded-2xl text-sm font-semibold text-slate-700">
                                        {{ $user->role }}
                                    </span>

                                </td>

                                <td class="p-6">

                                    <div class="flex flex-wrap gap-3">
                                        @unless(in_array(auth()->user()->role, ['direksi', 'manager']))
                                            <button
                                                onclick="event.stopPropagation(); showAssignForm({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                                class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-2xl font-semibold transition">
                                                Assign
                                            </button>
                                        @endunless
                                    </div>

                                </td>

                            </tr>

                            {{-- DROPDOWN --}}
                            <tr id="task-{{ $user->id }}" data-record-id="user-{{ $user->id }}" class="hidden bg-slate-50/40">
                                <td colspan="3" class="p-6">

                                    <div class="overflow-x-auto rounded-3xl border border-slate-200 bg-white shadow-sm">

                                        @if($userAssignments && $userAssignments->count())

                                            <table class="min-w-full table-fixed text-sm">

                                                <thead class="bg-slate-100 text-slate-700">
                                                    <tr>
                                                        <th class="w-[24%] px-5 py-4 text-left font-bold">
                                                            Task Name
                                                        </th>

                                                        <th class="w-[32%] px-5 py-4 text-left font-bold">
                                                            Checklist
                                                        </th>

                                                        <th class="w-[18%] px-5 py-4 text-left font-bold">
                                                            File Bukti
                                                        </th>

                                                        <th class="w-[12%] px-5 py-4 text-left font-bold">
                                                            Status
                                                        </th>
                                                        @unless(in_array(auth()->user()->role, ['direksi', 'manager']))
                                                            <th class="w-[14%] px-5 py-4 text-center font-bold">
                                                                Action
                                                            </th>
                                                        @endunless
                                                    </tr>
                                                </thead>

                                                <tbody class="divide-y divide-slate-100 bg-white">

                                                    @foreach($userAssignments as $assignment)

                                                        @if($assignment->checklists->count() > 0)

                                                            @foreach($assignment->checklists as $c)

                                                                <tr class="hover:bg-slate-50 transition align-top">

                                                                    {{-- Todoo --}}
                                                                    <td class="px-5 py-5">
                                                                        <div class="space-y-3">
                                                                            <div class="flex items-start justify-between gap-3">
                                                                                <p class="text-sm text-slate-600 leading-relaxed">
                                                                                    {{ $assignment->description ?? '-' }}
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </td>

                                                                    {{-- Checklist --}}
                                                                    <td class="px-5 py-5">
                                                                        <div class="flex items-start gap-3">

                                                                            <input type="checkbox"
                                                                                class="w-5 h-5 rounded border-slate-300 text-indigo-600 mt-0.5"
                                                                                disabled {{ $c->is_done ? 'checked' : '' }}>

                                                                            <div class="min-w-0">

                                                                                <div class="flex items-start justify-between gap-3">
                                                                                    <p
                                                                                        class="text-sm font-semibold leading-relaxed {{ $c->is_done ? 'text-slate-500 line-through' : 'text-slate-800' }}">
                                                                                        {{ $c->title }}
                                                                                    </p>
                                                                                </div>

                                                                                <p
                                                                                    class="text-xs mt-1 {{ $c->is_done ? 'text-emerald-600' : 'text-slate-500' }}">
                                                                                    {{ $c->is_done ? 'Completed' : 'Pending' }}
                                                                                </p>

                                                                                @if(!$c->is_done && $c->uncheck_reason)
                                                                                    <div
                                                                                        class="mt-3 rounded-2xl bg-red-50 border border-red-100 px-3 py-2">
                                                                                        <p class="text-[11px] font-semibold text-red-600">
                                                                                            Dibatalkan manager
                                                                                        </p>

                                                                                        <p class="text-[11px] text-red-500 mt-1 leading-relaxed">
                                                                                            {{ $c->uncheck_reason }}
                                                                                        </p>
                                                                                    </div>
                                                                                @endif

                                                                            </div>

                                                                        </div>
                                                                    </td>

                                                                    {{-- File --}}
                                                                    <td class="px-5 py-5">

                                                                        @if($c->file_path)

                                                                            @if(str_starts_with($c->file_type ?? '', 'image/'))

                                                                                <a href="{{ Storage::url($c->file_path) }}" target="_blank"
                                                                                    onclick="event.stopPropagation()"
                                                                                    class="flex items-center gap-3 max-w-xs rounded-2xl border border-slate-200 bg-slate-50 px-3 py-3 hover:bg-slate-100 transition">

                                                                                    <img src="{{ Storage::url($c->file_path) }}" alt="Bukti"
                                                                                        class="w-11 h-11 rounded-xl object-cover border border-slate-200">

                                                                                    <div class="min-w-0">
                                                                                        <p class="text-xs font-semibold text-slate-700">
                                                                                            Lihat Gambar
                                                                                        </p>

                                                                                        <p class="text-[11px] text-slate-400 truncate">
                                                                                            {{ Str::limit($c->file_name ?? 'image', 18) }}
                                                                                        </p>
                                                                                    </div>

                                                                                </a>

                                                                            @else

                                                                                <a href="{{ Storage::url($c->file_path) }}" target="_blank"
                                                                                    onclick="event.stopPropagation()"
                                                                                    class="flex items-center gap-3 max-w-xs rounded-2xl border border-slate-200 bg-slate-50 px-3 py-3 hover:bg-slate-100 transition">

                                                                                    <div
                                                                                        class="w-11 h-11 rounded-xl border border-slate-200 bg-white flex items-center justify-center text-lg shrink-0">

                                                                                        @if($c->file_type === 'application/pdf')
                                                                                            📄
                                                                                        @elseif(in_array($c->file_type ?? '', ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']))
                                                                                            📊
                                                                                        @else
                                                                                            📎
                                                                                        @endif

                                                                                    </div>

                                                                                    <div class="min-w-0">
                                                                                        <p class="text-xs font-semibold text-slate-700">
                                                                                            Lihat File
                                                                                        </p>

                                                                                        <p class="text-[11px] text-slate-400 truncate">
                                                                                            {{ Str::limit($c->file_name ?? 'file', 18) }}
                                                                                        </p>
                                                                                    </div>

                                                                                </a>

                                                                            @endif

                                                                        @else

                                                                            <span
                                                                                class="inline-flex items-center rounded-xl border border-amber-200 bg-amber-50 px-3 py-2 text-xs font-medium text-amber-600">
                                                                                ⚠ Belum ada bukti
                                                                            </span>

                                                                        @endif

                                                                    </td>

                                                                    {{-- Status --}}
                                                                    <td class="px-5 py-5">

                                                                        @if($c->file_path)
                                                                            <span
                                                                                class="inline-flex items-center rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-700">
                                                                                ✓ Ada bukti
                                                                            </span>
                                                                        @else
                                                                            <span
                                                                                class="inline-flex items-center rounded-xl border border-amber-200 bg-amber-50 px-3 py-2 text-xs font-semibold text-amber-600">
                                                                                Pending
                                                                            </span>
                                                                        @endif

                                                                    </td>

                                                                    {{-- Action --}}
                                                                    {{-- Action --}}
                                                                    @unless(in_array(auth()->user()->role, ['direksi', 'manager']))
                                                                        <td class="px-5 py-5">

                                                                            <div class="flex items-center justify-center gap-2">

                                                                                {{-- Edit Checklist --}}
                                                                                <div class="relative group">

                                                                                    <button type="button"
                                                                                        onclick="event.stopPropagation(); openEditDataModal('checklist', { id: {{ $c->id }}, title: @js($c->title) })"
                                                                                        class="flex h-10 w-10 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600 border border-indigo-100 hover:bg-indigo-600 hover:text-white transition">

                                                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                                            viewBox="0 0 24 24" stroke-width="1.8"
                                                                                            stroke="currentColor" class="h-5 w-5">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.75 20.25H3v-3.75L16.862 4.487Z" />
                                                                                        </svg>

                                                                                    </button>

                                                                                    {{-- Tooltip --}}
                                                                                    <div
                                                                                        class="absolute -top-11 left-1/2 z-20 -translate-x-1/2 whitespace-nowrap rounded-xl bg-slate-900 px-3 py-1.5 text-[11px] font-medium text-white opacity-0 shadow-lg transition-all duration-200 group-hover:opacity-100">
                                                                                        Edit Checklist
                                                                                    </div>

                                                                                </div>

                                                                                {{-- Reply --}}
                                                                                <div class="relative group">

                                                                                    <button type="button"
                                                                                        onclick="event.stopPropagation(); openChecklistReply({{ $c->id }}, '{{ addslashes($c->title) }}', {{ $task->id }}, '{{ addslashes($task->title) }}')"
                                                                                        class="flex h-10 w-10 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600 border border-indigo-100 hover:bg-indigo-600 hover:text-white transition">

                                                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                                            viewBox="0 0 24 24" stroke-width="1.8"
                                                                                            stroke="currentColor" class="h-5 w-5">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                                                d="M8.625 9.75a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H15.75M21 12c0 4.97-4.03 9-9 9a8.96 8.96 0 01-4.255-1.07L3 21l1.07-4.745A8.96 8.96 0 013 12c0-4.97 4.03-9 9-9s9 4.03 9 9z" />
                                                                                        </svg>

                                                                                    </button>

                                                                                    {{-- Tooltip --}}
                                                                                    <div
                                                                                        class="absolute -top-11 left-1/2 z-20 -translate-x-1/2 whitespace-nowrap rounded-xl bg-slate-900 px-3 py-1.5 text-[11px] font-medium text-white opacity-0 shadow-lg transition-all duration-200 group-hover:opacity-100">
                                                                                        Reply Checklist
                                                                                    </div>

                                                                                </div>

                                                                                {{-- Chat --}}
                                                                                <div class="relative group">

                                                                                    <button type="button"
                                                                                        onclick="event.stopPropagation(); openAssignmentChat({{ $assignment->id }}, '{{ addslashes($assignment->description ?? '-') }}', {{ $user->id }})"
                                                                                        class="flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-100 text-slate-700 border border-slate-200 hover:bg-slate-800 hover:text-white transition">

                                                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                                            viewBox="0 0 24 24" stroke-width="1.8"
                                                                                            stroke="currentColor" class="h-5 w-5">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                                                d="M2.25 12.76c0 1.6.84 3.087 2.227 4.048a11.94 11.94 0 004.18 1.834l-.39 2.314a.75.75 0 001.108.79l4.401-2.2c.224.015.45.023.674.023 5.385 0 9.75-3.533 9.75-7.875S19.835 3.75 14.45 3.75 4.7 7.283 4.7 11.625c0 .39.036.774.105 1.135z" />
                                                                                        </svg>

                                                                                    </button>

                                                                                    {{-- Tooltip --}}
                                                                                    <div
                                                                                        class="absolute -top-11 left-1/2 z-20 -translate-x-1/2 whitespace-nowrap rounded-xl bg-slate-900 px-3 py-1.5 text-[11px] font-medium text-white opacity-0 shadow-lg transition-all duration-200 group-hover:opacity-100">
                                                                                        Open Chat
                                                                                    </div>

                                                                                </div>

                                                                                {{-- Uncheck --}}
                                                                                @if($c->is_done)

                                                                                    <div class="relative group">

                                                                                        <button type="button"
                                                                                            onclick="event.stopPropagation(); openUncheckModal({{ $c->id }}, '{{ addslashes($c->title) }}')"
                                                                                            class="flex h-10 w-10 items-center justify-center rounded-2xl bg-red-50 text-red-600 border border-red-100 hover:bg-red-600 hover:text-white transition">

                                                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                                                viewBox="0 0 24 24" stroke-width="2"
                                                                                                stroke="currentColor" class="h-5 w-5">
                                                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                                                    d="M6 18L18 6M6 6l12 12" />
                                                                                            </svg>

                                                                                        </button>

                                                                                        {{-- Tooltip --}}
                                                                                        <div
                                                                                            class="absolute -top-11 left-1/2 z-20 -translate-x-1/2 whitespace-nowrap rounded-xl bg-red-600 px-3 py-1.5 text-[11px] font-medium text-white opacity-0 shadow-lg transition-all duration-200 group-hover:opacity-100">
                                                                                            Batalkan Checklist
                                                                                        </div>

                                                                                    </div>

                                                                                @endif

                                                                                {{-- Unassign --}}
                                                                                <div class="relative group">

                                                                                    <button type="button"
                                                                                        onclick="event.stopPropagation(); unassignChecklist({{ $c->id }}, '{{ addslashes($c->title) }}')"
                                                                                        class="flex h-10 w-10 items-center justify-center rounded-2xl bg-orange-50 text-orange-600 border border-orange-100 hover:bg-orange-500 hover:text-white transition">

                                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                                                            stroke="currentColor" stroke-width="2"
                                                                                            stroke-linecap="round" stroke-linejoin="round"
                                                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                                                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                                            <path d="M4 7l16 0" />
                                                                                            <path d="M10 11l0 6" />
                                                                                            <path d="M14 11l0 6" />
                                                                                            <path
                                                                                                d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                                                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                                                        </svg>
                                                                                    </button>

                                                                                    {{-- Tooltip --}}
                                                                                    <div
                                                                                        class="absolute -top-11 left-1/2 z-20 -translate-x-1/2 whitespace-nowrap rounded-xl bg-orange-500 px-3 py-1.5 text-[11px] font-medium text-white opacity-0 shadow-lg transition-all duration-200 group-hover:opacity-100">
                                                                                        Delete Task
                                                                                    </div>

                                                                                </div>

                                                                            </div>

                                                                        </td>
                                                                    @endunless
                                                                </tr>

                                                            @endforeach

                                                        @else

                                                            <tr>
                                                                <td colspan="5" class="px-5 py-10 text-center">

                                                                    <div class="flex flex-col items-center justify-center">
                                                                        <div class="text-5xl mb-4">
                                                                            📭
                                                                        </div>

                                                                        <p class="text-slate-400 font-medium">
                                                                            Todoo List kosong
                                                                        </p>
                                                                    </div>

                                                                </td>
                                                            </tr>

                                                        @endif

                                                    @endforeach

                                                </tbody>

                                            </table>

                                        @else

                                            <div class="py-14 text-center">

                                                <div class="text-5xl mb-4">
                                                    📭
                                                </div>

                                                <p class="text-slate-400 font-medium">
                                                    Belum ada assignment
                                                </p>

                                            </div>

                                        @endif

                                    </div>

                                </td>
                            </tr>

                        @endforeach

                    </tbody>

                </table>

                <div class="flex flex-col gap-3 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                    <div class="text-sm text-slate-600" id="assignTablePager"></div>
                    <div class="flex items-center gap-2">
                        <button type="button" id="assignTablePrev"
                            class="rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 disabled:opacity-40"
                            disabled>Previous</button>
                        <button type="button" id="assignTableNext"
                            class="rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 disabled:opacity-40"
                            disabled>Next</button>
                    </div>
                </div>

            </div>
        </div>

<div id="tab-supervisor" class="tab-content hidden mt-6">

    {{-- STAFF - SUPERVISOR PANEL --}}
    <div class="bg-white border border-slate-200 rounded-[32px] overflow-hidden shadow-sm">

        {{-- Header --}}
        <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-black text-slate-800 text-xl">Daftar Staff & Supervisor</h3>
                <p class="text-sm text-slate-400 mt-1">Seluruh anggota task beserta supervisor masing-masing</p>
            </div>
            <span class="px-4 py-2 bg-indigo-50 text-indigo-700 border border-indigo-100 rounded-2xl text-sm font-semibold">
                {{ $task->users->count() }} Staff
            </span>
        </div>

        @if($task->users->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-8 py-5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Staff</th>
                            <th class="px-8 py-5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Supervisor</th>
                            <th class="px-8 py-5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status SPV</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($task->users as $user)
                            @php $spv = optional($user->supervisor); @endphp
                            <tr class="hover:bg-slate-50/70 transition">

                                {{-- Staff --}}
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-3.5">
                                        <img src="https://i.pravatar.cc/100?u={{ $user->id }}"
                                            class="w-10 h-10 rounded-2xl object-cover border border-slate-200 shrink-0">
                                        <div>
                                            <p class="font-semibold text-slate-800 text-sm">{{ $user->name }}</p>
                                            <p class="text-xs text-slate-400 mt-0.5">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Supervisor --}}
                                <td class="px-8 py-6">
                                    @if($spv->name)
                                        <div class="flex items-center gap-3">
                                            <img src="https://i.pravatar.cc/100?u={{ $user->supervisor->id }}"
                                                class="w-10 h-10 rounded-2xl object-cover border border-indigo-100 shrink-0">
                                            <div>
                                                <p class="font-semibold text-slate-800 text-sm">{{ $spv->name }}</p>
                                                <p class="text-xs text-slate-400 mt-0.5">{{ $spv->email ?? '-' }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-sm text-amber-500 font-medium italic">— Belum diassign</span>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td class="px-8 py-6">
                                    @if($spv->name)
                                        <span class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                            Assigned
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-amber-50 text-amber-600 border border-amber-200 text-xs font-semibold">
                                            <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                                            Unassigned
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-16 text-slate-400">
                <p class="text-4xl mb-3">👥</p>
                <p class="text-sm font-semibold text-slate-500">Belum ada staff di task ini.</p>
            </div>
        @endif
    </div>

</div>
        {{-- ASSIGNMENT MODAL (HIDDEN - For modal-style popups if needed) --}}
        <div id="assignFormModalWrapper" onclick="hideAssignForm()"
            class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/50 p-4">
            <div onclick="event.stopPropagation()"
                class="w-full max-w-2xl overflow-hidden rounded-[32px] bg-white shadow-2xl border border-slate-200">
                <div class="flex items-center justify-between gap-4 border-b border-slate-200 px-6 py-5">
                    <div>
                        <p class="text-sm uppercase tracking-[0.2em] text-slate-400">Assignment</p>
                        <h2 id="assignModalTitle" class="text-2xl font-black text-slate-900">Assign User</h2>
                    </div>
                    <button type="button" onclick="hideAssignForm()"
                        class="text-slate-500 hover:text-slate-900 text-2xl leading-none">✕</button>
                </div>

                <div class="p-6">
                    <form id="saveAssignmentForm" action="{{ route('project.save-assignment') }}" method="POST">
                        @csrf

                        <input type="hidden" name="task_id" value="{{ $task->id }}">

                        <div class="grid gap-5">
                            <div>
                                <label class="text-sm font-semibold text-slate-700">Assign to User</label>
                                <select id="assignUserSelect" name="user_id"
                                    class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100">
                                    <option value="">Assign later</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role }})</option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-slate-400 mt-2">Kosongkan jika ingin membuat task detail dulu tanpa
                                    langsung assign.</p>
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-slate-700">Description</label>
                                <textarea name="description"
                                    class="mt-2 h-36 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-4 text-sm text-slate-800 outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100"
                                    placeholder="Write assignment details..."></textarea>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="text-sm font-semibold text-slate-700">Deadline</label>
                                    <input type="date" name="deadline"
                                        class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100">
                                </div>
                                <div>
                                    <label class="text-sm font-semibold text-slate-700">Checklist</label>
                                    <div id="checklistContainer" class="mt-2 space-y-3"></div>
                                    <button type="button" onclick="addTodolist()"
                                        class="mt-3 inline-flex items-center justify-center gap-2 rounded-3xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white hover:bg-indigo-700 transition">
                                        + Add checklist item
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label class="text-sm font-semibold text-slate-700">Notes</label>
                                <textarea name="notes"
                                    class="mt-2 h-28 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-4 text-sm text-slate-800 outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100"
                                    placeholder="Leave a note..."></textarea>
                            </div>
                        </div>

                        <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-end">
                            <button type="button" onclick="hideAssignForm()"
                                class="rounded-3xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">Cancel</button>
                            <button type="submit"
                                class="rounded-3xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700 transition">Save
                                Assignment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="projectEditModalWrapper" onclick="hideProjectEditModal()"
            class="fixed inset-0 z-[55] hidden flex items-center justify-center bg-slate-900/50 p-4">
            <div onclick="event.stopPropagation()"
                class="w-full max-w-2xl overflow-hidden rounded-[32px] bg-white shadow-2xl border border-slate-200">
                <div class="flex items-center justify-between gap-4 border-b border-slate-200 px-6 py-5">
                    <div>
                        <p class="text-sm uppercase tracking-[0.2em] text-slate-400">Project</p>
                        <h2 class="text-2xl font-black text-slate-900">Edit Project</h2>
                    </div>
                    <button type="button" onclick="hideProjectEditModal()"
                        class="text-slate-500 hover:text-slate-900 text-2xl leading-none">✕</button>
                </div>

                <div class="p-6">
                    <form id="projectEditForm" class="space-y-5">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" id="projectEditId" value="{{ $task->id }}">

                        <div>
                            <label class="text-sm font-semibold text-slate-700">Title</label>
                            <input id="projectEditTitle" name="title" type="text"
                                class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100">
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-slate-700">Description</label>
                            <textarea id="projectEditDescription" name="description" rows="4"
                                class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-4 text-sm text-slate-800 outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100"></textarea>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="text-sm font-semibold text-slate-700">Priority</label>
                                <select id="projectEditPriority" name="priority"
                                    class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100">
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-slate-700">Status</label>
                                <select id="projectEditStatus" name="status"
                                    class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100">
                                    <option value="pending">Pending</option>
                                    <option value="progress">Progress</option>
                                    <option value="done">Done</option>
                                    <option value="reject">Reject</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex gap-3 pt-2">
                            <button type="button" onclick="hideProjectEditModal()"
                                class="flex-1 rounded-3xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                                Cancel
                            </button>
                            <button type="button" onclick="submitProjectEdit()"
                                class="flex-1 rounded-3xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700 transition">
                                Save Project
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="draftEditModalWrapper" onclick="hideDraftEditModal()"
            class="fixed inset-0 z-[55] hidden flex items-center justify-center bg-slate-900/50 p-4">
            <div onclick="event.stopPropagation()"
                class="w-full max-w-2xl overflow-hidden rounded-[32px] bg-white shadow-2xl border border-slate-200">
                <div class="flex items-center justify-between gap-4 border-b border-slate-200 px-6 py-5">
                    <div>
                        <p class="text-sm uppercase tracking-[0.2em] text-slate-400">Task Draft</p>
                        <h2 class="text-2xl font-black text-slate-900">Edit Task Draft</h2>
                    </div>
                    <button type="button" onclick="hideDraftEditModal()"
                        class="text-slate-500 hover:text-slate-900 text-2xl leading-none">✕</button>
                </div>

                <div class="p-6">
                    <form id="draftEditForm" class="space-y-5">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" id="draftEditId" value="">

                        <div>
                            <label class="text-sm font-semibold text-slate-700">Assign to Member</label>
                            <select id="draftEditUserId" name="user_id"
                                class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100">
                                <option value="">Unassigned</option>
                                @foreach($task->users as $member)
                                    <option value="{{ $member->id }}">{{ $member->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-slate-700">Description</label>
                            <textarea id="draftEditDescription" name="description" rows="4"
                                class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-4 text-sm text-slate-800 outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100"></textarea>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="text-sm font-semibold text-slate-700">Deadline</label>
                                <input id="draftEditDeadline" name="deadline" type="date"
                                    class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100">
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-slate-700">Notes</label>
                                <input id="draftEditNotes" name="notes" type="text"
                                    class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100">
                            </div>
                        </div>

                        <div class="flex gap-3 pt-2">
                            <button type="button" onclick="hideDraftEditModal()"
                                class="flex-1 rounded-3xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                                Cancel
                            </button>
                            <button type="button" onclick="submitDraftEdit()"
                                class="flex-1 rounded-3xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700 transition">
                                Save Task Draft
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="checklistEditModalWrapper" onclick="hideChecklistEditModal()"
            class="fixed inset-0 z-[55] hidden flex items-center justify-center bg-slate-900/50 p-4">
            <div onclick="event.stopPropagation()"
                class="w-full max-w-lg overflow-hidden rounded-[32px] bg-white shadow-2xl border border-slate-200">
                <div class="flex items-center justify-between gap-4 border-b border-slate-200 px-6 py-5">
                    <div>
                        <p class="text-sm uppercase tracking-[0.2em] text-slate-400">Checklist</p>
                        <h2 class="text-2xl font-black text-slate-900">Edit Checklist</h2>
                    </div>
                    <button type="button" onclick="hideChecklistEditModal()"
                        class="text-slate-500 hover:text-slate-900 text-2xl leading-none">✕</button>
                </div>

                <div class="p-6">
                    <form id="checklistEditForm" class="space-y-5">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" id="checklistEditId" value="">

                        <div>
                            <label class="text-sm font-semibold text-slate-700">Checklist Title</label>
                            <input id="checklistEditTitle" name="title" type="text"
                                class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100">
                        </div>

                        <div class="flex gap-3 pt-2">
                            <button type="button" onclick="hideChecklistEditModal()"
                                class="flex-1 rounded-3xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                                Cancel
                            </button>
                            <button type="button" onclick="submitChecklistEdit()"
                                class="flex-1 rounded-3xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700 transition">
                                Save Checklist
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @include('project.component.edit-data-modal', [
            'projectEditUsers' => $users,
            'draftEditUsers' => $task->users,
        ])

        <div id="chatPanel" class="fixed right-0 top-0 h-full z-50 hidden w-[420px] bg-white shadow-2xl flex flex-col">
            <div
                class="bg-gradient-to-r from-indigo-600 via-indigo-700 to-purple-700 p-4 text-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img id="chatPanelAvatar" src="" class="w-10 h-10 rounded-full border-2 border-white/30">
                    <div>
                        <h2 id="chatPanelTitle" class="text-lg font-black">Task Chat</h2>
                        <p id="chatPanelSub" class="text-indigo-100 text-sm mt-1">Team collaboration</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button onclick="openCameraPanel()"
                        class="w-10 h-10 rounded-2xl bg-white/10 hover:bg-white/20 transition">📷</button>
                    <button onclick="closeChatPanel()"
                        class="w-10 h-10 rounded-2xl bg-white/10 hover:bg-white/20 transition">✕</button>
                </div>
            </div>

            <div id="chatBodyPanel" class="flex-1 overflow-y-auto p-4 bg-slate-100 space-y-4 min-h-0">
                <!-- messages will be appended here -->
            </div>

            <div id="previewContainerPanel" class="hidden px-4 pt-3 bg-white border-t border-slate-100">
                <div id="previewWrapperPanel" class="flex gap-3 overflow-x-auto pb-2 max-h-[120px]"></div>
            </div>

            <!-- REPLY PREVIEW (shown above input when replying) -->
            <div id="replyPreviewPanel" class="hidden px-4 pt-3 bg-white border-t border-slate-100">
                <div id="replyPreviewContent" class="flex items-start gap-3">
                    <!-- filled by JS -->
                </div>
            </div>

            <div id="cameraContainerPanel" class="hidden px-4 pt-4 bg-white">
                <div class="relative rounded-3xl overflow-hidden bg-black">
                    <video id="cameraVideoPanel" autoplay playsinline class="w-full max-h-[320px] object-cover"></video>
                    <button onclick="capturePhotoPanel()"
                        class="absolute bottom-5 left-1/2 -translate-x-1/2 w-12 h-12 rounded-full bg-white border-4 border-indigo-600 shadow-xl"></button>
                    <button onclick="closeCameraPanel()"
                        class="absolute top-4 right-4 w-10 h-10 rounded-2xl bg-black/50 text-white">✕</button>
                </div>
            </div>

            <div class="p-3 border-t bg-white">
                <div class="flex items-center gap-3">
                    <label
                        class="w-12 h-12 rounded-2xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-xl transition cursor-pointer">
                        🖼️
                        <input id="imageInputPanel" type="file" accept="image/*" multiple class="hidden"
                            onchange="previewSelectedImagePanel(event)">
                    </label>

                    <input id="chatInputPanel" type="text" placeholder="Type message..."
                        class="flex-1 h-12 rounded-2xl border border-slate-200 px-4 outline-none">

                    <button onclick="sendMessagePanel()"
                        class="w-12 h-12 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white transition">➤</button>
                </div>
            </div>
        </div>
        <div id="assignChecklistModalWrapper" onclick="hideAssignChecklistModal()"
            class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/50 p-4">
            <div onclick="event.stopPropagation()"
                class="w-full max-w-xl overflow-hidden rounded-[32px] bg-white shadow-2xl border border-slate-200">

                <div class="flex items-center justify-between gap-4 border-b border-slate-200 px-6 py-5">
                    <div>
                        <p class="text-sm uppercase tracking-[0.2em] text-slate-400">Checklist Assignment</p>
                        <h2 class="text-2xl font-black text-slate-900">Assign Checklist</h2>
                    </div>
                    <button type="button" onclick="hideAssignChecklistModal()"
                        class="text-slate-500 hover:text-slate-900 text-2xl leading-none">✕</button>
                </div>

                <div class="p-6 space-y-5">
                    {{-- Checklist title preview --}}
                    <div class="bg-indigo-50 border border-indigo-100 rounded-2xl px-4 py-3">
                        <p class="text-xs text-indigo-400 font-semibold uppercase tracking-widest mb-1">Checklist Item</p>
                        <p id="assignChecklistTitle" class="font-bold text-indigo-800 text-sm"></p>
                    </div>

                    <input type="hidden" id="assignChecklistId">

                    {{-- Member --}}
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Assign to Member <span
                                class="text-red-500">*</span></label>
                        <select id="assignChecklistUserId"
                            class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100">
                            <option value="">Pilih member...</option>
                            @foreach($task->users as $member)
                                <option value="{{ $member->id }}">{{ $member->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Description</label>
                        <textarea id="assignChecklistDescription" rows="3"
                            class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-4 text-sm text-slate-800 outline-none focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100"
                            placeholder="Deskripsi assignment untuk member ini..."></textarea>
                    </div>

                    {{-- Deadline --}}
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Deadline</label>
                        <input type="date" id="assignChecklistDeadline"
                            class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100">
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Notes</label>
                        <textarea id="assignChecklistNotes" rows="2"
                            class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-4 text-sm text-slate-800 outline-none focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100"
                            placeholder="Catatan tambahan..."></textarea>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" onclick="hideAssignChecklistModal()"
                            class="flex-1 rounded-3xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                            Cancel
                        </button>
                        <button type="button" onclick="submitAssignChecklist()"
                            class="flex-1 rounded-3xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700 transition">
                            Assign Checklist
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Modal Alasan Uncheck --}}
        <div id="uncheckReasonModal" onclick="hideUncheckModal()"
            class="fixed inset-0 z-[60] hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
            <div onclick="event.stopPropagation()"
                class="w-full max-w-md overflow-hidden rounded-[32px] bg-white shadow-2xl border border-slate-200">

                <div
                    class="flex items-center justify-between gap-4 border-b border-slate-200 px-6 py-5 bg-gradient-to-r from-indigo-50 to-slate-50">
                    <div>
                        <p class="text-xs uppercase tracking-[0.2em] text-indigo-400 font-semibold">Konfirmasi</p>
                        <h2 class="text-xl font-black text-slate-900">Batalkan Checklist</h2>
                    </div>
                    <button onclick="hideUncheckModal()"
                        class="rounded-full bg-slate-100 p-2 hover:bg-slate-200 transition">
                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <div class="bg-indigo-50 border border-indigo-100 rounded-2xl px-4 py-3">
                        <p class="text-xs text-indigo-400 font-semibold uppercase tracking-widest mb-1">Checklist Item</p>
                        <p id="uncheckItemTitle" class="font-bold text-indigo-800 text-sm"></p>
                    </div>

                    <input type="hidden" id="uncheckChecklistId">

                    <div>
                        <label class="text-sm font-semibold text-slate-700">
                            Alasan pembatalan <span class="text-red-500">*</span>
                        </label>
                        <textarea id="uncheckReason" rows="4"
                            class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none focus:border-indigo-400 focus:bg-white focus:ring-2 focus:ring-indigo-100 transition resize-none"
                            placeholder="Jelaskan mengapa checklist ini perlu dibatalkan..."></textarea>
                        <div id="uncheckReasonError" class="hidden mt-1.5 text-xs text-red-500 font-semibold">
                            Alasan wajib diisi.
                        </div>
                    </div>

                    <div class="rounded-2xl bg-blue-50 border border-blue-200 px-4 py-3 flex items-start gap-3">
                        <span class="text-blue-400 text-lg shrink-0">⚠</span>
                        <p class="text-xs text-blue-700">File bukti yang sudah diupload staff akan ikut dihapus dan status
                            checklist akan kembali ke <strong>Pending</strong>.</p>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex gap-3">
                    <button onclick="hideUncheckModal()"
                        class="flex-1 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                        Batal
                    </button>
                    <button onclick="submitUncheck()"
                        class="flex-1 rounded-2xl bg-indigo-600 hover:bg-indigo-700 px-4 py-3 text-sm font-semibold text-white transition">
                        Ya, Batalkan & Hapus File
                    </button>
                </div>
            </div>
        </div>
        <script>
            const isDireksi = @json(auth()->user()->role === 'direksi');
            let currentChatUserId = null;
            let currentChatRoomType = null;
            let currentChatRoomId = null;
            let checklistIndex = 0;
            let currentAssignmentId = null;
            let selectedImagesPanel = [];
            let currentReply = null;
            const currentUserId = {{ auth()->id() }};
            const currentTaskId = {{ $task->id }};
            const tabStorageKey = `project-detail-tab-${currentTaskId}`;

            /* ─── Tab ───────────────────────────────────────────────── */
            function switchTab(tab) {
                sessionStorage.setItem(tabStorageKey, tab);

                ['overview', 'addtask', 'assignments', 'supervisor'].forEach(key => {
                    const s = document.getElementById('tab-' + key);
                    const b = document.getElementById('tab-button-' + key);
                    if (s) s.classList.toggle('hidden', key !== tab);
                    if (b) {
                        b.classList.toggle('bg-indigo-600', key === tab);
                        b.classList.toggle('text-white', key === tab);
                        b.classList.toggle('bg-slate-100', key !== tab);
                        b.classList.toggle('text-slate-700', key !== tab);
                    }
                });
            }

            function toggleDraftChecklist(id) {
                const row = document.getElementById('draft-checklist-' + id);
                if (!row) return;
                if (event && (event.target.closest('button') || event.target.closest('select'))) return;
                row.classList.toggle('hidden');
            }

            /* ─── Pagination ─────────────────────────────────────────── */
            window.addEventListener('DOMContentLoaded', () => {
                const savedTab = sessionStorage.getItem(tabStorageKey);
                const initialTab = ['overview', 'addtask', 'assignments', 'supervisor'].includes(savedTab)
                    ? savedTab
                    : 'overview';

                switchTab(initialTab);
                initAssignmentPagination();

                const saveForm = document.getElementById('saveAssignmentForm');
                if (saveForm) {
                    saveForm.addEventListener('submit', async (e) => {
                        e.preventDefault();
                        const formData = new FormData(saveForm);
                        try {
                            const response = await fetch(saveForm.action, {
                                method: 'POST',
                                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                                body: formData
                            });
                            const data = await response.json();
                            if (data.success) {
                                hideAssignForm();
                                gToast.success('Berhasil', data.message || 'Assignment berhasil disimpan.');
                                setTimeout(() => location.reload(), 1200);
                            } else {
                                gModal.alert(data.message || 'Gagal menyimpan assignment.', 'warning');
                            }
                        } catch (error) {
                            console.error(error);
                            gModal.alert('Terjadi kesalahan saat menyimpan.', 'warning');
                        }
                    });
                }
            });

            function initAssignmentPagination() {
                const wrapper = document.getElementById('tab-assignments');
                if (!wrapper) return;
                const table = wrapper.querySelector('#assignmentsTable');
                const searchInput = document.getElementById('assignTableSearch');
                const pageSizeSelect = document.getElementById('assignTablePageSize');
                const prevButton = document.getElementById('assignTablePrev');
                const nextButton = document.getElementById('assignTableNext');
                const pagerLabel = document.getElementById('assignTablePager');
                const summaryLabel = document.getElementById('assignTableSummary');

                const rows = Array.from(table.querySelectorAll('tbody tr'));
                const records = [];
                for (let i = 0; i < rows.length; i++) {
                    const row = rows[i];
                    const recordId = row.dataset.recordId;
                    if (!recordId) continue;
                    const nextRow = rows[i + 1];
                    const detailRow = nextRow && nextRow.dataset.recordId === recordId ? nextRow : null;
                    records.push({ recordId, userRow: row, detailRow });
                    if (detailRow) i += 1;
                }

                let currentPage = 1;
                let pageSize = parseInt(pageSizeSelect.value, 10) || 10;

                function getFilteredRecords() {
                    const query = searchInput.value.trim().toLowerCase();
                    if (!query) return records;
                    return records.filter(({ userRow, detailRow }) => {
                        const text = (userRow.textContent + (detailRow ? detailRow.textContent : '')).toLowerCase();
                        return text.includes(query);
                    });
                }

                function updateTable() {
                    const filteredRecords = getFilteredRecords();
                    const total = filteredRecords.length;
                    const totalPages = Math.max(1, Math.ceil(total / pageSize));
                    if (currentPage > totalPages) currentPage = totalPages;
                    const startIndex = (currentPage - 1) * pageSize;
                    const endIndex = startIndex + pageSize;

                    records.forEach(record => {
                        record.userRow.style.display = filteredRecords.includes(record) ? '' : 'none';
                        if (record.detailRow) record.detailRow.style.display = filteredRecords.includes(record) ? '' : 'none';
                    });

                    filteredRecords.forEach((record, index) => {
                        const inPage = index >= startIndex && index < endIndex;
                        record.userRow.style.display = inPage ? '' : 'none';
                        if (record.detailRow) record.detailRow.style.display = inPage ? '' : 'none';
                    });

                    const showingFrom = total === 0 ? 0 : startIndex + 1;
                    const showingTo = Math.min(total, endIndex);
                    summaryLabel.innerText = `Showing ${showingFrom} - ${showingTo} of ${total} records`;
                    pagerLabel.innerText = `Page ${currentPage} of ${totalPages}`;
                    prevButton.disabled = currentPage <= 1;
                    nextButton.disabled = currentPage >= totalPages;
                }

                searchInput.addEventListener('input', () => { currentPage = 1; updateTable(); });
                pageSizeSelect.addEventListener('change', () => { pageSize = parseInt(pageSizeSelect.value, 10) || 10; currentPage = 1; updateTable(); });
                prevButton.addEventListener('click', () => { if (currentPage > 1) { currentPage--; updateTable(); } });
                nextButton.addEventListener('click', () => { currentPage++; updateTable(); });
                updateTable();
            }

            /* ─── Chat helpers ───────────────────────────────────────── */
            function openGroupChat(taskId, taskTitle) {
                currentChatRoomType = 'task';
                currentChatRoomId = taskId;
                currentChatUserId = null;
                currentAssignmentId = null;
                currentReply = null;
                const avatar = 'https://i.pravatar.cc/100?u=task-' + taskId;
                document.getElementById('chatPanelAvatar').src = avatar;
                document.getElementById('chatPanelTitle').innerText = taskTitle;
                document.getElementById('chatPanelSub').innerText = 'Task group chat';
                document.getElementById('chatPanel').classList.remove('hidden');
                document.getElementById('chatPanel').classList.add('flex');
                document.getElementById('replyPreviewPanel').classList.add('hidden');
                document.getElementById('chatInputPanel').value = '';
                loadChatConversation('task', taskId);
            }

            function openAssignmentChat(assignmentId, assignmentDesc, userId) {
                currentAssignmentId = assignmentId;
                currentChatRoomType = 'user';
                currentChatRoomId = userId;
                currentChatUserId = userId;
                const userName = document.querySelector(`#task-${currentChatUserId} .font-bold`)?.innerText || 'User';
                const avatar = 'https://i.pravatar.cc/100?u=' + currentChatUserId;
                document.getElementById('chatPanelAvatar').src = avatar;
                document.getElementById('chatPanelTitle').innerText = userName;
                document.getElementById('chatPanelSub').innerText = 'Chat with ' + userName;
                document.getElementById('chatPanel').classList.remove('hidden');
                document.getElementById('chatPanel').classList.add('flex');
                currentReply = { id: `assignment-${assignmentId}`, name: `Assignment #${assignmentId}`, text: assignmentDesc || '', type: 'assignment' };
                const preview = document.getElementById('replyPreviewContent');
                preview.innerHTML = `
                                                                                                                                                                    <div class="flex-1">
                                                                                                                                                                        <div class="text-xs text-slate-500">Replying to <span class="font-semibold">${escapeHtml(currentReply.name)}</span></div>
                                                                                                                                                                        <div class="text-sm text-slate-700 truncate">${escapeHtml(currentReply.text)}</div>
                                                                                                                                                                    </div>
                                                                                                                                                                    <button onclick="clearReply(); event.stopPropagation();" class="ml-3 text-slate-500">✕</button>`;
                document.getElementById('replyPreviewPanel').classList.remove('hidden');
                const input = document.getElementById('chatInputPanel');
                input.value = `#assignment-${assignmentId} `;
                input.focus();
                loadChatConversation('user', userId, userName, avatar);
            }

            function openChecklistReply(checklistId, checklistTitle, taskId, taskTitle) {
                currentChatRoomType = 'task';
                currentChatRoomId = taskId;
                currentChatUserId = null;
                currentAssignmentId = null;
                currentReply = { id: checklistId, name: checklistTitle, text: checklistTitle, type: 'checklist' };
                const avatar = 'https://i.pravatar.cc/100?u=task-' + taskId;
                document.getElementById('chatPanelAvatar').src = avatar;
                document.getElementById('chatPanelTitle').innerText = taskTitle;
                document.getElementById('chatPanelSub').innerText = 'Reply checklist';
                document.getElementById('chatPanel').classList.remove('hidden');
                document.getElementById('chatPanel').classList.add('flex');
                const preview = document.getElementById('replyPreviewContent');
                preview.innerHTML = `
                                                                                                                                                                    <div class="flex-1">
                                                                                                                                                                        <div class="text-xs text-slate-500">Replying to checklist:</div>
                                                                                                                                                                        <div class="text-sm text-slate-700 truncate">${escapeHtml(checklistTitle)}</div>
                                                                                                                                                                    </div>
                                                                                                                                                                    <button onclick="clearReply(); event.stopPropagation();" class="ml-3 text-slate-500">✕</button>`;
                document.getElementById('replyPreviewPanel').classList.remove('hidden');
                document.getElementById('chatInputPanel').value = '';
                loadChatConversation('task', taskId);
            }

            function loadChatConversation(roomType, roomId = null, displayName = null, avatarUrl = null) {
                const chatBody = document.getElementById('chatBodyPanel');
                chatBody.innerHTML = `<div class="text-sm text-slate-500">Loading conversation...</div>`;
                let url = '/chat/room/global';
                if (roomType === 'task') url = `/chat/room/task/${roomId}`;
                else if (roomType === 'user') url = `/chat/room/user/${roomId}`;
                fetch(url)
                    .then(r => r.json())
                    .then(data => {
                        if (data.room) document.getElementById('chatPanelTitle').innerText = data.room.title || displayName || document.getElementById('chatPanelTitle').innerText;
                        renderChatMessages(data.messages || []);
                    })
                    .catch(() => { chatBody.innerHTML = `<div class="text-sm text-red-500">Unable to load chat.</div>`; });
            }

            function renderChatMessages(messages) {
                const chatBody = document.getElementById('chatBodyPanel');
                chatBody.innerHTML = '';
                messages.forEach(message => {
                    const fromUser = message.from_user || {};
                    const replyTo = message.reply_to || null;
                    appendMessageBubble({
                        id: message.id,
                        name: fromUser.name || 'Unknown',
                        avatar: fromUser.id ? `https://i.pravatar.cc/100?u=${fromUser.id}` : 'https://i.pravatar.cc/100',
                        text: escapeHtml(message.body),
                        images: message.images || [],
                        isOwn: message.from_user_id === currentUserId,
                        time: new Date(message.created_at),
                        replyTo: replyTo ? { name: replyTo.from_user?.name || 'Reply', text: replyTo.body || '' } : null
                    });
                });
                chatBody.scrollTop = chatBody.scrollHeight;
            }

            function toggleTask(id) {
                const row = document.getElementById('task-' + id);
                if (!row) return;
                if (event && event.target.closest('button')) return;
                row.classList.toggle('hidden');
                row.style.display = row.classList.contains('hidden') ? 'none' : '';
            }

            function showAssignForm(id, name) {
                const modal = document.getElementById('assignFormModalWrapper');
                modal.classList.remove('hidden');
                const select = document.getElementById('assignUserSelect');
                if (select) select.value = id;
                document.getElementById('assignModalTitle').innerText = `Assign to ${name}`;
                document.body.style.overflow = 'hidden';
            }

            function showTaskAssignForm() {
                const modal = document.getElementById('assignFormModalWrapper');
                modal.classList.remove('hidden');
                document.getElementById('assignModalTitle').innerText = 'Create Task Draft';
                const select = document.getElementById('assignUserSelect');
                if (select) select.value = '';
                document.body.style.overflow = 'hidden';
            }

            function hideAssignForm() {
                document.getElementById('assignFormModalWrapper').classList.add('hidden');
                document.body.style.overflow = '';
            }



            function addTodolist() {
                const container = document.getElementById('checklistContainer');

                const wrapper = document.createElement('div');
                wrapper.className = "flex items-center gap-2";

                wrapper.innerHTML = `
            <input 
                name="checklists[]" 
                class="border border-slate-200 rounded-xl px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-indigo-200"
                placeholder="Todolist"
            />

            <button type="button"
                onclick="this.parentElement.remove()"
                class="w-10 h-10 flex items-center justify-center rounded-xl bg-red-500 text-white hover:bg-red-600">
                ×
            </button>
        `;

                container.appendChild(wrapper);
            }

            function openProjectEditModal(taskId, title, description, priority, status) {
                if (isDireksi) return;
                document.getElementById('projectEditId').value = taskId;
                document.getElementById('projectEditTitle').value = title || '';
                document.getElementById('projectEditDescription').value = description || '';
                document.getElementById('projectEditPriority').value = priority || 'medium';
                document.getElementById('projectEditStatus').value = status || 'pending';
                document.getElementById('projectEditModalWrapper').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function hideProjectEditModal() {
                document.getElementById('projectEditModalWrapper').classList.add('hidden');
                document.body.style.overflow = '';
            }

            async function submitProjectEdit() {
                const taskId = document.getElementById('projectEditId').value;
                const form = document.getElementById('projectEditForm');
                const formData = new FormData(form);
                try {
                    const response = await fetch(`/project/${taskId}`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                        body: formData
                    });
                    const data = await response.json();
                    if (data.success) {
                        hideProjectEditModal();
                        gToast.success('Project diperbarui', data.message || 'Project berhasil diupdate.');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        gModal.alert(data.message || 'Gagal mengupdate project.', 'warning');
                    }
                } catch (e) {
                    console.error(e);
                    gModal.alert('Terjadi kesalahan saat mengupdate project.', 'warning');
                }
            }

            function openDraftEditModal(draftId, userId, description, deadline, notes) {
                if (isDireksi) return;
                document.getElementById('draftEditId').value = draftId;
                document.getElementById('draftEditUserId').value = userId || '';
                document.getElementById('draftEditDescription').value = description || '';
                document.getElementById('draftEditDeadline').value = deadline || '';
                document.getElementById('draftEditNotes').value = notes || '';
                document.getElementById('draftEditModalWrapper').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function hideDraftEditModal() {
                document.getElementById('draftEditModalWrapper').classList.add('hidden');
                document.body.style.overflow = '';
            }

            async function submitDraftEdit() {
                const draftId = document.getElementById('draftEditId').value;
                const form = document.getElementById('draftEditForm');
                const formData = new FormData(form);
                try {
                    const response = await fetch(`/project/assignment/${draftId}`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                        body: formData
                    });
                    const data = await response.json();
                    if (data.success) {
                        hideDraftEditModal();
                        gToast.success('Task draft diperbarui', data.message || 'Task draft berhasil diupdate.');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        gModal.alert(data.message || 'Gagal mengupdate task draft.', 'warning');
                    }
                } catch (e) {
                    console.error(e);
                    gModal.alert('Terjadi kesalahan saat mengupdate task draft.', 'warning');
                }
            }

            function openChecklistEditModal(checklistId, title) {
                if (isDireksi) return;
                document.getElementById('checklistEditId').value = checklistId;
                document.getElementById('checklistEditTitle').value = title || '';
                document.getElementById('checklistEditModalWrapper').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function hideChecklistEditModal() {
                document.getElementById('checklistEditModalWrapper').classList.add('hidden');
                document.body.style.overflow = '';
            }

            async function submitChecklistEdit() {
                const checklistId = document.getElementById('checklistEditId').value;
                const form = document.getElementById('checklistEditForm');
                const formData = new FormData(form);
                try {
                    const response = await fetch(`/project/checklist/${checklistId}`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                        body: formData
                    });
                    const data = await response.json();
                    if (data.success) {
                        hideChecklistEditModal();
                        gToast.success('Checklist diperbarui', data.message || 'Checklist berhasil diupdate.');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        gModal.alert(data.message || 'Gagal mengupdate checklist.', 'warning');
                    }
                } catch (e) {
                    console.error(e);
                    gModal.alert('Terjadi kesalahan saat mengupdate checklist.', 'warning');
                }
            }

            /* ─── Send message ───────────────────────────────────────── */
            function sendMessagePanel() {
                const input = document.getElementById('chatInputPanel');
                const messageText = input.value.trim();
                if (messageText === '' && selectedImagesPanel.length === 0 && !currentReply) return;
                if (!currentChatRoomType) {
                    gModal.alert('Silakan buka chat terlebih dahulu.', 'info');
                    return;
                }
                const replyToId = currentReply && currentReply.type === 'message' && !isNaN(parseInt(currentReply.id, 10)) ? parseInt(currentReply.id, 10) : null;
                const payloadBody = currentReply && currentReply.type === 'checklist'
                    ? `[Reply checklist: ${currentReply.name}] ${messageText}`
                    : messageText;
                const payload = { body: payloadBody, room_type: currentChatRoomType, reply_to_id: replyToId };
                if (currentChatRoomType === 'task') payload.task_id = currentChatRoomId;
                if (currentChatRoomType === 'user') { payload.to_user_id = currentChatRoomId; payload.task_id = currentTaskId; }

                fetch('/chat/message', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify(payload)
                })
                    .then(r => r.json())
                    .then(data => {
                        if (!data.success) { gModal.alert(data.message || 'Pesan gagal terkirim.', 'warning'); return; }
                        input.value = '';
                        removePreviewImagePanel();
                        clearReply();
                        loadChatConversation(currentChatRoomType, currentChatRoomId);
                    })
                    .catch(() => gModal.alert('Pesan gagal terkirim.', 'warning'));
            }

            /* ─── Message bubbles ────────────────────────────────────── */
            function appendMessageBubble({ id, name, avatar, text, images = [], isOwn = false, time = new Date(), replyTo = null }) {
                const chatBody = document.getElementById('chatBodyPanel');
                const timeStr = formatTime(time);
                let imagesHTML = images.length ? `<div class="grid grid-cols-2 gap-2 mt-2">${images.map(img => `<img src="${img}" class="w-36 h-24 object-cover rounded-xl border">`).join('')}</div>` : '';
                let replyHTML = '';
                if (replyTo) {
                    replyHTML = `<div class="mb-2 px-3 py-2 rounded-md border-l-4 border-indigo-400 bg-white/60 text-xs text-slate-600">
                                                                                                                                                                        <div class="font-semibold text-xs text-slate-700">${escapeHtml(replyTo.name || '')}</div>
                                                                                                                                                                        <div class="truncate max-w-[240px]">${escapeHtml(replyTo.text)}</div></div>`;
                }
                if (isOwn) {
                    chatBody.innerHTML += `<div class="flex items-end justify-end" data-msg-id="${id}">
                                                                                                                                                                        <div class="text-xs text-slate-400 mr-3">${timeStr}</div>
                                                                                                                                                                        <div class="max-w-[70%]"><div class="bg-indigo-600 text-white rounded-2xl rounded-br-sm px-4 py-3 shadow-sm break-words">${replyHTML}${text ? `<div class="text-sm">${text}</div>` : ''}${imagesHTML}</div></div>
                                                                                                                                                                        <img src="${avatar}" class="w-8 h-8 rounded-full ml-3 hidden sm:block"></div>`;
                } else {
                    chatBody.innerHTML += `<div class="flex items-start gap-3" data-msg-id="${id}" onclick="setReplyFromDom(${id},'${addslashes(name)}','${addslashes(stripHtml(text).slice(0, 120))}')">
                                                                                                                                                                        <img src="${avatar}" class="w-8 h-8 rounded-full">
                                                                                                                                                                        <div class="max-w-[70%]"><div class="text-xs font-semibold text-slate-600 mb-1">${escapeHtml(name)}</div>
                                                                                                                                                                        <div class="bg-white rounded-2xl rounded-bl-sm px-4 py-3 shadow-sm break-words">${replyHTML}${text ? `<div class="text-sm text-slate-800">${text}</div>` : ''}${imagesHTML}</div>
                                                                                                                                                                        <div class="text-xs text-slate-400 mt-1">${timeStr}</div></div></div>`;
                }
                chatBody.scrollTop = chatBody.scrollHeight;
            }

            function setReplyFromDom(msgId, fromName, shortText) {
                currentReply = { id: msgId, name: fromName, text: shortText, type: 'message' };
                const preview = document.getElementById('replyPreviewContent');
                preview.innerHTML = `<div class="flex-1"><div class="text-xs text-slate-500">Replying to <span class="font-semibold">${escapeHtml(fromName)}</span></div><div class="text-sm text-slate-700 truncate">${escapeHtml(shortText)}</div></div><button onclick="clearReply(); event.stopPropagation();" class="ml-3 text-slate-500">✕</button>`;
                document.getElementById('replyPreviewPanel').classList.remove('hidden');
                document.getElementById('chatInputPanel').focus();
            }

            function clearReply() {
                currentReply = null;
                const panel = document.getElementById('replyPreviewPanel');
                if (panel) panel.classList.add('hidden');
                const preview = document.getElementById('replyPreviewContent');
                if (preview) preview.innerHTML = '';
            }

            function stripHtml(html) { const t = document.createElement('DIV'); t.innerHTML = html; return t.textContent || t.innerText || ''; }
            function formatTime(d) { const dt = new Date(d); let h = dt.getHours(), m = dt.getMinutes(); const a = h >= 12 ? 'PM' : 'AM'; h = h % 12 || 12; return `${h}:${m < 10 ? '0' + m : m} ${a}`; }
            function escapeHtml(u) { return u.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;'); }
            function addslashes(s) { return (s || '').replace(/\\/g, '\\\\').replace(/'/g, "\\'"); }

            /* ─── Image preview ──────────────────────────────────────── */
            function previewSelectedImagePanel(event) {
                const files = event.target.files;
                const previewWrapper = document.getElementById('previewWrapperPanel');
                previewWrapper.innerHTML = '';
                selectedImagesPanel = [];
                Array.from(files).forEach((file) => {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        selectedImagesPanel.push(e.target.result);
                        previewWrapper.innerHTML += `<div class="relative shrink-0"><img src="${e.target.result}" class="w-32 h-32 rounded-3xl object-cover border border-slate-200 shadow"><button onclick="removeSingleImagePanel(${selectedImagesPanel.length - 1})" class="absolute -top-2 -right-2 w-7 h-7 rounded-full bg-red-500 hover:bg-red-600 text-white text-xs shadow-lg">✕</button></div>`;
                    };
                    reader.readAsDataURL(file);
                });
                if (selectedImagesPanel.length) document.getElementById('previewContainerPanel').classList.remove('hidden');
            }

            function removePreviewImagePanel() {
                selectedImagesPanel = [];
                document.getElementById('previewWrapperPanel').innerHTML = '';
                document.getElementById('previewContainerPanel').classList.add('hidden');
                const el = document.getElementById('imageInputPanel');
                if (el) el.value = '';
            }

            function removeSingleImagePanel(index) {
                selectedImagesPanel.splice(index, 1);
                const pw = document.getElementById('previewWrapperPanel');
                pw.innerHTML = '';
                selectedImagesPanel.forEach((img, i) => { pw.innerHTML += `<div class="relative shrink-0"><img src="${img}" class="w-32 h-32 rounded-3xl object-cover border border-slate-200 shadow"><button onclick="removeSingleImagePanel(${i})" class="absolute -top-2 -right-2 w-7 h-7 rounded-full bg-red-500 hover:bg-red-600 text-white text-xs shadow-lg">✕</button></div>`; });
                if (!selectedImagesPanel.length) document.getElementById('previewContainerPanel').classList.add('hidden');
            }

            /* ─── Camera ─────────────────────────────────────────────── */
            function openCameraPanel() { document.getElementById('cameraContainerPanel').classList.remove('hidden'); }
            function closeCameraPanel() {
                const video = document.getElementById('cameraVideoPanel');
                if (video?.srcObject) { try { video.srcObject.getTracks().forEach(t => t.stop()); } catch (e) { } video.srcObject = null; }
                document.getElementById('cameraContainerPanel').classList.add('hidden');
            }
            function closeChatPanel() {
                closeCameraPanel();
                removePreviewImagePanel();
                currentChatUserId = null;
                currentAssignmentId = null;
                document.getElementById('chatPanel').classList.add('hidden');
                const input = document.getElementById('chatInputPanel');
                if (input) input.value = '';
                const body = document.getElementById('chatBodyPanel');
                if (body) body.innerHTML = '';
            }
            function capturePhotoPanel() { }

            /* ─── CRUD actions  (semua pakai gModal + gToast) ──────── */

            /* Assign checklist from draft table */
            function openAssignChecklistModal(checklistId, checklistTitle, deadline, description, notes) {
                document.getElementById('assignChecklistId').value = checklistId;
                document.getElementById('assignChecklistTitle').innerText = checklistTitle;
                document.getElementById('assignChecklistDeadline').value = deadline || '';
                document.getElementById('assignChecklistDescription').value = description || '';
                document.getElementById('assignChecklistNotes').value = notes || '';
                document.getElementById('assignChecklistUserId').value = '';
                document.getElementById('assignChecklistModalWrapper').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function hideAssignChecklistModal() {
                document.getElementById('assignChecklistModalWrapper').classList.add('hidden');
                document.body.style.overflow = '';
            }

            async function submitAssignChecklist() {
                const checklistId = document.getElementById('assignChecklistId').value;
                const userId = document.getElementById('assignChecklistUserId').value;
                const description = document.getElementById('assignChecklistDescription').value.trim();
                const deadline = document.getElementById('assignChecklistDeadline').value;
                const notes = document.getElementById('assignChecklistNotes').value.trim();
                if (!userId) {
                    gModal.alert('Silakan pilih member terlebih dahulu.', 'warning');
                    return;
                }
                try {
                    const response = await fetch(`/project/checklist/${checklistId}/assign`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                        body: JSON.stringify({ user_id: userId, description, deadline, notes })
                    });
                    const data = await response.json();
                    if (data.success) {
                        hideAssignChecklistModal();
                        gToast.success('Berhasil diassign', data.message || 'Checklist berhasil diassign ke member.');
                        setTimeout(() => location.reload(), 1200);
                    } else {
                        gModal.alert(data.message || 'Gagal meng-assign checklist.', 'warning');
                    }
                } catch (error) {
                    console.error(error);
                    gModal.alert('Terjadi kesalahan saat assign.', 'warning');
                }
            }

            /* Delete draft */
            async function deleteDraft(draftId) {
                gModal.confirm({
                    type: 'delete',
                    title: 'Hapus Draft Ini?',
                    message: 'Seluruh checklist dan assignment pada draft ini akan dihapus secara permanen.',
                    confirmLabel: 'Ya, Hapus',
                    onConfirm: async () => {
                        try {
                            const response = await fetch(`/project/assignment/${draftId}`, {
                                method: 'DELETE',
                                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                            });
                            const data = await response.json();
                            if (data.success) {
                                gToast.success('Draft dihapus', 'Draft assignment berhasil dihapus.');
                                setTimeout(() => location.reload(), 1000);
                            } else {
                                gModal.alert(data.message || 'Gagal menghapus draft.', 'warning');
                            }
                        } catch (e) {
                            console.error(e);
                            gModal.alert('Terjadi kesalahan.', 'warning');
                        }
                    }
                });
            }

            /* Delete checklist item */
            async function deleteChecklist(checklistId) {
                gModal.confirm({
                    type: 'delete',
                    title: 'Hapus Item Ini?',
                    message: 'Checklist item ini akan dihapus dan tidak bisa dikembalikan.',
                    confirmLabel: 'Hapus Item',
                    onConfirm: async () => {
                        try {
                            const response = await fetch(`/project/checklist/${checklistId}`, {
                                method: 'DELETE',
                                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                            });
                            const data = await response.json();
                            if (data.success) {
                                gToast.success('Item dihapus', 'Checklist item berhasil dihapus.');
                                setTimeout(() => location.reload(), 1000);
                            } else {
                                gModal.alert(data.message || 'Gagal menghapus checklist.', 'warning');
                            }
                        } catch (e) {
                            console.error(e);
                            gModal.alert('Terjadi kesalahan.', 'warning');
                        }
                    }
                });
            }

            /* Unassign checklist */
            async function unassignChecklist(checklistId, checklistTitle) {
                gModal.confirm({
                    type: 'unassign',
                    title: 'Unassign Checklist?',
                    message: 'Item ini akan kembali ke draft dan bisa diassign ulang ke member lain.',
                    highlight: checklistTitle,
                    confirmLabel: 'Ya, Unassign',
                    onConfirm: async () => {
                        try {
                            const response = await fetch(`/project/checklist/${checklistId}/unassign`, {
                                method: 'PATCH',
                                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                            });
                            const data = await response.json();
                            if (data.success) {
                                gToast.info('Di-unassign', 'Checklist dikembalikan ke draft.');
                                setTimeout(() => location.reload(), 1000);
                            } else {
                                gModal.alert(data.message || 'Gagal unassign checklist.', 'warning');
                            }
                        } catch (e) {
                            console.error(e);
                            gModal.alert('Terjadi kesalahan.', 'warning');
                        }
                    }
                });
            }

            /* ─── Manager uncheck ────────────────────────────────────── */
            function openUncheckModal(checklistId, title) {
                document.getElementById('uncheckChecklistId').value = checklistId;
                document.getElementById('uncheckItemTitle').innerText = title;
                document.getElementById('uncheckReason').value = '';
                document.getElementById('uncheckReasonError').classList.add('hidden');
                const modal = document.getElementById('uncheckReasonModal');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
                setTimeout(() => document.getElementById('uncheckReason').focus(), 100);
            }

            function hideUncheckModal() {
                const modal = document.getElementById('uncheckReasonModal');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            }

            async function submitUncheck() {
                const checklistId = document.getElementById('uncheckChecklistId').value;
                const reason = document.getElementById('uncheckReason').value.trim();
                if (!reason) {
                    document.getElementById('uncheckReasonError').classList.remove('hidden');
                    document.getElementById('uncheckReason').focus();
                    return;
                }
                document.getElementById('uncheckReasonError').classList.add('hidden');
                try {
                    const response = await fetch(`/project/checklist/${checklistId}/manager-uncheck`, {
                        method: 'PATCH',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                        body: JSON.stringify({ reason })
                    });
                    const data = await response.json();
                    if (data.success) {
                        hideUncheckModal();
                        gToast.warning('Checklist dibatalkan', 'Status checklist kembali ke pending.');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        gModal.alert(data.message || 'Gagal membatalkan checklist.', 'warning');
                    }
                } catch (e) {
                    console.error(e);
                    gModal.alert('Terjadi kesalahan.', 'warning');
                }
            }

            /* ─── Misc (todo, assign todo) ───────────────────────────── */
            async function createTodo(taskId) {
                const titleEl = document.getElementById('newTodoTitle');
                const title = titleEl.value.trim();
                if (!title) { gModal.alert('Judul todo harus diisi.', 'warning'); return; }
                const response = await fetch(`/project/${taskId}/todo`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: JSON.stringify({ title })
                });
                const json = await response.json();
                if (json.success) { titleEl.value = ''; location.reload(); }
                else { gModal.alert(json.message || 'Gagal membuat todo.', 'warning'); }
            }

            async function assignTodo(todoId, userId) {
                if (!userId) return;
                const response = await fetch(`/project/todo/${todoId}/assign`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: JSON.stringify({ assigned_user_id: userId })
                });
                const json = await response.json();
                if (json.success) { location.reload(); }
                else { gModal.alert(json.message || 'Gagal assign todo.', 'warning'); }
            }

            function addTodoField() {
                document.getElementById('todoFieldContainer').innerHTML += `
                                                                                                                                                                    <div class="todo-item bg-white border border-slate-200 rounded-2xl p-4">
                                                                                                                                                                        <div class="flex items-start justify-between gap-4">
                                                                                                                                                                            <div class="flex-1 space-y-3">
                                                                                                                                                                                <input type="text" class="todo-title w-full border border-slate-200 rounded-2xl px-4 py-3 outline-none focus:border-indigo-500" placeholder="Todo title">
                                                                                                                                                                                <textarea class="todo-description w-full border border-slate-200 rounded-2xl px-4 py-3 outline-none focus:border-indigo-500" placeholder="Description"></textarea>
                                                                                                                                                                            </div>
                                                                                                                                                                            <button type="button" onclick="removeTodoField(this)" class="w-10 h-10 rounded-2xl bg-red-100 hover:bg-red-200 text-red-600 font-bold">✕</button>
                                                                                                                                                                        </div>
                                                                                                                                                                    </div>`;
            }

            function removeTodoField(button) { button.closest('.todo-item').remove(); }

            async function createMultipleTodo(taskId) {
                const todoItems = [];
                document.querySelectorAll('.todo-item').forEach(item => {
                    const title = item.querySelector('.todo-title').value.trim();
                    if (title) todoItems.push({ title });
                });
                if (!todoItems.length) { gModal.alert('Minimal 1 todo harus diisi.', 'warning'); return; }
                try {
                    for (const todo of todoItems) {
                        const response = await fetch(`/project/${taskId}/todo`, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                            body: JSON.stringify(todo)
                        });
                        const json = await response.json();
                        if (!json.success) { gModal.alert(json.message || 'Gagal membuat todo.', 'warning'); return; }
                    }
                    location.reload();
                } catch (e) {
                    gModal.alert('Terjadi kesalahan.', 'warning');
                }
            }

            window.openProjectEditModal = openProjectEditModal;
            window.hideProjectEditModal = hideProjectEditModal;
            window.openDraftEditModal = openDraftEditModal;
            window.hideDraftEditModal = hideDraftEditModal;
            window.openChecklistEditModal = openChecklistEditModal;
            window.hideChecklistEditModal = hideChecklistEditModal;
        </script>

@endsection