<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token"
          content="{{ csrf_token() }}">

    <title>MBG Dashboard</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
<link rel="stylesheet"
          href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</head>

<body class="bg-[#eef2f7] overflow-hidden">

{{-- GLOBAL MODAL --}}
@include('components.global-modal')

{{-- GLOBAL LOADING --}}
@include('components.global-loading')

<div class="flex h-screen overflow-hidden">

    {{-- SIDEBAR --}}
    @include('components.sidebar')

    {{-- OVERLAY MOBILE --}}
    <div
        id="sidebarOverlay"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm z-30 hidden lg:hidden"
    ></div>

    {{-- MAIN --}}
    <div class="flex flex-col flex-1 overflow-hidden">

        {{-- HEADER --}}
        @include('components.header')

        {{-- CONTENT --}}
        <main class="flex-1 overflow-y-auto p-4 lg:p-6">

            @yield('content')

        </main>

        {{-- FOOTER --}}
        @include('components.footer')

    </div>

</div>

<script>

/* =========================================================
|--------------------------------------------------------------------------
| GLOBAL LOADING
|--------------------------------------------------------------------------
========================================================= */

window.gLoading = {

    el: null,

    init() {

        this.el = document.getElementById('globalLoading')

    },

    show() {

        if (!this.el) return

        this.el.classList.remove('hidden')
        this.el.classList.add('flex')

    },

    hide() {

        if (!this.el) return

        this.el.classList.add('hidden')
        this.el.classList.remove('flex')

    }

}

document.addEventListener('DOMContentLoaded', () => {

    gLoading.init()

})

/* =========================================================
|--------------------------------------------------------------------------
| AUTO FETCH LOADING
|--------------------------------------------------------------------------
========================================================= */

const originalFetch = window.fetch

window.fetch = async (...args) => {

    try {

        gLoading.show()

        const response = await originalFetch(...args)

        return response

    } catch (error) {

        throw error

    } finally {

        gLoading.hide()

    }

}

/* =========================================================
|--------------------------------------------------------------------------
| AUTO FORM SUBMIT LOADING
|--------------------------------------------------------------------------
========================================================= */

document.addEventListener('submit', function () {

    gLoading.show()

})

/* =========================================================
|--------------------------------------------------------------------------
| AUTO PAGE TRANSITION LOADING
|--------------------------------------------------------------------------
========================================================= */

document.addEventListener('click', function(e) {

    const link = e.target.closest('a')

    if (!link) return

    const href = link.getAttribute('href')

    if (
        href &&
        !href.startsWith('#') &&
        !href.startsWith('javascript:') &&
        !link.hasAttribute('target')
    ) {

        gLoading.show()

    }

})

window.addEventListener('beforeunload', () => {

    gLoading.show()

})

/* =========================================================
|--------------------------------------------------------------------------
| SIDEBAR
|--------------------------------------------------------------------------
========================================================= */

const sidebar    = document.getElementById('sidebar')
const overlay    = document.getElementById('sidebarOverlay')
const menuButton = document.getElementById('menuButton')

if (menuButton) {

    menuButton.addEventListener('click', () => {

        sidebar.classList.toggle('-translate-x-full')
        overlay.classList.toggle('hidden')

    })

}

if (overlay) {

    overlay.addEventListener('click', () => {

        sidebar.classList.add('-translate-x-full')
        overlay.classList.add('hidden')

    })

}

</script>

</body>
</html>