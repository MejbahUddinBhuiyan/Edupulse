<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    private function authorizeTeacherOrAdmin()
    {
        if (!auth()->check() || !auth()->user()->isAdminOrTeacher()) {
            abort(403, 'Only admin or teacher can manage courses.');
        }
    }

    public function index()
    {
        $courses = Course::with(['category', 'prerequisites'])->latest()->paginate(5);
        return view('courses.index', compact('courses'));
    }

    public function togglePublish(Course $course)
    {
        $this->authorizeTeacherOrAdmin();

        $course->update([
            'is_published' => ! $course->is_published,
        ]);

        return redirect()->route('courses.index')
            ->with('success', 'Course publish status updated successfully.');
    }

    public function create()
    {
        $this->authorizeTeacherOrAdmin();

        $categories = \App\Models\Category::all();
        $courses = Course::all();

        return view('courses.create', compact('categories', 'courses'));
    }

    public function store(Request $request)
    {
        $this->authorizeTeacherOrAdmin();

        $request->validate([
            'title' => 'required|string|max:255|unique:courses,title',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'prerequisites' => 'nullable|array',
            'prerequisites.*' => 'exists:courses,id',
        ]);

        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $count = 1;

        while (Course::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        $course = Course::create([
            'title' => $request->title,
            'slug' => $slug,
            'description' => $request->description,
            'category_id' => $request->category_id,
        ]);

        $course->prerequisites()->sync($request->prerequisites ?? []);

        return redirect()->route('courses.index')
            ->with('success', 'Course created successfully.');
    }

    public function show(Course $course)
    {
        $course->load(['category', 'prerequisites', 'reviews.user']);

        return view('courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        $this->authorizeTeacherOrAdmin();

        $categories = \App\Models\Category::all();
        $courses = Course::where('id', '!=', $course->id)->get();

        return view('courses.edit', compact('course', 'categories', 'courses'));
    }

    public function update(Request $request, Course $course)
    {
        $this->authorizeTeacherOrAdmin();

        $request->validate([
            'title' => 'required|string|max:255|unique:courses,title,' . $course->id,
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'prerequisites' => 'nullable|array',
            'prerequisites.*' => 'exists:courses,id',
        ]);

        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $count = 1;

        while (Course::where('slug', $slug)->where('id', '!=', $course->id)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        $course->update([
            'title' => $request->title,
            'slug' => $slug,
            'description' => $request->description,
            'category_id' => $request->category_id,
        ]);

        $course->prerequisites()->sync($request->prerequisites ?? []);

        return redirect()->route('courses.index')
            ->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        $this->authorizeTeacherOrAdmin();

        $course->delete();

        return redirect()->route('courses.index')
            ->with('success', 'Course deleted successfully.');
    }
}