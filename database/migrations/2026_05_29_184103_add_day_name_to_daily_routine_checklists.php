<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('daily_routine_checklists', function (Blueprint $table) {
            if (!Schema::hasColumn('daily_routine_checklists', 'day_name')) {
                $table->string('day_name')->nullable()->after('title');
            }

            if (!Schema::hasColumn('daily_routine_checklists', 'checked_at')) {
                $table->timestamp('checked_at')->nullable()->after('day_name');
            }

            if (!Schema::hasColumn('daily_routine_checklists', 'uncheck_reason')) {
                $table->text('uncheck_reason')->nullable()->after('file_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('daily_routine_checklists', function (Blueprint $table) {
            $columns = [];

            if (Schema::hasColumn('daily_routine_checklists', 'day_name')) {
                $columns[] = 'day_name';
            }

            if (Schema::hasColumn('daily_routine_checklists', 'checked_at')) {
                $columns[] = 'checked_at';
            }

            if (Schema::hasColumn('daily_routine_checklists', 'uncheck_reason')) {
                $columns[] = 'uncheck_reason';
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
