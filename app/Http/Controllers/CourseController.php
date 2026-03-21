<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::latest()->paginate(5);
        return view('courses.index', compact('courses'));
    }

public function togglePublish(Course $course)
{
    $course->update([
        'is_published' => ! $course->is_published,
    ]);

    return redirect()->route('courses.index')
        ->with('success', 'Course publish status updated successfully.');
}

public function create()
{
    $categories = \App\Models\Category::all();
    return view('courses.create', compact('categories'));
    
}

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:courses,title',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $count = 1;

        while (Course::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        Course::create([
            'title' => $request->title,
            'slug' => $slug,
            'description' => $request->description,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('courses.index')->with('success', 'Course created successfully.');
    }

    public function show(Course $course)
    {
        return view('courses.show', compact('course'));
    }

public function edit(Course $course)
{
    $categories = \App\Models\Category::all();
    return view('courses.edit', compact('course', 'categories'));
}

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:courses,title,' . $course->id,
            'description' => 'nullable|string',
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
            'category_id' => 'nullable|exists:categories,id',
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('courses.index')->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Course deleted successfully.');
    }
}