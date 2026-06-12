@extends('layouts.app')

@section('content')

    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="relative overflow-hidden rounded-[32px] border border-slate-200 bg-white shadow-sm p-6 md:p-8">
            <div class="absolute top-0 right-0 w-72 h-72 bg-indigo-100 rounded-full blur-3xl opacity-50"></div>
            <div class="absolute bottom-0 left-0 w-72 h-72 bg-slate-100 rounded-full blur-3xl opacity-50"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between gap-3">
                    <div class="min-w-0 flex-1">
                        <p class="text-[10px] md:text-sm uppercase tracking-[0.25em] text-indigo-600 font-semibold">Supervisor Projects</p>
                        <h1 class="mt-2 md:mt-4 text-lg sm:text-xl md:text-3xl font-black text-slate-900 leading-tight">Checklist Assignment Workspace</h1>
                    </div>
                    <div class="flex items-center gap-2 md:gap-3 shrink-0">
                        <button type="button" onclick="openStaffChat('global', null, 'Global Staff Chat')"
                            class="inline-flex items-center justify-center rounded-2xl md:rounded-3xl bg-indigo-600 px-3 md:px-5 py-2 md:py-3 text-[11px] md:text-sm font-semibold text-white shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition whitespace-nowrap">
                            <span class="hidden sm:inline">Global Chat</span>
                            <span class="sm:hidden">Chat</span>
                        </button>
                        <a href="{{ route('supervisor.dashboard') }}"
                            class="inline-flex items-center justify-center rounded-2xl md:rounded-3xl bg-slate-900 px-3 md:px-5 py-2 md:py-3 text-[11px] md:text-sm font-semibold text-white shadow-lg shadow-slate-200 hover:bg-slate-800 transition whitespace-nowrap">
                            <x-icon name="back" class="w-4 h-4" />
                            <span class="hidden sm:inline">Back</span>
                        </a>
                    </div>
                </div>

                @php
                    $allCl    = $assignmentsByUser->flatten()->flatMap(fn($a) => $a->checklists);
                    $allDone  = $allCl->where('is_done', true)->count();
                    $allTotal = $allCl->count();
                @endphp
                <div class="mt-6 md:mt-8 grid grid-cols-4 gap-2 md:gap-4">
                    <div class="rounded-[18px] md:rounded-[32px] border border-slate-200 bg-slate-50 p-3 md:p-6 shadow-sm">
                        <p class="text-[10px] md:text-sm font-semibold text-slate-500 truncate">Staff</p>
                        <h2 class="mt-1 md:mt-3 text-lg md:text-3xl font-black text-slate-900">{{ $staff->count() }}</h2>
                    </div>
                    <div class="rounded-[18px] md:rounded-[32px] border border-slate-200 bg-white p-3 md:p-6 shadow-sm">
                        <p class="text-[10px] md:text-sm font-semibold text-slate-500 truncate">Assignments</p>
                        <h2 class="mt-1 md:mt-3 text-lg md:text-3xl font-black text-slate-900">{{ $assignmentsByUser->flatten()->count() }}</h2>
                    </div>
                    <div class="rounded-[18px] md:rounded-[32px] border border-emerald-200 bg-emerald-50 p-3 md:p-6">
                        <p class="text-[10px] md:text-sm font-semibold text-emerald-600 truncate">Completed</p>
                        <h2 class="mt-1 md:mt-3 text-lg md:text-3xl font-black text-emerald-700">{{ $allDone }}</h2>
                    </div>
                    <div class="rounded-[18px] md:rounded-[32px] border border-amber-200 bg-amber-50 p-3 md:p-6">
                        <p class="text-[10px] md:text-sm font-semibold text-amber-600 truncate">Pending</p>
                        <h2 class="mt-1 md:mt-3 text-lg md:text-3xl font-black text-amber-700">{{ $allTotal - $allDone }}</h2>
                    </div>
                </div>
            </div>
        </div>

        {{-- PER-STAFF SECTIONS --}}
        @forelse($staff as $member)
            @php
                $memberAssignments = $assignmentsByUser->get($member->id, collect());
                $mCl    = $memberAssignments->flatMap(fn($a) => $a->checklists);
                $mDone  = $mCl->where('is_done', true)->count();
                $mTotal = $mCl->count();
                $mPct   = $mTotal > 0 ? round(($mDone / $mTotal) * 100) : 0;
            @endphp

            <div class="rounded-[20px] md:rounded-[32px] border border-slate-200 bg-white shadow-sm overflow-hidden">

                {{-- Staff Header --}}
                <div class="flex items-center justify-between px-5 md:px-7 py-4 bg-slate-50 border-b border-slate-200">
                    <div class="flex items-center gap-3">
                        <img src="https://i.pravatar.cc/100?u={{ $member->id }}"
                             class="w-9 h-9 rounded-full border-2 border-white shadow-sm shrink-0">
                        <div>
                            <p class="font-bold text-slate-800">{{ $member->name }}</p>
                            <p class="text-xs text-slate-500">{{ $member->email }}</p>
                        </div>
                        <span class="ml-1 px-2.5 py-1 rounded-full bg-indigo-100 text-indigo-700 text-[11px] font-semibold">
                            {{ $memberAssignments->count() }} assignment
                        </span>
                    </div>
                    @if($mTotal > 0)
                        <div class="flex items-center gap-2">
                            <div class="w-24 h-1.5 bg-slate-200 rounded-full overflow-hidden">
                                <div class="h-full bg-emerald-400 rounded-full" style="width:{{ $mPct }}%"></div>
                            </div>
                            <span class="text-xs text-slate-500 font-semibold">{{ $mPct }}%</span>
                        </div>
                    @endif
                </div>

                @if($memberAssignments->count())
                    {{-- Desktop Table --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700">Task</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700">Description</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-slate-700">Checklist</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-slate-700">Progress</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-slate-700">Deadline</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-slate-700">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @foreach($memberAssignments as $index => $assignment)
                                    @php
                                        $doneChecklist = $assignment->checklists->where('is_done', true)->count();
                                        $totalChecklist = $assignment->checklists->count();
                                        $assignmentStatus = $totalChecklist > 0
                                            ? ($doneChecklist >= $totalChecklist ? 'done' : ($doneChecklist > 0 ? 'progress' : 'pending'))
                                            : 'pending';
                                        $assignmentStatusLabel = $assignmentStatus === 'done' ? 'Done' : ($assignmentStatus === 'progress' ? 'On Progress' : 'Pending');
                                        $assignmentStatusClass = $assignmentStatus === 'done'
                                            ? 'bg-emerald-100 text-emerald-700'
                                            : ($assignmentStatus === 'progress' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700');
                                    @endphp
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-semibold text-slate-900">{{ optional($assignment->task)->title ?? '—' }}</p>
                                            <p class="text-xs text-slate-400 mt-1">#{{ $assignment->task_id }}</p>
                                            <span class="mt-2 inline-block rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $assignmentStatusClass }}">
                                                {{ $assignmentStatusLabel }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-slate-600 line-clamp-2">{{ $assignment->description ?? '-' }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-block rounded-full bg-indigo-100 px-3 py-1 text-sm font-semibold text-indigo-700">
                                                {{ $totalChecklist }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col items-center gap-2">
                                                <div class="w-full bg-slate-200 rounded-full h-2 max-w-xs">
                                                    <div class="bg-emerald-500 h-2 rounded-full"
                                                        style="width:{{ $totalChecklist > 0 ? ($doneChecklist / $totalChecklist * 100) : 0 }}%"></div>
                                                </div>
                                                <p class="text-xs text-slate-500">
                                                    {{ $doneChecklist }}/{{ $totalChecklist }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($assignment->deadline)
                                                <span class="inline-block rounded-full bg-slate-100 px-3 py-1 text-sm font-semibold text-slate-600">
                                                    {{ \Carbon\Carbon::parse($assignment->deadline)->format('d M Y') }}
                                                </span>
                                            @else
                                                <span class="text-slate-400 text-xs">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <button onclick="toggleDropdown('sup-drop-{{ $member->id }}-{{ $index }}')"
                                                class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-50 hover:bg-indigo-100 px-3 py-2 transition">
                                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- Dropdown Checklist --}}
                                    <tr id="sup-drop-{{ $member->id }}-{{ $index }}" class="hidden bg-slate-50 border-t-2 border-indigo-200">
                                        <td colspan="6" class="px-6 py-6">
                                            <div class="space-y-3">
                                                <p class="text-sm font-semibold text-slate-700 mb-4">Checklist Items:</p>

                                                @forelse($assignment->checklists as $checklist)
                                                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                                                        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between p-4 border-b border-slate-100">

                                                            <label class="flex items-start gap-3 flex-1 cursor-pointer"
                                                                onclick="handleChecklistClick(event,{{ $checklist->id }},{{ $checklist->is_done ? 'true' : 'false' }},{{ $checklist->file_path ? 'true' : 'false' }},{{ $checklist->uncheck_reason ? json_encode($checklist->uncheck_reason) : 'null' }})">
                                                                <div class="h-5 w-5 rounded border-2 flex-shrink-0 flex items-center justify-center mt-0.5
                                                                    {{ $checklist->is_done ? 'bg-indigo-600 border-indigo-600' : 'border-slate-300 bg-white' }}"
                                                                    id="checkbox-visual-{{ $checklist->id }}">
                                                                    @if($checklist->is_done)
                                                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                                                        </svg>
                                                                    @endif
                                                                </div>
                                                                <div class="min-w-0 flex-1">
                                                                    <div class="flex flex-wrap items-center gap-2">
                                                                        <p class="text-sm font-semibold {{ $checklist->is_done ? 'text-slate-500 line-through' : 'text-slate-800' }}">
                                                                            {{ $checklist->title }}
                                                                        </p>
                                                                        <span class="rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $checklist->is_done ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                                                            {{ $checklist->is_done ? 'Selesai' : 'Menunggu' }}
                                                                        </span>
                                                                    </div>
                                                                    @if(!$checklist->is_done && $checklist->uncheck_reason)
                                                                        <div class="mt-2 flex items-start gap-2 rounded-xl border border-red-100 bg-red-50 px-3 py-2">
                                                                            <span class="text-red-400 text-xs mt-0.5">⚠</span>
                                                                            <div>
                                                                                <p class="text-xs font-semibold text-red-600">Alasan penolakan:</p>
                                                                                <p class="text-xs text-red-500 mt-0.5 leading-relaxed">{{ $checklist->uncheck_reason }}</p>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </label>

                                                            <div class="flex items-center gap-2">
                                                                <button type="button"
                                                                    onclick="event.stopPropagation(); openStaffChat('task', {{ $assignment->task_id }}, '{{ addslashes(optional($assignment->task)->title ?? '') }}')"
                                                                    class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-800 transition">
                                                                    Chat
                                                                </button>
                                                                <span class="rounded-full px-3 py-1.5 text-xs font-semibold {{ $checklist->is_done ? 'bg-slate-100 text-slate-600' : 'bg-indigo-100 text-indigo-700' }}">
                                                                    {{ $checklist->is_done ? 'Batalkan' : 'Selesaikan' }}
                                                                </span>
                                                            </div>
                                                        </div>

                                                        {{-- File table --}}
                                                        <div class="overflow-x-auto">
                                                            <table class="min-w-full">
                                                                <thead class="bg-slate-50 border-b border-slate-200">
                                                                    <tr>
                                                                        <th class="px-4 py-3 text-left text-[11px] font-bold uppercase text-slate-500">Nama File</th>
                                                                        <th class="px-4 py-3 text-left text-[11px] font-bold uppercase text-slate-500">Preview</th>
                                                                        <th class="px-4 py-3 text-left text-[11px] font-bold uppercase text-slate-500">Aksi</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @if($checklist->file_path)
                                                                        <tr class="border-b border-slate-100">
                                                                            <td class="px-4 py-4 align-top">
                                                                                <p class="text-sm font-medium text-slate-700 break-all">{{ $checklist->file_name ?? 'File Upload' }}</p>
                                                                                <p class="text-xs text-slate-400 mt-1">{{ $checklist->file_type ?? '' }}</p>
                                                                            </td>
                                                                            <td class="px-4 py-4 align-top">
                                                                                @if(str_starts_with($checklist->file_type ?? '', 'image/'))
                                                                                    <a href="{{ Storage::url($checklist->file_path) }}" target="_blank" onclick="event.stopPropagation()"
                                                                                        class="block rounded-xl overflow-hidden border border-slate-200 w-20 h-20 hover:opacity-90 transition">
                                                                                        <img src="{{ Storage::url($checklist->file_path) }}" class="w-full h-full object-cover">
                                                                                    </a>
                                                                                @else
                                                                                    <div class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-700">
                                                                                        @if($checklist->file_type === 'application/pdf') 📄 PDF
                                                                                        @elseif(in_array($checklist->file_type, ['application/vnd.ms-excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])) 📊 Excel
                                                                                        @else 📎 File
                                                                                        @endif
                                                                                    </div>
                                                                                @endif
                                                                            </td>
                                                                            <td class="px-4 py-4 align-top">
                                                                                <div class="flex flex-wrap items-center gap-2">
                                                                                    <a href="{{ Storage::url($checklist->file_path) }}" target="_blank" onclick="event.stopPropagation()"
                                                                                        class="inline-flex items-center rounded-xl bg-indigo-600 px-4 py-2 text-xs font-semibold text-white hover:bg-indigo-700 transition">
                                                                                        Lihat File
                                                                                    </a>
                                                                                    <button type="button" onclick="event.stopPropagation(); deleteChecklistFile({{ $checklist->id }})"
                                                                                        class="inline-flex items-center rounded-xl bg-red-100 px-4 py-2 text-xs font-semibold text-red-600 hover:bg-red-200 transition">
                                                                                        Hapus
                                                                                    </button>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    @else
                                                                        <tr>
                                                                            <td colspan="3" class="px-4 py-5">
                                                                                <div class="flex items-center justify-between rounded-2xl border border-dashed border-amber-200 bg-amber-50 px-4 py-3">
                                                                                    <div>
                                                                                        <p class="text-sm font-semibold text-amber-700">Belum ada bukti file</p>
                                                                                        <p class="text-xs text-amber-600 mt-1">Klik checklist untuk upload file bukti.</p>
                                                                                    </div>
                                                                                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">Pending</span>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                        <form id="uncheck-form-{{ $checklist->id }}" method="POST"
                                                            action="{{ route('project.checklist.toggle', $checklist->id) }}" class="hidden">
                                                            @csrf
                                                        </form>
                                                    </div>
                                                @empty
                                                    <p class="text-sm text-slate-500 text-center py-4">Tidak ada checklist untuk task ini</p>
                                                @endforelse
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile Cards --}}
                    <div class="md:hidden divide-y divide-slate-200">
                        @foreach($memberAssignments as $index => $assignment)
                            @php
                                $doneChecklist = $assignment->checklists->where('is_done', true)->count();
                                $totalChecklist = $assignment->checklists->count();
                                $assignmentStatus = $totalChecklist > 0
                                    ? ($doneChecklist >= $totalChecklist ? 'done' : ($doneChecklist > 0 ? 'progress' : 'pending'))
                                    : 'pending';
                                $assignmentStatusLabel = $assignmentStatus === 'done' ? 'Done' : ($assignmentStatus === 'progress' ? 'On Progress' : 'Pending');
                                $assignmentStatusClass = $assignmentStatus === 'done'
                                    ? 'bg-emerald-100 text-emerald-700'
                                    : ($assignmentStatus === 'progress' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700');
                            @endphp
                            <div class="p-4">
                                <button onclick="toggleDropdown('sup-mob-{{ $member->id }}-{{ $index }}')" class="w-full text-left">
                                    <div class="flex items-start justify-between gap-3 mb-3">
                                        <div class="min-w-0 flex-1">
                                            <h3 class="text-sm font-bold text-slate-900">{{ optional($assignment->task)->title ?? '—' }}</h3>
                                            <p class="text-xs text-slate-400 mt-0.5">#{{ $assignment->task_id }}</p>
                                        </div>
                                        <svg class="w-5 h-5 text-slate-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                        </svg>
                                    </div>
                                    <div class="flex flex-col gap-1.5 mb-3">
                                        <div class="flex justify-between items-center">
                                            <span class="text-xs font-semibold text-slate-600">Progress</span>
                                            <span class="text-xs text-slate-500">{{ $doneChecklist }}/{{ $totalChecklist }}</span>
                                        </div>
                                        <div class="w-full bg-slate-200 rounded-full h-1.5">
                                            <div class="bg-emerald-500 h-1.5 rounded-full"
                                                style="width:{{ $totalChecklist > 0 ? ($doneChecklist / $totalChecklist * 100) : 0 }}%"></div>
                                        </div>
                                    </div>
                                    <div class="flex flex-wrap gap-2">
                                        <span class="rounded-full bg-indigo-100 px-2 py-1 text-xs font-semibold text-indigo-700">{{ $totalChecklist }} Items</span>
                                        <span class="rounded-full px-2 py-1 text-xs font-semibold {{ $assignmentStatusClass }}">{{ $assignmentStatusLabel }}</span>
                                        @if($assignment->deadline)
                                            <span class="rounded-full bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-600">{{ \Carbon\Carbon::parse($assignment->deadline)->format('d M Y') }}</span>
                                        @endif
                                    </div>
                                </button>

                                <div id="sup-mob-{{ $member->id }}-{{ $index }}" class="hidden mt-4 pt-4 border-t border-slate-200">
                                    <p class="text-xs font-semibold text-slate-700 mb-3">Checklist Items:</p>
                                    <div class="space-y-2">
                                        @forelse($assignment->checklists as $checklist)
                                            <div class="flex flex-row items-center gap-2 rounded-2xl border border-slate-200 bg-white p-3">
                                                <label class="flex items-center gap-2 flex-1 cursor-pointer"
                                                    onclick="handleChecklistClick(event,{{ $checklist->id }},{{ $checklist->is_done ? 'true' : 'false' }},{{ $checklist->file_path ? 'true' : 'false' }},{{ $checklist->uncheck_reason ? json_encode($checklist->uncheck_reason) : 'null' }})">
                                                    <div class="h-5 w-5 rounded border-2 flex-shrink-0 flex items-center justify-center {{ $checklist->is_done ? 'bg-indigo-600 border-indigo-600' : 'border-slate-300 bg-white' }}" id="checkbox-visual-{{ $checklist->id }}">
                                                        @if($checklist->is_done)
                                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                        @endif
                                                    </div>
                                                    <div class="min-w-0 flex-1">
                                                        <p class="text-xs font-semibold {{ $checklist->is_done ? 'line-through text-slate-400' : 'text-slate-800' }}">{{ $checklist->title }}</p>
                                                        @if($checklist->file_path)
                                                            <a href="{{ Storage::url($checklist->file_path) }}" target="_blank" onclick="event.stopPropagation()" class="text-xs text-indigo-600 font-semibold">📎 {{ Str::limit($checklist->file_name ?? 'File', 12) }}</a>
                                                        @else
                                                            <p class="text-xs text-amber-600 mt-0.5">⚠ Belum ada bukti</p>
                                                        @endif
                                                    </div>
                                                    <span class="rounded-full px-2 py-0.5 text-xs font-semibold flex-shrink-0 {{ $checklist->is_done ? 'bg-slate-100 text-slate-600' : 'bg-indigo-100 text-indigo-700' }}">
                                                        {{ $checklist->is_done ? 'Batal' : 'Selesai' }}
                                                    </span>
                                                </label>
                                                <form id="uncheck-form-{{ $checklist->id }}" method="POST" action="{{ route('project.checklist.toggle', $checklist->id) }}" class="hidden">@csrf</form>
                                                <button type="button" onclick="event.stopPropagation(); openStaffChat('task', {{ $assignment->task_id }}, '{{ addslashes(optional($assignment->task)->title ?? '') }}')"
                                                    class="inline-flex items-center justify-center rounded-xl bg-slate-900 w-9 h-9 text-white hover:bg-slate-800 transition flex-shrink-0">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
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

                @else
                    <div class="p-10 text-center">
                        <div class="text-4xl mb-3">📭</div>
                        <p class="font-medium text-slate-600">Belum ada assignment untuk {{ $member->name }}.</p>
                    </div>
                @endif
            </div>

        @empty
            <div class="rounded-[32px] border border-slate-200 bg-white p-10 text-center shadow-sm">
                <div class="text-5xl mb-4">👥</div>
                <p class="text-lg font-semibold text-slate-800">Belum ada staff diassign ke Anda</p>
                <p class="mt-2 text-sm text-slate-500">Hubungi manager untuk assign staff ke Anda.</p>
            </div>
        @endforelse

    </div>

    {{-- ── UPLOAD MODAL ── --}}
    <div id="uploadModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" onclick="closeUploadModal(event)">
        <div class="w-full max-w-md rounded-[28px] bg-white shadow-2xl border border-slate-200 overflow-hidden" onclick="event.stopPropagation()">
            <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-indigo-50 to-slate-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Upload Bukti Pengerjaan</h3>
                        <p id="uploadModalSubtitle" class="mt-0.5 text-xs text-slate-500 line-clamp-1">—</p>
                    </div>
                    <button onclick="closeUploadModal()" class="rounded-full bg-slate-100 p-2 hover:bg-slate-200 transition">
                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
            <div class="px-6 py-5 space-y-4">
                <div id="uploadReasonArea" class="hidden">
                    <div class="rounded-xl bg-red-50 border border-red-200 px-4 py-3">
                        <div class="flex items-start gap-2">
                            <span class="text-red-400 text-lg shrink-0 mt-0.5">⚠️</span>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-semibold text-red-600 mb-1">Alasan Penolakan:</p>
                                <p id="uploadReasonText" class="text-xs text-red-600 leading-relaxed break-words">—</p>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Pilih Sumber File</p>
                <div class="grid grid-cols-3 gap-3">
                    <button type="button" onclick="triggerFileInput('gallery')" class="flex flex-col items-center gap-2 rounded-2xl border-2 border-slate-200 bg-slate-50 p-4 hover:border-indigo-400 hover:bg-indigo-50 transition"><span class="text-2xl">🖼️</span><span class="text-xs font-semibold text-slate-600">Galeri</span></button>
                    <button type="button" onclick="triggerFileInput('camera')" class="flex flex-col items-center gap-2 rounded-2xl border-2 border-slate-200 bg-slate-50 p-4 hover:border-indigo-400 hover:bg-indigo-50 transition"><span class="text-2xl">📷</span><span class="text-xs font-semibold text-slate-600">Kamera</span></button>
                    <button type="button" onclick="triggerFileInput('document')" class="flex flex-col items-center gap-2 rounded-2xl border-2 border-slate-200 bg-slate-50 p-4 hover:border-indigo-400 hover:bg-indigo-50 transition"><span class="text-2xl">📎</span><span class="text-xs font-semibold text-slate-600">Dokumen</span></button>
                </div>
                <div id="locationStatus" class="hidden rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div id="locationIcon" class="text-lg flex-shrink-0">📍</div>
                        <div class="min-w-0 flex-1">
                            <p id="locationText" class="text-xs font-semibold text-slate-700">Mengambil lokasi...</p>
                            <p id="locationSub" class="text-xs text-slate-400 mt-0.5">Mohon izinkan akses lokasi</p>
                        </div>
                        <div id="locationSpinner" class="w-4 h-4 rounded-full border-2 border-indigo-400 border-t-transparent animate-spin flex-shrink-0"></div>
                    </div>
                </div>
                <input type="file" id="fileInputGallery" accept="image/*,application/pdf,.xls,.xlsx" class="hidden" onchange="handleFileSelected(this)">
                <input type="file" id="fileInputCamera" accept="image/*" capture="environment" class="hidden" onchange="handleFileSelected(this)">
                <input type="file" id="fileInputDocument" accept="application/pdf,.xls,.xlsx" class="hidden" onchange="handleFileSelected(this)">
                <div id="uploadPreviewArea" class="hidden">
                    <div class="rounded-2xl border-2 border-indigo-200 bg-indigo-50 p-4">
                        <div class="flex items-center gap-3">
                            <div id="uploadPreviewIcon" class="text-3xl flex-shrink-0">📎</div>
                            <div class="min-w-0 flex-1">
                                <p id="uploadPreviewName" class="text-sm font-semibold text-slate-800 truncate">—</p>
                                <p id="uploadPreviewSize" class="text-xs text-slate-500 mt-0.5">—</p>
                            </div>
                            <button type="button" onclick="clearFileSelection()" class="rounded-full bg-white p-1.5 shadow-sm hover:bg-red-50 transition flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        <div id="imagePreviewWrapper" class="hidden mt-3 rounded-xl overflow-hidden border border-indigo-100">
                            <img id="imagePreviewEl" src="" class="w-full max-h-48 object-cover">
                        </div>
                    </div>
                </div>
                <p class="text-xs text-slate-400 text-center">Mendukung: JPG, PNG, GIF, WEBP, PDF, XLS, XLSX · Maks 10 MB</p>
                <div id="uploadErrorMsg" class="hidden rounded-xl bg-red-50 border border-red-200 px-4 py-3">
                    <p class="text-xs text-red-600 font-semibold" id="uploadErrorText">—</p>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex gap-3">
                <button type="button" onclick="closeUploadModal()" class="flex-1 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">Batal</button>
                <button type="button" id="uploadSubmitBtn" onclick="submitChecklistFile()" class="flex-1 rounded-2xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white hover:bg-indigo-700 transition disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    <span id="uploadSubmitLabel">Upload & Selesaikan</span>
                    <span id="uploadSubmitSpinner" class="hidden"><svg class="inline w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg> Mengupload...</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ── CHAT PANEL ── --}}
    <div id="staffChatPanel" class="fixed inset-0 z-50 hidden items-end justify-end bg-slate-900/40 p-4">
        <div class="w-full max-w-2xl rounded-[32px] bg-white shadow-2xl border border-slate-200 overflow-hidden flex flex-col h-[80vh]">
            <div class="flex items-center justify-between gap-4 border-b border-slate-200 px-5 py-4 bg-slate-50">
                <div>
                    <h2 id="staffChatPanelTitle" class="text-lg font-semibold text-slate-900">Chat</h2>
                    <p id="staffChatPanelSubtitle" class="text-sm text-slate-500">Mulai chat untuk task.</p>
                </div>
                <button type="button" onclick="closeStaffChatPanel()" class="rounded-3xl bg-slate-900 px-4 py-2 text-white text-xs font-semibold hover:bg-slate-800 transition">Tutup</button>
            </div>
            <div id="staffChatMessages" class="flex-1 overflow-y-auto px-5 py-5 space-y-4 bg-slate-50"></div>
            <div class="border-t border-slate-200 bg-white px-5 py-4">
                <div class="flex items-center gap-3">
                    <input id="staffChatInput" type="text" placeholder="Ketik pesan..." class="flex-1 rounded-3xl border border-slate-200 px-4 py-3 outline-none focus:border-indigo-500"/>
                    <button type="button" onclick="sendStaffChatMessage()" class="rounded-3xl bg-indigo-600 px-5 py-3 text-white text-sm font-semibold hover:bg-indigo-700 transition">Kirim</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ── CAMERA MODAL ── --}}
    <div id="cameraModal" class="fixed inset-0 z-[70] hidden items-center justify-center bg-slate-900/80 p-4">
        <div class="w-full max-w-lg rounded-[28px] bg-slate-900 overflow-hidden shadow-2xl border border-slate-700">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-700">
                <h3 class="text-sm font-bold text-white">Ambil Foto Bukti</h3>
                <button onclick="closeCameraModal()" class="rounded-full bg-slate-700 p-2 hover:bg-slate-600 transition"><svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <div class="relative bg-black" style="aspect-ratio:16/9;"><video id="cameraVideo" autoplay playsinline muted class="w-full h-full object-cover"></video></div>
            <canvas id="cameraCanvas" class="hidden"></canvas>
            <div class="flex items-center justify-center py-6 gap-6 bg-slate-900">
                <button onclick="closeCameraModal()" class="text-xs font-semibold text-slate-400 hover:text-white transition px-4 py-2">Batal</button>
                <button onclick="capturePhoto()" class="w-16 h-16 rounded-full bg-white border-4 border-slate-400 hover:bg-slate-100 active:scale-95 transition flex items-center justify-center shadow-lg"><div class="w-12 h-12 rounded-full bg-white border-2 border-slate-300"></div></button>
                <div class="w-16"></div>
            </div>
        </div>
    </div>

    <script>
    // ── State
    let uploadChecklistId=null,selectedFile=null,cameraStream=null;
    let currentLatitude=null,currentLongitude=null,currentAddress=null;
    let staffChatRoomType=null,staffChatRoomId=null;

    function toggleDropdown(id){const el=document.getElementById(id);if(el)el.classList.toggle('hidden');}

    // ── Geolocation
    function startLocationWatch(){
        currentLatitude=currentLongitude=currentAddress=null;
        const s=document.getElementById('locationStatus'),icon=document.getElementById('locationIcon'),txt=document.getElementById('locationText'),sub=document.getElementById('locationSub'),spin=document.getElementById('locationSpinner');
        if(s){s.className='rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-3';s.classList.remove('hidden');icon.textContent='📍';txt.textContent='Mengambil lokasi...';sub.textContent='Mohon izinkan akses lokasi';spin.classList.remove('hidden');}
        if(!navigator.geolocation){if(s){icon.textContent='⚠️';txt.textContent='Lokasi tidak tersedia';sub.textContent='Browser tidak mendukung';spin.classList.add('hidden');}return;}
        navigator.geolocation.getCurrentPosition(
            async function(pos){
                currentLatitude=pos.coords.latitude;currentLongitude=pos.coords.longitude;
                try{const r=await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${currentLatitude}&lon=${currentLongitude}`,{headers:{'Accept-Language':'id'}});const d=await r.json();currentAddress=d.display_name??null;}catch(_){}
                if(s){icon.textContent='✅';txt.textContent=currentAddress??`${currentLatitude.toFixed(5)}, ${currentLongitude.toFixed(5)}`;sub.textContent='Akurasi ±'+Math.round(pos.coords.accuracy)+'m';spin.classList.add('hidden');s.className='rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3';}
            },
            function(err){if(s){icon.textContent='❌';txt.textContent='Gagal mendapatkan lokasi';sub.textContent=err.code===1?'Akses lokasi ditolak':'Periksa GPS Anda';spin.classList.add('hidden');s.className='rounded-xl border border-red-200 bg-red-50 px-4 py-3';}},
            {enableHighAccuracy:true,timeout:10000,maximumAge:0}
        );
    }

    // ── Stamp image
    function stampImage(file){return new Promise(function(resolve){if(!file.type.startsWith('image/')){resolve(file);return;}const img=new Image(),url=URL.createObjectURL(file);img.onload=function(){const canvas=document.createElement('canvas');canvas.width=img.naturalWidth;canvas.height=img.naturalHeight;const ctx=canvas.getContext('2d');ctx.drawImage(img,0,0);URL.revokeObjectURL(url);const now=new Date(),lines=[now.toLocaleDateString('id-ID',{weekday:'long',year:'numeric',month:'long',day:'numeric'}),now.toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit',second:'2-digit'}),currentAddress??'Lokasi tidak tersedia',(currentLatitude&&currentLongitude)?currentLatitude.toFixed(5)+', '+currentLongitude.toFixed(5):''].filter(Boolean);const fs=Math.max(20,Math.round(canvas.width*0.022)),pad=Math.round(fs*0.7),lh=Math.round(fs*1.55),boxH=pad*2+lh*lines.length,boxY=canvas.height-boxH;ctx.fillStyle='rgba(0,0,0,0.55)';ctx.fillRect(0,boxY,canvas.width,boxH);ctx.font='bold '+fs+'px Arial,sans-serif';ctx.fillStyle='#ffffff';ctx.shadowColor='rgba(0,0,0,0.8)';ctx.shadowBlur=4;ctx.textBaseline='top';lines.forEach(function(l,i){ctx.fillText(l,pad,boxY+pad+i*lh);});canvas.toBlob(function(blob){resolve(new File([blob],file.name,{type:'image/jpeg',lastModified:Date.now()}));},'image/jpeg',0.92);};img.onerror=function(){resolve(file);};img.src=url;});}

    async function processAndPreviewFile(file){hideUploadError();const btn=document.getElementById('uploadSubmitBtn'),label=document.getElementById('uploadSubmitLabel');btn.disabled=true;label.textContent='Memproses...';selectedFile=await stampImage(file);label.textContent='Upload & Selesaikan';showFilePreview(selectedFile);btn.disabled=false;}

    // ── Checklist click
    function handleChecklistClick(event,checklistId,isDone,hasFile,uncheckReason){
        event.preventDefault();event.stopPropagation();
        if(isDone){document.getElementById('uncheck-form-'+checklistId).submit();return;}
        openUploadModal(checklistId,event.currentTarget.querySelector('p.font-semibold')?.textContent?.trim()||'',uncheckReason);
    }

    // ── Upload modal
    function openUploadModal(checklistId,title,uncheckReason){
        uploadChecklistId=checklistId;selectedFile=null;currentLatitude=currentLongitude=currentAddress=null;
        document.getElementById('uploadModalSubtitle').textContent=title||'Checklist item';
        document.getElementById('uploadPreviewArea').classList.add('hidden');
        document.getElementById('uploadErrorMsg').classList.add('hidden');
        document.getElementById('uploadSubmitBtn').disabled=true;
        document.getElementById('uploadSubmitLabel').textContent='Upload & Selesaikan';
        document.getElementById('uploadSubmitLabel').classList.remove('hidden');
        document.getElementById('uploadSubmitSpinner').classList.add('hidden');
        const ra=document.getElementById('uploadReasonArea');
        if(uncheckReason){ra.classList.remove('hidden');document.getElementById('uploadReasonText').textContent=uncheckReason;}else ra.classList.add('hidden');
        ['fileInputGallery','fileInputCamera','fileInputDocument'].forEach(function(id){document.getElementById(id).value='';});
        startLocationWatch();
        const modal=document.getElementById('uploadModal');modal.classList.remove('hidden');modal.classList.add('flex');
    }
    function closeUploadModal(event){if(event&&event.target!==document.getElementById('uploadModal'))return;document.getElementById('uploadModal').classList.add('hidden');document.getElementById('uploadModal').classList.remove('flex');uploadChecklistId=null;selectedFile=null;}
    function triggerFileInput(source){if(source==='camera'){openCameraModal();return;}document.getElementById(source==='gallery'?'fileInputGallery':'fileInputDocument').click();}
    async function handleFileSelected(input){const file=input.files[0];if(!file)return;if(file.size>10*1024*1024){showUploadError('Ukuran file melebihi 10 MB.');input.value='';return;}await processAndPreviewFile(file);}

    // ── Camera
    async function openCameraModal(){const modal=document.getElementById('cameraModal');modal.classList.remove('hidden');modal.classList.add('flex');try{cameraStream=await navigator.mediaDevices.getUserMedia({video:{facingMode:{ideal:'environment'}}});document.getElementById('cameraVideo').srcObject=cameraStream;}catch(err){alert('Tidak dapat mengakses kamera: '+err.message);closeCameraModal();}}
    function closeCameraModal(){if(cameraStream){cameraStream.getTracks().forEach(function(t){t.stop();});cameraStream=null;}document.getElementById('cameraModal').classList.add('hidden');document.getElementById('cameraModal').classList.remove('flex');}
    function capturePhoto(){const video=document.getElementById('cameraVideo'),canvas=document.getElementById('cameraCanvas');canvas.width=video.videoWidth;canvas.height=video.videoHeight;canvas.getContext('2d').drawImage(video,0,0);canvas.toBlob(function(blob){const file=new File([blob],'foto-bukti.jpg',{type:'image/jpeg'});closeCameraModal();processAndPreviewFile(file);},'image/jpeg',0.92);}

    // ── File preview helpers
    function showFilePreview(file){const area=document.getElementById('uploadPreviewArea'),nameEl=document.getElementById('uploadPreviewName'),sizeEl=document.getElementById('uploadPreviewSize'),iconEl=document.getElementById('uploadPreviewIcon'),imgWrap=document.getElementById('imagePreviewWrapper'),imgEl=document.getElementById('imagePreviewEl');nameEl.textContent=file.name;sizeEl.textContent=formatBytes(file.size);const isImage=file.type.startsWith('image/'),isPdf=file.type==='application/pdf';iconEl.textContent=isImage?'🖼️':isPdf?'📄':'📊';if(isImage){const r=new FileReader();r.onload=function(e){imgEl.src=e.target.result;};r.readAsDataURL(file);imgWrap.classList.remove('hidden');}else imgWrap.classList.add('hidden');area.classList.remove('hidden');}
    function clearFileSelection(){selectedFile=null;['fileInputGallery','fileInputCamera','fileInputDocument'].forEach(function(id){document.getElementById(id).value='';});document.getElementById('uploadPreviewArea').classList.add('hidden');document.getElementById('uploadSubmitBtn').disabled=true;}
    function showUploadError(msg){document.getElementById('uploadErrorText').textContent=msg;document.getElementById('uploadErrorMsg').classList.remove('hidden');}
    function hideUploadError(){document.getElementById('uploadErrorMsg').classList.add('hidden');}
    function formatBytes(b){if(b<1024)return b+' B';if(b<1048576)return (b/1024).toFixed(1)+' KB';return (b/1048576).toFixed(1)+' MB';}

    // ── Submit upload
    function submitChecklistFile(){
        if(!selectedFile||!uploadChecklistId)return;
        const btn=document.getElementById('uploadSubmitBtn'),label=document.getElementById('uploadSubmitLabel'),spinner=document.getElementById('uploadSubmitSpinner');
        btn.disabled=true;label.classList.add('hidden');spinner.classList.remove('hidden');hideUploadError();
        const formData=new FormData();formData.append('file',selectedFile);formData.append('_token',document.querySelector('meta[name="csrf-token"]').content);
        if(currentLatitude!==null)formData.append('latitude',currentLatitude);if(currentLongitude!==null)formData.append('longitude',currentLongitude);if(currentAddress!==null)formData.append('address',currentAddress);
        fetch(`/project/checklist/${uploadChecklistId}/upload-file`,{method:'POST',body:formData})
        .then(function(r){return r.json();}).then(function(data){if(!data.success){showUploadError(data.message||'Gagal mengupload file.');btn.disabled=false;label.classList.remove('hidden');spinner.classList.add('hidden');return;}document.getElementById('uploadModal').classList.add('hidden');document.getElementById('uploadModal').classList.remove('flex');window.location.reload();})
        .catch(function(){showUploadError('Terjadi kesalahan.');btn.disabled=false;label.classList.remove('hidden');spinner.classList.add('hidden');});
    }

    // ── Delete file
    function deleteChecklistFile(checklistId){if(!confirm('Hapus file bukti ini?'))return;fetch(`/project/checklist/${checklistId}/delete-file`,{method:'DELETE',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Content-Type':'application/json'}}).then(function(r){return r.json();}).then(function(data){if(data.success)window.location.reload();else alert(data.message||'Gagal menghapus file.');}).catch(function(){alert('Terjadi kesalahan.');});}

    // ── Chat
    function openStaffChat(roomType,roomId,title){
        staffChatRoomType=roomType;staffChatRoomId=roomId;
        document.getElementById('staffChatPanelTitle').textContent=title;
        const panel=document.getElementById('staffChatPanel');panel.classList.remove('hidden');panel.classList.add('flex');
        const url=roomType==='task'?`/chat/room/task/${roomId}`:'/chat/room/global';
        fetch(url).then(r=>r.json()).then(data=>{
            const body=document.getElementById('staffChatMessages');body.innerHTML='';
            (data.messages||[]).forEach(function(m){const isOwn=m.from_user?.id=={{ auth()->id() }};body.innerHTML+=isOwn?`<div class="flex justify-end"><div class="max-w-[70%] bg-indigo-600 text-white rounded-2xl rounded-br-sm px-4 py-3 text-sm break-words">${m.body||''}</div></div>`:`<div class="flex gap-3"><img src="https://i.pravatar.cc/40?u=${m.from_user?.id}" class="w-8 h-8 rounded-full"><div class="max-w-[70%]"><p class="text-xs font-semibold text-slate-600 mb-1">${m.from_user?.name||''}</p><div class="bg-white rounded-2xl rounded-bl-sm px-4 py-3 shadow-sm text-sm text-slate-800 break-words">${m.body||''}</div></div></div>`;});
            body.scrollTop=body.scrollHeight;
        }).catch(()=>{});
    }
    function closeStaffChatPanel(){const p=document.getElementById('staffChatPanel');p.classList.add('hidden');p.classList.remove('flex');}
    function sendStaffChatMessage(){const input=document.getElementById('staffChatInput'),text=input.value.trim();if(!text)return;const payload={body:text,room_type:staffChatRoomType};if(staffChatRoomType==='task')payload.task_id=staffChatRoomId;fetch('/chat/message',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json'},body:JSON.stringify(payload)}).then(r=>r.json()).then(data=>{if(data.success){input.value='';openStaffChat(staffChatRoomType,staffChatRoomId,document.getElementById('staffChatPanelTitle').textContent);}});}
    </script>
    


    {{-- HEADER --}}
   

<script>
function toggleSupAssignment(rowId, tr) {
    const row = document.getElementById(rowId);
    if (!row) return;
    const arrow = tr.querySelector('.sup-a-arrow');
    row.classList.toggle('hidden');
    if (arrow) arrow.style.transform = row.classList.contains('hidden') ? '' : 'rotate(90deg)';
}
</script>
@endsection
