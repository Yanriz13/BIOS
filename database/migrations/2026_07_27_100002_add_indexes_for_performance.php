<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->index('role');
            $table->index('divisi');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->index('divisi');
            $table->index('status');
        });

        Schema::table('task_assignments', function (Blueprint $table) {
            $table->index(['task_id', 'user_id']);
            $table->index('status');
        });

        Schema::table('task_checklists', function (Blueprint $table) {
            $table->index(['task_assignment_id', 'is_done']);
        });

        Schema::table('daily_routines', function (Blueprint $table) {
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['divisi']);
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex(['divisi']);
            $table->dropIndex(['status']);
        });

        Schema::table('task_assignments', function (Blueprint $table) {
            $table->dropIndex(['task_id', 'user_id']);
            $table->dropIndex(['status']);
        });

        Schema::table('task_checklists', function (Blueprint $table) {
            $table->dropIndex(['task_assignment_id', 'is_done']);
        });

        Schema::table('daily_routines', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'status']);
        });
    }
};
