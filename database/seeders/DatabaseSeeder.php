<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
public function run(): void
{
    $this->call([
        AdminUserSeeder::class,
        TeacherUserSeeder::class,
        StudentUserSeeder::class,
        CategorySeeder::class,
        CourseSeeder::class,
        QuizSeeder::class,
        QuestionSeeder::class,
    ]);
}
}