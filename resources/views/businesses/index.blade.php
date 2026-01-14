@use('Illuminate\Support\Facades\Storage')

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

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
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
                    <h3 class="text-sm font-medium text-red-800">Validation Errors:</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(session('import_errors'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;"
             x-init="show = true">
            <div class="flex items-center justify-center min-h-screen px-4 py-6">
                <div @click="show = false" class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity"></div>
                
                <div class="relative bg-white rounded-xl shadow-xl max-w-2xl w-full border border-gray-200">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                    <svg class="h-5 w-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Import Completed with Errors</h3>
                                    <p class="text-sm text-gray-600 mt-1">Some rows were skipped during import</p>
                                </div>
                            </div>
                            <button @click="show = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 max-h-96 overflow-y-auto">
                            <ul class="space-y-2 text-sm text-red-700">
                                @foreach(session('import_errors') as $error)
                                    <li class="flex items-start gap-2">
                                        <span class="text-red-500 mt-0.5">•</span>
                                        <span>{{ $error }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        
                        <div class="mt-4 flex justify-end">
                            <button @click="show = false" 
                                    class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                                Close
                            </button>
                        </div>
                    </div>
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
        {{-- Tabs Navigation with Admin Button (Hidden for Admin) --}}
        <div class="bg-white shadow-sm sm:rounded-lg">
            @auth
                @if(!auth()->user()->isAdmin())
                    {{-- Show tabs only for non-admin users --}}
                    <div class="border-b border-gray-200">
                        <div class="flex justify-between items-center px-6">
                            {{-- Left: Tabs --}}
                            <nav class="flex -mb-px">
                                {{-- My Businesses Tab FIRST for non-admin --}}
                                <button @click="activeTab = 'my'" 
                                        :class="activeTab === 'my' ? 'border-gray-900 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                        class="flex items-center gap-2 py-4 px-4 border-b-2 font-medium text-sm transition duration-150">
                                    <i class="bi bi-briefcase"></i>
                                    My Businesses
                                    <span :class="activeTab === 'my' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600'" class="px-2 py-0.5 rounded-full text-xs transition-colors">
                                        {{ $businesses->where('user_id', auth()->id())->count() }}
                                    </span>
                                </button>

                                {{-- Browse All Tab --}}
                                <button @click="activeTab = 'browse'" 
                                        :class="activeTab === 'browse' ? 'border-gray-900 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                        class="flex items-center gap-2 py-4 px-4 border-b-2 font-medium text-sm transition duration-150">
                                    <i class="bi bi-shop"></i>
                                    Browse All Businesses
                                    <span :class="activeTab === 'browse' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600'" class="px-2 py-0.5 rounded-full text-xs transition-colors">
                                        {{ $businesses->total() }}
                                    </span>
                                </button>
                            </nav>
                        </div>
                    </div>
                @else
                    {{-- Admin: Just header with buttons, no tabs --}}
                    <div class="px-6 py-4 flex justify-between items-center">
                        <h2 class="text-xl font-bold text-gray-900">All Businesses</h2>
                        <div class="flex gap-2">
                            {{-- Import Button --}}
                            <button onclick="document.getElementById('importModal').classList.remove('hidden')"
                               class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
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
                    </div>
                @endif
            @else
                {{-- Guest: Just Browse All tab --}}
                <div class="border-b border-gray-200">
                    <div class="px-6">
                        <nav class="flex -mb-px">
                            <button @click="activeTab = 'browse'" 
                                    :class="activeTab === 'browse' ? 'border-gray-900 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="flex items-center gap-2 py-4 px-4 border-b-2 font-medium text-sm transition duration-150">
                                <i class="bi bi-shop"></i>
                                Browse All Businesses
                                <span :class="activeTab === 'browse' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600'" class="px-2 py-0.5 rounded-full text-xs transition-colors">
                                    {{ $businesses->total() }}
                                </span>
                            </button>
                        </nav>
                    </div>
                </div>
            @endauth

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
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        @foreach($businesses as $business)
                            <a href="{{ route('businesses.show', $business) }}" class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden group block">
                                <div class="flex flex-col h-full">
                                    {{-- Header with Photo & Logo --}}
                                    <div class="relative h-56">
                                        {{-- Category Badge - Top Left --}}
                                        <div class="absolute top-3 left-3 z-10">
                                            <span class="inline-block text-xs bg-white/95 backdrop-blur-sm text-slate-800 px-3 py-1.5 rounded-full font-semibold shadow-md" 
                                                  title="{{ $business->businessType->name }}">
                                                {{ $business->businessType->name }}
                                            </span>
                                        </div>
                                        
                                        {{-- Business Photo Background --}}
                                        @php $firstPhoto = $business->photos->first()?->photo_url; @endphp
                                        @if($firstPhoto && Storage::exists($firstPhoto))
                                            <img src="{{ Storage::url($firstPhoto) }}" 
                                                 alt="{{ $business->name }}" 
                                                 class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center">
                                                <i class="bi bi-briefcase text-6xl text-slate-400"></i>
                                            </div>
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-black/10 to-transparent"></div>
                                        @endif
                                        
                                        {{-- Logo Overlay --}}
                                        <div class="absolute bottom-4 left-4 flex items-end gap-4">
                                            @php $logo = $business->logo_url; @endphp
                                            @if($logo && Storage::exists($logo))
                                                <div class="w-20 h-20 rounded-xl bg-white shadow-lg border-2 border-white overflow-hidden flex-shrink-0">
                                                    <img src="{{ Storage::url($logo) }}" 
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
                                                {{-- Owner Avatar (safe) --}}
                                                @php
                                                    $ownerPhoto = $business->user->profile_photo_url ?? null;
                                                    $ownerPhotoUrl = null;
                                                    if ($ownerPhoto) {
                                                        try {
                                                            if (Storage::exists($ownerPhoto)) {
                                                                $ownerPhotoUrl = Storage::url($ownerPhoto);
                                                            }
                                                        } catch (\Exception $e) {
                                                            // ignore Cloudinary API errors and fallback to initials
                                                            $ownerPhotoUrl = null;
                                                        }
                                                    }
                                                @endphp
                                                @if($ownerPhotoUrl)
                                                    <img src="{{ $ownerPhotoUrl }}" 
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
                                        <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100 mt-auto">
                                            @auth
                                                @if(auth()->user()->isAdmin())
                                                    <button 
                                                        onclick="event.preventDefault(); event.stopPropagation(); toggleFeatured({{ $business->id }}, this)"
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg transition-colors {{ $business->is_featured ? 'text-yellow-600 hover:bg-yellow-50' : 'text-gray-400 hover:bg-gray-100' }}"
                                                        title="{{ $business->is_featured ? 'Remove from Featured' : 'Add to Featured' }}"
                                                        data-featured="{{ $business->is_featured ? 'true' : 'false' }}">
                                                        <i class="bi {{ $business->is_featured ? 'bi-star-fill' : 'bi-star' }} text-lg"></i>
                                                    </button>
                                                @endif
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
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                @foreach($myBusinesses as $business)
                                    <a href="{{ route('businesses.show', $business) }}" class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden group block">
                                        <div class="flex flex-col h-full">
                                            {{-- Header with Photo & Logo --}}
                                            <div class="relative h-56">
                                                {{-- Category Badge - Top Left --}}
                                                <div class="absolute top-3 left-3 z-10">
                                                    <span class="inline-block text-xs bg-white/95 backdrop-blur-sm text-slate-800 px-3 py-1.5 rounded-full font-semibold shadow-md" 
                                                          title="{{ $business->businessType->name }}">
                                                        {{ $business->businessType->name }}
                                                    </span>
                                                </div>
                                                
                                                {{-- Business Photo Background --}}
                                                @php $myFirstPhoto = $business->photos->first()?->photo_url; @endphp
                                                @if($myFirstPhoto)
                                                    @php
                                                        $myFirstPhotoUrl = null;
                                                        try {
                                                            if (Storage::exists($myFirstPhoto)) {
                                                                $myFirstPhotoUrl = Storage::url($myFirstPhoto);
                                                            }
                                                        } catch (\Exception $e) {
                                                            $myFirstPhotoUrl = null;
                                                        }
                                                    @endphp
                                                    @if($myFirstPhotoUrl)
                                                        <img src="{{ $myFirstPhotoUrl }}" alt="{{ $business->name }}" class="w-full h-full object-cover">
                                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                                                    @else
                                                        <div class="w-full h-full bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center">
                                                            <i class="bi bi-briefcase text-6xl text-slate-400"></i>
                                                        </div>
                                                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-black/10 to-transparent"></div>
                                                    @endif
                                                @else
                                                    <div class="w-full h-full bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center">
                                                        <i class="bi bi-briefcase text-6xl text-slate-400"></i>
                                                    </div>
                                                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-black/10 to-transparent"></div>
                                                @endif
                                                
                                                {{-- Logo Overlay --}}
                                                <div class="absolute bottom-4 left-4 flex items-end gap-4">
                                                    @php $myLogo = $business->logo_url ?? null; $myLogoUrl = null; @endphp
                                                    @if($myLogo)
                                                        @php
                                                            try {
                                                                if (Storage::exists($myLogo)) {
                                                                    $myLogoUrl = Storage::url($myLogo);
                                                                }
                                                            } catch (\Exception $e) {
                                                                $myLogoUrl = null;
                                                            }
                                                        @endphp
                                                    @endif
                                                    @if($myLogoUrl)
                                                        <div class="w-20 h-20 rounded-xl bg-white shadow-lg border-2 border-white overflow-hidden flex-shrink-0">
                                                            <img src="{{ $myLogoUrl }}" alt="{{ $business->name }} logo" class="w-full h-full object-cover">
                                                        </div>
                                                    @else
                                                        <div class="w-20 h-20 rounded-xl bg-white shadow-lg border-2 border-white flex items-center justify-center flex-shrink-0">
                                                            <i class="bi bi-building text-3xl text-slate-400"></i>
                                                        </div>
                                                    @endif
                                                    
                                                    {{-- My Business Badge --}}
                                                    <div class="mb-1 flex gap-2">
                                                        <span class="inline-flex items-center gap-1.5 text-xs bg-green-500 text-white px-2.5 py-1.5 rounded-lg font-bold shadow-sm">
                                                            <i class="bi bi-check-circle-fill"></i>
                                                            My Business
                                                        </span>
                                                    </div>
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
                                                        {{-- Owner Avatar (safe) --}}
                                                        @php
                                                            $ownerPhoto = $business->user->profile_photo_url ?? null;
                                                            $ownerPhotoUrl = null;
                                                            if ($ownerPhoto) {
                                                                try {
                                                                    if (Storage::exists($ownerPhoto)) {
                                                                        $ownerPhotoUrl = Storage::url($ownerPhoto);
                                                                    }
                                                                } catch (\Exception $e) {
                                                                    $ownerPhotoUrl = null;
                                                                }
                                                            }
                                                        @endphp
                                                        @if($ownerPhotoUrl)
                                                            <img src="{{ $ownerPhotoUrl }}" 
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
                                                <div class="flex items-center justify-between pt-3 border-t border-gray-100 mt-auto">
                                                    <button onclick="event.preventDefault(); event.stopPropagation(); window.location.href='{{ route('businesses.edit', $business) }}'" 
                                                       class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-semibold rounded-lg transition">
                                                        <i class="bi bi-pencil"></i>
                                                        Edit Business
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
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

    {{-- Import Modal - PURE HTML NO JS INTERFERENCE --}}
    @auth
        @if(auth()->user()->isAdmin())
            <div id="importModal" 
                 class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20">
                    <div onclick="document.getElementById('importModal').classList.add('hidden')" 
                         class="fixed inset-0 bg-gray-900 bg-opacity-50"></div>

                    <div class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full border border-soft-gray-200">
                        
                        <form action="{{ route('businesses.import') }}" 
                              method="POST" 
                              enctype="multipart/form-data">
                            @csrf
                            
                            {{-- Modal Header --}}
                            <div class="px-8 pt-8 pb-6 bg-gradient-to-br from-soft-gray-50 to-white border-b border-soft-gray-100">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-start gap-4">
                                        <div class="flex items-center justify-center flex-shrink-0 w-14 h-14 bg-soft-gray-900 rounded-2xl shadow-lg">
                                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-2xl font-bold text-soft-gray-900 tracking-tight">
                                                Import Businesses
                                            </h3>
                                            <p class="text-sm text-soft-gray-600 mt-1">
                                                Upload Excel file to bulk import businesses
                                            </p>
                                        </div>
                                    </div>
                                    <button type="button" 
                                            onclick="document.getElementById('importModal').classList.add('hidden')"
                                            class="text-soft-gray-400 hover:text-soft-gray-600 transition-colors ml-4">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            {{-- Modal Body --}}
                            <div class="px-8 py-6 space-y-5">
                                {{-- File Upload --}}
                                <div>
                                    <label class="block text-sm font-semibold text-soft-gray-900 mb-3">
                                        Select Excel File
                                    </label>
                                    <div class="relative">
                                        <input type="file" 
                                               name="file"
                                               accept=".xlsx,.xls"
                                               required
                                               class="block w-full text-sm text-soft-gray-900 file:mr-4 file:py-3 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-soft-gray-900 file:text-white hover:file:bg-soft-gray-800 file:cursor-pointer border border-soft-gray-300 rounded-xl cursor-pointer bg-soft-gray-50 focus:outline-none focus:border-soft-gray-900 focus:ring-2 focus:ring-soft-gray-900 focus:ring-opacity-20 transition-all">
                                    </div>
                                    <p class="mt-2 text-xs text-soft-gray-500">
                                        Supported formats: <span class="font-semibold">.xlsx, .xls</span> • Maximum size: <span class="font-semibold">10MB</span>
                                    </p>
                                </div>

                                {{-- Required Columns Info --}}
                                <div class="bg-soft-gray-50 border-l-4 border-soft-gray-900 rounded-r-xl p-4">
                                    <div class="flex gap-3">
                                        <svg class="w-5 h-5 text-soft-gray-700 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div class="flex-1">
                                            <p class="text-sm font-semibold text-soft-gray-900 mb-2">Required Excel Columns:</p>
                                            <div class="flex flex-wrap gap-2">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-mono font-medium bg-white border border-soft-gray-200 text-soft-gray-700">Nama</span>
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-mono font-medium bg-white border border-soft-gray-200 text-soft-gray-700">Email</span>
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-mono font-medium bg-white border border-soft-gray-200 text-soft-gray-700">Phone</span>
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-mono font-medium bg-white border border-soft-gray-200 text-soft-gray-700">Category</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Important Notes --}}
                                <div class="space-y-2">
                                    <p class="text-xs font-semibold text-soft-gray-700 uppercase tracking-wider">Important Notes:</p>
                                    <ul class="space-y-1.5 text-xs text-soft-gray-600">
                                        <li class="flex items-start gap-2">
                                            <svg class="w-4 h-4 text-soft-gray-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span>Existing business names will be skipped to prevent duplicates</span>
                                        </li>
                                        <li class="flex items-start gap-2">
                                            <svg class="w-4 h-4 text-soft-gray-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span>Missing business mode will default to <span class="font-mono font-semibold">product</span></span>
                                        </li>
                                        <li class="flex items-start gap-2">
                                            <svg class="w-4 h-4 text-soft-gray-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span>All data will be validated before import</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            {{-- Modal Footer --}}
                            <div class="px-8 py-5 bg-soft-gray-50 border-t border-soft-gray-100 flex justify-end gap-3">
                                <button type="button"
                                        onclick="document.getElementById('importModal').classList.add('hidden')"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-soft-gray-700 bg-white border border-soft-gray-300 rounded-xl hover:bg-soft-gray-50 hover:border-soft-gray-400 transition-all duration-200">
                                    Cancel
                                </button>
                                <button type="submit"
                                        class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white bg-soft-gray-900 rounded-xl hover:bg-soft-gray-800 shadow-md hover:shadow-lg transition-all duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    Import Businesses
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endauth
</x-app-layout>