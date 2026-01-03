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

                    {{-- Business Cards - Modern Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($featuredBusinesses as $business)
                        <a href="{{ route('businesses.show', $business) }}" 
                           class="group bg-white border border-soft-gray-200 rounded-2xl overflow-hidden hover:border-uco-orange-300 hover:shadow-xl transition-all duration-300">
                            {{-- Photo with Overlay --}}
                            @if($business->photos->first())
                                <div class="relative overflow-hidden h-52">
                                    <img src="{{ asset('storage/' . $business->photos->first()->photo_url) }}" 
                                         alt="{{ $business->name }}" 
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                </div>
                            @else
                                <div class="h-52 bg-gradient-to-br from-soft-gray-50 to-soft-gray-100 flex items-center justify-center">
                                    <svg class="w-20 h-20 text-soft-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                            @endif

                            {{-- Info with Better Spacing --}}
                            <div class="p-6">
                                <h3 class="text-lg font-bold text-soft-gray-900 group-hover:text-uco-orange-600 transition-colors leading-tight mb-3">
                                    {{ $business->name }}
                                </h3>
                                <p class="text-sm text-soft-gray-600 mb-5 line-clamp-2 leading-relaxed">
                                    {{ $business->description }}
                                </p>
                                <div class="flex items-center justify-between pt-4 border-t border-soft-gray-100">
                                    <span class="inline-flex items-center gap-2 text-xs bg-soft-gray-50 text-soft-gray-700 px-3 py-2 rounded-lg font-medium border border-soft-gray-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                        {{ $business->businessType->name }}
                                    </span>
                                    <span class="text-xs text-soft-gray-500 font-medium">
                                        by {{ Str::limit($business->user->name, 15) }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        
    </div>
</x-app-layout>