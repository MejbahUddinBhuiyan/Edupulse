@extends('layouts.app')

@section('content')
<div class="edu-container py-8">
    <div class="mb-6">
        <h1 class="edu-page-title">Attempt Quiz</h1>
        <p class="edu-subtitle mt-1">
            Quiz: {{ $attempt->quiz->title }} | Course: {{ $attempt->quiz->course->title ?? 'N/A' }}
        </p>
    </div>

    @if(session('error'))
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="edu-card p-6">
        <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <p class="text-sm text-gray-500">Attempt No.</p>
                <p class="font-semibold text-gray-800">{{ $attempt->attempt_number }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Marks</p>
                <p class="font-semibold text-gray-800">{{ $attempt->total_marks }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Time Limit</p>
                <p class="font-semibold text-gray-800">
                    {{ $attempt->quiz->time_limit ? $attempt->quiz->time_limit . ' min' : 'Not set' }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Status</p>
                <p class="font-semibold text-sky-700">{{ ucfirst(str_replace('_', ' ', $attempt->status)) }}</p>
            </div>
        </div>

        <form action="{{ route('assessment.attempts.submit', $attempt) }}" method="POST" class="space-y-6">
            @csrf

            @foreach($attempt->quiz->questions as $question)
                <div class="rounded-xl border border-sky-100 p-5 bg-white">
                    <div class="mb-3 flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-base font-semibold text-gray-800">
                                {{ $loop->iteration }}. {{ $question->question_text }}
                            </h3>
                            <div class="mt-2 flex flex-wrap gap-2">
                                <span class="inline-flex rounded-full bg-sky-100 px-3 py-1 text-xs font-medium text-sky-700">
                                    {{ strtoupper($question->question_type) }}
                                </span>
                                <span class="inline-flex rounded-full bg-purple-100 px-3 py-1 text-xs font-medium text-purple-700">
                                    {{ ucfirst($question->difficulty) }}
                                </span>
                                <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700">
                                    Topic: {{ $question->topic }}
                                </span>
                            </div>
                        </div>

                        <div class="text-sm font-medium text-gray-600">
                            {{ $question->marks }} marks
                        </div>
                    </div>

                    @if($question->question_type === 'mcq')
                        <div class="space-y-3">
                            @foreach($question->options as $option)
                                <label class="flex items-center gap-3 rounded-lg border border-sky-100 px-4 py-3 hover:bg-sky-50 cursor-pointer">
                                    <input type="radio"
                                           name="answers[{{ $question->id }}]"
                                           value="{{ $option->id }}"
                                           class="text-sky-600 focus:ring-sky-500">
                                    <span class="text-sm text-gray-700">{{ $option->option_text }}</span>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <div>
                            <textarea name="text_answers[{{ $question->id }}]"
                                      rows="4"
                                      class="w-full rounded-lg border border-sky-100 px-4 py-3 focus:border-sky-400 focus:ring-sky-400"
                                      placeholder="Write your answer here..."></textarea>
                        </div>
                    @endif
                </div>
            @endforeach

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="edu-btn">
                    Submit Quiz
                </button>

                <a href="{{ route('assessment.attempts.my') }}" class="edu-btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection