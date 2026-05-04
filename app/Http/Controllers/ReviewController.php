<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Course $course)
{
    if (!auth()->check() || !auth()->user()->isStudent()) {
        abort(403, 'Only students can submit reviews.');
    }

    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'review' => 'nullable|string|max:1000',
    ]);

    Review::updateOrCreate(
        [
            'course_id' => $course->id,
            'user_id' => auth()->id(),
        ],
        [
            'rating' => $request->rating,
            'review' => $request->review,
        ]
    );

    $course->updateAverageRating();

    return redirect()->route('courses.show', $course)
        ->with('success', 'Your review has been submitted successfully.');
}
}