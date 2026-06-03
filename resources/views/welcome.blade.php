<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BIOS - Borobudur Integrated Operation System</title>

    @vite('resources/css/app.css')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            overflow-x: hidden;
            background:
                radial-gradient(circle at top left, rgba(59, 130, 246, 0.20), transparent 30%),
                radial-gradient(circle at bottom right, rgba(37, 99, 235, 0.18), transparent 30%),
                #020617;
        }

        .glass {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .hero-image::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(to top,
                    rgba(2, 6, 23, .95),
                    rgba(2, 6, 23, .10));
        }

        .gradient-text {
            background: linear-gradient(to right, #60a5fa, #3b82f6, #2563eb);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .card-hover {
            transition: all .35s ease;
        }

        .card-hover:hover {
            transform: translateY(-8px);
        }

        .border-glow {
            border: 1px solid rgba(255, 255, 255, 0.06);
            box-shadow:
                0 0 0 1px rgba(255, 255, 255, 0.02),
                0 20px 80px rgba(0, 0, 0, 0.45);
        }

        .nav-blur {
            background: rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(20px);
        }
    </style>
</head>

<body class="text-white">

    {{-- BACKGROUND EFFECT --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none">

        <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-blue-500/20 blur-3xl rounded-full">
        </div>

        <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-indigo-500/20 blur-3xl rounded-full">
        </div>

    </div>

    {{-- NAVBAR --}}
    <nav class="fixed top-0 left-0 w-full z-50 px-4 md:px-8 py-4">

        <div class="max-w-7xl mx-auto nav-blur rounded-[28px] px-5 md:px-8 py-4 border border-white/10 shadow-2xl">

            <div class="flex items-center justify-between">

                {{-- LOGO --}}
                <div class="flex items-center gap-4">

                    <div
                        class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-400 via-blue-500 to-indigo-600 flex items-center justify-center text-3xl">
                        🏛️
                    </div>

                    <div>
                        <h1 class="font-black text-lg md:text-xl tracking-wide">
                            BIOS
                        </h1>

                        <p class="text-[11px] md:text-xs text-slate-300">
                            Borobudur Integrated Operation System
                        </p>
                    </div>

                </div>
                {{-- BUTTON --}}
                <div class="flex items-center ">

                    <a href="{{ route('login') }}"
                        class="px-5 py-2.5 rounded-2xl text-sm font-bold bg-gradient-to-r from-blue-500 to-indigo-600 shadow-xl hover:scale-105 transition">

                        Login

                    </a>


                </div>

            </div>

        </div>

    </nav>

    {{-- HERO --}}
    <section id="home" class="relative min-h-screen flex items-center px-4 md:px-8 pt-36 pb-24">

        <div class="max-w-7xl mx-auto w-full">

            <div class="grid lg:grid-cols-2 gap-16 items-center">

                {{-- LEFT --}}
                <div class="relative z-10">

                    <div class="inline-flex items-center gap-2 px-5 py-2 rounded-full glass mb-7">

                        <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>

                        <span class="text-sm font-semibold text-slate-200">
                            Smart Task & Todo Management Platform
                        </span>

                    </div>

                    <h1 class="text-5xl sm:text-6xl lg:text-7xl font-black leading-[1.05]">

                        BOROBUDUR
                        <span class="gradient-text">
                            INTEGRATED
                        </span>

                        OPERATION SYSTEM

                    </h1>

                    <p class="mt-8 text-base sm:text-lg text-slate-300 leading-relaxed max-w-2xl">

                        BIOS adalah aplikasi modern untuk mengelola task,
                        todo list, checklist pekerjaan, monitoring aktivitas,
                        dan progress operasional tim secara real-time
                        dengan tampilan elegan, modern, dan mobile friendly.

                    </p>

                    {{-- BUTTON --}}
                    <div class="flex flex-col sm:flex-row gap-4 mt-10">

                        <a href="#features"
                            class="px-8 py-4 rounded-2xl bg-gradient-to-r from-blue-500 to-indigo-600 font-bold shadow-2xl hover:scale-[1.03] transition text-center">

                            Explore Features

                        </a>

                        <a href="#about"
                            class="px-8 py-4 rounded-2xl glass font-semibold hover:bg-white/10 transition text-center">

                            Learn More

                        </a>

                    </div>

                    {{-- STATS --}}
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mt-12">

                        <div class="glass rounded-3xl p-5 border-glow">
                            <h2 class="text-3xl font-black">10K+</h2>
                            <p class="text-slate-300 text-sm mt-1">
                                Tasks Managed
                            </p>
                        </div>

                        <div class="glass rounded-3xl p-5 border-glow">
                            <h2 class="text-3xl font-black">99%</h2>
                            <p class="text-slate-300 text-sm mt-1">
                                Productivity
                            </p>
                        </div>

                        <div class="glass rounded-3xl p-5 border-glow col-span-2 sm:col-span-1">
                            <h2 class="text-3xl font-black">24/7</h2>
                            <p class="text-slate-300 text-sm mt-1">
                                Monitoring
                            </p>
                        </div>

                    </div>

                </div>

                {{-- RIGHT --}}
                <div class="relative">

                    {{-- GLOW --}}
                    <div class="absolute -top-10 -left-10 w-60 h-60 bg-blue-500/30 rounded-full blur-3xl">
                    </div>

                    <div class="absolute bottom-0 right-0 w-60 h-60 bg-indigo-500/20 rounded-full blur-3xl">
                    </div>

                    {{-- IMAGE --}}
                    <div
                        class="relative rounded-[36px] overflow-hidden border border-white/10 shadow-[0_25px_80px_rgba(0,0,0,0.5)] hero-image">
                        <img src="https://bimasbuddha.kemenag.go.id/admin/public/images/news/1260.jpg"
                            alt="Candi Borobudur" class="w-full h-[500px] md:h-[650px] object-cover">
                        {{-- OVERLAY --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/30 to-transparent">
                        </div>

                        {{-- CONTENT --}}
                        <div class="absolute bottom-0 left-0 right-0 p-6 md:p-10">

                            <div class="glass rounded-[30px] p-6 md:p-8 border border-white/10">

                                <div class="flex items-center justify-between gap-4 mb-6">

                                    <div>
                                        <p class="text-slate-300 text-sm">
                                            Productivity Dashboard
                                        </p>

                                        <h2 class="text-2xl md:text-4xl font-black mt-2">
                                            Smart Todo List
                                        </h2>
                                    </div>

                                    <div
                                        class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-2xl shadow-xl">
                                        🚀
                                    </div>

                                </div>

                                {{-- TASKS --}}
                                <div class="space-y-4">

                                    <div
                                        class="flex items-center justify-between p-4 rounded-2xl bg-white/5 border border-white/5">

                                        <div class="flex items-center gap-4">

                                            <div
                                                class="w-11 h-11 rounded-xl bg-blue-500/20 flex items-center justify-center">
                                                ✅
                                            </div>

                                            <div>
                                                <h3 class="font-semibold">
                                                    Daily Checklist
                                                </h3>

                                                <p class="text-sm text-slate-300">
                                                    Team Productivity
                                                </p>
                                            </div>

                                        </div>

                                        <span
                                            class="px-3 py-1 rounded-full bg-blue-500/20 text-blue-300 text-xs font-semibold">

                                            Completed

                                        </span>

                                    </div>

                                    <div
                                        class="flex items-center justify-between p-4 rounded-2xl bg-white/5 border border-white/5">

                                        <div class="flex items-center gap-4">

                                            <div
                                                class="w-11 h-11 rounded-xl bg-indigo-500/20 flex items-center justify-center">
                                                📊
                                            </div>

                                            <div>
                                                <h3 class="font-semibold">
                                                    Progress Monitoring
                                                </h3>

                                                <p class="text-sm text-slate-300">
                                                    Operational Activity
                                                </p>
                                            </div>

                                        </div>

                                        <span
                                            class="px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-300 text-xs font-semibold">

                                            Ongoing

                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

    {{-- FEATURES --}}
    <section id="features" class="relative py-24 px-4 md:px-8">

        <div class="max-w-7xl mx-auto">

            {{-- HEADER --}}
            <div class="text-center max-w-3xl mx-auto">

                <span class="inline-flex px-5 py-2 rounded-full glass text-sm font-semibold text-blue-300 mb-6">

                    BIOS FEATURES

                </span>

                <h2 class="text-4xl md:text-6xl font-black leading-tight">

                    Modern
                    <span class="gradient-text">
                        Productivity
                    </span>
                    Experience

                </h2>

                <p class="mt-6 text-slate-300 text-lg leading-relaxed">

                    Dibangun dengan tampilan modern,
                    responsive, mobile friendly,
                    dan pengalaman pengguna yang elegan.

                </p>

            </div>

            {{-- CARDS --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mt-20">

                {{-- CARD 1 --}}
                <div class="glass rounded-[32px] p-8 card-hover border-glow">

                    <div class="w-16 h-16 rounded-3xl bg-blue-500/20 flex items-center justify-center text-3xl mb-6">
                        ✅
                    </div>

                    <h3 class="text-2xl font-bold">
                        Todo List
                    </h3>

                    <p class="mt-4 text-slate-300 leading-relaxed">
                        Membuat dan mengatur task harian secara mudah dan realtime.
                    </p>

                </div>

                {{-- CARD 2 --}}
                <div class="glass rounded-[32px] p-8 card-hover border-glow">

                    <div class="w-16 h-16 rounded-3xl bg-sky-500/20 flex items-center justify-center text-3xl mb-6">
                        📊
                    </div>

                    <h3 class="text-2xl font-bold">
                        Monitoring
                    </h3>

                    <p class="mt-4 text-slate-300 leading-relaxed">
                        Monitoring aktivitas dan progress tim secara terintegrasi.
                    </p>

                </div>

                {{-- CARD 3 --}}
                <div class="glass rounded-[32px] p-8 card-hover border-glow">

                    <div class="w-16 h-16 rounded-3xl bg-indigo-500/20 flex items-center justify-center text-3xl mb-6">
                        📁
                    </div>

                    <h3 class="text-2xl font-bold">
                        Checklist
                    </h3>

                    <p class="mt-4 text-slate-300 leading-relaxed">
                        Sistem checklist modern untuk operasional harian.
                    </p>

                </div>

                {{-- CARD 4 --}}
                <div class="glass rounded-[32px] p-8 card-hover border-glow">

                    <div class="w-16 h-16 rounded-3xl bg-cyan-500/20 flex items-center justify-center text-3xl mb-6">
                        🚀
                    </div>

                    <h3 class="text-2xl font-bold">
                        Productivity
                    </h3>

                    <p class="mt-4 text-slate-300 leading-relaxed">
                        Meningkatkan efisiensi dan produktivitas kerja tim.
                    </p>

                </div>

            </div>

        </div>

    </section>

</body>


</html>