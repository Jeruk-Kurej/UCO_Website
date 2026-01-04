<x-app-layout>
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Contact Type Management</h1>
                <p class="text-sm text-gray-600 mt-1">Manage available contact platforms and icons</p>
            </div>
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="/contact-types/create" 
                       class="inline-flex items-center px-4 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create Contact Type
                    </a>
                @endif
            @endauth
        </div>

        {{-- Contact Types Table Card --}}
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3.5 text-center text-xs font-medium text-gray-600 uppercase tracking-wider w-[15%]">
                                Icon Preview
                            </th>
                            <th scope="col" class="px-4 py-3.5 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-[25%]">
                                Platform Name
                            </th>
                            <th scope="col" class="px-4 py-3.5 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-[25%]">
                                Icon Class
                            </th>
                            <th scope="col" class="px-4 py-3.5 text-center text-xs font-medium text-gray-600 uppercase tracking-wider w-[15%]">
                                Usage Count
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
                        @forelse($contactTypes as $type)
                            <tr class="hover:bg-gray-50 transition">
                                {{-- Icon Preview --}}
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-center">
                                        <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center text-purple-600 text-xl">
                                            <i class="{{ $type->icon_class }}"></i>
                                        </div>
                                    </div>
                                </td>

                                {{-- Platform Name --}}
                                <td class="px-4 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $type->platform_name }}</div>
                                </td>

                                {{-- Icon Class --}}
                                <td class="px-4 py-4">
                                    <code class="text-xs bg-gray-100 px-3 py-1.5 rounded-md text-gray-800 font-mono">{{ $type->icon_class }}</code>
                                </td>

                                {{-- Usage Count --}}
                                <td class="px-4 py-4 text-center">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium
                                        {{ $type->business_contacts_count > 0 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $type->business_contacts_count ?? 0 }}
                                    </span>
                                </td>

                                {{-- Actions --}}
                                @auth
                                    @if(auth()->user()->isAdmin())
                                        <td class="px-4 py-4">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('contact-types.show', $type) }}" 
                                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors"
                                                   title="View Details">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </a>
                                                <a href="{{ route('contact-types.edit', $type) }}" 
                                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors"
                                                   title="Edit Type">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </a>
                                                @if(($type->business_contacts_count ?? 0) == 0)
                                                    <form action="{{ route('contact-types.destroy', $type) }}" 
                                                          method="POST" 
                                                          onsubmit="return confirm('⚠️ Delete {{ $type->platform_name }}?\n\nThis action cannot be undone!');"
                                                          class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-red-600 hover:bg-red-50 transition-colors"
                                                                title="Delete Type">
                                                            <i class="bi bi-trash text-lg"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-300 cursor-not-allowed" 
                                                          title="Cannot delete - {{ $type->business_contacts_count }} contact(s) using this type">
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
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="bi bi-telephone text-6xl text-gray-300 mb-3"></i>
                                        <p class="text-gray-500 text-lg font-medium">No contact types found</p>
                                        <p class="text-gray-400 text-sm">Create your first contact type to enable business contact methods</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($contactTypes->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $contactTypes->links() }}
                </div>
            @endif
        </div>

        {{-- Stats Summary --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Total Contact Types</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $contactTypes->total() }}</p>
                    </div>
                    <i class="bi bi-telephone text-3xl text-purple-200"></i>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Total Contacts</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $contactTypes->sum('business_contacts_count') }}</p>
                    </div>
                    <i class="bi bi-link-45deg text-3xl text-blue-200"></i>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Avg per Type</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ $contactTypes->count() > 0 ? round($contactTypes->sum('business_contacts_count') / $contactTypes->count(), 1) : 0 }}
                        </p>
                    </div>
                    <i class="bi bi-bar-chart text-3xl text-green-200"></i>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>