<?php

namespace App\Http\Controllers\Personalization;

use App\Http\Controllers\Controller;
use App\Models\Recommendation;
use App\Models\StudentPerformance;
use App\Models\TopicPerformance;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'student') {
            abort(403, 'Only students can access this dashboard.');
        }

        $performances = StudentPerformance::with('course')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $topicPerformances = TopicPerformance::with('course')
            ->where('user_id', $user->id)
            ->orderByDesc('success_rate')
            ->get();

        $weakTopics = $topicPerformances->where('weakness_flag', true);
        $strongTopics = $topicPerformances->filter(function ($topicPerformance) {
            return $topicPerformance->success_rate > 80;
        });

        $recommendations = Recommendation::with('course')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('personalization.student-dashboard', compact(
            'performances',
            'topicPerformances',
            'weakTopics',
            'strongTopics',
            'recommendations'
        ));
    }
}