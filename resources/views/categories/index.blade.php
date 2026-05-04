@extends('layouts.app')

@section('content')
<div class="edu-container py-8">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="edu-page-title">Categories</h1>
        <a href="{{ route('categories.create') }}" class="edu-btn">Add Category</a>
    </div>

    <div class="edu-card p-6">
        @foreach($categories as $category)
            <div class="border-b py-3 flex justify-between">
                <div>
                    <h3 class="font-semibold">{{ $category->name }}</h3>
                    <p class="text-sm text-gray-600">{{ $category->description }}</p>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('categories.edit', $category) }}" class="edu-btn-secondary">Edit</a>

                    <form method="POST" action="{{ route('categories.destroy', $category) }}">
                        @csrf
                        @method('DELETE')
                        <button class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-white px-4 py-2 text-sm font-medium text-red-600 transition duration-200 hover:bg-red-50">Delete</button>
                    </form>
                </div>
            </div>
        @endforeach

        {{ $categories->links() }}
    </div>
</div>
@endsection