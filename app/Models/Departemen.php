<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Departemen extends Model
{
    use HasFactory;

    protected $table = 'departemens';

    protected $fillable = [
        'divisi_id',
        'nama',
        'kode',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke Divisi
     */
    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'divisi_id');
    }

    /**
     * Relasi ke Users
     */
    public function users()
    {
        return $this->hasMany(User::class, 'departemen_id');
    }

    /**
     * Scope hanya departemen aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
