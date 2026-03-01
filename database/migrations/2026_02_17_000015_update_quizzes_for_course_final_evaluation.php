<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('quizzes', 'course_id')) {
            return;
        }

        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys=OFF');

            Schema::create('quizzes_new', function (Blueprint $table) {
                $table->id();
                $table->foreignId('module_id')->nullable()->constrained('modules')->nullOnDelete();
                $table->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();
                $table->string('title');
                $table->unsignedInteger('min_score')->default(0);
                $table->unsignedInteger('max_attempts')->default(1);
                $table->timestamps();
            });

            $rows = DB::table('quizzes as q')
                ->leftJoin('modules as m', 'm.id', '=', 'q.module_id')
                ->select(
                    'q.id',
                    'q.module_id',
                    DB::raw('m.course_id as course_id'),
                    'q.title',
                    'q.min_score',
                    'q.max_attempts',
                    'q.created_at',
                    'q.updated_at'
                )
                ->get();

            foreach ($rows as $row) {
                DB::table('quizzes_new')->insert([
                    'id' => $row->id,
                    'module_id' => $row->module_id,
                    'course_id' => $row->course_id,
                    'title' => $row->title,
                    'min_score' => $row->min_score,
                    'max_attempts' => $row->max_attempts,
                    'created_at' => $row->created_at,
                    'updated_at' => $row->updated_at,
                ]);
            }

            Schema::drop('quizzes');
            Schema::rename('quizzes_new', 'quizzes');

            DB::statement('PRAGMA foreign_keys=ON');

            return;
        }

        Schema::table('quizzes', function (Blueprint $table) {
            $table->foreignId('course_id')->nullable()->after('module_id')->constrained('courses')->nullOnDelete();
        });

        DB::table('quizzes as q')
            ->join('modules as m', 'm.id', '=', 'q.module_id')
            ->update([
                'q.course_id' => DB::raw('m.course_id'),
            ]);
    }

    public function down(): void
    {
        if (! Schema::hasColumn('quizzes', 'course_id')) {
            return;
        }

        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys=OFF');

            Schema::create('quizzes_old', function (Blueprint $table) {
                $table->id();
                $table->foreignId('module_id')->constrained('modules')->cascadeOnDelete();
                $table->string('title');
                $table->unsignedInteger('min_score')->default(0);
                $table->unsignedInteger('max_attempts')->default(1);
                $table->timestamps();
            });

            $rows = DB::table('quizzes')
                ->whereNotNull('module_id')
                ->select('id', 'module_id', 'title', 'min_score', 'max_attempts', 'created_at', 'updated_at')
                ->get();

            foreach ($rows as $row) {
                DB::table('quizzes_old')->insert([
                    'id' => $row->id,
                    'module_id' => $row->module_id,
                    'title' => $row->title,
                    'min_score' => $row->min_score,
                    'max_attempts' => $row->max_attempts,
                    'created_at' => $row->created_at,
                    'updated_at' => $row->updated_at,
                ]);
            }

            Schema::drop('quizzes');
            Schema::rename('quizzes_old', 'quizzes');

            DB::statement('PRAGMA foreign_keys=ON');

            return;
        }

        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('course_id');
        });
    }
};
