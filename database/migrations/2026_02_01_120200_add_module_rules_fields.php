<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->boolean('is_paid')->default(false);
            $table->decimal('price', 10, 2)->nullable();
            $table->unsignedInteger('duration_minutes')->nullable();
            $table->boolean('counts_in_average')->default(true);
            $table->boolean('required_for_cert')->default(true);
            $table->boolean('required_for_final_eval')->default(false);
            $table->decimal('min_score', 5, 2)->nullable();
            $table->unsignedInteger('max_attempts')->nullable();
            $table->json('content_types')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->dropColumn([
                'is_paid',
                'price',
                'duration_minutes',
                'counts_in_average',
                'required_for_cert',
                'required_for_final_eval',
                'min_score',
                'max_attempts',
                'content_types',
            ]);
        });
    }
};
