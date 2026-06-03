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
    Schema::table('task_checklists', function (Blueprint $table) {
        $table->text('uncheck_reason')->nullable()->after('file_type');
    });
}

public function down(): void
{
    Schema::table('task_checklists', function (Blueprint $table) {
        $table->dropColumn('uncheck_reason');
    });
}
};
