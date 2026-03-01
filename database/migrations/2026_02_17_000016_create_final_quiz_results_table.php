<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('final_quiz_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained('enrollments')->cascadeOnDelete();
            $table->foreignId('quiz_id')->constrained('quizzes')->cascadeOnDelete();
            $table->unsignedInteger('score_percent')->default(0);
            $table->boolean('passed')->default(false);
            $table->timestamps();

            $table->unique(['enrollment_id', 'quiz_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('final_quiz_results');
    }
};
