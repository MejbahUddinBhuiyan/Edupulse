@extends('layouts.app')

@section('content')
<div class="edu-container py-8">
    <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
            <h1 class="edu-page-title">Student AI Performance Tracker</h1>
            <p class="edu-subtitle mt-1">
                Search and monitor student progress, weak topics, and AI-based recommendations.
            </p>
        </div>

    <form method="GET" action="{{ route('teacher.performance') }}" class="w-full md:w-auto">
        <div class="grid gap-3 md:grid-cols-3">
            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Search student name or email"
                class="edu-input w-full"
            >

            <select name="course_id" class="edu-input w-full">
                <option value="">All Courses</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" {{ (string) $courseId === (string) $course->id ? 'selected' : '' }}>
                        {{ $course->title }}
                    </option>
                @endforeach
            </select>

            <div class="flex gap-3">
                <button type="submit" class="edu-btn">Filter</button>
                <a href="{{ route('teacher.performance') }}" class="edu-btn-secondary">Reset</a>
            </div>
        </div>
    </form>
    </div>

    <div class="edu-card p-6">
        @if($students->isEmpty())
            <p class="text-sm text-gray-600">
                No students found.
            </p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-sky-100">
                    <thead class="bg-sky-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Student</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Email</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Tracked Courses</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Overall Average</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Weak Topics</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Strong Topics</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-sky-50 bg-white">
                        @foreach($students as $student)
                            @php
                                $performances = $student->studentPerformances;
                                $topics = $student->topicPerformances;
                                $overallAverage = $performances->count() ? round($performances->avg('average_score'), 2) : 0;
                                $weakTopicsCount = $topics->where('weakness_flag', true)->count();
                                $strongTopicsCount = $topics->filter(fn($topic) => $topic->success_rate > 80)->count();
                            @endphp

                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                    {{ $student->name }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    {{ $student->email }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    {{ $performances->count() }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    {{ $overallAverage }}%
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    {{ $weakTopicsCount }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    {{ $strongTopicsCount }}
                                </td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('teacher.performance.show', $student) }}" class="edu-btn-secondary">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $students->links() }}
            </div>
        @endif
    </div>
</div>
@endsection