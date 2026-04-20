@extends('layouts.app')

@section('content')
<div class="edu-container py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="edu-page-title">My Quiz Attempts</h1>
            <p class="edu-subtitle mt-1">View your quiz history and results.</p>
        </div>
    </div>

    <div class="edu-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-sky-100">
                <thead class="bg-sky-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Quiz</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Course</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Attempt</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Score</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold text-gray-700">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-sky-50 bg-white">
                    @forelse($attempts as $attempt)
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $attempt->quiz->title ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $attempt->quiz->course->title ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">Attempt {{ $attempt->attempt_number }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">@if($attempt->status === 'in_progress')
    Not submitted
@else
    {{ $attempt->obtained_marks }} / {{ $attempt->total_marks }}
@endif{{ $attempt->obtained_marks }} / {{ $attempt->total_marks }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <span class="inline-flex rounded-full bg-sky-100 px-3 py-1 text-xs font-medium text-sky-700">
                                    @if($attempt->status === 'in_progress')
    In Progress
@elseif($attempt->status === 'submitted')
    Pending Review
@elseif($attempt->status === 'evaluated')
    Evaluated
@else
    {{ ucfirst(str_replace('_', ' ', $attempt->status)) }}
@endif{{ ucfirst(str_replace('_', ' ', $attempt->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end">
                                    <a href="{{ route('assessment.attempts.show', $attempt) }}" class="edu-btn-secondary">
                                        View
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">
                                No attempts found.
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