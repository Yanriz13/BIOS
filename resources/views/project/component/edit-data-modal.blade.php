@php
    $projectEditUsers = $projectEditUsers ?? ($users ?? collect());
    $draftEditUsers = $draftEditUsers ?? ((isset($task) && isset($task->users)) ? $task->users : collect());
@endphp

<div id="editDataModal" class="fixed inset-0 z-[70] hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 overflow-y-auto">
    <div class="w-full max-w-4xl overflow-hidden rounded-[32px] bg-white shadow-2xl border border-slate-200 my-auto max-h-[92vh] flex flex-col">
        <div class="flex items-center justify-between gap-4 border-b border-slate-200 px-6 py-5 bg-gradient-to-r from-slate-50 to-white">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-slate-400 font-semibold">Edit Data</p>
                <h2 id="editDataTitle" class="text-2xl font-black text-slate-900">Edit Data</h2>
            </div>
            <button type="button" id="editDataCloseButton" class="inline-flex items-center justify-center rounded-full bg-slate-100 px-3 py-2 text-slate-600 hover:bg-slate-200 transition">
                <x-icon name="close" class="w-5 h-5" />
            </button>
        </div>

        <div class="px-6 pt-5">
            <div id="editDataSummary" class="rounded-[24px] border border-indigo-100 bg-indigo-50 px-5 py-4 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-[11px] uppercase tracking-[0.2em] text-indigo-400 font-semibold">Preview</p>
                        <p class="mt-1 text-sm font-semibold text-indigo-900">Buka modal untuk melihat dan mengubah data.</p>
                    </div>
                    <span class="inline-flex items-center rounded-full bg-white px-3 py-1.5 text-xs font-semibold text-indigo-700 border border-indigo-100">Ready</span>
                </div>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-6 space-y-6">
            <form id="editDataProjectForm" class="hidden space-y-5" method="POST">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" id="editDataProjectId" value="">

                <div class="grid gap-5 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label class="text-sm font-semibold text-slate-700">Task Title</label>
                        <input id="editDataProjectTitle" name="title" type="text" required
                            class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100">
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-sm font-semibold text-slate-700">Description</label>
                        <textarea id="editDataProjectDescription" name="description" rows="4"
                            class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-4 text-sm text-slate-800 outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100"></textarea>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-700">Priority</label>
                        <select id="editDataProjectPriority" name="priority"
                            class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-700">Status</label>
                        <select id="editDataProjectStatus" name="status"
                            class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100">
                            <option value="pending">Pending</option>
                            <option value="progress">Progress</option>
                            <option value="done">Done</option>
                            <option value="reject">Reject</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-4">Project Members</label>
                    <div class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-sm">
                        <div class="overflow-y-auto max-h-[260px]">
                            <table class="min-w-full text-sm">
                                <thead class="bg-slate-100 text-slate-700 sticky top-0 z-10">
                                    <tr>
                                        <th class="w-16 px-5 py-4 text-center font-bold">Pilih</th>
                                        <th class="px-5 py-4 text-left font-bold">User</th>
                                        <th class="w-40 px-5 py-4 text-left font-bold">Role</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    @foreach($projectEditUsers as $user)
                                        <tr class="hover:bg-slate-50 transition">
                                            <td class="px-5 py-4 text-center">
                                                <label class="inline-flex cursor-pointer">
                                                    <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="edit-data-project-user peer hidden">
                                                    <div class="flex h-6 w-6 items-center justify-center rounded-lg border-2 border-slate-300 bg-white transition-all peer-checked:border-indigo-500 peer-checked:bg-indigo-500">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="hidden h-3.5 w-3.5 text-white peer-checked:block">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </div>
                                                </label>
                                            </td>
                                            <td class="px-5 py-4">
                                                <div class="flex items-center gap-3">
                                                    <img src="https://i.pravatar.cc/100?u={{ $user->id }}" alt="{{ $user->name }}" class="h-11 w-11 rounded-2xl object-cover border border-slate-200">
                                                    <div class="min-w-0">
                                                        <p class="font-semibold text-slate-800 truncate">{{ $user->name }}</p>
                                                        <p class="text-xs text-slate-400 mt-0.5">ID User #{{ $user->id }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4">
                                                <span class="inline-flex items-center rounded-xl border border-slate-200 bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600">{{ ucfirst($user->role) }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>

            <form id="editDataDraftForm" class="hidden space-y-5" method="POST">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" id="editDataDraftId" value="">

                <div>
                    <label class="text-sm font-semibold text-slate-700">Assign to Member</label>
                    <select id="editDataDraftUserId" name="user_id"
                        class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100">
                        <option value="">Unassigned</option>
                        @foreach($draftEditUsers as $member)
                            <option value="{{ $member->id }}">{{ $member->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700">Description</label>
                    <textarea id="editDataDraftDescription" name="description" rows="4"
                        class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-4 text-sm text-slate-800 outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100"></textarea>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Deadline</label>
                        <input id="editDataDraftDeadline" name="deadline" type="date"
                            class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-700">Notes</label>
                        <input id="editDataDraftNotes" name="notes" type="text"
                            class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100">
                    </div>
                </div>
            </form>

            <form id="editDataChecklistForm" class="hidden space-y-5" method="POST">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" id="editDataChecklistId" value="">

                <div>
                    <label class="text-sm font-semibold text-slate-700">Checklist Title</label>
                    <input id="editDataChecklistTitle" name="title" type="text" required
                        class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100">
                </div>
            </form>
        </div>

        <div class="border-t border-slate-200 bg-slate-50 px-6 py-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div id="editDataHint" class="text-sm text-slate-500">Pilih data yang ingin diedit.</div>
            <div class="flex gap-3 sm:justify-end">
                <button type="button" id="editDataCancelButton"
                    class="rounded-3xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                    Cancel
                </button>
                <button type="button" id="editDataSaveButton"
                    class="rounded-3xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700 transition">
                    Save
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        const modal = document.getElementById('editDataModal');
        if (!modal) return;

        const titleEl = document.getElementById('editDataTitle');
        const hintEl = document.getElementById('editDataHint');
        const summaryEl = document.getElementById('editDataSummary');
        const saveButton = document.getElementById('editDataSaveButton');
        const cancelButton = document.getElementById('editDataCancelButton');
        const closeButton = document.getElementById('editDataCloseButton');

        const projectForm = document.getElementById('editDataProjectForm');
        const draftForm = document.getElementById('editDataDraftForm');
        const checklistForm = document.getElementById('editDataChecklistForm');

        const forms = {
            project: projectForm,
            draft: draftForm,
            checklist: checklistForm,
        };

        function parseJson(value, fallback) {
            if (!value) return fallback;
            try {
                return JSON.parse(value);
            } catch (error) {
                return fallback;
            }
        }

        function normalizeIdList(value) {
            if (Array.isArray(value)) return value;
            if (typeof value === 'string') {
                const trimmed = value.trim();
                if (!trimmed) return [];

                try {
                    const parsed = JSON.parse(trimmed);
                    return Array.isArray(parsed) ? parsed : [parsed];
                } catch (error) {
                    return trimmed.split(',').map(item => item.trim()).filter(Boolean);
                }
            }
            if (value && typeof value === 'object') return Object.values(value);
            return [];
        }

        function hideAllForms() {
            Object.values(forms).forEach(form => form.classList.add('hidden'));
        }

        function openModal(kind, data) {
            hideAllForms();

            if (kind === 'project') {
                titleEl.textContent = 'Edit Project';
                hintEl.textContent = 'Ubah data project dan anggota yang terhubung.';
                summaryEl.innerHTML = `
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-[11px] uppercase tracking-[0.2em] text-indigo-400 font-semibold">Project</p>
                            <p class="mt-1 text-sm font-semibold text-indigo-900 truncate">${(data.title || 'Project').replace(/</g, '&lt;').replace(/>/g, '&gt;')}</p>
                            <p class="text-xs text-indigo-700 mt-1">Klik Save untuk menyimpan perubahan project.</p>
                        </div>
                        <span class="inline-flex items-center rounded-full bg-white px-3 py-1.5 text-xs font-semibold text-indigo-700 border border-indigo-100">Editable</span>
                    </div>`;
                projectForm.classList.remove('hidden');
                projectForm.action = `/project/${data.id}`;
                document.getElementById('editDataProjectId').value = data.id;
                document.getElementById('editDataProjectTitle').value = data.title || '';
                document.getElementById('editDataProjectDescription').value = data.description || '';
                document.getElementById('editDataProjectPriority').value = data.priority || 'medium';
                document.getElementById('editDataProjectStatus').value = data.status || 'pending';
                const ids = normalizeIdList(data.userIds);
                const selectedIds = new Set(ids.map(String));
                document.querySelectorAll('.edit-data-project-user').forEach(cb => {
                    cb.checked = selectedIds.has(String(cb.value));
                });
                saveButton.dataset.kind = 'project';
                saveButton.textContent = 'Save Project';
            }

            if (kind === 'draft') {
                titleEl.textContent = 'Edit Task Draft';
                hintEl.textContent = 'Ubah penugasan, deskripsi, deadline, dan catatan task draft.';
                summaryEl.innerHTML = `
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-[11px] uppercase tracking-[0.2em] text-indigo-400 font-semibold">Task Draft</p>
                            <p class="mt-1 text-sm font-semibold text-indigo-900 truncate">${(data.description || 'Draft assignment').replace(/</g, '&lt;').replace(/>/g, '&gt;')}</p>
                            <p class="text-xs text-indigo-700 mt-1">Deadline: ${data.deadline || '-'} | Notes: ${(data.notes || '-').replace(/</g, '&lt;').replace(/>/g, '&gt;')}</p>
                        </div>
                        <span class="inline-flex items-center rounded-full bg-white px-3 py-1.5 text-xs font-semibold text-indigo-700 border border-indigo-100">Editable</span>
                    </div>`;
                draftForm.classList.remove('hidden');
                draftForm.action = `/project/assignment/${data.id}`;
                document.getElementById('editDataDraftId').value = data.id;
                document.getElementById('editDataDraftUserId').value = data.userId || '';
                document.getElementById('editDataDraftDescription').value = data.description || '';
                document.getElementById('editDataDraftDeadline').value = data.deadline || '';
                document.getElementById('editDataDraftNotes').value = data.notes || '';
                saveButton.dataset.kind = 'draft';
                saveButton.textContent = 'Save Task Draft';
            }

            if (kind === 'checklist') {
                titleEl.textContent = 'Edit Checklist';
                hintEl.textContent = 'Ubah nama checklist item.';
                summaryEl.innerHTML = `
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-[11px] uppercase tracking-[0.2em] text-indigo-400 font-semibold">Checklist</p>
                            <p class="mt-1 text-sm font-semibold text-indigo-900 truncate">${(data.title || 'Checklist').replace(/</g, '&lt;').replace(/>/g, '&gt;')}</p>
                            <p class="text-xs text-indigo-700 mt-1">Klik Save untuk memperbarui judul checklist.</p>
                        </div>
                        <span class="inline-flex items-center rounded-full bg-white px-3 py-1.5 text-xs font-semibold text-indigo-700 border border-indigo-100">Editable</span>
                    </div>`;
                checklistForm.classList.remove('hidden');
                checklistForm.action = `/project/checklist/${data.id}`;
                document.getElementById('editDataChecklistId').value = data.id;
                document.getElementById('editDataChecklistTitle').value = data.title || '';
                saveButton.dataset.kind = 'checklist';
                saveButton.textContent = 'Save Checklist';
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        async function submitVisibleForm() {
            const kind = saveButton.dataset.kind;
            const form = forms[kind];
            if (!form) return;

            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();
                if (data.success) {
                    closeModal();
                    if (window.gToast) {
                        window.gToast.success('Berhasil', data.message || 'Data berhasil diupdate.');
                    }
                    setTimeout(() => location.reload(), 900);
                } else {
                    if (window.gModal) {
                        window.gModal.alert(data.message || 'Gagal mengupdate data.', 'warning');
                    }
                }
            } catch (error) {
                console.error(error);
                if (window.gModal) {
                    window.gModal.alert('Terjadi kesalahan saat menyimpan perubahan.', 'warning');
                }
            }
        }

        document.addEventListener('click', function (event) {
            const button = event.target.closest('[data-edit-kind]');
            if (!button) return;
            event.preventDefault();
            event.stopPropagation();
            openModal(button.dataset.editKind, button.dataset);
        });

        saveButton.addEventListener('click', submitVisibleForm);
        cancelButton.addEventListener('click', closeModal);
        closeButton.addEventListener('click', closeModal);
        modal.addEventListener('click', function (event) {
            if (event.target === modal) closeModal();
        });

        window.openEditDataModal = openModal;
        window.closeEditDataModal = closeModal;
    })();
</script>
