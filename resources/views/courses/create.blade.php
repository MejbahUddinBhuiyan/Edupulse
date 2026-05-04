@extends('layouts.app')

@section('content')
<div class="edu-container py-8">
    <div class="mb-6">
        <h1 class="edu-page-title">Create Course</h1>
        <p class="edu-subtitle mt-1">Add a new course to the learning platform.</p>
    </div>

    <div class="edu-card-soft p-6">
        <form method="POST" action="{{ route('courses.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="edu-label">Title</label>
                <input type="text" name="title" class="edu-input" value="{{ old('title') }}" required>
                @error('title')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="edu-label">Description</label>
                <textarea name="description" rows="5" class="edu-input">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>


            <div>
                <label class="edu-label">Category</label>

                <select name="category_id" class="edu-input">
                     <option value="">Select Category</option>

                     @foreach($categories as $category)
                         <option value="{{ $category->id }}">
                              {{ $category->name }}
                         </option>
                    @endforeach
                </select>
            </div>
            <div>
    <label class="edu-label">Prerequisite Courses</label>

    <select name="prerequisites[]" multiple class="edu-input">
        @foreach($courses as $course)
            <option value="{{ $course->id }}">
                {{ $course->title }}
            </option>
        @endforeach
    </select>

    <p class="text-xs text-gray-500 mt-1">
        Hold Ctrl (Windows) or Cmd (Mac) to select multiple.
    </p>
</div>

            <div class="flex gap-3">
                <button type="submit" class="edu-btn">Save Course</button>
                <a href="{{ route('courses.index') }}" class="edu-btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection