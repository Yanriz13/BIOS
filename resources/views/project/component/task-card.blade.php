{{-- resources/views/partials/task-card.blade.php --}}

@php
    $priorityIcon = match($task->priority) {
        'high'   => '🔴',
        'medium' => '🟡',
        default  => '🟢'
    };
    
    $priorityBg = match($task->priority) {
        'high'   => 'bg-red-50 border-red-200 hover:bg-red-100',
        'medium' => 'bg-yellow-50 border-yellow-200 hover:bg-yellow-100',
        default  => 'bg-green-50 border-green-200 hover:bg-green-100'
    };
    
    $priorityText = match($task->priority) {
        'high'   => 'text-red-700',
        'medium' => 'text-yellow-700',
        default  => 'text-green-700'
    };
@endphp

<div
    id="task{{ $task->id }}"
    draggable="true"
    ondragstart="drag(event)"
    class="task-card group bg-white rounded-[24px] overflow-hidden shadow-md cursor-move hover:shadow-lg transition-all duration-300 border border-slate-100 hover:border-indigo-200 hover:-translate-y-1"
>

    {{-- TOP ACCENT BAR --}}
    <div class="h-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 group-hover:via-indigo-600"></div>

    <div class="p-5 space-y-4">
        
        {{-- TITLE & PRIORITY --}}
        <div class="flex items-start justify-between gap-2">
            <div class="min-w-0 flex-1">
                <h3 class="font-black text-slate-800 truncate text-base leading-tight">{{ $task->title }}</h3>
                <p class="text-xs text-slate-500 mt-1">ID: #{{ $task->id }}</p>
            </div>
            <span class="text-2xl shrink-0">{{ $priorityIcon }}</span>
        </div>

        {{-- DESCRIPTION --}}
        @if($task->description)
            <p class="text-sm text-slate-600 line-clamp-2 leading-relaxed">{{ $task->description }}</p>
        @endif

        {{-- TEAM SECTION --}}
        <div class="rounded-[18px] border border-slate-200 bg-slate-50 p-4 space-y-3">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold text-slate-700 uppercase tracking-wide">Team</p>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-indigo-100 px-2.5 py-1 text-xs font-bold text-indigo-700">
                    <span class="h-2 w-2 rounded-full bg-indigo-500"></span>
                    {{ $task->users->count() }}
                </span>
            </div>

            <div class="flex items-center -space-x-2">
                @foreach($task->users->take(5) as $user)
                    <img
                        draggable="false"
                        src="https://i.pravatar.cc/100?u={{ $user->id }}"
                        alt="{{ $user->name }}"
                        title="{{ $user->name }}"
                        class="w-11 h-11 rounded-full object-cover border-2 border-white shadow-md hover:scale-110 transition-transform"
                    >
                @endforeach

                @if($task->users->count() > 5)
                    <div class="w-11 h-11 rounded-full border-2 border-white bg-gradient-to-br from-indigo-500 to-purple-600 text-white shadow-md flex items-center justify-center text-xs font-bold">
                        +{{ $task->users->count() - 5 }}
                    </div>
                @endif
            </div>
        </div>

        {{-- FOOTER: PRIORITY & DETAIL BUTTON --}}
        <div class="flex items-center justify-between gap-2 pt-1">
            <div class="rounded-full {{ $priorityBg }} border px-3 py-1 flex items-center gap-1.5">
                <span class="text-xs font-bold {{ $priorityText }} uppercase tracking-wide">
                    {{ ucfirst($task->priority) }} Priority
                </span>
            </div>
            <a
                href="{{ route('project.detail', $task->id) }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 text-sm font-semibold transition-all duration-300 shadow-md hover:shadow-lg"
            >
                View
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>

    </div>

</div>