@extends('layouts.app')

@section('content')

    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

            <div>
                <h1 class="text-3xl font-black text-slate-800">Projek Board</h1>
                <p class="text-slate-500 mt-1">Drag & Drop Task Management System</p>
            </div>

            @unless(in_array(auth()->user()->role, ['direksi', 'manager']))
                <button onclick="openAddTaskModal()"
                    class="h-14 px-6 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold shadow-xl transition">
                    + Add Task
                </button>
            @endunless

        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded-2xl">
                {{ session('success') }}
            </div>
        @endif

        {{-- BOARD --}}
        <div class="overflow-x-auto">
            <div class="flex gap-6 min-w-max pb-4">

                {{-- ========================================= --}}
                {{-- PENDING --}}
                {{-- ========================================= --}}
                <div class="w-[340px] bg-slate-100 rounded-[28px] p-4 shadow-lg">

                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <h2 class="text-lg font-black text-slate-700">Pending</h2>
                            <p class="text-sm text-slate-500">Waiting Task</p>
                        </div>
                        <div id="pendingCount" class="px-3 py-1 rounded-xl bg-yellow-100 text-yellow-600 text-sm font-bold">
                            {{ $pendingTasks->count() }}
                        </div>
                    </div>

                    <div id="pendingColumn" data-status="pending" class="space-y-4 min-h-[500px]" ondrop="drop(event)"
                        ondragover="allowDrop(event)">
                        @foreach($pendingTasks as $task)
                            @include('project.component.task-card', ['task' => $task])
                        @endforeach
                    </div>

                </div>

                {{-- ========================================= --}}
                {{-- ON PROGRESS --}}
                {{-- ========================================= --}}
                <div class="w-[340px] bg-slate-100 rounded-[28px] p-4 shadow-lg">

                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <h2 class="text-lg font-black text-slate-700">On Progress</h2>
                            <p class="text-sm text-slate-500">Working Task</p>
                        </div>
                        <div id="progressCount" class="px-3 py-1 rounded-xl bg-blue-100 text-blue-600 text-sm font-bold">
                            {{ $progressTasks->count() }}
                        </div>
                    </div>

                    <div id="progressColumn" data-status="progress" class="space-y-4 min-h-[500px]" ondrop="drop(event)"
                        ondragover="allowDrop(event)">
                        @foreach($progressTasks as $task)
                            @include('project.component.task-card', ['task' => $task])
                        @endforeach
                    </div>

                </div>

                {{-- ========================================= --}}
                {{-- DONE (optional kolom ke-3) --}}
                {{-- ========================================= --}}
                @if(isset($doneTasks))
                    <div class="w-[340px] bg-slate-100 rounded-[28px] p-4 shadow-lg">

                        <div class="flex items-center justify-between mb-5">
                            <div>
                                <h2 class="text-lg font-black text-slate-700">Done</h2>
                                <p class="text-sm text-slate-500">Completed Task</p>
                            </div>
                            <div id="doneCount" class="px-3 py-1 rounded-xl bg-green-100 text-green-600 text-sm font-bold">
                                {{ $doneTasks->count() }}
                            </div>
                        </div>

                        <div id="doneColumn" data-status="done" class="space-y-4 min-h-[500px]" ondrop="drop(event)"
                            ondragover="allowDrop(event)">
                            @foreach($doneTasks as $task)
                                @include('project.component.task-card', ['task' => $task])
                            @endforeach
                        </div>

                    </div>
                @endif

            </div>
        </div>

    </div>

    @include('project.component.edit-data-modal', [
        'projectEditUsers' => $users,
    ])

    {{-- ========================================= --}}
    {{-- ADD TASK MODAL --}}
    {{-- ========================================= --}}
    <div id="addTaskModal"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 hidden items-center justify-center p-4 overflow-y-auto">

        {{-- Modal Container --}}
        <div class="bg-white w-full max-w-xl rounded-[32px] shadow-2xl overflow-hidden my-auto max-h-[92vh] flex flex-col">

            {{-- Header --}}
            <div class="p-6 border-b shrink-0">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-black text-slate-700">
                            Add New Task
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            Create new project task
                        </p>
                    </div>

                    <button onclick="closeAddTaskModal()"
                        class="w-11 h-11 rounded-2xl bg-slate-100 hover:bg-slate-200 transition shrink-0">
                        ✕
                    </button>
                </div>
            </div>

            {{-- FORM --}}
            <form action="{{ route('project.store') }}" method="POST" class="flex-1 overflow-y-auto p-6 space-y-5">

                @csrf

                {{-- Task Title --}}
                <div>
                    <label class="font-semibold text-slate-700">
                        Task Title
                    </label>

                    <input type="text" name="title" required
                        class="w-full mt-2 h-14 rounded-2xl border border-slate-200 px-5 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500">
                </div>

                {{-- Description --}}
                <div>
                    <label class="font-semibold text-slate-700">
                        Description
                    </label>

                    <textarea name="description" rows="3"
                        class="w-full mt-2 rounded-2xl border border-slate-200 p-5 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500"></textarea>
                </div>

                {{-- Priority --}}
                <div>
                    <label class="font-semibold text-slate-700">
                        Priority
                    </label>

                    <select name="priority"
                        class="w-full mt-2 h-14 rounded-2xl border border-slate-200 px-5 outline-none focus:ring-4 focus:ring-indigo-100">

                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>

                    </select>
                </div>

                {{-- Assign Users --}}
                <div>

                    <label class="block text-sm font-bold text-slate-700 mb-4">
                        Assign Users
                    </label>

                    <div class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-sm">

                        {{-- Scroll Table --}}
                        <div class="overflow-y-auto max-h-[320px]">

                            <table class="min-w-full text-sm">

                                {{-- HEAD --}}
                                <thead class="bg-slate-100 text-slate-700 sticky top-0 z-10">

                                    <tr>

                                        <th class="w-16 px-5 py-4 text-center font-bold">
                                            Pilih
                                        </th>

                                        <th class="px-5 py-4 text-left font-bold">
                                            User
                                        </th>

                                        <th class="w-40 px-5 py-4 text-left font-bold">
                                            Role
                                        </th>

                                        <th class="w-40 px-5 py-4 text-center font-bold">
                                            Status
                                        </th>

                                    </tr>

                                </thead>

                                {{-- BODY --}}
                                <tbody class="divide-y divide-slate-100 bg-white">

                                    @foreach($users as $user)

                                        <tr class="hover:bg-slate-50 transition">

                                            {{-- Checkbox --}}
                                            <td class="px-5 py-4 text-center">

                                                <label class="inline-flex cursor-pointer">

                                                    <input type="checkbox" name="user_ids[]" value="{{ $user->id }}"
                                                        class="peer hidden">

                                                    <div
                                                        class="flex h-6 w-6 items-center justify-center rounded-lg border-2 border-slate-300 bg-white transition-all
                                                                                                                                    peer-checked:border-indigo-500
                                                                                                                                    peer-checked:bg-indigo-500">

                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                            stroke-width="3" stroke="currentColor"
                                                            class="hidden h-3.5 w-3.5 text-white peer-checked:block">

                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M5 13l4 4L19 7" />

                                                        </svg>

                                                    </div>

                                                </label>

                                            </td>

                                            {{-- User --}}
                                            <td class="px-5 py-4">

                                                <div class="flex items-center gap-3">

                                                    <img src="https://i.pravatar.cc/100?u={{ $user->id }}"
                                                        alt="{{ $user->name }}"
                                                        class="h-11 w-11 rounded-2xl object-cover border border-slate-200">

                                                    <div class="min-w-0">

                                                        <p class="font-semibold text-slate-800 truncate">
                                                            {{ $user->name }}
                                                        </p>

                                                        <p class="text-xs text-slate-400 mt-0.5">
                                                            ID User #{{ $user->id }}
                                                        </p>

                                                    </div>

                                                </div>

                                            </td>

                                            {{-- Role --}}
                                            <td class="px-5 py-4">

                                                <span
                                                    class="inline-flex items-center rounded-xl border border-slate-200 bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600">

                                                    {{ ucfirst($user->role) }}

                                                </span>

                                            </td>

                                            {{-- Status --}}
                                            <td class="px-5 py-4 text-center">

                                                <span
                                                    class="inline-flex items-center gap-1 rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700">

                                                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>

                                                    Available

                                                </span>

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                    <p class="mt-3 text-xs text-slate-400">
                        Pilih satu atau lebih user untuk di-assign ke task ini.
                    </p>

                </div>

                {{-- Footer Button --}}
                <div class="pt-2 sticky bottom-0 bg-white">

                    <button type="submit"
                        class="w-full h-14 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold transition shadow-lg shadow-indigo-100">

                        Save Task

                    </button>

                </div>

            </form>

        </div>

    </div>

    {{-- ========================================= --}}
    {{-- EDIT TASK MODAL --}}
    {{-- ========================================= --}}
    <div id="editTaskModal"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 hidden items-center justify-center p-4 overflow-y-auto">

        <div class="bg-white w-full max-w-3xl rounded-[32px] shadow-2xl overflow-hidden my-auto max-h-[92vh] flex flex-col">
            <div class="p-6 border-b shrink-0">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-black text-slate-700">Edit Project</h2>
                        <p class="text-sm text-slate-500 mt-1">Update title, status, priority, and project members.</p>
                    </div>
                    <button type="button" onclick="closeEditTaskModal()"
                        class="inline-flex w-11 h-11 items-center justify-center rounded-2xl bg-slate-100 hover:bg-slate-200 transition shrink-0">
                        <x-icon name="close" class="w-5 h-5" />
                    </button>
                </div>
            </div>

            <form id="editTaskForm" class="flex-1 overflow-y-auto p-6 space-y-5">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" id="editTaskId" name="task_id" value="">

                <div class="grid gap-5 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label class="font-semibold text-slate-700">Task Title</label>
                        <input type="text" id="editTaskTitle" name="title" required
                            class="w-full mt-2 h-14 rounded-2xl border border-slate-200 px-5 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500">
                    </div>

                    <div class="md:col-span-2">
                        <label class="font-semibold text-slate-700">Description</label>
                        <textarea id="editTaskDescription" name="description" rows="4"
                            class="w-full mt-2 rounded-2xl border border-slate-200 p-5 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500"></textarea>
                    </div>

                    <div>
                        <label class="font-semibold text-slate-700">Priority</label>
                        <select id="editTaskPriority" name="priority"
                            class="w-full mt-2 h-14 rounded-2xl border border-slate-200 px-5 outline-none focus:ring-4 focus:ring-indigo-100">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>

                    <div>
                        <label class="font-semibold text-slate-700">Status</label>
                        <select id="editTaskStatus" name="status"
                            class="w-full mt-2 h-14 rounded-2xl border border-slate-200 px-5 outline-none focus:ring-4 focus:ring-indigo-100">
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
                        <div class="overflow-y-auto max-h-[300px]">
                            <table class="min-w-full text-sm">
                                <thead class="bg-slate-100 text-slate-700 sticky top-0 z-10">
                                    <tr>
                                        <th class="w-16 px-5 py-4 text-center font-bold">Pilih</th>
                                        <th class="px-5 py-4 text-left font-bold">User</th>
                                        <th class="w-40 px-5 py-4 text-left font-bold">Role</th>
                                        <th class="w-40 px-5 py-4 text-center font-bold">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    @foreach($users as $user)
                                        <tr class="hover:bg-slate-50 transition">
                                            <td class="px-5 py-4 text-center">
                                                <label class="inline-flex cursor-pointer">
                                                    <input type="checkbox" name="user_ids[]" value="{{ $user->id }}"
                                                        class="edit-task-user-checkbox peer hidden">
                                                    <div class="flex h-6 w-6 items-center justify-center rounded-lg border-2 border-slate-300 bg-white transition-all peer-checked:border-indigo-500 peer-checked:bg-indigo-500">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                            stroke-width="3" stroke="currentColor"
                                                            class="hidden h-3.5 w-3.5 text-white peer-checked:block">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </div>
                                                </label>
                                            </td>
                                            <td class="px-5 py-4">
                                                <div class="flex items-center gap-3">
                                                    <img src="https://i.pravatar.cc/100?u={{ $user->id }}"
                                                        alt="{{ $user->name }}"
                                                        class="h-11 w-11 rounded-2xl object-cover border border-slate-200">
                                                    <div class="min-w-0">
                                                        <p class="font-semibold text-slate-800 truncate">{{ $user->name }}</p>
                                                        <p class="text-xs text-slate-400 mt-0.5">ID User #{{ $user->id }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4">
                                                <span class="inline-flex items-center rounded-xl border border-slate-200 bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600">
                                                    {{ ucfirst($user->role) }}
                                                </span>
                                            </td>
                                            <td class="px-5 py-4 text-center">
                                                <span class="inline-flex items-center gap-1 rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700">
                                                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                                    Available
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <p class="mt-3 text-xs text-slate-400">Pilih satu atau lebih user untuk di-assign ke project ini.</p>
                </div>

                <div class="pt-2 sticky bottom-0 bg-white">
                    <button type="submit"
                        class="w-full h-14 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold transition shadow-lg shadow-indigo-100">
                        Update Project
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ========================================= --}}
    {{-- GLOBAL LOADING --}}
    {{-- ========================================= --}}
    <div id="globalLoading" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-[9999] hidden items-center justify-center">
        <div class="bg-white rounded-[32px] px-10 py-8 shadow-2xl flex flex-col items-center">
            <div class="w-16 h-16 border-[6px] border-indigo-200 border-t-indigo-600 rounded-full animate-spin"></div>
            <h3 class="mt-5 text-xl font-black text-slate-700">Loading...</h3>
            <p class="text-sm text-slate-400 mt-1">Please wait a moment</p>
        </div>
    </div>

    {{-- ========================================= --}}
    {{-- SCRIPT --}}
    {{-- ========================================= --}}
    <script>
        const isReadOnlyProject = @json(in_array(auth()->user()->role, ['direksi', 'manager']));
        const canEditProject = @json(auth()->user()->role === 'admin_divisi');
        let editingTaskId = null;
        let editingTaskUserIds = [];

        // =====================
        // LOADING
        // =====================
        function showLoading() {
            const el = document.getElementById('globalLoading');
            el.classList.remove('hidden');
            el.classList.add('flex');
        }

        function hideLoading() {
            const el = document.getElementById('globalLoading');
            el.classList.add('hidden');
            el.classList.remove('flex');
        }

        // =====================
        // ADD TASK MODAL
        // =====================
        function openAddTaskModal() {
            if (isReadOnlyProject) return;
            const el = document.getElementById('addTaskModal');
            el.classList.remove('hidden');
            el.classList.add('flex');
        }

        function closeAddTaskModal() {
            const el = document.getElementById('addTaskModal');
            el.classList.add('hidden');
            el.classList.remove('flex');
        }

        function openEditTaskModal(taskId, title, description, priority, status, userIds) {
            if (!canEditProject) return;
            editingTaskId = taskId;
            const normalizedUserIds = Array.isArray(userIds)
                ? userIds
                : (userIds && typeof userIds === 'object' ? Object.values(userIds) : []);
            editingTaskUserIds = normalizedUserIds.map(id => String(id));
            document.getElementById('editTaskId').value = taskId;
            document.getElementById('editTaskTitle').value = title || '';
            document.getElementById('editTaskDescription').value = description || '';
            document.getElementById('editTaskPriority').value = priority || 'medium';
            document.getElementById('editTaskStatus').value = status || 'pending';
            document.querySelectorAll('.edit-task-user-checkbox').forEach(cb => {
                cb.checked = editingTaskUserIds.includes(String(cb.value));
            });
            const el = document.getElementById('editTaskModal');
            el.classList.remove('hidden');
            el.classList.add('flex');
        }

        function closeEditTaskModal() {
            const el = document.getElementById('editTaskModal');
            el.classList.add('hidden');
            el.classList.remove('flex');
            editingTaskId = null;
            editingTaskUserIds = [];
        }

        document.getElementById('editTaskForm')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            if (!editingTaskId) return;

            const form = e.currentTarget;
            const formData = new FormData(form);

            try {
                const response = await fetch(`/project/${editingTaskId}`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: formData
                });

                const data = await response.json();
                if (data.success) {
                    closeEditTaskModal();
                    gToast.success('Project diperbarui', data.message || 'Project berhasil diupdate.');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    gModal.alert(data.message || 'Gagal mengupdate project.', 'warning');
                }
            } catch (error) {
                console.error(error);
                gModal.alert('Terjadi kesalahan saat mengupdate project.', 'warning');
            }
        });

        // =====================
        // DRAG & DROP
        // =====================
        function allowDrop(ev) {
            if (isReadOnlyProject) return;
            ev.preventDefault();
        }

        function drag(ev) {
            if (isReadOnlyProject) return false;
            if (ev.target.closest('button, a, input, textarea, select, label')) return false;
            const taskCard = ev.target.closest('.task-card');
            if (taskCard) ev.dataTransfer.setData("text", taskCard.id);
        }

        function drop(ev) {
            if (isReadOnlyProject) return;
            ev.preventDefault();
            const data = ev.dataTransfer.getData("text");
            const task = document.getElementById(data);
            const dropZone = ev.target.closest('[data-status]');

            if (!dropZone || !task) return;

            dropZone.appendChild(task);

            document.getElementById('pendingCount').innerText =
                document.querySelectorAll('#pendingColumn .task-card').length;
            document.getElementById('progressCount').innerText =
                document.querySelectorAll('#progressColumn .task-card').length;

            const taskId = data.replace('task', '');
            const status = dropZone.dataset.status;

            fetch(`/project/${taskId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: status })
            })
                .then(res => res.json())
                .then(response => console.log(response))
                .catch(error => console.error(error));
        }

            window.openEditTaskModal = openEditTaskModal;
            window.closeEditTaskModal = closeEditTaskModal;

        // =====================
        // CLOSE MODAL ON BACKDROP CLICK
        // =====================
        window.onclick = function (event) {
            const addTaskModal = document.getElementById('addTaskModal');
            if (event.target === addTaskModal) closeAddTaskModal();
        }

    </script>

@endsection