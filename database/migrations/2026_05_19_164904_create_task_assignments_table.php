<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_assignments', function (Blueprint $table) {

            $table->id();

            $table->foreignId('task_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            $table->text('description')->nullable();

            $table->date('deadline')->nullable();

            $table->text('notes')->nullable();

            $table->string('file')->nullable();

            $table->enum('status', [
                'pending',
                'progress',
                'done',
                'reject'
            ])->default('pending');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_assignments');
    }
};