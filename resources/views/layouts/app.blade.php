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
    initGlobalTableTools()
    normalizeTableActionIcons()

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

/* =========================================================
|--------------------------------------------------------------------------
| GLOBAL TABLE TOOLS (SEARCH + PAGE SIZE + PAGINATION)
|--------------------------------------------------------------------------
========================================================= */

function initGlobalTableTools() {

    const tables = document.querySelectorAll('main table')

    const isElementVisible = (element) => {
        if (!element) return false
        return !!(element.offsetWidth || element.offsetHeight || element.getClientRects().length)
    }

    tables.forEach((table) => {

        if (table.dataset.tableToolsInitialized === 'true') return
        if (table.dataset.disableTableTools === 'true') return
        if (!isElementVisible(table)) return

        const tbody = table.tBodies?.[0]
        if (!tbody) return

        const allRows = Array.from(tbody.rows)
        if (!allRows.length) return

        table.dataset.tableToolsInitialized = 'true'

        const state = {
            query: '',
            pageSize: 5,
            currentPage: 1
        }

        const mountElement = (() => {
            const parent = table.parentElement

            if (!parent || parent.tagName === 'MAIN') {
                return table
            }

            const classText = (parent.className || '').toString()
            const isOverflowWrapper =
                classText.includes('overflow') ||
                classText.includes('min-w') ||
                classText.includes('table-responsive')

            return isOverflowWrapper ? parent : table
        })()

        const toolsContainer = document.createElement('div')
        toolsContainer.className = 'mb-3 rounded-xl border border-slate-200 bg-white/90 p-3 shadow-sm'

        const toolbarRow = document.createElement('div')
        toolbarRow.className = 'flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between'

        const leftTools = document.createElement('div')
        leftTools.className = 'flex flex-wrap items-center gap-2'

        const perPageWrap = document.createElement('div')
        perPageWrap.className = 'inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1.5'

        const perPageLabel = document.createElement('label')
        perPageLabel.className = 'text-xs font-medium uppercase tracking-wide text-slate-500'
        perPageLabel.textContent = 'Tampilkan'

        const pageSizeSelect = document.createElement('select')
        pageSizeSelect.className = 'rounded-md border border-slate-300 bg-white px-2 py-1 text-sm font-semibold text-slate-700 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100'

        ;[5, 10, 20, 40].forEach((size) => {
            const option = document.createElement('option')
            option.value = String(size)
            option.textContent = String(size)
            if (size === 5) option.selected = true
            pageSizeSelect.appendChild(option)
        })

        const perPageSuffix = document.createElement('span')
        perPageSuffix.className = 'text-xs font-medium uppercase tracking-wide text-slate-500'
        perPageSuffix.textContent = 'data'

        perPageWrap.appendChild(perPageLabel)
        perPageWrap.appendChild(pageSizeSelect)
        perPageWrap.appendChild(perPageSuffix)

        leftTools.appendChild(perPageWrap)

        const hintText = document.createElement('span')
        hintText.className = 'text-xs text-slate-500'
        hintText.textContent = 'Gunakan search untuk filter cepat'
        leftTools.appendChild(hintText)

        const searchWrap = document.createElement('div')
        searchWrap.className = 'relative w-full lg:w-72'

        const searchIcon = document.createElement('span')
        searchIcon.className = 'pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400'
        searchIcon.textContent = '⌕'

        const searchInput = document.createElement('input')
        searchInput.type = 'search'
        searchInput.placeholder = 'Cari data tabel...'
        searchInput.className = 'w-full rounded-lg border border-slate-300 bg-white py-2 pl-8 pr-3 text-sm text-slate-700 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100'

        searchWrap.appendChild(searchIcon)
        searchWrap.appendChild(searchInput)

        toolbarRow.appendChild(leftTools)
        toolbarRow.appendChild(searchWrap)

        toolsContainer.appendChild(toolbarRow)

        mountElement.parentNode.insertBefore(toolsContainer, mountElement)

        const paginationContainer = document.createElement('div')
        paginationContainer.className = 'mt-3 rounded-xl border border-slate-200 bg-white/90 px-3 py-2 shadow-sm'

        const paginationRow = document.createElement('div')
        paginationRow.className = 'flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between'

        const infoText = document.createElement('div')
        infoText.className = 'text-sm font-medium text-slate-600'

        const pagerButtons = document.createElement('div')
        pagerButtons.className = 'flex flex-wrap items-center gap-1.5 sm:justify-end'

        const createPagerButton = (label) => {
            const button = document.createElement('button')
            button.type = 'button'
            button.className = 'inline-flex h-8 min-w-8 items-center justify-center rounded-md border border-slate-300 bg-white px-2 text-xs font-semibold text-slate-700 transition hover:border-sky-300 hover:bg-sky-50 hover:text-sky-700 disabled:cursor-not-allowed disabled:opacity-45'
            button.textContent = label
            return button
        }

        const firstButton = createPagerButton('<<')
        const prevButton = createPagerButton('<')

        const pageNumbers = document.createElement('div')
        pageNumbers.className = 'inline-flex items-center gap-1'

        const nextButton = createPagerButton('>')
        const lastButton = createPagerButton('>>')

        pagerButtons.appendChild(firstButton)
        pagerButtons.appendChild(prevButton)
        pagerButtons.appendChild(pageNumbers)
        pagerButtons.appendChild(nextButton)
        pagerButtons.appendChild(lastButton)

        paginationRow.appendChild(infoText)
        paginationRow.appendChild(pagerButtons)
        paginationContainer.appendChild(paginationRow)

        if (mountElement.nextSibling) {
            mountElement.parentNode.insertBefore(paginationContainer, mountElement.nextSibling)
        } else {
            mountElement.parentNode.appendChild(paginationContainer)
        }

        function getFilteredRows() {
            const query = state.query.trim().toLowerCase()

            if (!query) return allRows

            return allRows.filter((row) => row.innerText.toLowerCase().includes(query))
        }

        function render() {
            const filteredRows = getFilteredRows()
            const totalRows = filteredRows.length
            const totalPages = Math.max(1, Math.ceil(totalRows / state.pageSize))

            if (state.currentPage > totalPages) {
                state.currentPage = totalPages
            }

            const startIndex = (state.currentPage - 1) * state.pageSize
            const endIndex = startIndex + state.pageSize
            const visibleRows = filteredRows.slice(startIndex, endIndex)

            allRows.forEach((row) => {
                row.style.display = 'none'
            })

            visibleRows.forEach((row) => {
                row.style.display = ''
            })

            const showingFrom = totalRows === 0 ? 0 : startIndex + 1
            const showingTo = Math.min(endIndex, totalRows)

            infoText.textContent = `Menampilkan ${showingFrom}-${showingTo} dari ${totalRows} data`
            pageNumbers.innerHTML = ''

            const startPage = Math.max(1, state.currentPage - 2)
            const endPage = Math.min(totalPages, startPage + 4)

            for (let page = startPage; page <= endPage; page++) {
                const pageButton = createPagerButton(String(page))

                if (page === state.currentPage) {
                    pageButton.className = 'inline-flex h-8 min-w-8 items-center justify-center rounded-md border border-sky-500 bg-sky-500 px-2 text-xs font-semibold text-white'
                }

                pageButton.addEventListener('click', () => {
                    state.currentPage = page
                    render()
                })

                pageNumbers.appendChild(pageButton)
            }

            firstButton.disabled = state.currentPage <= 1
            prevButton.disabled = state.currentPage <= 1
            nextButton.disabled = state.currentPage >= totalPages
            lastButton.disabled = state.currentPage >= totalPages
        }

        pageSizeSelect.addEventListener('change', (event) => {
            state.pageSize = Number(event.target.value) || 5
            state.currentPage = 1
            render()
        })

        searchInput.addEventListener('input', (event) => {
            state.query = event.target.value || ''
            state.currentPage = 1
            render()
        })

        prevButton.addEventListener('click', () => {
            if (state.currentPage > 1) {
                state.currentPage -= 1
                render()
            }
        })

        firstButton.addEventListener('click', () => {
            if (state.currentPage > 1) {
                state.currentPage = 1
                render()
            }
        })

        nextButton.addEventListener('click', () => {
            const totalRows = getFilteredRows().length
            const totalPages = Math.max(1, Math.ceil(totalRows / state.pageSize))

            if (state.currentPage < totalPages) {
                state.currentPage += 1
                render()
            }
        })

        lastButton.addEventListener('click', () => {
            const totalRows = getFilteredRows().length
            const totalPages = Math.max(1, Math.ceil(totalRows / state.pageSize))

            if (state.currentPage < totalPages) {
                state.currentPage = totalPages
                render()
            }
        })

        render()

    })

}

