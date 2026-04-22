@use('Illuminate\Support\Facades\Storage')

<x-app-layout>




    {{-- Main Content --}}
    <div class="businesses-wrapper max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{
        showImportModal: {{ request('import') ? 'true' : 'false' }},
        search: '{{ request('search') }}',
        selectedType: '{{ request('type', '') }}',
        isSearching: false,
        performSearch() {
            this.isSearching = true;
            const params = new URLSearchParams();
            if (this.search.trim()) params.append('search', this.search.trim());
            if (this.selectedType) params.append('type', this.selectedType);
    
            const url = '{{ route('businesses.index') }}?' + params.toString();
    
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
                        const container = document.querySelector('#content-container');
                        container.innerHTML = newContent.innerHTML;
    
                        // Push to history
                        window.history.pushState({}, '', url);
    
                        // Important: Re-initialize animations for new elements
                        if (typeof window.initRevealOnScroll === 'function') {
                            window.initRevealOnScroll();
                        }
                    }
                    this.isSearching = false;
                })
                .catch(error => {
                    console.error('Search error:', error);
                    window.location.href = url;
                });
        }
    }" x-cloak>

        @php $activeImportId = session('active_business_import_id'); @endphp
        @if ($activeImportId)
            <div x-data="{
                importId: '{{ $activeImportId }}',
                progress: 0,
                current: 0,
                total: 0,
                success: 0,
                skipped: 0,
                errors: [],
                status: 'processing',
                show: true,
                zombieCount: 0,
                summaryVisible: false,
                lastRefreshSuccess: 0,
                lastRefreshTime: Date.now(),
                poll() {
                    if (this.status === 'completed' || this.status === 'failed') return;
            
                    fetch(`/import-progress/${this.importId}?type=business`, {
                        headers: { 'Accept': 'application/json' }
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (!data.total && !data.current && this.status === 'processing') {
                                this.zombieCount++;
                                if (this.zombieCount > 8) {
                                    this.clearSession(false);
                                    this.show = false;
                                    return;
                                }
                            } else {
                                this.zombieCount = 0;
                            }
            
                            this.current = data.current || this.current;
                            this.total = data.total || this.total;
                            this.status = data.status || this.status;
                            this.success = data.success || 0;
                            this.skipped = data.skipped || 0;
                            this.errors = data.errors || [];
            
                            if (this.total > 0) {
                                this.progress = Math.min(100, Math.round((this.current / this.total) * 100));
                            }
                            
                            if (this.success > this.lastRefreshSuccess && Date.now() - this.lastRefreshTime > 3000) {
                                this.refreshList();
                                this.lastRefreshSuccess = this.success;
                                this.lastRefreshTime = Date.now();
                            }
            
                            if (this.status === 'completed' || (this.total > 0 && this.current >= this.total)) {
                                this.progress = 100;
                                this.status = 'completed';
                                this.summaryVisible = true;
                                this.refreshList();
                                this.clearSession(false);
                                setTimeout(() => { this.show = false; }, 8000);
                            } else if (this.status === 'failed') {
                                this.summaryVisible = true;
                                this.clearSession(false);
                            } else {
                                setTimeout(() => this.poll(), 1500);
                            }
                        })
                        .catch(err => {
                            console.error('Polling error:', err);
                            setTimeout(() => this.poll(), 5000);
                        });
                },
                refreshList() {
                    const url = new URL('{{ route('businesses.index') }}');
                    url.searchParams.append('_t', Date.now());
                    
                    fetch(url.toString(), {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(res => res.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const newContent = doc.querySelector('#content-container');
                            const currentContent = document.querySelector('#content-container');
                            if (newContent && currentContent) {
                                // Prevent invisible cards from reveal-on-scroll CSS by forcing visibility on injected DOM
                                newContent.querySelectorAll('.reveal-on-scroll').forEach(el => {
                                    el.classList.add('is-visible');
                                });
                                currentContent.innerHTML = newContent.innerHTML;
                                if (window.Alpine) window.Alpine.initTree(currentContent);
                            }
                        });
                },
                clearSession(reload = false) {
                    fetch('{{ route('import.clear') }}?type=business', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    }).then(() => {
                        if (reload) window.location.reload();
                    });
                },
                manualClose() {
                    this.show = false;
                    this.clearSession(false);
                }
            }" x-init="$nextTick(() => poll())" x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform -translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-4"
                class="bg-white border border-uco-orange-200 rounded-2xl p-5 shadow-sm mb-8 relative overflow-hidden">

                <button type="button" @click="manualClose()"
                    class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 transition-colors p-1 z-20">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>

                <div class="absolute top-0 left-0 h-1 w-full">
                    <div class="h-full transition-all duration-700 ease-out"
                        :class="summaryVisible ? (status === 'completed' ? 'bg-green-400' : 'bg-red-400') : 'bg-uco-orange-500'"
                        :style="`width: ${progress}%`"></div>
                </div>

                {{-- ① PROCESSING STATE --}}
                <div x-show="!summaryVisible" class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-4 text-left">
                        <div
                            class="flex-shrink-0 w-12 h-12 bg-uco-orange-50 rounded-xl flex items-center justify-center">
                            <svg class="animate-spin h-6 w-6 text-uco-orange-500" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Importing Businesses...</h3>
                            <p class="text-xs text-gray-500 mt-0.5"
                                x-text="total > 0 ? `Processing row ${current} of ${total}` : 'Preparing import job...'">
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div
                            class="text-center px-4 py-2.5 rounded-xl bg-green-50 border border-green-100 min-w-[68px]">
                            <p class="text-lg font-bold text-green-700 leading-none" x-text="success"></p>
                            <p class="text-[10px] uppercase tracking-wider text-green-500 font-semibold mt-1">Imported
                            </p>
                        </div>
                        <div
                            class="text-center px-4 py-2.5 rounded-xl bg-amber-50 border border-amber-100 min-w-[68px]">
                            <p class="text-lg font-bold text-amber-700 leading-none" x-text="skipped"></p>
                            <p class="text-[10px] uppercase tracking-wider text-amber-500 font-semibold mt-1">Skipped
                            </p>
                        </div>
                        <div class="text-center px-4 py-2.5 rounded-xl bg-gray-50 border border-gray-200 min-w-[68px]">
                            <p class="text-lg font-bold text-gray-600 leading-none" x-text="total"></p>
                            <p class="text-[10px] uppercase tracking-wider text-gray-400 font-semibold mt-1">Total</p>
                        </div>
                    </div>
                </div>

                {{-- ② SUMMARY / COMPLETION STATE --}}
                <div x-show="summaryVisible" x-transition class="space-y-4">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-11 h-11 rounded-xl flex items-center justify-center border"
                                :class="status === 'completed' ? 'bg-green-50 border-green-100' : 'bg-red-50 border-red-100'">
                                <svg x-show="status === 'completed'" class="h-5 w-5 text-green-600" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <svg x-show="status !== 'completed'" class="h-5 w-5 text-amber-500" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-gray-900"
                                    x-text="status === 'completed' ? 'Import Complete' : 'Import Complete with Issues'">
                                </h3>
                                <p class="text-xs text-gray-400 mt-0.5">All rows processed &middot; See breakdown below
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div
                                class="text-center px-4 py-3 rounded-xl bg-green-50 border border-green-200 min-w-[76px]">
                                <p class="text-2xl font-bold text-green-700 leading-none" x-text="success"></p>
                                <p class="text-[10px] uppercase tracking-wider text-green-600 font-semibold mt-1.5">
                                    Imported</p>
                            </div>
                            <div
                                class="text-center px-4 py-3 rounded-xl bg-amber-50 border border-amber-200 min-w-[76px]">
                                <p class="text-2xl font-bold text-amber-700 leading-none" x-text="skipped"></p>
                                <p class="text-[10px] uppercase tracking-wider text-amber-600 font-semibold mt-1.5">
                                    Skipped</p>
                            </div>
                            <div
                                class="text-center px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 min-w-[76px]">
                                <p class="text-2xl font-bold text-gray-600 leading-none" x-text="total"></p>
                                <p class="text-[10px] uppercase tracking-wider text-gray-400 font-semibold mt-1.5">
                                    Total</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        @endif
        {{-- Page Header --}}
        <section
            class="relative overflow-hidden rounded-3xl border border-uco-orange-100 bg-white px-6 py-8 shadow-sm md:px-8 md:py-10 mb-8">
            <div class="uco-hero-mesh"></div>
            <div class="relative z-10 flex flex-col gap-6 md:flex-row md:items-end md:justify-between text-left">
                <div class="space-y-2 reveal-on-scroll">
                    <span
                        class="inline-flex items-center rounded-full border border-uco-orange-200 bg-uco-orange-50 px-4 py-1.5 text-xs font-semibold uppercase tracking-wide text-uco-orange-700">
                        UCO Directory
                    </span>
                    <h1 class="text-3xl font-extrabold text-soft-gray-900 md:text-4xl">Business Management</h1>
                    <p class="text-sm text-soft-gray-600 mt-1">Manage platform businesses and categories.</p>
                </div>

                <div class="reveal-on-scroll relative z-10 flex items-center gap-3" style="transition-delay: 100ms;">
                    @auth
                        @if (auth()->user()->isAdmin())
                            <button type="button" @click="showImportModal = true"
                                class="inline-flex items-center rounded-xl border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-700 shadow-sm transition-colors hover:bg-gray-50">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                    </path>
                                </svg>
                                Import Excel
                            </button>
                            <a href="{{ route('businesses.create') }}"
                                class="bg-uco-orange-500 shadow-uco-orange-200 inline-flex items-center gap-2 rounded-xl px-5 py-3 text-sm font-semibold text-white shadow-md transition hover:-translate-y-0.5 hover:bg-uco-orange-600">
                                <i class="bi bi-plus-circle"></i>
                                Add Business
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </section>

        {{-- Search and Filter Card --}}
        <div class="bg-white border border-gray-200 rounded-xl p-4 mb-8 shadow-sm space-y-2" x-data="{
            search: '{{ request('search') }}',
            selectedType: '{{ request('type') }}',
            isSearching: false,
            performSearch() {
                this.isSearching = true;
                const params = new URLSearchParams();
                if (this.search.trim()) params.append('search', this.search.trim());
                if (this.selectedType) params.append('type', this.selectedType);
        
                const url = '{{ route('businesses.index') }}' + (params.toString() ? '?' + params.toString() : '');
        
                window.location.href = url;
            }
        }">
            <div class="flex gap-3">
                {{-- Search Input --}}
                <div class="flex-1">
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400 group-focus-within:text-uco-orange-500 transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" x-model="search" @input.debounce.500ms="performSearch()"
                            @keydown.enter="performSearch()"
                            placeholder="Search businesses by name, description, or category..."
                            class="block w-full pl-10 pr-12 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-uco-orange-500 focus:border-uco-orange-500 transition-all shadow-sm">

                        <div x-show="isSearching" class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg class="animate-spin h-5 w-5 text-uco-orange-500" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>

                        <button x-show="search" @click="search = ''; performSearch()"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600"
                            x-transition.opacity>
                            <i class="bi bi-x-circle-fill"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Category Filter Chips --}}

            <div class="flex items-center gap-3 overflow-x-auto py-4 scrollbar-hide -mx-1 px-1">
                <button @click="selectedType = ''; performSearch()"
                    :class="selectedType === '' ?
                        'bg-soft-gray-900 text-white shadow-lg shadow-gray-200 ring-4 ring-gray-900/10' :
                        'bg-white text-gray-600 border-gray-200 hover:border-soft-gray-300 hover:bg-gray-50'"
                    class="whitespace-nowrap px-6 py-3 rounded-full text-sm font-bold border transition-all duration-300">
                    All Categories
                </button>
                @foreach ($businessTypes as $type)
                    <button @click="selectedType = '{{ $type->id }}'; performSearch()"
                        :class="selectedType === '{{ $type->id }}' ?
                            'bg-uco-orange-500 text-white shadow-lg shadow-uco-orange-100 ring-4 ring-uco-orange-500/10' :
                            'bg-white text-gray-600 border-gray-200 hover:border-soft-gray-300 hover:bg-gray-50'"
                        class="whitespace-nowrap px-6 py-3 rounded-full text-sm font-bold border transition-all duration-300">
                        {{ $type->name }}
                    </button>
                @endforeach
            </div>
        </div>



        <div id="content-container">
            {{-- All Businesses --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($businesses as $business)
                    @php $delay = ($loop->index % 12) * 50; @endphp
                    <div class="bg-white border rounded-xl overflow-hidden hover:-translate-y-1 hover:border-uco-orange-300 hover:shadow-xl transition-all duration-300 relative group reveal-on-scroll"
                        style="transition-delay: {{ $delay }}ms;" x-data="{
                            isFeatured: {{ $business->is_featured ? 'true' : 'false' }},
                            isToggling: false,
                            toggleFeatured() {
                                if (this.isToggling) return;
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
                            @if (auth()->user()->isAdmin())
                                <div class="absolute top-4 right-4 z-10 flex items-center gap-2">
                                    <span
                                        class="text-[10px] font-bold uppercase tracking-wider transition-colors duration-200"
                                        :class="isFeatured ? 'text-yellow-600' : 'text-gray-400'">Featured</span>
                                    <button type="button" @click.prevent="toggleFeatured()" :disabled="isToggling"
                                        :class="isFeatured ? 'bg-yellow-400' : 'bg-gray-200 hover:bg-gray-300'"
                                        class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none shadow-inner disabled:opacity-50">
                                        <span class="sr-only">Toggle featured</span>
                                        <span aria-hidden="true" :class="isFeatured ? 'translate-x-4' : 'translate-x-0'"
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
                                    <img src="{{ $logoUrl }}" alt="{{ $business->name }}"
                                        class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl object-contain aspect-square shadow-sm ring-1 ring-gray-900/5 mt-1">
                                @else
                                    <div
                                        class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl flex items-center justify-center text-gray-400 shadow-sm ring-1 ring-gray-900/5 mt-1">
                                        <i class="bi bi-building text-2xl sm:text-3xl"></i>
                                    </div>
                                @endif

                                <div class="flex-1 min-w-0 pr-20 lg:pr-24">
                                    <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                                        <h3
                                            class="font-bold text-gray-900 text-lg truncate group-hover:text-soft-gray-900 transition-colors">
                                            {{ $business->name }}</h3>
                                        @if ($business->businessType)
                                            <span
                                                class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-[10px] font-semibold uppercase tracking-wider text-blue-700 ring-1 ring-inset ring-blue-700/10 max-w-[170px]"
                                                title="{{ $business->businessType->name }}">
                                                <span class="truncate">{{ $business->businessType->name }}</span>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="min-h-[2.5rem] mb-3">
                                        <p class="text-sm text-gray-600 line-clamp-2 leading-relaxed">
                                            {{ $business->description ?: 'No description provided' }}</p>
                                    </div>

                                    <div
                                        class="flex flex-wrap items-center gap-4 text-xs text-gray-500 font-medium mt-auto">
                                        @if ($business->user)
                                            <div
                                                class="flex items-center gap-1.5 text-gray-600 bg-gray-50 px-2 py-1 rounded-lg">
                                                <i class="bi bi-person-fill text-gray-400"></i>
                                                <span
                                                    class="truncate max-w-[120px]">{{ $business->user->name }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 text-gray-500">No businesses found.</div>
                @endforelse
            </div>

            {{-- Pagination for All Businesses --}}
            <div class="mt-6">
                <div class="flex items-center justify-center">
                    @if (method_exists($businesses, 'links'))
                        {{ $businesses->withQueryString()->appends(['tab' => 'all'])->links() }}
                    @endif
                </div>
            </div>

            {{-- Inline JS for admin toggle (minimal, unobtrusive) --}}
            @auth
                @if (auth()->user()->isAdmin())
                    <script>
                        (function() {
                            const csrf = '{{ csrf_token() }}';

                            // Ensure a persistent toast container exists
                            function getToastContainer() {
                                const id = 'global-toast-container';
                                let container = document.getElementById(id);
                                if (!container) {
                                    container = document.createElement('div');
                                    container.id = id;
                                    container.setAttribute('aria-live', 'polite');
                                    container.setAttribute('role', 'status');
                                    container.className = 'fixed top-6 right-6 z-50 flex flex-col gap-3 items-end';
                                    document.body.appendChild(container);
                                }
                                return container;
                            }

                            function showToast(message, type = 'info') {
                                const colors = {
                                    success: 'bg-emerald-600',
                                    error: 'bg-red-600',
                                    info: 'bg-gray-800'
                                };

                                const container = getToastContainer();
                                // Deduplicate identical messages: if found, reset its timeout and bring to front
                                const existing = Array.from(container.children).find(t => t.dataset && t.dataset.msg === message && t
                                    .dataset.type === type);
                                if (existing) {
                                    if (existing.__timeoutId) clearTimeout(existing.__timeoutId);
                                    // bump to top (end)
                                    container.appendChild(existing);
                                    existing.style.opacity = '1';
                                    existing.style.transform = 'translateY(0)';
                                    existing.__timeoutId = setTimeout(() => dismissToast(existing), 3500);
                                    return;
                                }

                                const toast = document.createElement('div');
                                toast.dataset.msg = message;
                                toast.dataset.type = type;
                                toast.className =
                                    `max-w-sm w-full pointer-events-auto flex items-start justify-between gap-3 text-white px-4 py-3 rounded-lg shadow-lg transform transition-all duration-300 ease-out ${colors[type] || colors.info}`;
                                toast.style.opacity = '0';
                                toast.style.transform = 'translateY(-8px)';
                                toast.setAttribute('role', 'alert');

                                const iconMap = {
                                    success: 'bi-check-circle-fill',
                                    error: 'bi-exclamation-triangle-fill',
                                    info: 'bi-info-circle-fill'
                                };
                                const iconClass = iconMap[type] || iconMap.info;

                                toast.innerHTML = `<div class="flex items-center gap-2">
                                <i class="bi ${iconClass} text-lg"></i>
                                <span class="text-sm font-medium">${escapeHtml(message)}</span>
                            </div>
                            <button type="button" aria-label="Close" class="text-white opacity-90 hover:opacity-100 transition-opacity flex-shrink-0 mt-0.5 close-toast">
                                <i class="bi bi-x-lg pointer-events-none"></i>
                            </button>`;

                                // cap visible toasts (remove oldest if over 4)
                                while (container.children.length >= 4) {
                                    const oldest = container.children[0];
                                    dismissToast(oldest);
                                }

                                container.appendChild(toast);
                                // trigger enter
                                requestAnimationFrame(() => {
                                    toast.style.opacity = '1';
                                    toast.style.transform = 'translateY(0)';
                                });

                                // dismiss after timeout
                                toast.__timeoutId = setTimeout(() => dismissToast(toast), 3500);

                                // click to close
                                toast.addEventListener('click', (e) => {
                                    if (e.target.closest('.close-toast')) {
                                        if (toast.__timeoutId) clearTimeout(toast.__timeoutId);
                                        dismissToast(toast);
                                    }
                                });
                            }

                            function dismissToast(toast) {
                                toast.style.opacity = '0';
                                toast.style.transform = 'translateY(-8px)';
                                setTimeout(() => toast.remove(), 300);
                            }

                            function escapeHtml(unsafe) {
                                return String(unsafe)
                                    .replace(/&/g, '&amp;')
                                    .replace(/</g, '&lt;')
                                    .replace(/>/g, '&gt;')
                                    .replace(/\"/g, '&quot;')
                                    .replace(/'/g, '&#039;');
                            }

                            // Spinner SVG
                            const spinner =
                                '<svg class="animate-spin w-4 h-4 text-gray-700" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>';

                            document.addEventListener('click', async (e) => {
                                const btn = e.target.closest('.toggle-featured-btn');
                                if (!btn) return;
                                e.preventDefault();
                                const url = btn.dataset.url;
                                if (!url) return;

                                // Prevent double clicks
                                if (btn.dataset.loading === '1') return;
                                btn.dataset.loading = '1';

                                const originalHtml = btn.innerHTML;
                                btn.innerHTML = spinner;
                                btn.setAttribute('aria-busy', 'true');

                                try {
                                    const res = await fetch(url, {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': csrf,
                                            'Accept': 'application/json',
                                            'X-Requested-With': 'XMLHttpRequest'
                                        },
                                    });

                                    if (!res.ok) {
                                        const text = await res.text();
                                        console.error('Toggle featured failed', res.status, text);
                                        showToast('Failed to update featured status.', 'error');

                                        btn.innerHTML = originalHtml;
                                        return;
                                    }

                                    const data = await res.json();

                                    if (data?.is_featured) {
                                        btn.innerHTML = '<i class="bi bi-star-fill text-yellow-500"></i>';
                                        btn.setAttribute('aria-pressed', 'true');
                                        btn.title = 'Unfeature business';
                                        showToast(data.message || 'Business featured', 'success');
                                    } else {
                                        btn.innerHTML = '<i class="bi bi-star text-gray-400"></i>';
                                        btn.setAttribute('aria-pressed', 'false');
                                        btn.title = 'Feature business';
                                        showToast(data.message || 'Business unfeatured', 'success');
                                    }
                                } catch (err) {
                                    console.error(err);
                                    showToast('Failed to update featured status.', 'error');
                                    btn.innerHTML = originalHtml;
                                } finally {
                                    btn.dataset.loading = '0';
                                    btn.removeAttribute('aria-busy');
                                }
                            });
                        })
                        ();
                    </script>
                @endif
            @endauth

        </div>

        {{-- Duplicate 'My Businesses' block removed; kept only the tabbed 'my' section above. --}}

        @auth
            @if (auth()->user()->isAdmin())
                {{-- Import Modal - Elegant Professional Design --}}
                <div x-show="showImportModal" x-cloak class="fixed inset-0 z-[100] overflow-y-auto"
                    aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                        {{-- Background overlay with blur --}}
                        <div x-show="showImportModal" x-transition:enter="ease-out duration-300"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0" @click="showImportModal = false"
                            class="fixed inset-0 transition-opacity bg-gray-900/60 backdrop-blur-sm" aria-hidden="true">
                        </div>

                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                        {{-- Modal panel --}}
                        <div x-show="showImportModal" x-transition:enter="ease-out duration-300 transform"
                            x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                            x-transition:leave="ease-in duration-200 transform"
                            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                            x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                            class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-gray-100">

                            <form action="{{ route('businesses.import') }}" method="POST" enctype="multipart/form-data"
                                id="importForm" x-data="{
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
                                <div
                                    class="px-8 pt-8 pb-6 bg-gradient-to-br from-soft-gray-50 to-white border-b border-gray-100">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start gap-4">
                                            <div
                                                class="flex items-center justify-center flex-shrink-0 w-12 h-12 bg-white rounded-xl shadow border border-gray-100">
                                                <svg class="w-6 h-6 text-soft-gray-900" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                                    </path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="text-xl font-bold text-gray-900 tracking-tight"
                                                    id="modal-title">
                                                    Import Businesses
                                                </h3>
                                                <p class="text-sm text-gray-500 mt-1">
                                                    Upload Excel file to bulk import businesses
                                                </p>
                                            </div>
                                        </div>
                                        <button type="button" @click="showImportModal = false"
                                            class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-2 rounded-xl transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
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
                                                @dragleave.prevent="isDragging = false" @drop.prevent="handleDrop($event)"
                                                :class="isDragging ?
                                                    'border-soft-gray-900 bg-soft-gray-50 ring-4 ring-soft-gray-900/10' :
                                                    'border-gray-300 hover:border-soft-gray-400 bg-gray-50 hover:bg-gray-100/50'"
                                                class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed rounded-xl cursor-pointer transition-all duration-200 ease-in-out relative overflow-hidden">

                                                <div
                                                    class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4">
                                                    <template x-if="!fileName">
                                                        <div class="flex flex-col items-center transition-all">
                                                            <div
                                                                class="p-3 bg-white rounded-full shadow-sm border border-gray-100 mb-3 group-hover:scale-110 transition-transform duration-200">
                                                                <svg class="w-6 h-6 text-soft-gray-500" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="1.5"
                                                                        d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                </svg>
                                                            </div>
                                                            <p class="mb-1 text-sm font-semibold text-gray-700">Click to
                                                                upload or drag and drop</p>
                                                            <p
                                                                class="text-xs text-gray-500 bg-white px-2 py-1 rounded-md border border-gray-200 shadow-sm mt-2">
                                                                XLS, XLSX (Max. 10MB)</p>
                                                        </div>
                                                    </template>

                                                    <template x-if="fileName">
                                                        <div class="flex flex-col items-center w-full transition-all">
                                                            <div
                                                                class="p-3 bg-green-50 rounded-full border border-green-100 mb-3">
                                                                <svg class="w-6 h-6 text-green-500" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M5 13l4 4L19 7" />
                                                                </svg>
                                                            </div>
                                                            <p class="mb-1 text-sm font-bold text-gray-900 truncate max-w-xs"
                                                                x-text="fileName"></p>
                                                            <p class="text-xs text-gray-500 font-medium"
                                                                x-text="fileSize"></p>
                                                        </div>
                                                    </template>
                                                </div>

                                                <input type="file" id="fileInputBusinesses" name="file"
                                                    accept=".xlsx,.xls"
                                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                                    required @change="handleFileSelect($event)">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Required Columns Info --}}
                                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                                        <div class="flex gap-3">
                                            <div class="flex-shrink-0 mt-0.5">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-semibold text-gray-900 mb-2">Required Excel Columns:
                                                </p>
                                                <div class="flex flex-wrap gap-2">
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-mono font-medium bg-white border border-gray-200 text-gray-700 shadow-sm">Nama
                                                        Bisnis</span>
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-mono font-medium bg-white border border-gray-200 text-gray-700 shadow-sm">Kategori</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Modal Footer --}}
                                <div
                                    class="px-8 py-5 bg-gray-50 border-t border-gray-100 flex justify-end gap-3 rounded-b-2xl">
                                    <button type="button" @click="showImportModal = false"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                        class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white bg-soft-gray-900 rounded-xl hover:bg-soft-gray-800 shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                            </path>
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

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const revealTargets = document.querySelectorAll('.reveal-on-scroll');

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-visible');
                            // Ensure the observer stops tracking once it has revealed
                            observer.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.10,
                    rootMargin: '0px 0px -40px 0px'
                });

                revealTargets.forEach(target => observer.observe(target));
            });
        </script>
    @endpush

</x-app-layout>
