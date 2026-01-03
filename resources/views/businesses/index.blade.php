<x-app-layout>
    {{-- ✅ REMOVED: <x-slot name="header"> section --}}

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-4 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('import_errors'))
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700 font-medium mb-2">Import Errors (showing first 5):</p>
                    <ul class="list-disc list-inside text-xs text-red-600 space-y-1">
                        @foreach(session('import_errors') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <p class="text-xs text-red-500 mt-2 italic">Check Laravel logs for complete error details</p>
                </div>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700 font-medium">Errors:</p>
                    <ul class="list-disc list-inside text-xs text-red-600 space-y-1 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

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

                    {{-- Right: Admin Buttons --}}
                    @auth
                        @if(auth()->user()->isAdmin())
                            <div class="py-2 flex gap-2">
                                {{-- Import Button --}}
                                <button onclick="document.getElementById('importModal').classList.remove('hidden')"
                                   class="inline-flex items-center px-4 py-2.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    Import Excel
                                </button>
                                
                                {{-- Add Business Button --}}
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

            {{-- Search Bar --}}
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200"
                 x-data="{
                    search: '{{ request('search') }}',
                    isSearching: false,
                    performSearch() {
                        this.isSearching = true;
                        const trimmed = this.search.trim();
                        const myParam = '{{ request('my') }}';
                        let url = '{{ route('businesses.index') }}';
                        if (myParam) url += '?my=1';
                        if (trimmed.length > 0) {
                            url += (myParam ? '&' : '?') + 'search=' + encodeURIComponent(trimmed);
                        }
                        
                        fetch(url, {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'text/html'
                            }
                        })
                        .then(response => response.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const newContent = doc.querySelector('.bg-white.border.border-gray-200.rounded-xl.overflow-hidden');
                            if (newContent) {
                                const currentCard = document.querySelector('.bg-white.border.border-gray-200.rounded-xl.overflow-hidden');
                                if (currentCard) {
                                    currentCard.outerHTML = newContent.outerHTML;
                                }
                                window.history.pushState({}, '', url);
                            }
                            this.isSearching = false;
                        })
                        .catch(error => {
                            console.error('Search error:', error);
                            window.location.href = url;
                        });
                    }
                 }">
                <div class="flex gap-3">
                    <div class="flex-1">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" 
                                   x-model="search"
                                   @input.debounce.500ms="performSearch()"
                                   @keydown.enter="performSearch()"
                                   placeholder="Search by business name, description, owner, or category..." 
                                   class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            <div x-show="isSearching" class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    @if(request('search'))
                        <button type="button"
                                @click="search = ''; performSearch()"
                                class="inline-flex items-center px-4 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                            Clear
                        </button>
                    @endif
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
                                                    @if($business->position)
                                                        <span class="ml-1 text-purple-600">• {{ $business->position }}</span>
                                                    @endif
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

    {{-- Import Modal --}}
    @auth
        @if(auth()->user()->isAdmin())
            <div id="importModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Import Businesses from Excel</h3>
                        <button onclick="document.getElementById('importModal').classList.add('hidden')" 
                                class="text-gray-400 hover:text-gray-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <form action="{{ route('businesses.import') }}" method="POST" enctype="multipart/form-data" onsubmit="return validateImportFile()">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="import_file" class="block text-sm font-medium text-gray-700 mb-2">
                                Select Excel File (.xlsx, .xls, .csv)
                            </label>
                            <input type="file" 
                                   name="file" 
                                   id="import_file" 
                                   accept=".xlsx,.xls,.csv"
                                   required
                                   class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                            <p class="mt-1 text-xs text-gray-500">Maximum file size: 10 MB</p>
                        </div>

                        <div class="bg-blue-50 border-l-4 border-blue-400 p-3 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs text-blue-700">
                                        Make sure your Excel file has the following columns:<br>
                                        <span class="font-mono text-xs">Nama, Status dan Major, Email, Phone, Mobile, etc.</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit"
                                    class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                                Import
                            </button>
                            <button type="button"
                                    onclick="document.getElementById('importModal').classList.add('hidden')"
                                    class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <script>
                function validateImportFile() {
                    const fileInput = document.getElementById('import_file');
                    const file = fileInput.files[0];
                    
                    if (!file) {
                        alert('Please select a file to import');
                        return false;
                    }
                    
                    // Check file size (10 MB = 10 * 1024 * 1024 bytes)
                    const maxSize = 10 * 1024 * 1024;
                    if (file.size > maxSize) {
                        alert('File size exceeds 10 MB. Please select a smaller file.');
                        return false;
                    }
                    
                    // Check file extension
                    const fileName = file.name.toLowerCase();
                    const validExtensions = ['.xlsx', '.xls', '.csv'];
                    const isValidExtension = validExtensions.some(ext => fileName.endsWith(ext));
                    
                    if (!isValidExtension) {
                        alert('Please select a valid Excel file (.xlsx, .xls, or .csv)');
                        return false;
                    }
                    
                    return true;
                }
            </script>
        @endif
    @endauth
</x-app-layout>