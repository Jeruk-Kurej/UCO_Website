<x-app-layout>
    <div class="max-w-3xl mx-auto">
        {{-- Page Header --}}
        <div class="mb-6 flex items-center gap-3">
            <a href="{{ route('businesses.show', $business) }}" 
               class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-gray-800 transition duration-150">
                <i class="bi bi-arrow-left text-lg"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Contact Information</h1>
                <p class="text-sm text-gray-600">{{ $contact->contactType->platform_name }}</p>
            </div>
        </div>

        <div class="bg-white shadow-sm sm:rounded-lg">
            <div class="p-6">
                <form method="POST" action="{{ route('businesses.contacts.update', [$business, $contact]) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Contact Type --}}
                    <div>
                        <label for="contact_type_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Contact Type <span class="text-red-500">*</span>
                        </label>
                        <select name="contact_type_id" 
                                id="contact_type_id" 
                                required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm @error('contact_type_id') border-red-500 @enderror">
                            @foreach($contactTypes as $type)
                                <option value="{{ $type->id }}" {{ old('contact_type_id', $contact->contact_type_id) == $type->id ? 'selected' : '' }}>
                                    {{ $type->platform_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('contact_type_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Contact Value --}}
                    <div>
                        <label for="contact_value" class="block text-sm font-medium text-gray-700 mb-2">
                            Contact Value <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="contact_value" 
                               id="contact_value" 
                               value="{{ old('contact_value', $contact->contact_value) }}"
                               required
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm @error('contact_value') border-red-500 @enderror">
                        @error('contact_value')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Is Primary --}}
                    <div>
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" 
                                   name="is_primary" 
                                   id="is_primary"
                                   value="1"
                                   {{ old('is_primary', $contact->is_primary) ? 'checked' : '' }}
                                   class="mt-1 rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                            <div>
                                <span class="text-sm font-medium text-gray-700">Set as Primary Contact</span>
                                <p class="text-xs text-gray-500">This will be the main contact method displayed prominently</p>
                            </div>
                        </label>
                    </div>

                    {{-- Submit Buttons --}}
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="{{ route('businesses.show', $business) }}" 
                           class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-150">
                            <i class="bi bi-x-lg me-2"></i>
                            Cancel
                        </a>
                        <div class="flex items-center gap-3">
                            <button type="button" 
                                    onclick="if(confirm('Delete this contact?')) document.getElementById('delete-form').submit();"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-150">
                                <i class="bi bi-trash me-2"></i>
                                Delete
                            </button>

                            <button type="submit" 
                                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-900 hover:bg-gray-800 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                                <i class="bi bi-check-lg me-2"></i>
                                Update Contact
                            </button>
                        </div>
                    </div>
                </form>

                <form id="delete-form" action="{{ route('businesses.contacts.destroy', [$business, $contact]) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
</x-app-layout>