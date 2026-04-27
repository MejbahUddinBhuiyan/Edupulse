@extends('layouts.app')

@section('content')
<div class="edu-container py-8">
    <div class="mb-6">
        <h1 class="edu-page-title">Add Question</h1>
        <p class="edu-subtitle mt-1">Quiz: {{ $quiz->title }}</p>
    </div>

    <div class="edu-card p-6">
        <form action="{{ route('assessment.quizzes.questions.store', $quiz) }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Question Text</label>
                <textarea name="question_text" rows="3" class="w-full rounded-lg border px-3 py-2">{{ old('question_text') }}</textarea>
                @error('question_text')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Question Type</label>
                    <select name="question_type" id="question_type" class="w-full rounded-lg border px-3 py-2">
                        <option value="mcq" {{ old('question_type') === 'mcq' ? 'selected' : '' }}>MCQ</option>
                        <option value="text" {{ old('question_type') === 'text' ? 'selected' : '' }}>Text</option>
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Topic</label>
                    <input type="text" name="topic" value="{{ old('topic') }}" class="w-full rounded-lg border px-3 py-2">
                    @error('topic')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Difficulty</label>
                    <select name="difficulty" class="w-full rounded-lg border px-3 py-2">
                        <option value="easy" {{ old('difficulty') === 'easy' ? 'selected' : '' }}>Easy</option>
                        <option value="medium" {{ old('difficulty') === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="hard" {{ old('difficulty') === 'hard' ? 'selected' : '' }}>Hard</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Marks</label>
                <input type="number" step="0.5" name="marks" value="{{ old('marks', 1) }}" class="w-full rounded-lg border px-3 py-2">
                @error('marks')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div id="mcq_options_section">
                <label class="mb-2 block text-sm font-medium text-gray-700">MCQ Options</label>

                @for($i = 0; $i < 4; $i++)
                    <div class="mb-3 flex items-center gap-3">
                        <input type="radio" name="correct_option" value="{{ $i }}" {{ old('correct_option') == $i ? 'checked' : '' }}>
                        <input type="text" name="options[]" value="{{ old('options.' . $i) }}"
                               placeholder="Option {{ $i + 1 }}"
                               class="w-full rounded-lg border px-3 py-2">
                    </div>
                @endfor
            </div>

            <div id="text_answer_section" style="display: none;">
                <label class="mb-1 block text-sm font-medium text-gray-700">Expected Answer (optional)</label>
                <textarea name="correct_answer" rows="3" class="w-full rounded-lg border px-3 py-2">{{ old('correct_answer') }}</textarea>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="edu-btn">Save Question</button>
                <a href="{{ route('assessment.quizzes.questions.index', $quiz) }}" class="edu-btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
    const questionType = document.getElementById('question_type');
    const mcqSection = document.getElementById('mcq_options_section');
    const textSection = document.getElementById('text_answer_section');

    function toggleQuestionFields() {
        if (questionType.value === 'mcq') {
            mcqSection.style.display = 'block';
            textSection.style.display = 'none';
        } else {
            mcqSection.style.display = 'none';
            textSection.style.display = 'block';
        }
    }

    questionType.addEventListener('change', toggleQuestionFields);
    toggleQuestionFields();
</script>
@endsection