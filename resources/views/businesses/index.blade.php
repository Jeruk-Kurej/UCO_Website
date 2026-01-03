<x-app-layout>
    {{-- ======================================== FLASH MESSAGES ======================================== --}}
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-3 sm:p-4 mb-4 rounded-r-lg">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5 text-green-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('import_errors'))
        <div x-data="{ show: true }" x-show="show" x-transition class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-init="show = true">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div @click="show = false" class="fixed inset-0 bg-gray-900 bg-opacity-50"></div>
                <div class="relative bg-white rounded-xl shadow-xl max-w-2xl w-full">
                    <div class="p-4 sm:p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base sm:text-lg font-bold text-gray-900">Import Errors</h3>
                                    <p class="text-xs sm:text-sm text-gray-600 mt-1">Some rows skipped</p>
                                </div>
                            </div>
                            <button @click="show = false" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 max-h-96 overflow-y-auto">
                            <ul class="space-y-2 text-xs sm:text-sm text-red-700">
                                @foreach(session('import_errors') as $error)
                                    <li class="flex items-start gap-2">
                                        <span class="text-red-500">•</span>
                                        <span>{{ $error }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <button @click="show = false" class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-400 p-3 sm:p-4 mb-4 rounded-r-lg">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5 text-red-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div>
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

    {{-- ======================================== TAB NAVIGATION ======================================== --}}
    <div x-data="{ activeTab: '{{ $activeTab }}' }" class="space-y-4 sm:space-y-6">
        <div class="bg-white shadow-sm sm:rounded-lg">
            <div class="border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center px-4 sm:px-6">
                    <nav class="flex -mb-px overflow-x-auto">
                        @if($showMyTab)
                            <button @click="activeTab = 'my'" 
                                    :class="activeTab === 'my' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="flex items-center gap-2 py-3 sm:py-4 px-3 sm:px-4 border-b-2 font-medium text-sm whitespace-nowrap">
                                <i class="bi bi-briefcase"></i>
                                <span class="hidden sm:inline">My Businesses</span>
                                <span class="sm:hidden">My</span>
                                <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full text-xs">{{ $myBusinessesCount }}</span>
                            </button>
                        @endif

                        <button @click="activeTab = 'browse'" 
                                :class="activeTab === 'browse' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="flex items-center gap-2 py-3 sm:py-4 px-3 sm:px-4 border-b-2 font-medium text-sm whitespace-nowrap">
                            <i class="bi bi-shop"></i>
                            <span class="hidden sm:inline">Browse All</span>
                            <span class="sm:hidden">All</span>
                            <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full text-xs">{{ $businesses->total() }}</span>
                        </button>
                    </nav>

                    @if($showAdminButtons)
                        <div class="py-3 sm:py-2 flex gap-2 border-t sm:border-t-0 border-gray-200">
                            <button onclick="document.getElementById('importModal').classList.remove('hidden')"
                               class="inline-flex items-center px-3 sm:px-4 py-2 sm:py-2.5 bg-white border border-gray-300 text-gray-700 text-xs sm:text-sm font-medium rounded-lg hover:bg-gray-50">
                                <svg class="w-4 h-4 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <span class="hidden sm:inline">Import</span>
                            </button>
                            
                            <a href="/businesses/create"
                               class="inline-flex items-center px-3 sm:px-4 py-2 sm:py-2.5 bg-gray-900 text-white text-xs sm:text-sm font-medium rounded-lg hover:bg-gray-800">
                                <svg class="w-4 h-4 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span class="hidden sm:inline">Add Business</span>
                                <span class="sm:hidden">Add</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ======================================== SEARCH BAR ======================================== --}}
            <div class="px-4 sm:px-6 py-4 bg-gray-50 border-b border-gray-200" x-data="{ search: '{{ request('search') }}', isSearching: false }">
                <div class="flex gap-2 sm:gap-3">
                    <div class="flex-1">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" 
                                   x-model="search"
                                   @keydown.enter="window.location.href = '{{ route('businesses.index') }}' + (search.trim() ? '?search=' + encodeURIComponent(search.trim()) : '')"
                                   placeholder="Search businesses..." 
                                   class="block w-full pl-10 pr-3 py-2 sm:py-2.5 border border-gray-300 rounded-lg text-xs sm:text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            <div x-show="isSearching" class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg class="animate-spin h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    @if(request('search'))
                        <a href="{{ route('businesses.index') }}"
                           class="inline-flex items-center px-3 sm:px-4 py-2 sm:py-2.5 bg-gray-100 text-gray-700 text-xs sm:text-sm font-medium rounded-lg hover:bg-gray-200">
                            Clear
                        </a>
                    @endif
                </div>
            </div>

            {{-- ======================================== BROWSE ALL TAB CONTENT ======================================== --}}
            <div x-show="activeTab === 'browse'" class="p-4 sm:p-6">
                @if($businesses->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                        @foreach($businesses as $business)
                            <div class="bg-white border border-gray-200 rounded-lg shadow hover:shadow-lg transition">
                                @if($business->photos->first())
                                    <img src="{{ asset('storage/' . $business->photos->first()->photo_url) }}" 
                                         alt="{{ $business->name }}" 
                                         class="w-full h-40 sm:h-48 object-cover rounded-t-lg">
                                @else
                                    <div class="w-full h-40 sm:h-48 bg-purple-100 rounded-t-lg flex items-center justify-center">
                                        <i class="bi bi-briefcase text-5xl sm:text-6xl text-purple-600"></i>
                                    </div>
                                @endif

                                <div class="p-4 sm:p-5">
                                    <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-2 line-clamp-1">{{ $business->name }}</h3>
                                    <p class="text-xs sm:text-sm text-gray-600 mb-3 line-clamp-2">{{ $business->description }}</p>
                                    
                                    <div class="flex items-center justify-between mb-3 flex-wrap gap-2">
                                        <span class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded">
                                            {{ $business->businessType->name }}
                                        </span>
                                        @if($business->is_featured)
                                            <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded flex items-center gap-1">
                                                <i class="bi bi-star-fill"></i>
                                                Featured
                                            </span>
                                        @endif
                                    </div>

                                    <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                                        <a href="{{ route('businesses.show', $business) }}" 
                                           class="text-purple-600 hover:text-purple-700 text-xs sm:text-sm font-medium flex items-center gap-1">
                                            View
                                            <i class="bi bi-arrow-right"></i>
                                        </a>

                                        <div class="flex items-center gap-2">
                                            @if($business->canToggleFeatured)
                                                <button onclick="toggleFeatured({{ $business->id }}, this)"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg {{ $business->is_featured ? 'text-yellow-600 hover:bg-yellow-50' : 'text-gray-400 hover:bg-gray-100' }}"
                                                    data-featured="{{ $business->is_featured ? 'true' : 'false' }}">
                                                    <i class="bi {{ $business->is_featured ? 'bi-star-fill' : 'bi-star' }} text-lg"></i>
                                                </button>
                                            @endif
                                            @if($business->canEdit)
                                                <a href="{{ route('businesses.edit', $business) }}" 
                                                   class="text-gray-500 hover:text-gray-700 text-sm">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $businesses->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="bi bi-inbox text-6xl text-gray-300"></i>
                        <p class="mt-4 text-gray-500 text-base sm:text-lg font-medium">No businesses found</p>
                    </div>
                @endif
            </div>

            {{-- ======================================== MY BUSINESSES TAB CONTENT ======================================== --}}
            @if($showMyTab)
                <div x-show="activeTab === 'my'" class="p-4 sm:p-6" style="display: none;">
                    <div class="mb-6 flex items-center justify-between">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">My Businesses</h3>
                        <a href="/businesses/create" 
                           class="inline-flex items-center px-3 sm:px-4 py-2 sm:py-2.5 bg-gray-900 text-white text-xs sm:text-sm font-medium rounded-lg hover:bg-gray-800">
                            <svg class="w-4 h-4 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span class="hidden sm:inline">Add Business</span>
                            <span class="sm:hidden">Add</span>
                        </a>
                    </div>

                    @if($myBusinesses->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                            @foreach($myBusinesses as $business)
                                <div class="bg-white border border-gray-200 rounded-lg shadow hover:shadow-lg transition">
                                    @if($business->photos->first())
                                        <img src="{{ asset('storage/' . $business->photos->first()->photo_url) }}" 
                                             alt="{{ $business->name }}" 
                                             class="w-full h-40 sm:h-48 object-cover rounded-t-lg">
                                    @else
                                        <div class="w-full h-40 sm:h-48 bg-purple-100 rounded-t-lg flex items-center justify-center">
                                            <i class="bi bi-briefcase text-5xl sm:text-6xl text-purple-600"></i>
                                        </div>
                                    @endif

                                    <div class="p-4 sm:p-5">
                                        <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-2 line-clamp-1">{{ $business->name }}</h3>
                                        <p class="text-xs sm:text-sm text-gray-600 mb-3 line-clamp-2">{{ $business->description }}</p>
                                        
                                        <div class="flex items-center justify-between mb-3 flex-wrap gap-2">
                                            <span class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded">
                                                {{ $business->businessType->name }}
                                            </span>
                                            @if($business->is_featured)
                                                <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded flex items-center gap-1">
                                                    <i class="bi bi-star-fill"></i>
                                                    Featured
                                                </span>
                                            @endif
                                        </div>

                                        <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                                            <a href="{{ route('businesses.show', $business) }}" 
                                               class="text-purple-600 hover:text-purple-700 text-xs sm:text-sm font-medium flex items-center gap-1">
                                                View
                                                <i class="bi bi-arrow-right"></i>
                                            </a>
                                            <a href="{{ route('businesses.edit', $business) }}" 
                                               class="inline-flex items-center px-2 sm:px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded text-xs sm:text-sm">
                                                <i class="bi bi-pencil sm:me-1"></i>
                                                <span class="hidden sm:inline">Edit</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="bi bi-inbox text-6xl text-gray-300"></i>
                            <p class="mt-4 text-gray-500 text-base sm:text-lg font-medium">No businesses yet</p>
                            <a href="/businesses/create" 
                               class="mt-4 inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 bg-gray-900 text-white text-xs sm:text-sm rounded-lg font-medium shadow-sm hover:bg-gray-800">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Create First Business
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- ======================================== FEATURED TOGGLE JAVASCRIPT ======================================== --}}
    @if($showAdminButtons)
        <script>
            function toggleFeatured(businessId, button) {
                button.disabled = true;
                fetch(`/businesses/${businessId}/toggle-featured`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ _token: '{{ csrf_token() }}' })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const icon = button.querySelector('i');
                        if (data.is_featured) {
                            button.classList.remove('text-gray-400', 'hover:bg-gray-100');
                            button.classList.add('text-yellow-600', 'hover:bg-yellow-50');
                            icon.classList.remove('bi-star');
                            icon.classList.add('bi-star-fill');
                        } else {
                            button.classList.remove('text-yellow-600', 'hover:bg-yellow-50');
                            button.classList.add('text-gray-400', 'hover:bg-gray-100');
                            icon.classList.remove('bi-star-fill');
                            icon.classList.add('bi-star');
                        }
                    }
                    button.disabled = false;
                })
                .catch(() => button.disabled = false);
            }
        </script>
    @endif

    {{-- ======================================== IMPORT MODAL ======================================== --}}
    @if($showAdminButtons)
        <div id="importModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-base sm:text-lg font-medium text-gray-900">Import Businesses</h3>
                    <button onclick="document.getElementById('importModal').classList.add('hidden')" 
                            class="text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form action="{{ route('businesses.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="import_file" class="block text-sm font-medium text-gray-700 mb-2">
                            Excel File (.xlsx, .xls, .csv)
                        </label>
                        <input type="file" 
                               name="file" 
                               id="import_file" 
                               accept=".xlsx,.xls,.csv"
                               required
                               class="block w-full text-xs sm:text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50">
                        <p class="mt-1 text-xs text-gray-500">Max: 10 MB</p>
                    </div>

                    <div class="bg-blue-50 border-l-4 border-blue-400 p-3 mb-4">
                        <p class="text-xs text-blue-700">
                            Required columns: Nama, Status dan Major, Email, Phone, Mobile, etc.
                        </p>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit"
                                class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm">
                            Import
                        </button>
                        <button type="button"
                                onclick="document.getElementById('importModal').classList.add('hidden')"
                                class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</x-app-layout>
