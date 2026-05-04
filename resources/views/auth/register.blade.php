<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="edu-page-title text-2xl">Create Account</h1>
        <p class="edu-subtitle mt-2">Start your personalized learning journey with Edupulse.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <label for="name" class="edu-label">Full Name</label>
            <input id="name" class="edu-input" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <label for="email" class="edu-label">Email Address</label>
            <input id="email" class="edu-input" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <label for="password" class="edu-label">Password</label>
            <input id="password" class="edu-input" type="password" name="password" required autocomplete="new-password">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <label for="password_confirmation" class="edu-label">Confirm Password</label>
            <input id="password_confirmation" class="edu-input" type="password" name="password_confirmation" required autocomplete="new-password">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button type="submit" class="edu-btn w-full">
            <span class="material-icons mr-2 text-base">person_add</span>
            Register
        </button>

        <p class="text-center text-sm text-gray-600">
            Already have an account?
            <a href="{{ route('login') }}" class="font-medium text-sky-600 hover:text-sky-700">Log in</a>
        </p>
    </form>
</x-guest-layout>