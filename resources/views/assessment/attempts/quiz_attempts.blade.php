@extends('layouts.app')

@section('content')
<div class="edu-container py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="edu-page-title">Attempts for: {{ $quiz->title }}</h1>
            <p class="edu-subtitle mt-1">
                Course: {{ $quiz->course->title ?? 'N/A' }}
            </p>
        </div>

        <a href="{{ route('assessment.attempts.index') }}" class="edu-btn-secondary">
            Back
        </a>
    </div>

    <div class="edu-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-sky-100">
                <thead class="bg-sky-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Student</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Attempt</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Score</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-sky-50 bg-white">
                    @forelse($attempts as $attempt)
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                {{ $attempt->student->name ?? 'N/A' }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-600">
                                Attempt {{ $attempt->attempt_number }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-600">
                                @if($attempt->status === 'in_progress')
                                    Not submitted
                                @else
                                    {{ $attempt->obtained_marks }} / {{ $attempt->total_marks }}
                                @endif
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-600">
                                @if($attempt->status === 'in_progress')
                                    <span class="inline-flex rounded-full bg-sky-100 px-3 py-1 text-xs font-medium text-sky-700">
                                        In Progress
                                    </span>
                                @elseif($attempt->status === 'submitted')
                                    <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-700">
                                        Pending Review
                                    </span>
                                @elseif($attempt->status === 'evaluated')
                                    <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                        Evaluated
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700">
                                        {{ ucfirst(str_replace('_', ' ', $attempt->status)) }}
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('assessment.attempts.show', $attempt) }}"
                                       class="edu-btn-secondary">
                                        View
                                    </a>

                                    @if($attempt->status === 'submitted')
                                        <a href="{{ route('assessment.attempts.review', $attempt) }}"
                                           class="inline-flex items-center justify-center rounded-lg border border-amber-200 bg-white px-4 py-2 text-sm font-medium text-amber-700 transition duration-200 hover:bg-amber-50">
                                            Review
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">
                                No attempts found for this quiz.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-sky-100 px-6 py-4">
            {{ $attempts->links() }}
        </div>
    </div>
</div>
@endsection