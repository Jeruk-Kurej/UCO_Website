<x-app-layout>
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Business Type Management</h1>
                <p class="text-sm text-gray-600">Manage business categories and types</p>
            </div>
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('business-types.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-orange-500 to-yellow-500 hover:from-orange-600 hover:to-yellow-600 text-white rounded-lg font-semibold text-sm shadow-sm transition duration-150">
                        <i class="bi bi-plus-lg me-2"></i>
                        Create Business Type
                    </a>
                @endif
            @endauth
        </div>

        {{-- Business Types Table Card --}}
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[25%]">
                                Business Type
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[40%]">
                                Description
                            </th>
                            <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-[15%]">
                                Total Businesses
                            </th>
                            @auth
                                @if(auth()->user()->isAdmin())
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-[20%]">
                                        Actions
                                    </th>
                                @endif
                            @endauth
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($businessTypes as $type)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                {{-- Type Name --}}
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-orange-400 to-yellow-400 flex items-center justify-center text-white flex-shrink-0">
                                            <i class="bi bi-tag-fill text-lg"></i>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $type->name }}</p>
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
                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap
                                        {{ $type->businesses_count > 0 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600' }}">
                                        <i class="bi bi-briefcase-fill"></i>
                                        {{ $type->businesses_count ?? 0 }}
                                    </span>
                                </td>

                                {{-- Actions --}}
                                @auth
                                    @if(auth()->user()->isAdmin())
                                        <td class="px-4 py-4">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('business-types.show', $type) }}" 
                                                   class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-blue-600 hover:bg-blue-50 transition duration-150"
                                                   title="View Details">
                                                    <i class="bi bi-eye text-lg"></i>
                                                </a>
                                                <a href="{{ route('business-types.edit', $type) }}" 
                                                   class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-orange-600 hover:bg-orange-50 transition duration-150"
                                                   title="Edit Type">
                                                    <i class="bi bi-pencil text-lg"></i>
                                                </a>
                                                @if($type->businesses_count == 0)
                                                    <form action="{{ route('business-types.destroy', $type) }}" 
                                                          method="POST" 
                                                          onsubmit="return confirm('⚠️ Delete {{ $type->name }}?\n\nThis action cannot be undone!');"
                                                          class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-red-600 hover:bg-red-50 transition duration-150"
                                                                title="Delete Type">
                                                            <i class="bi bi-trash text-lg"></i>
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