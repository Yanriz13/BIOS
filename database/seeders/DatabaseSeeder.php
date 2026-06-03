<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ==========================
        // SUPER ADMIN
        // ==========================
        User::create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@gmail.com',
            'role' => 'super_admin',
            'divisi' => 'IT',
            'password' => bcrypt('superadmin123')
        ]);
    }
}