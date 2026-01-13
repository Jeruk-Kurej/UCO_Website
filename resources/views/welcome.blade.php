<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>UCO Platform - Student & Alumni Business Directory</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
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
    
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
    </style>
</head>
<body class="antialiased bg-soft-white">
    <div class="h-screen flex flex-col overflow-hidden relative">
        <!-- Elegant Background Accents -->
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-gradient-to-br from-uco-orange-100 to-uco-yellow-100 rounded-full blur-3xl opacity-20 -z-10"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-gradient-to-tr from-uco-yellow-50 to-uco-orange-50 rounded-full blur-3xl opacity-30 -z-10"></div>
        
        {{-- Main Content --}}
        <main class="flex-1 flex items-center relative overflow-hidden">
            <div class="max-w-6xl mx-auto px-6 w-full relative z-10">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    {{-- Left: Content --}}
                    <div class="space-y-6 relative">
                        <div class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-soft-gray-200 rounded-full shadow-sm">
                            <div class="w-2 h-2 bg-gradient-to-r from-uco-orange-400 to-uco-yellow-400 rounded-full"></div>
                            <span class="text-xs font-semibold text-soft-gray-700">For UC Students & Alumni</span>
                        </div>
                        
                        <h1 class="text-5xl font-bold text-soft-gray-900 leading-tight tracking-tight">
                            Business directory<br>
                            for <span class="text-soft-gray-900">entrepreneurs</span>
                        </h1>
                        
                        <p class="text-lg text-soft-gray-600 leading-relaxed max-w-lg">
                            Connect with Universitas Ciputra's entrepreneurial community. Discover businesses, showcase your products, and grow your network.
                        </p>
                        
                        {{-- Feature Pills --}}
                        <div class="flex flex-wrap gap-3 pt-2">
                            <div class="px-4 py-2 bg-white border border-soft-gray-200 rounded-lg text-sm font-medium text-soft-gray-700 shadow-sm hover:shadow-md transition-shadow">
                                🏢 Business Profiles
                            </div>
                            <div class="px-4 py-2 bg-white border border-soft-gray-200 rounded-lg text-sm font-medium text-soft-gray-700 shadow-sm hover:shadow-md transition-shadow">
                                📦 Product Catalog
                            </div>
                            <div class="px-4 py-2 bg-white border border-soft-gray-200 rounded-lg text-sm font-medium text-soft-gray-700 shadow-sm hover:shadow-md transition-shadow">
                                🤝 Alumni Network
                            </div>
                        </div>
                    </div>
                    
                    {{-- Right: Login Form --}}
                    <div class="bg-white border border-soft-gray-100 rounded-2xl p-8 shadow-xl relative overflow-hidden">
                        {{-- Decorative corner elements --}}
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-uco-orange-100 to-uco-yellow-100 rounded-bl-full opacity-40"></div>
                        <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-uco-yellow-100 to-uco-orange-100 rounded-tr-full opacity-30"></div>
                        
                        <div class="mb-6 relative z-10">
                            <h2 class="text-2xl font-bold text-soft-gray-900 mb-1">Sign in</h2>
                            <p class="text-sm text-soft-gray-600">Access your entrepreneurial hub</p>
                        </div>
                        
                        @if (session('status'))
                            <div class="mb-5 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-800">
                                {{ session('status') }}
                            </div>
                        @endif
                        
                        <form method="POST" action="/login" class="space-y-4 relative z-10">
                            @csrf
                            
                            <div>
                                <label for="email" class="block text-sm font-semibold text-soft-gray-700 mb-2">Email</label>
                                <input id="email" 
                                       type="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required 
                                       autofocus 
                                       autocomplete="username"
                                       class="w-full px-4 py-3 bg-soft-gray-50 border border-soft-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-soft-gray-400 focus:border-transparent transition-all @error('email') border-red-500 @enderror"
                                       placeholder="name@example.com">
                                @error('email')
                                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label for="password" class="block text-sm font-semibold text-soft-gray-700">Password</label>
                                    @if (Route::has('password.request'))
                                        <a href="/forgot-password" class="text-xs font-medium text-soft-gray-600 hover:text-soft-gray-900 transition-colors">
                                            Forgot password?
                                        </a>
                                    @endif
                                </div>
                                <input id="password" 
                                       type="password" 
                                       name="password" 
                                       required 
                                       autocomplete="current-password"
                                       class="w-full px-4 py-3 bg-soft-gray-50 border border-soft-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-soft-gray-400 focus:border-transparent transition-all @error('password') border-red-500 @enderror"
                                       placeholder="Enter your password">
                                @error('password')
                                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="flex items-center">
                                <input id="remember_me" 
                                       type="checkbox" 
                                       name="remember"
                                       class="w-4 h-4 text-soft-gray-900 border-soft-gray-300 rounded focus:ring-soft-gray-500">
                                <label for="remember_me" class="ml-2 text-sm text-soft-gray-600">Remember me</label>
                            </div>
                            
                            <button type="submit" 
                                    class="w-full py-3 bg-soft-gray-900 text-white text-sm font-semibold rounded-xl hover:bg-soft-gray-800 hover:shadow-lg transition-all duration-200">
                                Sign in to Dashboard
                            </button>
                        </form>
                        
                        {{-- Back to Home Button --}}
                        <div class="mt-4 pt-4 border-t border-soft-gray-100 relative z-10">
                            <a href="/businesses" 
                               class="block w-full py-3 text-center bg-soft-gray-50 text-soft-gray-700 text-sm font-medium rounded-xl border border-soft-gray-200 hover:bg-soft-gray-100 hover:border-soft-gray-300 transition-all duration-200">
                                Browse as Guest
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Floating Decorative Elements -->
            <div class="absolute inset-0 pointer-events-none overflow-hidden">
                <!-- Floating Circle 1 -->
                <div class="absolute top-20 left-[10%] w-16 h-16 border-2 border-uco-orange-300 rounded-full opacity-40 animate-float-slow"></div>
                
                <!-- Floating Square 2 -->
                <div class="absolute top-40 right-[15%] w-12 h-12 border-2 border-uco-yellow-300 rotate-45 opacity-30 animate-float-slower"></div>
                
                <!-- Floating Circle 3 -->
                <div class="absolute bottom-32 left-[20%] w-20 h-20 border-2 border-uco-orange-200 rounded-full opacity-30 animate-float-medium"></div>
                
                <!-- Floating Triangle 4 -->
                <svg class="absolute top-[60%] right-[8%] w-14 h-14 opacity-25 animate-float-slow" viewBox="0 0 100 100">
                    <polygon points="50,10 90,90 10,90" fill="none" stroke="currentColor" stroke-width="3" class="text-uco-yellow-400"/>
                </svg>
                
                <!-- Floating Squiggle 5 -->
                <svg class="absolute bottom-20 right-[25%] w-24 h-16 opacity-20 animate-float-slower" viewBox="0 0 120 80">
                    <path d="M10,40 Q30,20 50,40 T90,40" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" class="text-uco-orange-300"/>
                </svg>
                
                <!-- Floating Star 6 -->
                <svg class="absolute top-[30%] left-[5%] w-10 h-10 opacity-25 animate-float-medium" viewBox="0 0 51 48">
                    <path d="M25.5,0 L31,18 L50,18 L35,29 L40,48 L25.5,37 L11,48 L16,29 L1,18 L20,18 Z" fill="none" stroke="currentColor" stroke-width="2" class="text-uco-yellow-300"/>
                </svg>
                
                <!-- Small dots scattered -->
                <div class="absolute top-[25%] right-[30%] w-2 h-2 bg-uco-orange-300 rounded-full opacity-40 animate-pulse-slow"></div>
                <div class="absolute top-[70%] left-[35%] w-2 h-2 bg-uco-yellow-300 rounded-full opacity-40 animate-pulse-slower"></div>
                <div class="absolute bottom-[40%] right-[12%] w-2 h-2 bg-uco-orange-200 rounded-full opacity-30 animate-pulse-slow"></div>
            </div>
        </main>
    </div>
    
    <style>
        @keyframes float-slow {
            0%, 100% { transform: translateY(0px) translateX(0px); }
            50% { transform: translateY(-20px) translateX(10px); }
        }
        
        @keyframes float-slower {
            0%, 100% { transform: translateY(0px) translateX(0px) rotate(45deg); }
            50% { transform: translateY(-25px) translateX(-10px) rotate(50deg); }
        }
        
        @keyframes float-medium {
            0%, 100% { transform: translateY(0px) translateX(0px); }
            50% { transform: translateY(-15px) translateX(-15px); }
        }
        
        @keyframes pulse-slow {
            0%, 100% { opacity: 0.4; transform: scale(1); }
            50% { opacity: 0.2; transform: scale(1.2); }
        }
        
        @keyframes pulse-slower {
            0%, 100% { opacity: 0.3; transform: scale(1); }
            50% { opacity: 0.15; transform: scale(1.3); }
        }
        
        .animate-float-slow {
            animation: float-slow 8s ease-in-out infinite;
        }
        
        .animate-float-slower {
            animation: float-slower 12s ease-in-out infinite;
        }
        
        .animate-float-medium {
            animation: float-medium 10s ease-in-out infinite;
        }
        
        .animate-pulse-slow {
            animation: pulse-slow 4s ease-in-out infinite;
        }
        
        .animate-pulse-slower {
            animation: pulse-slower 6s ease-in-out infinite;
        }
    </style>
</body>
</html>
