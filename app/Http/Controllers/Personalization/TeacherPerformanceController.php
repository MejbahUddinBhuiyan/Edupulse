<?php

namespace App\Http\Controllers\Personalization;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherPerformanceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user || !in_array($user->role, ['admin', 'teacher'])) {
            abort(403, 'Only admin and teacher can access this page.');
        }

        $search = $request->input('search');
        $courseId = $request->input('course_id');

        $courses = Course::orderBy('title')->get();

        $students = User::query()
            ->where('role', 'student')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->with([
                'studentPerformances' => function ($query) use ($courseId) {
                    if ($courseId) {
                        $query->where('course_id', $courseId);
                    }
                },
                'studentPerformances.course',
                'topicPerformances' => function ($query) use ($courseId) {
                    if ($courseId) {
                        $query->where('course_id', $courseId);
                    }
                },
            ])
            ->whereHas('studentPerformances', function ($query) use ($courseId) {
                if ($courseId) {
                    $query->where('course_id', $courseId);
                }
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('personalization.teacher-performance', compact(
            'students',
            'search',
            'courses',
            'courseId'
        ));
    }

    public function show(User $student)
    {
        $user = Auth::user();

        if (!$user || !in_array($user->role, ['admin', 'teacher'])) {
            abort(403, 'Only admin and teacher can access this page.');
        }

        if ($student->role !== 'student') {
            abort(404, 'Student not found.');
        }

        $student->load([
            'studentPerformances.course',
            'topicPerformances.course',
            'recommendations.course',
        ]);

        $weakTopics = $student->topicPerformances->where('weakness_flag', true);
        $strongTopics = $student->topicPerformances->filter(function ($topicPerformance) {
            return $topicPerformance->success_rate > 80;
        });

        return view('personalization.teacher-student-show', compact(
            'student',
            'weakTopics',
            'strongTopics'
        ));
    }
}