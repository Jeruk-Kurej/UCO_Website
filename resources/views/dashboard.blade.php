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

                    {{-- Business Cards - Unique & Eye-catching Design --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    @foreach($featuredBusinesses as $business)
                        <div class="group relative">
                            {{-- Decorative corner accent - Ciri khas UCO --}}
                            <div class="absolute -top-3 -right-3 w-20 h-20 bg-gradient-to-br from-uco-orange-400 to-uco-yellow-400 rounded-full blur-2xl opacity-40 group-hover:opacity-60 transition-opacity"></div>
                            <div class="absolute -bottom-3 -left-3 w-16 h-16 bg-gradient-to-tr from-uco-yellow-300 to-uco-orange-300 rounded-full blur-2xl opacity-30 group-hover:opacity-50 transition-opacity"></div>
                            
                            <a href="{{ route('businesses.show', $business) }}" 
                               class="block relative bg-white border-2 border-soft-gray-200 rounded-3xl overflow-hidden hover:border-uco-orange-400 hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-1">
                                
                                {{-- Orange accent stripe - Signature element --}}
                                <div class="absolute top-0 left-0 h-full w-2 bg-gradient-to-b from-uco-orange-500 via-uco-yellow-500 to-uco-orange-500 transform origin-top group-hover:scale-y-110 transition-transform duration-500"></div>
                                
                                <div class="grid md:grid-cols-5 gap-0">
                                    {{-- Photo Section - Unique angle --}}
                                    <div class="relative md:col-span-2 h-80 md:h-auto">
                                        @if($business->photos->first())
                                            <div class="relative overflow-hidden h-full">
                                                <img src="{{ asset('storage/' . $business->photos->first()->photo_url) }}" 
                                                     alt="{{ $business->name }}" 
                                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                                <div class="absolute inset-0 bg-gradient-to-br from-uco-orange-500/20 via-transparent to-uco-yellow-500/20 mix-blend-overlay"></div>
                                                
                                                {{-- Mini product photos overlay - Unique feature! --}}
                                                @if($business->products->count() > 0 && $business->products->where('photos')->count() > 0)
                                                    <div class="absolute bottom-4 left-4 right-4 flex gap-2 overflow-hidden">
                                                        @foreach($business->products->take(3) as $product)
                                                            @if($product->photos->first())
                                                                <div class="w-16 h-16 rounded-xl overflow-hidden border-2 border-white shadow-lg flex-shrink-0 transform hover:scale-110 transition-transform">
                                                                    <img src="{{ asset('storage/' . $product->photos->first()->photo_url) }}" 
                                                                         alt="{{ $product->name }}" 
                                                                         class="w-full h-full object-cover">
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <div class="h-full bg-gradient-to-br from-uco-orange-50 via-uco-yellow-50 to-uco-orange-50 flex items-center justify-center relative overflow-hidden">
                                                {{-- Geometric pattern background --}}
                                                <div class="absolute inset-0 opacity-10">
                                                    <div class="absolute top-10 left-10 w-32 h-32 border-4 border-uco-orange-300 rounded-full"></div>
                                                    <div class="absolute bottom-10 right-10 w-24 h-24 border-4 border-uco-yellow-300 rotate-45"></div>
                                                </div>
                                                <svg class="w-24 h-24 text-uco-orange-300 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Content Section - Asymmetric design --}}
                                    <div class="p-8 md:col-span-3 flex flex-col relative">
                                        {{-- Decorative top corner --}}
                                        <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-uco-yellow-100 to-transparent rounded-bl-full opacity-50"></div>
                                        
                                        {{-- Header with unique badge --}}
                                        <div class="mb-4 relative z-10">
                                            <span class="inline-flex items-center gap-2 text-xs bg-gradient-to-r from-uco-orange-500 to-uco-yellow-500 text-white px-4 py-1.5 rounded-full font-semibold shadow-lg mb-3">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                </svg>
                                                {{ $business->businessType->name }}
                                            </span>
                                            <h3 class="text-2xl font-bold text-soft-gray-900 group-hover:text-uco-orange-600 transition-colors leading-tight">
                                                {{ $business->name }}
                                            </h3>
                                        </div>

                                        {{-- Description with gradient fade --}}
                                        <div class="relative mb-5">
                                            <p class="text-sm text-soft-gray-600 leading-relaxed line-clamp-3">
                                                {{ $business->description }}
                                            </p>
                                            <div class="absolute bottom-0 left-0 right-0 h-6 bg-gradient-to-t from-white to-transparent"></div>
                                        </div>

                                        {{-- Products Grid with Photos - UNIQUE! --}}
                                        @if($business->products->count() > 0)
                                            <div class="mb-4">
                                                <div class="flex items-center gap-2 mb-3">
                                                    <div class="w-1 h-4 bg-gradient-to-b from-uco-orange-500 to-uco-yellow-500 rounded-full"></div>
                                                    <span class="text-xs font-bold text-soft-gray-700 uppercase tracking-wider">Featured Products</span>
                                                </div>
                                                <div class="grid grid-cols-3 gap-3">
                                                    @foreach($business->products->take(3) as $product)
                                                        <div class="group/product relative bg-gradient-to-br from-soft-gray-50 to-white rounded-xl p-3 border border-soft-gray-100 hover:border-uco-orange-200 hover:shadow-md transition-all">
                                                            @if($product->photos->first())
                                                                <div class="aspect-square rounded-lg overflow-hidden mb-2 bg-soft-gray-100">
                                                                    <img src="{{ asset('storage/' . $product->photos->first()->photo_url) }}" 
                                                                         alt="{{ $product->name }}"
                                                                         class="w-full h-full object-cover group-hover/product:scale-110 transition-transform duration-500">
                                                                </div>
                                                            @else
                                                                <div class="aspect-square rounded-lg bg-gradient-to-br from-uco-orange-50 to-uco-yellow-50 flex items-center justify-center mb-2">
                                                                    <svg class="w-8 h-8 text-uco-orange-300" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                                                                    </svg>
                                                                </div>
                                                            @endif
                                                            <p class="text-xs font-medium text-soft-gray-700 truncate">{{ $product->name }}</p>
                                                            @if($product->price)
                                                                <p class="text-xs font-bold text-uco-orange-600">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                                @if($business->products->count() > 3)
                                                    <p class="text-xs text-soft-gray-500 mt-2 text-center">+{{ $business->products->count() - 3 }} more products</p>
                                                @endif
                                            </div>
                                        @endif

                                        {{-- Services - Icon based --}}
                                        @if($business->services->count() > 0)
                                            <div class="mb-4">
                                                <div class="flex items-center gap-2 mb-3">
                                                    <div class="w-1 h-4 bg-gradient-to-b from-uco-yellow-500 to-uco-orange-500 rounded-full"></div>
                                                    <span class="text-xs font-bold text-soft-gray-700 uppercase tracking-wider">Services Offered</span>
                                                </div>
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($business->services->take(4) as $service)
                                                        <span class="inline-flex items-center gap-1.5 text-xs bg-gradient-to-r from-uco-yellow-100 to-uco-orange-100 text-soft-gray-800 px-3 py-1.5 rounded-lg font-medium border border-uco-yellow-200 shadow-sm">
                                                            <svg class="w-3 h-3 text-uco-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                            </svg>
                                                            {{ Str::limit($service->name, 15) }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Footer with gradient accent --}}
                                        <div class="mt-auto pt-5 border-t-2 border-gradient-to-r from-uco-orange-100 via-uco-yellow-100 to-uco-orange-100 flex items-center justify-between">
                                            <span class="text-xs text-soft-gray-600 flex items-center gap-2 font-medium">
                                                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-uco-orange-400 to-uco-yellow-400 flex items-center justify-center text-white text-xs font-bold shadow-md">
                                                    {{ substr($business->user->name, 0, 1) }}
                                                </div>
                                                {{ Str::limit($business->user->name, 20) }}
                                            </span>
                                            <span class="inline-flex items-center gap-2 text-sm font-bold text-uco-orange-600 group-hover:text-uco-orange-700 px-4 py-2 rounded-full bg-gradient-to-r from-uco-orange-50 to-uco-yellow-50 group-hover:shadow-lg transition-all">
                                                Explore
                                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                                </svg>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        
    </div>
</x-app-layout>