<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('short_description')->nullable();
            $table->text('detailed_description')->nullable();
            $table->string('target_audience')->nullable();
            $table->string('level')->nullable();
            $table->string('language')->nullable();
            $table->unsignedInteger('total_duration_minutes')->nullable();
            $table->boolean('certification_enabled')->default(true);
            $table->decimal('min_average', 5, 2)->default(75);
            $table->string('final_evaluation_mode')->default('optional');
            $table->boolean('manual_validation')->default(false);
            $table->string('payment_mode')->default('module');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
            $table->dropColumn([
                'short_description',
                'detailed_description',
                'target_audience',
                'level',
                'language',
                'total_duration_minutes',
                'certification_enabled',
                'min_average',
                'final_evaluation_mode',
                'manual_validation',
                'payment_mode',
            ]);
        });
    }
};
