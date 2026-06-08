<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'supervisor' to the ENUM definition for users.role
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('super_admin','direksi','manager','supervisor','staff') NOT NULL DEFAULT 'staff'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to previous enum (may fail if supervisor values exist)
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('super_admin','direksi','manager','staff') NOT NULL DEFAULT 'staff'");
    }
};
