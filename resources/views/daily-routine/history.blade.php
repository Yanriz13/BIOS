@extends('layouts.app')

@section('content')

    <div class="p-6">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">

            <div>
                <h1 class="text-3xl font-bold text-slate-800">
                    History Daily Routine
                </h1>

                <p class="text-slate-500 mt-1">
                    Riwayat aktivitas dan checklist routine harian
                </p>
            </div>

            <a href="{{ route('daily-routine.index') }}"
                class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-slate-800 text-white hover:bg-slate-700 transition shadow-sm">

                ← Kembali

            </a>

        </div>

        {{-- LIST --}}
        <div class="space-y-6">

            @forelse($routines as $routine)

                <div
                    class="bg-white border border-slate-200 rounded-[32px] overflow-hidden shadow-sm hover:shadow-md transition">

                    {{-- TOP HEADER --}}
                    <div class="p-6 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white">

                        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-5">

                            <div class="flex-1">

                                <div class="flex items-center gap-3 flex-wrap">

                                    <h2 class="text-2xl font-bold text-slate-800">
                                        {{ $routine->title }}
                                    </h2>

                                    <span
                                        class="px-4 py-1.5 rounded-full text-xs font-semibold bg-red-100 text-red-600 border border-red-200">
                                        HISTORY
                                    </span>

                                </div>

                                <p class="text-slate-500 mt-3 leading-relaxed max-w-3xl">
                                    {{ $routine->description }}
                                </p>

                            </div>

                            {{-- INFO CARD --}}
                            <div class="bg-white border border-slate-200 rounded-2xl px-5 py-4 min-w-[220px] shadow-sm">

                                <div class="text-xs uppercase tracking-wide text-slate-400 mb-1">
                                    Hari
                                </div>

                                <div class="font-semibold text-red-500 text-lg">
                                    {{ ucfirst($routine->archived_day ?? '-') }}
                                </div>
                                <div class="mt-3 text-sm text-slate-500">
                                    Total Checklist :
                                    <span class="font-semibold text-slate-700">
                                        {{ count($routine->checklists) }}
                                    </span>
                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- TABLE --}}
                    <div class="overflow-x-auto">

                        <table class="w-full min-w-[900px]">

                            <thead class="bg-slate-50 border-b border-slate-200">

                                <tr>

                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500 w-[60px]">
                                        No
                                    </th>

                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                        Checklist
                                    </th>

                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500 w-[150px]">
                                        Status
                                    </th>

                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
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

                                                    {{-- NO --}}
                                                    <td class="px-6 py-5 align-top">

                                                        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-sm font-bold
                                                                                                                                            {{ $checklist->is_done
                                    ? 'bg-green-100 text-green-600'
                                    : 'bg-slate-100 text-slate-500' }}">

                                                            {{ $index + 1 }}

                                                        </div>

                                                    </td>

                                                    {{-- TITLE --}}
                                                    <td class="px-6 py-5 align-top">

                                                        <div class="flex items-start gap-4">

                                                            <div class="w-6 h-6 mt-0.5 rounded-lg border flex items-center justify-center text-sm font-bold
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

                                                    {{-- STATUS --}}
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

                                                    {{-- ALASAN --}}
                                                    <td class="px-6 py-5 align-top">

                                                        @if($checklist->uncheck_reason)

                                                            <div class="bg-red-50 border border-red-100 rounded-2xl p-4">

                                                                <div class="text-xs font-semibold uppercase text-red-400 mb-1">
                                                                    Alasan
                                                                </div>

                                                                <div class="text-sm text-red-600 leading-relaxed">
                                                                    {{ $checklist->uncheck_reason }}
                                                                </div>

                                                            </div>

                                                        @else

                                                            <span class="text-slate-400 text-sm italic">
                                                                Tidak ada alasan
                                                            </span>

                                                        @endif

                                                    </td>

                                                    {{-- FILE --}}
                                                    <td class="px-6 py-5 align-top">

                                                        @if($checklist->file_path)

                                                            {{-- DROPDOWN TABLE STYLE --}}
                                                            <details class="group">

                                                                <summary class="list-none cursor-pointer select-none">

                                                                    <div
                                                                        class="flex items-center justify-between gap-3 px-4 py-3 rounded-2xl border border-slate-200 bg-white hover:bg-slate-50 transition">

                                                                        <div class="flex items-center gap-3 min-w-0">

                                                                            <div
                                                                                class="w-11 h-11 rounded-xl bg-slate-100 flex items-center justify-center text-lg">

                                                                                @if($isImage)
                                                                                    🖼️
                                                                                @else
                                                                                    📄
                                                                                @endif

                                                                            </div>

                                                                            <div class="min-w-0">

                                                                                <div class="font-medium text-slate-700 truncate max-w-[130px]">
                                                                                    {{ $checklist->file_name }}
                                                                                </div>

                                                                                <div class="text-xs uppercase text-slate-400 mt-1">
                                                                                    {{ $extension }}
                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                        <div class="text-slate-400 group-open:rotate-180 transition">
                                                                            ▼
                                                                        </div>

                                                                    </div>

                                                                </summary>

                                                                {{-- DROPDOWN CONTENT --}}
                                                                <div class="mt-3 border border-slate-200 rounded-2xl overflow-hidden bg-white">

                                                                    <table class="w-full text-sm">

                                                                        <thead class="bg-slate-50">

                                                                            <tr>

                                                                                <th class="px-4 py-3 text-left font-semibold text-slate-500">
                                                                                    Tipe
                                                                                </th>

                                                                                <th class="px-4 py-3 text-left font-semibold text-slate-500">
                                                                                    Nama File
                                                                                </th>

                                                                                <th
                                                                                    class="px-4 py-3 text-center font-semibold text-slate-500 w-[140px]">
                                                                                    Aksi
                                                                                </th>

                                                                            </tr>

                                                                        </thead>

                                                                        <tbody>

                                                                            <tr class="border-t border-slate-100">

                                                                                <td class="px-4 py-4">

                                                                                    @if($isImage)
                                                                                        Image
                                                                                    @else
                                                                                        Document
                                                                                    @endif

                                                                                </td>

                                                                                <td class="px-4 py-4 break-all">
                                                                                    {{ $checklist->file_name }}
                                                                                </td>

                                                                                <td class="px-4 py-4">

                                                                                    <div class="flex items-center justify-center gap-2">

                                                                                        <a href="{{ asset('storage/' . $checklist->file_path) }}"
                                                                                            target="_blank"
                                                                                            class="px-4 py-2 rounded-xl bg-blue-500 text-white hover:bg-blue-600 transition text-sm font-medium">

                                                                                            Lihat

                                                                                        </a>

                                                                                        <a href="{{ asset('storage/' . $checklist->file_path) }}"
                                                                                            download
                                                                                            class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 transition text-sm font-medium text-slate-700">

                                                                                            Download

                                                                                        </a>

                                                                                    </div>

                                                                                </td>

                                                                            </tr>

                                                                            {{-- PREVIEW IMAGE --}}
                                                                            @if($isImage)

                                                                                <tr class="border-t border-slate-100">

                                                                                    <td colspan="3" class="p-5 bg-slate-50">

                                                                                        <img src="{{ asset('storage/' . $checklist->file_path) }}"
                                                                                            class="w-48 h-48 object-cover rounded-2xl border border-slate-200 shadow-sm">

                                                                                    </td>

                                                                                </tr>

                                                                            @endif

                                                                        </tbody>

                                                                    </table>

                                                                </div>

                                                            </details>

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

                <div class="bg-white border border-slate-200 rounded-[32px] p-14 text-center shadow-sm">

                    <div class="text-6xl mb-4">
                        🗂️
                    </div>

                    <h2 class="text-2xl font-bold text-slate-700 mb-2">
                        Belum Ada History
                    </h2>

                    <p class="text-slate-400">
                        History daily routine akan muncul di sini
                    </p>

                </div>

            @endforelse

        </div>

    </div>

@endsection