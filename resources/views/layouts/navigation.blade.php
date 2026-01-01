<nav class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            {{-- Logo & Brand --}}
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <x-application-logo class="h-12 w-auto fill-current text-orange-500" />
                    <div>
                        <h1 class="font-bold text-xl text-gray-900">UNIVERSITAS CIPUTRA</h1>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Creating World Class Entrepreneurs</p>
                    </div>
                </a>
            </div>

            {{-- Navigation Links (Desktop) --}}
            <div class="hidden md:flex items-center space-x-8">
                {{-- ✅ CHANGED: Larger font size (text-base instead of text-sm) --}}
                <a href="{{ route('dashboard') }}" 
                   class="text-base font-bold {{ request()->routeIs('dashboard') ? 'text-orange-500' : 'text-gray-700 hover:text-orange-500' }} transition duration-150">
                    Home
                </a>

                @auth
                    <a href="{{ route('businesses.index') }}" 
                       class="text-base font-bold {{ request()->routeIs('businesses.*') ? 'text-orange-500' : 'text-gray-700 hover:text-orange-500' }} transition duration-150">
                        Business
                    </a>

                    @if(auth()->user()->isAdmin())
                        {{-- Admin Dropdown (Hover) --}}
                        <div class="relative group">
                            <button class="text-base font-bold {{ request()->routeIs('users.*') || request()->routeIs('business-types.*') || request()->routeIs('contact-types.*') || request()->routeIs('ai-analyses.*') ? 'text-orange-500' : 'text-gray-700 group-hover:text-orange-500' }} transition duration-150 flex items-center gap-1">
                                Admin Panel
                                <svg class="w-4 h-4 group-hover:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            {{-- Dropdown appears on hover --}}
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                <a href="{{ route('users.index') }}" class="block px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:text-orange-500">
                                    Manage Users
                                </a>
                                <a href="{{ route('business-types.index') }}" class="block px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:text-orange-500">
                                    Business Types
                                </a>
                                <a href="{{ route('contact-types.index') }}" class="block px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:text-orange-500">
                                    Contact Types
                                </a>
                                <a href="{{ route('ai-analyses.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:text-purple-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    AI Moderation
                                </a>
                            </div>
                        </div>
                    @endif
                    

                    {{-- ✅ CHANGED: Profile Dropdown WITHOUT Avatar Icon --}}
                    <div class="relative group">
                        <button class="text-base font-bold text-gray-700 group-hover:text-orange-500 transition duration-150 flex items-center gap-1">
                            {{ Auth::user()->name }}
                            <svg class="w-4 h-4 group-hover:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        {{-- Dropdown appears on hover --}}
                        <div class="absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg py-1 border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-bold text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-600">{{ Auth::user()->email }}</p>
                                <span class="inline-block mt-1 px-2 py-0.5 text-xs font-semibold rounded-full 
                                    {{ Auth::user()->isAdmin() ? 'bg-purple-100 text-purple-800' : (Auth::user()->isStudent() ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                    {{ ucfirst(Auth::user()->role) }}
                                </span>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:text-orange-500">
                                My Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-50">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('businesses.index') }}" 
                       class="text-base font-bold text-gray-700 hover:text-orange-500 transition duration-150">
                        About Us
                    </a>
                    <a href="{{ route('businesses.index') }}" 
                       class="text-base font-bold text-gray-700 hover:text-orange-500 transition duration-150">
                        Program
                    </a>
                    <a href="{{ route('login') }}" 
                       class="text-base font-bold text-gray-700 hover:text-orange-500 transition duration-150">
                        Contact
                    </a>
                @endauth
            </div>

            {{-- Mobile Menu Button --}}
            <div class="flex md:hidden">
                <button @click="open = ! open" 
                        x-data="{ open: false }"
                        class="p-2 rounded-md text-gray-700 hover:text-orange-500 hover:bg-gray-100">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-data="{ open: false }" :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden border-t border-gray-200 bg-white">
        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('dashboard') }}" 
               class="block py-2 text-base font-bold {{ request()->routeIs('dashboard') ? 'text-orange-500' : 'text-gray-700' }}">
                Home
            </a>
            @auth
                <a href="{{ route('businesses.index') }}" 
                   class="block py-2 text-base font-bold {{ request()->routeIs('businesses.*') ? 'text-orange-500' : 'text-gray-700' }}">
                    Program
                </a>
                @if(auth()->user()->isAdmin())
                    <div class="pt-2 border-t border-gray-200">
                        <p class="px-2 py-1 text-xs font-bold text-gray-500 uppercase">Admin</p>
                        <a href="{{ route('users.index') }}" class="block py-2 text-sm font-semibold text-gray-700 hover:text-orange-500">
                            Manage Users
                        </a>
                        <a href="{{ route('business-types.index') }}" class="block py-2 text-sm font-semibold text-gray-700 hover:text-orange-500">
                            Business Types
                        </a>
                        <a href="{{ route('contact-types.index') }}" class="block py-2 text-sm font-semibold text-gray-700 hover:text-orange-500">
                            Contact Types
                        </a>
                        <a href="{{ route('ai-analyses.index') }}" class="flex items-center gap-2 py-2 text-sm font-semibold text-gray-700 hover:text-purple-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            AI Moderation
                        </a>
                    </div>
                @endif
                <div class="pt-2 border-t border-gray-200">
                    <p class="px-2 py-1 text-xs font-bold text-gray-500">{{ Auth::user()->name }}</p>
                    <a href="{{ route('profile.edit') }}" class="block py-2 text-sm font-semibold text-gray-700 hover:text-orange-500">
                        Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left py-2 text-sm font-semibold text-red-600">
                            Log Out
                        </button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}" class="block py-2 text-base font-bold text-gray-700 hover:text-orange-500">
                    Contact
                </a>
            @endauth
        </div>
    </div>
</nav>
