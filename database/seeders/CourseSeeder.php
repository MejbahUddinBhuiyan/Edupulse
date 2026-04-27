<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();

        if ($categories->isEmpty()) {
            return;
        }

        $courses = [
            [
                'title' => 'PHP Basics',
                'description' => 'Learn the fundamentals of PHP programming.',
                'difficulty_level' => 1,
            ],
            [
                'title' => 'Laravel Fundamentals',
                'description' => 'Introduction to Laravel framework and MVC.',
                'difficulty_level' => 2,
            ],
            [
                'title' => 'Database Design',
                'description' => 'Learn relational database design and normalization.',
                'difficulty_level' => 1,
            ],
            [
                'title' => 'Advanced Web Development',
                'description' => 'Deep dive into full-stack web development.',
                'difficulty_level' => 3,
            ],
        ];

        foreach ($courses as $index => $courseData) {
            $category = $categories[$index % $categories->count()];

            Course::updateOrCreate(
                ['title' => $courseData['title']],
                [
                    'title' => $courseData['title'],
                    'slug' => Str::slug($courseData['title']),
                    'description' => $courseData['description'],
                    'category_id' => $category->id,
                    'difficulty_level' => $courseData['difficulty_level'],
                    'is_published' => true,
                ]
            );
        }
    }
}