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
                        <a href="{{ route('businesses.show', $business) }}" class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden group block">
                            <div class="flex flex-col h-full">
                                {{-- Header with Photo & Logo --}}
                                <div class="relative h-56">
                                    {{-- Category Badge - Top Left --}}
                                    <div class="absolute top-3 left-3 z-10">
                                        <span class="inline-block text-xs bg-white/95 backdrop-blur-sm text-slate-800 px-3 py-1.5 rounded-full font-semibold shadow-md">
                                            {{ $business->businessType->name }}
                                        </span>
                                    </div>
                                    
                                    {{-- Business Photo Background --}}
                                    @if($business->photos->first())
                                        <img src="{{ asset('storage/' . $business->photos->first()->photo_url) }}" 
                                             alt="{{ $business->name }}" 
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center">
                                            <i class="bi bi-briefcase text-6xl text-slate-400"></i>
                                        </div>
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-black/10 to-transparent"></div>
                                    @endif
                                    
                                    {{-- Logo & Featured Badge Overlay --}}
                                    <div class="absolute bottom-4 left-4 flex items-end gap-4">
                                        @if($business->logo_url)
                                            <div class="w-20 h-20 rounded-xl bg-white shadow-lg border-2 border-white overflow-hidden flex-shrink-0">
                                                <img src="{{ asset('storage/' . $business->logo_url) }}" 
                                                     alt="{{ $business->name }} logo" 
                                                     class="w-full h-full object-cover">
                                            </div>
                                        @else
                                            <div class="w-20 h-20 rounded-xl bg-white shadow-lg border-2 border-white flex items-center justify-center flex-shrink-0">
                                                <i class="bi bi-building text-3xl text-slate-400"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Content --}}
                                <div class="p-5 flex-1 flex flex-col">
                                    {{-- Business Name --}}
                                    <div class="mb-3">
                                        <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-1 group-hover:text-gray-700 transition" 
                                            title="{{ $business->name }}">
                                            {{ $business->name }}
                                        </h3>
                                    </div>
                                    
                                    {{-- Description --}}
                                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $business->description }}</p>
                                    
                                    {{-- Owner Info Card --}}
                                    <div class="bg-slate-50 border border-slate-100 rounded-lg p-3 mb-4">
                                        <div class="flex items-center gap-3">
                                            {{-- Owner Avatar --}}
                                            @if($business->user->profile_photo_url ?? false)
                                                <img src="{{ asset('storage/' . $business->user->profile_photo_url) }}" 
                                                     alt="{{ $business->user->name }}" 
                                                     class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm">
                                            @else
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-slate-400 to-slate-600 flex items-center justify-center text-white font-bold text-sm border-2 border-white shadow-sm">
                                                    {{ strtoupper(substr($business->user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold text-gray-900 truncate" title="{{ $business->user->name }}">
                                                    {{ $business->user->name }}
                                                </p>
                                                @if($business->position)
                                                    <p class="text-xs text-slate-600 truncate" title="{{ $business->position }}">
                                                        {{ $business->position }}
                                                    </p>
                                                @else
                                                    <p class="text-xs text-slate-500">Owner</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    {{-- Actions --}}
                                    <div class="flex items-center justify-end pt-3 border-t border-gray-100 mt-auto">
                                        @auth
                                            @if(auth()->id() === $business->user_id || auth()->user()->isAdmin())
                                                <button onclick="event.preventDefault(); event.stopPropagation(); window.location.href='{{ route('businesses.edit', $business) }}'" 
                                                   class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition">
                                                    <i class="bi bi-pencil"></i>
                                                    Edit
                                                </button>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                    </div>
                @else
                    {{-- No Featured Businesses Message --}}
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <p class="text-slate-600">No featured businesses available at the moment.</p>
                    </div>
                @endif

        
    </div>
</x-app-layout>