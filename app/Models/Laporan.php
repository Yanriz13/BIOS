<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $fillable = [
        'divisi',
        'title',
        'description',
        'status',
        'created_by',
        'updated_by',
    ];
}
