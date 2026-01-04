<x-app-layout>
    {{-- ✅ REMOVED: <x-slot name="header"> section --}}

    <div x-data="{ activeTab: @auth @if(!auth()->user()->isAdmin()) 'my' @else 'browse' @endif @else 'browse' @endauth }" class="space-y-6">
        {{-- Tabs Navigation with Admin Button --}}
        <div class="bg-white shadow-sm sm:rounded-lg">
            <div class="border-b border-gray-200">
                <div class="flex justify-between items-center px-6">
                    {{-- Left: Tabs --}}
                    <nav class="flex -mb-px">
                        @auth
                            @if(!auth()->user()->isAdmin())
                                {{-- My Businesses Tab FIRST for non-admin --}}
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
                    </nav>

                    {{-- Right: Admin "Add Business" Button --}}
                    @auth
                        @if(auth()->user()->isAdmin())
                            <div class="py-2">
                                <a href="/businesses/create"
                                   class="inline-flex items-center px-4 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Add Business
                                </a>
                            </div>
                        @endif
                    @endauth
                </div>
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
                                        <div class="w-full h-48 bg-purple-100 rounded-t-lg flex items-center justify-center">
                                            <i class="bi bi-briefcase text-6xl text-purple-600"></i>
                                        </div>
                                    @endif

                                <div class="p-5">
                                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $business->name }}</h3>
                                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $business->description }}</p>
                                    
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded">
                                                {{ $business->businessType->name }}
                                            </span>
                                            <div class="flex items-center gap-2">
                                                @if($business->is_featured)
                                                    <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded flex items-center gap-1">
                                                        <i class="bi bi-star-fill"></i>
                                                        Featured
                                                    </span>
                                                @endif
                                                <span class="text-xs text-gray-500">
                                                    <i class="bi bi-person"></i>
                                                    {{ $business->user->name }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                                            <a href="{{ route('businesses.show', $business) }}" 
                                               class="text-purple-600 hover:text-purple-700 text-sm font-medium flex items-center gap-1">
                                                View Details
                                                <i class="bi bi-arrow-right"></i>
                                            </a>

                                        <div class="flex items-center gap-2">
                                            @auth
                                                @if(auth()->user()->isAdmin())
                                                    <button 
                                                        onclick="toggleFeatured({{ $business->id }}, this)"
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg transition-colors {{ $business->is_featured ? 'text-yellow-600 hover:bg-yellow-50' : 'text-gray-400 hover:bg-gray-100' }}"
                                                        title="{{ $business->is_featured ? 'Remove from Featured' : 'Add to Featured' }}"
                                                        data-featured="{{ $business->is_featured ? 'true' : 'false' }}">
                                                        <i class="bi {{ $business->is_featured ? 'bi-star-fill' : 'bi-star' }} text-lg"></i>
                                                    </button>
                                                @endif
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

            {{-- Tab Content: My Businesses (ONLY for Student/Alumni) --}}
            @auth
                @if(!auth()->user()->isAdmin())
                    <div x-show="activeTab === 'my'" class="p-6" style="display: none;">
                        <div class="mb-6 flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">My Businesses</h3>
                            <a href="/businesses/create" 
                               class="inline-flex items-center px-4 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Add Business
                            </a>
                        </div>

                        @if($myBusinesses->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($myBusinesses as $business)
                                    <div class="bg-white border border-gray-200 rounded-lg shadow hover:shadow-lg transition duration-150">
                                        @if($business->photos->first())
                                            <img src="{{ asset('storage/' . $business->photos->first()->photo_url) }}" 
                                                 alt="{{ $business->name }}" 
                                                 class="w-full h-48 object-cover rounded-t-lg">
                                        @else
                                            <div class="w-full h-48 bg-purple-100 rounded-t-lg flex items-center justify-center">
                                                <i class="bi bi-briefcase text-6xl text-purple-600"></i>
                                            </div>
                                        @endif

                                        <div class="p-5">
                                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $business->name }}</h3>
                                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $business->description }}</p>
                                            
                                            <div class="flex items-center justify-between mb-3">
                                                <span class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded">
                                                    {{ $business->businessType->name }}
                                                </span>
                                                <div class="flex items-center gap-2">
                                                    @if($business->is_featured)
                                                        <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded flex items-center gap-1">
                                                            <i class="bi bi-star-fill"></i>
                                                            Featured
                                                        </span>
                                                    @endif
                                                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">
                                                        <i class="bi bi-check-circle me-1"></i>
                                                        My Business
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                                                <a href="{{ route('businesses.show', $business) }}" 
                                                   class="text-purple-600 hover:text-purple-700 text-sm font-medium flex items-center gap-1">
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
                                <a href="/businesses/create" 
                                   class="mt-4 inline-flex items-center px-6 py-3 bg-gray-900 text-white rounded-lg font-medium shadow-sm hover:bg-gray-800 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Create Your First Business
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            @endauth
        </div>
    </div>

    {{-- JavaScript for Toggle Featured --}}
    @auth
        @if(auth()->user()->isAdmin())
            <script>
                function toggleFeatured(businessId, button) {
                    // Prevent double clicks
                    button.disabled = true;

                    fetch(`/businesses/${businessId}/toggle-featured`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({ _token: '{{ csrf_token() }}' })
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => {
                                const msg = err.message || 'Failed to update featured status';
                                showToast(msg, true);
                                throw new Error(msg);
                            }).catch(() => {
                                // If response isn't JSON
                                showToast('Failed to update featured status', true);
                                throw new Error('Network error');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data && data.success) {
                            // Update button appearance
                            const icon = button.querySelector('i');
                            if (data.is_featured) {
                                button.classList.remove('text-gray-400', 'hover:bg-gray-100');
                                button.classList.add('text-yellow-600', 'hover:bg-yellow-50');
                                icon.classList.remove('bi-star');
                                icon.classList.add('bi-star-fill');
                                button.setAttribute('title', 'Remove from Featured');
                                button.setAttribute('data-featured', 'true');
                            } else {
                                button.classList.remove('text-yellow-600', 'hover:bg-yellow-50');
                                button.classList.add('text-gray-400', 'hover:bg-gray-100');
                                icon.classList.remove('bi-star-fill');
                                icon.classList.add('bi-star');
                                button.setAttribute('title', 'Add to Featured');
                                button.setAttribute('data-featured', 'false');
                            }

                            showToast(data.message || 'Updated');
                        }
                    })
                    .catch(error => {
                        console.error('Toggle featured error:', error);
                    })
                    .finally(() => {
                        button.disabled = false;
                    });
                }

                function showToast(message, isError = false) {
                    let toast = document.getElementById('uco-toast');
                    if (!toast) {
                        toast = document.createElement('div');
                        toast.id = 'uco-toast';
                        toast.className = 'fixed top-6 right-6 z-50 max-w-xs';
                        document.body.appendChild(toast);
                    }

                    const item = document.createElement('div');
                    item.className = 'mb-2 px-4 py-2 rounded-lg shadow-sm text-sm text-white ' + (isError ? 'bg-red-600' : 'bg-gray-900');
                    item.textContent = message;
                    toast.appendChild(item);

                    setTimeout(() => {
                        item.classList.add('opacity-0', 'transition', 'duration-300');
                        setTimeout(() => item.remove(), 300);
                    }, 2500);
                }
            </script>
        @endif
    @endauth
</x-app-layout>