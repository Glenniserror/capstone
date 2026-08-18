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
        Schema::create('student_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->integer('module_number')->comment('1, 2, or 3');
            $table->boolean('module_completed')->default(false);
            $table->integer('quiz_score')->nullable();
            $table->timestamp('module_completed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'module_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_progress');
    }
};
