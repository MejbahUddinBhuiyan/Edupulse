@extends('layouts.app')

@section('content')
<div class="edu-container py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="edu-page-title">Quizzes</h1>
            <p class="edu-subtitle mt-1">
                @if(auth()->user()->role === 'student')
                    View available published quizzes.
                @else
                    Manage all quizzes here.
                @endif
            </p>
        </div>

        @auth
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'teacher')
                <a href="{{ route('assessment.quizzes.create') }}" class="edu-btn">
                    + Create Quiz
                </a>
            @endif
        @endauth
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="edu-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-sky-100">
                <thead class="bg-sky-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Title</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Course</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Total Marks</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Pass Marks</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Created By</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-sky-50 bg-white">
                    @forelse($quizzes as $quiz)
                        <tr>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $quiz->title }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $quiz->course->title ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $quiz->total_marks }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $quiz->pass_marks }}</td>
                            <td class="px-6 py-4">
                                @if($quiz->status === 'published')
                                    <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                        Published
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-700">
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $quiz->creator->name ?? 'N/A' }}</td>

                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('assessment.quizzes.show', $quiz) }}"
                                       class="edu-btn-secondary">
                                        View
                                    </a>

                                    @auth
                                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'teacher')
                                            <a href="{{ route('assessment.quizzes.edit', $quiz) }}"
                                               class="edu-btn-secondary">
                                                Edit
                                            </a>

                                            <form action="{{ route('assessment.quizzes.toggle-publish', $quiz) }}" method="POST">
                                                @csrf
                                                @method('PATCH')

                                                <button type="submit"
                                                    class="inline-flex items-center justify-center rounded-lg border px-4 py-2 text-sm font-medium transition duration-200
                                                    {{ $quiz->status === 'published'
                                                        ? 'border-amber-200 bg-white text-amber-700 hover:bg-amber-50'
                                                        : 'border-green-200 bg-white text-green-700 hover:bg-green-50' }}">
                                                    {{ $quiz->status === 'published' ? 'Unpublish' : 'Publish' }}
                                                </button>
                                            </form>

                                            <form action="{{ route('assessment.quizzes.destroy', $quiz) }}" method="POST"
                                                  onsubmit="return confirm('Are you sure you want to delete this quiz?');">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-white px-4 py-2 text-sm font-medium text-red-600 transition duration-200 hover:bg-red-50">
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    @endauth
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500">
                                No quizzes found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-sky-100 px-6 py-4">
            {{ $quizzes->links() }}
        </div>
    </div>
</div>
@endsection