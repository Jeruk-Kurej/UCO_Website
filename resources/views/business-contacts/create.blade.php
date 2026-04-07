<x-app-layout>
    <div class="max-w-5xl mx-auto">
        {{-- Page Header --}}
        <div class="mb-6 flex items-center gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Add Contact Information</h1>
                <p class="text-sm text-gray-600">{{ $business->name }}</p>
            </div>
        </div>

        <div class="bg-white shadow-sm sm:rounded-xl">
            <div class="p-6">
                <form method="POST" action="{{ route('businesses.contacts.store', $business) }}" class="space-y-6">
                    @csrf

                    {{-- Contact Type --}}
                    <div>
                        <label for="contact_type_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Contact Type <span class="text-red-500">*</span>
                        </label>
                        <select name="contact_type_id" 
                                id="contact_type_id" 
                                required
                                class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-soft-gray-900 focus:ring-soft-gray-900 sm:text-sm @error('contact_type_id') border-red-500 @enderror">
                            <option value="">-- Select Contact Type --</option>
                            @foreach($contactTypes as $type)
                                <option value="{{ $type->id }}" {{ old('contact_type_id') == $type->id ? 'selected' : '' }}>
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
                               value="{{ old('contact_value') }}"
                               required
                               class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-soft-gray-900 focus:ring-soft-gray-900 sm:text-sm @error('contact_value') border-red-500 @enderror"
                               placeholder="e.g., 081234567890, @username, email@example.com">
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
                                   {{ old('is_primary') ? 'checked' : '' }}
                                   class="mt-1 rounded border-gray-200 text-orange-600 focus:ring-soft-gray-900">
                            <div>
                                <span class="text-sm font-medium text-gray-700">Set as Primary Contact</span>

                            </div>
                        </label>
                        @error('is_primary')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit Buttons --}}
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="{{ route('businesses.show', $business) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 hover:text-gray-900 rounded-xl transition duration-150">
    Cancel
</a>
                        <button type="submit" 
                                class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-900 hover:bg-gray-800 text-white font-semibold rounded-xl shadow-md hover:shadow-lg transition-all duration-200">
                            <i class="bi bi-check-lg me-2"></i>
                            Add Contact
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>