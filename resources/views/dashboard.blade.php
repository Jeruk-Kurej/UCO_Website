<x-app-layout>
    <div class="space-y-0">
        {{-- Hero Section --}}
        <div class="bg-gradient-to-r from-orange-500 via-orange-400 to-yellow-500 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-20">
            <div class="max-w-4xl mx-auto text-center text-white">
                <h1 class="text-5xl font-bold mb-4">UCO Student & Alumni Platform</h1>
                <p class="text-xl text-orange-50 mb-8">
                    Connecting entrepreneurs, building dreams together
                </p>
                <div class="flex items-center justify-center gap-4">
                    @auth
                        @if(!auth()->user()->isAdmin())
                            <a href="{{ route('businesses.create') }}" 
                               class="inline-flex items-center px-6 py-3 bg-white text-orange-600 rounded-xl font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
                                <i class="bi bi-plus-circle-fill me-2"></i>
                                Create Business
                            </a>
                        @endif
                        <a href="{{ route('businesses.index') }}" 
                           class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-sm text-white rounded-xl font-bold border-2 border-white hover:bg-white hover:text-orange-600 transition-all duration-200">
                            <i class="bi bi-compass me-2"></i>
                            Explore Directory
                        </a>
                    @else
                        <a href="{{ route('register') }}" 
                           class="inline-flex items-center px-6 py-3 bg-white text-orange-600 rounded-xl font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
                            <i class="bi bi-person-plus me-2"></i>
                            Join Now
                        </a>
                        <a href="{{ route('login') }}" 
                           class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-sm text-white rounded-xl font-bold border-2 border-white hover:bg-white hover:text-orange-600 transition-all duration-200">
                            <i class="bi bi-box-arrow-in-right me-2"></i>
                            Login
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        {{-- Stats Banner --}}
        <div class="bg-white -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-12 border-b border-gray-200">
            <div class="max-w-6xl mx-auto">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                    <div>
                        <div class="text-4xl font-bold text-orange-600 mb-2">{{ \App\Models\Business::count() }}+</div>
                        <p class="text-sm text-gray-600 font-medium">Active Businesses</p>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-orange-600 mb-2">{{ \App\Models\User::where('is_active', true)->count() }}+</div>
                        <p class="text-sm text-gray-600 font-medium">Community Members</p>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-orange-600 mb-2">{{ \App\Models\Product::count() }}+</div>
                        <p class="text-sm text-gray-600 font-medium">Products & Services</p>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-orange-600 mb-2">{{ \App\Models\BusinessType::count() }}+</div>
                        <p class="text-sm text-gray-600 font-medium">Business Categories</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- About Section --}}
        <div class="py-16 bg-gray-50">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">About UCO Platform</h2>
                    <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                        Universitas Ciputra's dedicated platform for students and alumni to showcase their businesses, 
                        connect with fellow entrepreneurs, and build a thriving community.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    {{-- Feature 1 --}}
                    <div class="bg-white rounded-2xl p-8 shadow-md hover:shadow-xl transition-shadow duration-300">
                        <div class="w-16 h-16 bg-gradient-to-br from-orange-400 to-orange-600 rounded-xl flex items-center justify-center text-white mb-6">
                            <i class="bi bi-briefcase text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Business Directory</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Showcase your business with detailed profiles, product catalogs, photo galleries, and customer testimonials.
                        </p>
                    </div>

                    {{-- Feature 2 --}}
                    <div class="bg-white rounded-2xl p-8 shadow-md hover:shadow-xl transition-shadow duration-300">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center text-white mb-6">
                            <i class="bi bi-people text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Community Network</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Connect with fellow UC entrepreneurs, share experiences, and collaborate on exciting business opportunities.
                        </p>
                    </div>

                    {{-- Feature 3 --}}
                    <div class="bg-white rounded-2xl p-8 shadow-md hover:shadow-xl transition-shadow duration-300">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center text-white mb-6">
                            <i class="bi bi-graph-up-arrow text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Growth Support</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Access resources, mentorship, and visibility to help your business grow within the UC ecosystem.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Featured Businesses --}}
        @php
            $featuredBusinesses = \App\Models\Business::with(['businessType', 'photos', 'user'])
                ->latest()
                ->take(6)
                ->get();
        @endphp

        @if($featuredBusinesses->count() > 0)
            <div class="py-16 bg-white">
                <div class="max-w-6xl mx-auto">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Featured Businesses</h2>
                        <p class="text-lg text-gray-600">
                            Discover amazing businesses from our community
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($featuredBusinesses as $business)
                            <a href="{{ route('businesses.show', $business) }}" 
                               class="group bg-white border-2 border-gray-200 rounded-2xl overflow-hidden hover:border-orange-500 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">
                                {{-- Photo --}}
                                @if($business->photos->first())
                                    <div class="relative overflow-hidden h-48">
                                        <img src="{{ asset('storage/' . $business->photos->first()->photo_url) }}" 
                                             alt="{{ $business->name }}" 
                                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                    </div>
                                @else
                                    <div class="h-48 bg-gradient-to-br from-orange-100 via-orange-200 to-yellow-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-500">
                                        <i class="bi bi-briefcase text-7xl text-orange-400/50"></i>
                                    </div>
                                @endif

                                {{-- Info --}}
                                <div class="p-6">
                                    <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-orange-600 transition-colors duration-200">
                                        {{ $business->name }}
                                    </h3>
                                    <p class="text-sm text-gray-600 mb-4 line-clamp-2 leading-relaxed">
                                        {{ $business->description }}
                                    </p>
                                    <div class="flex items-center justify-between">
                                        <span class="inline-flex items-center gap-1.5 text-xs bg-gradient-to-r from-orange-50 to-yellow-50 text-orange-700 px-3 py-1.5 rounded-lg font-semibold border border-orange-100">
                                            <i class="bi bi-tag-fill"></i>
                                            {{ $business->businessType->name }}
                                        </span>
                                        <span class="text-xs text-gray-500 flex items-center gap-1">
                                            <i class="bi bi-person-circle"></i>
                                            {{ Str::limit($business->user->name, 15) }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    {{-- View All Button --}}
                    <div class="text-center mt-12">
                        <a href="{{ route('businesses.index') }}" 
                           class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white rounded-xl font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
                            <span>Explore All Businesses</span>
                            <i class="bi bi-arrow-right-circle-fill text-xl"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endif

        {{-- How It Works --}}
        <div class="py-16 bg-gradient-to-br from-gray-50 to-orange-50">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">How It Works</h2>
                    <p class="text-lg text-gray-600">
                        Get started in three simple steps
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    {{-- Step 1 --}}
                    <div class="relative">
                        <div class="bg-white rounded-2xl p-8 shadow-md text-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-orange-400 to-orange-600 rounded-full flex items-center justify-center text-white text-2xl font-bold mx-auto mb-6">
                                1
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Create Account</h3>
                            <p class="text-gray-600">
                                Sign up with your UC email and join our entrepreneurial community
                            </p>
                        </div>
                        {{-- Arrow --}}
                        <div class="hidden md:block absolute top-1/2 -right-4 transform -translate-y-1/2 text-orange-400 text-3xl">
                            <i class="bi bi-arrow-right"></i>
                        </div>
                    </div>

                    {{-- Step 2 --}}
                    <div class="relative">
                        <div class="bg-white rounded-2xl p-8 shadow-md text-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white text-2xl font-bold mx-auto mb-6">
                                2
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Setup Business</h3>
                            <p class="text-gray-600">
                                Create your business profile with photos, products, and contact details
                            </p>
                        </div>
                        {{-- Arrow --}}
                        <div class="hidden md:block absolute top-1/2 -right-4 transform -translate-y-1/2 text-blue-400 text-3xl">
                            <i class="bi bi-arrow-right"></i>
                        </div>
                    </div>

                    {{-- Step 3 --}}
                    <div>
                        <div class="bg-white rounded-2xl p-8 shadow-md text-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center text-white text-2xl font-bold mx-auto mb-6">
                                3
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Grow & Connect</h3>
                            <p class="text-gray-600">
                                Reach customers, network with peers, and grow your business
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CTA Section --}}
        <div class="py-20 bg-gradient-to-r from-orange-500 via-orange-400 to-yellow-500 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center text-white">
                <h2 class="text-4xl font-bold mb-4">Ready to Showcase Your Business?</h2>
                <p class="text-xl text-orange-50 mb-8">
                    Join hundreds of UC entrepreneurs already growing their businesses on our platform
                </p>
                @auth
                    @if(!auth()->user()->isAdmin())
                        <a href="{{ route('businesses.create') }}" 
                           class="inline-flex items-center gap-2 px-8 py-4 bg-white text-orange-600 rounded-xl font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
                            <i class="bi bi-plus-circle-fill text-xl"></i>
                            <span>Create Your Business Now</span>
                        </a>
                    @else
                        <a href="{{ route('users.index') }}" 
                           class="inline-flex items-center gap-2 px-8 py-4 bg-white text-orange-600 rounded-xl font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
                            <i class="bi bi-shield-check text-xl"></i>
                            <span>Go to Admin Panel</span>
                        </a>
                    @endif
                @else
                    <a href="{{ route('register') }}" 
                       class="inline-flex items-center gap-2 px-8 py-4 bg-white text-orange-600 rounded-xl font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
                        <i class="bi bi-person-plus text-xl"></i>
                        <span>Get Started - It's Free</span>
                    </a>
                @endauth
            </div>
        </div>
    </div>
</x-app-layout>
