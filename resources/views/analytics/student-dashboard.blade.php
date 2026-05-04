@extends('layouts.app')

@section('content')
<div class="edu-container py-8">
    <div class="mb-6">
        <h1 class="edu-page-title">Student Analytics Dashboard</h1>
        <p class="edu-subtitle mt-1">
            View your learning progress, performance summary, and course analytics.
        </p>
        <a href="/reports/students/pdf" class="inline-block bg-sky-600 text-white px-4 py-2 rounded-md hover:bg-sky-700">Download PDF</a>
        <a href="/reports/students/csv" class="inline-block bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700">Download CSV</a>
    </div>

    <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
        <div class="edu-card p-6">
            <p class="text-sm font-medium text-gray-500">Total Courses</p>
            <h2 class="mt-2 text-3xl font-bold text-sky-600">{{ $totalCourses }}</h2>
        </div>

        <div class="edu-card p-6">
            <p class="text-sm font-medium text-gray-500">Average Score</p>
            <h2 class="mt-2 text-3xl font-bold text-indigo-600">{{ $avgScore }}%</h2>
        </div>

        <div class="edu-card p-6">
            <p class="text-sm font-medium text-gray-500">Weak Topics</p>
            <h2 class="mt-2 text-3xl font-bold text-red-500">{{ $totalWeakTopics }}</h2>
        </div>

        <div class="edu-card p-6">
            <p class="text-sm font-medium text-gray-500">Total Attempts</p>
            <h2 class="mt-2 text-3xl font-bold text-emerald-600">{{ $totalAttempts }}</h2>
        </div>
    </div>

    <div class="mt-8 edu-card p-6">
        <div class="mb-4 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Course Performance</h2>
                <p class="mt-1 text-sm text-gray-500">
                    Performance summary for each course you have engaged with.
                </p>
            </div>
        </div>

        @if($performances->isEmpty())
            <div class="rounded-xl border border-sky-100 bg-sky-50 px-4 py-5 text-sm text-gray-600">
                No analytics data available yet.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-sky-100">
                    <thead class="bg-sky-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Course</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Average Score</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Weak Topics</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Strong Topics</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Difficulty</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Completion</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-sky-50 bg-white">
                        @foreach ($performances as $performance)
                            @php
                                $completion = $enrollments[$performance->course_id]->completion_percentage ?? 0;

                                // color based on completion
                                $barColor = $completion == 100 ? 'bg-green-500' : ($completion >= 50 ? 'bg-sky-500' : 'bg-red-500');
                            @endphp

                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                    {{ $performance->course->title ?? 'N/A' }}
                                </td>

                                <td class="px-4 py-3 text-sm text-gray-600">
                                    {{ $performance->average_score }}%
                                </td>

                                <td class="px-4 py-3 text-sm">
                                    <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700">
                                        {{ $performance->weak_topics_count }}
                                    </span>
                                </td>

                                <td class="px-4 py-3 text-sm">
                                    <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                        {{ $performance->strong_topics_count }}
                                    </span>
                                </td>

                                <td class="px-4 py-3 text-sm">
                                    @php
                                        $difficultyClasses = match(strtolower($performance->recommended_difficulty)) {
                                            'easy' => 'bg-amber-100 text-amber-700',
                                            'medium' => 'bg-sky-100 text-sky-700',
                                            'hard' => 'bg-purple-100 text-purple-700',
                                            default => 'bg-gray-100 text-gray-700',
                                        };
                                    @endphp

                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $difficultyClasses }}">
                                        {{ ucfirst($performance->recommended_difficulty) }}
                                    </span>
                                </td>

                                <!-- ✅ NEW COMPLETION COLUMN -->
                                <td class="px-4 py-3 text-sm">
                                    <div class="w-32">
                                        <div class="mb-1 text-xs font-medium text-gray-600">
                                            {{ $completion }}%
                                        </div>

                                        <div class="h-2 rounded-full bg-gray-200">
                                            <div class="h-2 rounded-full {{ $barColor }}" style="width: {{ $completion }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <!-- ✅ END -->

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- ✅ NEW PROGRESS TREND SECTION -->
    <div class="mt-8 edu-card p-6">
        <div class="mb-4">
            <h2 class="text-xl font-semibold text-gray-900">Progress Trend</h2>
            <p class="mt-1 text-sm text-gray-500">
                Your latest quiz attempt scores over time.
            </p>
        </div>

        @if($recentAttempts->isEmpty())
            <div class="rounded-xl border border-sky-100 bg-sky-50 px-4 py-5 text-sm text-gray-600">
                No quiz attempt trend available yet.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-sky-100">
                    <thead class="bg-sky-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Date</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Course</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Quiz</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Score</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-sky-50 bg-white">
                        @foreach($recentAttempts as $attempt)
                            @php
                                $scorePercentage = $attempt->total_marks > 0
                                    ? round(($attempt->obtained_marks / $attempt->total_marks) * 100, 2)
                                    : 0;

                                $scoreColor = $scorePercentage >= 80
                                    ? 'text-green-600'
                                    : ($scorePercentage >= 50 ? 'text-sky-600' : 'text-red-600');
                            @endphp

                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    {{ optional($attempt->submitted_at)->format('d M Y') ?? 'N/A' }}
                                </td>

                                <td class="px-4 py-3 text-sm text-gray-600">
                                    {{ $attempt->quiz->course->title ?? 'N/A' }}
                                </td>

                                <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                    {{ $attempt->quiz->title ?? 'N/A' }}
                                </td>

                                <td class="px-4 py-3 text-sm font-semibold {{ $scoreColor }}">
                                    {{ $scorePercentage }}%
                                </td>

                                <td class="px-4 py-3 text-sm">
                                    <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700">
                                        {{ ucfirst($attempt->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    <!-- ✅ END PROGRESS TREND SECTION -->
</div>
@endsection