<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndUsersSeeder::class,
            CategoriesSeeder::class,
            SampleCourseSeeder::class,
            RematchCourseSeeder::class,
            MeaningfulTextCoursesSeeder::class,
            HealthTextCoursesSeeder::class,
        ]);
    }
}
