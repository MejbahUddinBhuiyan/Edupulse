@extends('layouts.app')

@section('content')
<div class="edu-container py-8">
    <div class="mb-6 flex items-start justify-between gap-4">
        <div>
            <h1 class="edu-page-title">Student AI Details</h1>
            <p class="edu-subtitle mt-1">
                Detailed AI-generated learning insights for this student.
            </p>
        </div>

        <a href="{{ route('teacher.performance') }}" class="edu-btn-secondary">
            Back to Student List
        </a>
    </div>

    <div class="edu-card p-6">
        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">{{ $student->name }}</h2>
                <p class="text-sm text-gray-500">{{ $student->email }}</p>
            </div>

            <div class="flex flex-wrap gap-2">
                <span class="inline-flex rounded-full bg-sky-100 px-3 py-1 text-sm font-medium text-sky-700">
                    {{ $student->studentPerformances->count() }} course summaries
                </span>
                <span class="inline-flex rounded-full bg-indigo-100 px-3 py-1 text-sm font-medium text-indigo-700">
                    {{ $student->topicPerformances->count() }} topics tracked
                </span>
                <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-sm font-medium text-amber-700">
                    {{ $student->recommendations->count() }} recommendations
                </span>
            </div>
        </div>
    </div>

    <div class="mt-8 grid gap-6 md:grid-cols-2">
        <div class="edu-card p-6">
            <h2 class="mb-4 text-xl font-semibold text-gray-900">Weak Topics</h2>

            @if($weakTopics->isEmpty())
                <p class="text-sm text-gray-500">No weak topics found.</p>
            @else
                <div class="flex flex-wrap gap-2">
                    @foreach($weakTopics as $topic)
                        <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-sm font-medium text-red-700">
                            {{ $topic->topic }} ({{ $topic->success_rate }}%)
                        </span>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="edu-card p-6">
            <h2 class="mb-4 text-xl font-semibold text-gray-900">Strong Topics</h2>

            @if($strongTopics->isEmpty())
                <p class="text-sm text-gray-500">No strong topics found.</p>
            @else
                <div class="flex flex-wrap gap-2">
                    @foreach($strongTopics as $topic)
                        <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-700">
                            {{ $topic->topic }} ({{ $topic->success_rate }}%)
                        </span>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="mt-8 edu-card p-6">
        <h2 class="mb-4 text-xl font-semibold text-gray-900">Course Performance Summary</h2>

        @if($student->studentPerformances->isEmpty())
            <p class="text-sm text-gray-500">No course performance summaries available.</p>
        @else
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @foreach($student->studentPerformances as $performance)
                    <div class="rounded-2xl border border-sky-100 bg-white p-5 shadow-sm">
                        <p class="text-sm font-medium text-gray-500">Course</p>
                        <h3 class="mt-1 text-lg font-semibold text-gray-900">
                            {{ $performance->course->title ?? 'N/A' }}
                        </h3>

                        <div class="mt-4 space-y-2 text-sm text-gray-700">
                            <p><span class="font-medium">Average Score:</span> {{ $performance->average_score }}%</p>
                            <p><span class="font-medium">Weak Topics:</span> {{ $performance->weak_topics_count }}</p>
                            <p><span class="font-medium">Strong Topics:</span> {{ $performance->strong_topics_count }}</p>
                            <p><span class="font-medium">Recommended Difficulty:</span>
                                <span class="capitalize">{{ $performance->recommended_difficulty }}</span>
                            </p>
                            <p><span class="font-medium">Study Time:</span> {{ $performance->study_minutes_per_day }} mins/day</p>
                            <p><span class="font-medium">Last Analyzed:</span>
                                {{ optional($performance->last_analyzed_at)->format('d M Y h:i A') ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="mt-8 edu-card p-6">
        <h2 class="mb-4 text-xl font-semibold text-gray-900">Topic Performance Breakdown</h2>

        @if($student->topicPerformances->isEmpty())
            <p class="text-sm text-gray-500">No topic performance data available.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-sky-100">
                    <thead class="bg-sky-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Topic</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Course</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Score</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Correct</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Wrong</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Next Difficulty</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-sky-50 bg-white">
                        @foreach($student->topicPerformances as $topic)
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $topic->topic }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $topic->course->title ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $topic->success_rate }}%</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $topic->correct_answers }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $topic->wrong_answers }}</td>
                                <td class="px-4 py-3 text-sm">
                                    @if($topic->weakness_flag)
                                        <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700">
                                            Weak
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                            Strong
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600 capitalize">
                                    {{ $topic->recommended_difficulty }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="mt-8 edu-card p-6">
        <h2 class="mb-4 text-xl font-semibold text-gray-900">AI Recommendations</h2>

        @if($student->recommendations->isEmpty())
            <p class="text-sm text-gray-500">No recommendations generated yet.</p>
        @else
            <div class="space-y-4">
                @foreach($student->recommendations as $recommendation)
                    <div class="rounded-xl border border-sky-100 bg-sky-50 p-4">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-base font-semibold text-gray-900">
                                    {{ $recommendation->title }}
                                </h3>
                                <p class="mt-1 text-sm text-gray-600">
                                    {{ $recommendation->message }}
                                </p>

                                <div class="mt-3 flex flex-wrap gap-2">
                                    <span class="inline-flex rounded-full border border-sky-200 bg-white px-3 py-1 text-xs font-medium text-sky-700">
                                        Type: {{ str_replace('_', ' ', $recommendation->type) }}
                                    </span>

                                    @if($recommendation->recommended_difficulty)
                                        <span class="inline-flex rounded-full border border-amber-200 bg-white px-3 py-1 text-xs font-medium text-amber-700">
                                            Difficulty: {{ $recommendation->recommended_difficulty }}
                                        </span>
                                    @endif

                                    @if($recommendation->priority)
                                        <span class="inline-flex rounded-full border border-red-200 bg-white px-3 py-1 text-xs font-medium text-red-700">
                                            Priority: {{ $recommendation->priority }}
                                        </span>
                                    @endif

                                    @if($recommendation->course)
                                        <span class="inline-flex rounded-full border border-indigo-200 bg-white px-3 py-1 text-xs font-medium text-indigo-700">
                                            Course: {{ $recommendation->course->title }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="whitespace-nowrap text-xs text-gray-500">
                                {{ $recommendation->created_at->format('d M Y') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection