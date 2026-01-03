<x-app-layout>
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Business Type Management</h1>
                <p class="text-sm text-gray-600 mt-1">Manage business categories and types</p>
            </div>
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="/business-types/create" 
                       class="inline-flex items-center px-4 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create Business Type
                    </a>
                @endif
            @endauth
        </div>

        {{-- Search Bar --}}
        <div class="bg-white border border-gray-200 rounded-xl p-4" 
             x-data="{
                searchQuery: '{{ request('search') }}',
                performSearch() {
                    const trimmed = this.searchQuery.trim();
                    if (trimmed.length > 0) {
                        window.location.href = '{{ route('business-types.index') }}?search=' + encodeURIComponent(trimmed);
                    } else {
                        window.location.href = '{{ route('business-types.index') }}';
                    }
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
                               x-model="searchQuery"
                               x-on:input.debounce.500ms="performSearch()"
                               x-on:keydown.enter="performSearch()"
                               placeholder="Search by category name or description..." 
                               class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                </div>
                <button x-show="searchQuery.length > 0"
                        @click="searchQuery = ''; performSearch()"
                        class="inline-flex items-center px-4 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    Clear
                </button>
            </div>
        </div>

        {{-- Business Types Table Card --}}
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3.5 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-[25%]">
                                Business Type
                            </th>
                            <th scope="col" class="px-4 py-3.5 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-[40%]">
                                Description
                            </th>
                            <th scope="col" class="px-4 py-3.5 text-center text-xs font-medium text-gray-600 uppercase tracking-wider w-[15%]">
                                Total Businesses
                            </th>
                            @auth
                                @if(auth()->user()->isAdmin())
                                    <th scope="col" class="px-4 py-3.5 text-center text-xs font-medium text-gray-600 uppercase tracking-wider w-[20%]">
                                        Actions
                                    </th>
                                @endif
                            @endauth
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($businessTypes as $type)
                            <tr class="hover:bg-gray-50 transition">
                                {{-- Type Name --}}
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $type->name }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Description --}}
                                <td class="px-4 py-4">
                                    <p class="text-sm text-gray-600 line-clamp-2">
                                        {{ $type->description ?? 'No description provided' }}
                                    </p>
                                </td>

                                {{-- Businesses Count --}}
                                <td class="px-4 py-4 text-center">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium
                                        {{ $type->businesses_count > 0 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $type->businesses_count ?? 0 }}
                                    </span>
                                </td>

                                {{-- Actions --}}
                                @auth
                                    @if(auth()->user()->isAdmin())
                                        <td class="px-4 py-4">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('business-types.show', $type) }}" 
                                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors"
                                                   title="View Details">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </a>
                                                <a href="{{ route('business-types.edit', $type) }}" 
                                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors"
                                                   title="Edit Type">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </a>
                                                @if($type->businesses_count == 0)
                                                    <form action="{{ route('business-types.destroy', $type) }}" 
                                                          method="POST" 
                                                          onsubmit="return confirm('⚠️ Delete {{ $type->name }}?\n\nThis action cannot be undone!');"
                                                          class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-red-600 hover:bg-red-50 transition-colors"
                                                                title="Delete Type">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-300 cursor-not-allowed" 
                                                          title="Cannot delete - {{ $type->businesses_count }} business(es) using this type">
                                                        <i class="bi bi-trash text-lg"></i>
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                    @endif
                                @endauth
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="bi bi-tags text-6xl text-gray-300 mb-3"></i>
                                        <p class="text-gray-500 text-lg font-medium">No business types found</p>
                                        <p class="text-gray-400 text-sm">Create your first business type to categorize businesses</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($businessTypes->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $businessTypes->links() }}
                </div>
            @endif
        </div>

        {{-- Stats Summary --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-orange-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Total Types</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $businessTypes->total() }}</p>
                    </div>
                    <i class="bi bi-tags text-3xl text-orange-200"></i>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Total Businesses</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $businessTypes->sum('businesses_count') }}</p>
                    </div>
                    <i class="bi bi-briefcase text-3xl text-blue-200"></i>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Avg per Type</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ $businessTypes->count() > 0 ? round($businessTypes->sum('businesses_count') / $businessTypes->count(), 1) : 0 }}
                        </p>
                    </div>
                    <i class="bi bi-bar-chart text-3xl text-green-200"></i>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>