@extends('layouts.app')

@section('content')
<div class="edu-container py-8">
    <h1 class="edu-page-title mb-6">Create Category</h1>

    <div class="edu-card-soft p-6">
        <form method="POST" action="{{ route('categories.store') }}">
            @csrf

            <div class="mb-4">
                <label class="edu-label">Name</label>
                <input name="name" class="edu-input" required>
            </div>

            <div class="mb-4">
                <label class="edu-label">Description</label>
                <textarea name="description" class="edu-input"></textarea>
            </div>

            <button class="edu-btn">Save</button>
        </form>
    </div>
</div>
@endsection