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
            <div class="hidden md:flex items-center space-x-1">
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="px-3 py-2 rounded-md hover:bg-orange-50">
                    <i class="bi bi-speedometer2 me-1"></i>
                    Dashboard
                </x-nav-link>

                <x-nav-link :href="route('businesses.index')" :active="request()->routeIs('businesses.*')" class="px-3 py-2 rounded-md hover:bg-orange-50">
                    <i class="bi bi-briefcase me-1"></i>
                    Businesses
                </x-nav-link>

                @auth
                    @if(Auth::user()->isAdmin())
                        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')" class="px-3 py-2 rounded-md hover:bg-orange-50">
                            <i class="bi bi-people me-1"></i>
                            Users
                        </x-nav-link>

                        <x-nav-link :href="route('business-types.index')" :active="request()->routeIs('business-types.*')" class="px-3 py-2 rounded-md hover:bg-orange-50">
                            <i class="bi bi-tags me-1"></i>
                            Categories
                        </x-nav-link>
                    @endif
                @endauth
            </div>

            <!-- Right: User Menu / Auth Buttons -->
            <div class="hidden md:flex items-center gap-3">
                @auth
                    <!-- User Dropdown -->
                    <x-dropdown align="right" width="56">
                        <x-slot name="trigger">
                            <button class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-orange-500 to-yellow-500 hover:from-orange-600 hover:to-yellow-600 text-white rounded-lg transition duration-150 shadow-sm">
                                <i class="bi bi-person-circle text-lg"></i>
                                <div class="text-left hidden lg:block">
                                    <p class="text-sm font-semibold">{{ Auth::user()->name }}</p>
                                    <p class="text-xs opacity-90">
                                        @if(Auth::user()->isAdmin())
                                            Admin
                                        @elseif(Auth::user()->isStudent())
                                            Student
                                        @else
                                            Alumni
                                        @endif
                                    </p>
                                </div>
                                <i class="bi bi-chevron-down text-xs"></i>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <!-- User Info Header -->
                            <div class="px-4 py-3 bg-gradient-to-r from-orange-50 to-yellow-50 border-b border-gray-200">
                                <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-600">{{ Auth::user()->email }}</p>
                                <span class="inline-block mt-1 px-2 py-0.5 text-xs rounded-full 
                                    @if(Auth::user()->isAdmin()) bg-indigo-100 text-indigo-800
                                    @elseif(Auth::user()->isStudent()) bg-green-100 text-green-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                    {{ Auth::user()->role }}
                                </span>
                            </div>

                            <x-dropdown-link :href="route('profile.edit')">
                                <i class="bi bi-person me-2"></i>
                                My Profile
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('businesses.index')">
                                <i class="bi bi-briefcase me-2"></i>
                                My Businesses
                            </x-dropdown-link>

                            <div class="border-t border-gray-100"></div>

                            <!-- Logout -->
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
                    <!-- Guest Buttons -->
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
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                <i class="bi bi-speedometer2 me-2"></i>
                Dashboard
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('businesses.index')" :active="request()->routeIs('businesses.*')">
                <i class="bi bi-briefcase me-2"></i>
                Businesses
            </x-responsive-nav-link>

            @auth
                @if(Auth::user()->isAdmin())
                    <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                        <i class="bi bi-people me-2"></i>
                        Users
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('business-types.index')" :active="request()->routeIs('business-types.*')">
                        <i class="bi bi-tags me-2"></i>
                        Categories
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <!-- Mobile User Section -->
        @auth
            <div class="pt-4 pb-3 border-t border-gray-300">
                <div class="px-4 mb-3">
                    <p class="text-base font-semibold text-gray-800">{{ Auth::user()->name }}</p>
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
