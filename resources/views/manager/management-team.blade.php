@extends('layouts.app')

@section('content')

<div class="tm-wrapper">

    {{-- Header --}}
    <div class="tm-header">
        <div class="tm-header-left">
            <p class="tm-breadcrumb">Manajemen Tim</p>
            <h1 class="tm-title">Divisi <span class="tm-divisi-name">{{ $divisi }}</span></h1>
        </div>
        <span class="tm-badge-divisi">{{ $divisi }}</span>
    </div>

    {{-- Supervisor Section --}}
    <div class="tm-section">
        <div class="tm-section-header">
            <svg class="tm-section-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
            <span class="tm-section-label">Supervisor</span>
            <span class="tm-section-count">{{ $supervisors->count() }}</span>
        </div>

        <div class="tm-table-wrap">
            <table class="tm-table">
                <thead>
                    <tr>
                        <th class="col-no">#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th class="col-action">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($supervisors as $s)
                        <tr>
                            <td class="col-no td-muted">{{ $loop->iteration }}</td>
                            <td>
                                <div class="tm-name-cell">
                                    <div class="tm-avatar tm-avatar-blue">{{ strtoupper(substr($s->name, 0, 1)) }}{{ strtoupper(substr(strstr($s->name, ' '), 1, 1)) }}</div>
                                    <span class="td-name">{{ $s->name }}</span>
                                </div>
                            </td>
                            <td class="td-email">{{ $s->email }}</td>
                            <td>
                                <button
                                    type="button"
                                    class="tm-btn-assign"
                                    onclick="openAssignModal({{ $s->id }}, '{{ addslashes($s->name) }}')"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="14" height="14"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                                    Assign staff
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Staff Section --}}
    <div class="tm-section">
        <div class="tm-section-header">
            <svg class="tm-section-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            <span class="tm-section-label">Staff</span>
            <span class="tm-section-count">{{ $staff->count() }}</span>
        </div>

        <div class="tm-table-wrap">
            <table class="tm-table">
                <thead>
                    <tr>
                        <th class="col-no">#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Supervisor saat ini</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($staff as $u)
                        <tr>
                            <td class="col-no td-muted">{{ $loop->iteration }}</td>
                            <td>
                                <div class="tm-name-cell">
                                    <div class="tm-avatar tm-avatar-amber">{{ strtoupper(substr($u->name, 0, 1)) }}{{ strtoupper(substr(strstr($u->name, ' '), 1, 1)) }}</div>
                                    <span class="td-name">{{ $u->name }}</span>
                                </div>
                            </td>
                            <td class="td-email">{{ $u->email }}</td>
                            <td>
                                @if(optional($u->supervisor)->name)
                                    <span class="tm-sup-badge">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="12" height="12"><polyline points="20 6 9 17 4 12"/></svg>
                                        {{ $u->supervisor->name }}
                                    </span>
                                @else
                                    <span class="tm-no-sup">— Belum diassign</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Assign Modal --}}
<div id="assignModalBackdrop" class="tm-modal-backdrop" onclick="closeAssignModal()">
    <div class="tm-modal" onclick="event.stopPropagation()">
        <div class="tm-modal-header">
            <div>
                <p class="tm-modal-sub">Assign staff ke supervisor</p>
                <p class="tm-modal-title" id="modalSupervisorName"></p>
            </div>
            <button type="button" class="tm-modal-close" onclick="closeAssignModal()" aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="18" height="18"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <div class="tm-modal-body">
            <form id="assignMultipleForm" action="{{ route('manager.management.team.assign-multiple') }}" method="POST">
                @csrf
                <input type="hidden" name="supervisor_id" id="modalSupervisorId" value="">

                @foreach($staff as $u)
                    <label class="tm-check-row" for="staff_check_{{ $u->id }}">
                        <input
                            type="checkbox"
                            id="staff_check_{{ $u->id }}"
                            class="staff-checkbox tm-checkbox"
                            data-current-supervisor="{{ $u->supervisor_id ?? '' }}"
                            data-current-supervisor-name="{{ optional($u->supervisor)->name ?? '' }}"
                            name="staff_ids[]"
                            value="{{ $u->id }}"
                        >
                        <div class="tm-avatar tm-avatar-amber tm-avatar-sm">{{ strtoupper(substr($u->name, 0, 1)) }}{{ strtoupper(substr(strstr($u->name, ' '), 1, 1)) }}</div>
                        <div class="tm-check-info">
                            <span class="tm-check-name">{{ $u->name }}</span>
                            <span class="tm-check-email">{{ $u->email }}</span>
                        </div>
                        <span class="tm-other-spv-badge" style="display:none"></span>
                    </label>
                @endforeach

            </form>
        </div>

        <div class="tm-modal-footer">
            <button type="button" class="tm-btn-cancel" onclick="closeAssignModal()">Batal</button>
            <button type="submit" form="assignMultipleForm" class="tm-btn-save">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" width="14" height="14"><polyline points="20 6 9 17 4 12"/></svg>
                Simpan
            </button>
        </div>
    </div>
