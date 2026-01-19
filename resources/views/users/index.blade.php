<x-app-layout>
    <div class="space-y-6" x-data="{ showImportModal: false }">
        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
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

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
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
            <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
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

        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">User Management</h1>
                <p class="text-sm text-gray-600 mt-1">Manage platform users and permissions</p>
            </div>
            <div class="flex gap-3">
                <button @click="showImportModal = true"
                        class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    Import Excel
                </button>
                <a href="/users/create" 
                   class="inline-flex items-center px-4 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create User
                </a>
            </div>
        </div>

        {{-- Search Bar --}}
        <div class="bg-white border border-gray-200 rounded-xl p-4" 
             x-data="{
                search: '{{ request('search') }}',
                isSearching: false,
                performSearch() {
                    this.isSearching = true;
                    const trimmed = this.search.trim();
                    const url = trimmed.length > 0 
                        ? '{{ route('users.index') }}?search=' + encodeURIComponent(trimmed)
                        : '{{ route('users.index') }}';
                    
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
                        const newContent = doc.querySelector('.space-y-6');
                        if (newContent) {
                            document.querySelector('.space-y-6').innerHTML = newContent.innerHTML;
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
                               placeholder="Search by name, email, username..." 
                               class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent">
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

        {{-- Import Modal - Elegant Professional Design --}}
        <div x-show="showImportModal" 
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto" 
             aria-labelledby="modal-title" 
             role="dialog" 
             aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                {{-- Background overlay --}}
                <div x-show="showImportModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click="showImportModal = false"
                     class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-50" 
                     aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                {{-- Modal panel --}}
                <div x-show="showImportModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-soft-gray-200">
                    
                    <form action="/users/import" method="POST" enctype="multipart/form-data">
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
                                        <h3 class="text-2xl font-bold text-soft-gray-900 tracking-tight" id="modal-title">
                                            Import Users
                                        </h3>
                                        <p class="text-sm text-soft-gray-600 mt-1">
                                            Upload Excel file to bulk import users
                                        </p>
                                    </div>
                                </div>
                                <button type="button" 
                                        @click="showImportModal = false"
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
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-mono font-medium bg-white border border-soft-gray-200 text-soft-gray-700">Role</span>
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-mono font-medium bg-white border border-soft-gray-200 text-soft-gray-700">Status</span>
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
                                        <span>Existing emails will be skipped to prevent duplicates</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-soft-gray-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>Missing passwords will default to <span class="font-mono font-semibold">password123</span></span>
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
                                    @click="showImportModal = false"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-soft-gray-700 bg-white border border-soft-gray-300 rounded-xl hover:bg-soft-gray-50 hover:border-soft-gray-400 transition-all duration-200">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white bg-soft-gray-900 rounded-xl hover:bg-soft-gray-800 shadow-md hover:shadow-lg transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                Import Users
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Users Table Card --}}
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            {{-- Table Container --}}
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3.5 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-[15%]">
                                User
                            </th>
                            <th scope="col" class="px-4 py-3.5 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-[20%]">
                                Email
                            </th>
                            <th scope="col" class="px-4 py-3.5 text-center text-xs font-medium text-gray-600 uppercase tracking-wider w-[12%]">
                                Role
                            </th>
                            <th scope="col" class="px-4 py-3.5 text-center text-xs font-medium text-gray-600 uppercase tracking-wider w-[12%]">
                                Status
                            </th>
                            <th scope="col" class="px-4 py-3.5 text-center text-xs font-medium text-gray-600 uppercase tracking-wider w-[12%]">
                                Businesses
                            </th>
                            <th scope="col" class="px-4 py-3.5 text-center text-xs font-medium text-gray-600 uppercase tracking-wider w-[15%]">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50 transition">
                                {{-- User Info --}}
                                <td class="px-4 py-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500">@<!-- -->{{ $user->username }}</p>
                                    </div>
                                </td>

                                {{-- Email --}}
                                <td class="px-4 py-4">
                                    <div class="text-sm text-gray-600">{{ $user->email }}</div>
                                </td>

                                {{-- Role --}}
                                <td class="px-4 py-4 text-center">
                                    @if($user->role === 'admin')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-md bg-purple-100 text-purple-800">
                                            Admin
                                        </span>
                                    @elseif($user->role === 'student')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-md bg-blue-100 text-blue-800">
                                            Student
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-md bg-green-100 text-green-800">
                                            Alumni
                                        </span>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td class="px-4 py-4 text-center">
                                    @if($user->is_active)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-md bg-green-100 text-green-800">
                                            <span class="w-1.5 h-1.5 bg-green-600 rounded-full"></span>
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-md bg-gray-100 text-gray-800">
                                            <span class="w-1.5 h-1.5 bg-gray-600 rounded-full"></span>
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                {{-- Businesses Count --}}
                                <td class="px-4 py-4 text-center">
                                    <span class="text-sm text-gray-700 font-medium">{{ $user->businesses_count ?? 0 }}</span>
                                </td>

                                {{-- Actions --}}
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('users.show', $user) }}" 
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors"
                                           title="View Details">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('users.edit', $user) }}" 
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors"
                                           title="Edit User">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        @if(auth()->id() !== $user->id)
                                            <form action="{{ route('users.destroy', $user) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('⚠️ Delete {{ $user->name }}?\n\nThis will also delete:\n- All their businesses\n- All their products\n- All their data\n\nThis action cannot be undone!');"
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-red-600 hover:bg-red-50 transition-colors"
                                                        title="Delete User">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-300 cursor-not-allowed" 
                                                  title="Cannot delete yourself">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        <p class="text-gray-500 text-base font-medium mb-1">No users found</p>
                                        <p class="text-gray-400 text-sm">Create your first user to get started</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($users->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

        {{-- Stats Summary --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-600 uppercase tracking-wide">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalUsers }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-600 uppercase tracking-wide">Admins</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalAdmins }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-600 uppercase tracking-wide">Students</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalStudents }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                            <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-600 uppercase tracking-wide">Alumni</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalAlumni }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>