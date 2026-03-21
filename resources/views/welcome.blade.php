<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Edupulse</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-white text-gray-900">
        <header class="border-b border-sky-100 bg-white/90 backdrop-blur">
            <div class="edu-container flex items-center justify-between py-4">
                <a href="/" class="flex items-center gap-2 text-2xl font-bold text-sky-600">
                    <span class="material-icons text-3xl">school</span>
                    <span>Edupulse</span>
                </a>

                <nav class="flex items-center gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="edu-btn-secondary">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="edu-btn-secondary">Log in</a>
                        <a href="{{ route('register') }}" class="edu-btn">Get Started</a>
                    @endauth
                </nav>
            </div>
        </header>

        <main>
            <section class="bg-gradient-to-br from-sky-50 to-white py-20">
                <div class="edu-container grid items-center gap-10 lg:grid-cols-2">
                    <div>
                        <p class="mb-3 inline-flex rounded-full bg-sky-100 px-4 py-1 text-sm font-medium text-sky-700">
                            AI-Based Personalized Learning Platform
                        </p>
                        <h1 class="text-4xl font-bold leading-tight sm:text-5xl">
                            Welcome to <span class="text-sky-600">Edupulse</span>
                        </h1>
                        <p class="mt-5 max-w-xl text-lg text-gray-600">
                            A formal and user-friendly learning platform that manages courses, assessments, personalized recommendations, and student performance analytics.
                        </p>

                        <div class="mt-8 flex flex-wrap gap-4">
                            <a href="{{ route('register') }}" class="edu-btn">
                                <span class="material-icons mr-2 text-base">arrow_forward</span>
                                Start Learning
                            </a>
                            <a href="{{ route('login') }}" class="edu-btn-secondary">
                                <span class="material-icons mr-2 text-base">login</span>
                                Sign In
                            </a>
                        </div>
                    </div>

                    <div class="edu-card p-8">
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="rounded-xl bg-sky-50 p-5">
                                <span class="material-icons text-sky-600">menu_book</span>
                                <h3 class="mt-3 text-lg font-semibold">Course Management</h3>
                                <p class="mt-2 text-sm text-gray-600">Create and organize learning content efficiently.</p>
                            </div>
                            <div class="rounded-xl bg-sky-50 p-5">
                                <span class="material-icons text-sky-600">quiz</span>
                                <h3 class="mt-3 text-lg font-semibold">Smart Assessment</h3>
                                <p class="mt-2 text-sm text-gray-600">Track quizzes, grading, and progress in one place.</p>
                            </div>
                            <div class="rounded-xl bg-sky-50 p-5">
                                <span class="material-icons text-sky-600">psychology</span>
                                <h3 class="mt-3 text-lg font-semibold">Rule-Based AI</h3>
                                <p class="mt-2 text-sm text-gray-600">Recommend lessons based on weak topics and scores.</p>
                            </div>
                            <div class="rounded-xl bg-sky-50 p-5">
                                <span class="material-icons text-sky-600">analytics</span>
                                <h3 class="mt-3 text-lg font-semibold">Analytics & Reports</h3>
                                <p class="mt-2 text-sm text-gray-600">Monitor learner performance with clear reporting tools.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </body>
</html>