</div>

<style>
/* ─── Reset & Base Scoped to Management Tim ─── */
.tm-wrapper,
.tm-wrapper * {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

/* ─── Wrapper ─── */
.tm-wrapper {
    max-width: 960px;
    margin: 2.5rem auto;
    padding: 0 1.5rem;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

/* ─── Header ─── */
.tm-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 2rem;
}
.tm-breadcrumb {
    font-size: 12px;
    color: #94a3b8;
    margin-bottom: 4px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
.tm-title {
    font-size: 22px;
    font-weight: 600;
    color: #0f172a;
}
.tm-divisi-name { color: #2563eb; }
.tm-badge-divisi {
    font-size: 12px;
    font-weight: 500;
    background: #eff6ff;
    color: #1d4ed8;
    padding: 5px 14px;
    border-radius: 20px;
    border: 1px solid #bfdbfe;
    margin-top: 4px;
}

/* ─── Section ─── */
.tm-section { margin-bottom: 2rem; }
.tm-section-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 10px;
}
.tm-section-icon {
    width: 15px;
    height: 15px;
    color: #64748b;
}
.tm-section-label {
    font-size: 12px;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.06em;
}
.tm-section-count {
    font-size: 11px;
    background: #f1f5f9;
    color: #64748b;
    padding: 2px 8px;
    border-radius: 20px;
    border: 1px solid #e2e8f0;
}

/* ─── Table ─── */
.tm-table-wrap {
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
}
.tm-table { width: 100%; border-collapse: collapse; font-size: 14px; }
.tm-table thead th {
    padding: 11px 16px;
    text-align: left;
    font-size: 11px;
    font-weight: 600;
    color: #64748b;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
.tm-table tbody tr { border-bottom: 1px solid #f1f5f9; transition: background 0.12s; }
.tm-table tbody tr:last-child { border-bottom: none; }
.tm-table tbody tr:hover { background: #f8fafc; }
.tm-table td { padding: 12px 16px; color: #0f172a; vertical-align: middle; }

.col-no { width: 44px; }
.col-action { width: 140px; }
.td-muted { color: #94a3b8; font-size: 13px; }
.td-name { font-weight: 500; }
.td-email { color: #64748b; font-size: 13px; }

/* ─── Avatar ─── */
.tm-name-cell { display: flex; align-items: center; gap: 10px; }
.tm-avatar {
    width: 34px; height: 34px;
    border-radius: 50%;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 600;
    flex-shrink: 0;
}
.tm-avatar-blue { background: #dbeafe; color: #1e40af; }
.tm-avatar-amber { background: #fef3c7; color: #92400e; }
.tm-avatar-sm { width: 28px; height: 28px; font-size: 10px; }

/* ─── Buttons ─── */
.tm-btn-assign {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 13px; font-weight: 500;
    padding: 6px 14px;
    border-radius: 8px;
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
    cursor: pointer;
    transition: background 0.15s, border-color 0.15s;
}
.tm-btn-assign:hover { background: #dbeafe; border-color: #93c5fd; }

/* ─── Badges ─── */
.tm-sup-badge {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 12px; font-weight: 500;
    color: #166534; background: #dcfce7;
    padding: 4px 10px; border-radius: 20px;
    border: 1px solid #bbf7d0;
}
.tm-no-sup { font-size: 12px; color: #94a3b8; }

/* ─── Modal Backdrop ─── */
.tm-modal-backdrop {
    display: none;
    position: fixed; inset: 0; z-index: 50;
    background: rgba(15, 23, 42, 0.45);
    align-items: center; justify-content: center;
    padding: 1rem;
}
.tm-modal-backdrop.active { display: flex; }

/* ─── Modal ─── */
.tm-modal {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    width: 100%; max-width: 520px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.12);
    overflow: hidden;
}
.tm-modal-header {
    display: flex; align-items: flex-start; justify-content: space-between;
    padding: 20px 24px 16px;
    border-bottom: 1px solid #f1f5f9;
}
.tm-modal-sub { font-size: 12px; color: #94a3b8; margin-bottom: 3px; }
.tm-modal-title { font-size: 16px; font-weight: 600; color: #1d4ed8; }
.tm-modal-close {
    background: none; border: none; cursor: pointer;
    color: #94a3b8; padding: 4px;
    border-radius: 6px; transition: background 0.12s, color 0.12s;
}
.tm-modal-close:hover { background: #f1f5f9; color: #475569; }

.tm-modal-body { padding: 12px 24px; max-height: 300px; overflow-y: auto; }
.tm-modal-body::-webkit-scrollbar { width: 4px; }
.tm-modal-body::-webkit-scrollbar-track { background: transparent; }
.tm-modal-body::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }

/* ─── Checkbox Rows ─── */
.tm-check-row {
    display: flex; align-items: center; gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid #f8fafc;
    cursor: pointer;
    transition: background 0.1s;
}
.tm-check-row:last-child { border-bottom: none; }
.tm-check-row:hover { background: #f8fafc; margin: 0 -24px; padding-left: 24px; padding-right: 24px; }
.tm-checkbox {
    width: 16px; height: 16px;
    accent-color: #2563eb;
    cursor: pointer; flex-shrink: 0;
}
.tm-check-info { flex: 1; }
.tm-check-name { display: block; font-size: 14px; font-weight: 500; color: #0f172a; }
.tm-check-email { display: block; font-size: 12px; color: #94a3b8; margin-top: 1px; }

/* ─── Modal Footer ─── */
.tm-modal-footer {
    display: flex; justify-content: flex-end; gap: 8px;
    padding: 16px 24px;
    border-top: 1px solid #f1f5f9;
    background: #fafafa;
}
.tm-btn-cancel {
    font-size: 13px; font-weight: 500;
    padding: 8px 18px; border-radius: 8px;
    border: 1px solid #e2e8f0;
    background: #fff; color: #475569;
    cursor: pointer; transition: background 0.12s;
}
.tm-btn-cancel:hover { background: #f8fafc; }
.tm-btn-save {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 13px; font-weight: 500;
    padding: 8px 20px; border-radius: 8px;
    border: none;
    background: #2563eb; color: #fff;
    cursor: pointer; transition: background 0.15s;
}
.tm-btn-save:hover { background: #1d4ed8; }

/* ─── Badge "sudah di SPV lain" ─── */
.tm-other-spv-badge {
    margin-left: auto;
    flex-shrink: 0;
    font-size: 11px;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 20px;
    background: #fef9c3;
    color: #854d0e;
    border: 1px solid #fde047;
    white-space: nowrap;
}
</style>

<script>
function openAssignModal(supervisorId, supervisorName) {
    document.getElementById('assignModalBackdrop').classList.add('active');
    document.getElementById('modalSupervisorName').innerText = supervisorName;
    document.getElementById('modalSupervisorId').value = supervisorId;

    document.querySelectorAll('.staff-checkbox').forEach(function(cb) {
        const isCurrentSpv = cb.dataset.currentSupervisor == supervisorId;
        const otherSpvName = cb.dataset.currentSupervisorName;
        const hasOtherSpv  = cb.dataset.currentSupervisor !== ''
                             && cb.dataset.currentSupervisor != supervisorId;

        cb.checked = isCurrentSpv;

        const badge = cb.closest('label').querySelector('.tm-other-spv-badge');
        if (hasOtherSpv && otherSpvName) {
            // disable + visual dimmed
            cb.disabled = true;
            cb.closest('label').style.opacity = '0.55';
            cb.closest('label').style.cursor  = 'not-allowed';
            cb.closest('label').style.pointerEvents = 'none';
            badge.textContent = '⚠ Sudah di ' + otherSpvName;
            badge.style.display = '';
        } else {
            cb.disabled = false;
            cb.closest('label').style.opacity = '';
            cb.closest('label').style.cursor  = '';
            cb.closest('label').style.pointerEvents = '';
            badge.style.display = 'none';
        }
    });
}

function closeAssignModal() {
    document.getElementById('assignModalBackdrop').classList.remove('active');
}

// Close on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeAssignModal();
});
</script>

@endsection