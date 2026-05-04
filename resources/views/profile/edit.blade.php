@extends('layouts.app')

@section('content')
<div class="edu-container py-8">
    <div class="mb-6">
        <h1 class="edu-page-title">Profile</h1>
        <p class="edu-subtitle mt-1">Manage your account information and password.</p>
    </div>

    <div class="space-y-6">
        <div class="edu-card p-6">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="edu-card p-6">
            @include('profile.partials.update-password-form')
        </div>

        <div class="edu-card p-6">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
@endsection