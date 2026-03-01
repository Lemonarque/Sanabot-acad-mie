<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->date('date_of_birth')->nullable()->after('motivation');
            $table->string('gender', 20)->nullable()->after('date_of_birth');
            $table->string('city')->nullable()->after('gender');
            $table->string('country')->nullable()->after('city');
            $table->string('address')->nullable()->after('country');
            $table->unsignedTinyInteger('experience_years')->nullable()->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'date_of_birth',
                'gender',
                'city',
                'country',
                'address',
                'experience_years',
            ]);
        });
    }
};
