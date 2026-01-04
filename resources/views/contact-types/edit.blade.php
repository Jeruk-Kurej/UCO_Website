<x-app-layout>
    <div class="max-w-3xl mx-auto">
        {{-- Page Header --}}
        <div class="mb-6 flex items-center gap-3">
            <a href="{{ route('contact-types.index') }}" 
               class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-gray-800 transition duration-150">
                <i class="bi bi-arrow-left text-lg"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Contact Type</h1>
                <p class="text-sm text-gray-600">{{ $contactType->platform_name }}</p>
            </div>
        </div>

        <div class="bg-white shadow-sm sm:rounded-lg">
            <div class="p-6">
                <form method="POST" action="{{ route('contact-types.update', $contactType) }}" class="space-y-6" x-data="{ iconClass: '{{ old('icon_class', $contactType->icon_class) }}' }">
                    @csrf
                    @method('PUT')

                    {{-- Platform Name --}}
                    <div>
                        <label for="platform_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Platform Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="platform_name" 
                               id="platform_name" 
                               value="{{ old('platform_name', $contactType->platform_name) }}"
                               required
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm @error('platform_name') border-red-500 @enderror">
                        @error('platform_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Icon Class --}}
                    <div>
                        <label for="icon_class" class="block text-sm font-medium text-gray-700 mb-2">
                            Bootstrap Icon Class <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="icon_class" 
                               id="icon_class" 
                               value="{{ old('icon_class', $contactType->icon_class) }}"
                               x-model="iconClass"
                               required
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm @error('icon_class') border-red-500 @enderror">
                        @error('icon_class')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            Bootstrap Icon class name. Browse at: 
                            <a href="https://icons.getbootstrap.com/" target="_blank" class="text-orange-600 hover:underline">icons.getbootstrap.com</a>
                        </p>
                    </div>

                    {{-- Icon Preview --}}
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Icon Preview</label>
                        <div class="flex items-center justify-center">
                            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-orange-400 to-yellow-400 flex items-center justify-center text-white text-3xl">
                                <i :class="iconClass || 'bi bi-question-circle'"></i>
                            </div>
                        </div>
                        <p class="text-center mt-3 text-xs text-gray-600">
                            Preview updates as you type
                        </p>
                    </div>

                    {{-- Common Icons Quick Reference --}}
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm font-semibold text-blue-900 mb-2">Common Icons:</p>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2 text-xs">
                            <button type="button" @click="iconClass = 'bi bi-whatsapp'" class="text-left px-2 py-1 bg-white rounded hover:bg-blue-100 transition">
                                <i class="bi bi-whatsapp me-1"></i> WhatsApp
                            </button>
                            <button type="button" @click="iconClass = 'bi bi-instagram'" class="text-left px-2 py-1 bg-white rounded hover:bg-blue-100 transition">
                                <i class="bi bi-instagram me-1"></i> Instagram
                            </button>
                            <button type="button" @click="iconClass = 'bi bi-facebook'" class="text-left px-2 py-1 bg-white rounded hover:bg-blue-100 transition">
                                <i class="bi bi-facebook me-1"></i> Facebook
                            </button>
                            <button type="button" @click="iconClass = 'bi bi-telephone'" class="text-left px-2 py-1 bg-white rounded hover:bg-blue-100 transition">
                                <i class="bi bi-telephone me-1"></i> Phone
                            </button>
                            <button type="button" @click="iconClass = 'bi bi-envelope'" class="text-left px-2 py-1 bg-white rounded hover:bg-blue-100 transition">
                                <i class="bi bi-envelope me-1"></i> Email
                            </button>
                            <button type="button" @click="iconClass = 'bi bi-twitter'" class="text-left px-2 py-1 bg-white rounded hover:bg-blue-100 transition">
                                <i class="bi bi-twitter me-1"></i> Twitter
                            </button>
                            <button type="button" @click="iconClass = 'bi bi-linkedin'" class="text-left px-2 py-1 bg-white rounded hover:bg-blue-100 transition">
                                <i class="bi bi-linkedin me-1"></i> LinkedIn
                            </button>
                            <button type="button" @click="iconClass = 'bi bi-tiktok'" class="text-left px-2 py-1 bg-white rounded hover:bg-blue-100 transition">
                                <i class="bi bi-tiktok me-1"></i> TikTok
                            </button>
                            <button type="button" @click="iconClass = 'bi bi-youtube'" class="text-left px-2 py-1 bg-white rounded hover:bg-blue-100 transition">
                                <i class="bi bi-youtube me-1"></i> YouTube
                            </button>
                        </div>
                    </div>

                    {{-- Usage Information --}}
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <i class="bi bi-info-circle text-blue-600 text-xl"></i>
                            <div>
                                <h3 class="text-sm font-semibold text-blue-900">Usage Information</h3>
                                <p class="mt-1 text-sm text-blue-800">
                                    This contact type is currently used by <strong>{{ $contactType->businessContacts->count() }}</strong> business contact(s).
                                </p>
                                @if($contactType->businessContacts->count() > 0)
                                    <p class="mt-2 text-xs text-blue-700">
                                        Deleting this type is not allowed while contacts are using it.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Submit Buttons --}}
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="{{ route('contact-types.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-150">
                            <i class="bi bi-x-lg me-2"></i>
                            Cancel
                        </a>
                        <div class="flex items-center gap-3">
                            @if($contactType->businessContacts->count() === 0)
                                <button type="button" 
                                        onclick="if(confirm('Are you sure you want to delete this contact type?')) document.getElementById('delete-form').submit();"
                                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-150">
                                    <i class="bi bi-trash me-2"></i>
                                    Delete
                                </button>
                            @else
                                <button type="button" 
                                        disabled
                                        class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-500 rounded-md cursor-not-allowed"
                                        title="Cannot delete - type is in use">
                                    <i class="bi bi-trash me-2"></i>
                                    Delete
                                </button>
                            @endif

                            <button type="submit" 
                                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-soft-gray-900 hover:bg-soft-gray-800 text-white font-semibold rounded-xl shadow-md hover:shadow-lg transition-all duration-200">
                                <i class="bi bi-check-lg me-2"></i>
                                Update Contact Type
                            </button>
                        </div>
                    </div>
                </form>

                @if($contactType->businessContacts->count() === 0)
                    <form id="delete-form" action="{{ route('contact-types.destroy', $contactType) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>