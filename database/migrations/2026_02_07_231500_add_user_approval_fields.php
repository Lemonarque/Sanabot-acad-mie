<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('approval_status')->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('phone')->nullable();
            $table->string('organization')->nullable();
            $table->string('position')->nullable();
            $table->text('motivation')->nullable();
        });

        DB::table('users')->whereNull('approval_status')->update(['approval_status' => 'approved']);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('approved_by');
            $table->dropColumn([
                'approval_status',
                'approved_at',
                'phone',
                'organization',
                'position',
                'motivation',
            ]);
        });
    }
};
