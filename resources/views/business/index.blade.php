<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <i class="bi bi-briefcase me-2"></i>
                {{ request()->get('my') ? __('My Businesses') : __('Browse Businesses') }}
            </h2>
            @auth
                <a href="{{ route('businesses.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-orange-500 to-yellow-500 hover:from-orange-600 hover:to-yellow-600 text-white rounded-lg font-semibold text-xs uppercase tracking-widest shadow-sm">
                    <i class="bi bi-plus-lg me-2"></i>
                    Create Business
                </a>
            @endauth
        </div>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            {{-- Filter Tabs --}}
            @auth
                <div class="mb-6 border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8">
                        <a href="{{ route('businesses.index') }}" 
                           class="border-transparent hover:border-gray-300 flex items-center gap-2 py-2 px-1 border-b-2 font-medium text-sm transition duration-150
                                  {{ !request()->get('my') ? 'border-orange-500 text-orange-600' : 'text-gray-500 hover:text-gray-700' }}">
                            <i class="bi bi-shop"></i>
                            All Businesses
                        </a>
                        <a href="{{ route('businesses.index', ['my' => 'true']) }}" 
                           class="border-transparent hover:border-gray-300 flex items-center gap-2 py-2 px-1 border-b-2 font-medium text-sm transition duration-150
                                  {{ request()->get('my') ? 'border-orange-500 text-orange-600' : 'text-gray-500 hover:text-gray-700' }}">
                            <i class="bi bi-briefcase"></i>
                            My Businesses
                        </a>
                    </nav>
                </div>
            @endauth

            {{-- Business Grid --}}
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
                    <p class="mt-4 text-gray-500 text-lg font-medium">
                        {{ request()->get('my') ? 'You have no businesses yet.' : 'No businesses found.' }}
                    </p>
                    @auth
                        @if(request()->get('my'))
                            <a href="{{ route('businesses.create') }}" 
                               class="mt-4 inline-flex items-center px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 transition duration-150">
                                <i class="bi bi-plus-lg me-2"></i>
                                Create Your First Business
                            </a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
    </div>
</x-app-layout>