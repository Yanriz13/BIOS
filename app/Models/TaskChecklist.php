<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskChecklist extends Model
{
    protected $fillable = [
        'task_assignment_id',
        'title',
        'assigned_user_id',
        'is_done',
        'file_path',
        'file_name',
        'file_type',
        'uncheck_reason',
        'latitude',   // ← tambah
        'longitude',  // ← tambah
        'address',
    ];

    public function assignment()
    {
        return $this->belongsTo(TaskAssignment::class, 'task_assignment_id');
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }
}