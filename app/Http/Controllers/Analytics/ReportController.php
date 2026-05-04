<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function exportStudentCsv(): StreamedResponse
    {
        $filename = 'student_analytics_report.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Student Name',
                'Email',
                'Tracked Courses',
                'Average Score (%)',
                'Weak Topics',
                'Strong Topics',
                'Total Attempts',
                'Average Completion (%)',
                'Recommended Difficulty',
                'Study Minutes/Day',
            ]);

            $students = User::with(['studentPerformances', 'quizAttempts', 'enrollments'])
                ->where('role', 'student')
                ->get()
                ->filter(function ($student) {
                    return $student->studentPerformances->isNotEmpty();
                })
                ->sortByDesc(function ($student) {
                    return (float) $student->studentPerformances->avg('average_score');
                });

            foreach ($students as $student) {
                $performances = $student->studentPerformances;

                $averageScore = round((float) $performances->avg('average_score'), 2);
                $trackedCourses = $performances->count();
                $weakTopics = $performances->sum('weak_topics_count');
                $strongTopics = $performances->sum('strong_topics_count');
                $totalAttempts = $student->quizAttempts->count();
                $averageCompletion = round((float) $student->enrollments->avg('completion_percentage'), 2);

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

                fputcsv($handle, [
                    $student->name,
                    $student->email,
                    $trackedCourses,
                    number_format($averageScore, 2),
                    $weakTopics,
                    $strongTopics,
                    $totalAttempts,
                    number_format($averageCompletion, 2),
                    ucfirst($recommendedDifficulty),
                    $studyMinutes,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportCourseCsv(): StreamedResponse
    {
        $filename = 'course_analytics_report.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Course Title',
                'Enrolled Students',
                'Completed Students',
                'Student Names',
                'Average Score (%)',
                'Total Attempts',
                'Weak Topics',
                'Strong Topics',
                'Completion Ratio (%)',
            ]);

            $courses = Course::with([
                'studentPerformances',
                'topicPerformances',
                'quizzes.attempts',
                'enrollments.user',
            ])->get();

            $courseRows = $courses->map(function ($course) {
                $enrolledStudents = $course->enrollments->count();

                $completedStudents = $course->enrollments
                    ->where('is_completed', true)
                    ->count();

                $studentNames = $course->enrollments
                    ->pluck('user.name')
                    ->filter()
                    ->sort()
                    ->implode(', ');

                $avgScore = round((float) $course->studentPerformances->avg('average_score'), 2);

                $totalAttempts = $course->quizzes
                    ->flatMap(function ($quiz) {
                        return $quiz->attempts;
                    })
                    ->count();

                $weakTopics = $course->topicPerformances
                    ->where('weakness_flag', true)
                    ->count();

                $strongTopics = $course->topicPerformances
                    ->filter(fn ($topic) => $topic->success_rate > 80)
                    ->count();

                $completionRatio = $enrolledStudents > 0
                    ? round(($completedStudents / $enrolledStudents) * 100, 2)
                    : 0;

                return [
                    'course' => $course->title,
                    'enrolled_students' => $enrolledStudents,
                    'completed_students' => $completedStudents,
                    'student_names' => $studentNames,
                    'avg_score' => $avgScore,
                    'total_attempts' => $totalAttempts,
                    'weak_topics' => $weakTopics,
                    'strong_topics' => $strongTopics,
                    'completion_ratio' => $completionRatio,
                ];
            })
            ->filter(function ($row) {
                return $row['enrolled_students'] > 0 || $row['total_attempts'] > 0;
            })
            ->sortByDesc('enrolled_students');

            foreach ($courseRows as $row) {
                fputcsv($handle, [
                    $row['course'],
                    $row['enrolled_students'],
                    $row['completed_students'],
                    $row['student_names'],
                    number_format($row['avg_score'], 2),
                    $row['total_attempts'],
                    $row['weak_topics'],
                    $row['strong_topics'],
                    number_format($row['completion_ratio'], 2),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportStudentPdf()
    {
        /** @var \App\Models\User $student */
        $student = Auth::user();

        if (!$student || $student->role !== 'student') {
            abort(403, 'Only students can export their analytics PDF.');
        }

        $performances = $student->studentPerformances()->with('course')->get();

        $enrollments = $student->enrollments()
            ->with('course')
            ->get()
            ->keyBy('course_id');

        $totalCourses = $performances->count();
        $avgScore = round((float) $performances->avg('average_score'), 2);
        $totalWeakTopics = $performances->sum('weak_topics_count');
        $totalStrongTopics = $performances->sum('strong_topics_count');
        $totalAttempts = $student->quizAttempts()->count();
        $averageCompletion = round((float) $student->enrollments()->avg('completion_percentage'), 2);

        $pdf = Pdf::loadView('reports.student-pdf', compact(
            'student',
            'performances',
            'enrollments',
            'totalCourses',
            'avgScore',
            'totalWeakTopics',
            'totalStrongTopics',
            'totalAttempts',
            'averageCompletion'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('student_analytics_report.pdf');
    }

    public function exportCoursePdf()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user || !in_array($user->role, ['admin', 'teacher'])) {
            abort(403, 'Only admin and teacher can export course analytics PDF.');
        }

        $courses = Course::with([
            'studentPerformances',
            'topicPerformances',
            'quizzes.attempts',
            'enrollments.user',
        ])->get();

        $analytics = $courses->map(function ($course) {
            $enrolledStudents = $course->enrollments->count();

            $completedStudents = $course->enrollments
                ->where('is_completed', true)
                ->count();

            $totalAttempts = $course->quizzes
                ->flatMap(function ($quiz) {
                    return $quiz->attempts;
                })
                ->count();

            $avgScore = round((float) $course->studentPerformances->avg('average_score'), 2);

            $weakTopics = $course->topicPerformances
                ->where('weakness_flag', true)
                ->count();

            $strongTopics = $course->topicPerformances
                ->filter(fn ($topic) => $topic->success_rate > 80)
                ->count();

            $completionRatio = $enrolledStudents > 0
                ? round(($completedStudents / $enrolledStudents) * 100, 2)
                : 0;

            return [
                'course' => $course,
                'active_students' => $enrolledStudents,
                'completed_students' => $completedStudents,
                'avg_score' => $avgScore,
                'total_attempts' => $totalAttempts,
                'weak_topics' => $weakTopics,
                'strong_topics' => $strongTopics,
                'completion_ratio' => $completionRatio,
            ];
        });

        $pdf = Pdf::loadView('reports.course-pdf', compact('analytics'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('course_analytics_report.pdf');
    }
}