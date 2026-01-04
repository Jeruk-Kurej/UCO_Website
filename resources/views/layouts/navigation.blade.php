<nav class="bg-white border-b border-soft-gray-100 shadow-sm sticky top-0 z-50">
    <!-- Subtle Orange Accent Line -->
    <div class="h-1 bg-gradient-to-r from-uco-orange-500 via-uco-yellow-500 to-uco-orange-500"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            {{-- Logo & Brand --}}
            <div class="flex items-center">
                <a href="/dashboard" class="flex items-center gap-3">
                    <img src="{{ asset('images/Logo UCO.png') }}" alt="UCO Logo" class="w-9 h-9 object-contain">
                    <span class="text-lg font-bold text-soft-gray-900">UC Online</span>
                </a>
            </div>

            {{-- Navigation Links (Desktop) --}}
            <div class="hidden md:flex items-center space-x-8">
                @auth
                    <a href="/dashboard" 
                       class="text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-soft-gray-900 font-semibold' : 'text-soft-gray-600 hover:text-soft-gray-900' }} transition">
                        Featured
                    </a>

                    <a href="/businesses" 
                       class="text-sm font-medium {{ request()->routeIs('businesses.index') ? 'text-soft-gray-900 font-semibold' : 'text-soft-gray-600 hover:text-soft-gray-900' }} transition">
                        Business
                    </a>

                    {{-- Testimonies Dropdown --}}
                    <div class="relative group">
                        <button class="text-sm font-medium {{ request()->routeIs('uc-testimonies.*') || request()->routeIs('ai-analyses.*') ? 'text-soft-gray-900 font-semibold' : 'text-soft-gray-600 group-hover:text-soft-gray-900' }} transition flex items-center gap-1">
                            Testimonies
                            <svg class="w-4 h-4 group-hover:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div class="absolute left-0 mt-2 w-52 bg-white rounded-xl shadow-xl border border-soft-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
                            <div class="py-2">
                                <a href="{{ route('uc-testimonies.index') }}" class="block px-4 py-2.5 text-sm font-medium text-soft-gray-700 hover:bg-soft-gray-50 hover:text-soft-gray-900 transition">
                                    View Testimonies
                                </a>
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('ai-analyses.index') }}" class="block px-4 py-2.5 text-sm font-medium text-soft-gray-700 hover:bg-soft-gray-50 hover:text-soft-gray-900 transition">
                                        Testimonial Review
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if(auth()->user()->isAdmin())
                        {{-- Admin Dropdown --}}
                        <div class="relative group">
                            <button class="text-sm font-medium {{ request()->routeIs('users.*') || request()->routeIs('business-types.*') || request()->routeIs('contact-types.*') ? 'text-soft-gray-900 font-semibold' : 'text-soft-gray-600 group-hover:text-soft-gray-900' }} transition flex items-center gap-1">
                                Admin Panel
                                <svg class="w-4 h-4 group-hover:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-xl border border-soft-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
                                <div class="py-2">
                                    <a href="/users" class="block px-4 py-2.5 text-sm font-medium text-soft-gray-700 hover:bg-soft-gray-50 hover:text-soft-gray-900 transition">
                                        Manage Users
                                    </a>
                                    <a href="/business-types" class="block px-4 py-2.5 text-sm font-medium text-soft-gray-700 hover:bg-soft-gray-50 hover:text-soft-gray-900 transition">
                                        Business Types
                                    </a>
                                    <a href="/contact-types" class="block px-4 py-2.5 text-sm font-medium text-soft-gray-700 hover:bg-soft-gray-50 hover:text-soft-gray-900 transition">
                                        Contact Types
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Profile Dropdown --}}
                    <div class="relative group">
                        <button class="text-sm font-medium text-soft-gray-700 group-hover:text-soft-gray-900 transition flex items-center gap-2 px-3 py-2 rounded-lg group-hover:bg-soft-gray-50">
                            @if(auth()->user()->profile_photo_url)
                                <img src="{{ asset('storage/' . auth()->user()->profile_photo_url) }}?t={{ auth()->user()->updated_at?->timestamp ?? time() }}" 
                                     alt="Profile" 
                                     class="w-7 h-7 rounded-lg object-cover">
                            @else
                                <div class="w-7 h-7 bg-gradient-to-br from-uco-orange-500 to-uco-yellow-500 rounded-lg flex items-center justify-center text-white text-xs font-bold">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                            @endif
                            {{ auth()->user()->name }}
                            <svg class="w-4 h-4 group-hover:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div class="absolute right-0 mt-2 w-60 bg-white rounded-xl shadow-xl border border-soft-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
                            <div class="px-4 py-3 border-b border-soft-gray-100">
                                <p class="text-sm font-semibold text-soft-gray-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-soft-gray-600 mt-0.5">{{ auth()->user()->email }}</p>
                                <span class="inline-block mt-2 px-2.5 py-1 text-xs font-medium rounded-lg 
                                    {{ auth()->user()->isAdmin() ? 'bg-soft-gray-100 text-soft-gray-800' : (auth()->user()->isStudent() ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                    {{ ucfirst(auth()->user()->role) }}
                                </span>
                            </div>
                            <div class="py-2">
                                <a href="/profile" class="block px-4 py-2.5 text-sm font-medium text-soft-gray-700 hover:bg-soft-gray-50 hover:text-soft-gray-900 transition">
                                    My Profile
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 transition">
                                        Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="/" 
                       class="px-4 py-2 text-sm font-medium text-white bg-soft-gray-900 rounded-lg hover:bg-soft-gray-800 hover:shadow-lg transition-all">
                        Login
                    </a>
                @endauth
            </div>

            {{-- Mobile Menu Button --}}
            <div class="flex md:hidden">
                <button @click="open = ! open" 
                        class="p-2 rounded-lg text-soft-gray-700 hover:text-soft-gray-900 hover:bg-soft-gray-50">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-data="{ open: false }" :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden border-t border-soft-gray-100 bg-white">
        <div class="px-4 py-3 space-y-1">
            @auth
                <a href="/dashboard" 
                   class="block py-2.5 px-3 text-sm font-medium rounded-lg {{ request()->routeIs('dashboard') ? 'bg-soft-gray-100 text-soft-gray-900' : 'text-soft-gray-700 hover:bg-soft-gray-50' }}">
                    Featured
                </a>
                <a href="/businesses" 
                   class="block py-2.5 px-3 text-sm font-medium rounded-lg {{ request()->routeIs('businesses.index') ? 'bg-soft-gray-100 text-soft-gray-900' : 'text-soft-gray-700 hover:bg-soft-gray-50' }}">
                    Business
                </a>
                
                {{-- Testimonies Section --}}
                <div class="pt-2 border-t border-soft-gray-100">
                    <p class="px-3 py-2 text-xs font-semibold text-soft-gray-500 uppercase tracking-wider">Testimonies</p>
                    <a href="{{ route('uc-testimonies.index') }}" class="block py-2.5 px-3 text-sm font-medium text-soft-gray-700 hover:bg-soft-gray-50 hover:text-soft-gray-900 rounded-lg {{ request()->routeIs('uc-testimonies.*') ? 'bg-soft-gray-100 text-soft-gray-900' : '' }}">
                        View Testimonies
                    </a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('ai-analyses.index') }}" class="block py-2.5 px-3 text-sm font-medium text-soft-gray-700 hover:bg-soft-gray-50 hover:text-soft-gray-900 rounded-lg {{ request()->routeIs('ai-analyses.*') ? 'bg-soft-gray-100 text-soft-gray-900' : '' }}">
                            Testimonial Review
                        </a>
                    @endif
                </div>

                @if(auth()->user()->isAdmin())
                    <div class="pt-2 border-t border-soft-gray-100">
                        <p class="px-3 py-2 text-xs font-semibold text-soft-gray-500 uppercase tracking-wider">Admin Panel</p>
                        <a href="/users" class="block py-2.5 px-3 text-sm font-medium text-soft-gray-700 hover:bg-soft-gray-50 hover:text-soft-gray-900 rounded-lg">
                            Manage Users
                        </a>
                        <a href="/business-types" class="block py-2.5 px-3 text-sm font-medium text-soft-gray-700 hover:bg-soft-gray-50 hover:text-soft-gray-900 rounded-lg">
                            Business Types
                        </a>
                        <a href="/contact-types" class="block py-2.5 px-3 text-sm font-medium text-soft-gray-700 hover:bg-soft-gray-50 hover:text-soft-gray-900 rounded-lg">
                            Contact Types
                        </a>
                    </div>
                @endif
                <div class="pt-2 mt-2 border-t border-soft-gray-100">
                    <div class="flex items-center gap-2 px-3 py-2">
                        @if(auth()->user()->profile_photo_url)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo_url) }}?t={{ auth()->user()->updated_at?->timestamp ?? time() }}" 
                                 alt="Profile" 
                                 class="w-8 h-8 rounded-lg object-cover">
                        @else
                            <div class="w-8 h-8 bg-gradient-to-br from-uco-orange-500 to-uco-yellow-500 rounded-lg flex items-center justify-center text-white text-xs font-bold">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <p class="text-xs font-semibold text-soft-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-soft-gray-600">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <a href="/profile" class="block py-2.5 px-3 text-sm font-medium text-soft-gray-700 hover:bg-soft-gray-50 hover:text-soft-gray-900 rounded-lg">
                        Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left py-2.5 px-3 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg">
                            Log Out
                        </button>
                    </form>
                </div>
            @else
                <a href="/" class="block py-2.5 px-3 text-sm font-medium text-white bg-soft-gray-900 rounded-lg text-center hover:bg-soft-gray-800">
                    Login
                </a>
            @endauth
        </div>
    </div>
</nav>
