{{--
    ╔══════════════════════════════════════════════════════════╗
    ║         GLOBAL MODAL & TOAST COMPONENT                  ║
    ║  Include sekali di layouts/app.blade.php sebelum </body> ║
    ║  @include('components.global-modal')                    ║
    ╚══════════════════════════════════════════════════════════╝
--}}

{{-- ── CONFIRM / ALERT MODAL ────────────────────────────────── --}}
<div id="gModalOverlay"
     onclick="if(event.target===this && window._gModalDismissable) gModal.close()"
     class="fixed inset-0 z-[9999] hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">

    <div id="gModalBox"
         class="w-full max-w-md overflow-hidden rounded-[28px] bg-white shadow-2xl border border-slate-200
                scale-95 opacity-0 transition-all duration-300 ease-[cubic-bezier(0.34,1.56,0.64,1)]">

        {{-- Top accent bar (color injected by JS) --}}
        <div id="gModalAccent" class="h-1 w-full"></div>

        <div class="px-7 pt-7 pb-5 flex flex-col items-center text-center">

            {{-- Icon --}}
            <div id="gModalIconWrap"
                 class="w-14 h-14 rounded-2xl flex items-center justify-center mb-4 shrink-0">
            </div>

            {{-- Title --}}
            <h2 id="gModalTitle"
                class="text-xl font-black text-slate-900 leading-tight"></h2>

            {{-- Message --}}
            <p id="gModalMessage"
               class="mt-2 text-sm text-slate-500 leading-relaxed max-w-xs"></p>

            {{-- Optional highlight box (e.g. item name) --}}
            <div id="gModalHighlight"
                 class="hidden mt-3 w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-100">
                <p id="gModalHighlightText" class="text-sm font-semibold text-slate-700 truncate"></p>
            </div>

        </div>

        {{-- Footer buttons --}}
        <div id="gModalFooter"
             class="flex gap-3 px-7 pb-7"></div>

    </div>
</div>

{{-- ── TOAST CONTAINER ──────────────────────────────────────── --}}
<div id="gToastContainer"
     class="fixed top-5 right-5 z-[99999] flex flex-col items-end gap-3 pointer-events-none"
     style="max-width: 360px; width: calc(100vw - 40px)">
</div>

<style>
/* ── Modal open/close states ── */
#gModalOverlay.g-open  { display: flex; }
#gModalOverlay.g-open #gModalBox { opacity: 1; transform: scale(1); }

/* ── Toast slide-in ── */
.g-toast {
    pointer-events: all;
    transform: translateX(110%);
    opacity: 0;
    transition: transform 0.32s cubic-bezier(0.34,1.4,0.64,1), opacity 0.25s ease;
}
.g-toast.g-show  { transform: translateX(0); opacity: 1; }
.g-toast.g-hide  { transform: translateX(110%); opacity: 0; transition-duration: 0.28s; }

/* ── Progress bar ── */
@keyframes gToastProgress {
    from { width: 100%; }
    to   { width: 0%; }
}
.g-toast-progress {
    position: absolute; bottom: 0; left: 0; height: 3px;
    border-radius: 0 0 14px 14px;
    animation: gToastProgress linear forwards;
}
</style>

<script>
/* ═══════════════════════════════════════════════════════════════
   gModal  –  confirm / alert helper
   ═══════════════════════════════════════════════════════════════ */
window.gModal = (() => {

    const overlay     = document.getElementById('gModalOverlay');
    const box         = document.getElementById('gModalBox');
    const accent      = document.getElementById('gModalAccent');
    const iconWrap    = document.getElementById('gModalIconWrap');
    const titleEl     = document.getElementById('gModalTitle');
    const msgEl       = document.getElementById('gModalMessage');
    const highlightEl = document.getElementById('gModalHighlight');
    const hlText      = document.getElementById('gModalHighlightText');
    const footer      = document.getElementById('gModalFooter');

    /* built-in type configs */
    const TYPES = {
        delete: {
            accent : '#ef4444',
            iconBg : '#fef2f2',
            iconClr: '#dc2626',
            svg    : `<svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <polyline points="3 6 5 6 21 6"/>
                        <path d="M19 6l-1 14H6L5 6"/>
                        <path d="M10 11v6M14 11v6"/>
                        <path d="M9 6V4h6v2"/>
                      </svg>`,
            btnClass: 'g-btn-danger',
        },
        unassign: {
            accent : '#f59e0b',
            iconBg : '#fffbeb',
            iconClr: '#d97706',
            svg    : `<svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <line x1="17" y1="11" x2="23" y2="11"/>
                      </svg>`,
            btnClass: 'g-btn-warning',
        },
        warning: {
            accent : '#f59e0b',
            iconBg : '#fffbeb',
            iconClr: '#d97706',
            svg    : `<svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        <line x1="12" y1="9" x2="12" y2="13"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                      </svg>`,
            btnClass: 'g-btn-warning',
        },
        info: {
            accent : '#3b82f6',
            iconBg : '#eff6ff',
            iconClr: '#2563eb',
            svg    : `<svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="16" x2="12" y2="12"/>
                        <line x1="12" y1="8" x2="12.01" y2="8"/>
                      </svg>`,
            btnClass: 'g-btn-primary',
        },
        success: {
            accent : '#22c55e',
            iconBg : '#f0fdf4',
            iconClr: '#16a34a',
            svg    : `<svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                      </svg>`,
            btnClass: 'g-btn-success',
        },
    };

    /* button style classes (Tailwind) */
    const BTN_BASE = 'flex-1 h-11 rounded-2xl text-sm font-semibold transition-all duration-150 active:scale-[0.98] cursor-pointer border-0';
    const BTN_GHOST = `${BTN_BASE} bg-slate-100 hover:bg-slate-200 text-slate-700`;
    const BTN_MAP = {
        'g-btn-danger' : `${BTN_BASE} bg-red-500 hover:bg-red-600 text-white shadow-lg shadow-red-100`,
        'g-btn-warning': `${BTN_BASE} bg-amber-500 hover:bg-amber-600 text-white shadow-lg shadow-amber-100`,
        'g-btn-primary': `${BTN_BASE} bg-indigo-600 hover:bg-indigo-700 text-white shadow-lg shadow-indigo-100`,
        'g-btn-success': `${BTN_BASE} bg-emerald-500 hover:bg-emerald-600 text-white shadow-lg shadow-emerald-100`,
    };

    window._gModalDismissable = true;

    function _open({ type = 'info', title, message, highlight = null,
                     confirmLabel = 'OK', cancelLabel = 'Batal',
                     onConfirm = null, showCancel = true }) {

        const cfg = TYPES[type] || TYPES.info;

        /* accent bar */
        accent.style.background = cfg.accent;

        /* icon */
        iconWrap.style.background = cfg.iconBg;
        iconWrap.style.color = cfg.iconClr;
        iconWrap.innerHTML = cfg.svg;

        /* text */
        titleEl.textContent = title || '';
        msgEl.textContent   = message || '';

        /* optional highlight item name */
        if (highlight) {
            hlText.textContent = highlight;
            highlightEl.classList.remove('hidden');
        } else {
            highlightEl.classList.add('hidden');
        }

        /* buttons */
        footer.innerHTML = '';
        if (showCancel) {
            const cancelBtn = document.createElement('button');
            cancelBtn.type = 'button';
            cancelBtn.className = BTN_GHOST;
            cancelBtn.textContent = cancelLabel;
            cancelBtn.onclick = () => close();
            footer.appendChild(cancelBtn);
        }

        const confirmBtn = document.createElement('button');
        confirmBtn.type = 'button';
        confirmBtn.className = BTN_MAP[cfg.btnClass] || BTN_MAP['g-btn-primary'];
        confirmBtn.textContent = confirmLabel;
        confirmBtn.onclick = () => {
            close();
            if (typeof onConfirm === 'function') onConfirm();
        };
        footer.appendChild(confirmBtn);

        window._gModalDismissable = showCancel;

        /* show */
        overlay.classList.add('g-open');
        document.body.style.overflow = 'hidden';
    }

    function close() {
        box.style.opacity = '0';
        box.style.transform = 'scale(0.95)';
        setTimeout(() => {
            overlay.classList.remove('g-open');
            box.style.opacity = '';
            box.style.transform = '';
            document.body.style.overflow = '';
        }, 220);
    }

    /* keyboard ESC */
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && overlay.classList.contains('g-open') && window._gModalDismissable) {
            close();
        }
    });

    return {
        /* confirm – resolves boolean via onConfirm callback */
        confirm: (opts) => _open({ showCancel: true, ...opts }),

        /* alert – info only, no cancel */
        alert: (message, type = 'info', title = null) => _open({
            type,
            title: title || (type === 'warning' ? 'Perhatian' : type === 'success' ? 'Berhasil' : 'Informasi'),
            message,
            showCancel: false,
            confirmLabel: 'OK',
        }),

        close,
    };
})();


