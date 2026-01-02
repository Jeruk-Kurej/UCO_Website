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
    
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
    </style>
</head>
<body class="antialiased bg-gray-50">
    <div class="h-screen flex flex-col overflow-hidden">
     
        
        {{-- Main Content --}}
        <main class="flex-1 flex items-center relative overflow-hidden">
            <div class="max-w-6xl mx-auto px-6 w-full relative z-10">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    {{-- Left: Content --}}
                    <div class="space-y-6 relative">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-full">
                            <span class="w-1.5 h-1.5 bg-purple-600 rounded-full"></span>
                            <span class="text-xs font-medium text-gray-700">For UC Students & Alumni</span>
                        </div>
                        
                        <h1 class="text-5xl font-bold text-gray-900 leading-tight tracking-tight">
                            Business directory<br>
                            for <span class="text-purple-600">entrepreneurs</span>
                        </h1>
                        
                        <p class="text-lg text-gray-600 leading-relaxed max-w-lg">
                            Connect with Universitas Ciputra's entrepreneurial community. Discover businesses, showcase your products, and grow your network.
                        </p>
                        
                        {{-- Feature Pills --}}
                        <div class="flex flex-wrap gap-2 pt-2">
                            <div class="px-3 py-1.5 bg-white border border-gray-200 rounded-full text-xs font-medium text-gray-700">
                                Business Profiles
                            </div>
                            <div class="px-3 py-1.5 bg-white border border-gray-200 rounded-full text-xs font-medium text-gray-700">
                                Product Catalog
                            </div>
                            <div class="px-3 py-1.5 bg-white border border-gray-200 rounded-full text-xs font-medium text-gray-700">
                                Alumni Network
                            </div>
                        </div>
                    </div>
                    
                    {{-- Right: Login Form --}}
                    <div class="bg-white border border-gray-200 rounded-2xl p-8 shadow-sm relative overflow-hidden">
                        {{-- Decorative corner element --}}
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-50 to-transparent rounded-bl-full opacity-50"></div>
                        
                        <div class="mb-6 relative z-10">
                            <h2 class="text-xl font-bold text-gray-900 mb-1">Sign in</h2>
                            <p class="text-sm text-gray-600">Access your account</p>
                        </div>
                        
                        @if (session('status'))
                            <div class="mb-5 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-800">
                                {{ session('status') }}
                            </div>
                        @endif
                        
                        <form method="POST" action="{{ route('login') }}" class="space-y-4 relative z-10">
                            @csrf
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                                <input id="email" 
                                       type="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required 
                                       autofocus 
                                       autocomplete="username"
                                       class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all @error('email') border-red-500 @enderror"
                                       placeholder="name@example.com">
                                @error('email')
                                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" class="text-xs font-medium text-gray-600 hover:text-gray-900 transition-colors">
                                            Forgot?
                                        </a>
                                    @endif
                                </div>
                                <input id="password" 
                                       type="password" 
                                       name="password" 
                                       required 
                                       autocomplete="current-password"
                                       class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all @error('password') border-red-500 @enderror"
                                       placeholder="Enter password">
                                @error('password')
                                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="flex items-center">
                                <input id="remember_me" 
                                       type="checkbox" 
                                       name="remember"
                                       class="w-4 h-4 text-gray-900 border-gray-300 rounded focus:ring-gray-900">
                                <label for="remember_me" class="ml-2 text-sm text-gray-600">Remember me</label>
                            </div>
                            
                            <button type="submit" 
                                    class="w-full py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                                Sign in
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
