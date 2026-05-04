@extends('layouts.app')

@section('content')
<div class="edu-container py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="edu-page-title">Courses</h1>
            <p class="edu-subtitle mt-1">Manage all course content from one place.</p>
        </div>
       @auth
    @if(auth()->user()->isAdminOrTeacher())
        <a href="{{ route('courses.create') }}" class="edu-btn">Add Course</a>
    @endif
@endauth
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
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Title</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Category</th>

                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Slug</th>
                        
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Description</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
<th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Prerequisites</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold text-gray-700">Ratings</th>
                    </tr>

                </thead>
                <tbody class="divide-y divide-sky-50 bg-white">
                    @forelse($courses as $course)
                        <tr>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $course->title }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                  {{ $course->category->name ?? 'No Category' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $course->slug }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ \Illuminate\Support\Str::limit($course->description, 60) }}
                            </td>
                            <td class="px-6 py-4">
    @if($course->is_published)
        <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
            Published
        </span>
    @else
        <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-700">
            Unpublished
        </span>
    @endif
    <td class="px-6 py-4 text-sm text-gray-600">
    @if($course->prerequisites->count())
        <div class="flex flex-wrap gap-2">
            @foreach($course->prerequisites as $pre)
                <span class="inline-flex rounded-full bg-sky-100 px-3 py-1 text-xs font-medium text-sky-700">
                    {{ $pre->title }}
                </span>
            @endforeach
        </div>
    @else
        <span class="text-gray-400">None</span>
    @endif
</td>
<td class="px-6 py-4 text-sm text-gray-600">
    {{ $course->rating }}/5
</td>
</td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('courses.show', $course) }}" class="edu-btn-secondary">View</a>
                                   

@auth
    @if(auth()->user()->isAdminOrTeacher())
        <a href="{{ route('courses.edit', $course) }}" class="edu-btn-secondary">Edit</a>

        <form action="{{ route('courses.toggle-publish', $course) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit"
                class="inline-flex items-center justify-center rounded-lg border px-4 py-2 text-sm font-medium transition duration-200
                {{ $course->is_published
                    ? 'border-amber-200 bg-white text-amber-700 hover:bg-amber-50'
                    : 'border-green-200 bg-white text-green-700 hover:bg-green-50' }}">
                {{ $course->is_published ? 'Unpublish' : 'Publish' }}
            </button>
        </form>

        <form action="{{ route('courses.destroy', $course) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this course?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-white px-4 py-2 text-sm font-medium text-red-600 transition duration-200 hover:bg-red-50">
                Delete
            </button>
        </form>
    @endif
@endauth
                                </div>
                            </td>
                            
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">
                                No courses available.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-sky-100 px-6 py-4">
            {{ $courses->links() }}
        </div>
    </div>
</div>
@endsection