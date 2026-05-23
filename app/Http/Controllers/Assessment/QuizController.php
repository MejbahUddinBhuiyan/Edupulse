<?php

namespace App\Http\Controllers\Assessment;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'teacher', 'student'])) {
            abort(403, 'Unauthorized access.');
        }

        $query = Quiz::with(['course', 'creator'])->latest();

        // Teacher sees only own quizzes
        if ($user->role === 'teacher') {
            $query->where('created_by', $user->id);
        }

        // Student sees only published quizzes
        if ($user->role === 'student') {
            $query->where('status', 'published');
        }

        $quizzes = $query->paginate(10);

        return view('assessment.quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'teacher'])) {
            abort(403, 'Unauthorized access.');
        }

        $courses = Course::orderBy('title')->get();

        return view('assessment.quizzes.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'teacher'])) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'time_limit' => ['nullable', 'integer', 'min:1'],
            'pass_marks' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:draft,published'],
        ]);

        $validated['created_by'] = $user->id;
        $validated['total_marks'] = 0;

        Quiz::create($validated);

        return redirect()
            ->route('assessment.quizzes.index')
            ->with('success', 'Quiz created successfully.');
    }

    public function show(Quiz $quiz)
    {
        $user = Auth::user();

        // Admin can view all
        if ($user->role === 'admin') {
            $quiz->load(['course', 'creator', 'questions.options']);
            return view('assessment.quizzes.show', compact('quiz'));
        }

        // Teacher can view only own quizzes
        if ($user->role === 'teacher') {
            if ($quiz->created_by !== $user->id) {
                abort(403, 'Unauthorized access.');
            }

            $quiz->load(['course', 'creator', 'questions.options']);
            return view('assessment.quizzes.show', compact('quiz'));
        }

        // Student can view only published quizzes
        if ($user->role === 'student' && $quiz->status === 'published') {
            $quiz->load(['course', 'creator', 'questions.options']);
            return view('assessment.quizzes.show', compact('quiz'));
        }

        abort(403, 'Unauthorized access.');
    }

    public function edit(Quiz $quiz)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'teacher'])) {
            abort(403, 'Unauthorized access.');
        }

        if ($user->role === 'teacher' && $quiz->created_by !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        $courses = Course::orderBy('title')->get();

        return view('assessment.quizzes.edit', compact('quiz', 'courses'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'teacher'])) {
            abort(403, 'Unauthorized access.');
        }

        if ($user->role === 'teacher' && $quiz->created_by !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'time_limit' => ['nullable', 'integer', 'min:1'],
            'pass_marks' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:draft,published'],
        ]);

        $quiz->update($validated);

        return redirect()
            ->route('assessment.quizzes.index')
            ->with('success', 'Quiz updated successfully.');
    }

    public function destroy(Quiz $quiz)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'teacher'])) {
            abort(403, 'Unauthorized access.');
        }

        if ($user->role === 'teacher' && $quiz->created_by !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        $quiz->delete();

        return redirect()
            ->route('assessment.quizzes.index')
            ->with('success', 'Quiz deleted successfully.');
    }

    public function togglePublish(Quiz $quiz)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'teacher'])) {
            abort(403, 'Unauthorized access.');
        }

        if ($user->role === 'teacher' && $quiz->created_by !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        $quiz->status = $quiz->status === 'published' ? 'draft' : 'published';
        $quiz->save();
        $quiz->refresh();

        return redirect()
            ->route('assessment.quizzes.index')
            ->with('success', 'Quiz status updated successfully.');
    }
}