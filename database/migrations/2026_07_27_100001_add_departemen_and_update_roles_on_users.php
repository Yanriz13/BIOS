<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'departemen_id')) {
                $table->foreignId('departemen_id')
                      ->nullable()
                      ->after('divisi_id')
                      ->constrained('departemens')
                      ->nullOnDelete();
            }
            if (!Schema::hasColumn('users', 'departemen')) {
                $table->string('departemen')
                      ->nullable()
                      ->after('divisi');
            }
        });

        // 1. Expand enum to include new and old values temporarily
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('super_admin','direksi','gh','div_head','dept_head','admin_dept','staff','manager','supervisor','admin_divisi') NOT NULL DEFAULT 'staff'");

        // 2. Migrate existing user roles
        DB::table('users')->where('role', 'manager')->update(['role' => 'div_head']);
        DB::table('users')->where('role', 'supervisor')->update(['role' => 'dept_head']);
        DB::table('users')->where('role', 'admin_divisi')->update(['role' => 'admin_dept']);

        // 3. Finalize enum with new role values
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('super_admin','direksi','gh','div_head','dept_head','admin_dept','staff') NOT NULL DEFAULT 'staff'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('super_admin','direksi','gh','div_head','dept_head','admin_dept','staff','manager','supervisor','admin_divisi') NOT NULL DEFAULT 'staff'");

        DB::table('users')->where('role', 'div_head')->update(['role' => 'manager']);
        DB::table('users')->where('role', 'dept_head')->update(['role' => 'supervisor']);
        DB::table('users')->where('role', 'admin_dept')->update(['role' => 'admin_divisi']);

        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('super_admin','direksi','manager','admin_divisi','supervisor','staff') NOT NULL DEFAULT 'staff'");

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'departemen_id')) {
                $table->dropForeign(['departemen_id']);
                $table->dropColumn('departemen_id');
            }
            if (Schema::hasColumn('users', 'departemen')) {
                $table->dropColumn('departemen');
            }
        });
    }
};
