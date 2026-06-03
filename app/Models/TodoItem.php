<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TodoItem extends Model
{
    protected $fillable = [
        'task_id',
        'title',
        'description',
        'assigned_user_id',
        'status',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function assignee()
    {
        return $this->belongsTo(\App\Models\User::class, 'assigned_user_id');
    }
}
