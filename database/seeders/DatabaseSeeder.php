<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ==========================
        // SUPER ADMIN
        // ==========================
        User::firstOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'name' => 'Super Administrator',
                'role' => 'super_admin',
                'divisi' => 'IT',
                'password' => bcrypt('superadmin123'),
            ]
        );

        $this->call([
            DivisiSeeder::class,
            DepartemenSeeder::class,
            UserSeeder::class,
        ]);
    }
}