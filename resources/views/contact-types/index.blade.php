<x-app-layout>
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Contact Type Management</h1>
                <p class="text-sm text-gray-600">Manage available contact platforms and icons</p>
            </div>
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('contact-types.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-orange-500 to-yellow-500 hover:from-orange-600 hover:to-yellow-600 text-white rounded-lg font-semibold text-sm shadow-sm transition duration-150">
                         Create Contact Type
                    </a>
                @endif
            @endauth
        </div>

        {{-- Contact Types Table Card --}}
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-[15%]">
                                Icon Preview
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[25%]">
                                Platform Name
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[25%]">
                                Icon Class
                            </th>
                            <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-[15%]">
                                Usage Count
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
                        @forelse($contactTypes as $type)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                {{-- Icon Preview --}}
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-center">
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-orange-400 to-yellow-400 flex items-center justify-center text-white text-xl">
                                            <i class="{{ $type->icon_class }}"></i>
                                        </div>
                                    </div>
                                </td>

                                {{-- Platform Name --}}
                                <td class="px-4 py-4">
                                    <div class="text-sm font-semibold text-gray-900">{{ $type->platform_name }}</div>
                                </td>

                                {{-- Icon Class --}}
                                <td class="px-4 py-4">
                                    <code class="text-xs bg-gray-100 px-3 py-1.5 rounded-md text-gray-800 font-mono">{{ $type->icon_class }}</code>
                                </td>

                                {{-- Usage Count --}}
                                <td class="px-4 py-4 text-center">
                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap
                                        {{ $type->business_contacts_count > 0 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600' }}">
                                        <i class="bi bi-link-45deg"></i>
                                        {{ $type->business_contacts_count ?? 0 }} contacts
                                    </span>
                                </td>

                                {{-- Actions --}}
                                @auth
                                    @if(auth()->user()->isAdmin())
                                        <td class="px-4 py-4">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('contact-types.show', $type) }}" 
                                                   class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-blue-600 hover:bg-blue-50 transition duration-150"
                                                   title="View Details">
                                                    <i class="bi bi-eye text-lg"></i>
                                                </a>
                                                <a href="{{ route('contact-types.edit', $type) }}" 
                                                   class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-orange-600 hover:bg-orange-50 transition duration-150"
                                                   title="Edit Type">
                                                    <i class="bi bi-pencil text-lg"></i>
                                                </a>
                                                @if(($type->business_contacts_count ?? 0) == 0)
                                                    <form action="{{ route('contact-types.destroy', $type) }}" 
                                                          method="POST" 
                                                          onsubmit="return confirm('⚠️ Delete {{ $type->platform_name }}?\n\nThis action cannot be undone!');"
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

        {{-- Icon Reference Guide --}}
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-full bg-blue-500 flex items-center justify-center text-white flex-shrink-0">
                    <i class="bi bi-info-circle text-2xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-blue-900 mb-2">Bootstrap Icons Reference</h3>
                    <p class="text-sm text-blue-800 mb-3">
                        Use Bootstrap Icons for contact types. Browse available icons at: 
                        <a href="https://icons.getbootstrap.com/" target="_blank" class="underline font-semibold hover:text-blue-600">icons.getbootstrap.com</a>
                    </p>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
                        <div class="bg-white rounded-lg p-3 border border-blue-200">
                            <div class="flex items-center gap-2 mb-1">
                                <i class="bi bi-whatsapp text-green-600 text-lg"></i>
                                <span class="text-xs font-medium text-gray-700">WhatsApp</span>
                            </div>
                            <code class="text-xs text-gray-500">bi bi-whatsapp</code>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-blue-200">
                            <div class="flex items-center gap-2 mb-1">
                                <i class="bi bi-instagram text-pink-600 text-lg"></i>
                                <span class="text-xs font-medium text-gray-700">Instagram</span>
                            </div>
                            <code class="text-xs text-gray-500">bi bi-instagram</code>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-blue-200">
                            <div class="flex items-center gap-2 mb-1">
                                <i class="bi bi-facebook text-blue-600 text-lg"></i>
                                <span class="text-xs font-medium text-gray-700">Facebook</span>
                            </div>
                            <code class="text-xs text-gray-500">bi bi-facebook</code>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-blue-200">
                            <div class="flex items-center gap-2 mb-1">
                                <i class="bi bi-telephone text-gray-600 text-lg"></i>
                                <span class="text-xs font-medium text-gray-700">Phone</span>
                            </div>
                            <code class="text-xs text-gray-500">bi bi-telephone</code>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-blue-200">
                            <div class="flex items-center gap-2 mb-1">
                                <i class="bi bi-envelope text-red-600 text-lg"></i>
                                <span class="text-xs font-medium text-gray-700">Email</span>
                            </div>
                            <code class="text-xs text-gray-500">bi bi-envelope</code>
                        </div>
                    </div>
                </div>
            </div>
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