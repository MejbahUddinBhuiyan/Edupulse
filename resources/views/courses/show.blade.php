@extends('layouts.app')

@section('content')
<div class="edu-container py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="edu-page-title">{{ $course->title }}</h1>
            <p class="edu-subtitle mt-1">Course details and overview.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('courses.edit', $course) }}" class="edu-btn-secondary">Edit</a>
            <a href="{{ route('courses.index') }}" class="edu-btn">Back</a>
        </div>
    </div>

    <div class="edu-card p-6 space-y-4">
        <div>
            <h2 class="text-sm font-semibold text-gray-700">Title</h2>
            <p class="mt-1 text-gray-900">{{ $course->title }}</p>
        </div>

        <div>
            <h2 class="text-sm font-semibold text-gray-700">Slug</h2>
            <p class="mt-1 text-gray-900">{{ $course->slug }}</p>
        </div>

        <div>
            <h2 class="text-sm font-semibold text-gray-700">Description</h2>
            <p class="mt-1 text-gray-900">{{ $course->description ?: 'No description available.' }}</p>
        </div>
    </div>
</div>
@endsection