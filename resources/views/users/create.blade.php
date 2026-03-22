@extends('layouts.app')

@section('content')
<div class="edu-container py-8">
    <div class="mb-6">
        <h1 class="edu-page-title">Create User</h1>
        <p class="edu-subtitle mt-1">Create a new admin, teacher, or student account.</p>
    </div>

    <div class="edu-card-soft p-6">
        <form method="POST" action="{{ route('users.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="edu-label">Name</label>
                <input type="text" name="name" class="edu-input" value="{{ old('name') }}" required>
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="edu-label">Email</label>
                <input type="email" name="email" class="edu-input" value="{{ old('email') }}" required>
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="edu-label">Role</label>
                <select name="role" class="edu-input" required>
                    <option value="">Select Role</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                    <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                </select>
                @error('role')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="edu-label">Password</label>
                <input type="password" name="password" class="edu-input" required>
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="edu-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="edu-input" required>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="edu-btn">Create User</button>
                <a href="{{ route('users.index') }}" class="edu-btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection