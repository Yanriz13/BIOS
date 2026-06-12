<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\TaskChecklist;
use App\Models\DailyRoutineChecklist;
use App\Models\DailyRoutineChecklistHistory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $taskQuery       = Task::query();
        $assignmentQuery = TaskAssignment::query();
        $checklistQuery  = TaskChecklist::query();
        $employeeQuery   = User::where('role', 'staff');

        if (in_array($user->role, ['admin_divisi', 'supervisor'])) {
            $taskQuery->where('divisi', $user->divisi);
            $assignmentQuery->whereHas('task', fn($q) => $q->where('divisi', $user->divisi));
            $checklistQuery->whereHas('assignment.task', fn($q) => $q->where('divisi', $user->divisi));
            $employeeQuery->where('divisi', $user->divisi);
            $scopeTitle = 'Divisi ' . $user->divisi;

        } elseif (in_array($user->role, ['direksi', 'manager'])) {
            $scopeTitle = 'Semua Divisi';

        } else {
            $assignmentQuery->where('user_id', $user->id);
            $checklistQuery->whereHas('assignment', fn($q) => $q->where('user_id', $user->id));
            $employeeQuery->where('id', $user->id);
            $scopeTitle = $user->name;
        }

        $resolveAssignmentStatus = function ($assignment) {
            if ($assignment->status === 'reject') {
                return 'reject';
            }

            $totalChecklist = $assignment->checklists->count();
            $doneChecklist  = $assignment->checklists->where('is_done', 1)->count();

            if ($totalChecklist > 0) {
                if ($doneChecklist >= $totalChecklist) {
                    return 'done';
                }

                if ($doneChecklist > 0) {
                    return 'progress';
                }

                return 'pending';
            }

            return in_array($assignment->status, ['pending', 'progress', 'done'], true)
                ? $assignment->status
                : 'pending';
        };

        /*
        |--------------------------------------------------------------------------
        | SUMMARY COUNTS
        |--------------------------------------------------------------------------
        */
        $projectCount           = $taskQuery->count();
        $taskCount              = $assignmentQuery->count();
        $checklistCount         = $checklistQuery->count();
        $doneChecklistCount     = (clone $checklistQuery)->where('is_done', 1)->count();
        $progressChecklistCount = $checklistCount - $doneChecklistCount;
        $activeStaffCount       = (clone $assignmentQuery)->distinct('user_id')->count('user_id');

        /*
        |--------------------------------------------------------------------------
        | DROPDOWN PROJECT LIST
        |--------------------------------------------------------------------------
        */
        $projects = (clone $taskQuery)->select('id', 'title')->orderBy('title')->get();

        /*
        |--------------------------------------------------------------------------
        | TOP 5 DEADLINE PROJECT
        |--------------------------------------------------------------------------
        */
        $projectCards = (clone $taskQuery)
            ->with(['assignments.user', 'assignments.checklists'])
            ->whereHas('assignments')
            ->get()
            ->map(function ($task) use ($resolveAssignmentStatus) {
                $deadline = $task->assignments->whereNotNull('deadline')->min('deadline');
                $total    = $task->assignments->count();
                $done     = $task->assignments->filter(function ($assignment) use ($resolveAssignmentStatus) {
                    return $resolveAssignmentStatus($assignment) === 'done';
                })->count();

                $assignmentDetail = $task->assignments->map(function ($a) use ($resolveAssignmentStatus) {
                    $totalCl = $a->checklists->count();
                    $doneCl  = $a->checklists->where('is_done', 1)->count();
                    return [
                        'user_name'  => optional($a->user)->name ?? '-',
                        'status'     => $resolveAssignmentStatus($a),
                        'deadline'   => $a->deadline,
                        'total_cl'   => $totalCl,
                        'done_cl'    => $doneCl,
                        'checklists' => $a->checklists->map(fn($cl) => [
                            'name'    => $cl->name ?? $cl->title ?? $cl->description ?? 'Checklist #' . $cl->id,
                            'is_done' => $cl->is_done,
                        ])->values(),
                    ];
                })->values();

                return [
                    'id'                => $task->id,
                    'title'             => $task->title,
                    'divisi'            => $task->divisi,
                    'deadline'          => $deadline,
                    'total_task'        => $total,
                    'done_task'         => $done,
                    'progress'          => $total > 0 ? round(($done / $total) * 100) : 0,
                    'assignment_detail' => $assignmentDetail,
                ];
            })
            ->sortBy('deadline')
            ->take(5)
            ->values();

        /*
        |--------------------------------------------------------------------------
        | EMPLOYEE PERFORMANCE
        |--------------------------------------------------------------------------
        */
        $employees     = $employeeQuery->get();
        $employeeStats = [];

        foreach ($employees as $employee) {
            $assignments = TaskAssignment::with(['task', 'checklists'])
                ->where('user_id', $employee->id)
                ->get();

            $statusCounts = $assignments
                ->map(fn($assignment) => $resolveAssignmentStatus($assignment))
                ->countBy()
                ->toArray();

            $totalTasks      = $assignments->count();
            $doneTasks       = (int) ($statusCounts['done'] ?? 0);
            $progressTasks   = (int) ($statusCounts['progress'] ?? 0);
            $pendingTasks    = (int) ($statusCounts['pending'] ?? 0);
            $projectCountEmp = $assignments->pluck('task_id')->unique()->count();

            $checklists     = TaskChecklist::whereHas('assignment', fn($q) => $q->where('user_id', $employee->id))->get();
            $totalChecklist = $checklists->count();
            $doneChecklist  = $checklists->where('is_done', 1)->count();

            $taskDetail = $assignments->map(function ($a) use ($resolveAssignmentStatus) {
                return [
                    'assignment_id' => $a->id,
                    'task_title'    => $a->task->title ?? '-',
                    'task_divisi'   => $a->task->divisi ?? '-',
                    'status'        => $resolveAssignmentStatus($a),
                    'deadline'      => $a->deadline,
                    'total_cl'      => $a->checklists->count(),
                    'done_cl'       => $a->checklists->where('is_done', 1)->count(),
                    'checklists'    => $a->checklists->map(fn($cl) => [
                        'name'    => $cl->name ?? $cl->title ?? $cl->description ?? 'Checklist #' . $cl->id,
                        'is_done' => $cl->is_done,
                    ])->values(),
                ];
            })->values();

            $sla = $totalChecklist > 0 ? round(($doneChecklist / $totalChecklist) * 100) : 0;

            $doneAssignments = $assignments->filter(function ($assignment) use ($resolveAssignmentStatus) {
                return $resolveAssignmentStatus($assignment) === 'done';
            });

            $durationHours = $doneAssignments->map(function ($assignment) {
                if (!$assignment->created_at) {
                    return null;
                }

                // Prefer the latest checked checklist time when available.
                $lastChecklistDoneAt = $assignment->checklists
                    ->where('is_done', 1)
                    ->max('updated_at');

                $endAt = $lastChecklistDoneAt ?: $assignment->updated_at;

                if (!$endAt) {
                    return null;
                }

                return $assignment->created_at->diffInHours($endAt);
            })->filter(fn($hours) => $hours !== null);

            $avgDuration = $durationHours->isNotEmpty()
                ? round($durationHours->avg())
                : null;

            $employeeStats[] = [
                'employee'        => $employee,
                'project_count'   => $projectCountEmp,
                'task_count'      => $totalTasks,
                'done_tasks'      => $doneTasks,
                'progress_tasks'  => $progressTasks,
                'pending_tasks'   => $pendingTasks,
                'total_checklist' => $totalChecklist,
                'done_checklist'  => $doneChecklist,
                 'avg_duration'    => $avgDuration,
                'sla'             => $sla,
                'task_detail'     => $taskDetail,
            ];
        }

        $employeeStats = collect($employeeStats)->sortByDesc('sla')->values();
        $topPerformers = $employeeStats->take(3);

        /*
        |--------------------------------------------------------------------------
        | ACTIVITY (today's assignments)
        |--------------------------------------------------------------------------
        */
        $activityEntries = (clone $assignmentQuery)
            ->with(['task', 'user', 'checklists'])
            ->latest()
            ->paginate(20);

        /*
        |--------------------------------------------------------------------------
        | MAP LOCATIONS — DailyRoutineChecklist
        |--------------------------------------------------------------------------
        */
      $dailyLocationQuery = DailyRoutineChecklist::with(['routine.user'])
    ->whereNotNull('latitude')
    ->whereNotNull('longitude')
    ->where('latitude', '!=', '')
    ->where('longitude', '!=', '');

