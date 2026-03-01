<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institution_course_accesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained('institutions')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->boolean('is_enabled')->default(true);
            $table->decimal('adjusted_price', 10, 2)->nullable();
            $table->timestamps();

            $table->unique(['institution_id', 'course_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institution_course_accesses');
    }
};
