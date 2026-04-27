<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Quiz;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $quiz = Quiz::where('title', 'PHP Basics Assessment')->first();

        if (!$quiz) {
            return;
        }

        $questions = [
            [
                'question_text' => 'Which symbol is used to declare a variable in PHP?',
                'question_type' => 'mcq',
                'topic' => 'Variables',
                'difficulty' => 'easy',
                'marks' => 2,
                'options' => [
                    ['text' => '$', 'is_correct' => true],
                    ['text' => '#', 'is_correct' => false],
                    ['text' => '@', 'is_correct' => false],
                    ['text' => '&', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Which of the following is a valid PHP loop?',
                'question_type' => 'mcq',
                'topic' => 'Loops',
                'difficulty' => 'easy',
                'marks' => 2,
                'options' => [
                    ['text' => 'foreach', 'is_correct' => true],
                    ['text' => 'repeat', 'is_correct' => false],
                    ['text' => 'loopfor', 'is_correct' => false],
                    ['text' => 'iterate', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Which function is used to count array elements in PHP?',
                'question_type' => 'mcq',
                'topic' => 'Arrays',
                'difficulty' => 'medium',
                'marks' => 2,
                'options' => [
                    ['text' => 'size()', 'is_correct' => false],
                    ['text' => 'count()', 'is_correct' => true],
                    ['text' => 'length()', 'is_correct' => false],
                    ['text' => 'total()', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Which keyword is used to define a function in PHP?',
                'question_type' => 'mcq',
                'topic' => 'Functions',
                'difficulty' => 'easy',
                'marks' => 2,
                'options' => [
                    ['text' => 'method', 'is_correct' => false],
                    ['text' => 'function', 'is_correct' => true],
                    ['text' => 'def', 'is_correct' => false],
                    ['text' => 'func', 'is_correct' => false],
                ],
            ],
        ];

        foreach ($questions as $questionData) {
            $question = Question::updateOrCreate(
                [
                    'quiz_id' => $quiz->id,
                    'question_text' => $questionData['question_text'],
                ],
                [
                    'question_type' => $questionData['question_type'],
                    'topic' => $questionData['topic'],
                    'difficulty' => $questionData['difficulty'],
                    'marks' => $questionData['marks'],
                    'correct_answer' => null,
                ]
            );

            $question->options()->delete();

            foreach ($questionData['options'] as $option) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => $option['text'],
                    'is_correct' => $option['is_correct'],
                ]);
            }
        }

        $quiz->update([
            'total_marks' => $quiz->questions()->sum('marks'),
        ]);
    }
}