<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->boolean('show_on_home_carousel')->default(false)->after('presentation_image');
            $table->unsignedInteger('home_carousel_order')->nullable()->after('show_on_home_carousel');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['show_on_home_carousel', 'home_carousel_order']);
        });
    }
};
