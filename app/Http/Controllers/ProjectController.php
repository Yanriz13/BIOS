<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskAssignment;
use App\Models\TaskChecklist;
use App\Models\TodoItem;
use App\Models\ChatNotification;

class ProjectController extends Controller
{
    public function index()
    {
        $divisi = auth()->user()->divisi;

        $tasks = Task::with(['users', 'assignments.checklists'])
            ->where('divisi', $divisi)
            ->latest()
            ->get();

        $resolveTaskStatus = function (Task $task): string {
            $rawStatus = $task->getRawOriginal('status');

            if ($rawStatus === 'reject') {
                return 'reject';
            }

            $totalChecklist = 0;
            $doneChecklist = 0;

            foreach ($task->assignments as $assignment) {
                $totalChecklist += $assignment->checklists->count();
                $doneChecklist += $assignment->checklists->where('is_done', 1)->count();
            }

            if ($totalChecklist > 0) {
                if ($doneChecklist >= $totalChecklist) {
                    return 'done';
                }

                if ($doneChecklist > 0) {
                    return 'progress';
                }

                return 'pending';
            }

            return in_array($rawStatus, ['pending', 'progress', 'done'], true)
                ? $rawStatus
                : 'pending';
        };

        $tasks = $tasks->map(function (Task $task) use ($resolveTaskStatus) {
            $task->setAttribute('status', $resolveTaskStatus($task));
            return $task;
        });

        return view('project.index', [
            'pendingTasks' => $tasks->filter(fn($task) => $task->status === 'pending')->values(),

            'progressTasks' => $tasks->filter(fn($task) => $task->status === 'progress')->values(),

            'doneTasks' => $tasks->filter(fn($task) => $task->status === 'done')->values(),

            'rejectTasks' => $tasks->filter(fn($task) => $task->status === 'reject')->values(),

            'users' => User::where('role', 'staff')
                ->where('divisi', $divisi)
                ->get(),
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'priority' => 'required',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => 'pending',
            'divisi' => auth()->user()->divisi,
        ]);

        if ($request->user_ids) {
            $validUsers = User::whereIn('id', $request->user_ids)
                ->where('role', 'staff')
                ->where('divisi', auth()->user()->divisi)
                ->pluck('id');

            $task->users()->attach($validUsers);
        }

        return redirect()->back()
            ->with('success', 'Task berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:pending,progress,done,reject',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $task = Task::findOrFail($id);
        $divisi = $task->divisi ?: auth()->user()->divisi;

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => $request->status,
        ]);

        if ($request->has('user_ids')) {
            $validUsers = User::whereIn('id', $request->user_ids ?? [])
                ->where('role', 'staff')
                ->where('divisi', $divisi)
                ->pluck('id');

            $task->users()->sync($validUsers);
        }

