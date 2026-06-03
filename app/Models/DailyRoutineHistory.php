<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyRoutineHistory extends Model
{
    protected $table = 'daily_routine_histories';

    protected $fillable = [
        'daily_routine_id',
        'user_id',
        'title',
        'description',
        'deadline',
        'status',
        'archived_at',
        'archived_day',
    ];

    public function checklists()
    {
        return $this->hasMany(
            DailyRoutineChecklistHistory::class,
            'daily_routine_history_id',
            'id'
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}