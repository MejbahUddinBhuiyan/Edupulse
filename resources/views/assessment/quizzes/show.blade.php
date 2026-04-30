@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-8 px-4">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $quiz->title }}</h1>
            <p class="text-sm text-gray-500">Quiz details and question summary.</p>
        </div>

<div class="flex gap-2">
    @auth
        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'teacher')
            <a href="{{ route('assessment.quizzes.edit', $quiz) }}"
               class="edu-btn-secondary">
                Edit
            </a>

            <a href="{{ route('assessment.quizzes.questions.index', $quiz) }}"
               class="edu-btn-secondary">
                Manage Questions
            </a>
        @endif

        @if(auth()->user()->role === 'student' && $quiz->status === 'published')
            <form action="{{ route('assessment.quizzes.start', $quiz) }}" method="POST">
                @csrf
                <button type="submit" class="edu-btn">
                    Start Quiz
                </button>
            </form>
        @endif
    @endauth
</div>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-100 text-green-800 px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-xl p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500">Course</p>
                <p class="font-semibold text-gray-800">{{ $quiz->course->title ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Created By</p>
                <p class="font-semibold text-gray-800">{{ $quiz->creator->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Marks</p>
                <p class="font-semibold text-gray-800">{{ $quiz->total_marks }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Pass Marks</p>
                <p class="font-semibold text-gray-800">{{ $quiz->pass_marks }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Time Limit</p>
                <p class="font-semibold text-gray-800">{{ $quiz->time_limit ? $quiz->time_limit . ' min' : 'Not set' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Status</p>
                <p class="font-semibold text-gray-800">{{ ucfirst($quiz->status) }}</p>
            </div>
        </div>

        @if($quiz->description)
            <div class="mt-4">
                <p class="text-sm text-gray-500">Description</p>
                <p class="text-gray-700 mt-1">{{ $quiz->description }}</p>
            </div>
        @endif
    </div>

    <div class="bg-white shadow rounded-xl p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-semibold text-gray-800">Questions</h2>

        @auth
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'teacher')
                <a href="{{ route('assessment.quizzes.questions.create', $quiz) }}"
                   class="edu-btn">
                    + Add Question
                </a>
            @endif
        @endauth
    </div>

    @auth
        @if(auth()->user()->role === 'student')
            <div class="rounded-lg border border-sky-100 bg-sky-50 px-4 py-4 text-sm text-sky-700">
                Questions will be shown after you click <strong>Start Quiz</strong>.
            </div>
        @else
            @forelse($quiz->questions as $question)
                <div class="border rounded-lg p-4 mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="font-semibold text-gray-800">
                            {{ $loop->iteration }}. {{ $question->question_text }}
                        </h3>
                        <span class="text-sm text-gray-500">{{ $question->marks }} marks</span>
                    </div>

                    <div class="flex flex-wrap gap-2 text-sm mb-3">
                        <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded">{{ ucfirst($question->question_type) }}</span>
                        <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded">{{ $question->topic }}</span>
                        <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded">{{ ucfirst($question->difficulty) }}</span>
                    </div>

                    @if($question->question_type === 'mcq')
                        <ul class="list-disc list-inside text-gray-700 space-y-1">
                            @foreach($question->options as $option)
                                <li>
                                    {{ $option->option_text }}
                                    @if($option->is_correct)
                                        <span class="text-green-600 font-medium">(Correct)</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-700">
                            <span class="font-medium">Expected Answer:</span>
                            {{ $question->correct_answer ?: 'No model answer provided.' }}
                        </p>
                    @endif
                </div>
            @empty
                <p class="text-gray-500">No questions added yet.</p>
            @endforelse
        @endif
    @endauth
</div>
</div>
@endsection