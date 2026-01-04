<x-app-layout>
    <div class="space-y-12">

        {{-- Featured Businesses - Professional & Elegant --}}
        @if($featuredBusinesses->count() > 0)
            <div class="relative">
                {{-- Subtle Background Accent --}}
                <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-bl from-uco-orange-50 to-uco-yellow-50 rounded-full blur-3xl opacity-40 -z-10"></div>
                
                <div class="space-y-8">
                    {{-- Section Header - Clean & Professional --}}
                    <div class="flex items-end justify-between border-b border-soft-gray-200 pb-6">
                        <div>
                            <h2 class="text-3xl font-bold text-soft-gray-900 tracking-tight">
                                Our Businesses
                            </h2>
                            <p class="text-sm text-soft-gray-600 mt-2">
                                Explore amazing ventures from our community of entrepreneurs
                            </p>
                        </div>
                        <a href="{{ route('businesses.index') }}" 
                           class="hidden sm:inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-soft-gray-700 hover:text-soft-gray-900 border border-soft-gray-200 rounded-lg hover:border-soft-gray-300 hover:shadow-sm transition-all">
                            View All
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>

                    {{-- Business Cards - Modern Professional Grid with 2 Columns --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    @foreach($featuredBusinesses as $business)
                        <a href="{{ route('businesses.show', $business) }}" 
                           class="group bg-white border border-soft-gray-200 rounded-2xl overflow-hidden hover:shadow-2xl hover:border-soft-gray-300 transition-all duration-300">
                            
                            {{-- Photo Section with Gradient Overlay - BALANCED SIZE --}}
                            <div class="relative overflow-hidden h-72">
                                @if($business->photos->first())
                                    <img src="{{ asset('storage/' . $business->photos->first()->photo_url) }}" 
                                         alt="{{ $business->name }}" 
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                    {{-- Dark gradient overlay for better text readability --}}
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                                @else
                                    <div class="h-full bg-gradient-to-br from-soft-gray-100 via-soft-gray-50 to-soft-gray-100 flex items-center justify-center relative">
                                        <svg class="w-24 h-24 text-soft-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-black/10 to-transparent"></div>
                                    </div>
                                @endif
                                
                                {{-- Business Category Badge on Photo --}}
                                <div class="absolute top-5 left-5">
                                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-white/95 backdrop-blur-sm text-soft-gray-800 text-sm font-semibold rounded-xl shadow-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                        {{ $business->businessType->name }}
                                    </span>
                                </div>
                                
                                {{-- Business Name Overlay on Photo - BALANCED SIZE --}}
                                <div class="absolute bottom-6 left-6 right-6">
                                    <h3 class="text-2xl font-bold text-white mb-1 line-clamp-2 drop-shadow-lg group-hover:text-uco-orange-300 transition-colors">
                                        {{ $business->name }}
                                    </h3>
                                </div>
                            </div>

                            {{-- Content Section - BALANCED PADDING --}}
                            <div class="p-6 space-y-5">
                                {{-- Owner & Position Info - PROMINENT & BALANCED --}}
                                <div class="flex items-start gap-4 pb-5 border-b border-soft-gray-100">
                                    <div class="flex-shrink-0 w-14 h-14 rounded-2xl bg-gradient-to-br from-uco-orange-500 to-uco-yellow-500 flex items-center justify-center text-white text-xl font-bold shadow-lg">
                                        {{ substr($business->user->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-base font-bold text-soft-gray-900 truncate">
                                            {{ $business->user->name }}
                                        </p>
                                        @if($business->position)
                                            <p class="text-sm text-soft-gray-600 mt-1 font-medium truncate" title="{{ $business->position }}">
                                                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                </svg>
                                                {{ $business->position }}
                                            </p>
                                        @else
                                            <p class="text-sm text-soft-gray-500 mt-1">
                                                Business Owner
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                {{-- Description - LARGER TEXT --}}
                                <p class="text-base text-soft-gray-600 leading-relaxed line-clamp-2">
                                    {{ $business->description }}
                                </p>

                                {{-- Products with Photos --}}
                                @if($business->products->count() > 0)
                                    <div class="mb-4">
                                        <p class="text-xs font-semibold text-soft-gray-700 mb-2">Products</p>
                                        <div class="grid grid-cols-3 gap-2">
                                            @foreach($business->products->take(3) as $product)
                                                <div class="group/product bg-white rounded-lg border border-soft-gray-100 overflow-hidden hover:border-soft-gray-300 transition-all">
                                                    @if($product->photos->first())
                                                        <div class="aspect-square overflow-hidden bg-soft-gray-50">
                                                            <img src="{{ asset('storage/' . $product->photos->first()->photo_url) }}" 
                                                                 alt="{{ $product->name }}"
                                                                 class="w-full h-full object-cover group-hover/product:scale-105 transition-transform">
                                                        </div>
                                                    @else
                                                        <div class="aspect-square bg-soft-gray-50 flex items-center justify-center">
                                                            <svg class="w-6 h-6 text-soft-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                    <div class="p-2">
                                                        <p class="text-xs font-medium text-soft-gray-700 truncate">{{ $product->name }}</p>
                                                        @if($product->price)
                                                            <p class="text-xs font-semibold text-uco-orange-600">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        @if($business->products->count() > 3)
                                            <p class="text-xs text-soft-gray-500 mt-2">+{{ $business->products->count() - 3 }} more products</p>
                                        @endif
                                    </div>
                                @endif

                                {{-- Services --}}
                                @if($business->services->count() > 0)
                                    <div class="mb-4">
                                        <p class="text-xs font-semibold text-soft-gray-700 mb-2">Services</p>
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach($business->services->take(3) as $service)
                                                <span class="text-xs bg-soft-gray-50 text-soft-gray-700 px-2.5 py-1 rounded-md border border-soft-gray-200">
                                                    {{ Str::limit($service->name, 15) }}
                                                </span>
                                            @endforeach
                                            @if($business->services->count() > 3)
                                                <span class="text-xs text-soft-gray-500 px-2 py-1">
                                                    +{{ $business->services->count() - 3 }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- Footer with CTA - LARGER BUTTON --}}
                                <div class="pt-5 border-t border-soft-gray-100">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-soft-gray-500">
                                            @if($business->products->count() > 0 || $business->services->count() > 0)
                                                <span class="font-semibold text-soft-gray-700">{{ $business->products->count() + $business->services->count() }}</span> Total Offerings
                                            @else
                                                <span class="text-soft-gray-400">Getting Started</span>
                                            @endif
                                        </span>
                                        <span class="inline-flex items-center gap-2 px-5 py-2.5 bg-soft-gray-900 text-white text-sm font-semibold rounded-xl group-hover:bg-uco-orange-600 transition-colors shadow-md">
                                            View Details
                                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                    </div>
                </div>
            </div>
        @else
            {{-- No Featured Businesses --}}
            <div class="bg-white border border-soft-gray-200 rounded-xl p-12 text-center">
                <svg class="w-16 h-16 text-soft-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <p class="text-soft-gray-600">No featured businesses available at the moment.</p>
            </div>
        @endif

        
    </div>
</x-app-layout>