$dailyHistoryQuery = DailyRoutineChecklistHistory::with(['history.user'])
    ->whereNotNull('latitude')
    ->whereNotNull('longitude')
    ->where('latitude', '!=', '')
    ->where('longitude', '!=', '')
    ->where('is_done', true);

                if ($user->role === 'admin_divisi') {
            $dailyLocationQuery->whereHas('routine.user', fn($q) =>
                $q->where('divisi', $user->divisi)
                  ->whereIn('role', ['staff', 'supervisor'])
            );
            $dailyHistoryQuery->whereHas('history.user', fn($q) =>
                $q->where('divisi', $user->divisi)
                  ->whereIn('role', ['staff', 'supervisor'])
            );
        } elseif ($user->role === 'supervisor') {
            $supervisorStaffIds = \App\Models\User::where('supervisor_id', $user->id)
                ->pluck('id')
                ->push($user->id);

            $dailyLocationQuery->whereHas('routine', fn($q) =>
                $q->whereIn('user_id', $supervisorStaffIds)
            );
            $dailyHistoryQuery->whereHas('history', fn($q) =>
                $q->whereIn('user_id', $supervisorStaffIds)
            );
        } elseif ($user->role === 'staff') {
            $dailyLocationQuery->whereHas('routine', fn($q) =>
                $q->where('user_id', $user->id)
            );
            $dailyHistoryQuery->whereHas('history', fn($q) =>
                $q->where('user_id', $user->id)
            );
        }
        // direksi: tidak ada filter tambahan, lihat semua

        $dailyLocations        = $dailyLocationQuery->latest()->get();
        $dailyHistoryLocations = $dailyHistoryQuery->latest()->get();

        /*
        |--------------------------------------------------------------------------
        | MAP LOCATIONS — TaskChecklist
        |--------------------------------------------------------------------------
        */
        $taskChecklistQuery = TaskChecklist::with(['assignment.task', 'assignment.user'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');

        if (in_array($user->role, ['admin_divisi', 'supervisor'])) {
            $taskChecklistQuery->whereHas('assignment.task', fn($q) =>
                $q->where('divisi', $user->divisi)
            );
        } elseif ($user->role === 'staff') {
            $taskChecklistQuery->whereHas('assignment', fn($q) =>
                $q->where('user_id', $user->id)
            );
        }
        // direksi: tidak ada filter tambahan, lihat semua

        $taskLocations = $taskChecklistQuery->latest()->get();

        /*
        |--------------------------------------------------------------------------
        | GABUNGKAN KE $dashboardLocations
        |--------------------------------------------------------------------------
        */
    $dashboardLocations = $dailyLocations->map(fn($item) => [
    'id'        => 'daily_' . $item->id,
    'title'     => $item->title ?? 'Daily Routine',
    'lat'       => ($item->latitude  !== null && $item->latitude  !== '') ? (float) $item->latitude  : null,
    'lng'       => ($item->longitude !== null && $item->longitude !== '') ? (float) $item->longitude : null,
    'address'   => $item->address,
    'created'   => optional($item->created_at)->format('d M Y H:i'),
    'user_name' => optional($item->routine?->user)->name ?? '-',
    'task_name' => '-',
    'file_path' => $item->file_path,
    'file_type' => $item->file_type,
    'source'    => 'daily',
])->filter(fn($item) => $item['lat'] !== null && $item['lng'] !== null)
  ->merge(
    $dailyHistoryLocations->map(fn($item) => [
        'id'        => 'daily_hist_' . $item->id,
        'title'     => $item->title ?? 'Daily Routine (Arsip)',
        'lat'       => ($item->latitude  !== null && $item->latitude  !== '') ? (float) $item->latitude  : null,
        'lng'       => ($item->longitude !== null && $item->longitude !== '') ? (float) $item->longitude : null,
        'address'   => $item->address,
        'created'   => optional($item->created_at)->format('d M Y H:i'),
        'user_name' => optional($item->history?->user)->name ?? '-',
        'task_name' => '-',
        'file_path' => $item->file_path,
        'file_type' => $item->file_type,
        'source'    => 'daily',
    ])->filter(fn($item) => $item['lat'] !== null && $item['lng'] !== null)
)->merge(
    $taskLocations->map(fn($item) => [
        'id'        => 'task_' . $item->id,
        'title'     => $item->title ?? 'Task Checklist',
        'lat'       => ($item->latitude  !== null && $item->latitude  !== '') ? (float) $item->latitude  : null,
        'lng'       => ($item->longitude !== null && $item->longitude !== '') ? (float) $item->longitude : null,
        'address'   => $item->address,
        'created'   => optional($item->created_at)->format('d M Y H:i'),
        'user_name' => optional($item->assignment?->user)->name  ?? '-',
        'task_name' => optional($item->assignment?->task)->title ?? '-',
        'file_path' => $item->file_path,
        'file_type' => $item->file_type,
        'source'    => 'task',
    ])->filter(fn($item) => $item['lat'] !== null && $item['lng'] !== null)
)->values();

        return view('dashboard.main', compact(
            'projects',
            'projectCount',
            'taskCount',
            'checklistCount',
            'doneChecklistCount',
            'progressChecklistCount',
            'activeStaffCount',
            'scopeTitle',
            'projectCards',
            'employeeStats',
            'topPerformers',
            'activityEntries',
            'dashboardLocations',   // ← gabungan daily + task
        ));
    }

    public function managementTeam()
    {
        $user = Auth::user();
        $divisi = $user->divisi;

        $supervisors = User::where('role', 'supervisor')
            ->where('divisi', $divisi)
            ->get();

        $staff = User::where('role', 'staff')
            ->where('divisi', $divisi)
            ->get();

        return view('manager.management-team', compact('supervisors', 'staff', 'divisi'));
    }

    public function assignSupervisor(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'assignments' => 'required|array',
            'assignments.*.user_id' => 'required|integer|exists:users,id',
            'assignments.*.supervisor_id' => 'nullable|integer|exists:users,id',
        ]);

        foreach ($request->assignments as $item) {
            $user = \App\Models\User::find($item['user_id']);
            if ($user) {
                $user->supervisor_id = $item['supervisor_id'] ?? null;
                $user->save();
            }
        }

        return redirect()->route('manager.management.team')
            ->with('success', 'Assignment supervisor berhasil disimpan');
    }

    public function assignMultipleToSupervisor(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'supervisor_id' => 'required|integer|exists:users,id',
            'staff_ids' => 'nullable|array',
            'staff_ids.*' => 'integer|exists:users,id',
        ]);

        $supervisorId = $request->supervisor_id;
        $selected = $request->staff_ids ?? [];

        // Remove supervisor from users previously assigned to this supervisor but not selected now
        \App\Models\User::where('supervisor_id', $supervisorId)
            ->whereNotIn('id', $selected)
            ->update(['supervisor_id' => null]);

        if (!empty($selected)) {
            \App\Models\User::whereIn('id', $selected)
                ->update(['supervisor_id' => $supervisorId]);
        }

        return redirect()->route('manager.management.team')
            ->with('success', 'Assignment supervisor berhasil diperbarui');
    }
}