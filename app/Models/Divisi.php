<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Divisi extends Model
{
    use HasFactory;

    protected $table = 'divisis';

    protected $fillable = [
        'nama',
        'kode',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke users
     */
    public function users()
    {
        return $this->hasMany(User::class, 'divisi_id');
    }

    /**
     * Scope hanya divisi aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}