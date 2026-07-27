<?php

namespace Database\Seeders;

use App\Models\Divisi;
use App\Models\Departemen;
use Illuminate\Database\Seeder;

class DepartemenSeeder extends Seeder
{
    public function run(): void
    {
        $map = [
            'Sales' => [
                ['nama' => 'Sales Regional', 'kode' => 'SLS-REG'],
                ['nama' => 'Sales Nasional', 'kode' => 'SLS-NAS'],
            ],
            'Operasional' => [
                ['nama' => 'Produksi', 'kode' => 'OPS-PRD'],
                ['nama' => 'Distribusi', 'kode' => 'OPS-DIS'],
            ],
            'Keuangan' => [
                ['nama' => 'Akuntansi', 'kode' => 'KEU-AKT'],
                ['nama' => 'Treasury', 'kode' => 'KEU-TRE'],
            ],
        ];

        foreach ($map as $divisiNama => $departemens) {
            $divisi = Divisi::where('nama', $divisiNama)->first();

            if (!$divisi) {
                continue;
            }

            foreach ($departemens as $dept) {
                Departemen::firstOrCreate(
                    ['divisi_id' => $divisi->id, 'nama' => $dept['nama']],
                    [
                        'kode' => $dept['kode'],
                        'deskripsi' => $dept['nama'] . ' - ' . $divisi->nama,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}