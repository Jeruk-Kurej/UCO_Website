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

                    {{-- Business Cards - Large & Informative --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    @foreach($featuredBusinesses as $business)
                        <a href="{{ route('businesses.show', $business) }}" 
                           class="group bg-white border border-soft-gray-200 rounded-2xl overflow-hidden hover:border-uco-orange-300 hover:shadow-2xl transition-all duration-300">
                            
                            <div class="grid md:grid-cols-5 gap-0">
                                {{-- Photo - Larger --}}
                                @if($business->photos->first())
                                    <div class="relative overflow-hidden h-64 md:h-auto md:col-span-2">
                                        <img src="{{ asset('storage/' . $business->photos->first()->photo_url) }}" 
                                             alt="{{ $business->name }}" 
                                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                    </div>
                                @else
                                    <div class="h-64 md:h-auto md:col-span-2 bg-gradient-to-br from-soft-gray-50 to-soft-gray-100 flex items-center justify-center">
                                        <svg class="w-24 h-24 text-soft-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                @endif

                                {{-- Content - More Detailed --}}
                                <div class="p-6 md:col-span-3 flex flex-col">
                                    {{-- Header --}}
                                    <div class="mb-4">
                                        <h3 class="text-xl font-bold text-soft-gray-900 group-hover:text-uco-orange-600 transition-colors leading-tight mb-2">
                                            {{ $business->name }}
                                        </h3>
                                        <span class="inline-flex items-center gap-2 text-xs bg-soft-gray-50 text-soft-gray-700 px-3 py-1.5 rounded-lg font-medium border border-soft-gray-200">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                            </svg>
                                            {{ $business->businessType->name }}
                                        </span>
                                    </div>

                                    {{-- Description - More visible --}}
                                    <p class="text-sm text-soft-gray-600 leading-relaxed mb-5 line-clamp-3">
                                        {{ $business->description }}
                                    </p>

                                    {{-- Products & Services Preview --}}
                                    <div class="mt-auto space-y-3">
                                        @if($business->products->count() > 0)
                                            <div>
                                                <div class="flex items-center gap-2 mb-2">
                                                    <svg class="w-4 h-4 text-soft-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                    </svg>
                                                    <span class="text-xs font-semibold text-soft-gray-500 uppercase tracking-wide">Products</span>
                                                </div>
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($business->products->take(3) as $product)
                                                        <span class="text-xs bg-gradient-to-r from-uco-orange-50 to-uco-yellow-50 text-soft-gray-700 px-3 py-1 rounded-full border border-uco-orange-100">
                                                            {{ Str::limit($product->name, 20) }}
                                                        </span>
                                                    @endforeach
                                                    @if($business->products->count() > 3)
                                                        <span class="text-xs text-soft-gray-500 px-2 py-1">
                                                            +{{ $business->products->count() - 3 }} more
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        @if($business->services->count() > 0)
                                            <div>
                                                <div class="flex items-center gap-2 mb-2">
                                                    <svg class="w-4 h-4 text-soft-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                    </svg>
                                                    <span class="text-xs font-semibold text-soft-gray-500 uppercase tracking-wide">Services</span>
                                                </div>
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($business->services->take(3) as $service)
                                                        <span class="text-xs bg-gradient-to-r from-uco-yellow-50 to-uco-orange-50 text-soft-gray-700 px-3 py-1 rounded-full border border-uco-yellow-100">
                                                            {{ Str::limit($service->name, 20) }}
                                                        </span>
                                                    @endforeach
                                                    @if($business->services->count() > 3)
                                                        <span class="text-xs text-soft-gray-500 px-2 py-1">
                                                            +{{ $business->services->count() - 3 }} more
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Footer Info --}}
                                        <div class="flex items-center justify-between pt-4 border-t border-soft-gray-100">
                                            <span class="text-xs text-soft-gray-500 flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                {{ $business->user->name }}
                                            </span>
                                            <span class="text-xs font-medium text-uco-orange-600 group-hover:text-uco-orange-700 flex items-center gap-1">
                                                View Details
                                                <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        
    </div>
</x-app-layout>