<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <i class="bi bi-shop me-2"></i>
            {{ __('Businesses Directory') }}
        </h2>
    </x-slot>

    <div x-data="{ activeTab: 'browse' }" class="space-y-6">
        {{-- Tabs Navigation --}}
        <div class="bg-white shadow-sm sm:rounded-lg">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px px-6">
                    {{-- Browse All Tab --}}
                    <button @click="activeTab = 'browse'" 
                            :class="activeTab === 'browse' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="flex items-center gap-2 py-4 px-4 border-b-2 font-medium text-sm transition duration-150">
                        <i class="bi bi-shop"></i>
                        Browse All Businesses
                        <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full text-xs">
                            {{ $businesses->total() }}
                        </span>
                    </button>

                    {{-- My Businesses Tab (ONLY for authenticated users) --}}
                    @auth
                        @if(!auth()->user()->isAdmin())
                            <button @click="activeTab = 'my'" 
                                    :class="activeTab === 'my' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="flex items-center gap-2 py-4 px-4 border-b-2 font-medium text-sm transition duration-150">
                                <i class="bi bi-briefcase"></i>
                                My Businesses
                                <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full text-xs">
                                    {{ $businesses->where('user_id', auth()->id())->count() }}
                                </span>
                            </button>
                        @endif
                    @endauth
                </nav>
            </div>

            {{-- Tab Content: Browse All Businesses --}}
            <div x-show="activeTab === 'browse'" class="p-6">
                @if($businesses->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($businesses as $business)
                            <div class="bg-white border border-gray-200 rounded-lg shadow hover:shadow-lg transition duration-150">
                                {{-- Business Photo --}}
                                @if($business->photos->first())
                                    <img src="{{ asset('storage/' . $business->photos->first()->photo_url) }}" 
                                         alt="{{ $business->name }}" 
                                         class="w-full h-48 object-cover rounded-t-lg">
                                @else
                                    <div class="w-full h-48 bg-gradient-to-br from-orange-100 to-yellow-100 rounded-t-lg flex items-center justify-center">
                                        <i class="bi bi-briefcase text-6xl text-orange-300"></i>
                                    </div>
                                @endif

                                <div class="p-5">
                                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $business->name }}</h3>
                                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $business->description }}</p>
                                    
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-xs bg-orange-100 text-orange-800 px-2 py-1 rounded">
                                            {{ $business->businessType->name }}
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            <i class="bi bi-person"></i>
                                            {{ $business->user->name }}
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                                        <a href="{{ route('businesses.show', $business) }}" 
                                           class="text-orange-600 hover:text-orange-700 text-sm font-medium flex items-center gap-1">
                                            View Details
                                            <i class="bi bi-arrow-right"></i>
                                        </a>

                                        @auth
                                            @if(auth()->id() === $business->user_id || auth()->user()->isAdmin())
                                                <a href="{{ route('businesses.edit', $business) }}" 
                                                   class="text-gray-500 hover:text-gray-700 text-sm">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-6">
                        {{ $businesses->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="bi bi-inbox text-6xl text-gray-300"></i>
                        <p class="mt-4 text-gray-500 text-lg font-medium">No businesses found.</p>
                    </div>
                @endif
            </div>

            {{-- Tab Content: My Businesses --}}
            @auth
                @if(!auth()->user()->isAdmin())
                    <div x-show="activeTab === 'my'" class="p-6" style="display: none;">
                        {{-- ✅ Add Business Button (ONLY in My Businesses Tab) --}}
                        <div class="mb-6 flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">My Businesses</h3>
                            <a href="{{ route('businesses.create') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-orange-500 to-yellow-500 hover:from-orange-600 hover:to-yellow-600 text-white rounded-lg font-semibold text-sm shadow-sm transition duration-150">
                                <i class="bi bi-plus-lg me-2"></i>
                                Add Business
                            </a>
                        </div>

                        @php
                            $myBusinesses = $businesses->where('user_id', auth()->id());
                        @endphp

                        @if($myBusinesses->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($myBusinesses as $business)
                                    <div class="bg-white border border-gray-200 rounded-lg shadow hover:shadow-lg transition duration-150">
                                        @if($business->photos->first())
                                            <img src="{{ asset('storage/' . $business->photos->first()->photo_url) }}" 
                                                 alt="{{ $business->name }}" 
                                                 class="w-full h-48 object-cover rounded-t-lg">
                                        @else
                                            <div class="w-full h-48 bg-gradient-to-br from-orange-100 to-yellow-100 rounded-t-lg flex items-center justify-center">
                                                <i class="bi bi-briefcase text-6xl text-orange-300"></i>
                                            </div>
                                        @endif

                                        <div class="p-5">
                                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $business->name }}</h3>
                                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $business->description }}</p>
                                            
                                            <div class="flex items-center justify-between mb-3">
                                                <span class="text-xs bg-orange-100 text-orange-800 px-2 py-1 rounded">
                                                    {{ $business->businessType->name }}
                                                </span>
                                                <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">
                                                    <i class="bi bi-check-circle me-1"></i>
                                                    My Business
                                                </span>
                                            </div>

                                            <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                                                <a href="{{ route('businesses.show', $business) }}" 
                                                   class="text-orange-600 hover:text-orange-700 text-sm font-medium flex items-center gap-1">
                                                    View Details
                                                    <i class="bi bi-arrow-right"></i>
                                                </a>
                                                <a href="{{ route('businesses.edit', $business) }}" 
                                                   class="inline-flex items-center px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded text-sm transition duration-150">
                                                    <i class="bi bi-pencil me-1"></i>
                                                    Edit
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <i class="bi bi-inbox text-6xl text-gray-300"></i>
                                <p class="mt-4 text-gray-500 text-lg font-medium">You have no businesses yet.</p>
                                <a href="{{ route('businesses.create') }}" 
                                   class="mt-4 inline-flex items-center px-6 py-3 bg-gradient-to-r from-orange-500 to-yellow-500 hover:from-orange-600 hover:to-yellow-600 text-white rounded-lg font-semibold shadow-sm transition duration-150">
                                    <i class="bi bi-plus-lg me-2"></i>
                                    Create Your First Business
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            @endauth
        </div>
    </div>
</x-app-layout>