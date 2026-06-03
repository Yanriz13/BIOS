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
}