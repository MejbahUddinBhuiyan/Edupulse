@extends('layouts.app')

@section('content')
<div class="edu-container py-8">
    <div class="mb-6">
        <h1 class="edu-page-title">Edit Course</h1>
        <p class="edu-subtitle mt-1">Update existing course information.</p>
    </div>

    <div class="edu-card-soft p-6">
        <form method="POST" action="{{ route('courses.update', $course) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="edu-label">Title</label>
                <input type="text" name="title" class="edu-input" value="{{ old('title', $course->title) }}" required>
                @error('title')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="edu-label">Description</label>
                <textarea name="description" rows="5" class="edu-input">{{ old('description', $course->description) }}</textarea>
                @error('description')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>


            <div>
                <label class="edu-label">Category</label>

                <select name="category_id" class="edu-input">
                    <option value="">Select Category</option>

                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ $course->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>


            <div class="flex gap-3">
                <button type="submit" class="edu-btn">Update Course</button>
                <a href="{{ route('courses.index') }}" class="edu-btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection