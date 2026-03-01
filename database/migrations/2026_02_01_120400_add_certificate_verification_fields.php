<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->uuid('verification_code')->nullable()->unique();
            $table->timestamp('issued_at')->nullable();
            $table->string('qr_code_path')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropColumn(['verification_code', 'issued_at', 'qr_code_path']);
        });
    }
};
