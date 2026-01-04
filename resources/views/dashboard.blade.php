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

                    {{-- Business Cards - Clean Grid --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach($featuredBusinesses as $business)
                        <a href="{{ route('businesses.show', $business) }}" 
                           class="group bg-white border border-soft-gray-200 rounded-xl overflow-hidden hover:shadow-xl hover:border-soft-gray-300 transition-all duration-300">
                            
                            {{-- Photo Section - Top --}}
                            @if($business->photos->first())
                                <div class="relative overflow-hidden h-64">
                                    <img src="{{ asset('storage/' . $business->photos->first()->photo_url) }}" 
                                         alt="{{ $business->name }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                </div>
                            @else
                                <div class="h-64 bg-gradient-to-br from-soft-gray-50 to-soft-gray-100 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-soft-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                            @endif

                            {{-- Content Section - Below Photo --}}
                            <div class="p-6">
                                {{-- Header --}}
                                <div class="mb-4">
                                    <span class="inline-block text-xs text-soft-gray-500 bg-soft-gray-50 px-3 py-1 rounded-md mb-2">
                                        {{ $business->businessType->name }}
                                    </span>
                                    <h3 class="text-lg font-bold text-soft-gray-900 group-hover:text-uco-orange-600 transition-colors leading-tight">
                                        {{ $business->name }}
                                    </h3>
                                </div>

                                {{-- Description --}}
                                <p class="text-sm text-soft-gray-600 leading-relaxed mb-4 line-clamp-2">
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

                                {{-- Footer --}}
                                <div class="pt-4 border-t border-soft-gray-100 flex items-center justify-between">
                                    <span class="text-xs text-soft-gray-500 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        {{ Str::limit($business->user->name, 15) }}
                                    </span>
                                    <span class="text-xs font-medium text-uco-orange-600 group-hover:text-uco-orange-700 flex items-center gap-1">
                                        View
                                        <svg class="w-3 h-3 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </span>
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

        {{-- What They Say About UCO Section --}}
        @if($testimonies->count() > 0)
            <div class="relative">
                {{-- Subtle Background Accent --}}
                <div class="absolute top-0 left-0 w-96 h-96 bg-gradient-to-br from-uco-yellow-50 to-uco-orange-50 rounded-full blur-3xl opacity-40 -z-10"></div>
                
                <div class="space-y-8">
                    {{-- Section Header --}}
                    <div class="flex items-end justify-between border-b border-soft-gray-200 pb-6">
                        <div>
                            <h2 class="text-3xl font-bold text-soft-gray-900 tracking-tight">
                                What They Say About UCO
                            </h2>
                            <p class="text-sm text-soft-gray-600 mt-2">
                                Hear from our community of students and alumni
                            </p>
                        </div>
                        <a href="{{ route('uc-testimonies.index') }}"
                           class="hidden sm:inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-uco-orange-500 to-uco-yellow-500 hover:from-uco-orange-600 hover:to-uco-yellow-600 rounded-lg shadow-sm hover:shadow-md transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Write to Us
                        </a>
                    </div>

                    {{-- Testimonies Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($testimonies as $testimony)
                            <div class="bg-white border border-soft-gray-200 rounded-xl p-6 hover:shadow-lg hover:border-soft-gray-300 transition-all">
                                {{-- Rating Stars --}}
                                <div class="flex items-center gap-1 mb-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= $testimony->rating ? 'text-yellow-400' : 'text-gray-300' }} fill-current" viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                        </svg>
                                    @endfor
                                </div>

                                {{-- Testimony Content --}}
                                <p class="text-soft-gray-700 text-sm leading-relaxed mb-4 line-clamp-4">
                                    "{{ $testimony->content }}"
                                </p>

                                {{-- Author Info --}}
                                <div class="pt-4 border-t border-soft-gray-100">
                                    <p class="text-sm font-semibold text-soft-gray-900">{{ $testimony->customer_name }}</p>
                                    <p class="text-xs text-soft-gray-500 mt-1">{{ optional($testimony->date)->format('F d, Y') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Mobile Write Button --}}
                    <div class="sm:hidden flex justify-center">
                        <a href="{{ route('uc-testimonies.index') }}"
                           class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-uco-orange-500 to-uco-yellow-500 hover:from-uco-orange-600 hover:to-uco-yellow-600 rounded-lg shadow-sm hover:shadow-md transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Write to Us
                        </a>
                    </div>
                </div>
            </div>
        @endif
        
    </div>
</x-app-layout>