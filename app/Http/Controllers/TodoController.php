<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskAssignment;
use App\Models\TaskChecklist;

class TodoController extends Controller
{
    public function index()
    {
        return view('project.index', [

            'pendingTasks' => Task::where('status', 'pending')->latest()->get(),
            'progressTasks' => Task::where('status', 'progress')->latest()->get(),
            'doneTasks' => Task::where('status', 'done')->latest()->get(),
            'rejectTasks' => Task::where('status', 'reject')->latest()->get(),
            'users' => User::all(),

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

    ]);

    if ($request->user_ids) {

        $task->users()->attach($request->user_ids);

    }

    return redirect()->back()
        ->with('success', 'Task berhasil ditambahkan');
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

        'user_id' => 'required|exists:users,id',

        'description' => 'nullable',

        'deadline' => 'nullable|date',

        'notes' => 'nullable',

        'file' => 'nullable|file',

    ]);

    $fileName = null;

    if ($request->hasFile('file')) {

        $fileName = $request->file('file')
            ->store('task-files', 'public');

    }

    $assignment = TaskAssignment::create([

        'task_id' => $request->task_id,

        'user_id' => $request->user_id,

        'description' => $request->description,

        'deadline' => $request->deadline,

        'notes' => $request->notes,

        'file' => $fileName,

        'status' => 'pending'

    ]);

    if ($request->checklists) {

        foreach ($request->checklists as $item) {

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
public function viewAssignment($taskId, $userName)
{
    $assignment = TaskAssignment::with([
        'task',
        'user',
        'checklists'
    ])
    ->where('task_id', $taskId)
    ->whereHas('user', function($q) use ($userName) {

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
}