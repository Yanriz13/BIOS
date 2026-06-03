<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('daily_routine_histories', function (Blueprint $table) {
            if (!Schema::hasColumn('daily_routine_histories', 'archived_day')) {
                $table->string('archived_day')->nullable()->after('archived_at');
            }
        });

        Schema::table('daily_routine_checklist_histories', function (Blueprint $table) {
            if (!Schema::hasColumn('daily_routine_checklist_histories', 'latitude')) {
                $table->decimal('latitude', 10, 7)->nullable()->after('file_type');
            }

            if (!Schema::hasColumn('daily_routine_checklist_histories', 'longitude')) {
                $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            }

            if (!Schema::hasColumn('daily_routine_checklist_histories', 'address')) {
                $table->text('address')->nullable()->after('longitude');
            }
        });
    }

    public function down(): void
    {
        Schema::table('daily_routine_histories', function (Blueprint $table) {
            if (Schema::hasColumn('daily_routine_histories', 'archived_day')) {
                $table->dropColumn('archived_day');
            }
        });

        Schema::table('daily_routine_checklist_histories', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('daily_routine_checklist_histories', 'latitude')) {
                $columns[] = 'latitude';
            }
            if (Schema::hasColumn('daily_routine_checklist_histories', 'longitude')) {
                $columns[] = 'longitude';
            }
            if (Schema::hasColumn('daily_routine_checklist_histories', 'address')) {
                $columns[] = 'address';
            }
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