/* =========================================================
|--------------------------------------------------------------------------
| GLOBAL TABLE ACTION ICON NORMALIZER
|--------------------------------------------------------------------------
========================================================= */

function createTableHeroIcon(name) {
    const paths = {
        edit: 'm16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125',
        trash: 'm14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0 1 15.916 21.75H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0C9.16 2.313 8.25 3.296 8.25 4.477v.916m7.5 0a48.667 48.667 0 0 0-7.5 0',
        eye: 'M2.036 12.322a1.012 1.012 0 0 1 0-.644C3.423 7.51 7.36 4.5 12 4.5s8.577 3.01 9.964 7.178c.07.207.07.437 0 .644C20.577 16.49 16.64 19.5 12 19.5s-8.577-3.01-9.964-7.178Z',
        eyeInner: 'M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z',
        download: 'M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M7.5 10.5 12 15m0 0 4.5-4.5M12 15V3',
        users: 'M18 18.72a9.094 9.094 0 0 0 3.742-.479 3 3 0 0 0-4.682-2.72m.94 3.198v.001c0 .518-.115 1.01-.321 1.452m.32-1.453a5.997 5.997 0 0 0-11.358 0m11.358 0A5.99 5.99 0 0 1 12 21a5.99 5.99 0 0 1-5.999-5.281m0 0a3 3 0 0 0-4.681 2.72A9.094 9.094 0 0 0 5.06 18.72m.94-3.197a3 3 0 1 1 5.999 0 3 3 0 0 1-6 0m6 0a3 3 0 1 1 5.999 0 3 3 0 0 1-6 0M9 7.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0Z',
        checklist: 'M9 12.75 11.25 15 15 9.75m6 2.25c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9Z',
        close: 'M6 18 18 6M6 6l12 12'
    }

    const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg')
    svg.setAttribute('viewBox', '0 0 24 24')
    svg.setAttribute('fill', 'none')
    svg.setAttribute('stroke', 'currentColor')
    svg.setAttribute('stroke-width', '1.5')
    svg.setAttribute('class', 'h-4 w-4')
    svg.setAttribute('aria-hidden', 'true')

    const path = document.createElementNS('http://www.w3.org/2000/svg', 'path')
    path.setAttribute('stroke-linecap', 'round')
    path.setAttribute('stroke-linejoin', 'round')
    path.setAttribute('d', paths[name] || paths.close)
    svg.appendChild(path)

    if (name === 'eye') {
        const innerPath = document.createElementNS('http://www.w3.org/2000/svg', 'path')
        innerPath.setAttribute('stroke-linecap', 'round')
        innerPath.setAttribute('stroke-linejoin', 'round')
        innerPath.setAttribute('d', paths.eyeInner)
        svg.appendChild(innerPath)
    }

    return svg
}

