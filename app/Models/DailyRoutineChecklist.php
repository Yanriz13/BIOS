<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyRoutineChecklist extends Model
{
protected $fillable = [
    'daily_routine_id',
    'title',
    'is_done',
    'day_name',
    'file_path',
    'file_name',
    'file_type',
    'latitude',
    'longitude',
    'address',
];

    protected $casts = [
        'is_done' => 'boolean',
    ];

    public function dailyRoutine(): BelongsTo
    {
        return $this->belongsTo(DailyRoutine::class);
    }
    public function routine()
{
    return $this->belongsTo(DailyRoutine::class, 'daily_routine_id');
}
}