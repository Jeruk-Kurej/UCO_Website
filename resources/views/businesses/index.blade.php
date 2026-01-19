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
            <form action="{{ route('businesses.index') }}" method="GET" class="w-1/3">
                <input name="search" type="text" value="{{ request('search') }}" placeholder="Search businesses..." class="w-full px-3 py-2 border rounded-lg">
            </form>
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
</x-app-layout>