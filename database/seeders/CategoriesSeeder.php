<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'IA & Sante',
            'Cyber-securite',
            'Donnees & Gouvernance',
            'Innovation medicale',
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate([
                'slug' => Str::slug($name),
            ], [
                'name' => $name,
                'description' => 'Categorie ' . $name,
            ]);
        }
    }
}
