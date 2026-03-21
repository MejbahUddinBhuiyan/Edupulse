<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="edu-page-title text-2xl">Welcome Back</h1>
        <p class="edu-subtitle mt-2">Sign in to continue to your learning dashboard.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="edu-label">Email Address</label>
            <input id="email" class="edu-input" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <label for="password" class="edu-label">Password</label>
            <input id="password" class="edu-input" type="password" name="password" required autocomplete="current-password">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">
            <label class="inline-flex items-center gap-2 text-sm text-gray-600">
                <input type="checkbox" name="remember" class="rounded border-sky-200 text-sky-500 focus:ring-sky-400">
                <span>Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-sky-600 hover:text-sky-700" href="{{ route('password.request') }}">
                    Forgot password?
                </a>
            @endif
        </div>

        <button type="submit" class="edu-btn w-full">
            <span class="material-icons mr-2 text-base">login</span>
            Log in
        </button>

        <p class="text-center text-sm text-gray-600">
            Don’t have an account?
            <a href="{{ route('register') }}" class="font-medium text-sky-600 hover:text-sky-700">Create one</a>
        </p>
    </form>
</x-guest-layout>