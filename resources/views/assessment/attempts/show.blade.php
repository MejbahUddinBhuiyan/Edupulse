@extends('layouts.app')

@section('content')
<div class="edu-container py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="edu-page-title">Attempt Details</h1>
            <p class="edu-subtitle mt-1">
                Quiz: {{ $attempt->quiz->title }} | Course: {{ $attempt->quiz->course->title ?? 'N/A' }}
            </p>
        </div>

        @if(auth()->user()->role === 'student')
            <a href="{{ route('assessment.attempts.my') }}" class="edu-btn-secondary">My Attempts</a>
        @else
            <a href="{{ route('assessment.attempts.index') }}" class="edu-btn-secondary">All Attempts</a>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="edu-card p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <p class="text-sm text-gray-500">Student</p>
                <p class="font-semibold text-gray-800">{{ $attempt->student->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Attempt No.</p>
                <p class="font-semibold text-gray-800">{{ $attempt->attempt_number }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Marks</p>
                <p class="font-semibold text-gray-800">{{ $attempt->total_marks }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Obtained Marks</p>
                <p class="font-semibold text-gray-800">{{ $attempt->obtained_marks }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Status</p>
                <p class="font-semibold text-gray-800">@if($attempt->status === 'in_progress')
    In Progress
@elseif($attempt->status === 'submitted')
    Pending Review
@elseif($attempt->status === 'evaluated')
    Evaluated
@else
    {{ ucfirst(str_replace('_', ' ', $attempt->status)) }}
@endif</p>
            </div>
        </div>

        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <p class="text-sm text-gray-500">Started At</p>
                <p class="text-gray-700">{{ $attempt->started_at ? $attempt->started_at->format('d M Y, h:i A') : 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Submitted At</p>
                <p class="text-gray-700">{{ $attempt->submitted_at ? $attempt->submitted_at->format('d M Y, h:i A') : 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Graded By</p>
                <p class="text-gray-700">{{ $attempt->grader->name ?? 'Auto / Pending' }}</p>
            </div>
        </div>

        @if($attempt->override_note)
            <div class="mt-4 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                <span class="font-medium">Override Note:</span> {{ $attempt->override_note }}
            </div>
        @endif
    </div>

    <div class="space-y-4">
        @foreach($attempt->answers as $answer)
            <div class="edu-card p-5">
                <div class="mb-3 flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-base font-semibold text-gray-800">
                            {{ $loop->iteration }}. {{ $answer->question->question_text }}
                        </h3>
                        <div class="mt-2 flex flex-wrap gap-2">
                            <span class="inline-flex rounded-full bg-sky-100 px-3 py-1 text-xs font-medium text-sky-700">
                                {{ strtoupper($answer->question->question_type) }}
                            </span>
                            <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700">
                                {{ $answer->question->marks }} marks
                            </span>
                        </div>
                    </div>

                    <div class="text-right">
                        <p class="text-sm text-gray-500">Awarded</p>
                        <p class="font-semibold text-gray-800">{{ $answer->marks_awarded }}</p>
                    </div>
                </div>

                @if($answer->question->question_type === 'mcq')
                    <div class="text-sm text-gray-700">
                        <p>
                            <span class="font-medium">Selected Option:</span>
                            {{ $answer->selectedOption->option_text ?? 'Not answered' }}
                        </p>
                        <p class="mt-1">
                            <span class="font-medium">Result:</span>
                            @if($answer->is_correct === true)
                                <span class="text-green-600 font-medium">Correct</span>
                            @elseif($answer->is_correct === false)
                                <span class="text-red-600 font-medium">Incorrect</span>
                            @else
                                <span class="text-gray-500">Pending</span>
                            @endif
                        </p>

                        @if($answer->is_correct === false)
                            @php
                                $correctOption = $answer->question->options->firstWhere('is_correct', true);
                            @endphp

                            <p class="mt-1 text-sm text-gray-600">
                                <span class="font-medium">Correct Answer:</span>
                                <span class="text-green-700 font-medium">
                                    {{ $correctOption->option_text ?? 'Not available' }}
                                </span>
                            </p>
                        @endif
                    </div>
                @else
                    <div class="text-sm text-gray-700">
                        <p class="font-medium">Submitted Answer:</p>
                        <div class="mt-2 rounded-lg border border-sky-100 bg-sky-50 px-4 py-3 whitespace-pre-line">
                            {{ $answer->answer_text ?: 'No answer submitted.' }}
                        </div>
                    </div>
                @endif

                @if($answer->teacher_feedback)
                    <div class="mt-4 rounded-lg border border-purple-200 bg-purple-50 px-4 py-3 text-sm text-purple-800">
                        <span class="font-medium">Teacher Feedback:</span> {{ $answer->teacher_feedback }}
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    @if(in_array(auth()->user()->role, ['admin', 'teacher']) && $attempt->status !== 'evaluated')
        <div class="mt-6">
            <a href="{{ route('assessment.attempts.review', $attempt) }}" class="edu-btn">
                Review & Grade
            </a>
        </div>
    @endif
</div>
@endsection