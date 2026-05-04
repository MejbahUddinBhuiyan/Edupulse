<?php

namespace App\Http\Controllers\Assessment;

use App\Http\Controllers\Controller;
use App\Models\AttemptAnswer;
use App\Models\CourseEnrollment;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Services\PersonalizationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuizAttemptController extends Controller
{
    public function start(Quiz $quiz)
    {
        $user = Auth::user();

        if ($user->role !== 'student') {
            abort(403, 'Only students can attempt quizzes.');
        }

        if ($quiz->status !== 'published') {
            abort(403, 'This quiz is not available for students.');
        }

        $nextAttemptNumber = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->max('attempt_number');

        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'user_id' => $user->id,
            'attempt_number' => $nextAttemptNumber ? $nextAttemptNumber + 1 : 1,
            'total_marks' => $quiz->total_marks,
            'obtained_marks' => 0,
            'status' => 'in_progress',
            'started_at' => now(),
        ]);

        return redirect()->route('assessment.attempts.show', $attempt);
    }

    public function show(QuizAttempt $attempt)
    {
        $user = Auth::user();

        if ($user->role === 'student' && $attempt->user_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        $attempt->load([
            'quiz.course',
            'answers.question.options',
            'answers.selectedOption',
        ]);

        if ($attempt->status === 'in_progress') {
            $attempt->quiz->load('questions.options');
            return view('assessment.attempts.take', compact('attempt'));
        }

        return view('assessment.attempts.show', compact('attempt'));
    }

    public function submit(Request $request, QuizAttempt $attempt, PersonalizationService $personalizationService)
    {
        $user = Auth::user();

        if ($user->role !== 'student' || $attempt->user_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        if ($attempt->status !== 'in_progress') {
            return redirect()
                ->route('assessment.attempts.show', $attempt)
                ->with('error', 'This attempt has already been submitted.');
        }

        $quiz = $attempt->quiz()->with('questions.options')->first();

        DB::transaction(function () use ($request, $attempt, $quiz) {
            $obtainedMarks = 0;

            foreach ($quiz->questions as $question) {
                $selectedOptionId = null;
                $answerText = null;
                $isCorrect = null;
                $marksAwarded = 0;

                if ($question->question_type === 'mcq') {
                    $selectedOptionId = $request->input('answers.' . $question->id);
                    $correctOption = $question->options->firstWhere('is_correct', true);

                    if (
                        $selectedOptionId &&
                        $correctOption &&
                        (int) $selectedOptionId === (int) $correctOption->id
                    ) {
                        $isCorrect = true;
                        $marksAwarded = $question->marks;
                    } else {
                        $isCorrect = false;
                        $marksAwarded = 0;
                    }
                }

                if ($question->question_type === 'text') {
                    $answerText = $request->input('text_answers.' . $question->id);
                    $isCorrect = null;
                    $marksAwarded = 0;
                }

                AttemptAnswer::updateOrCreate(
                    [
                        'quiz_attempt_id' => $attempt->id,
                        'question_id' => $question->id,
                    ],
                    [
                        'selected_option_id' => $selectedOptionId,
                        'answer_text' => $answerText,
                        'is_correct' => $isCorrect,
                        'marks_awarded' => $marksAwarded,
                    ]
                );

                $obtainedMarks += $marksAwarded;
            }

            $hasTextQuestions = $quiz->questions->contains(function ($question) {
                return $question->question_type === 'text';
            });

            $attempt->update([
                'obtained_marks' => $obtainedMarks,
                'status' => $hasTextQuestions ? 'submitted' : 'evaluated',
                'submitted_at' => now(),
            ]);
        });

        $attempt->refresh()->load('quiz.course.quizzes', 'answers.question');

        $this->updateCourseEnrollment($attempt);

        if ($attempt->status === 'evaluated') {
            $personalizationService->analyzeAttempt($attempt);
        }

        return redirect()
            ->route('assessment.attempts.show', $attempt)
            ->with('success', 'Quiz submitted successfully.');
    }

    public function myAttempts()
    {
        $user = Auth::user();

        if ($user->role !== 'student') {
            abort(403, 'Only students can view their attempts.');
        }

        $attempts = QuizAttempt::with('quiz.course')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('assessment.attempts.my_attempts', compact('attempts'));
    }

    public function allAttempts()
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'teacher'])) {
            abort(403, 'Unauthorized access.');
        }

        $quizzesQuery = Quiz::with(['course', 'creator'])
            ->withCount('attempts');

        if ($user->role === 'teacher') {
            $quizzesQuery->where('created_by', $user->id);
        }

        $quizzes = $quizzesQuery->latest()->paginate(10);

        return view('assessment.attempts.index', compact('quizzes'));
    }

    public function quizAttempts(Quiz $quiz)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'teacher'])) {
            abort(403, 'Unauthorized access.');
        }

        if ($user->role === 'teacher' && $quiz->created_by !== $user->id) {
            abort(403, 'You can only view attempts for your own quizzes.');
        }

        $attempts = QuizAttempt::with(['quiz.course', 'student', 'grader'])
            ->where('quiz_id', $quiz->id)
            ->latest()
            ->paginate(10);

        return view('assessment.attempts.quiz_attempts', compact('quiz', 'attempts'));
    }

    public function review(QuizAttempt $attempt)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'teacher'])) {
            abort(403, 'Unauthorized access.');
        }

        $attempt->load([
            'quiz.course',
            'student',
            'answers.question.options',
            'answers.selectedOption',
        ]);

        return view('assessment.attempts.review', compact('attempt'));
    }

    public function grade(Request $request, QuizAttempt $attempt, PersonalizationService $personalizationService)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'teacher'])) {
            abort(403, 'Unauthorized access.');
        }

        $attempt->load('answers.question');

        $validated = $request->validate([
            'answers' => ['nullable', 'array'],
            'answers.*.marks_awarded' => ['nullable', 'numeric', 'min:0'],
            'answers.*.teacher_feedback' => ['nullable', 'string'],
            'override_note' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated, $attempt, $user) {
            foreach ($attempt->answers as $answer) {
                $marksAwarded = data_get($validated, "answers.{$answer->id}.marks_awarded");
                $teacherFeedback = data_get($validated, "answers.{$answer->id}.teacher_feedback");

                if ($marksAwarded !== null) {
                    $maxMarks = $answer->question->marks;

                    $answer->update([
                        'marks_awarded' => min((float) $marksAwarded, (float) $maxMarks),
                        'teacher_feedback' => $teacherFeedback,
                        'is_correct' => null,
                    ]);
                } elseif ($teacherFeedback !== null) {
                    $answer->update([
                        'teacher_feedback' => $teacherFeedback,
                    ]);
                }
            }

            $newObtainedMarks = $attempt->answers()->sum('marks_awarded');

            $attempt->update([
                'obtained_marks' => $newObtainedMarks,
                'status' => 'evaluated',
                'graded_by' => $user->id,
                'is_overridden' => true,
                'override_note' => $validated['override_note'] ?? null,
            ]);
        });

        $attempt->refresh()->load('quiz.course.quizzes', 'answers.question');

        $this->updateCourseEnrollment($attempt);

        $personalizationService->analyzeAttempt($attempt);

        return redirect()
            ->route('assessment.attempts.show', $attempt)
            ->with('success', 'Attempt graded successfully.');
    }

    private function updateCourseEnrollment(QuizAttempt $attempt): void
    {
        $courseId = $attempt->quiz->course_id;
        $userId = $attempt->user_id;

        $enrollment = CourseEnrollment::firstOrCreate(
            [
                'user_id' => $userId,
                'course_id' => $courseId,
            ],
            [
                'enrolled_at' => now(),
            ]
        );

        $totalQuizzes = $attempt->quiz->course->quizzes()->count();

        $completedQuizCount = QuizAttempt::where('user_id', $userId)
            ->whereHas('quiz', function ($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })
            ->whereIn('status', ['submitted', 'evaluated'])
            ->distinct('quiz_id')
            ->count('quiz_id');

        $completion = $totalQuizzes > 0
            ? min(100, round(($completedQuizCount / $totalQuizzes) * 100))
            : 0;

        $enrollment->update([
            'completion_percentage' => $completion,
            'is_completed' => $completion >= 100,
            'completed_at' => $completion >= 100 && !$enrollment->completed_at
                ? now()
                : $enrollment->completed_at,
        ]);
    }
}