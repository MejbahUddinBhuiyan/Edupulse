@extends('layouts.app')

@section('content')
<div class="edu-container py-8">
    <div class="mb-6">
        <h1 class="edu-page-title">Edit User</h1>
        <p class="edu-subtitle mt-1">Update account information and role.</p>
    </div>

    <div class="edu-card-soft p-6">
        <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="edu-label">Name</label>
                <input type="text" name="name" class="edu-input" value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="edu-label">Email</label>
                <input type="email" name="email" class="edu-input" value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="edu-label">Role</label>
                <select name="role" class="edu-input" required>
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="teacher" {{ old('role', $user->role) == 'teacher' ? 'selected' : '' }}>Teacher</option>
                    <option value="student" {{ old('role', $user->role) == 'student' ? 'selected' : '' }}>Student</option>
                </select>
                @error('role')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="edu-label">New Password (optional)</label>
                <input type="password" name="password" class="edu-input">
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="edu-label">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="edu-input">
            </div>

            <div class="flex gap-3">
                <button type="submit" class="edu-btn">Update User</button>
                <a href="{{ route('users.index') }}" class="edu-btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection