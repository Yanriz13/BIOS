<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Divisi;
use App\Models\Departemen;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ==========================
        // DIREKSI & GH (level tertinggi, tidak terikat 1 divisi spesifik)
        // Tetap butuh divisi_id karena kolomnya required, jadi diarahkan ke divisi pertama.
        // ==========================
        $mainDivisi = Divisi::orderBy('id')->first();

        User::firstOrCreate(
            ['email' => 'direksi@gmail.com'],
            [
                'name' => 'Direksi Utama',
                'role' => 'direksi',
                'divisi' => $mainDivisi?->nama,
                'divisi_id' => $mainDivisi?->id,
                'departemen' => null,
                'departemen_id' => null,
                'password' => Hash::make('direksi123'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'gh@gmail.com'],
            [
                'name' => 'General Head',
                'role' => 'gh',
                'divisi' => $mainDivisi?->nama,
                'divisi_id' => $mainDivisi?->id,
                'departemen' => null,
                'departemen_id' => null,
                'password' => Hash::make('gh123'),
            ]
        );

        // ==========================
        // PER DIVISI: div_head
        // PER DEPARTEMEN: dept_head, admin_dept, staff
        // ==========================
        $divisis = Divisi::with('departemens')->get();

        foreach ($divisis as $divisi) {
            $divisiSlug = strtolower(str_replace(' ', '', $divisi->nama));

            // div_head — level divisi, departemen_id null
            User::firstOrCreate(
                ['email' => "divhead.{$divisiSlug}@gmail.com"],
                [
                    'name' => 'Div Head ' . $divisi->nama,
                    'role' => 'div_head',
                    'divisi' => $divisi->nama,
                    'divisi_id' => $divisi->id,
                    'departemen' => null,
                    'departemen_id' => null,
                    'password' => Hash::make('divhead123'),
                ]
            );

            foreach ($divisi->departemens as $departemen) {
                $deptSlug = strtolower(str_replace(' ', '', $departemen->nama));

                // dept_head
                User::firstOrCreate(
                    ['email' => "depthead.{$deptSlug}@gmail.com"],
                    [
                        'name' => 'Dept Head ' . $departemen->nama,
                        'role' => 'dept_head',
                        'divisi' => $divisi->nama,
                        'divisi_id' => $divisi->id,
                        'departemen' => $departemen->nama,
                        'departemen_id' => $departemen->id,
                        'password' => Hash::make('depthead123'),
                    ]
                );

                // admin_dept
                User::firstOrCreate(
                    ['email' => "admin.{$deptSlug}@gmail.com"],
                    [
                        'name' => 'Admin ' . $departemen->nama,
                        'role' => 'admin_dept',
                        'divisi' => $divisi->nama,
                        'divisi_id' => $divisi->id,
                        'departemen' => $departemen->nama,
                        'departemen_id' => $departemen->id,
                        'password' => Hash::make('admindept123'),
                    ]
                );

                // staff (2 akun contoh)
                foreach ([1, 2] as $i) {
                    User::firstOrCreate(
                        ['email' => "staff{$i}.{$deptSlug}@gmail.com"],
                        [
                            'name' => "Staff {$i} " . $departemen->nama,
                            'role' => 'staff',
                            'divisi' => $divisi->nama,
                            'divisi_id' => $divisi->id,
                            'departemen' => $departemen->nama,
                            'departemen_id' => $departemen->id,
                            'password' => Hash::make('staff123'),
                        ]
                    );
                }
            }
        }
    }
}