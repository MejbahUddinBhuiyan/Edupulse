@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-4">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Quiz</h1>
        <p class="text-sm text-gray-500">Update quiz information.</p>
    </div>

    <div class="bg-white shadow rounded-xl p-6">
        <form action="{{ route('assessment.quizzes.update', $quiz) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Course</label>
                <select name="course_id" class="w-full border rounded-lg px-3 py-2">
                    <option value="">Select Course</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}"
                            {{ old('course_id', $quiz->course_id) == $course->id ? 'selected' : '' }}>
                            {{ $course->title }}
                        </option>
                    @endforeach
                </select>
                @error('course_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                <input type="text" name="title" value="{{ old('title', $quiz->title) }}"
                       class="w-full border rounded-lg px-3 py-2">
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="4"
                          class="w-full border rounded-lg px-3 py-2">{{ old('description', $quiz->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Time Limit (minutes)</label>
                    <input type="number" name="time_limit" value="{{ old('time_limit', $quiz->time_limit) }}"
                           class="w-full border rounded-lg px-3 py-2">
                    @error('time_limit')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pass Marks</label>
                    <input type="number" step="0.01" name="pass_marks" value="{{ old('pass_marks', $quiz->pass_marks) }}"
                           class="w-full border rounded-lg px-3 py-2">
                    @error('pass_marks')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full border rounded-lg px-3 py-2">
                    <option value="draft" {{ old('status', $quiz->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status', $quiz->status) === 'published' ? 'selected' : '' }}>Published</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3 mt-6">
                <button type="submit"
                    style="background:#0ea5e9; color:white; padding:10px 22px; border:none; border-radius:10px; font-weight:600; cursor:pointer;">
                    Update Quiz
                </button>

                <a href="{{ route('assessment.quizzes.index') }}"
                   style="display:inline-block; padding:10px 22px; border:1px solid #93c5fd; color:#111827; border-radius:10px; text-decoration:none; font-weight:500; background:white;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection