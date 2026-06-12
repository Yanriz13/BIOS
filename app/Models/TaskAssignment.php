<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskAssignment extends Model
{
    protected $fillable = [

        'task_id',
        'user_id',
        'description',
        'deadline',
        'notes',
        'file',
        'status'

    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

   public function checklists()
    {
        return $this->hasMany(
            TaskChecklist::class,
            'task_assignment_id'
        );
    }

    public function getResolvedStatusAttribute(): string
    {
        $rawStatus = $this->getRawOriginal('status');

        if ($rawStatus === 'reject') {
            return 'reject';
        }

        $checklistsLoaded = $this->relationLoaded('checklists');
        $totalChecklist = $checklistsLoaded
            ? $this->checklists->count()
            : $this->checklists()->count();

        if ($totalChecklist > 0) {
            $doneChecklist = $checklistsLoaded
                ? $this->checklists->where('is_done', 1)->count()
                : $this->checklists()->where('is_done', 1)->count();

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
    }

    public function getStatusAttribute($value): string
    {
        // Keep status presentation consistent across all pages by deriving
        // it from checklist progress whenever checklist data exists.
        return $this->resolved_status;
    }
}