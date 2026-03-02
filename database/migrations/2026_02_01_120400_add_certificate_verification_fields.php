<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('certificates', 'verification_code')) {
            Schema::table('certificates', function (Blueprint $table) {
                $table->uuid('verification_code')->nullable()->unique();
            });
        }

        if (! Schema::hasColumn('certificates', 'issued_at')) {
            Schema::table('certificates', function (Blueprint $table) {
                $table->timestamp('issued_at')->nullable();
            });
        }

        if (! Schema::hasColumn('certificates', 'qr_code_path')) {
            Schema::table('certificates', function (Blueprint $table) {
                $table->string('qr_code_path')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('certificates', 'verification_code')) {
            Schema::table('certificates', function (Blueprint $table) {
                $table->dropColumn('verification_code');
            });
        }

        if (Schema::hasColumn('certificates', 'issued_at')) {
            Schema::table('certificates', function (Blueprint $table) {
                $table->dropColumn('issued_at');
            });
        }

        if (Schema::hasColumn('certificates', 'qr_code_path')) {
            Schema::table('certificates', function (Blueprint $table) {
                $table->dropColumn('qr_code_path');
            });
        }
    }
};
