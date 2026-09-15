<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SuperAdmin\UserManagementController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DailyRoutineController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SuperAdmin\DivisiController;
use App\Http\Controllers\SuperAdmin\DepartemenController;

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->role == 'super_admin') {
            return redirect()->route('superadmin.users.index');
        }
        if (in_array($user->role, ['direksi', 'gh', 'div_head', 'admin_dept'])) {
            return redirect()->route('divhead.dashboard');
        }
        if (in_array($user->role, ['dept_head'])) {
            return redirect()->route('depthead.project.index');
        }
        if ($user->role == 'staff') {
            return redirect()->route('staff.project.index');
        }
    }
    return redirect()->route('login');
});

Auth::routes();


// =============================
// SUPER ADMIN
// =============================
Route::middleware(['auth', 'role:super_admin'])
    ->group(function () {

        Route::get('/super-admin/dashboard', function () {
            return view('dashboard.superadmin');
        })->name('superadmin.dashboard');
    });

// =============================
// DIREKSI, GH, DIV HEAD & ADMIN DEPT
// =============================
Route::middleware(['auth', 'role:admin_dept,div_head,direksi,gh'])
    ->group(function () {

        Route::get('/divhead/dashboard', [HomeController::class, 'index'])
            ->name('divhead.dashboard');

        Route::get('/divhead/management-tim', [HomeController::class, 'managementTeam'])
            ->name('divhead.management.team');
    });

Route::middleware(['auth', 'role:admin_dept,div_head'])
    ->group(function () {
        Route::post('/divhead/management-tim/assign', [HomeController::class, 'assignSupervisor'])
            ->name('divhead.management.team.assign');

        Route::post('/divhead/management-tim/assign-multiple', [HomeController::class, 'assignMultipleToSupervisor'])
            ->name('divhead.management.team.assign-multiple');
    });


// =============================
// STAFF
// =============================
Route::middleware(['auth', 'role:staff'])
    ->group(function () {

        Route::get('/staff/dashboard', [HomeController::class, 'index'])
            ->name('staff.dashboard');
    });


// =============================
// DEPT HEAD
// =============================
Route::middleware(['auth', 'role:dept_head'])
    ->group(function () {

        Route::get('/depthead/dashboard', [HomeController::class, 'index'])
            ->name('depthead.dashboard');

        Route::get('/depthead/project', [ProjectController::class, 'deptheadProject'])
            ->name('depthead.project.index');
    });


// =============================
// SUPER ADMIN USER & DIVISI & DEPARTEMEN MANAGEMENT
// =============================
Route::middleware(['auth', 'role:super_admin'])
    ->prefix('super-admin')
    ->name('superadmin.')
    ->group(function () {

        Route::resource('users', UserManagementController::class);
        Route::resource('divisi', DivisiController::class);
        Route::resource('departemen', DepartemenController::class);
        Route::get('/divisi/{divisiId}/departemens', [DepartemenController::class, 'getByDivisi'])->name('departemen.by-divisi');
    });


// ======================================================
// PROJECT VIEW (ADMIN DEPT + DIV HEAD + DIREKSI + GH)
// ======================================================
Route::middleware(['auth', 'role:admin_dept,div_head,direksi,gh'])
    ->group(function () {

        Route::get('/project', [ProjectController::class, 'index'])
            ->name('project.index');

        Route::get('/project/{id}/detail', [ProjectController::class, 'detail'])
            ->name('project.detail');
    });


// ======================================================
// PROJECT ACTION (ADMIN DEPT & DIV HEAD ONLY)
// ======================================================
Route::middleware(['auth', 'role:admin_dept,div_head'])
    ->group(function () {

        Route::post('/project/store', [ProjectController::class, 'store'])
            ->name('project.store');

        Route::put('/project/{id}', [ProjectController::class, 'update'])
            ->name('project.update');

        Route::post('/project/{id}/users', [ProjectController::class, 'addUsers'])
            ->name('project.users.add');

        Route::post('/project/{id}/status', [ProjectController::class, 'updateStatus'])
            ->name('project.updateStatus');

        Route::post('/project/save-assignment', [ProjectController::class, 'saveAssignment'])
            ->name('project.save-assignment');

        Route::post('/project/checklist/{id}/assign', [ProjectController::class, 'assignChecklist'])
            ->name('project.checklist.assign');

        Route::post('/project/{id}/todo', [ProjectController::class, 'storeTodo'])
            ->name('project.todo.store');

        Route::post('/project/todo/{id}/assign', [ProjectController::class, 'assignTodo'])
            ->name('project.todo.assign');

        Route::post('/project/{id}/todos', [ProjectController::class, 'storeTodos'])
            ->name('project.todos.store');

        Route::put('/project/assignment/{id}', [ProjectController::class, 'updateAssignment'])
            ->name('project.assignment.update');

        Route::delete('/project/assignment/{id}', [ProjectController::class, 'destroyAssignment'])
            ->name('project.assignment.destroy');

        Route::put('/project/checklist/{id}', [ProjectController::class, 'updateChecklist'])
            ->name('project.checklist.update');

        Route::delete('/project/checklist/{id}', [ProjectController::class, 'destroyChecklist'])
            ->name('project.checklist.destroy');

        Route::patch('/project/checklist/{id}/unassign', [ProjectController::class, 'unassignChecklist'])
            ->name('project.checklist.unassign');

        Route::patch('/project/checklist/{id}/divhead-uncheck', [ProjectController::class, 'divheadUncheck'])
            ->name('project.checklist.divhead-uncheck');
    });


// ======================================================
// STAFF PROJECT
// ======================================================
Route::middleware(['auth', 'role:staff,dept_head'])
    ->group(function () {

        Route::get('/staff/project', [ProjectController::class, 'staffProject'])
            ->name('staff.project.index');

        Route::post('/project/checklist/{id}/toggle', [ProjectController::class, 'toggleChecklist'])
            ->name('project.checklist.toggle');

        Route::post('/project/checklist/{id}/upload-file', [ProjectController::class, 'uploadChecklistFile'])
            ->name('project.checklist.upload-file');

        Route::delete('/project/checklist/{id}/delete-file', [ProjectController::class, 'deleteChecklistFile'])
            ->name('project.checklist.delete-file');
    });


// ======================================================
// CHAT (SEMUA LOGIN)
// ======================================================
Route::middleware(['auth'])->group(function () {

    Route::get('/chat/room/task/{taskId}', [ChatController::class, 'taskRoom'])
        ->name('chat.room.task');

    Route::get('/chat/room/user/{userId}', [ChatController::class, 'userRoom'])
        ->name('chat.room.user');

    Route::get('/chat/room/global', [ChatController::class, 'globalRoom'])
        ->name('chat.room.global');

    Route::post('/chat/message', [ChatController::class, 'sendMessage'])
        ->name('chat.message.send');

    Route::get('/chat/notifications', [ChatController::class, 'notifications'])
        ->name('chat.notifications');
});


// ======================================================
// DAILY ROUTINE VIEW
// ======================================================
Route::prefix('project/daily-routine')
    ->name('daily-routine.')
    ->middleware(['auth', 'role:admin_dept,div_head,direksi,gh,dept_head,staff'])
    ->group(function () {

        Route::get('/', [DailyRoutineController::class, 'index'])
            ->name('index');

        Route::get('/history', [DailyRoutineController::class, 'history'])
            ->name('history');
    });


// ======================================================
// DAILY ROUTINE ACTION (ADMIN DEPT & DEPT HEAD)
// ======================================================
Route::prefix('project/daily-routine')
    ->name('daily-routine.')
    ->middleware(['auth', 'role:admin_dept,dept_head,div_head'])
    ->group(function () {

        Route::post('/', [DailyRoutineController::class, 'store'])
            ->name('store');

        Route::put('{id}', [DailyRoutineController::class, 'update'])
            ->name('update');

        Route::patch('{id}/status', [DailyRoutineController::class, 'updateStatus'])
            ->name('update-status');

        Route::patch('{id}/reassign', [DailyRoutineController::class, 'reassign'])
            ->name('reassign');

        Route::delete('{id}', [DailyRoutineController::class, 'destroy'])
            ->name('destroy');

        Route::post('{id}/checklist', [DailyRoutineController::class, 'checklistStore'])
            ->name('checklist.store');

        Route::post('checklist/add', [DailyRoutineController::class, 'checklistStoreLegacy'])
            ->name('checklist.store-legacy');

        Route::delete('checklist/{checklistId}', [DailyRoutineController::class, 'checklistDestroy'])
            ->name('checklist.destroy');

        Route::patch('checklist/{checklistId}/divhead-uncheck', [DailyRoutineController::class, 'checklistDivheadUncheck'])
            ->name('checklist.divhead-uncheck');
    });


// ======================================================
// DAILY ROUTINE STAFF & DEPT HEAD
// ======================================================
Route::prefix('project/daily-routine')
    ->name('daily-routine.')
    ->middleware(['auth', 'role:staff,dept_head'])
    ->group(function () {

        Route::patch('checklist/{checklistId}/toggle', [DailyRoutineController::class, 'checklistToggle'])
            ->name('checklist.toggle');

        Route::post('checklist/{checklistId}/upload-file', [DailyRoutineController::class, 'checklistUploadFile'])
            ->name('checklist.upload-file');

        Route::delete('checklist/{checklistId}/delete-file', [DailyRoutineController::class, 'checklistDeleteFile'])
            ->name('checklist.delete-file');
    });
