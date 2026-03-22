<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ReviewController;
Route::post('/courses/{course}/reviews', [ReviewController::class, 'store'])->name('courses.reviews.store');
Route::resource('courses', CourseController::class);
Route::patch('/courses/{course}/toggle-publish', [CourseController::class, 'togglePublish'])->name('courses.toggle-publish');
Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\CategoryController;
Route::resource('users', UserController::class)->except(['show']);
Route::resource('categories', CategoryController::class);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
