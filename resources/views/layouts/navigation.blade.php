<nav x-data="{ open: false }" class="bg-white border-b border-soft-gray-100 shadow-sm sticky top-0 z-50">
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            {{-- Logo & Brand --}}
            <div class="flex items-center">
                <a href="{{ auth()->check() ? route('dashboard') : route('featured') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/Logo UCO.png') }}" alt="UCO Logo" class="w-9 h-9 object-contain">
                    <span class="text-lg font-bold text-soft-gray-900">UC Online Learning</span>
                </a>
            </div>

            {{-- Navigation Links (Desktop) --}}
            <div class="hidden md:flex items-center space-x-8">
                @php $user = auth()->user(); @endphp

                @if(Auth::check())
                    @if($user->isAdmin())
                        {{-- Admin navigation --}}
                        <a href="{{ route('dashboard') }}" class="text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-soft-gray-900 font-semibold underline decoration-uco-orange-500 decoration-2 underline-offset-8' : 'text-soft-gray-600 hover:text-soft-gray-900' }}">Featured</a>
                        <a href="{{ route('businesses.index') }}" class="text-sm font-medium {{ request()->routeIs('businesses.*') ? 'text-soft-gray-900 font-semibold underline decoration-uco-orange-500 decoration-2 underline-offset-8' : 'text-soft-gray-600 hover:text-soft-gray-900' }}">Businesses</a>
                        
                        {{-- Management Dropdown (Desktop) --}}
                        <div class="relative group">
                            @php $pendingApprovals = \App\Models\Business::pending()->count(); @endphp
                            <button class="text-sm font-medium flex items-center gap-2 transition-all duration-300 {{ request()->routeIs('business-types.*', 'contact-types.*', 'users.*', 'ai-analyses.*', 'uc-testimonies.*', 'admin.business-approvals.*') ? 'text-soft-gray-900 font-semibold underline decoration-uco-orange-500 decoration-2 underline-offset-8' : 'text-soft-gray-600 hover:text-soft-gray-900' }}">
                                <span class="relative">
                                    Management
                                    @if($pendingApprovals > 0)
                                        <span class="absolute -top-1 -right-3 w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                                    @endif
                                </span>
                                <svg class="w-4 h-4 group-hover:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div class="absolute left-0 mt-2 w-72 bg-white rounded-2xl shadow-2xl border border-soft-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 overflow-hidden transform group-hover:translate-y-0 translate-y-2">
                                <div class="p-2 space-y-1">
                                    <a href="{{ route('admin.business-approvals.index') }}" class="flex items-start gap-3 p-3 rounded-xl hover:bg-soft-gray-50 transition-colors {{ request()->routeIs('admin.business-approvals.*') ? 'bg-orange-50' : '' }}">
                                        <div class="w-9 h-9 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center text-lg flex-shrink-0 relative">
                                            <i class="bi bi-check2-square"></i>
                                            @if($pendingApprovals > 0)
                                                <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full animate-pulse border-2 border-white"></span>
                                            @endif
                                        </div>
                                        <div class="flex-grow flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-bold text-soft-gray-900">Business Approvals</p>
                                                <p class="text-xs text-soft-gray-500">Moderate new registrations</p>
                                            </div>
                                            @if($pendingApprovals > 0)
                                                <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[20px] text-center">{{ $pendingApprovals }}</span>
                                            @endif
                                        </div>
                                    </a>
                                    <a href="{{ route('users.index') }}" class="flex items-start gap-3 p-3 rounded-xl hover:bg-soft-gray-50 transition-colors {{ request()->routeIs('users.*') ? 'bg-emerald-50' : '' }}">
                                        <div class="w-9 h-9 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg flex-shrink-0">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-soft-gray-900">Users</p>
                                            <p class="text-xs text-soft-gray-500">Manage platform users</p>
                                        </div>
                                    </a>
                                    <a href="{{ route('ai-analyses.index') }}" class="flex items-start gap-3 p-3 rounded-xl hover:bg-soft-gray-50 transition-colors {{ request()->routeIs('ai-analyses.*') ? 'bg-purple-50' : '' }}">
                                        <div class="w-9 h-9 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center text-lg flex-shrink-0">
                                            <i class="bi bi-robot"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-soft-gray-900">Testimonial Review</p>
                                            <p class="text-xs text-soft-gray-500">AI analysis of platform feedback</p>
                                        </div>
                                    </a>
                                    <a href="{{ route('business-types.index') }}" class="flex items-start gap-3 p-3 rounded-xl hover:bg-soft-gray-50 transition-colors {{ request()->routeIs('business-types.*') ? 'bg-uco-orange-50' : '' }}">
                                        <div class="w-9 h-9 rounded-lg bg-uco-orange-100 text-uco-orange-600 flex items-center justify-center text-lg flex-shrink-0">
                                            <i class="bi bi-tags"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-soft-gray-900">Business Types</p>
                                            <p class="text-xs text-soft-gray-500">Manage industry categories</p>
                                        </div>
                                    </a>
                                    <a href="{{ route('contact-types.index') }}" class="flex items-start gap-3 p-3 rounded-xl hover:bg-soft-gray-50 transition-colors {{ request()->routeIs('contact-types.*') ? 'bg-blue-50' : '' }}">
                                        <div class="w-9 h-9 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-lg flex-shrink-0">
                                            <i class="bi bi-telephone"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-soft-gray-900">Contact Types</p>
                                            <p class="text-xs text-soft-gray-500">Manage contact platforms</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('about') }}" class="text-sm font-medium {{ request()->routeIs('about') ? 'text-soft-gray-900 font-semibold underline decoration-uco-orange-500 decoration-2 underline-offset-8' : 'text-soft-gray-600 hover:text-soft-gray-900' }}">About Us</a>
                    @else
                        {{-- Authenticated Student / Alumni navigation --}}
                        <a href="{{ route('dashboard') }}" class="text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-soft-gray-900 font-semibold underline decoration-uco-orange-500 decoration-2 underline-offset-8' : 'text-soft-gray-600 hover:text-soft-gray-900' }}">Featured</a>
                        <a href="{{ route('businesses.index') }}" class="text-sm font-medium {{ request()->routeIs('businesses.*') ? 'text-soft-gray-900 font-semibold underline decoration-uco-orange-500 decoration-2 underline-offset-8' : 'text-soft-gray-600 hover:text-soft-gray-900' }}">Businesses</a>
                        <a href="{{ route('uc-testimonies.index') }}" class="text-sm font-medium {{ request()->routeIs('uc-testimonies.*') ? 'text-soft-gray-900 font-semibold underline decoration-uco-orange-500 decoration-2 underline-offset-8' : 'text-soft-gray-600 hover:text-soft-gray-900' }}">Testimonies</a>
                        <a href="{{ route('about') }}" class="text-sm font-medium {{ request()->routeIs('about') ? 'text-soft-gray-900 font-semibold underline decoration-uco-orange-500 decoration-2 underline-offset-8' : 'text-soft-gray-600 hover:text-soft-gray-900' }}">About Us</a>
                    @endif

                    {{-- Profile Dropdown --}}
                    <div class="relative group">
                        <button class="text-sm font-medium text-soft-gray-700 group-hover:text-soft-gray-900 transition flex items-center gap-2 px-3 py-2 rounded-lg group-hover:bg-soft-gray-50">
                            @php $profilePhoto = auth()->user()->profile_photo_url; @endphp
                            @if($profilePhoto)
                                <img src="{{ storage_image_url($profilePhoto, ['width' => 64, 'height' => 64, 'crop' => 'thumb', 'quality' => 'auto', 'fetch_format' => 'auto']) }}?t={{ auth()->user()->updated_at?->timestamp ?? time() }}" alt="Profile" class="w-7 h-7 rounded-lg object-cover">
                            @else
                                <div class="w-7 h-7 bg-gradient-to-br from-uco-orange-500 to-uco-yellow-500 rounded-lg flex items-center justify-center text-white text-xs font-bold">{{ substr(auth()->user()->name, 0, 1) }}</div>
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
                                <span class="inline-block mt-2 px-2.5 py-1 text-xs font-medium rounded-lg {{ auth()->user()->isAdmin() ? 'bg-soft-gray-100 text-soft-gray-800' : (auth()->user()->isStudent() ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">{{ ucfirst(auth()->user()->role) }}</span>
                            </div>
                            <div class="py-2">
                                <a href="/profile" class="block px-4 py-2.5 text-sm font-medium text-soft-gray-700 hover:bg-soft-gray-50 hover:text-soft-gray-900 transition">My Profile</a>
                                @unless(auth()->user()->isAdmin())
                                    <a href="{{ route('businesses.my') }}" class="block px-4 py-2.5 text-sm font-medium text-soft-gray-700 hover:bg-soft-gray-50 hover:text-soft-gray-900 transition">My Businesses</a>
                                @endunless
                                <form method="POST" action="{{ route('logout') }}">@csrf
                                    <button type="submit" class="w-full text-left px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 transition">Log Out</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Guest navigation --}}
                    <a href="{{ route('featured') }}" class="text-sm font-medium {{ request()->routeIs('featured') ? 'text-soft-gray-900 font-semibold underline decoration-uco-orange-500 decoration-2 underline-offset-8' : 'text-soft-gray-600 hover:text-soft-gray-900' }}">Featured</a>
                    <a href="{{ route('businesses.index') }}" class="text-sm font-medium {{ request()->routeIs('businesses.*') ? 'text-soft-gray-900 font-semibold underline decoration-uco-orange-500 decoration-2 underline-offset-8' : 'text-soft-gray-600 hover:text-soft-gray-900' }}">Businesses</a>
                    <a href="{{ route('uc-testimonies.index') }}" class="text-sm font-medium {{ request()->routeIs('uc-testimonies.*') ? 'text-soft-gray-900 font-semibold underline decoration-uco-orange-500 decoration-2 underline-offset-8' : 'text-soft-gray-600 hover:text-soft-gray-900' }}">Testimonies</a>
                    <a href="{{ route('about') }}" class="text-sm font-medium {{ request()->routeIs('about') ? 'text-soft-gray-900 font-semibold underline decoration-uco-orange-500 decoration-2 underline-offset-8' : 'text-soft-gray-600 hover:text-soft-gray-900' }}">About Us</a>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('login') }}" class="px-4 py-2.5 text-sm font-medium text-soft-gray-700 hover:text-soft-gray-900 transition">Log in</a>
                    </div>
                @endif
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
    <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden border-t border-soft-gray-100 bg-white">
        <div class="px-4 py-3 space-y-1">
            @php $user = auth()->user(); @endphp
            @if(Auth::check())
                @if($user->isAdmin())
                    <a href="{{ route('dashboard') }}" class="block py-2.5 px-3 text-sm font-medium rounded-xl {{ request()->routeIs('dashboard') ? 'bg-uco-orange-50 text-uco-orange-700 font-bold' : 'text-soft-gray-700 hover:bg-soft-gray-50' }}">Featured</a>
                    <a href="{{ route('businesses.index') }}" class="block py-2.5 px-3 text-sm font-medium rounded-xl {{ request()->routeIs('businesses.*') ? 'bg-uco-orange-50 text-uco-orange-700 font-bold' : 'text-soft-gray-700 hover:bg-soft-gray-50' }}">Businesses</a>
                    
                    <div class="pt-2 pb-1 px-3">
                        <p class="text-[10px] font-bold text-soft-gray-400 uppercase tracking-widest">Management</p>
                    </div>
                    <div class="space-y-1">
                        <a href="{{ route('admin.business-approvals.index') }}" class="flex items-center justify-between py-2.5 px-3 text-sm font-medium rounded-xl {{ request()->routeIs('admin.business-approvals.*') ? 'bg-soft-gray-100 text-soft-gray-900 font-bold border-l-4 border-orange-500' : 'text-soft-gray-600 hover:bg-soft-gray-50' }}">
                            <div class="flex items-center gap-3">
                                <i class="bi bi-check2-square text-orange-500"></i>
                                Business Approvals
                            </div>
                            @if($pendingApprovals > 0)
                                <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full animate-pulse">
                                    {{ $pendingApprovals }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('users.index') }}" class="flex items-center gap-3 py-2.5 px-3 text-sm font-medium rounded-xl {{ request()->routeIs('users.*') ? 'bg-soft-gray-100 text-soft-gray-900 font-bold border-l-4 border-emerald-500' : 'text-soft-gray-600 hover:bg-soft-gray-50' }}">
                            <i class="bi bi-people text-emerald-500"></i>
                            Users
                        </a>
                        <a href="{{ route('ai-analyses.index') }}" class="flex items-center gap-3 py-2.5 px-3 text-sm font-medium rounded-xl {{ request()->routeIs('ai-analyses.*') ? 'bg-soft-gray-100 text-soft-gray-900 font-bold border-l-4 border-purple-500' : 'text-soft-gray-600 hover:bg-soft-gray-50' }}">
                            <i class="bi bi-robot text-purple-500"></i>
                            Testimonial Review
                        </a>
                        <a href="{{ route('business-types.index') }}" class="flex items-center gap-3 py-2.5 px-3 text-sm font-medium rounded-xl {{ request()->routeIs('business-types.*') ? 'bg-soft-gray-100 text-soft-gray-900 font-bold border-l-4 border-uco-orange-500' : 'text-soft-gray-600 hover:bg-soft-gray-50' }}">
                            <i class="bi bi-tags text-uco-orange-500"></i>
                            Business Types
                        </a>
                        <a href="{{ route('contact-types.index') }}" class="flex items-center gap-3 py-2.5 px-3 text-sm font-medium rounded-xl {{ request()->routeIs('contact-types.*') ? 'bg-soft-gray-100 text-soft-gray-900 font-bold border-l-4 border-blue-500' : 'text-soft-gray-600 hover:bg-soft-gray-50' }}">
                            <i class="bi bi-telephone text-blue-500"></i>
                            Contact Types
                        </a>
                    </div>

                    <a href="{{ route('about') }}" class="block py-2.5 px-3 text-sm font-medium rounded-xl {{ request()->routeIs('about') ? 'bg-soft-gray-100 text-soft-gray-900' : 'text-soft-gray-700 hover:bg-soft-gray-50' }} mt-2">About Us</a>
                @else
                    <a href="{{ route('dashboard') }}" class="block py-2.5 px-3 text-sm font-medium rounded-lg {{ request()->routeIs('dashboard') ? 'bg-soft-gray-100 text-soft-gray-900' : 'text-soft-gray-700 hover:bg-soft-gray-50' }}">Dashboard</a>
                    <a href="{{ route('businesses.index') }}" class="block py-2.5 px-3 text-sm font-medium rounded-lg {{ request()->routeIs('businesses.*') ? 'bg-soft-gray-100 text-soft-gray-900' : 'text-soft-gray-700 hover:bg-soft-gray-50' }}">Businesses</a>
                    <a href="{{ route('uc-testimonies.index') }}" class="block py-2.5 px-3 text-sm font-medium rounded-lg {{ request()->routeIs('uc-testimonies.*') ? 'bg-soft-gray-100 text-soft-gray-900' : 'text-soft-gray-700 hover:bg-soft-gray-50' }}">Testimonies</a>
                    <a href="{{ route('about') }}" class="block py-2.5 px-3 text-sm font-medium rounded-lg {{ request()->routeIs('about') ? 'bg-soft-gray-100 text-soft-gray-900' : 'text-soft-gray-700 hover:bg-soft-gray-50' }}">About Us</a>
                @endif

                <div class="pt-2 mt-2 border-t border-soft-gray-100">
                    <div class="flex items-center gap-2 px-3 py-2">
                        @php $profilePhoto = auth()->user()->profile_photo_url; @endphp
                        @if($profilePhoto)
                            <img src="{{ storage_image_url($profilePhoto, ['width' => 80, 'height' => 80, 'crop' => 'thumb', 'quality' => 'auto', 'fetch_format' => 'auto']) }}?t={{ auth()->user()->updated_at?->timestamp ?? time() }}" alt="Profile" class="w-8 h-8 rounded-lg object-cover">
                        @else
                            <div class="w-8 h-8 bg-gradient-to-br from-uco-orange-500 to-uco-yellow-500 rounded-lg flex items-center justify-center text-white text-xs font-bold">{{ substr(auth()->user()->name, 0, 1) }}</div>
                        @endif
                        <div>
                            <p class="text-xs font-semibold text-soft-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-soft-gray-600">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <a href="/profile" class="block py-2.5 px-3 text-sm font-medium text-soft-gray-700 hover:bg-soft-gray-50 hover:text-soft-gray-900 rounded-lg">Profile</a>
                    @unless(auth()->user()->isAdmin())
                        <a href="{{ route('businesses.my') }}" class="block py-2.5 px-3 text-sm font-medium text-soft-gray-700 hover:bg-soft-gray-50 hover:text-soft-gray-900 rounded-lg">My Businesses</a>
                    @endunless
                    <form method="POST" action="{{ route('logout') }}">@csrf
                        <button type="submit" class="w-full text-left py-2.5 px-3 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg">Log Out</button>
                    </form>
                </div>
            @else
                {{-- Guest mobile --}}
                <a href="{{ route('featured') }}" class="block py-2.5 px-3 text-sm font-medium rounded-lg {{ request()->routeIs('featured') ? 'bg-soft-gray-100 text-soft-gray-900' : 'text-soft-gray-700 hover:bg-soft-gray-50' }}">Featured</a>
                <a href="{{ route('businesses.index') }}" class="block py-2.5 px-3 text-sm font-medium rounded-lg {{ request()->routeIs('businesses.*') ? 'bg-soft-gray-100 text-soft-gray-900' : 'text-soft-gray-700 hover:bg-soft-gray-50' }}">Businesses</a>
                <a href="{{ route('uc-testimonies.index') }}" class="block py-2.5 px-3 text-sm font-medium rounded-lg {{ request()->routeIs('uc-testimonies.*') ? 'bg-soft-gray-100 text-soft-gray-900' : 'text-soft-gray-700 hover:bg-soft-gray-50' }}">Testimonies</a>
                <a href="{{ route('about') }}" class="block py-2.5 px-3 text-sm font-medium rounded-lg {{ request()->routeIs('about') ? 'bg-soft-gray-100 text-soft-gray-900' : 'text-soft-gray-700 hover:bg-soft-gray-50' }}">About Us</a>
                <div class="pt-2 mt-2 border-t border-soft-gray-100 space-y-2">
                    <a href="{{ route('login') }}" class="block text-center py-2.5 px-3 text-sm font-medium text-soft-gray-700 hover:bg-soft-gray-50 rounded-lg border border-soft-gray-200 transition">Log in</a>
                </div>
            @endif
        </div>
    </div>
</nav>