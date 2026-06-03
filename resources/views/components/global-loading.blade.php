<div id="globalLoading"
     class="fixed inset-0 z-[99999] hidden items-center justify-center bg-slate-950/40 backdrop-blur-sm">

    <div class="relative flex flex-col items-center">

        {{-- GLOW --}}
        <div class="absolute w-52 h-52 bg-indigo-500/20 blur-3xl rounded-full"></div>

        {{-- CARD --}}
        <div class="relative bg-white border border-slate-200 rounded-[32px] px-10 py-8 shadow-2xl flex flex-col items-center">

            {{-- SPINNER --}}
            <div class="relative w-20 h-20">

                {{-- OUTER --}}
                <div class="absolute inset-0 rounded-full border-[6px] border-indigo-100"></div>

                {{-- SPIN --}}
                <div class="absolute inset-0 rounded-full border-[6px] border-transparent border-t-indigo-600 animate-spin"></div>

                {{-- CENTER --}}
                <div class="absolute inset-4 rounded-full bg-indigo-600/10 flex items-center justify-center">

                    <div class="w-5 h-5 rounded-full bg-indigo-600 animate-pulse"></div>

                </div>

            </div>

            {{-- TEXT --}}
            <div class="mt-6 text-center">

                <h3 class="text-xl font-bold text-slate-800">
                    Loading...
                </h3>

                <p class="text-sm text-slate-500 mt-1">
                    Please wait a moment
                </p>

            </div>

        </div>

    </div>

</div>