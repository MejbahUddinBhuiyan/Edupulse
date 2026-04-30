@extends('layouts.app')

@section('content')
<div class="edu-container py-8">
    <div class="mb-6">
        <h1 class="edu-page-title">My AI Learning Dashboard</h1>
        <p class="edu-subtitle mt-1">
            Track your weak topics, strengths, recommendations, and study plan.
        </p>
    </div>

    @if($performances->isEmpty())
        <div class="edu-card-soft p-6">
            <p class="text-sm text-gray-600">
                No performance data available yet. Complete a quiz to generate personalized insights.
            </p>
        </div>
    @else
        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            @foreach($performances as $performance)
                <div class="edu-card p-5">
                    <p class="text-sm font-medium text-gray-500">Course</p>
                    <h2 class="mt-1 text-lg font-semibold text-gray-900">
                        {{ $performance->course->title ?? 'General Performance' }}
                    </h2>

                    <div class="mt-4 space-y-2 text-sm text-gray-700">
                        <p><span class="font-medium">Average Score:</span> {{ $performance->average_score }}%</p>
                        <p><span class="font-medium">Weak Topics:</span> {{ $performance->weak_topics_count }}</p>
                        <p><span class="font-medium">Strong Topics:</span> {{ $performance->strong_topics_count }}</p>
                        <p><span class="font-medium">Recommended Difficulty:</span>
                            <span class="capitalize">{{ $performance->recommended_difficulty }}</span>
                        </p>
                        <p><span class="font-medium">Study Time:</span> {{ $performance->study_minutes_per_day }} mins/day</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-2">
            <div class="edu-card p-6">
                <h2 class="mb-4 text-xl font-semibold text-gray-900">Weak Topics</h2>

                @if($weakTopics->isEmpty())
                    <p class="text-sm text-gray-500">No weak topics detected. Great work!</p>
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
                    <p class="text-sm text-gray-500">No strong topics identified yet.</p>
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
            <h2 class="mb-4 text-xl font-semibold text-gray-900">Topic Performance Details</h2>

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
                        @foreach($topicPerformances as $topic)
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
        </div>

        <div class="mt-8 edu-card p-6">
            <h2 class="mb-4 text-xl font-semibold text-gray-900">Personalized Recommendations</h2>

            @if($recommendations->isEmpty())
                <p class="text-sm text-gray-500">No recommendations generated yet.</p>
            @else
                <div class="space-y-4">
                    @foreach($recommendations as $recommendation)
                        <div class="rounded-xl border p-4 {{ $recommendation->is_read ? 'opacity-60' : 'bg-sky-50 border-sky-100' }}">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h3 class="text-base font-semibold text-gray-900">
                                        {{ $recommendation->title }}
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-600">
                                        {{ $recommendation->message }}
                                    </p>

                                    <div class="mt-3 flex flex-wrap items-center gap-2">
                                        <span class="inline-flex rounded-full bg-white px-3 py-1 text-xs font-medium text-sky-700 border border-sky-200">
                                            Type: {{ str_replace('_', ' ', $recommendation->type) }}
                                        </span>

                                        @if($recommendation->recommended_difficulty)
                                            <span class="inline-flex rounded-full bg-white px-3 py-1 text-xs font-medium text-amber-700 border border-amber-200">
                                                Difficulty: {{ $recommendation->recommended_difficulty }}
                                            </span>
                                        @endif

                                        @if($recommendation->priority)
                                            <span class="inline-flex rounded-full bg-white px-3 py-1 text-xs font-medium text-red-700 border border-red-200">
                                                Priority: {{ $recommendation->priority }}
                                            </span>
                                        @endif
                                    @if(!$recommendation->is_read)
                                        <form method="POST" action="{{ route('recommendations.read', $recommendation) }}">
                                            @csrf
                                            <button type="submit" class="edu-btn-secondary text-xs px-3 py-1">
                                                Mark as Read
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-green-600 font-medium">
                                            ✓ Read
                                        </span>
                                    @endif
                                    </div>
                                </div>

                                <div class="text-xs text-gray-500 whitespace-nowrap">
                                    {{ $recommendation->created_at->format('d M Y') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif
</div>
@endsection