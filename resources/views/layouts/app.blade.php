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
        
        <!-- FontAwesome Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        {{-- Fallback for built assets (used when Vite manifest exists) --}}
        @php
            $manifestPath = public_path('build/manifest.json');
            if (file_exists($manifestPath)) {
                $manifest = json_decode(file_get_contents($manifestPath), true);
                $cssFile = $manifest['resources/css/app.css']['file'] ?? null;
                $jsFile = $manifest['resources/js/app.js']['file'] ?? null;
            }
        @endphp

        @if(!empty($cssFile))
            <link rel="stylesheet" href="/build/{{ $cssFile }}">
        @endif

        @if(!empty($jsFile))
            <script type="module" src="/build/{{ $jsFile }}"></script>
        @endif

        <style>
            [x-cloak] { display: none !important; }
        </style>

        @stack('styles')
    </head>
    {{-- Layout body: keep background subtle and readable --}}
    <body class="font-sans antialiased bg-soft-white">
        <div class="min-h-screen flex flex-col">
            {{-- Navigation --}}
            @include('layouts.navigation')

            {{-- Page Heading --}}
            @isset($header)
                <header class="bg-white shadow-sm border-b border-gray-200">
                    <div class="w-full max-w-[1600px] 2xl:max-w-[1720px] mx-auto py-4 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            {{-- Main Content --}}
            <main class="flex-grow">
                <div class="w-full max-w-[1600px] 2xl:max-w-[1720px] mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{-- Global Toast Notifications --}}
                    <div class="fixed top-6 right-6 z-50 flex flex-col gap-3 items-end pointer-events-none">
                        @if (session('success'))
                            <div x-data="{ show: true }" 
                                 x-show="show" 
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 translate-y-[-8px]"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-300"
                                 x-transition:leave-start="opacity-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 translate-y-[-8px]"
                                 x-init="setTimeout(() => show = false, 4000)" 
                                 class="pointer-events-auto max-w-sm w-full bg-emerald-600 text-white px-4 py-3 rounded-lg shadow-lg flex items-start justify-between gap-3" 
                                 role="alert">
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-check-circle-fill text-lg"></i>
                                    <span class="text-sm font-medium">{{ session('success') }}</span>
                                </div>
                                <button @click="show = false" class="text-white opacity-90 hover:opacity-100 transition-opacity flex-shrink-0 mt-0.5">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div x-data="{ show: true }" 
                                 x-show="show" 
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 translate-y-[-8px]"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-300"
                                 x-transition:leave-start="opacity-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 translate-y-[-8px]"
                                 x-init="setTimeout(() => show = false, 4000)" 
                                 class="pointer-events-auto max-w-sm w-full bg-red-600 text-white px-4 py-3 rounded-lg shadow-lg flex items-start justify-between gap-3" 
                                 role="alert">
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-exclamation-triangle-fill text-lg"></i>
                                    <span class="text-sm font-medium">{{ session('error') }}</span>
                                </div>
                                <button @click="show = false" class="text-white opacity-90 hover:opacity-100 transition-opacity flex-shrink-0 mt-0.5">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                        @endif

                        @if (session('warning'))
                            <div x-data="{ show: true }" 
                                 x-show="show" 
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 translate-y-[-8px]"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-300"
                                 x-transition:leave-start="opacity-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 translate-y-[-8px]"
                                 x-init="setTimeout(() => show = false, 4000)" 
                                 class="pointer-events-auto max-w-sm w-full bg-yellow-600 text-white px-4 py-3 rounded-lg shadow-lg flex items-start justify-between gap-3" 
                                 role="alert">
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-exclamation-circle-fill text-lg"></i>
                                    <span class="text-sm font-medium">{{ session('warning') }}</span>
                                </div>
                                <button @click="show = false" class="text-white opacity-90 hover:opacity-100 transition-opacity flex-shrink-0 mt-0.5">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div x-data="{ show: true }" 
                                 x-show="show" 
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 translate-y-[-8px]"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-300"
                                 x-transition:leave-start="opacity-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 translate-y-[-8px]"
                                 x-init="setTimeout(() => show = false, 5000)" 
                                 class="pointer-events-auto max-w-sm w-full bg-red-600 text-white px-4 py-3 rounded-lg shadow-lg flex items-start justify-between gap-3" 
                                 role="alert">
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-exclamation-triangle-fill text-lg"></i>
                                    <span class="text-sm font-medium">Validasi Gagal! Pastikan form diisi dengan benar.</span>
                                </div>
                                <button @click="show = false" class="text-white opacity-90 hover:opacity-100 transition-opacity flex-shrink-0 mt-0.5">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                        @endif
                    </div>

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
