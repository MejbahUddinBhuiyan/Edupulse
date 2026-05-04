<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        $teacher = User::where('role', 'teacher')->first();
        $course = Course::first();

        if (!$teacher || !$course) {
            return;
        }

        Quiz::updateOrCreate(
            [
                'title' => 'PHP Basics Assessment',
                'course_id' => $course->id,
            ],
            [
                'description' => 'Basic quiz for testing personalization logic.',
                'time_limit' => 30,
                'pass_marks' => 5,
                'status' => 'published',
                'created_by' => $teacher->id,
                'total_marks' => 0,
            ]
        );
    }
}