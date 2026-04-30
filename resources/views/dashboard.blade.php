@extends('layouts.app')

@section('content')
<div class="edu-container py-10">

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">
            Welcome back, {{ auth()->user()->name }} 👋
        </h1>
        <p class="mt-2 text-gray-500">
            Here is your Edupulse overview for today.
        </p>
    </div>

    @if(auth()->user()->role === 'student')
        <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
            <div class="edu-card p-6">
                <p class="text-sm text-gray-500">Enrolled Courses</p>
                <h2 class="mt-2 text-3xl font-bold text-sky-600">{{ $stats['total_courses'] }}</h2>
            </div>

            <div class="edu-card p-6">
                <p class="text-sm text-gray-500">Quiz Attempts</p>
                <h2 class="mt-2 text-3xl font-bold text-emerald-600">{{ $stats['total_attempts'] }}</h2>
            </div>

            <div class="edu-card p-6">
                <p class="text-sm text-gray-500">Avg Completion</p>
                <h2 class="mt-2 text-3xl font-bold text-indigo-600">{{ $stats['avg_completion'] }}%</h2>
            </div>

            <div class="edu-card p-6">
                <p class="text-sm text-gray-500">Avg Score</p>
                <h2 class="mt-2 text-3xl font-bold text-purple-600">{{ $stats['avg_score'] }}%</h2>
            </div>
        </div>

        <div class="mt-8 grid gap-6 sm:grid-cols-3">
            <a href="{{ route('analytics.student') }}" class="edu-card p-6 hover:shadow-lg transition">
                <h2 class="text-xl font-semibold text-sky-600">My Analytics</h2>
                <p class="mt-2 text-sm text-gray-500">View scores, completion, and progress trends.</p>
            </a>

            <a href="{{ route('courses.index') }}" class="edu-card p-6 hover:shadow-lg transition">
                <h2 class="text-xl font-semibold text-indigo-600">Courses</h2>
                <p class="mt-2 text-sm text-gray-500">Explore available learning courses.</p>
            </a>

            <a href="{{ route('assessment.quizzes.index') }}" class="edu-card p-6 hover:shadow-lg transition">
                <h2 class="text-xl font-semibold text-emerald-600">Quizzes</h2>
                <p class="mt-2 text-sm text-gray-500">Attempt quizzes and improve performance.</p>
            </a>
        </div>
    @endif

    @if(in_array(auth()->user()->role, ['teacher', 'admin']))
        <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
            <div class="edu-card p-6">
                <p class="text-sm text-gray-500">Total Courses</p>
                <h2 class="mt-2 text-3xl font-bold text-sky-600">{{ $stats['total_courses'] }}</h2>
            </div>

            <div class="edu-card p-6">
                <p class="text-sm text-gray-500">Total Quizzes</p>
                <h2 class="mt-2 text-3xl font-bold text-indigo-600">{{ $stats['total_quizzes'] }}</h2>
            </div>

            <div class="edu-card p-6">
                <p class="text-sm text-gray-500">Quiz Attempts</p>
                <h2 class="mt-2 text-3xl font-bold text-emerald-600">{{ $stats['total_attempts'] }}</h2>
            </div>

            <div class="edu-card p-6">
                <p class="text-sm text-gray-500">Enrollments</p>
                <h2 class="mt-2 text-3xl font-bold text-purple-600">{{ $stats['total_enrollments'] }}</h2>
            </div>
        </div>

        <div class="mt-8 grid gap-6 sm:grid-cols-3">
            <a href="{{ route('analytics.courses') }}" class="edu-card p-6 hover:shadow-lg transition">
                <h2 class="text-xl font-semibold text-purple-600">Course Analytics</h2>
                <p class="mt-2 text-sm text-gray-500">Monitor enrollment, performance, and completion rates.</p>
            </a>

            <a href="{{ route('teacher.performance') }}" class="edu-card p-6 hover:shadow-lg transition">
                <h2 class="text-xl font-semibold text-sky-600">Student AI Tracker</h2>
                <p class="mt-2 text-sm text-gray-500">Track weak topics and AI recommendations.</p>
            </a>

            <a href="{{ route('assessment.quizzes.index') }}" class="edu-card p-6 hover:shadow-lg transition">
                <h2 class="text-xl font-semibold text-emerald-600">Manage Quizzes</h2>
                <p class="mt-2 text-sm text-gray-500">Create quizzes and review attempts.</p>
            </a>
        </div>
    @endif

    <div class="mt-10 edu-card p-6 text-center">
        <h2 class="text-xl font-semibold text-gray-800">
            Keep building progress 🚀
        </h2>
        <p class="mt-2 text-gray-500">
            Edupulse helps students learn smarter and helps instructors track performance clearly.
        </p>

        
    </div>

</div>

@endsection