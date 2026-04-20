@extends('layouts.app')

@section('content')
<div class="edu-container py-8">
    <div class="mb-6">
        <h1 class="edu-page-title">All Quiz Attempts</h1>
        <p class="edu-subtitle mt-1">View attempts grouped by quiz.</p>
    </div>

    <div class="edu-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-sky-100">
                <thead class="bg-sky-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Quiz</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Course</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Created By</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Total Attempts</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-sky-50 bg-white">
                    @forelse($quizzes as $quiz)
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                {{ $quiz->title }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $quiz->course->title ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $quiz->creator->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $quiz->attempts_count }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end">
                                    <a href="{{ route('assessment.quizzes.attempts', $quiz) }}"
                                       class="edu-btn-secondary">
                                        View Attempts
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">
                                No quiz attempts available.
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