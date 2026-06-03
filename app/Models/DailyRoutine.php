<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DailyRoutine extends Model
{
    protected $fillable = [
        'task_id',
        'user_id',
        'created_by',
        'title',
        'description',
        'deadline',
        'notes',
        'status',
    ];

    protected $casts = [
        // 'deadline' => 'date',
    ];

    // ─── Relations ────────────────────────────────────────

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function checklists(): HasMany
    {
        return $this->hasMany(DailyRoutineChecklist::class);
    }

    // ─── Scopes ───────────────────────────────────────────

    public function scopeForTask($query, int $taskId)
    {
        return $query->where('task_id', $taskId);
    }

    public function scopeAssigned($query)
    {
        return $query->whereNotNull('user_id');
    }

    public function scopeUnassigned($query)
    {
        return $query->whereNull('user_id');
    }

    // ─── Helpers ──────────────────────────────────────────

    public function getCompletionPercentageAttribute(): int
    {
        $total = $this->checklists->count();
        if ($total === 0) return 0;
        $done = $this->checklists->where('is_done', true)->count();
        return (int) round(($done / $total) * 100);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'progress' => 'bg-blue-100 text-blue-700 border-blue-200',
            'done'     => 'bg-green-100 text-green-700 border-green-200',
            default    => 'bg-yellow-100 text-yellow-700 border-yellow-200',
        };
    }
}