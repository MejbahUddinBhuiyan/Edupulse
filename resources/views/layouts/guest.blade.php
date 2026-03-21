<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Edupulse') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-gradient-to-br from-sky-50 via-white to-sky-100 text-gray-900">
        <div class="relative min-h-screen overflow-hidden">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute top-0 left-0 h-72 w-72 rounded-full bg-sky-200/30 blur-3xl"></div>
                <div class="absolute bottom-0 right-0 h-72 w-72 rounded-full bg-sky-300/20 blur-3xl"></div>
            </div>

            <div class="relative flex min-h-screen items-center justify-center px-4 py-10">
                <div class="w-full max-w-md">
                    <div class="mb-6 text-center">
                        <a href="/" class="inline-flex items-center gap-2 text-3xl font-bold text-sky-600">
                            <span class="material-icons text-4xl">school</span>
                            <span>Edupulse</span>
                        </a>
                        <p class="mt-2 text-sm text-gray-600">AI-Based Personalized Learning Platform</p>
                    </div>

                    <div class="edu-card-soft p-8">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>