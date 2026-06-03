<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('daily_routine_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('daily_routine_id')->nullable();

            $table->foreignId('user_id')->nullable();

            $table->string('title');

            $table->text('description')->nullable();

            $table->date('deadline')->nullable();

            $table->string('status')->nullable();

            $table->timestamp('archived_at');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_routine_histories');
    }
};
