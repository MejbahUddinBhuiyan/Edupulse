<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Personalization\StudentDashboardController;
use App\Http\Controllers\Personalization\TeacherPerformanceController;


Route::post('/courses/{course}/reviews', [ReviewController::class, 'store'])->name('courses.reviews.store');
Route::resource('courses', CourseController::class);
Route::patch('/courses/{course}/toggle-publish', [CourseController::class, 'togglePublish'])->name('courses.toggle-publish');
Route::get('/', function () {
    return view('welcome');
});




use App\Http\Controllers\Assessment\QuizController;

Route::middleware(['auth'])->prefix('assessment')->name('assessment.')->group(function () {
    Route::resource('quizzes', QuizController::class);
});
use App\Http\Controllers\CategoryController;
Route::resource('users', UserController::class)->except(['show']);
Route::resource('categories', CategoryController::class);
use App\Http\Controllers\Assessment\QuestionController;
Route::middleware(['auth'])->prefix('assessment')->name('assessment.')->group(function () {
    Route::resource('quizzes', QuizController::class);

    Route::get('quizzes/{quiz}/questions', [QuestionController::class, 'index'])->name('quizzes.questions.index');
    Route::get('quizzes/{quiz}/questions/create', [QuestionController::class, 'create'])->name('quizzes.questions.create');
    Route::post('quizzes/{quiz}/questions', [QuestionController::class, 'store'])->name('quizzes.questions.store');
    Route::get('quizzes/{quiz}/questions/{question}/edit', [QuestionController::class, 'edit'])->name('quizzes.questions.edit');
    Route::put('quizzes/{quiz}/questions/{question}', [QuestionController::class, 'update'])->name('quizzes.questions.update');
    Route::delete('quizzes/{quiz}/questions/{question}', [QuestionController::class, 'destroy'])->name('quizzes.questions.destroy');
});

use App\Http\Controllers\Assessment\QuizAttemptController;
Route::middleware(['auth'])->prefix('assessment')->name('assessment.')->group(function () {
    Route::resource('quizzes', QuizController::class);
    Route::patch('quizzes/{quiz}/toggle-publish', [QuizController::class, 'togglePublish'])
    ->name('quizzes.toggle-publish');
    Route::get('quizzes/{quiz}/questions', [QuestionController::class, 'index'])->name('quizzes.questions.index');
    Route::get('quizzes/{quiz}/questions/create', [QuestionController::class, 'create'])->name('quizzes.questions.create');
    Route::post('quizzes/{quiz}/questions', [QuestionController::class, 'store'])->name('quizzes.questions.store');
    Route::get('quizzes/{quiz}/questions/{question}/edit', [QuestionController::class, 'edit'])->name('quizzes.questions.edit');
    Route::put('quizzes/{quiz}/questions/{question}', [QuestionController::class, 'update'])->name('quizzes.questions.update');
    Route::delete('quizzes/{quiz}/questions/{question}', [QuestionController::class, 'destroy'])->name('quizzes.questions.destroy');
    Route::get('quizzes/{quiz}/attempts', [QuizAttemptController::class, 'quizAttempts'])
    ->name('quizzes.attempts');
    Route::post('quizzes/{quiz}/start', [QuizAttemptController::class, 'start'])->name('quizzes.start');
    Route::get('attempts/{attempt}', [QuizAttemptController::class, 'show'])->name('attempts.show');
    Route::post('attempts/{attempt}/submit', [QuizAttemptController::class, 'submit'])->name('attempts.submit');
    Route::get('my-attempts', [QuizAttemptController::class, 'myAttempts'])->name('attempts.my');
    Route::get('all-attempts', [QuizAttemptController::class, 'allAttempts'])->name('attempts.index');
    Route::get('attempts/{attempt}/review', [QuizAttemptController::class, 'review'])->name('attempts.review');
    Route::post('attempts/{attempt}/grade', [QuizAttemptController::class, 'grade'])->name('attempts.grade');
});
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])
    ->name('student.dashboard');
require __DIR__.'/auth.php';
Route::get('/teacher/performance', [TeacherPerformanceController::class, 'index'])
    ->name('teacher.performance');
Route::get('/teacher/performance/{student}', [TeacherPerformanceController::class, 'show'])
    ->name('teacher.performance.show');
use App\Http\Controllers\Personalization\RecommendationController;

Route::post('/recommendations/{recommendation}/read', [RecommendationController::class, 'markAsRead'])
    ->name('recommendations.read');