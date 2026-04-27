@extends('layouts.app')

@section('content')
<div class="edu-container py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="edu-page-title">Review Attempt</h1>
            <p class="edu-subtitle mt-1">
                Student: {{ $attempt->student->name ?? 'N/A' }} | Quiz: {{ $attempt->quiz->title }}
            </p>
        </div>

        <a href="{{ route('assessment.attempts.show', $attempt) }}" class="edu-btn-secondary">
            Back
        </a>
    </div>

    <form action="{{ route('assessment.attempts.grade', $attempt) }}" method="POST" class="space-y-5">
        @csrf

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
                                Max: {{ $answer->question->marks }}
                            </span>
                        </div>
                    </div>
                </div>

                @if($answer->question->question_type === 'mcq')
                    <div class="text-sm text-gray-700">
                        <p><span class="font-medium">Selected Option:</span> {{ $answer->selectedOption->option_text ?? 'Not answered' }}</p>
                        <p class="mt-1"><span class="font-medium">Auto Marks:</span> {{ $answer->marks_awarded }}</p>
                    </div>
                @else
                    <div class="mb-4">
                        <p class="mb-2 text-sm font-medium text-gray-700">Student Answer</p>
                        <div class="rounded-lg border border-sky-100 bg-sky-50 px-4 py-3 whitespace-pre-line text-sm text-gray-700">
                            {{ $answer->answer_text ?: 'No answer submitted.' }}
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Marks Awarded</label>
                        <input type="number"
                               step="0.5"
                               name="answers[{{ $answer->id }}][marks_awarded]"
                               value="{{ old('answers.' . $answer->id . '.marks_awarded', $answer->marks_awarded) }}"
                               class="w-full rounded-lg border px-3 py-2">
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Teacher Feedback</label>
                        <input type="text"
                               name="answers[{{ $answer->id }}][teacher_feedback]"
                               value="{{ old('answers.' . $answer->id . '.teacher_feedback', $answer->teacher_feedback) }}"
                               class="w-full rounded-lg border px-3 py-2">
                    </div>
                </div>
            </div>
        @endforeach

        <div class="edu-card p-5">
            <label class="mb-1 block text-sm font-medium text-gray-700">Override Note</label>
            <textarea name="override_note" rows="3" class="w-full rounded-lg border px-3 py-2">{{ old('override_note', $attempt->override_note) }}</textarea>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="edu-btn">Save Grading</button>
            <a href="{{ route('assessment.attempts.show', $attempt) }}" class="edu-btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection