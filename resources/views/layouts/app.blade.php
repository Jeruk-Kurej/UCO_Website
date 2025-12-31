<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'UCO Platform') }} - @yield('title', 'Dashboard')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @stack('styles')
    </head>
    {{-- ✅ CHANGED: Soft white background (not harsh white) --}}
    <body class="font-sans antialiased bg-gray-50">
        <div class="min-h-screen flex flex-col">
            {{-- Navigation --}}
            @include('layouts.navigation')

            {{-- Page Heading --}}
            @isset($header)
                <header class="bg-white shadow-sm border-b border-gray-200">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            {{-- Main Content --}}
            <main class="flex-grow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{-- Flash Messages --}}
                    @if (session('success'))
                        <div x-data="{ show: true }" 
                             x-show="show" 
                             x-transition 
                             x-init="setTimeout(() => show = false, 4000)" 
                             class="mb-4 bg-green-50 border-l-4 border-green-500 text-green-800 px-4 py-3 rounded-r shadow-sm flex items-center justify-between" 
                             role="alert">
                            <div class="flex items-center gap-2">
                                <i class="bi bi-check-circle-fill text-green-600"></i>
                                <span class="font-medium">{{ session('success') }}</span>
                            </div>
                            <button @click="show = false" class="text-green-600 hover:text-green-800">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div x-data="{ show: true }" 
                             x-show="show" 
                             x-transition 
                             x-init="setTimeout(() => show = false, 4000)" 
                             class="mb-4 bg-red-50 border-l-4 border-red-500 text-red-800 px-4 py-3 rounded-r shadow-sm flex items-center justify-between" 
                             role="alert">
                            <div class="flex items-center gap-2">
                                <i class="bi bi-exclamation-triangle-fill text-red-600"></i>
                                <span class="font-medium">{{ session('error') }}</span>
                            </div>
                            <button @click="show = false" class="text-red-600 hover:text-red-800">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    @endif

                    @if (session('warning'))
                        <div x-data="{ show: true }" 
                             x-show="show" 
                             x-transition 
                             x-init="setTimeout(() => show = false, 4000)" 
                             class="mb-4 bg-yellow-50 border-l-4 border-yellow-500 text-yellow-800 px-4 py-3 rounded-r shadow-sm flex items-center justify-between" 
                             role="alert">
                            <div class="flex items-center gap-2">
                                <i class="bi bi-exclamation-circle-fill text-yellow-600"></i>
                                <span class="font-medium">{{ session('warning') }}</span>
                            </div>
                            <button @click="show = false" class="text-yellow-600 hover:text-yellow-800">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    @endif

                    {{-- Page Content --}}
                    {{ $slot }}
                </div>
            </main>

            {{-- Footer --}}
            @include('layouts.footer')
        </div>

        @stack('scripts')
    </body>
</html>
