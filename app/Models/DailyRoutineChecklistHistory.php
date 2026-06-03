<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyRoutineChecklistHistory extends Model
{
    protected $fillable = [
        'daily_routine_history_id',
        'title',
        'is_done',
        'file_path',
        'file_name',
        'file_type',
        'uncheck_reason',
        'day_name',
        'latitude',
        'longitude',
        'address',
    ];

    protected $table = 'daily_routine_checklist_histories';

    public function history()
    {
        return $this->belongsTo(
            DailyRoutineHistory::class,
            'daily_routine_history_id'
        );
    }
}