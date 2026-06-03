
@extends('layouts.app')

@section('content')

<div class="grid grid-cols-12 gap-6">

    {{-- LEFT --}}
    <div class="col-span-12 xl:col-span-9 space-y-6">

        {{-- ========================= --}}
        {{-- STATS --}}
        {{-- ========================= --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">

            {{-- CARD --}}
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">

                <p class="text-slate-500 text-sm">
                    Average SLA
                </p>

                <h1 class="text-5xl font-black mt-3">
                    150
                </h1>

                <div class="mt-4 flex items-center justify-between">

                    <span class="text-green-500 text-sm font-semibold">
                        +12%
                    </span>

                    <div class="w-14 h-14 rounded-2xl bg-green-100 flex items-center justify-center text-2xl">
                        ⏱️
                    </div>

                </div>

            </div>

            {{-- CARD --}}
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">

                <p class="text-slate-500 text-sm">
                    Active Projects
                </p>

                <h1 class="text-5xl font-black mt-3">
                    3
                </h1>

                <div class="mt-4 flex items-center justify-between">

                    <span class="text-blue-500 text-sm font-semibold">
                        Running
                    </span>

                    <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center text-2xl">
                        📁
                    </div>

                </div>

            </div>

            {{-- CARD --}}
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">

                <p class="text-slate-500 text-sm">
                    Awaiting Tasks
                </p>

                <h1 class="text-5xl font-black mt-3">
                    25
                </h1>

                <div class="mt-4 flex items-center justify-between">

                    <span class="text-orange-500 text-sm font-semibold">
                        Need review
                    </span>

                    <div class="w-14 h-14 rounded-2xl bg-orange-100 flex items-center justify-center text-2xl">
                        📋
                    </div>

                </div>

            </div>

            {{-- CARD --}}
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">

                <p class="text-slate-500 text-sm">
                    Compliance
                </p>

                <h1 class="text-5xl font-black mt-3">
                    99%
                </h1>

                <div class="mt-4 flex items-center justify-between">

                    <span class="text-pink-500 text-sm font-semibold">
                        Excellent
                    </span>

                    <div class="w-14 h-14 rounded-2xl bg-pink-100 flex items-center justify-center text-2xl">
                        ✅
                    </div>

                </div>

            </div>

        </div>

        {{-- ========================= --}}
        {{-- INTERACTIVE MAP --}}
        {{-- ========================= --}}
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">

            {{-- HEADER --}}
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">

                <div>

                    <h2 class="text-2xl font-black text-slate-800">
                        Interactive Map
                    </h2>

                    <p class="text-slate-500 text-sm">
                        Monitoring area activity & conservation zone
                    </p>

                </div>

                {{-- STATUS --}}
                <div class="flex flex-wrap gap-2">

                    <span class="px-4 py-2 bg-green-100 text-green-600 rounded-xl text-sm font-semibold">
                        ✔ Completed
                    </span>

                    <span class="px-4 py-2 bg-yellow-100 text-yellow-600 rounded-xl text-sm font-semibold">
                        ⏳ In Progress
                    </span>

                    <span class="px-4 py-2 bg-red-100 text-red-600 rounded-xl text-sm font-semibold">
                        ✖ Missed
                    </span>

                </div>

            </div>

            {{-- MAP --}}
          <div class="rounded-3xl overflow-hidden h-[520px] relative border border-slate-200">

    <div id="activityMap" class="w-full h-full"></div>

    <div
        class="absolute bottom-5 left-5 bg-white/95 backdrop-blur-md px-5 py-3 rounded-2xl shadow-lg z-[500]"
    >
        <p class="font-bold text-slate-800">
            Monitoring Lokasi Daily Routine
        </p>

        <small class="text-slate-500">
            Total Titik:
            {{ $locations->count() }}
        </small>
    </div>

</div>

        </div>

        {{-- ========================= --}}
        {{-- CONSERVATION REPORT --}}
        {{-- ========================= --}}
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">

            {{-- HEADER --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

                <div>

                    <h2 class="text-2xl font-black text-slate-800">
                        Conservation Report Access
                    </h2>

                    <p class="text-slate-500 text-sm">
                        Recent conservation documentation & reports
                    </p>

                </div>

                <button class="bg-indigo-600 hover:bg-indigo-700 transition text-white px-5 py-3 rounded-2xl text-sm font-semibold">
                    View All Reports
                </button>

            </div>

            {{-- REPORT LIST --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

                {{-- ITEM --}}
                <div class="group bg-slate-50 hover:bg-slate-100 rounded-3xl overflow-hidden border border-slate-100 transition-all duration-300 hover:shadow-xl">

                    {{-- IMAGE --}}
                    <div class="h-52 overflow-hidden">

                        <img
                            src="https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?q=80&w=1200"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-700"
                        >

                    </div>

                    {{-- CONTENT --}}
                    <div class="p-5">

                        <div class="flex items-center justify-between mb-4">

                            <span class="bg-green-100 text-green-600 text-xs font-semibold px-3 py-1 rounded-xl">
                                Completed
                            </span>

                            <small class="text-slate-400">
                                18 May 2026
                            </small>

                        </div>

                        <h3 class="font-black text-lg text-slate-800 mb-2">
                            Borobudur Zone Restoration
                        </h3>

                        <p class="text-slate-500 text-sm leading-relaxed">
                            Restoration activity report for temple area conservation and environmental monitoring.
                        </p>

                    </div>

                </div>

                {{-- ITEM --}}
                <div class="group bg-slate-50 hover:bg-slate-100 rounded-3xl overflow-hidden border border-slate-100 transition-all duration-300 hover:shadow-xl">

                    {{-- IMAGE --}}
                    <div class="h-52 overflow-hidden">

                        <img
                            src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?q=80&w=1200"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-700"
                        >

                    </div>

                    {{-- CONTENT --}}
                    <div class="p-5">

                        <div class="flex items-center justify-between mb-4">

                            <span class="bg-yellow-100 text-yellow-600 text-xs font-semibold px-3 py-1 rounded-xl">
                                In Progress
                            </span>

                            <small class="text-slate-400">
                                16 May 2026
                            </small>

                        </div>

                        <h3 class="font-black text-lg text-slate-800 mb-2">
                            Environmental Inspection
                        </h3>

                        <p class="text-slate-500 text-sm leading-relaxed">
                            Inspection and humidity monitoring around conservation zones and public areas.
                        </p>

                    </div>

                </div>

                {{-- ITEM --}}
                <div class="group bg-slate-50 hover:bg-slate-100 rounded-3xl overflow-hidden border border-slate-100 transition-all duration-300 hover:shadow-xl">

                    {{-- IMAGE --}}
                    <div class="h-52 overflow-hidden">

                        <img
                            src="https://images.unsplash.com/photo-1521295121783-8a321d551ad2?q=80&w=1200"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-700"
                        >

                    </div>

                    {{-- CONTENT --}}
                    <div class="p-5">

                        <div class="flex items-center justify-between mb-4">

                            <span class="bg-red-100 text-red-600 text-xs font-semibold px-3 py-1 rounded-xl">
                                Need Review
                            </span>

                            <small class="text-slate-400">
                                12 May 2026
                            </small>

                        </div>

                        <h3 class="font-black text-lg text-slate-800 mb-2">
                            Stone Structure Audit
                        </h3>

                        <p class="text-slate-500 text-sm leading-relaxed">
                            Structural condition analysis and maintenance recommendations for ancient stone areas.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- RIGHT --}}
    <div class="col-span-12 xl:col-span-3 space-y-6">

        {{-- ========================= --}}
        {{-- CHART --}}
        {{-- ========================= --}}
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">

            <div class="mb-6">

                <h2 class="text-xl font-black text-slate-800">
                    Division Performance
                </h2>

                <p class="text-slate-500 text-sm">
                    Performance statistics
                </p>

            </div>

            <div class="h-[280px]">

                <canvas id="divisionChart"></canvas>

            </div>

        </div>

        {{-- ========================= --}}
        {{-- URGENT TASK --}}
        {{-- ========================= --}}
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">

            <div class="mb-6">

                <h2 class="text-xl font-black text-slate-800">
                    Urgent Tasks
                </h2>

                <p class="text-slate-500 text-sm">
                    Latest important activity
                </p>

            </div>

            <div class="space-y-5">

                {{-- ITEM --}}
                <div class="flex gap-4">

                    <div class="w-12 h-12 rounded-2xl bg-red-100 flex items-center justify-center shrink-0">
                        🚨
                    </div>

                    <div>

                        <p class="font-semibold text-sm text-slate-800">
                            Security patrol pending
                        </p>

                        <small class="text-slate-500">
                            13 mins ago
                        </small>

                    </div>

                </div>

                {{-- ITEM --}}
                <div class="flex gap-4">

                    <div class="w-12 h-12 rounded-2xl bg-yellow-100 flex items-center justify-center shrink-0">
                        📄
                    </div>

                    <div>

                        <p class="font-semibold text-sm text-slate-800">
                            New proposal review
                        </p>

                        <small class="text-slate-500">
                            25 mins ago
                        </small>

                    </div>

                </div>

                {{-- ITEM --}}
                <div class="flex gap-4">

                    <div class="w-12 h-12 rounded-2xl bg-green-100 flex items-center justify-center shrink-0">
                        🌿
                    </div>

                    <div>

                        <p class="font-semibold text-sm text-slate-800">
                            Conservation report
                        </p>

                        <small class="text-slate-500">
                            1 hour ago
                        </small>

                    </div>

                </div>

                {{-- ITEM --}}
                <div class="flex gap-4">

                    <div class="w-12 h-12 rounded-2xl bg-blue-100 flex items-center justify-center shrink-0">
                        📊
                    </div>

                    <div>

                        <p class="font-semibold text-sm text-slate-800">
                            KPI report updated
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

{{-- ========================= --}}
{{-- CHART --}}
{{-- ========================= --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>

const routineLocations = @json(
    $locations->map(function ($item) {

        return [
            'id'      => $item->id,
            'title'   => $item->title,
            'lat'     => (float) ($item->latitude ?? 0),
            'lng'     => (float) ($item->longitude ?? 0),
            'address' => $item->location_address,
            'created' => optional($item->created_at)->format('d M Y H:i'),
        ];

    })->values()
);

document.addEventListener('DOMContentLoaded', function () {

    const mapElement = document.getElementById('activityMap');

    if (!mapElement) {
        console.error('activityMap tidak ditemukan');
        return;
    }

    const map = L.map('activityMap', {
        zoomControl: true
    }).setView(
        [-6.2088, 106.8456],
        5
    );

    L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        {
            attribution:
                '&copy; OpenStreetMap Contributors',
            maxZoom: 19
        }
    ).addTo(map);

    const bounds = [];

    routineLocations.forEach(function(item) {

        if (
            !item.lat ||
            !item.lng ||
            isNaN(item.lat) ||
            isNaN(item.lng)
        ) {
            return;
        }

        const marker = L.marker([
            parseFloat(item.lat),
            parseFloat(item.lng)
        ]).addTo(map);

        marker.bindPopup(`
            <div style="min-width:250px">
                <div style="font-weight:bold;margin-bottom:8px">
                    ${item.title ?? '-'}
                </div>

                <div>
                    📍 ${item.address ?? '-'}
                </div>

                <div style="margin-top:6px">
                    🕒 ${item.created ?? '-'}
                </div>

                <div style="margin-top:6px;color:#666">
                    Lat: ${item.lat}<br>
                    Lng: ${item.lng}
                </div>
            </div>
        `);

        bounds.push([
            parseFloat(item.lat),
            parseFloat(item.lng)
        ]);
    });

    if (bounds.length > 0) {

        map.fitBounds(bounds, {
            padding: [50, 50]
        });

    } else {

        map.setView(
            [-6.2088, 106.8456],
            10
        );

        L.popup()
            .setLatLng([-6.2088, 106.8456])
            .setContent('Belum ada data lokasi')
            .openOn(map);
    }

    setTimeout(function () {
        map.invalidateSize();
    }, 500);

    console.log('Map Loaded');
    console.log(routineLocations);

});

</script>
@endsection