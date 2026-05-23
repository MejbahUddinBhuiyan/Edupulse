<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Programming'],
            ['name' => 'Web Development'],
            ['name' => 'Database'],
            ['name' => 'Software Engineering'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                [
                    'name' => $category['name'],
                    'slug' => Str::slug($category['name']),
                ]
            );
        }
    }
}