{{-- resources/views/auth/login.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>BIOS Login</title>
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
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-[#0b1120]">

{{-- ====================================== --}}
{{-- BACKGROUND --}}
{{-- ====================================== --}}
<div class="fixed inset-0 overflow-hidden -z-10">

    <div class="absolute -top-40 -left-40 w-[350px] h-[350px] bg-indigo-500/20 rounded-full blur-3xl"></div>

    <div class="absolute bottom-0 right-0 w-[300px] h-[300px] bg-cyan-500/10 rounded-full blur-3xl"></div>

</div>

{{-- ====================================== --}}
{{-- WRAPPER --}}
{{-- ====================================== --}}
<div class="min-h-screen flex items-center justify-center px-4 py-8 sm:py-10 overflow-y-auto">

    <div class="w-full max-w-4xl grid grid-cols-1 lg:grid-cols-2 rounded-[28px] overflow-hidden border border-white/10 bg-white/80 backdrop-blur-2xl shadow-[0_20px_60px_rgba(0,0,0,0.35)]">

        {{-- ====================================== --}}
        {{-- LEFT SIDE --}}
        {{-- ====================================== --}}
        <div class="hidden lg:flex flex-col justify-between bg-gradient-to-br from-[#101935] via-[#132850] to-[#0a1124] p-10 text-white">

            {{-- TOP --}}
            <div>

                <div class="flex items-center gap-4">

                    <div class="w-14 h-14 rounded-2xl bg-white/10 border border-white/10 flex items-center justify-center text-3xl">
                        🏛️
                    </div>

                    <div>

                        <h1 class="text-3xl font-black">
                            BIOS
                        </h1>

                        <p class="text-sm text-slate-300">
                            Integrated Operation System
                        </p>

                    </div>

                </div>

                <div class="mt-16">

                    <span class="px-4 py-2 rounded-full bg-white/10 border border-white/10 text-xs tracking-wide">
                        Enterprise Dashboard
                    </span>

                      <h2 class="mt-6 text-4xl font-black leading-tight">
<span class="gradient-text">
    Smart Access
</span>
                      
                           For Modern
  

                        Workspace

                    </h2>

                    <p class="mt-5 text-slate-300 leading-relaxed text-sm">

                       BIOS adalah aplikasi modern untuk mengelola task,
                        todo list, checklist pekerjaan, monitoring aktivitas,
                        dan progress operasional tim secara real-time
                        dengan tampilan elegan, modern, dan mobile friendly.

                    </p>

                </div>

            </div>

            {{-- STATS --}}
            <div class="grid grid-cols-3 gap-3 mt-10">

                <div class="bg-white/10 rounded-2xl p-4 border border-white/10">

                    <h3 class="text-2xl font-black">
                        99%
                    </h3>

                    <p class="text-xs text-slate-300 mt-1">
                        Stability
                    </p>

                </div>

                <div class="bg-white/10 rounded-2xl p-4 border border-white/10">

                    <h3 class="text-2xl font-black">
                        24/7
                    </h3>

                    <p class="text-xs text-slate-300 mt-1">
                        Monitoring
                    </p>

                </div>

                <div class="bg-white/10 rounded-2xl p-4 border border-white/10">

                    <h3 class="text-2xl font-black">
                        150+
                    </h3>

                    <p class="text-xs text-slate-300 mt-1">
                        Users
                    </p>

                </div>

            </div>

        </div>

        {{-- ====================================== --}}
        {{-- RIGHT SIDE --}}
        {{-- ====================================== --}}
        <div class="bg-white px-5 sm:px-8 lg:px-10 py-8 sm:py-10 flex items-center justify-center">

            <div class="w-full max-w-sm">

                {{-- MOBILE LOGO --}}
                <div class="lg:hidden text-center mb-8">

                    <div class="w-14 h-14 mx-auto rounded-2xl bg-gradient-to-br from-indigo-600 to-indigo-800 text-white flex items-center justify-center text-2xl shadow-lg">
                        🏛️
                    </div>

                    <h1 class="mt-4 text-3xl font-black text-slate-800">
                        BIOS
                    </h1>

                    <p class="text-sm text-slate-500 mt-1">
                       Borobudur Integrated Operation System
                    </p>

                </div>

                {{-- TITLE --}}
                <div class="mb-7">

                    <p class="text-indigo-600 font-bold uppercase tracking-[3px] text-xs">
                        Welcome Back
                    </p>

                    <h2 class="mt-2 text-3xl font-black text-slate-800">
                        Sign In
                    </h2>

                    <p class="mt-2 text-sm text-slate-500">
                        Login to continue your dashboard
                    </p>

                </div>

                {{-- FORM --}}
                <form method="POST"
                      action="{{ route('login') }}"
                      class="space-y-5">

                    @csrf

                    {{-- EMAIL --}}
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Email Address
                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="email"
                            placeholder="Enter your email"
                            class="w-full h-12 rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition"
                        >

                        @error('email')

                            <p class="text-red-500 text-xs mt-2">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>

                    {{-- PASSWORD --}}
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Password
                        </label>

                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Enter your password"
                            class="w-full h-12 rounded-xl border border-slate-200 bg-slate-50 px-4 text-sm outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition"
                        >

                        @error('password')

                            <p class="text-red-500 text-xs mt-2">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>

                    {{-- REMEMBER --}}
                    <div class="flex items-center justify-between text-sm">

                        <label class="flex items-center gap-2 cursor-pointer">

                            <input
                                type="checkbox"
                                name="remember"
                                id="remember"
                                {{ old('remember') ? 'checked' : '' }}
                                class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                            >

                            <span class="text-slate-600">
                                Remember me
                            </span>

                        </label>

                        @if (Route::has('password.request'))

                            <a href="{{ route('password.request') }}"
                               class="text-indigo-600 font-semibold hover:text-indigo-700 transition">

                                Forgot?

                            </a>

                        @endif

                    </div>

                    {{-- BUTTON --}}
                    <button
                        type="submit"
                        class="w-full h-12 rounded-xl bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-bold text-sm shadow-lg shadow-indigo-200 transition-all duration-300"
                    >

                        Sign In

                    </button>

                </form>

                {{-- DEMO --}}
            

            </div>

        </div>

    </div>

</div>

</body>
</html>