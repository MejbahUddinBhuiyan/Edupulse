@extends('layouts.app')

@section('content')
<div class="edu-container py-8">

    <div class="mb-6">
        <h1 class="edu-page-title">Course Analytics</h1>
        <p class="edu-subtitle mt-1">
            Monitor performance of all students across courses.
        </p>
        <a href="/reports/courses/pdf" class="inline-block bg-sky-600 text-white px-4 py-2 rounded-md hover:bg-sky-700">Download PDF</a>
        <a href="/reports/courses/csv" class="inline-block bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700">Download CSV</a>
    </div>

    <div class="edu-card p-6">
        <div class="mb-4">
            <h2 class="text-xl font-semibold text-gray-900">
                Course Performance Overview
            </h2>
        </div>

        @if($analytics->isEmpty())
            <div class="rounded-xl border border-sky-100 bg-sky-50 px-4 py-5 text-sm text-gray-600">
                No course analytics data available.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-sky-100">
                    <thead class="bg-sky-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Course</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Enrolled Students</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Avg Score</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Attempts</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Weak Topics</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Strong Topics</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Completion</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-sky-50 bg-white">
                        @foreach ($analytics as $item)
                            <tr>
                                <td class="px-4 py-3 font-medium text-gray-900">
                                    {{ $item['course']->title }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $item['active_students'] }}
                                </td>

                                <td class="px-4 py-3 text-indigo-600 font-semibold">
                                    {{ $item['avg_score'] }}%
                                </td>

                                <td class="px-4 py-3">
                                    {{ $item['total_attempts'] }}
                                </td>

                                <td class="px-4 py-3">
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs">
                                        {{ $item['weak_topics'] }}
                                    </span>
                                </td>

                                <td class="px-4 py-3">
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs">
                                        {{ $item['strong_topics'] }}
                                    </span>
                                </td>
<td class="px-4 py-3">
    <div class="w-36">
        <div class="mb-1 text-xs font-medium text-gray-600">
            {{ $item['completion_ratio'] }}%
        </div>
        <div class="h-2 rounded-full bg-gray-200">
            <div class="h-2 rounded-full bg-green-500" style="width: {{ $item['completion_ratio'] }}%"></div>
        </div>
    </div>
</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection