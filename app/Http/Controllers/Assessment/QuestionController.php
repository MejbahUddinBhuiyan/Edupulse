<?php

namespace App\Http\Controllers\Assessment;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    public function index(Quiz $quiz)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'teacher'])) {
            abort(403, 'Unauthorized access.');
        }

        $quiz->load('questions.options');

        return view('assessment.questions.index', compact('quiz'));
    }

    public function create(Quiz $quiz)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'teacher'])) {
            abort(403, 'Unauthorized access.');
        }

        return view('assessment.questions.create', compact('quiz'));
    }

    public function store(Request $request, Quiz $quiz)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'teacher'])) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'question_text' => ['required', 'string'],
            'question_type' => ['required', 'in:mcq,text'],
            'topic' => ['required', 'string', 'max:255'],
            'difficulty' => ['required', 'in:easy,medium,hard'],
            'marks' => ['required', 'numeric', 'min:0.5'],
            'correct_answer' => ['nullable', 'string'],

            'options' => ['nullable', 'array'],
            'options.*' => ['nullable', 'string', 'max:255'],
            'correct_option' => ['nullable', 'integer'],
        ]);

        $question = $quiz->questions()->create([
            'question_text' => $validated['question_text'],
            'question_type' => $validated['question_type'],
            'topic' => $validated['topic'],
            'difficulty' => $validated['difficulty'],
            'marks' => $validated['marks'],
            'correct_answer' => $validated['question_type'] === 'text'
                ? ($validated['correct_answer'] ?? null)
                : null,
        ]);

        if ($validated['question_type'] === 'mcq' && !empty($validated['options'])) {
            foreach ($validated['options'] as $index => $optionText) {
                if (!empty($optionText)) {
                    $question->options()->create([
                        'option_text' => $optionText,
                        'is_correct' => isset($validated['correct_option']) && (int) $validated['correct_option'] === $index,
                    ]);
                }
            }
        }

        $this->updateQuizTotalMarks($quiz);

        return redirect()
            ->route('assessment.quizzes.questions.index', $quiz)
            ->with('success', 'Question added successfully.');
    }

    public function edit(Quiz $quiz, Question $question)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'teacher'])) {
            abort(403, 'Unauthorized access.');
        }

        if ($question->quiz_id !== $quiz->id) {
            abort(404);
        }

        $question->load('options');

        return view('assessment.questions.edit', compact('quiz', 'question'));
    }

    public function update(Request $request, Quiz $quiz, Question $question)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'teacher'])) {
            abort(403, 'Unauthorized access.');
        }

        if ($question->quiz_id !== $quiz->id) {
            abort(404);
        }

        $validated = $request->validate([
            'question_text' => ['required', 'string'],
            'question_type' => ['required', 'in:mcq,text'],
            'topic' => ['required', 'string', 'max:255'],
            'difficulty' => ['required', 'in:easy,medium,hard'],
            'marks' => ['required', 'numeric', 'min:0.5'],
            'correct_answer' => ['nullable', 'string'],

            'options' => ['nullable', 'array'],
            'options.*' => ['nullable', 'string', 'max:255'],
            'correct_option' => ['nullable', 'integer'],
        ]);

        $question->update([
            'question_text' => $validated['question_text'],
            'question_type' => $validated['question_type'],
            'topic' => $validated['topic'],
            'difficulty' => $validated['difficulty'],
            'marks' => $validated['marks'],
            'correct_answer' => $validated['question_type'] === 'text'
                ? ($validated['correct_answer'] ?? null)
                : null,
        ]);

        $question->options()->delete();

        if ($validated['question_type'] === 'mcq' && !empty($validated['options'])) {
            foreach ($validated['options'] as $index => $optionText) {
                if (!empty($optionText)) {
                    $question->options()->create([
                        'option_text' => $optionText,
                        'is_correct' => isset($validated['correct_option']) && (int) $validated['correct_option'] === $index,
                    ]);
                }
            }
        }

        $this->updateQuizTotalMarks($quiz);

        return redirect()
            ->route('assessment.quizzes.questions.index', $quiz)
            ->with('success', 'Question updated successfully.');
    }

    public function destroy(Quiz $quiz, Question $question)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'teacher'])) {
            abort(403, 'Unauthorized access.');
        }

        if ($question->quiz_id !== $quiz->id) {
            abort(404);
        }

        $question->delete();

        $this->updateQuizTotalMarks($quiz);

        return redirect()
            ->route('assessment.quizzes.questions.index', $quiz)
            ->with('success', 'Question deleted successfully.');
    }

    private function updateQuizTotalMarks(Quiz $quiz): void
    {
        $totalMarks = $quiz->questions()->sum('marks');

        $quiz->update([
            'total_marks' => $totalMarks,
        ]);
    }
    
}