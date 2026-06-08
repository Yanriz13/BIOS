@extends('layouts.app')

@section('content')

    <div class="p-3 sm:p-5 lg:p-6">

        {{-- HEADER --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">

            <div>

                <h1 class="text-2xl sm:text-3xl font-bold text-slate-800">
                    History Daily Routine
                </h1>

                <p class="text-sm sm:text-base text-slate-500 mt-1">
                    Riwayat aktivitas dan checklist routine harian
                </p>

            </div>

            <a href="{{ route('daily-routine.index') }}"
                class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-slate-800 text-white hover:bg-slate-700 transition shadow-sm text-sm font-medium w-full sm:w-auto">

                ← Kembali

            </a>

        </div>

        {{-- LIST --}}
        <div class="space-y-5">

            @forelse($routines as $routine)

                                <div class="bg-white border border-slate-200 rounded-[28px] overflow-hidden shadow-sm">

                                    {{-- TOP HEADER --}}
                                    <div class="p-4 sm:p-6 border-b border-slate-100 bg-gradient-to-br from-slate-50 via-white to-slate-50">

                                        <div class="flex flex-col gap-5">

                                            <div>

                                                <div class="flex flex-wrap items-center gap-3">

                                                    <h2 class="text-xl sm:text-2xl font-bold text-slate-800 break-words">
                                                        {{ $routine->title }}
                                                    </h2>

                                                    <span
                                                        class="px-3 py-1 rounded-full text-[11px] font-semibold bg-red-100 text-red-600 border border-red-200">

                                                        HISTORY

                                                    </span>

                                                </div>

                                                <p class="text-slate-500 mt-3 text-sm sm:text-base leading-relaxed">
                                                    {{ $routine->description }}
                                                </p>

                                            </div>

                                            {{-- INFO CARD --}}
                                            <div
                                                class="grid grid-cols-2 sm:grid-cols-2 gap-3 bg-white border border-slate-200 rounded-2xl p-4 shadow-sm">

                                                <div>

                                                    <div class="text-[11px] uppercase tracking-wide text-slate-400 mb-1">
                                                        Hari
                                                    </div>

                                                    <div class="font-bold text-red-500 text-sm sm:text-base">
                                                        <div class="font-semibold text-red-500 text-lg">
                                                            {{ ucfirst($routine->archived_day ?? '-') }}
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="text-right">

                                                    <div class="text-[11px] uppercase tracking-wide text-slate-400 mb-1">
                                                        Checklist
                                                    </div>

                                                    <div class="font-bold text-slate-700 text-sm sm:text-base">
                                                        {{ count($routine->checklists) }} Item
                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    {{-- MOBILE CARD --}}
                              {{-- MOBILE FRIENDLY HISTORY --}}
                <div class="block lg:hidden space-y-4">

                    @foreach($routine->checklists as $index => $checklist)

                        @php
                            $extension = strtolower(pathinfo($checklist->file_name ?? '', PATHINFO_EXTENSION));
                            $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                        @endphp

                        <details
                            class="group bg-white border border-slate-200 rounded-[28px] overflow-hidden shadow-sm transition-all duration-300 open:shadow-lg">

                            {{-- HEADER --}}
                            <summary class="list-none cursor-pointer">

                                <div class="p-4">

                                    <div class="flex items-start gap-4">

                                        {{-- NUMBER --}}
                                        <div
                                            class="w-12 h-12 rounded-2xl flex items-center justify-center text-sm font-bold shrink-0
                                            {{ $checklist->is_done
                            ? 'bg-green-100 text-green-600'
                            : 'bg-red-100 text-red-500' }}">

                                            {{ $index + 1 }}

                                        </div>

                                        {{-- CONTENT --}}
                                        <div class="flex-1 min-w-0">

                                            <div class="flex items-start justify-between gap-3">

                                                <div class="min-w-0">

                                                    <h3 class="font-bold text-slate-800 text-[15px] leading-relaxed break-words">

                                                        {{ $checklist->title }}

                                                    </h3>

                                                    <p class="text-xs text-slate-400 mt-1">
                                                        Checklist history routine
                                                    </p>

                                                </div>

                                                {{-- ARROW --}}
                                                <div
                                                    class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 text-xs shrink-0 transition duration-300 group-open:rotate-180">

                                                    ▼

                                                </div>

                                            </div>

                                            {{-- STATUS --}}
                                            <div class="mt-4 flex items-center gap-2 flex-wrap">

                                                @if($checklist->is_done)

                                                    <span
                                                        class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-green-100 text-green-700 text-xs font-semibold border border-green-200">

                                                        ✓ Selesai

                                                    </span>

                                                @else

                                                    <span
                                                        class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-red-100 text-red-600 text-xs font-semibold border border-red-200">

                                                        ✕ Belum

                                                    </span>

                                                @endif

                                                @if($checklist->file_path)

                                                    <span
                                                        class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-100 text-slate-600 text-xs font-semibold border border-slate-200">

                                                        @if($isImage)
                                                            🖼️ Image
                                                        @else
                                                            📄 File
                                                        @endif

                                                    </span>

                                                @endif

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </summary>

                            {{-- DROPDOWN CONTENT --}}
                            <div class="px-4 pb-4">

                                <div class="border border-slate-100 rounded-[24px] bg-slate-50 overflow-hidden">

                                    {{-- DETAIL --}}
                                    <div class="p-5 space-y-5">

                                        {{-- CHECKLIST INFO --}}
                                        <div class="bg-white rounded-2xl border border-slate-200 p-4">

                                            <div
                                                class="text-[11px] uppercase tracking-wider font-semibold text-slate-400 mb-2">

                                                Detail Checklist

                                            </div>

                                            <div class="text-sm text-slate-700 leading-relaxed">

                                                {{ $checklist->title }}

                                            </div>

                                        </div>

                                        {{-- REASON --}}
                                        @if($checklist->uncheck_reason)

                                            <div class="bg-red-50 border border-red-100 rounded-2xl p-4">

                                                <div
                                                    class="text-[11px] uppercase tracking-wider font-semibold text-red-400 mb-2">

                                                    Alasan Batal

                                                </div>

                                                <div class="text-sm text-red-600 leading-relaxed break-words">

                                                    {{ $checklist->uncheck_reason }}

                                                </div>

                                            </div>

                                        @endif

                                        {{-- FILE --}}
                                        @if($checklist->file_path)

                                            <div class="bg-white border border-slate-200 rounded-2xl p-4">

                                                <div
                                                    class="text-[11px] uppercase tracking-wider font-semibold text-slate-400 mb-4">

                                                    Lampiran

                                                </div>

                                                {{-- FILE INFO --}}
                                                <div
                                                    class="flex items-center gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-200">

                                                    <div
                                                        class="w-12 h-12 rounded-2xl bg-white border border-slate-200 flex items-center justify-center text-xl shrink-0">

                                                        @if($isImage)
                                                            🖼️
                                                        @else
                                                            📄
                                                        @endif

                                                    </div>

                                                    <div class="min-w-0 flex-1">

                                                        <div
                                                            class="font-semibold text-slate-700 text-sm truncate">

                                                            {{ $checklist->file_name }}

                                                        </div>

                                                        <div
                                                            class="text-xs uppercase tracking-wide text-slate-400 mt-1">

                                                            {{ $extension }}

                                                        </div>

                                                    </div>

                                                </div>

                                                {{-- IMAGE --}}
                                                @if($isImage)

                                                    <div class="mt-4">

                                                        <img src="{{ asset('storage/' . $checklist->file_path) }}"
                                                            class="w-full h-52 object-cover rounded-2xl border border-slate-200 shadow-sm">

                                                    </div>

                                                @endif

                                                {{-- ACTION --}}
                                                <div class="grid grid-cols-2 gap-3 mt-4">

                                                    <a href="{{ asset('storage/' . $checklist->file_path) }}"
                                                        target="_blank"
                                                        class="flex items-center justify-center gap-2 px-4 py-3 rounded-2xl bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold transition">

                                                        👁️ Lihat

                                                    </a>

                                                    <a href="{{ asset('storage/' . $checklist->file_path) }}"
                                                        download
                                                        class="flex items-center justify-center gap-2 px-4 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold transition">

                                                        ⬇ Download

                                                    </a>

                                                </div>

                                            </div>

                                        @else

                                            <div
                                                class="bg-white border border-dashed border-slate-200 rounded-2xl p-5 text-center">

                                                <div class="text-3xl mb-2">
                                                    📂
                                                </div>

                                                <div class="text-sm text-slate-400 italic">
                                                    Tidak ada file lampiran
                                                </div>

                                            </div>

                                        @endif

                                    </div>

                                </div>

                            </div>

                        </details>

                    @endforeach

                </div>
                                    {{-- DESKTOP TABLE --}}
                                    <div class="hidden lg:block overflow-x-auto">

                                        <table class="w-full min-w-[900px]">

                                            <thead class="bg-slate-50 border-b border-slate-200">

                                                <tr>

                                                    <th
                                                        class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500 w-[60px]">
                                                        No
                                                    </th>

                                                    <th
                                                        class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                                        Checklist
                                                    </th>

                                                    <th
                                                        class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500 w-[150px]">
                                                        Status
                                                    </th>

                                                    <th
                                                        class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                                        Alasan Batal
                                                    </th>

                                                    <th
                                                        class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500 w-[200px]">
                                                        Lampiran
                                                    </th>

                                                </tr>

                                            </thead>

                                            <tbody class="divide-y divide-slate-100">

                                                @foreach($routine->checklists as $index => $checklist)

                                                                                                @php
                                                                                                    $extension = strtolower(pathinfo($checklist->file_name ?? '', PATHINFO_EXTENSION));
                                                                                                    $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                                                                                @endphp

                                                                                                <tr class="hover:bg-slate-50/70 transition">

                                                                                                    <td class="px-6 py-5 align-top">

                                                                                                        <div
                                                                                                            class="w-9 h-9 rounded-xl flex items-center justify-center text-sm font-bold
                                                                                                            {{ $checklist->is_done
                                                    ? 'bg-green-100 text-green-600'
                                                    : 'bg-slate-100 text-slate-500' }}">

                                                                                                            {{ $index + 1 }}

                                                                                                        </div>

                                                                                                    </td>

                                                                                                    <td class="px-6 py-5 align-top">

                                                                                                        <div class="flex items-start gap-4">

                                                                                                            <div
                                                                                                                class="w-6 h-6 mt-0.5 rounded-lg border flex items-center justify-center text-sm font-bold
                                                                                                                {{ $checklist->is_done
                                                    ? 'bg-green-500 border-green-500 text-white'
                                                    : 'bg-white border-slate-300 text-transparent' }}">

                                                                                                                ✓

                                                                                                            </div>

                                                                                                            <div>

                                                                                                                <div class="font-semibold text-slate-800 leading-relaxed">
                                                                                                                    {{ $checklist->title }}
                                                                                                                </div>

                                                                                                                <div class="text-sm text-slate-400 mt-1">
                                                                                                                    Checklist history routine
                                                                                                                </div>

                                                                                                            </div>

                                                                                                        </div>

                                                                                                    </td>

                                                                                                    <td class="px-6 py-5 align-top">

                                                                                                        @if($checklist->is_done)

                                                                                                            <span
                                                                                                                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-green-100 text-green-700 text-sm font-semibold border border-green-200">

                                                                                                                ✓ Selesai

                                                                                                            </span>

                                                                                                        @else

                                                                                                            <span
                                                                                                                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-red-100 text-red-600 text-sm font-semibold border border-red-200">

                                                                                                                ✕ Belum

                                                                                                            </span>

                                                                                                        @endif

                                                                                                    </td>

                                                                                                    <td class="px-6 py-5 align-top">

                                                                                                        @if($checklist->uncheck_reason)

                                                                                                            <div class="bg-red-50 border border-red-100 rounded-2xl p-4">

                                                                                                                <div
                                                                                                                    class="text-xs font-semibold uppercase text-red-400 mb-1">

                                                                                                                    Alasan

                                                                                                                </div>

                                                                                                                <div
                                                                                                                    class="text-sm text-red-600 leading-relaxed">

                                                                                                                    {{ $checklist->uncheck_reason }}

                                                                                                                </div>

                                                                                                            </div>

                                                                                                        @else

                                                                                                            <span class="text-slate-400 text-sm italic">
                                                                                                                Tidak ada alasan
                                                                                                            </span>

                                                                                                        @endif

                                                                                                    </td>

                                                                                                    <td class="px-6 py-5 align-top">

                                                                                                        @if($checklist->file_path)

                                                                                                            <a href="{{ asset('storage/' . $checklist->file_path) }}"
                                                                                                                target="_blank"
                                                                                                                class="inline-flex items-center gap-2 px-4 py-3 rounded-2xl border border-slate-200 hover:bg-slate-50 transition">

                                                                                                                @if($isImage)
                                                                                                                    🖼️
                                                                                                                @else
                                                                                                                    📄
                                                                                                                @endif

                                                                                                                <span class="truncate max-w-[120px]">
                                                                                                                    {{ $checklist->file_name }}
                                                                                                                </span>

                                                                                                            </a>

                                                                                                        @else

                                                                                                            <span class="text-slate-400 text-sm italic">
                                                                                                                Tidak ada file
                                                                                                            </span>

                                                                                                        @endif

                                                                                                    </td>

                                                                                                </tr>

                                                @endforeach

                                            </tbody>

                                        </table>

                                    </div>

                                </div>

            @empty

                <div
                    class="bg-white border border-slate-200 rounded-[32px] p-10 sm:p-14 text-center shadow-sm">

                    <div class="text-5xl sm:text-6xl mb-4">
                        🗂️
                    </div>

                    <h2 class="text-xl sm:text-2xl font-bold text-slate-700 mb-2">
                        Belum Ada History
                    </h2>

                    <p class="text-sm sm:text-base text-slate-400">
                        History daily routine akan muncul di sini
                    </p>

                </div>

            @endforelse

        </div>

    </div>

@endsection