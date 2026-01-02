<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>UCO Platform - Student & Alumni Business Directory (Bootstrap Test)</title>
    
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Inter', system-ui, sans-serif;
            background: linear-gradient(135deg, #fff5eb 0%, #fffef0 100%);
            min-height: 100vh;
        }
        .hero-section {
            padding: 100px 0;
        }
        .badge-custom {
            background: linear-gradient(135deg, #ff8c2e 0%, #ffd633 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            display: inline-block;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Bootstrap Test: Simple Hero Section -->
    <div class="container hero-section">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <span class="badge-custom">✅ Bootstrap CSS Loaded Successfully!</span>
                <h1 class="display-3 fw-bold mb-4">UCO Platform Test</h1>
                <p class="lead text-muted mb-4">If you can see this styled page with gradient background, Bootstrap buttons, and proper spacing, then CSS is loading correctly!</p>
                
                <div class="d-flex gap-3 mb-5">
                    <a href="/login" class="btn btn-warning btn-lg">Sign In</a>
                    <a href="/register" class="btn btn-outline-secondary btn-lg">Register</a>
                </div>
                
                <div class="alert alert-info">
                    <strong>Test Result:</strong> If this blue alert box is visible with proper styling, external CSS works fine!
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card shadow-lg">
                    <div class="card-body p-5">
                        <h3 class="card-title mb-3">Quick Login Test</h3>
                        <form method="POST" action="/login">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control form-control-lg" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control form-control-lg" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100">Sign In</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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
                        
                        <div class="mt-6 pt-6 border-t border-soft-gray-100 text-center relative z-10">
                            <p class="text-sm text-soft-gray-600">
                                Don't have an account? 
                                <a href="/register" class="font-semibold text-soft-gray-900 hover:text-soft-gray-700 transition-colors">Sign up</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
