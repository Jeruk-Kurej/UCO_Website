<x-app-layout>
    <div class="space-y-12">
        {{-- Hero Section --}}
        <div class="bg-white border border-gray-200 rounded-2xl p-12">
            <div class="max-w-4xl mx-auto">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-full mb-6">
                    <span class="w-1.5 h-1.5 bg-purple-600 rounded-full"></span>
                    <span class="text-xs font-medium text-gray-700">Welcome to UCO Platform</span>
                </div>
                
                <h1 class="text-4xl font-bold text-gray-900 mb-4">
                    Student & Alumni <span class="text-purple-600">Business Directory</span>
                </h1>
                <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                    Connect with Universitas Ciputra's entrepreneurial community. Discover businesses, showcase your products, and grow your network.
                </p>
                
                <div class="flex items-center gap-4">
                    @auth
                        @if(!auth()->user()->isAdmin())
                            <a href="/businesses/create" 
                               class="inline-flex items-center px-5 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Create Business
                            </a>
                        @endif
                        <a href="/businesses" 
                           class="inline-flex items-center px-5 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            Explore Directory
                        </a>
                    @else
                        <a href="/register" 
                           class="inline-flex items-center px-5 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                            Join Now
                        </a>
                        <a href="/login" 
                           class="inline-flex items-center px-5 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            Login
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        {{-- Featured Businesses --}}
        @if($featuredBusinesses->count() > 0)
            <div class="space-y-8">
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Featured Businesses</h2>
                    <p class="text-sm text-gray-600">
                        Discover amazing businesses from our community
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($featuredBusinesses as $business)
                        <a href="{{ route('businesses.show', $business) }}" 
                           class="group bg-white border border-gray-200 rounded-xl overflow-hidden hover:border-purple-600 hover:shadow-lg transition-all duration-300">
                            {{-- Photo --}}
                            @if($business->photos->first())
                                <div class="relative overflow-hidden h-48">
                                    <img src="{{ asset('storage/' . $business->photos->first()->photo_url) }}" 
                                         alt="{{ $business->name }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                </div>
                            @else
                                <div class="h-48 bg-gray-100 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                            @endif

                            {{-- Info --}}
                            <div class="p-5">
                                <div class="flex items-start justify-between mb-2">
                                    <h3 class="text-base font-bold text-gray-900 group-hover:text-purple-600 transition-colors flex-1">
                                        {{ $business->name }}
                                    </h3>
                                    @if($business->is_featured)
                                        <span class="ml-2 inline-flex items-center gap-1 text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded font-medium flex-shrink-0">
                                            <i class="bi bi-star-fill"></i>
                                            Featured
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600 mb-4 line-clamp-2 leading-relaxed">
                                    {{ $business->description }}
                                </p>
                                <div class="flex items-center justify-between">
                                    <span class="inline-flex items-center gap-1.5 text-xs bg-gray-50 text-gray-700 px-3 py-1.5 rounded-lg font-medium border border-gray-200">
                                        {{ $business->businessType->name }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        {{ Str::limit($business->user->name, 15) }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- View All Button --}}
                <div class="text-center">
                    <a href="/businesses" 
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                        <span>Explore All Businesses</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        @endif

        
    </div>
</x-app-layout>