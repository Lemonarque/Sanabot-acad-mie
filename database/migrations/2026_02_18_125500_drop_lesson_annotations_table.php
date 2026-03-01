<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('lesson_annotations');
    }

    public function down(): void
    {
        Schema::create('lesson_annotations', function ($table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->text('selected_text');
            $table->text('note_text')->nullable();
            $table->string('color', 20)->default('yellow');
            $table->timestamps();

            $table->index(['user_id', 'lesson_id']);
        });
    }
};
