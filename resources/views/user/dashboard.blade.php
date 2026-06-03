@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- ============================== --}}
    {{-- HERO --}}
    {{-- ============================== --}}
    <div class="relative overflow-hidden rounded-[32px] bg-gradient-to-r from-[#111c44] via-[#132850] to-[#0b1120] p-6 md:p-10 shadow-2xl">

        {{-- BG EFFECT --}}
        <div class="absolute top-0 right-0 w-72 h-72 bg-cyan-400/10 blur-3xl rounded-full"></div>
        <div class="absolute bottom-0 left-0 w-72 h-72 bg-indigo-500/10 blur-3xl rounded-full"></div>

        <div class="relative z-10 flex flex-col xl:flex-row xl:items-center xl:justify-between gap-8">

            {{-- LEFT --}}
            <div class="max-w-2xl">

                <div class="inline-flex items-center gap-2 bg-white/10 border border-white/10 text-white px-4 py-2 rounded-2xl text-sm backdrop-blur-xl">

                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>

                    System Active

                </div>

                <h1 class="mt-6 text-3xl md:text-5xl font-black text-white leading-tight">

                    User Monitoring
                    Dashboard System

                </h1>

                <p class="mt-5 text-slate-300 text-sm md:text-base leading-relaxed max-w-xl">

                    Monitor conservation reports, project analytics,
                    operational performance and environmental activities
                    in one integrated platform.

                </p>

                <div class="mt-8 flex flex-wrap gap-4">

                    <button class="h-12 px-6 rounded-2xl bg-white text-slate-800 font-bold hover:scale-105 transition">

                        View Reports

                    </button>

                    <button class="h-12 px-6 rounded-2xl border border-white/20 text-white font-semibold hover:bg-white/10 transition">

                        Analytics

                    </button>

                </div>

            </div>

            {{-- RIGHT --}}
            <div class="grid grid-cols-2 gap-4 w-full xl:w-auto">

                <div class="bg-white/10 backdrop-blur-xl border border-white/10 rounded-3xl p-5 min-w-[150px]">

                    <p class="text-slate-300 text-sm">
                        Active Users
                    </p>

                    <h2 class="mt-3 text-3xl font-black text-white">
                        150+
                    </h2>

                    <span class="text-green-400 text-sm font-semibold">
                        +8% this week
                    </span>

                </div>

                <div class="bg-white/10 backdrop-blur-xl border border-white/10 rounded-3xl p-5 min-w-[150px]">

                    <p class="text-slate-300 text-sm">
                        Reports
                    </p>

                    <h2 class="mt-3 text-3xl font-black text-white">
                        58
                    </h2>

                    <span class="text-cyan-400 text-sm font-semibold">
                        Updated today
                    </span>

                </div>

                <div class="bg-white/10 backdrop-blur-xl border border-white/10 rounded-3xl p-5 min-w-[150px]">

                    <p class="text-slate-300 text-sm">
                        Performance
                    </p>

                    <h2 class="mt-3 text-3xl font-black text-white">
                        99%
                    </h2>

                    <span class="text-yellow-400 text-sm font-semibold">
                        Excellent
                    </span>

                </div>

                <div class="bg-white/10 backdrop-blur-xl border border-white/10 rounded-3xl p-5 min-w-[150px]">

                    <p class="text-slate-300 text-sm">
                        Monitoring
                    </p>

                    <h2 class="mt-3 text-3xl font-black text-white">
                        24/7
                    </h2>

                    <span class="text-pink-400 text-sm font-semibold">
                        Realtime
                    </span>

                </div>

            </div>

        </div>

    </div>

    {{-- ============================== --}}
    {{-- CONTENT --}}
    {{-- ============================== --}}
    <div class="grid grid-cols-12 gap-6">

        {{-- LEFT --}}
        <div class="col-span-12 xl:col-span-8 space-y-6">

            {{-- ============================== --}}
            {{-- STATS --}}
            {{-- ============================== --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

                {{-- CARD --}}
                <div class="bg-white rounded-3xl border border-slate-100 p-5 shadow-sm hover:shadow-xl transition">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-slate-500 text-sm">
                                SLA
                            </p>

                            <h2 class="mt-2 text-3xl font-black text-slate-800">
                                150
                            </h2>

                        </div>

                        <div class="w-14 h-14 rounded-2xl bg-green-100 flex items-center justify-center text-2xl">
                            ⏱️
                        </div>

                    </div>

                    <div class="mt-4 text-green-500 text-sm font-semibold">
                        +12% increase
                    </div>

                </div>

                {{-- CARD --}}
                <div class="bg-white rounded-3xl border border-slate-100 p-5 shadow-sm hover:shadow-xl transition">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-slate-500 text-sm">
                                Projects
                            </p>

                            <h2 class="mt-2 text-3xl font-black text-slate-800">
                                12
                            </h2>

                        </div>

                        <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center text-2xl">
                            📁
                        </div>

                    </div>

                    <div class="mt-4 text-blue-500 text-sm font-semibold">
                        Active project
                    </div>

                </div>

                {{-- CARD --}}
                <div class="bg-white rounded-3xl border border-slate-100 p-5 shadow-sm hover:shadow-xl transition">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-slate-500 text-sm">
                                Tasks
                            </p>

                            <h2 class="mt-2 text-3xl font-black text-slate-800">
                                25
                            </h2>

                        </div>

                        <div class="w-14 h-14 rounded-2xl bg-orange-100 flex items-center justify-center text-2xl">
                            📋
                        </div>

                    </div>

                    <div class="mt-4 text-orange-500 text-sm font-semibold">
                        Need review
                    </div>

                </div>

                {{-- CARD --}}
                <div class="bg-white rounded-3xl border border-slate-100 p-5 shadow-sm hover:shadow-xl transition">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-slate-500 text-sm">
                                Compliance
                            </p>

                            <h2 class="mt-2 text-3xl font-black text-slate-800">
                                99%
                            </h2>

                        </div>

                        <div class="w-14 h-14 rounded-2xl bg-pink-100 flex items-center justify-center text-2xl">
                            ✅
                        </div>

                    </div>

                    <div class="mt-4 text-pink-500 text-sm font-semibold">
                        Excellent
                    </div>

                </div>

            </div>

            {{-- ============================== --}}
            {{-- MAP --}}
            {{-- ============================== --}}
            <div class="bg-white rounded-[32px] border border-slate-100 overflow-hidden shadow-sm">

                <div class="p-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                    <div>

                        <h2 class="text-2xl font-black text-slate-800">
                            Interactive Monitoring Map
                        </h2>

                        <p class="text-slate-500 text-sm mt-1">
                            Real-time conservation activity monitoring
                        </p>

                    </div>

                    <div class="flex flex-wrap gap-2">

                        <span class="px-4 py-2 rounded-2xl bg-green-100 text-green-600 text-sm font-semibold">
                            Completed
                        </span>

                        <span class="px-4 py-2 rounded-2xl bg-yellow-100 text-yellow-600 text-sm font-semibold">
                            Progress
                        </span>

                        <span class="px-4 py-2 rounded-2xl bg-red-100 text-red-600 text-sm font-semibold">
                            Delayed
                        </span>

                    </div>

                </div>

                <div class="relative h-[260px] sm:h-[340px] lg:h-[420px]">

                    <img
                        src="https://images.unsplash.com/photo-1524661135-423995f22d0b?q=80&w=1200"
                        class="w-full h-full object-cover"
                    >

                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>

                    <div class="absolute bottom-5 left-5 right-5 sm:right-auto bg-white/90 backdrop-blur-xl rounded-2xl px-5 py-4 shadow-xl">

                        <div class="flex items-center justify-between gap-4">

                            <div>

                                <p class="font-bold text-slate-800">
                                    Conservation Zone Active
                                </p>

                                <small class="text-slate-500">
                                    Last update 2 mins ago
                                </small>

                            </div>

                            <div class="w-4 h-4 rounded-full bg-green-500 animate-pulse"></div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- ============================== --}}
            {{-- REPORT --}}
            {{-- ============================== --}}
            <div class="bg-white rounded-[32px] border border-slate-100 p-6 shadow-sm">

                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

                    <div>

                        <h2 class="text-2xl font-black text-slate-800">
                            Conservation Reports
                        </h2>

                        <p class="text-slate-500 text-sm mt-1">
                            Latest environmental documentation
                        </p>

                    </div>

                    <button class="h-12 px-5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold transition">
                        View All
                    </button>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- ITEM --}}
                    <div class="group rounded-3xl overflow-hidden border border-slate-100 bg-slate-50 hover:shadow-xl transition">

                        <div class="h-56 overflow-hidden">

                            <img
                                src="https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?q=80&w=1200"
                                class="w-full h-full object-cover group-hover:scale-110 transition duration-700"
                            >

                        </div>

                        <div class="p-5">

                            <div class="flex items-center justify-between mb-4">

                                <span class="px-3 py-1 rounded-xl bg-green-100 text-green-600 text-xs font-bold">
                                    Completed
                                </span>

                                <small class="text-slate-400">
                                    18 May 2026
                                </small>

                            </div>

                            <h3 class="font-black text-lg text-slate-800">
                                Borobudur Restoration
                            </h3>

                            <p class="mt-3 text-sm text-slate-500 leading-relaxed">
                                Restoration activity report for environmental
                                monitoring and heritage conservation area.
                            </p>

                        </div>

                    </div>

                    {{-- ITEM --}}
                    <div class="group rounded-3xl overflow-hidden border border-slate-100 bg-slate-50 hover:shadow-xl transition">

                        <div class="h-56 overflow-hidden">

                            <img
                                src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?q=80&w=1200"
                                class="w-full h-full object-cover group-hover:scale-110 transition duration-700"
                            >

                        </div>

                        <div class="p-5">

                            <div class="flex items-center justify-between mb-4">

                                <span class="px-3 py-1 rounded-xl bg-yellow-100 text-yellow-600 text-xs font-bold">
                                    In Progress
                                </span>

                                <small class="text-slate-400">
                                    16 May 2026
                                </small>

                            </div>

                            <h3 class="font-black text-lg text-slate-800">
                                Environmental Inspection
                            </h3>

                            <p class="mt-3 text-sm text-slate-500 leading-relaxed">
                                Humidity and environmental inspection around
                                conservation public areas and ecosystem zones.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- RIGHT --}}
        <div class="col-span-12 xl:col-span-4 space-y-6">

            {{-- PERFORMANCE --}}
            <div class="bg-white rounded-[32px] border border-slate-100 p-6 shadow-sm">

                <div class="mb-6">

                    <h2 class="text-xl font-black text-slate-800">
                        Performance
                    </h2>

                    <p class="text-slate-500 text-sm">
                        User analytics statistics
                    </p>

                </div>

                <div class="h-[280px]">

                    <canvas id="divisionChart"></canvas>

                </div>

            </div>

            {{-- TASK --}}
            <div class="bg-white rounded-[32px] border border-slate-100 p-6 shadow-sm">

                <div class="mb-6">

                    <h2 class="text-xl font-black text-slate-800">
                        Recent Activity
                    </h2>

                    <p class="text-slate-500 text-sm">
                        Latest activity updates
                    </p>

                </div>

                <div class="space-y-5">

                    <div class="flex items-start gap-4">

                        <div class="w-12 h-12 rounded-2xl bg-red-100 flex items-center justify-center shrink-0">
                            🚨
                        </div>

                        <div>

                            <p class="font-semibold text-slate-800 text-sm">
                                Security patrol pending
                            </p>

                            <small class="text-slate-500">
                                13 mins ago
                            </small>

                        </div>

                    </div>

                    <div class="flex items-start gap-4">

                        <div class="w-12 h-12 rounded-2xl bg-yellow-100 flex items-center justify-center shrink-0">
                            📄
                        </div>

                        <div>

                            <p class="font-semibold text-slate-800 text-sm">
                                Proposal review submitted
                            </p>

                            <small class="text-slate-500">
                                25 mins ago
                            </small>

                        </div>

                    </div>

                    <div class="flex items-start gap-4">

                        <div class="w-12 h-12 rounded-2xl bg-green-100 flex items-center justify-center shrink-0">
                            🌿
                        </div>

                        <div>

                            <p class="font-semibold text-slate-800 text-sm">
                                Conservation report updated
                            </p>

                            <small class="text-slate-500">
                                1 hour ago
                            </small>

                        </div>

                    </div>

                    <div class="flex items-start gap-4">

                        <div class="w-12 h-12 rounded-2xl bg-blue-100 flex items-center justify-center shrink-0">
                            📊
                        </div>

                        <div>

                            <p class="font-semibold text-slate-800 text-sm">
                                KPI analytics generated
                            </p>

                            <small class="text-slate-500">
                                2 hours ago
                            </small>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

{{-- CHART --}}
<script>

const ctx = document.getElementById('divisionChart')

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Security', 'Cleanliness', 'Conservation'],
        datasets: [
            {
                label: 'SLA',
                data: [90, 60, 75],
                borderRadius: 12
            },
            {
                label: 'Performance',
                data: [70, 80, 65],
                borderRadius: 12
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,

        plugins: {
            legend: {
                position: 'top'
            }
        },

        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
})

</script>

@endsection