<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 // php artisan make:migration add_location_to_task_checklists_table
public function up()
{
    Schema::table('task_checklists', function (Blueprint $table) {
        $table->decimal('latitude', 10, 7)->nullable()->after('file_type');
        $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
        $table->string('address')->nullable()->after('longitude');
    });
}

public function down()
{
    Schema::table('task_checklists', function (Blueprint $table) {
        $table->dropColumn(['latitude', 'longitude', 'address']);
    });
}
};
