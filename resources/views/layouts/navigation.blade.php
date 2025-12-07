<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Left: Logo & Brand -->
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                    <x-application-logo class="h-10 w-auto fill-current text-orange-500 group-hover:text-orange-600 transition duration-150" />
                    <div class="hidden md:block">
                        <h1 class="font-bold text-lg text-gray-800 leading-tight">UCO Platform</h1>
                        <p class="text-xs text-gray-500">Student & Alumni Community</p>
                    </div>
                </a>
            </div>

            <!-- Center: Main Navigation Links (Desktop) -->
            <div class="hidden md:flex items-center space-x-2">
                {{-- Dashboard (All Authenticated Users) --}}
                @auth
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-md transition duration-150
                              {{ request()->routeIs('dashboard') 
                                  ? 'bg-orange-50 text-orange-600' 
                                  : 'text-gray-700 hover:bg-orange-50 hover:text-orange-600' }}">
                        <i class="bi bi-speedometer2"></i>
                        Dashboard
                    </a>
                @endauth

                {{-- ✅ UPDATED: Browse Businesses (ONLY link for all users) --}}
                <a href="{{ route('businesses.index') }}" 
                   class="flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-md transition duration-150
                          {{ request()->routeIs('businesses.index') || request()->routeIs('businesses.show')
                              ? 'bg-orange-50 text-orange-600' 
                              : 'text-gray-700 hover:bg-orange-50 hover:text-orange-600' }}">
                    <i class="bi bi-shop"></i>
                    Browse Businesses
                </a>

                {{-- Admin Panel Dropdown (Admin Only) --}}
                @auth
                    @if(auth()->user()->isAdmin())
                        <div class="relative" x-data="{ adminOpen: false }" @click.away="adminOpen = false">
                            <button @click="adminOpen = !adminOpen" 
                                    class="flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-md transition duration-150
                                           {{ request()->routeIs('users.*') || request()->routeIs('business-types.*') || request()->routeIs('contact-types.*')
                                               ? 'bg-orange-50 text-orange-600' 
                                               : 'text-gray-700 hover:bg-orange-50 hover:text-orange-600' }}">
                                <i class="bi bi-shield-check"></i>
                                Admin Panel
                                <i class="bi bi-chevron-down text-xs" :class="{'rotate-180': adminOpen}"></i>
                            </button>

                            <div x-show="adminOpen"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute z-50 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                                 style="display: none;">
                                <div class="py-1">
                                    <a href="{{ route('businesses.index') }}" 
                                       class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition duration-150">
                                        <i class="bi bi-briefcase-fill"></i>
                                        Manage All Businesses
                                    </a>

                                    <div class="border-t border-gray-200 my-1"></div>

                                    <a href="{{ route('users.index') }}" 
                                       class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition duration-150">
                                        <i class="bi bi-people"></i>
                                        Manage Users
                                    </a>
                                    <a href="{{ route('business-types.index') }}" 
                                       class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition duration-150">
                                        <i class="bi bi-tags"></i>
                                        Business Types
                                    </a>
                                    <a href="{{ route('contact-types.index') }}" 
                                       class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition duration-150">
                                        <i class="bi bi-telephone"></i>
                                        Contact Types
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                @endauth
            </div>

            <!-- Right: User Menu -->
            <div class="hidden md:flex items-center gap-3">
                @auth
                    <x-dropdown align="right" width="56">
                        <x-slot name="trigger">
                            <button class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-orange-500 to-yellow-500 hover:from-orange-600 hover:to-yellow-600 text-white rounded-lg transition duration-150 shadow-sm">
                                <i class="bi bi-person-circle text-lg"></i>
                                <div class="text-left hidden lg:block">
                                    <p class="text-sm font-semibold">{{ Auth::user()->name }}</p>
                                    <p class="text-xs opacity-90">{{ ucfirst(Auth::user()->role) }}</p>
                                </div>
                                <i class="bi bi-chevron-down text-xs"></i>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-3 bg-gradient-to-r from-orange-50 to-yellow-50 border-b border-gray-200">
                                <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-600">{{ Auth::user()->email }}</p>
                                <span class="inline-block mt-1 px-2 py-0.5 text-xs rounded-full 
                                    @if(Auth::user()->isAdmin()) bg-indigo-100 text-indigo-800
                                    @elseif(Auth::user()->isStudent()) bg-green-100 text-green-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                    {{ ucfirst(Auth::user()->role) }}
                                </span>
                            </div>

                            <x-dropdown-link :href="route('profile.edit')">
                                <i class="bi bi-person me-2"></i>
                                My Profile
                            </x-dropdown-link>

                            <div class="border-t border-gray-100"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="text-red-600 hover:bg-red-50">
                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    Log Out
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-orange-600 transition duration-150">
                        <i class="bi bi-box-arrow-in-right me-1"></i>
                        Login
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-orange-500 to-yellow-500 hover:from-orange-600 hover:to-yellow-600 rounded-lg shadow-sm transition duration-150">
                            <i class="bi bi-person-plus me-1"></i>
                            Register
                        </a>
                    @endif
                @endauth
            </div>

            <!-- Mobile Hamburger -->
            <div class="flex md:hidden items-center">
                <button @click="open = ! open" class="p-2 rounded-md text-gray-500 hover:text-orange-600 hover:bg-orange-50 focus:outline-none transition duration-150">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden border-t border-gray-200 bg-gray-50">
        <div class="px-2 pt-2 pb-3 space-y-1">
            @auth
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    <i class="bi bi-speedometer2 me-2"></i>
                    Dashboard
                </x-responsive-nav-link>
            @endauth

            <x-responsive-nav-link :href="route('businesses.index')" :active="request()->routeIs('businesses.index') || request()->routeIs('businesses.show')">
                <i class="bi bi-shop me-2"></i>
                Browse Businesses
            </x-responsive-nav-link>

            {{-- Admin Panel Section (Mobile) --}}
            @auth
                @if(auth()->user()->isAdmin())
                    <div class="pt-2 pb-1 border-t border-gray-300">
                        <div class="px-4 py-2">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Admin Panel</p>
                        </div>

                        <x-responsive-nav-link :href="route('businesses.index')" :active="request()->routeIs('businesses.index')">
                            <i class="bi bi-briefcase-fill me-2"></i>
                            Manage All Businesses
                        </x-responsive-nav-link>
                        
                        <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                            <i class="bi bi-people me-2"></i>
                            Manage Users
                        </x-responsive-nav-link>

                        <x-responsive-nav-link :href="route('business-types.index')" :active="request()->routeIs('business-types.*')">
                            <i class="bi bi-tags me-2"></i>
                            Business Types
                        </x-responsive-nav-link>

                        <x-responsive-nav-link :href="route('contact-types.index')" :active="request()->routeIs('contact-types.*')">
                            <i class="bi bi-telephone me-2"></i>
                            Contact Types
                        </x-responsive-nav-link>
                    </div>
                @endif
            @endauth
        </div>

        {{-- Mobile User Section --}}
        @auth
            <div class="pt-4 pb-3 border-t border-gray-300">
                <div class="px-4 mb-3">
                    <p class="text-base font-semibold text-gray-800 flex items-center gap-2">
                        {{ Auth::user()->name }}
                        <span class="px-2 py-0.5 text-xs rounded-full 
                            @if(Auth::user()->isAdmin()) bg-indigo-100 text-indigo-800
                            @elseif(Auth::user()->isStudent()) bg-green-100 text-green-800
                            @else bg-blue-100 text-blue-800 @endif">
                            {{ ucfirst(Auth::user()->role) }}
                        </span>
                    </p>
                    <p class="text-sm text-gray-600">{{ Auth::user()->email }}</p>
                </div>

                <div class="space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        <i class="bi bi-person me-2"></i>
                        Profile
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="text-red-600">
                            <i class="bi bi-box-arrow-right me-2"></i>
                            Log Out
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</nav>
