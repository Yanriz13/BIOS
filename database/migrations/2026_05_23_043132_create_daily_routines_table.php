<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_routines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // assigned member
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('deadline')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'progress', 'done'])->default('pending');
            $table->timestamps();
        });

        Schema::create('daily_routine_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_routine_id')->constrained('daily_routines')->cascadeOnDelete();
            $table->string('title');
            $table->boolean('is_done')->default(false);
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_type')->nullable();
            $table->text('uncheck_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_routine_checklists');
        Schema::dropIfExists('daily_routines');
    }
};