function normalizeTableActionIcons() {

    const actionCells = document.querySelectorAll('main table tbody tr td:last-child')

    actionCells.forEach((cell) => {
        const controls = cell.querySelectorAll('a, button')

        controls.forEach((control) => {
            if (control.closest('form.hidden')) return

            const hasSvg = !!control.querySelector('svg')
            const rawText = (control.textContent || '').replace(/\s+/g, ' ').trim()
            const hasIconText = /[✕👁🗑⬇📄🖼📎📊➤]/u.test(rawText)

            const semanticIcon = (() => {
                const text = rawText.toLowerCase()
                if (text.includes('edit')) return 'edit'
                if (text.includes('hapus') || text.includes('delete')) return 'trash'
                if (text.includes('lihat') || text.includes('view')) return 'eye'
                if (text.includes('download')) return 'download'
                if (text.includes('assign')) return 'users'
                if (text.includes('checklist')) return 'checklist'
                if (text === '✕') return 'close'
                return null
            })()

            if (!hasSvg && !hasIconText && !semanticIcon) return

            const removableClasses = Array.from(control.classList).filter((name) =>
                /^(bg-|text-|border-|hover:bg-|hover:text-|hover:border-|shadow|ring|from-|to-|via-)/.test(name)
            )

            removableClasses.forEach((name) => control.classList.remove(name))

            control.classList.add('inline-flex', 'items-center', 'justify-center', 'rounded-lg', 'border', 'border-slate-300', 'text-slate-700', 'transition', 'hover:bg-slate-50')

            if (semanticIcon && rawText.length > 0) {
                const labelText = rawText.replace(/[✕👁️🗑⬇📄🖼📎📊➤]/gu, '').trim()
                control.innerHTML = ''
                control.appendChild(createTableHeroIcon(semanticIcon))

                if (labelText) {
                    const label = document.createElement('span')
                    label.className = 'text-xs font-medium'
                    label.textContent = labelText
                    control.classList.add('gap-1.5', 'px-3', 'py-1.5')
                    control.appendChild(label)
                } else {
                    control.classList.remove('px-3', 'py-1.5')
                    control.classList.add('h-9', 'w-9')
                }
            }

            control.querySelectorAll('svg').forEach((svg) => {
                svg.setAttribute('stroke', 'currentColor')
                svg.classList.remove('text-indigo-600', 'text-emerald-600', 'text-red-500', 'text-white', 'text-blue-600', 'text-yellow-600')
                svg.classList.add('h-4', 'w-4')
            })

            control.querySelectorAll('span').forEach((span) => {
                if (span.querySelector('svg')) {
                    span.classList.remove('border', 'border-slate-300', 'bg-white', 'p-1', 'rounded-lg')
                }
            })
        })
    })

}

</script>

</body>
</html>