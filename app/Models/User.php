<?php

namespace App\Models;


use App\Models\Divisi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'divisi',
          'divisi_id',
        'supervisor_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function tasks()
    {
        return $this->belongsToMany(Task::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function subordinates()
    {
        return $this->hasMany(User::class, 'supervisor_id');
    }

    public function messagesSent()
    {
        return $this->hasMany(ChatMessage::class, 'from_user_id');
    }

    public function messagesReceived()
    {
        return $this->hasMany(ChatMessage::class, 'to_user_id');
    }

    public function chatNotifications()
    {
        return $this->hasMany(ChatNotification::class);
    }
    public function divisiRelasi()
    {
        return $this->belongsTo(Divisi::class, 'divisi_id');
    }
}