        return response()->json([
            'success' => true,
            'message' => 'Project berhasil diupdate',
        ]);
    }

    public function addUsers(Request $request, $id)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);
        $validUsers = User::whereIn('id', $request->user_ids)
            ->where('role', 'staff')
            ->where('divisi', auth()->user()->divisi)
            ->pluck('id');

        $task = Task::findOrFail($id);
        $task->users()->syncWithoutDetaching($validUsers);

        return redirect()->back()
            ->with('success', 'User berhasil ditambahkan ke detail task');
    }
    public function destroyAssignment($id)
    {
        $assignment = TaskAssignment::findOrFail($id);
        $assignment->checklists()->delete();
        $assignment->delete();

        return response()->json(['success' => true, 'message' => 'Draft berhasil dihapus']);
    }

    public function destroyChecklist($id)
    {
        $checklist = TaskChecklist::findOrFail($id);
        $checklist->delete();

        return response()->json(['success' => true, 'message' => 'Checklist berhasil dihapus']);
    }
    public function detail($id)
    {
        $task = Task::with(['users.supervisor'])->findOrFail($id);

        $assignments = TaskAssignment::with(['checklists', 'user'])
            ->where('task_id', $id)
            ->get()
            ->groupBy('user_id');

        // Semua assignments (draft & assigned) untuk tabel overview
        $allDrafts = TaskAssignment::with(['checklists.assignment.user', 'user'])
            ->where('task_id', $id)
            ->latest()
            ->get();

        $memberIds = $task->users->pluck('id')
            ->merge($allDrafts->pluck('user_id')->filter())
            ->unique()
            ->values();

        $overviewMemberCount = $memberIds->count();
        $overviewTaskCount = $allDrafts->count();
        $overviewChecklistCount = (int) $allDrafts->sum(function ($draft) {
            return $draft->checklists->count();
        });

        $completionPercents = $allDrafts
            ->map(function ($draft) {
                $total = $draft->checklists->count();
                if ($total <= 0) {
                    return null;
                }

                $done = $draft->checklists->where('is_done', 1)->count();
                return ($done / $total) * 100;
            })
            ->filter(function ($value) {
                return $value !== null;
            })
            ->values();

        $overviewAvgProgress = $completionPercents->count() > 0
            ? (int) round($completionPercents->avg())
            : 0;

        $taskChatUnread = ChatNotification::where('user_id', auth()->id())
            ->where('task_id', $id)->where('is_read', false)->count();

        $globalChatUnread = ChatNotification::where('user_id', auth()->id())
            ->whereNull('task_id')->where('is_read', false)->count();

        return view('project.detail', compact(
            'task',
            'assignments',
            'allDrafts',
            'taskChatUnread',
            'globalChatUnread',
            'overviewMemberCount',
            'overviewTaskCount',
            'overviewChecklistCount',
            'overviewAvgProgress'
        ))
            ->with(
                'users',
                User::where('role', 'staff')
                    ->where('divisi', auth()->user()->divisi)
                    ->get()
            );
    }

    public function staffProject()
    {
        $assignments = TaskAssignment::with(['task', 'checklists'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        $totalChecklist = 0;
        $completedChecklist = 0;

        foreach ($assignments as $assignment) {
            $totalChecklist += $assignment->checklists->count();
            $completedChecklist += $assignment->checklists->where('is_done', 1)->count();
        }

        return view('staff.project', compact('assignments', 'totalChecklist', 'completedChecklist'));
    }

    public function supervisorProject()
    {
        $supervisor = auth()->user();

        $staff = User::where('supervisor_id', $supervisor->id)
            ->get();

        $staffIds = $staff->pluck('id');

        $assignments = TaskAssignment::with(['task', 'checklists', 'user'])
            ->whereIn('user_id', $staffIds)
            ->latest()
            ->get();

        // Group assignments by user_id
        $assignmentsByUser = $assignments->groupBy('user_id');

        return view('supervisor.project', compact('staff', 'assignmentsByUser'));
    }

    public function toggleChecklist(Request $request, $id)
    {
        $checklist = TaskChecklist::findOrFail($id);

        $checklist->update([
            'is_done' => $request->has('is_done')
        ]);

        $this->syncAssignmentStatus($checklist->task_assignment_id);

        return redirect()->back()
            ->with('success', 'Checklist berhasil diperbarui');
    }

    public function updateStatus(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $task->update([
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function saveAssignment(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'user_id' => 'nullable|exists:users,id',
            'description' => 'nullable',
            'deadline' => 'nullable|date',
            'notes' => 'nullable',
            'file' => 'nullable|file',
        ]);
        if ($request->filled('user_id')) {

            $user = User::where('id', $request->user_id)
                ->where('role', 'staff')
                ->where('divisi', auth()->user()->divisi)
                ->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Assignment hanya boleh untuk staff.'
                ], 403);
            }
        }
        $fileName = null;

        if ($request->hasFile('file')) {
            $fileName = $request->file('file')
                ->store('task-files', 'public');
        }

        $assignment = TaskAssignment::create([
            'task_id' => $request->task_id,
            'user_id' => $request->user_id ?: null,
            'description' => $request->description,
            'deadline' => $request->deadline,
            'notes' => $request->notes,
            'file' => $fileName,
            'status' => 'pending'
        ]);

        if ($request->filled('checklists')) {
            foreach ($request->checklists ?? [] as $item) {
                if ($item) {
                    TaskChecklist::create([
                        'task_assignment_id' => $assignment->id,
                        'title' => $item
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Assignment berhasil disimpan'
        ]);
    }

    public function updateAssignment(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $assignment = TaskAssignment::with('task')->findOrFail($id);

        if ($request->filled('user_id')) {
            $user = User::where('id', $request->user_id)
                ->where('role', 'staff')
                ->where('divisi', $assignment->task->divisi ?? auth()->user()->divisi)
                ->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Assignment hanya boleh untuk staff pada divisi yang sama.',
                ], 403);
            }
        }

        $assignment->update([
            'user_id' => $request->filled('user_id') ? $request->user_id : null,
            'description' => $request->description,
            'deadline' => $request->deadline,
            'notes' => $request->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Task berhasil diupdate',
        ]);
    }

    public function viewAssignment($taskId, $userName)
    {
        $assignment = TaskAssignment::with([
            'task',
            'user',
            'checklists'
        ])
            ->where('task_id', $taskId)
            ->whereHas('user', function ($q) use ($userName) {
                $q->where('name', $userName);
            })
            ->first();

        if (!$assignment) {
            return response()->json([
                'success' => false
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $assignment
        ]);
    }

    public function storeTodo(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $task = Task::findOrFail($id);

        $todo = TodoItem::create([
            'task_id' => $task->id,
            'title' => $request->title,
            'description' => $request->filled('description')
                ? $request->description
                : null,
            'assigned_user_id' => null,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'data' => $todo
        ]);
    }

    public function assignTodo(Request $request, $id)
    {
        $request->validate([
            'assigned_user_id' => 'required|integer|exists:users,id',
        ]);
        $user = User::where('id', $request->assigned_user_id)
            ->where('role', 'staff')
            ->where('divisi', auth()->user()->divisi)
            ->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Todo hanya bisa diassign ke staff.'
            ], 403);
        }
        $todo = TodoItem::findOrFail($id);

        $todo->update([
            'assigned_user_id' => $request->assigned_user_id,
            'status' => 'progress',
        ]);

        $assignment = TaskAssignment::create([
            'task_id' => $todo->task_id,
            'user_id' => $request->assigned_user_id,
            'description' => null,
            'notes' => null,
            'status' => 'pending',
        ]);

        // checklist title utama
        TaskChecklist::create([
            'task_assignment_id' => $assignment->id,
            'title' => $todo->title,
            'is_done' => false,
        ]);

        // hanya buat checklist description jika ada isi
        if (!empty(trim($todo->description ?? ''))) {

            TaskChecklist::create([
                'task_assignment_id' => $assignment->id,
                'title' => $todo->description,
                'is_done' => false,
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $assignment
        ]);
    }
    public function unassignChecklist($id)
    {
        $checklist = TaskChecklist::findOrFail($id);
        $currentAssignment = TaskAssignment::find($checklist->task_assignment_id);

        if (!$currentAssignment) {
            return response()->json([
                'success' => false,
                'message' => 'Assignment tidak ditemukan.'
            ], 404);
        }

        // Cari atau buat draft assignment (user_id = null) untuk task yang sama
        $draftAssignment = TaskAssignment::firstOrCreate(
            [
                'task_id' => $currentAssignment->task_id,
                'user_id' => null,
            ],
            [
                'description' => $currentAssignment->description,
                'deadline' => $currentAssignment->deadline,
                'notes' => $currentAssignment->notes,
                'file' => $currentAssignment->file,
                'status' => 'pending',
            ]
        );

        // Pindahkan hanya checklist ini ke draft assignment
        $checklist->update([
            'task_assignment_id' => $draftAssignment->id
        ]);

        // Jika assignment lama sudah tidak punya checklist, hapus assignment-nya
        if ($currentAssignment->checklists()->count() === 0) {
            $currentAssignment->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Checklist berhasil di-unassign dan kembali ke draft'
        ]);
    }
    public function storeTodos(Request $request, $id)
    {
        $request->validate([
            'todos' => 'required|array|min:1',
            'todos.*.title' => 'required|string|max:255',
            'todos.*.description' => 'nullable|string',
        ]);

        $task = Task::findOrFail($id);

        foreach ($request->todos as $todoData) {

            TodoItem::create([
                'task_id' => $task->id,
                'title' => $todoData['title'],
                'description' => !empty(trim($todoData['description'] ?? ''))
                    ? $todoData['description']
                    : null,
                'assigned_user_id' => null,
                'status' => 'pending',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Todos created successfully'
        ]);
    }

    public function assignChecklist(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
        $user = User::where('id', $request->user_id)
            ->where('role', 'staff')
            ->where('divisi', auth()->user()->divisi)
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya staff yang bisa diassign.'
            ], 403);
        }
        $checklist = TaskChecklist::findOrFail($id);
        $draftAssignment = TaskAssignment::find($checklist->task_assignment_id);

        if (!$draftAssignment) {
            return response()->json(['success' => false, 'message' => 'Draft tidak ditemukan.'], 404);
        }

        $description = $request->filled('description') ? $request->description : $draftAssignment->description;
        $deadline = $request->filled('deadline') ? $request->deadline : $draftAssignment->deadline;
        $notes = $request->filled('notes') ? $request->notes : $draftAssignment->notes;

        // Cari assignment user yang sudah ada dengan detail sama, atau buat baru
        $userAssignment = TaskAssignment::firstOrCreate(
            [
                'task_id' => $draftAssignment->task_id,
                'user_id' => $request->user_id,
                'description' => $description,
                'deadline' => $deadline,
            ],
            [
                'notes' => $notes,
                'file' => $draftAssignment->file,
                'status' => 'pending',
            ]
        );

        // Pindahkan checklist ke assignment user
        $checklist->update([
            'task_assignment_id' => $userAssignment->id
        ]);

        // Jika draft sudah tidak punya checklist lagi, hapus draft
        if ($draftAssignment->user_id === null && $draftAssignment->checklists()->count() === 0) {
            $draftAssignment->delete();
        }

        return response()->json(['success' => true, 'message' => 'Checklist berhasil diassign ke user']);
    }

    public function updateChecklist(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $checklist = TaskChecklist::findOrFail($id);
        $checklist->update([
            'title' => $request->title,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Checklist berhasil diupdate',
        ]);
    }
