<?php

namespace App\Services;

use App\Models\QuizAttempt;
use App\Models\Recommendation;
use App\Models\StudentPerformance;
use App\Models\TopicPerformance;

class PersonalizationService
{
    public function analyzeAttempt(QuizAttempt $attempt): void
    {
        $attempt->loadMissing([
            'quiz.course',
            'answers.question',
        ]);

        $topicStats = [];

        foreach ($attempt->answers as $answer) {
            if (!$answer->question) {
                continue;
            }

            $question = $answer->question;
            $topic = $question->topic ?? 'General';
            $questionMarks = (float) $question->marks;
            $awardedMarks = (float) ($answer->marks_awarded ?? 0);

            if (!isset($topicStats[$topic])) {
                $topicStats[$topic] = [
                    'total_marks' => 0,
                    'obtained_marks' => 0,
                    'correct_answers' => 0,
                    'wrong_answers' => 0,
                    'question_count' => 0,
                ];
            }

            $topicStats[$topic]['total_marks'] += $questionMarks;
            $topicStats[$topic]['obtained_marks'] += $awardedMarks;
            $topicStats[$topic]['question_count']++;

            if ($awardedMarks >= $questionMarks && $questionMarks > 0) {
                $topicStats[$topic]['correct_answers']++;
            } else {
                $topicStats[$topic]['wrong_answers']++;
            }
        }

        foreach ($topicStats as $topic => $data) {
            $totalMarks = $data['total_marks'];
            $obtainedMarks = $data['obtained_marks'];

            $successRate = $totalMarks > 0
                ? round(($obtainedMarks / $totalMarks) * 100, 2)
                : 0;

            $isWeak = $successRate < 50;

            if ($successRate > 80) {
                $recommendedDifficulty = 'hard';
            } elseif ($successRate >= 50) {
                $recommendedDifficulty = 'medium';
            } else {
                $recommendedDifficulty = 'easy';
            }

            TopicPerformance::updateOrCreate(
                [
                    'user_id' => $attempt->user_id,
                    'course_id' => $attempt->quiz->course_id,
                    'topic' => $topic,
                ],
                [
                    'average_score' => $successRate,
                    'attempt_count' => $data['question_count'],
                    'correct_answers' => $data['correct_answers'],
                    'wrong_answers' => $data['wrong_answers'],
                    'success_rate' => $successRate,
                    'weakness_flag' => $isWeak,
                    'recommended_difficulty' => $recommendedDifficulty,
                    'last_attempt_id' => $attempt->id,
                ]
            );

            $this->createTopicRecommendation(
                attempt: $attempt,
                topic: $topic,
                successRate: $successRate,
                recommendedDifficulty: $recommendedDifficulty,
                isWeak: $isWeak
            );
        }

        $this->updateStudentPerformance($attempt);
    }

    protected function updateStudentPerformance(QuizAttempt $attempt): void
    {
        $topicPerformances = TopicPerformance::where('user_id', $attempt->user_id)
            ->where('course_id', $attempt->quiz->course_id)
            ->get();

        $averageScore = round((float) $topicPerformances->avg('success_rate'), 2);
        $weakTopicsCount = $topicPerformances->where('weakness_flag', true)->count();
        $strongTopicsCount = $topicPerformances->filter(function ($topicPerformance) {
            return $topicPerformance->success_rate > 80;
        })->count();

        if ($averageScore > 80) {
            $recommendedDifficulty = 'hard';
            $studyMinutes = 15;
        } elseif ($averageScore >= 50) {
            $recommendedDifficulty = 'medium';
            $studyMinutes = 30;
        } else {
            $recommendedDifficulty = 'easy';
            $studyMinutes = 45;
        }

        StudentPerformance::updateOrCreate(
            [
                'user_id' => $attempt->user_id,
                'course_id' => $attempt->quiz->course_id,
            ],
            [
                'average_score' => $averageScore,
                'total_quizzes_attempted' => QuizAttempt::where('user_id', $attempt->user_id)
                    ->whereHas('quiz', function ($query) use ($attempt) {
                        $query->where('course_id', $attempt->quiz->course_id);
                    })
                    ->where('status', 'evaluated')
                    ->count(),
                'total_topics_analyzed' => $topicPerformances->count(),
                'weak_topics_count' => $weakTopicsCount,
                'strong_topics_count' => $strongTopicsCount,
                'recommended_difficulty' => $recommendedDifficulty,
                'study_minutes_per_day' => $studyMinutes,
                'last_analyzed_at' => now(),
            ]
        );

        $this->createStudyPlanRecommendation(
            attempt: $attempt,
            averageScore: $averageScore,
            studyMinutes: $studyMinutes,
            recommendedDifficulty: $recommendedDifficulty
        );
    }

    protected function createTopicRecommendation(
        QuizAttempt $attempt,
        string $topic,
        float $successRate,
        string $recommendedDifficulty,
        bool $isWeak
    ): void {
        $title = $isWeak
            ? "Weak topic detected: {$topic}"
            : "Progress update for {$topic}";

        if ($isWeak) {
            $message = "Your performance in {$topic} is {$successRate}%. Review easier content and practice more questions on this topic.";
            $type = 'weak_topic';
            $priority = 'high';
        } elseif ($successRate > 80) {
            $message = "Great job in {$topic}. Your performance is {$successRate}%. You are ready for more difficult content.";
            $type = 'difficulty_upgrade';
            $priority = 'medium';
        } else {
            $message = "Your performance in {$topic} is {$successRate}%. Continue practicing to strengthen this topic.";
            $type = 'content_recommendation';
            $priority = 'medium';
        }

        Recommendation::updateOrCreate(
            [
                'user_id' => $attempt->user_id,
                'course_id' => $attempt->quiz->course_id,
                'topic' => $topic,
                'type' => $type,
            ],
            [
                'title' => $title,
                'message' => $message,
                'recommended_difficulty' => $recommendedDifficulty,
                'content_type' => 'quiz',
                'content_id' => $attempt->quiz_id,
                'priority' => $priority,
                'is_read' => false,
                'generated_from' => 'topic_rule',
            ]
        );
    }

    protected function createStudyPlanRecommendation(
        QuizAttempt $attempt,
        float $averageScore,
        int $studyMinutes,
        string $recommendedDifficulty
    ): void {
        Recommendation::updateOrCreate(
            [
                'user_id' => $attempt->user_id,
                'course_id' => $attempt->quiz->course_id,
                'topic' => null,
                'type' => 'study_plan',
            ],
            [
                'title' => 'Personalized study plan',
                'message' => "Your current average performance is {$averageScore}%. We recommend studying {$studyMinutes} minutes per day and focusing on {$recommendedDifficulty}-level practice.",
                'recommended_difficulty' => $recommendedDifficulty,
                'content_type' => 'course',
                'content_id' => $attempt->quiz->course_id,
                'priority' => 'medium',
                'is_read' => false,
                'generated_from' => 'study_time_rule',
            ]
        );
    }
}