/* ═══════════════════════════════════════════════════════════════
   gToast  –  non-blocking toast notification
   ═══════════════════════════════════════════════════════════════ */
window.gToast = (() => {

    const container = document.getElementById('gToastContainer');
    let _id = 0;

    const ICONS = {
        success: { bg:'#f0fdf4', clr:'#16a34a', svg:`<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>`, bar:'#16a34a' },
        error  : { bg:'#fef2f2', clr:'#dc2626', svg:`<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>`, bar:'#dc2626' },
        warning: { bg:'#fffbeb', clr:'#d97706', svg:`<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>`, bar:'#f59e0b' },
        info   : { bg:'#eff6ff', clr:'#2563eb', svg:`<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>`, bar:'#3b82f6' },
    };

    function show(type = 'info', title = '', message = '', duration = 4000) {
        const cfg = ICONS[type] || ICONS.info;
        const id  = 'gt-' + (++_id);

        const el = document.createElement('div');
        el.id = id;
        el.className = 'g-toast relative flex items-start gap-3 bg-white border border-slate-200 rounded-2xl px-4 py-3 shadow-xl w-full overflow-hidden';
        el.style.cssText = 'max-width:360px;';

        el.innerHTML = `
            <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 mt-0.5"
                 style="background:${cfg.bg}; color:${cfg.clr}">${cfg.svg}</div>
            <div class="flex-1 min-w-0 pt-0.5">
                <p class="text-sm font-semibold text-slate-800 leading-tight truncate">${title}</p>
                ${message ? `<p class="text-xs text-slate-500 mt-0.5 leading-relaxed">${message}</p>` : ''}
            </div>
            <button onclick="gToast.remove('${id}')"
                    class="text-slate-400 hover:text-slate-700 ml-1 shrink-0 mt-0.5 text-lg leading-none bg-transparent border-0 cursor-pointer">
                ×
            </button>
            <div class="g-toast-progress" style="background:${cfg.bar}; animation-duration:${duration}ms;"></div>
        `;

        container.appendChild(el);
        requestAnimationFrame(() => requestAnimationFrame(() => el.classList.add('g-show')));

        if (duration > 0) {
            setTimeout(() => _remove(el), duration);
        }

        return id;
    }

    function _remove(el) {
        if (!el) return;
        el.classList.remove('g-show');
        el.classList.add('g-hide');
        setTimeout(() => el.remove(), 320);
    }

    return {
        show,
        success: (title, msg, dur)  => show('success', title, msg, dur),
        error  : (title, msg, dur)  => show('error',   title, msg, dur),
        warning: (title, msg, dur)  => show('warning', title, msg, dur),
        info   : (title, msg, dur)  => show('info',    title, msg, dur),
        remove : (id) => _remove(document.getElementById(id)),
    };
})();
</script>