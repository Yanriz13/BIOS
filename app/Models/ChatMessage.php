<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $fillable = [
        'task_id',
        'from_user_id',
        'to_user_id',
        'group',
        'reply_to_id',
        'body',
    ];

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function replyTo()
    {
        return $this->belongsTo(self::class, 'reply_to_id');
    }

    public function notifications()
    {
        return $this->hasMany(ChatNotification::class);
    }
}