public function uploadChecklistFile(Request $request, $id)
{
    $request->validate([
        'file'      => 'required|file|mimes:jpg,jpeg,png,gif,webp,pdf,xls,xlsx|max:10240',
        'latitude'  => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
        'address'   => 'nullable|string',
    ]);

    $checklist = TaskChecklist::findOrFail($id);

    if ($checklist->file_path) {
        \Storage::disk('public')->delete($checklist->file_path);
    }

    $file = $request->file('file');
    $path = $file->store('checklist-files', 'public');

    $checklist->update([
        'file_path'      => $path,
        'file_name'      => $file->getClientOriginalName(),
        'file_type'      => $file->getMimeType(),
        'is_done'        => true,
        'uncheck_reason' => null,
        'latitude'       => $request->latitude,
        'longitude'      => $request->longitude,
        'address'        => $request->address,
    ]);

    $this->syncAssignmentStatus($checklist->task_assignment_id);

    return response()->json([
        'success' => true,
        'message' => 'File berhasil diupload dan checklist ditandai selesai.',
    ]);
}

public function deleteChecklistFile($id)
{
    $checklist = TaskChecklist::findOrFail($id);

    if ($checklist->file_path) {
        \Storage::disk('public')->delete($checklist->file_path);
    }

    $checklist->update([
        'file_path'  => null,
        'file_name'  => null,
        'file_type'  => null,
        'is_done'    => false,
        'latitude'   => null,
        'longitude'  => null,
        'address'    => null,
    ]);

    $this->syncAssignmentStatus($checklist->task_assignment_id);

    return response()->json(['success' => true, 'message' => 'File berhasil dihapus.']);
}
    public function managerUncheck(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $checklist = TaskChecklist::findOrFail($id);

        if ($checklist->file_path) {
            \Storage::disk('public')->delete($checklist->file_path);
        }

        $checklist->update([
            'is_done' => false,
            'file_path' => null,
            'file_name' => null,
            'file_type' => null,
            'uncheck_reason' => $request->reason,
        ]);

        $this->syncAssignmentStatus($checklist->task_assignment_id);

        return response()->json([
            'success' => true,
            'message' => 'Checklist berhasil dibatalkan dan file dihapus.',
        ]);
    }

    private function syncAssignmentStatus(?int $assignmentId): void
    {
        if (!$assignmentId) {
            return;
        }

        $assignment = TaskAssignment::withCount([
            'checklists as total_checklists',
            'checklists as done_checklists' => function ($query) {
                $query->where('is_done', 1);
            },
        ])->find($assignmentId);

        if (!$assignment) {
            return;
        }

        $taskId = (int) $assignment->task_id;

        // Keep manual reject state untouched.
        if ($assignment->getRawOriginal('status') === 'reject') {
            $this->syncTaskStatus($taskId);
            return;
        }

        $totalChecklist = (int) $assignment->total_checklists;
        $doneChecklist = (int) $assignment->done_checklists;

        if ($totalChecklist <= 0) {
            $newStatus = 'pending';
        } elseif ($doneChecklist >= $totalChecklist) {
            $newStatus = 'done';
        } elseif ($doneChecklist > 0) {
            $newStatus = 'progress';
        } else {
            $newStatus = 'pending';
        }

        if ($assignment->getRawOriginal('status') !== $newStatus) {
            $assignment->update(['status' => $newStatus]);
        }

        $this->syncTaskStatus($taskId);
    }

    private function syncTaskStatus(int $taskId): void
    {
        if ($taskId <= 0) {
            return;
        }

        $task = Task::find($taskId);

        if (!$task) {
            return;
        }

        // Keep manual reject state untouched.
        if ($task->getRawOriginal('status') === 'reject') {
            return;
        }

        $totalChecklist = TaskChecklist::whereHas('assignment', function ($query) use ($taskId) {
            $query->where('task_id', $taskId);
        })->count();

        $doneChecklist = TaskChecklist::whereHas('assignment', function ($query) use ($taskId) {
            $query->where('task_id', $taskId);
        })->where('is_done', 1)->count();

        if ($totalChecklist <= 0) {
            $newStatus = 'pending';
        } elseif ($doneChecklist >= $totalChecklist) {
            $newStatus = 'done';
        } elseif ($doneChecklist > 0) {
            $newStatus = 'progress';
        } else {
            $newStatus = 'pending';
        }

        if ($task->getRawOriginal('status') !== $newStatus) {
            $task->update(['status' => $newStatus]);
        }
    }
}