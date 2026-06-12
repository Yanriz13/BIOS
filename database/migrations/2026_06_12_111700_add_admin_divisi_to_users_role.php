<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('super_admin','direksi','manager','admin_divisi','supervisor','staff') NOT NULL DEFAULT 'staff'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert enum (may fail if admin_divisi values still exist)
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('super_admin','direksi','manager','supervisor','staff') NOT NULL DEFAULT 'staff'");
    }
};
