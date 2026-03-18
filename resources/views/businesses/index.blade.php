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

    {{-- Main Content --}}
    @php
        // Default tab: for authenticated non-admin users default to 'my', otherwise 'all'
        if (auth()->check() && !auth()->user()->isAdmin()) {
            $initialTab = request('tab', 'my');
        } else {
            $initialTab = request('tab', 'all');
        }
    @endphp
    <div class="businesses-wrapper max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ activeTab: '{{ $initialTab }}', showImportModal: false }" x-cloak>
        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Businesses</h1>
                <p class="text-sm text-gray-600 mt-1">Manage and discover businesses</p>
            </div>

            <div class="flex items-center gap-3">
                @auth
                    @if(auth()->user()->isAdmin())
                        <button type="button" 
                                @click="showImportModal = true" 
                                class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 text-sm font-medium rounded-xl text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            Import Excel
                        </button>
                        <a href="{{ route('businesses.create') }}" 
                           class="inline-flex items-center px-4 py-2.5 bg-soft-gray-900 text-white text-sm font-medium rounded-xl hover:bg-soft-gray-800 transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Business
                        </a>
                    @endif
                @endauth
            </div>
        </div>

        {{-- Search and Filter Card --}}
        <div class="bg-white border border-gray-200 rounded-xl p-4 mb-6 shadow-sm">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1" 
                     x-data="{
                        search: '{{ request('search') }}',
                        isSearching: false,
                        performSearch() {
                            this.isSearching = true;
                            const trimmed = this.search.trim();
                            const url = trimmed.length > 0 
                                ? '{{ route('businesses.index') }}?search=' + encodeURIComponent(trimmed)
                                : '{{ route('businesses.index') }}';
                            
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
                                const newContent = doc.querySelector('#content-container');
                                if (newContent) {
                                    document.querySelector('#content-container').innerHTML = newContent.innerHTML;
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
                               placeholder="Search businesses..." 
                               class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-uco-orange-500 focus:border-uco-orange-500 transition-all">
                        <div x-show="isSearching" class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Client-side Tabs for non-admin users: All / My --}}
                @auth
                    @if(!auth()->user()->isAdmin())
                        <div role="tablist" class="inline-flex rounded-xl bg-gray-100 p-1 gap-1 border border-gray-200 self-start">
                            <button @click="activeTab = 'my'" type="button" role="tab" :aria-selected="activeTab === 'my'" :class="activeTab === 'my' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-2 rounded-lg text-sm font-medium transition-all duration-200">My Businesses</button>
                            <button @click="activeTab = 'all'" type="button" role="tab" :aria-selected="activeTab === 'all'" :class="activeTab === 'all' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-2 rounded-lg text-sm font-medium transition-all duration-200">All Businesses</button>
                        </div>
                    @endif
                @endauth
            </div>
        </div>

        @if (session('success'))
            <div class="mb-4 text-green-700">{{ session('success') }}</div>
        @endif

        <div id="content-container">
            {{-- All Businesses (visible when activeTab === 'all') --}}
            <div x-show="activeTab === 'all'" x-transition.opacity class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @if ($businesses->count() > 0)
                @foreach ($businesses as $business)
                    <div class="bg-white border rounded-xl overflow-hidden hover:shadow-md transition-all duration-200 relative group"
                         x-data="{ 
                            isFeatured: {{ $business->is_featured ? 'true' : 'false' }}, 
                            isToggling: false,
                            toggleFeatured() {
                                if(this.isToggling) return;
                                this.isToggling = true;
                                fetch('{{ route('businesses.toggle-featured', $business) }}', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(res => res.json())
                                .then(data => {
                                    this.isFeatured = data.is_featured;
                                })
                                .finally(() => {
                                    this.isToggling = false;
                                });
                            }
                         }">
                        
                        @auth
                            @if(auth()->user()->isAdmin())
                                <div class="absolute top-4 right-4 z-10 flex items-center gap-2">
                                    <span class="text-[10px] font-bold uppercase tracking-wider transition-colors duration-200" :class="isFeatured ? 'text-yellow-600' : 'text-gray-400'">Featured</span>
                                    <button type="button" 
                                            @click.prevent="toggleFeatured()"
                                            :disabled="isToggling"
                                            :class="isFeatured ? 'bg-yellow-400' : 'bg-gray-200 hover:bg-gray-300'"
                                            class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none shadow-inner disabled:opacity-50">
                                        <span class="sr-only">Toggle featured</span>
                                        <span aria-hidden="true" 
                                              :class="isFeatured ? 'translate-x-4' : 'translate-x-0'"
                                              class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                                    </button>
                                </div>
                            @endif
                        @endauth

                        <a href="{{ route('businesses.show', $business) }}" class="block p-5 h-full">
                            <div class="flex items-start gap-4">
                                @php
                                    $logo = $business->logo_url ?? null;
                                    $logoUrl = $logo ? storage_image_url($logo, 'logo_thumb') : null;
                                @endphp
                                @if ($logoUrl)
                                    <img src="{{ $logoUrl }}" alt="{{ $business->name }}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl object-cover shadow-sm ring-1 ring-gray-900/5 mt-1">
                                @else
                                    <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl flex items-center justify-center text-gray-400 shadow-sm ring-1 ring-gray-900/5 mt-1">
                                        <i class="bi bi-building text-2xl sm:text-3xl"></i>
                                    </div>
                                @endif

                                <div class="flex-1 min-w-0 pr-20 lg:pr-24">
                                    <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                                        <h3 class="font-bold text-gray-900 text-lg truncate group-hover:text-soft-gray-900 transition-colors">{{ $business->name }}</h3>
                                        @if($business->businessType)
                                            <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-[10px] font-semibold uppercase tracking-wider text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                                {{ $business->businessType->name }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="min-h-[2.5rem] mb-3">
                                        <p class="text-sm text-gray-600 line-clamp-2 leading-relaxed">{{ $business->description ?: 'No description provided' }}</p>
                                    </div>
                                    
                                    <div class="flex flex-wrap items-center gap-4 text-xs text-gray-500 font-medium mt-auto">
                                        @if($business->user)
                                            <div class="flex items-center gap-1.5 text-gray-600 bg-gray-50 px-2 py-1 rounded-lg">
                                                <i class="bi bi-person-fill text-gray-400"></i>
                                                <span class="truncate max-w-[120px]">{{ $business->user->name }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            @else
                <div class="col-span-1 md:col-span-2 text-center py-12 text-gray-500">No businesses found.</div>
            @endif
            {{-- Pagination Links Table --}}
            <div class="mt-8 col-span-1 md:col-span-2">
                {{ $businesses->links() }}
            </div>
        </div>

        @auth
            {{-- My Businesses (visible when activeTab === 'my') --}}
            <div x-show="activeTab === 'my'" x-transition.opacity class="col-span-1 md:col-span-2">
            <div class="p-6 bg-white border border-slate-200 rounded-xl shadow-sm">
                <div class="mb-6 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">My Businesses</h3>
                    <a href="{{ route('businesses.create') }}" class="inline-flex items-center px-4 py-2.5 bg-soft-gray-900 text-white text-sm font-medium rounded-xl hover:bg-soft-gray-800 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Business
                    </a>
                </div>

                @if(($myBusinesses ?? collect())->count() > 0)
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        @foreach($myBusinesses as $b)
                            <a href="{{ route('businesses.show', $b) }}" class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-200 overflow-hidden block">
                                <div class="p-4 flex items-center gap-4">
                                    @php $myLogo = $b->logo_url ?? null; $myLogoUrl = $myLogo ? storage_image_url($myLogo, 'logo_thumb') : null; @endphp
                                    @if($myLogoUrl)
                                        <img src="{{ $myLogoUrl }}" alt="{{ $b->name }}" class="w-12 h-12 rounded-lg object-cover">
                                    @else
                                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400"><i class="bi bi-briefcase"></i></div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-semibold text-gray-900 truncate mb-1">{{ $b->name }}</h4>
                                        <div class="min-h-[2.5rem]">
                                            <p class="text-sm text-gray-500 line-clamp-2 overflow-hidden">{{ $b->description ?: 'No description provided' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-600 mb-4">You don't have any businesses yet.</p>
                        <a href="{{ route('businesses.create') }}" class="inline-flex items-center px-4 py-2 bg-soft-gray-900 text-white rounded-xl hover:bg-soft-gray-800">Create Your First Business</a>
                    </div>
                @endif
            </div>
            </div>
        @endauth
        </div>

    {{-- Duplicate 'My Businesses' block removed; kept only the tabbed 'my' section above. --}}

    @auth
        @if(auth()->user()->isAdmin())
        {{-- Import Modal - Elegant Professional Design --}}
        <div x-show="showImportModal" 
             x-cloak
             class="fixed inset-0 z-[100] overflow-y-auto" 
             aria-labelledby="modal-title" 
             role="dialog" 
             aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                {{-- Background overlay with blur --}}
                <div x-show="showImportModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click="showImportModal = false"
                     class="fixed inset-0 transition-opacity bg-gray-900/60 backdrop-blur-sm" 
                     aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                {{-- Modal panel --}}
                <div x-show="showImportModal"
                     x-transition:enter="ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200 transform"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                     class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-gray-100">
                    
                    <form action="{{ route('businesses.import') }}" method="POST" enctype="multipart/form-data" id="importForm"
                          x-data="{ 
                              isDragging: false, 
                              fileName: null,
                              fileSize: null,
                              handleDrop(e) {
                                  this.isDragging = false;
                                  if (e.dataTransfer.files.length > 0) {
                                      const file = e.dataTransfer.files[0];
                                      this.updateFileInfo(file);
                                      document.getElementById('fileInputBusinesses').files = e.dataTransfer.files;
                                  }
                              },
                              handleFileSelect(e) {
                                  if (e.target.files.length > 0) {
                                      this.updateFileInfo(e.target.files[0]);
                                  }
                              },
                              updateFileInfo(file) {
                                  this.fileName = file.name;
                                  this.fileSize = (file.size / 1024 / 1024).toFixed(2) + ' MB';
                              }
                          }">
                        @csrf
                        
                        {{-- Modal Header --}}
                        <div class="px-8 pt-8 pb-6 bg-gradient-to-br from-soft-gray-50 to-white border-b border-gray-100">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start gap-4">
                                    <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 bg-white rounded-xl shadow border border-gray-100">
                                        <svg class="w-6 h-6 text-soft-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-900 tracking-tight" id="modal-title">
                                            Import Businesses
                                        </h3>
                                        <p class="text-sm text-gray-500 mt-1">
                                            Upload Excel file to bulk import businesses
                                        </p>
                                    </div>
                                </div>
                                <button type="button" 
                                        @click="showImportModal = false"
                                        class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-2 rounded-xl transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Modal Body --}}
                        <div class="px-8 py-6 space-y-6">
                            {{-- Drag & Drop File Upload --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-3">
                                    Upload File
                                </label>
                                <div class="relative group">
                                    <div @dragover.prevent="isDragging = true"
                                         @dragleave.prevent="isDragging = false"
                                         @drop.prevent="handleDrop($event)"
                                         :class="isDragging ? 'border-soft-gray-900 bg-soft-gray-50 ring-4 ring-soft-gray-900/10' : 'border-gray-300 hover:border-soft-gray-400 bg-gray-50 hover:bg-gray-100/50'"
                                         class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed rounded-xl cursor-pointer transition-all duration-200 ease-in-out relative overflow-hidden">
                                        
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4">
                                            <template x-if="!fileName">
                                                <div class="flex flex-col items-center transition-all">
                                                    <div class="p-3 bg-white rounded-full shadow-sm border border-gray-100 mb-3 group-hover:scale-110 transition-transform duration-200">
                                                        <svg class="w-6 h-6 text-soft-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                    </div>
                                                    <p class="mb-1 text-sm font-semibold text-gray-700">Click to upload or drag and drop</p>
                                                    <p class="text-xs text-gray-500 bg-white px-2 py-1 rounded-md border border-gray-200 shadow-sm mt-2">XLS, XLSX (Max. 10MB)</p>
                                                </div>
                                            </template>

                                            <template x-if="fileName">
                                                <div class="flex flex-col items-center w-full transition-all">
                                                    <div class="p-3 bg-green-50 rounded-full border border-green-100 mb-3">
                                                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </div>
                                                    <p class="mb-1 text-sm font-bold text-gray-900 truncate max-w-xs" x-text="fileName"></p>
                                                    <p class="text-xs text-gray-500 font-medium" x-text="fileSize"></p>
                                                </div>
                                            </template>
                                        </div>

                                        <input type="file" 
                                               id="fileInputBusinesses"
                                               name="file" 
                                               accept=".xlsx,.xls"
                                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                               required
                                               @change="handleFileSelect($event)">
                                    </div>
                                </div>
                            </div>

                            {{-- Required Columns Info --}}
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                                <div class="flex gap-3">
                                    <div class="flex-shrink-0 mt-0.5">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-900 mb-2">Required Excel Columns:</p>
                                        <div class="flex flex-wrap gap-2">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-mono font-medium bg-white border border-gray-200 text-gray-700 shadow-sm">Nama Bisnis</span>
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-mono font-medium bg-white border border-gray-200 text-gray-700 shadow-sm">Kategori</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Modal Footer --}}
                        <div class="px-8 py-5 bg-gray-50 border-t border-gray-100 flex justify-end gap-3 rounded-b-2xl">
                            <button type="button"
                                    @click="showImportModal = false"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white bg-soft-gray-900 rounded-xl hover:bg-soft-gray-800 shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
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
    </div>

</x-app-layout>