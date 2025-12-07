<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'UCO Student & Alumni') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-indigo-50 to-blue-50 dark:from-gray-900 dark:to-gray-800">
            <div>
                <a href="/" class="flex flex-col items-center gap-2">
                    <x-application-logo class="w-20 h-20 fill-current text-indigo-600 dark:text-indigo-400" />
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">UCO Platform</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Student & Alumni Community</p>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <div class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
                <p>&copy; {{ date('Y') }} UCO. All rights reserved.</p>
            </div>
        </div>
    </body>
</html>
