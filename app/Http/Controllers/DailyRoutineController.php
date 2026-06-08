<?php

namespace App\Http\Controllers;

use App\Models\DailyRoutine;
use App\Models\DailyRoutineChecklist;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Models\DailyRoutineHistory;
use App\Models\DailyRoutineChecklistHistory;

class DailyRoutineController extends Controller
{
    // ─── Show tab in task detail ───────────────────────────

    /**
     * Data untuk tab Daily Routine di halaman detail task.
     * Dipanggil via partial/inject ke view task show.
     */
public function index(): View
{
    // dd(now()->format('l'));
    $mapDays = [
        'Sunday' => 'minggu',
        'Monday' => 'senin',
        'Tuesday' => 'selasa',
        'Wednesday' => 'rabu',
        'Thursday' => 'kamis',
        'Friday' => 'jumat',
        'Saturday' => 'sabtu',
    ];

    $today = strtolower(trim($mapDays[now()->format('l')]));

    /*
    |--------------------------------------------------------------------------
    | STAFF
    |--------------------------------------------------------------------------
    */

    if (auth()->user()->role === 'staff') {

        $routines = DailyRoutine::with(['checklists', 'task'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get()
            ->filter(function ($routine) use ($today) {

                if (!$routine->deadline) {
                    return false;
                }

                $days = collect(explode(',', strtolower($routine->deadline)))
                    ->map(fn($day) => trim($day))
                    ->toArray();

                return in_array($today, $days);
            })
            ->values();

        return view('staff.project.index', compact('routines'));
    }

    /*
    |--------------------------------------------------------------------------
    | SUPERVISOR
    |--------------------------------------------------------------------------
    */

    if (auth()->user()->role === 'supervisor') {
        $members = User::where('role', 'staff')
            ->where('supervisor_id', auth()->id())
            ->get();

        $allRoutines = DailyRoutine::with(['user', 'checklists', 'task'])
            ->whereHas('user', function ($q) {
                $q->where('supervisor_id', auth()->id());
            })
            ->latest()
            ->get()
            ->filter(function ($routine) use ($today) {
                if (!$routine->deadline) return false;
                $days = collect(explode(',', strtolower($routine->deadline)))
                    ->map(fn($day) => trim($day))->toArray();
                return in_array($today, $days);
            });

        // Group routines keyed by user_id
        $routinesByUser = $allRoutines->groupBy('user_id');

        $tasks = Task::all();

        return view('supervisor.daily-routine', compact(
            'routinesByUser',
            'members',
            'tasks'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | MANAGER / ADMIN
    |--------------------------------------------------------------------------
    */

    $routines = DailyRoutine::with(['user', 'checklists', 'task'])
        ->whereHas('user', function ($q) {
            $q->where('divisi', auth()->user()->divisi);
        })
        ->latest()
        ->get()
        ->filter(function ($routine) use ($today) {

            if (!$routine->deadline) {
                return false;
            }

            $days = collect(explode(',', strtolower($routine->deadline)))
                ->map(fn($day) => trim($day))
                ->toArray();

            return in_array($today, $days);
        })
        ->values();

    $tasks = Task::all();

    $members = User::where('role', 'staff')
        ->where('divisi', auth()->user()->divisi)
        ->get();

    return view('daily-routine.index', compact(
        'routines',
        'tasks',
        'members'
    ));
}
    public function forTask(int $taskId): array
    {
        $task = Task::with('users')->findOrFail($taskId);
        $routines = DailyRoutine::with(['user', 'checklists', 'createdBy'])
            ->forTask($taskId)
            ->latest()
            ->get();

        return compact('task', 'routines');
    }

    // ─── Store (create routine + optional assign) ──────────

public function store(Request $request): JsonResponse
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'deadline' => 'nullable|string',
        'notes' => 'nullable|string',
        'user_id' => 'nullable|exists:users,id',
        'checklists' => 'nullable|array',
        'checklists.*' => 'nullable|string|max:255',
    ]);

    $mapDays = [
        'Sunday' => 'minggu',
        'Monday' => 'senin',
        'Tuesday' => 'selasa',
        'Wednesday' => 'rabu',
        'Thursday' => 'kamis',
        'Friday' => 'jumat',
        'Saturday' => 'sabtu',
    ];

    $today = $mapDays[now()->format('l')] ?? null;

    DB::beginTransaction();

    try {

        $routine = DailyRoutine::create([
            'user_id' => $validated['user_id'] ?? null,
            'created_by' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'deadline' => $validated['deadline'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        if (!empty($validated['checklists'])) {
            foreach (array_filter($validated['checklists']) as $item) {

                DailyRoutineChecklist::create([
                    'daily_routine_id' => $routine->id,
                    'title' => $item,

                    // 🔥 PENTING INI
                    'day_name' => $today,
                    'is_done' => false,
                ]);
            }
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Daily routine berhasil dibuat.',
            'data' => $routine->load('checklists', 'user'),
        ]);

    } catch (\Throwable $e) {
        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

    // ─── Update status ─────────────────────────────────────

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $request->validate(['status' => 'required|in:pending,progress,done']);
        $routine = DailyRoutine::findOrFail($id);
        $routine->update(['status' => $request->status]);

        return response()->json(['success' => true, 'message' => 'Status diperbarui.']);
    }

    // ─── Delete routine ────────────────────────────────────

    public function destroy(int $id): JsonResponse
    {
        $routine = DailyRoutine::findOrFail($id);
        $routine->delete();

        return response()->json(['success' => true, 'message' => 'Daily routine dihapus.']);
    }

    // ─── Checklist: toggle done ────────────────────────────

public function checklistToggle(Request $request, int $checklistId)
{
    $checklist = DailyRoutineChecklist::findOrFail($checklistId);

    $newStatus = !$checklist->is_done;

    $updateData = [
        'is_done' => $newStatus,
    ];

    if ($newStatus) {
        $mapDays = [
            'Sunday' => 'minggu',
            'Monday' => 'senin',
            'Tuesday' => 'selasa',
            'Wednesday' => 'rabu',
            'Thursday' => 'kamis',
            'Friday' => 'jumat',
            'Saturday' => 'sabtu',
        ];

        $updateData['day_name'] = $mapDays[now()->format('l')] ?? null;
        $updateData['checked_at'] = now();
    }

   if (!$newStatus) {

    if ($checklist->file_path) {
        \Storage::disk('public')->delete($checklist->file_path);
    }

    $updateData['file_path'] = null;
    $updateData['file_name'] = null;
    $updateData['file_type'] = null;

    $updateData['latitude'] = null;
    $updateData['longitude'] = null;
    $updateData['address'] = null;
}

    $checklist->update($updateData);

    return response()->json([
        'success' => true,
        'is_done' => $checklist->is_done,
    ]);
}

    // ─── Checklist: manager uncheck with reason ────────────

    public function checklistManagerUncheck(Request $request, int $checklistId): JsonResponse
    {
        $request->validate(['reason' => 'required|string|max:500']);
        $checklist = DailyRoutineChecklist::findOrFail($checklistId);

        // Delete file bukti jika ada
        if ($checklist->file_path) {
            \Storage::disk('public')->delete($checklist->file_path);
        }
$checklist->update([
    'is_done' => false,
    'file_path' => null,
    'file_name' => null,
    'file_type' => null,

    'latitude' => null,
    'longitude' => null,
    'address' => null,

    'uncheck_reason' => $request->reason,
]);

        return response()->json(['success' => true, 'message' => 'Checklist dibatalkan.']);
    }

    // ─── Checklist: delete ─────────────────────────────────

    public function checklistDestroy(int $checklistId): JsonResponse
    {
        $checklist = DailyRoutineChecklist::findOrFail($checklistId);
        if ($checklist->file_path) {
            \Storage::disk('public')->delete($checklist->file_path);
        }
        $checklist->delete();

        return response()->json(['success' => true, 'message' => 'Checklist item dihapus.']);
    }

    // ─── Re-assign routine to another member ──────────────

    public function reassign(Request $request, int $id): JsonResponse
    {
        $request->validate(['user_id' => 'nullable|exists:users,id']);
        if ($request->filled('user_id')) {

            $user = User::where('id', $request->user_id)
                ->where('divisi', auth()->user()->divisi)
                ->where('role', 'staff')
                ->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya bisa assign ke staff dengan divisi yang sama.'
                ], 403);
            }
        }
        $routine = DailyRoutine::findOrFail($id);
        $routine->update(['user_id' => $request->user_id]);

        return response()->json(['success' => true, 'message' => 'Routine berhasil di-reassign.']);
    }


    // ─── Checklist: upload file ────────────────────────────

    public function checklistUploadFile(Request $request, int $checklistId): JsonResponse
    {
        $request->validate([
    'file' => 'required|file|mimes:jpg,jpeg,png,gif,webp,pdf,xls,xlsx|max:10240',

    'latitude'  => 'nullable|numeric',
    'longitude' => 'nullable|numeric',
    'address'   => 'nullable|string',
]);

        $checklist = DailyRoutineChecklist::with('routine')->findOrFail($checklistId);

        // hari sekarang
        $mapDays = [
    'Sunday' => 'minggu',
    'Monday' => 'senin',
    'Tuesday' => 'selasa',
    'Wednesday' => 'rabu',
    'Thursday' => 'kamis',
    'Friday' => 'jumat',
    'Saturday' => 'sabtu',
];

$today = $mapDays[now()->format('l')];

        // validasi apakah hari sekarang ada di deadline routine
        $days = array_map('trim', explode(',', strtolower($checklist->routine->deadline)));

        if (!in_array($today, $days)) {
            return response()->json([
                'success' => false,
                'message' => 'Upload hanya bisa dilakukan pada hari yang ditentukan.',
            ], 403);
        }

        if ($checklist->file_path) {
            \Storage::disk('public')->delete($checklist->file_path);
        }

        $file = $request->file('file');

        $path = $file->store(
            'checklist-files/' . $today,
            'public'
        );

       $checklist->update([
    'file_path' => $path,
    'file_name' => $file->getClientOriginalName(),
    'file_type' => $file->getMimeType(),

    'is_done' => true,
    'uncheck_reason' => null,
    'day_name' => $today,

    'latitude'  => $request->latitude,
    'longitude' => $request->longitude,
    'address'   => $request->address,
]);

        return response()->json([
            'success' => true,
            'message' => 'File berhasil diupload.',
        ]);
    }

    // ─── Checklist: delete file ────────────────────────────

    public function checklistDeleteFile(int $checklistId): JsonResponse
    {
        $checklist = DailyRoutineChecklist::findOrFail($checklistId);

        if ($checklist->file_path) {
            \Storage::disk('public')->delete($checklist->file_path);
        }

        $checklist->update([
    'file_path' => null,
    'file_name' => null,
    'file_type' => null,
    'is_done' => false,

    'latitude' => null,
    'longitude' => null,
    'address' => null,
]);

        return response()->json([
            'success' => true,
            'message' => 'File berhasil dihapus.',
        ]);
    }
    public function history(): View
    {
        if (auth()->user()->role === 'staff') {
            $routines = DailyRoutineHistory::with([
                'checklists',
                'user'
            ])
                ->where('user_id', auth()->id())
                ->latest()
                ->get();

            return view('staff.project.history', compact('routines'));
        }

        if (auth()->user()->role === 'supervisor') {
            $routines = DailyRoutineHistory::with([
                'checklists',
                'user'
            ])
                ->whereHas('user', function ($q) {
                    $q->where('supervisor_id', auth()->id());
                })
                ->latest()
                ->get();

            return view('daily-routine.history', compact('routines'));
        }

        $routines = DailyRoutineHistory::with([
            'checklists',
            'user'
        ])
            ->whereHas('user', function ($q) {
                $q->where('divisi', auth()->user()->divisi);
            })
            ->latest()
            ->get();

        return view('daily-routine.history', compact('routines'));
    }
public function archiveExpiredRoutines()
{
    $mapDays = [
        'Sunday' => 'minggu',
        'Monday' => 'senin',
        'Tuesday' => 'selasa',
        'Wednesday' => 'rabu',
        'Thursday' => 'kamis',
        'Friday' => 'jumat',
        'Saturday' => 'sabtu',
    ];

    $today = strtolower($mapDays[now()->format('l')] ?? '');

    if (!$today) {
        return;
    }

    $routines = DailyRoutine::with('checklists')->get();

    foreach ($routines as $routine) {

        if (!$routine->deadline) {
            continue;
        }

        // Ambil semua hari dari deadline routine ini
        $routineDays = collect(explode(',', strtolower($routine->deadline)))
            ->map(fn($d) => trim($d))
            ->toArray();

        // ✅ Proses tiap hari di deadline yang BUKAN hari ini
        foreach ($routineDays as $routineDay) {

            if ($routineDay === $today) {
                continue; // skip hari ini
            }

            // Cek apakah hari ini sudah pernah diarchive untuk routine ini
            $alreadyArchived = DailyRoutineHistory::where('daily_routine_id', $routine->id)
                ->where('archived_day', $routineDay) // ← kolom baru (lihat catatan)
                ->exists();

            if ($alreadyArchived) {
                continue;
            }

            DB::beginTransaction();

            try {

                $history = DailyRoutineHistory::create([
                    'daily_routine_id' => $routine->id,
                    'user_id'          => $routine->user_id,
                    'title'            => $routine->title,
                    'description'      => $routine->description,
                    'deadline'         => $routine->deadline,
                    'status'           => $routine->status,
                    'archived_at'      => now(),
                    'archived_day'     => $routineDay, // ← simpan hari yang diarchive
                ]);

                // ✅ Archive SEMUA checklist untuk hari ini (done maupun tidak)
                $checklistsForDay = $routine->checklists->filter(function ($checklist) use ($routineDay) {
                    // Checklist yang memang untuk hari tersebut
                    // Bisa done (is_done=true, day_name=routineDay)
                    // Atau belum dikerjakan (is_done=false, day_name bisa null/routineDay)
                    return strtolower(trim($checklist->day_name ?? '')) === $routineDay
                        || $checklist->is_done === false; // checklist yang belum pernah dikerjakan
                });

                // Jika tidak ada checklist sama sekali, tetap buat history dengan checklist kosong
                foreach ($routine->checklists as $checklist) {

                    $isDoneForThisDay = $checklist->is_done
                        && strtolower(trim($checklist->day_name ?? '')) === $routineDay;

                  DailyRoutineChecklistHistory::create([
    'daily_routine_history_id' => $history->id,
    'title' => $checklist->title,
    'is_done' => $isDoneForThisDay,

    'file_path' => $isDoneForThisDay ? $checklist->file_path : null,
    'file_name' => $isDoneForThisDay ? $checklist->file_name : null,
    'file_type' => $isDoneForThisDay ? $checklist->file_type : null,

    'latitude'  => $checklist->latitude,
    'longitude' => $checklist->longitude,
    'address'   => $checklist->address,

    'uncheck_reason' => $checklist->uncheck_reason,
    'day_name' => $routineDay,
]);
                }

                // Reset checklist hanya untuk hari yang diarchive
                foreach ($routine->checklists as $checklist) {
                    if (strtolower(trim($checklist->day_name ?? '')) === $routineDay) {
                        $checklist->update([
                            'is_done'        => false,
                            'checked_at'     => null,
                            'file_path'      => null,
                            'file_name'      => null,
                            'file_type'      => null,
                            'uncheck_reason' => null,
                        ]);
                    }
                }

                DB::commit();

            } catch (\Throwable $e) {

                DB::rollBack();

                logger()->error('Archive Daily Routine Error', [
                    'routine_id' => $routine->id,
                    'routine_day' => $routineDay,
                    'message'    => $e->getMessage(),
                ]);
            }
        }

        $routine->update(['status' => 'pending']);
    }
}
}