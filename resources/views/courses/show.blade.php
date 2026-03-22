@extends('layouts.app')

@section('content')
<div class="edu-container py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="edu-page-title">{{ $course->title }}</h1>
            <p class="edu-subtitle mt-1">Course details and overview.</p>
        </div>
        <div class="flex gap-3">
@auth
    @if(auth()->user()->isAdminOrTeacher())
        <a href="{{ route('courses.edit', $course) }}" class="edu-btn-secondary">Edit</a>
    @endif
@endauth            <a href="{{ route('courses.index') }}" class="edu-btn">Back</a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2 space-y-6">
            <div class="edu-card p-6 space-y-4">
                <div>
                    <h2 class="text-sm font-semibold text-gray-700">Title</h2>
                    <p class="mt-1 text-gray-900">{{ $course->title }}</p>
                </div>

                <div>
                    <h2 class="text-sm font-semibold text-gray-700">Category</h2>
                    <p class="mt-1 text-gray-900">{{ $course->category->name ?? 'No Category' }}</p>
                </div>

                <div>
                    <h2 class="text-sm font-semibold text-gray-700">Slug</h2>
                    <p class="mt-1 text-gray-900">{{ $course->slug }}</p>
                </div>

                <div>
                    <h2 class="text-sm font-semibold text-gray-700">Average Rating</h2>
                    <p class="mt-1 text-gray-900">{{ $course->rating }}/5</p>
                </div>

                <div>
                    <h2 class="text-sm font-semibold text-gray-700">Description</h2>
                    <p class="mt-1 text-gray-900">{{ $course->description ?: 'No description available.' }}</p>
                </div>

                <div>
                    <h2 class="text-sm font-semibold text-gray-700">Prerequisites</h2>

                    @if($course->prerequisites->count())
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach($course->prerequisites as $pre)
                                <span class="inline-flex rounded-full bg-sky-100 px-3 py-1 text-xs font-medium text-sky-700">
                                    {{ $pre->title }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="mt-1 text-gray-500">No prerequisites.</p>
                    @endif
                </div>
            </div>

            <div class="edu-card p-6">
                <h2 class="mb-4 text-xl font-semibold text-gray-900">Student Reviews</h2>

                @forelse($course->reviews as $review)
                    <div class="border-b border-sky-100 py-4 last:border-b-0">
                        <div class="flex items-center justify-between">
                            <h3 class="font-medium text-gray-900">{{ $review->user->name }}</h3>
                            <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-medium text-sky-700">
                                {{ $review->rating }}/5
                            </span>
                        </div>

                        <p class="mt-2 text-sm text-gray-600">
                            {{ $review->review ?: 'No written review provided.' }}
                        </p>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No reviews yet.</p>
                @endforelse
            </div>
        </div>

        <div>
            <div class="edu-card-soft p-6">
                <h2 class="mb-4 text-xl font-semibold text-gray-900">Leave a Review</h2>
@auth
    @if(auth()->user()->isStudent())
        <form method="POST" action="{{ route('courses.reviews.store', $course) }}" class="space-y-5">
            @csrf

            <div>
                <label class="edu-label">Rating</label>
                <select name="rating" class="edu-input" required>
                    <option value="">Select Rating</option>
                    <option value="1" {{ old('rating') == 1 ? 'selected' : '' }}>1 - Poor</option>
                    <option value="2" {{ old('rating') == 2 ? 'selected' : '' }}>2 - Fair</option>
                    <option value="3" {{ old('rating') == 3 ? 'selected' : '' }}>3 - Good</option>
                    <option value="4" {{ old('rating') == 4 ? 'selected' : '' }}>4 - Very Good</option>
                    <option value="5" {{ old('rating') == 5 ? 'selected' : '' }}>5 - Excellent</option>
                </select>
                @error('rating')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="edu-label">Review</label>
                <textarea name="review" rows="5" class="edu-input">{{ old('review') }}</textarea>
                @error('review')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="edu-btn w-full">Submit Review</button>
        </form>
    @else
        <p class="text-sm text-gray-600">Only students can submit reviews.</p>
    @endif
@else
    <p class="text-sm text-gray-600">
        Please <a href="{{ route('login') }}" class="font-medium text-sky-600 hover:text-sky-700">log in</a> to leave a review.
    </p>
@endauth


            </div>
        </div>
    </div>
</div>
@endsection