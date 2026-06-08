@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- ============================== --}}
    {{-- HERO CARD --}}
    {{-- ============================== --}}
    <div class="relative overflow-hidden rounded-[32px] bg-gradient-to-br from-[#0d1b3e] via-[#132850] to-[#0b1120] p-6 md:p-10 shadow-2xl">

        {{-- BG BLOBS --}}
        <div class="absolute top-0 right-0 w-80 h-80 bg-cyan-400/10 blur-3xl rounded-full pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-indigo-500/10 blur-3xl rounded-full pointer-events-none"></div>

        <div class="relative z-10">

            {{-- HEADER --}}
            <div class="flex flex-col xl:flex-row xl:items-start xl:justify-between gap-6">
                <div class="max-w-xl">
                    <div class="inline-flex items-center gap-2 bg-white/10 border border-white/10 text-white px-4 py-2 rounded-2xl text-sm backdrop-blur-xl">
                        <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                        Dashboard Aktif
                    </div>
                    <h1 class="mt-4 text-3xl md:text-4xl font-black text-white leading-tight">
                        Project Dashboard
                    </h1>
                    <p class="mt-2 text-slate-300 text-sm leading-relaxed">
                        Ringkasan proyek dan aktivitas tugas {{ $scopeTitle ?? 'Anda' }}.
                    </p>
                </div>

                {{-- STAT PILLS --}}
                <div class="flex flex-wrap gap-3">
                    <div class="bg-white/10 backdrop-blur-xl border border-white/10 rounded-2xl px-5 py-3 text-center min-w-[110px]">
                        <div class="text-xs text-slate-300 uppercase tracking-wider mb-1">Checklist</div>
                        <div class="text-2xl font-black text-white">{{ $checklistCount }}</div>
                        <div class="text-xs text-cyan-400 font-semibold mt-1">Total item</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-xl border border-white/10 rounded-2xl px-5 py-3 text-center min-w-[110px]">
                        <div class="text-xs text-slate-300 uppercase tracking-wider mb-1">Selesai</div>
                        <div class="text-2xl font-black text-white">{{ $doneChecklistCount }}</div>
                        <div class="text-xs text-green-400 font-semibold mt-1">Done</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-xl border border-white/10 rounded-2xl px-5 py-3 text-center min-w-[110px]">
                        <div class="text-xs text-slate-300 uppercase tracking-wider mb-1">Proses</div>
                        <div class="text-2xl font-black text-white">{{ $progressChecklistCount }}</div>
                        <div class="text-xs text-yellow-400 font-semibold mt-1">On Progress</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-xl border border-white/10 rounded-2xl px-5 py-3 text-center min-w-[110px]">
                        <div class="text-xs text-slate-300 uppercase tracking-wider mb-1">Manpower</div>
                        <div class="text-2xl font-black text-white">{{ $activeStaffCount }}</div>
                        <div class="text-xs text-pink-400 font-semibold mt-1">Org. Bertugas</div>
                    </div>
                </div>
            </div>

            {{-- DROPDOWNS --}}
            <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white/10 border border-white/10 rounded-2xl p-4 backdrop-blur-xl">
                    <label class="block text-xs text-slate-300 uppercase tracking-wider mb-2 font-semibold">📁 Filter Project</label>
                    <select id="filterProject" class="w-full rounded-xl border border-white/20 bg-slate-900/60 text-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option value="">Semua Project</option>
                        @foreach($projectCards as $project)
                            <option value="{{ $project['id'] }}">{{ $project['title'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="bg-white/10 border border-white/10 rounded-2xl p-4 backdrop-blur-xl">
                    <label class="block text-xs text-slate-300 uppercase tracking-wider mb-2 font-semibold">📋 Filter Task</label>
                    <select id="filterTask" class="w-full rounded-xl border border-white/20 bg-slate-900/60 text-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option value="">Semua Status Task</option>
                        <option value="pending">Pending</option>
                        <option value="progress">On Progress</option>
                        <option value="done">Done</option>
                    </select>
                </div>
                <div class="bg-white/10 border border-white/10 rounded-2xl p-4 backdrop-blur-xl">
                    <label class="block text-xs text-slate-300 uppercase tracking-wider mb-2 font-semibold">✅ Filter Checklist</label>
                    <select id="filterChecklist" class="w-full rounded-xl border border-white/20 bg-slate-900/60 text-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option value="">Semua Checklist</option>
                        <option value="0">Belum Selesai</option>
                        <option value="1">Sudah Selesai</option>
                    </select>
                </div>
            </div>

        </div>
    </div>

    {{-- ============================== --}}
    {{-- STATS SUMMARY --}}
    {{-- ============================== --}}
    <!-- <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        <div class="bg-white rounded-3xl border border-slate-100 p-5 shadow-sm hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm">Projects</p>
                    <h2 class="mt-2 text-3xl font-black text-slate-800">{{ $projectCount }}</h2>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center text-2xl">📁</div>
            </div>
            <div class="mt-4 text-blue-500 text-sm font-semibold">Project aktif</div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 p-5 shadow-sm hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm">Tasks</p>
                    <h2 class="mt-2 text-3xl font-black text-slate-800">{{ $taskCount }}</h2>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-orange-100 flex items-center justify-center text-2xl">📋</div>
            </div>
            <div class="mt-4 text-orange-500 text-sm font-semibold">Total assignment</div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 p-5 shadow-sm hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm">Checklist</p>
                    <h2 class="mt-2 text-3xl font-black text-slate-800">{{ $checklistCount }}</h2>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center text-2xl">✅</div>
            </div>
            <div class="mt-4 text-slate-500 text-sm font-semibold">
                {{ $doneChecklistCount }} / {{ $checklistCount }} selesai
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 p-5 shadow-sm hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm">Manpower Aktif</p>
                    <h2 class="mt-2 text-3xl font-black text-slate-800">{{ $activeStaffCount }}</h2>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-emerald-100 flex items-center justify-center text-2xl">👥</div>
            </div>
            <div class="mt-4 text-emerald-500 text-sm font-semibold">Sedang bertugas</div>
        </div>

    </div> -->

    {{-- ============================== --}}
    {{-- TOP 5 DEADLINE PROJECT --}}
    {{-- ============================== --}}
    <div class="bg-white rounded-[32px] border border-slate-100 p-6 shadow-sm">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
            <div>
                <h2 class="text-xl font-black text-slate-800">🗓️ 5 Project Deadline Terdekat</h2>
                <p class="text-slate-500 text-sm mt-1">Project yang paling cepat mendekati deadline.</p>
            </div>
            <div class="flex gap-2">
                <button class="btn-deadline-filter active h-9 px-4 rounded-xl border text-sm transition-all" data-filter="all">Semua</button>
                <button class="btn-deadline-filter h-9 px-4 rounded-xl border text-sm transition-all" data-filter="progress">On Progress</button>
                <button class="btn-deadline-filter h-9 px-4 rounded-xl border text-sm transition-all" data-filter="done">Selesai</button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @forelse($projectCards as $project)
                @php
                    $pct       = $project['progress'] ?? 0;
                    $total     = $project['total_task'] ?? 0;
                    $done      = $project['done_task'] ?? 0;
                    $daysLeft  = \Carbon\Carbon::parse($project['deadline'])->diffInDays(now(), false);
                    $isLate    = $daysLeft > 0;
                    $label     = $pct >= 100 ? 'done' : 'progress';
                    $barColor  = $pct >= 100 ? 'bg-emerald-500' : ($isLate ? 'bg-red-500' : 'bg-blue-500');
                    $badgeColor= $pct >= 100
                        ? 'bg-emerald-100 text-emerald-700'
                        : ($isLate ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700');
                    $badgeText = $pct >= 100 ? 'Selesai' : ($isLate ? 'Terlambat' : 'On Progress');
                @endphp
                @php $cardId = 'project-detail-' . $loop->index; @endphp
                <div class="deadline-card rounded-2xl border border-slate-200 bg-white transition-all" data-status="{{ $label }}">

                    {{-- CARD HEADER — clickable --}}
                    <div class="p-5 cursor-pointer select-none" onclick="toggleCard('{{ $cardId }}', this)">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-slate-800 truncate">{{ $project['title'] }}</h3>
                                <p class="text-xs text-slate-400 mt-0.5">{{ $project['divisi'] ?? '-' }}</p>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $badgeColor }}">{{ $badgeText }}</span>
                                <span class="card-toggle-icon text-slate-400 text-xs transition-transform">▶</span>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center justify-between text-xs text-slate-500">
                            <span>Deadline: <strong class="text-slate-700">{{ \Carbon\Carbon::parse($project['deadline'])->format('d M Y') }}</strong></span>
                            <span>{{ $done }} / {{ $total }} task</span>
                        </div>
                        <div class="mt-2 h-2.5 rounded-full bg-slate-200 overflow-hidden">
                            <div class="h-full rounded-full {{ $barColor }} transition-all" style="width: {{ $pct }}%"></div>
                        </div>
                        <div class="mt-1 text-right text-xs font-semibold text-slate-500">{{ $pct }}%</div>
                    </div>

                    {{-- EXPANDABLE DETAIL --}}
                    <div id="{{ $cardId }}" class="hidden border-t border-slate-100 px-5 pb-5 pt-4 bg-slate-50/60 rounded-b-2xl">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Detail Assignment</p>
                        @if(count($project['assignment_detail']) === 0)
                            <p class="text-xs text-slate-400 italic">Tidak ada assignment.</p>
                        @else
                            <div class="space-y-3">
                                @foreach($project['assignment_detail'] as $ad)
                                    @php
                                        $adClPct = $ad['total_cl'] > 0 ? round(($ad['done_cl'] / $ad['total_cl']) * 100) : 0;
                                        $adBadge = match($ad['status']) {
                                            'done'     => 'bg-emerald-100 text-emerald-700',
                                            'progress' => 'bg-yellow-100 text-yellow-700',
                                            default    => 'bg-slate-200 text-slate-600',
                                        };
                                    @endphp
                                    <div class="rounded-xl border border-slate-200 bg-white overflow-hidden">
                                        <div class="flex items-center justify-between gap-3 px-4 py-3 border-b border-slate-100">
                                            <div class="flex items-center gap-2 flex-1 min-w-0">
                                                <span class="text-xs font-bold px-2 py-0.5 rounded-full {{ $adBadge }}">{{ strtoupper($ad['status']) }}</span>
                                                <span class="font-semibold text-slate-800 text-sm truncate">{{ $ad['user_name'] }}</span>
                                            </div>
                                            <div class="flex items-center gap-2 shrink-0 text-xs text-slate-500">
                                                <span>{{ $ad['done_cl'] }}/{{ $ad['total_cl'] }} CL</span>
                                                <div class="w-16 h-1.5 rounded-full bg-slate-200 overflow-hidden">
                                                    <div class="h-full rounded-full bg-blue-400" style="width: {{ $adClPct }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                        @if(count($ad['checklists']) > 0)
                                            <div class="px-4 py-2 grid grid-cols-1 sm:grid-cols-2 gap-1">
                                                @foreach($ad['checklists'] as $cl)
                                                    <div class="flex items-center gap-2 text-xs py-1">
                                                        @if($cl['is_done'])
                                                            <span class="w-4 h-4 rounded-full bg-emerald-500 flex items-center justify-center text-white shrink-0" style="font-size:9px">✓</span>
                                                            <span class="text-slate-400 line-through">{{ $cl['name'] }}</span>
                                                        @else
                                                            <span class="w-4 h-4 rounded-full border-2 border-slate-300 shrink-0"></span>
                                                            <span class="text-slate-700">{{ $cl['name'] }}</span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="px-4 py-2 text-xs text-slate-400 italic">Tidak ada checklist.</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                </div>
            @empty
                <div class="col-span-full rounded-2xl border border-slate-200 bg-slate-50 p-8 text-center text-slate-400">
                    Belum ada project dengan deadline terdekat.
                </div>
            @endforelse
        </div>

    </div>

    {{-- ============================== --}}
    {{-- MAIN GRID --}}
    {{-- ============================== --}}
    <div class="grid grid-cols-12 gap-6">

        {{-- LEFT: MAP --}}
        <div class="col-span-12 xl:col-span-8 space-y-6">

            <div class="bg-white rounded-[32px] border border-slate-100 overflow-hidden shadow-sm">
                <div class="p-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-black text-slate-800">🗺️ Monitoring Map</h2>
                        <p class="text-slate-500 text-sm mt-1">Lokasi aktivitas konservasi secara real-time.</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-3 py-1.5 rounded-xl bg-green-100 text-green-700 text-xs font-semibold">● Selesai</span>
                        <span class="px-3 py-1.5 rounded-xl bg-yellow-100 text-yellow-700 text-xs font-semibold">● On Progress</span>
                        <span class="px-3 py-1.5 rounded-xl bg-red-100 text-red-700 text-xs font-semibold">● Terlambat</span>
                    </div>
                </div>
                <div class="relative h-[300px] lg:h-[400px]">
                    <div id="activityMap" class="w-full h-full"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent pointer-events-none"></div>
                    <div class="absolute bottom-4 left-4 bg-white/90 backdrop-blur-xl rounded-2xl px-4 py-3 shadow-lg">
                        <div class="flex items-center gap-3">
                            <div>
                                <p class="font-bold text-slate-800 text-sm">Conservation Zone Active</p>
                                <p class="text-xs text-slate-500">Last update 2 mins ago</p>
                            </div>
                            <div class="w-3 h-3 rounded-full bg-green-500 animate-pulse"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- RIGHT: ACTIVITY --}}
        <div class="col-span-12 xl:col-span-4 space-y-6">

            <div class="bg-white rounded-[32px] border border-slate-100 p-6 shadow-sm">
                <div class="mb-5">
                    <h2 class="text-xl font-black text-slate-800">📅 Aktivitas Hari Ini</h2>
                    <p class="text-slate-500 text-sm mt-1">Status tugas terbaru per karyawan.</p>
                </div>

                <div class="flex flex-wrap gap-2 mb-4">
                    <button class="filter-activity active h-9 px-3 rounded-xl border text-sm transition-all" data-filter="all">Semua</button>
                    <button class="filter-activity h-9 px-3 rounded-xl border text-sm transition-all" data-filter="progress">On Progress</button>
                    <button class="filter-activity h-9 px-3 rounded-xl border text-sm transition-all" data-filter="pending">No Progress</button>
                </div>

                <div class="space-y-3 max-h-[520px] overflow-y-auto pr-1">
                    @forelse($activityEntries as $entry)
                        @php
                            $totalCL   = $entry->checklists->count();
                            $doneCL    = $entry->checklists->where('is_done', 1)->count();
                            $allCLDone = ($totalCL > 0 && $doneCL === $totalCL) ? '1' : '0';
                        @endphp
                        @php $actId = 'act-detail-' . $loop->index; @endphp
                        <div class="activity-row rounded-2xl border border-slate-200 bg-white transition-all"
                             data-status="{{ $entry->status }}"
                             data-task-id="{{ $entry->task_id ?? '' }}"
                             data-checklist-done="{{ $allCLDone }}">

                            {{-- HEADER — clickable --}}
                            <div class="p-4 cursor-pointer select-none" onclick="toggleCard('{{ $actId }}', this)">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-slate-800 text-sm truncate">{{ $entry->task->title ?? 'Task tidak ditemukan' }}</p>
                                        <p class="text-xs text-slate-400 mt-0.5">{{ $entry->user->name ?? 'Unknown' }} · {{ $entry->task->divisi ?? '-' }}</p>
                                    </div>
                                    <div class="flex items-center gap-1.5 shrink-0">
                                        <span class="text-xs font-bold px-2.5 py-1 rounded-full
                                            {{ $entry->status === 'done' ? 'bg-emerald-100 text-emerald-700' : ($entry->status === 'progress' ? 'bg-yellow-100 text-yellow-700' : 'bg-slate-200 text-slate-600') }}">
                                            {{ strtoupper($entry->status) }}
                                        </span>
                                        <span class="card-toggle-icon text-slate-400 text-xs transition-transform">▶</span>
                                    </div>
                                </div>
                                <div class="mt-2 flex items-center justify-between text-xs text-slate-500">
                                    <span>Deadline: {{ optional($entry->deadline)->format('d M Y') ?? '-' }}</span>
                                    <span>✅ {{ $doneCL }}/{{ $totalCL }} checklist</span>
                                </div>
                            </div>

                            {{-- EXPANDABLE CHECKLIST --}}
                            <div id="{{ $actId }}" class="hidden border-t border-slate-100 px-4 pb-4 pt-3 bg-slate-50/60 rounded-b-2xl">
                                @if($entry->checklists->isEmpty())
                                    <p class="text-xs text-slate-400 italic">Tidak ada checklist.</p>
                                @else
                                    <div class="grid grid-cols-1 gap-1">
                                        @foreach($entry->checklists as $cl)
                                            <div class="flex items-center gap-2 text-xs py-1">
                                                @if($cl->is_done)
                                                    <span class="w-4 h-4 rounded-full bg-emerald-500 flex items-center justify-center text-white shrink-0" style="font-size:9px">✓</span>
                                                    <span class="text-slate-400 line-through">{{ $cl->name ?? $cl->title ?? $cl->description ?? 'Item #' . $cl->id }}</span>
                                                @else
                                                    <span class="w-4 h-4 rounded-full border-2 border-slate-300 shrink-0"></span>
                                                    <span class="text-slate-700">{{ $cl->name ?? $cl->title ?? $cl->description ?? 'Item #' . $cl->id }}</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                        </div>
                    @empty
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6 text-center text-slate-400 text-sm">
                            Tidak ada aktivitas hari ini.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

    {{-- ============================== --}}
    {{-- EMPLOYEE PERFORMANCE TABLE --}}
    {{-- ============================== --}}
    <div class="bg-white rounded-[32px] border border-slate-100 p-6 shadow-sm">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
            <div>
                <h2 class="text-xl font-black text-slate-800">👤 Performa Karyawan</h2>
                <p class="text-slate-500 text-sm mt-1">Detail tugas, checklist, dan SLA per karyawan.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <button class="btn-emp-filter active h-9 px-4 rounded-xl border text-sm" data-filter="all">Semua</button>
                <button class="btn-emp-filter h-9 px-4 rounded-xl border text-sm" data-filter="progress">On Progress</button>
                <button class="btn-emp-filter h-9 px-4 rounded-xl border text-sm" data-filter="noprogress">No Progress</button>
            </div>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-slate-100">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                        <th class="px-3 py-3 text-left font-semibold w-8"></th>
                        <th class="px-5 py-3 text-left font-semibold">Karyawan</th>
                        <th class="px-4 py-3 text-center font-semibold">Project</th>
                        <th class="px-4 py-3 text-center font-semibold">Total Task</th>
                        <th class="px-4 py-3 text-center font-semibold">Done</th>
                        <th class="px-4 py-3 text-center font-semibold">On Progress</th>
                        <th class="px-4 py-3 text-center font-semibold">Pending</th>
                        <th class="px-4 py-3 text-center font-semibold">Task %</th>
                        <th class="px-4 py-3 text-center font-semibold">Checklist</th>
                        <th class="px-4 py-3 text-center font-semibold">CL Selesai</th>
                        <th class="px-4 py-3 text-center font-semibold">Avg. Waktu</th>
                        <th class="px-4 py-3 text-center font-semibold">SLA</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($employeeStats as $empIdx => $emp)
                        @php
                            $taskPct     = $emp['task_count'] > 0 ? round(($emp['done_tasks'] / $emp['task_count']) * 100) : 0;
                            $hasProgress = $emp['progress_tasks'] > 0;
                            $rowId       = 'emp-detail-' . $empIdx;
                        @endphp

                        {{-- SUMMARY ROW --}}
                        <tr class="emp-row hover:bg-slate-50 transition-colors cursor-pointer"
                            data-progress="{{ $hasProgress ? 'yes' : 'no' }}"
                            onclick="toggleEmpDetail('{{ $rowId }}', this)">

                            {{-- Toggle icon --}}
                            <td class="px-3 py-4 text-center">
                                <span class="toggle-icon inline-flex items-center justify-center w-6 h-6 rounded-full bg-slate-100 text-slate-500 text-xs font-bold transition-transform">▶</span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-semibold text-slate-800">{{ $emp['employee']->name }}</div>
                                <div class="text-xs text-slate-400">{{ $emp['employee']->divisi ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-4 text-center font-semibold text-slate-700">{{ $emp['project_count'] }}</td>
                            <td class="px-4 py-4 text-center font-semibold text-slate-700">{{ $emp['task_count'] }}</td>
                            <td class="px-4 py-4 text-center">
                                <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 font-semibold text-xs">{{ $emp['done_tasks'] }}</span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-700 font-semibold text-xs">{{ $emp['progress_tasks'] }}</span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="px-2 py-0.5 rounded-full bg-slate-200 text-slate-600 font-semibold text-xs">{{ $emp['pending_tasks'] }}</span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <div class="flex flex-col items-center gap-1">
                                    <span class="text-xs font-bold text-slate-700">{{ $taskPct }}%</span>
                                    <div class="w-20 h-1.5 rounded-full bg-slate-200 overflow-hidden">
                                        <div class="h-full rounded-full bg-blue-500" style="width: {{ $taskPct }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-center font-semibold text-slate-700">{{ $emp['total_checklist'] }}</td>
                            <td class="px-4 py-4 text-center">
                                <span class="px-2 py-0.5 rounded-full bg-cyan-100 text-cyan-700 font-semibold text-xs">{{ $emp['done_checklist'] }}</span>
                            </td>
                            <td class="px-4 py-4 text-center">
    @if($emp['avg_duration'] !== null)
        <div class="flex flex-col items-center gap-1">
            <span class="text-xs font-bold text-slate-700">
                {{ $emp['avg_duration'] >= 24
                    ? round($emp['avg_duration'] / 24, 1) . ' hr'
                    : $emp['avg_duration'] . ' jam' }}
            </span>
            <span class="text-[10px] text-slate-400">dari task done</span>
        </div>
    @else
        <span class="text-xs text-slate-400">—</span>
    @endif
</td>
                            <td class="px-4 py-4 text-center">
                                <div class="flex flex-col items-center gap-1">
                                    <span class="text-xs font-bold {{ $emp['sla'] >= 80 ? 'text-emerald-600' : ($emp['sla'] >= 50 ? 'text-yellow-600' : 'text-red-500') }}">
                                        {{ $emp['sla'] }}%
                                    </span>
                                    <div class="w-20 h-1.5 rounded-full bg-slate-200 overflow-hidden">
                                        <div class="h-full rounded-full {{ $emp['sla'] >= 80 ? 'bg-emerald-500' : ($emp['sla'] >= 50 ? 'bg-yellow-500' : 'bg-red-500') }}"
                                             style="width: {{ $emp['sla'] }}%"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        {{-- DETAIL EXPAND ROW --}}
                        <tr id="{{ $rowId }}" class="hidden bg-slate-50/80">
                            <td colspan="12" class="px-6 py-4">

                                {{-- Judul --}}
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">
                                    Detail Task & Checklist — {{ $emp['employee']->name }}
                                </p>

                                @if(count($emp['task_detail']) === 0)
                                    <p class="text-sm text-slate-400 italic">Tidak ada task.</p>
                                @else
                                    <div class="space-y-3">
                                        @foreach($emp['task_detail'] as $td)
                                            @php
                                                $clPct = $td['total_cl'] > 0 ? round(($td['done_cl'] / $td['total_cl']) * 100) : 0;
                                                $statusColor = match($td['status']) {
                                                    'done'     => 'bg-emerald-100 text-emerald-700',
                                                    'progress' => 'bg-yellow-100 text-yellow-700',
                                                    default    => 'bg-slate-200 text-slate-600',
                                                };
                                            @endphp

                                            <div class="rounded-xl border border-slate-200 bg-white overflow-hidden">

                                                {{-- Task header --}}
                                                <div class="flex items-center justify-between gap-3 px-4 py-3 border-b border-slate-100">
                                                    <div class="flex items-center gap-3 flex-1 min-w-0">
                                                        <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $statusColor }}">
                                                            {{ strtoupper($td['status']) }}
                                                        </span>
                                                        <div class="min-w-0">
                                                            <p class="font-semibold text-slate-800 text-sm truncate">{{ $td['task_title'] }}</p>
                                                            <p class="text-xs text-slate-400">{{ $td['task_divisi'] }} · Deadline: {{ $td['deadline'] ? \Carbon\Carbon::parse($td['deadline'])->format('d M Y') : '-' }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center gap-3 shrink-0 text-xs text-slate-500">
                                                        <span class="font-semibold text-slate-700">{{ $td['done_cl'] }}/{{ $td['total_cl'] }} CL</span>
                                                        <div class="w-24 h-1.5 rounded-full bg-slate-200 overflow-hidden">
                                                            <div class="h-full rounded-full bg-blue-400" style="width: {{ $clPct }}%"></div>
                                                        </div>
                                                        <span class="font-bold text-slate-700">{{ $clPct }}%</span>
                                                    </div>
                                                </div>

                                                {{-- Checklist items --}}
                                                @if(count($td['checklists']) > 0)
                                                    <div class="px-4 py-2 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-1.5">
                                                        @foreach($td['checklists'] as $cl)
                                                            <div class="flex items-center gap-2 text-xs py-1">
                                                                @if($cl['is_done'])
                                                                    <span class="w-4 h-4 rounded-full bg-emerald-500 flex items-center justify-center text-white shrink-0" style="font-size:9px">✓</span>
                                                                    <span class="text-slate-500 line-through">{{ $cl['name'] }}</span>
                                                                @else
                                                                    <span class="w-4 h-4 rounded-full border-2 border-slate-300 shrink-0"></span>
                                                                    <span class="text-slate-700">{{ $cl['name'] }}</span>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <p class="px-4 py-2 text-xs text-slate-400 italic">Tidak ada checklist.</p>
                                                @endif

                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="11" class="px-5 py-10 text-center text-slate-400">Belum ada data karyawan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>

{{-- ============================== --}}
{{-- LEAFLET MAP --}}
{{-- ============================== --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
const dashboardLocations = @json($dashboardLocations);
console.log('MAP DATA');
console.log(dashboardLocations);
document.addEventListener('DOMContentLoaded', function () {

    /* ── MAP INIT ── */
    const mapEl = document.getElementById('activityMap');
    if (!mapEl) return;

    const map = L.map('activityMap', { zoomControl: true }).setView([-6.2088, 106.8456], 5);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap Contributors',
        maxZoom: 19,
    }).addTo(map);

    /* ── CUSTOM MARKER ICONS (pin style) ── */
    function makeIcon(color) {
        return L.divIcon({
            className: '',
            html: `<svg xmlns="http://www.w3.org/2000/svg" width="28" height="36" viewBox="0 0 28 36">
                <path d="M14 0C6.268 0 0 6.268 0 14c0 9.333 14 22 14 22S28 23.333 28 14C28 6.268 21.732 0 14 0z"
                      fill="${color}" stroke="#fff" stroke-width="1.5"/>
                <circle cx="14" cy="14" r="6" fill="#fff" opacity="0.9"/>
            </svg>`,
            iconSize: [28, 36],
            iconAnchor: [14, 36],
            popupAnchor: [0, -38],
        });
    }
const coordinateCount = {};
    const iconDaily = makeIcon('#6366f1'); // indigo — daily routine
    const iconTask  = makeIcon('#8b5cf6'); // violet — task checklist

    /* ── MARKERS ── */
    const bounds = [];
/* ==========================
   HANDLE OVERLAPPING MARKER
========================== */
const coordinateCounter = {};

dashboardLocations.forEach(function (item) {

    if (
        item.lat === null ||
        item.lat === undefined ||
        item.lng === null ||
        item.lng === undefined ||
        isNaN(parseFloat(item.lat)) ||
        isNaN(parseFloat(item.lng))
    ) {
        return;
    }

    item.lat = parseFloat(item.lat);
    item.lng = parseFloat(item.lng);

    /* ------------------------
       OFFSET JIKA TITIK SAMA
    ------------------------ */
    const coordKey = `${item.lat}_${item.lng}`;

    if (!coordinateCounter[coordKey]) {
        coordinateCounter[coordKey] = 0;
    }

    const offsetIndex = coordinateCounter[coordKey];
    coordinateCounter[coordKey]++;

    // geser melingkar
    const radius = 0.00008; // ±8 meter

    const angle = offsetIndex * 45;

    const latOffset =
        item.lat +
        (radius * Math.cos(angle * Math.PI / 180));

    const lngOffset =
        item.lng +
        (radius * Math.sin(angle * Math.PI / 180));

    /* ------------------------
       STYLE POPUP
    ------------------------ */
    const isTask = item.source === 'task';

    const badgeBg = isTask
        ? '#ede9fe'
        : '#e0e7ff';

    const badgeTxt = isTask
        ? '#5b21b6'
        : '#3730a3';

    const badgeLabel = isTask
        ? (
            item.task_name &&
            item.task_name !== '-'
                ? item.task_name
                : 'Task Checklist'
        )
        : 'Daily Routine';

    let fotoHtml = '';

    if (item.file_path) {

        const isImage =
            item.file_type &&
            item.file_type.startsWith('image/');

        fotoHtml = isImage
            ? `
            <div style="margin-top:8px">
                <img
                    src="/storage/${item.file_path}"
                    style="
                        width:100%;
                        max-width:220px;
                        border-radius:8px;
                        object-fit:cover;
                        max-height:140px;
                        cursor:pointer
                    "
                    onclick="window.open('/storage/${item.file_path}','_blank')"
                >
            </div>
            `
            : `
            <div style="margin-top:8px">
                <a
                    href="/storage/${item.file_path}"
                    target="_blank"
                    style="font-size:12px;color:#3b82f6"
                >
                    📎 Lihat File Lampiran
                </a>
            </div>
            `;
    }

    const taskLine =
        item.task_name &&
        item.task_name !== '-'
            ? `
            <div style="
                color:#64748b;
                font-size:12px;
                margin-bottom:2px
            ">
                📋 ${item.task_name}
            </div>
            `
            : '';

    /* ------------------------
       MARKER
    ------------------------ */
    const marker = L.marker(
        [latOffset, lngOffset],
        {
            icon: isTask
                ? iconTask
                : iconDaily
        }
    ).addTo(map);

    marker.bindPopup(
        `
        <div style="
            min-width:230px;
            font-family:sans-serif;
            font-size:13px;
            line-height:1.6
        ">
            <div style="
                display:inline-block;
                background:${badgeBg};
                color:${badgeTxt};
                font-size:10px;
                font-weight:700;
                padding:2px 8px;
                border-radius:20px;
                text-transform:uppercase;
                letter-spacing:.05em;
                margin-bottom:6px
            ">
                ${badgeLabel}
            </div>

            <div style="
                font-weight:700;
                font-size:14px;
                color:#1e293b;
                margin-bottom:4px;
                word-break:break-word
            ">
                ${item.title ?? '-'}
            </div>

            <div style="
                color:#64748b;
                font-size:12px;
                margin-bottom:2px
            ">
                👤 <strong>${item.user_name}</strong>
            </div>

            ${taskLine}

            <div style="
                color:#64748b;
                font-size:12px;
                margin-bottom:2px
            ">
                📍 ${item.address ?? '-'}
            </div>

            <div style="
                color:#94a3b8;
                font-size:11px
            ">
                🕒 ${item.created ?? '-'}
            </div>

            ${fotoHtml}
        </div>
        `,
        {
            maxWidth: 270
        }
    );

    bounds.push([latOffset, lngOffset]);

});
    /* ── FIT BOUNDS ── */
    if (bounds.length > 0) {
        map.fitBounds(bounds, { padding: [50, 50] });
    } else {
        map.setView([-6.2088, 106.8456], 10);
        L.popup()
            .setLatLng([-6.2088, 106.8456])
            .setContent('Belum ada data lokasi')
            .openOn(map);
    }

    setTimeout(() => map.invalidateSize(), 400);
});

/* ---- FILTER SYSTEM ---- */
document.addEventListener('DOMContentLoaded', function () {

    var activeDropdown = { project: '', task: '', checklist: '' };

    var selProject   = document.getElementById('filterProject');
    var selTask      = document.getElementById('filterTask');
    var selChecklist = document.getElementById('filterChecklist');

    function runActivityFilter() {
        var pId  = activeDropdown.project;
        var task = activeDropdown.task;
        var cl   = activeDropdown.checklist;

        document.querySelectorAll('.activity-row').forEach(function (row) {
            var matchProject   = !pId  || row.dataset.taskId === pId;
            var matchTask      = !task || row.dataset.status === task;
            var matchChecklist = !cl   || row.dataset.checklistDone === cl;
            row.style.display  = (matchProject && matchTask && matchChecklist) ? '' : 'none';
        });
    }

    if (selProject)   selProject.addEventListener('change',   function () { activeDropdown.project   = this.value; runActivityFilter(); });
    if (selTask)      selTask.addEventListener('change',      function () { activeDropdown.task       = this.value; runActivityFilter(); });
    if (selChecklist) selChecklist.addEventListener('change', function () { activeDropdown.checklist  = this.value; runActivityFilter(); });

    /* ── BUTTON FILTERS ── */
    function setupFilter(btnSelector, rowSelector, matchFn) {
        var buttons = document.querySelectorAll(btnSelector);
        if (!buttons.length) return;

        function setActive(activeBtn) {
            buttons.forEach(function (b) {
                b.style.background  = '#f8fafc';
                b.style.color       = '#374151';
                b.style.borderColor = '#e2e8f0';
                b.style.fontWeight  = '500';
            });
            activeBtn.style.background  = '#0f172a';
            activeBtn.style.color       = '#ffffff';
            activeBtn.style.borderColor = '#0f172a';
            activeBtn.style.fontWeight  = '700';
        }

        function applyRows(filterValue) {
            document.querySelectorAll(rowSelector).forEach(function (row) {
                row.style.display = matchFn(filterValue, row) ? '' : 'none';
            });
        }

        setActive(buttons[0]);
        buttons.forEach(function (btn) {
            btn.addEventListener('click', function () { setActive(this); applyRows(this.dataset.filter); });
        });
    }

    /* Activity button filter */
    var activityBtns = document.querySelectorAll('.filter-activity');
    var activeActivityFilter = 'all';

    function setActivityBtnActive(activeBtn) {
        activityBtns.forEach(function (b) {
            b.style.background  = '#f8fafc';
            b.style.color       = '#374151';
            b.style.borderColor = '#e2e8f0';
            b.style.fontWeight  = '500';
        });
        activeBtn.style.background  = '#0f172a';
        activeBtn.style.color       = '#ffffff';
        activeBtn.style.borderColor = '#0f172a';
        activeBtn.style.fontWeight  = '700';
    }

    if (activityBtns.length) {
        setActivityBtnActive(activityBtns[0]);
        activityBtns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                activeActivityFilter = this.dataset.filter;
                setActivityBtnActive(this);
                document.querySelectorAll('.activity-row').forEach(function (row) {
                    var pId  = activeDropdown.project;
                    var cl   = activeDropdown.checklist;
                    var matchProject   = !pId || row.dataset.taskId === pId;
                    var matchChecklist = !cl  || row.dataset.checklistDone === cl;
                    var matchStatus    = activeActivityFilter === 'all'      ? true
                                      : activeActivityFilter === 'progress' ? row.dataset.status === 'progress'
                                      : activeActivityFilter === 'pending'  ? row.dataset.status === 'pending'
                                      : true;
                    row.style.display = (matchProject && matchChecklist && matchStatus) ? '' : 'none';
                });
            });
        });
    }

    /* Deadline card filter */
    setupFilter('.btn-deadline-filter', '.deadline-card', function (filter, row) {
        if (filter === 'all') return true;
        return row.dataset.status === filter;
    });

    /* Employee table filter */
    setupFilter('.btn-emp-filter', '.emp-row', function (filter, row) {
        if (filter === 'all')        return true;
        if (filter === 'progress')   return row.dataset.progress === 'yes';
        if (filter === 'noprogress') return row.dataset.progress === 'no';
        return true;
    });
});

/* ---- CARD TOGGLE ---- */
function toggleCard(detailId, headerEl) {
    var detail   = document.getElementById(detailId);
    if (!detail) return;
    var icon     = headerEl.querySelector('.card-toggle-icon');
    var isHidden = detail.classList.contains('hidden');
    if (isHidden) {
        detail.classList.remove('hidden');
        if (icon) { icon.style.transform = 'rotate(90deg)'; icon.style.color = '#0f172a'; }
        headerEl.parentElement.style.borderColor = '#cbd5e1';
    } else {
        detail.classList.add('hidden');
        if (icon) { icon.style.transform = ''; icon.style.color = ''; }
        headerEl.parentElement.style.borderColor = '';
    }
}

/* ---- EMPLOYEE ROW TOGGLE ---- */
function toggleEmpDetail(rowId, triggerRow) {
    var detailRow = document.getElementById(rowId);
    if (!detailRow) return;
    var icon     = triggerRow.querySelector('.toggle-icon');
    var isHidden = detailRow.classList.contains('hidden');
    if (isHidden) {
        detailRow.classList.remove('hidden');
        if (icon) { icon.style.transform = 'rotate(90deg)'; icon.style.background = '#0f172a'; icon.style.color = '#fff'; }
        triggerRow.style.background = '#f8fafc';
    } else {
        detailRow.classList.add('hidden');
        if (icon) { icon.style.transform = ''; icon.style.background = ''; icon.style.color = ''; }
        triggerRow.style.background = '';
    }
}
</script>

@endsection