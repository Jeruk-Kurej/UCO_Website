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
        
        {{-- Manual fallback: Read manifest and inject assets --}}
        @php
            $manifestPath = public_path('build/manifest.json');
            if (file_exists($manifestPath)) {
                $manifest = json_decode(file_get_contents($manifestPath), true);
                $cssFile = $manifest['resources/css/app.css']['file'] ?? null;
                $jsFile = $manifest['resources/js/app.js']['file'] ?? null;
            }
        @endphp
        
        @if(isset($cssFile))
            <link rel="stylesheet" href="/build/{{ $cssFile }}">
        @endif
        
        @if(isset($jsFile))
            <script type="module" src="/build/{{ $jsFile }}"></script>
        @endif
    </head>
    <body class="font-sans antialiased bg-soft-white">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden">
            <div class="relative z-10">
                <a href="/" class="flex flex-col items-center gap-3">
                    <img src="{{ asset('images/Logo UCO.png') }}" alt="UCO Logo" class="w-20 h-20 object-contain shadow-lg">
                    <h1 class="text-2xl font-bold text-soft-gray-900">UCO Platform</h1>
                    <p class="text-sm text-soft-gray-600">Student & Alumni Community</p>
                </a>
            </div>

            <div class="relative z-10 w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-xl rounded-2xl border border-soft-gray-100">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <div class="relative z-10 mt-6 text-center text-sm text-soft-gray-500">
                <p>&copy; {{ date('Y') }} UCO. All rights reserved.</p>
            </div>
        </div>
    </body>
</html>
