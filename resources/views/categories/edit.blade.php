@extends('layouts.app')

@section('content')
<div class="edu-container py-8">
    <h1 class="edu-page-title mb-6">Edit Category</h1>

    <div class="edu-card-soft p-6">
        <form method="POST" action="{{ route('categories.update', $category) }}">
            @csrf
            @method('PUT')

            <input name="name" value="{{ $category->name }}" class="edu-input mb-4">
            <textarea name="description" class="edu-input">{{ $category->description }}</textarea>

            <button class="edu-btn mt-4">Update</button>
        </form>
    </div>
</div>
@endsection