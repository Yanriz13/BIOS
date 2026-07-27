<?php

namespace Database\Seeders;

use App\Models\Divisi;
use Illuminate\Database\Seeder;

class DivisiSeeder extends Seeder
{
    public function run(): void
    {
        $divisis = [
            ['nama' => 'Sales', 'kode' => 'SLS', 'deskripsi' => 'Divisi Sales & Marketing'],
            ['nama' => 'Operasional', 'kode' => 'OPS', 'deskripsi' => 'Divisi Operasional'],
            ['nama' => 'Keuangan', 'kode' => 'KEU', 'deskripsi' => 'Divisi Keuangan & Akuntansi'],
        ];

        foreach ($divisis as $d) {
            Divisi::firstOrCreate(
                ['nama' => $d['nama']],
                [
                    'kode' => $d['kode'],
                    'deskripsi' => $d['deskripsi'],
                    'is_active' => true,
                ]
            );
        }
    }
}