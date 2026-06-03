<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('daily_routine_checklists', function (Blueprint $table) {

            if (!Schema::hasColumn('daily_routine_checklists', 'latitude')) {
                $table->decimal('latitude', 10, 7)->nullable()->after('file_type');
            }

            if (!Schema::hasColumn('daily_routine_checklists', 'longitude')) {
                $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            }

            if (!Schema::hasColumn('daily_routine_checklists', 'address')) {
                $table->text('address')->nullable()->after('longitude');
            }
        });
    }

    public function down(): void
    {
        Schema::table('daily_routine_checklists', function (Blueprint $table) {

            $columns = [];

            if (Schema::hasColumn('daily_routine_checklists', 'latitude')) {
                $columns[] = 'latitude';
            }

            if (Schema::hasColumn('daily_routine_checklists', 'longitude')) {
                $columns[] = 'longitude';
            }

            if (Schema::hasColumn('daily_routine_checklists', 'address')) {
                $columns[] = 'address';
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};