<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [

        'title',
        'description',
        'priority',
        'status',
        'divisi',

    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function todoItems()
    {
        return $this->hasMany(\App\Models\TodoItem::class);
    }
}