<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    public function studentDashboard()
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'student') {
            abort(403, 'Only students can access analytics.');
        }

        // performance data
        $performances = $user->studentPerformances()
            ->with('course')
            ->get();

        // enrollments
        $enrollments = $user->enrollments()
            ->with('course')
            ->get()
            ->keyBy('course_id');

        // summary
        $totalCourses = $performances->count();
        $avgScore = round((float) $performances->avg('average_score'), 2);
        $totalWeakTopics = $performances->sum('weak_topics_count');
        $totalStrongTopics = $performances->sum('strong_topics_count');
        $totalAttempts = $user->quizAttempts()->count();

        // ✅ NEW: trend data
        $recentAttempts = $user->quizAttempts()
            ->with('quiz.course')
            ->whereIn('status', ['submitted', 'evaluated'])
            ->latest('submitted_at')
            ->take(10)
            ->get();

        return view('analytics.student-dashboard', compact(
            'performances',
            'enrollments',
            'recentAttempts',
            'totalCourses',
            'avgScore',
            'totalWeakTopics',
            'totalStrongTopics',
            'totalAttempts'
        ));
    }

    public function courseAnalytics()
    {
        $user = Auth::user();

        if (!$user || !in_array($user->role, ['admin', 'teacher'])) {
            abort(403, 'Only admin and teacher can access course analytics.');
        }

        $courses = Course::with([
            'studentPerformances',
            'topicPerformances',
            'quizzes',
            'enrollments',
        ])->get();

        $analytics = $courses->map(function ($course) {
            $quizIds = $course->quizzes->pluck('id');

            $enrolledStudents = $course->enrollments->count();

            $totalAttempts = QuizAttempt::whereIn('quiz_id', $quizIds)->count();

            $avgScore = round((float) $course->studentPerformances->avg('average_score'), 2);

            $weakTopics = $course->topicPerformances
                ->where('weakness_flag', true)
                ->count();

            $strongTopics = $course->topicPerformances
                ->filter(fn ($topic) => $topic->success_rate > 80)
                ->count();

            $completedStudents = $course->enrollments
                ->where('is_completed', true)
                ->count();

            $completionRatio = $enrolledStudents > 0
                ? round(($completedStudents / $enrolledStudents) * 100, 2)
                : 0;

            return [
                'course' => $course,
                'active_students' => $enrolledStudents,
                'avg_score' => $avgScore,
                'total_attempts' => $totalAttempts,
                'weak_topics' => $weakTopics,
                'strong_topics' => $strongTopics,
                'completion_ratio' => $completionRatio,
            ];
        });

        return view('analytics.course-analytics', compact('analytics'));
    }
}