@extends('layouts.app')

@section('content')
<div class="edu-container py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="edu-page-title">Questions for: {{ $quiz->title }}</h1>
            <p class="edu-subtitle mt-1">Manage quiz questions, options, topic, and difficulty.</p>
        </div>

        <a href="{{ route('assessment.quizzes.questions.create', $quiz) }}" class="edu-btn">
            Add Question
        </a>
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
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Question</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Type</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Topic</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Difficulty</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Marks</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-sky-50 bg-white">
                    @forelse($quiz->questions as $question)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-800">
                                <div class="font-medium">{{ $question->question_text }}</div>

                                @if($question->question_type === 'mcq' && $question->options->count())
                                    <div class="mt-2 space-y-1">
                                        @foreach($question->options as $option)
                                            <div class="text-xs text-gray-600">
                                                • {{ $option->option_text }}
                                                @if($option->is_correct)
                                                    <span class="ml-1 font-medium text-green-600">(Correct)</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif($question->question_type === 'text' && $question->correct_answer)
                                    <div class="mt-2 text-xs text-gray-600">
                                        <span class="font-medium">Expected Answer:</span>
                                        {{ $question->correct_answer }}
                                    </div>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-600">
                                <span class="inline-flex rounded-full bg-sky-100 px-3 py-1 text-xs font-medium text-sky-700">
                                    {{ strtoupper($question->question_type) }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-600">{{ $question->topic }}</td>

                            <td class="px-6 py-4 text-sm text-gray-600">
                                <span class="inline-flex rounded-full bg-purple-100 px-3 py-1 text-xs font-medium text-purple-700">
                                    {{ ucfirst($question->difficulty) }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-600">{{ $question->marks }}</td>

                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('assessment.quizzes.questions.edit', [$quiz, $question]) }}"
                                       class="edu-btn-secondary">
                                        Edit
                                    </a>

                                    <form action="{{ route('assessment.quizzes.questions.destroy', [$quiz, $question]) }}"
                                          method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this question?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-white px-4 py-2 text-sm font-medium text-red-600 transition duration-200 hover:bg-red-50">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">
                                No questions added yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-sky-100 px-6 py-4 flex items-center justify-between">
            <div class="text-sm text-gray-600">
                <span class="font-medium">Quiz Total Marks:</span> {{ $quiz->total_marks }}
            </div>

            <a href="{{ route('assessment.quizzes.show', $quiz) }}" class="edu-btn-secondary">
                Back to Quiz
            </a>
        </div>
    </div>
</div>
@endsection