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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Businesses</h1>

            <div class="flex items-center gap-3">
                <form action="{{ route('businesses.index') }}" method="GET" class="w-72">
                    <input name="search" type="text" value="{{ request('search') }}" placeholder="Search businesses..." class="w-full px-3 py-2 border rounded-lg">
                </form>

                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('businesses.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Business
                        </a>

                        <button type="button" onclick="document.getElementById('importModal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-white border text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5"></path>
                            </svg>
                            Import
                        </button>
                    @endif
                @endauth
            </div>
        </div>

        @if (session('success'))
            <div class="mb-4 text-green-700">{{ session('success') }}</div>
        @endif

        @if ($businesses->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($businesses as $business)
                    <div class="bg-white border rounded-lg overflow-hidden">
                        <a href="{{ route('businesses.show', $business) }}" class="block p-4">
                            <div class="flex items-center gap-4">
                                @php
                                    $logo = $business->logo_url ?? null;
                                    $logoUrl = $logo ? storage_image_url($logo, 'logo_thumb') : null;
                                @endphp
                                @if ($logoUrl)
                                    <img src="{{ $logoUrl }}" alt="{{ $business->name }}" class="w-16 h-16 rounded-md object-cover">
                                @else
                                    <div class="w-16 h-16 bg-gray-100 rounded-md flex items-center justify-center text-gray-400">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 16 16"><path d="M8 0a2 2 0 00-2 2v1H3a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2h-3V2a2 2 0 00-2-2z"/></svg>
                                    </div>
                                @endif

                                <div class="flex-1">
                                    <h3 class="font-semibold">{{ $business->name }}</h3>
                                    <p class="text-sm text-gray-600">{{ \Illuminate\Support\Str::limit($business->description, 80) }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">{{ $businesses->links() }}</div>
        @else
            <div class="text-center py-12 text-gray-500">No businesses found.</div>
        @endif
    </div>

    @auth
        @if(auth()->user()->isAdmin())
            <div id="importModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20">
                    <div onclick="document.getElementById('importModal').classList.add('hidden')" class="fixed inset-0 bg-gray-900 bg-opacity-50"></div>

                    <div class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full border border-soft-gray-200">
                        <form action="{{ route('businesses.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="px-8 pt-8 pb-6 bg-gradient-to-br from-soft-gray-50 to-white border-b border-soft-gray-100">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-start gap-4">
                                        <div class="flex items-center justify-center flex-shrink-0 w-14 h-14 bg-soft-gray-900 rounded-2xl shadow-lg">
                                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-2xl font-bold text-soft-gray-900 tracking-tight">Import Businesses</h3>
                                            <p class="text-sm text-soft-gray-600 mt-1">Upload Excel file to bulk import businesses</p>
                                        </div>
                                    </div>
                                    <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="text-soft-gray-400 hover:text-soft-gray-600 transition-colors ml-4">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="px-8 py-6 space-y-5">
                                <div>
                                    <label class="block text-sm font-semibold text-soft-gray-900 mb-3">Select Excel File</label>
                                    <div class="relative">
                                        <input type="file" name="file" accept=".xlsx,.xls" required class="block w-full text-sm text-soft-gray-900 file:mr-4 file:py-3 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-soft-gray-900 file:text-white hover:file:bg-soft-gray-800 file:cursor-pointer border border-soft-gray-300 rounded-xl cursor-pointer bg-soft-gray-50 focus:outline-none focus:border-soft-gray-900 focus:ring-2 focus:ring-soft-gray-900 focus:ring-opacity-20 transition-all">
                                    </div>
                                    <p class="mt-2 text-xs text-soft-gray-500">Supported formats: <span class="font-semibold">.xlsx, .xls</span> • Maximum size: <span class="font-semibold">10MB</span></p>
                                </div>

                                <div class="flex items-center justify-end gap-3">
                                    <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="px-4 py-2 bg-white border rounded-lg">Cancel</button>
                                    <button type="submit" class="px-4 py-2 bg-gray-900 text-white rounded-lg">Upload</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endauth

</x-app-layout>