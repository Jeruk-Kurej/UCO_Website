<x-app-layout>
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.default.min.css" rel="stylesheet">
        <style>
            .ts-wrapper {
                width: 100% !important;
                display: block !important;
                margin: 0 !important;
                padding: 0 !important;
                box-sizing: border-box !important;
            }

            .ts-wrapper .ts-control {
                border: 1px solid #e2e8f0 !important;
                border-radius: 0.75rem !important;
                padding: 10px 16px !important; 
                min-height: 42px !important;
                width: 100% !important;
                box-sizing: border-box !important;
                transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
                background: white !important;
                display: flex !important;
                align-items: center !important;
            }

            .ts-wrapper.focus .ts-control {
                border-color: #111827 !important; /* Soft Gray 900 */
                box-shadow: 0 0 0 4px rgba(17, 24, 39, 0.05) !important;
                ring: none !important;
            }

            .ts-dropdown {
                background-color: white !important;
                border: 1px solid #e2e8f0 !important;
                border-radius: 1rem !important;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
                margin-top: 6px !important;
                padding: 6px !important;
                z-index: 1000 !important;
            }

            .ts-dropdown .option {
                padding: 8px 12px !important;
                font-size: 13px !important;
                color: #475569 !important;
                border-radius: 0.75rem !important;
                margin-bottom: 2px !important;
                transition: all 0.15s ease !important;
            }

            .ts-dropdown .option.active {
                background-color: #fff7ed !important;
                color: #f97316 !important;
                font-weight: 600 !important;
            }

            .ts-wrapper .ts-control>input {
                font-size: 14px !important;
            }
        </style>
    @endpush
    <div class="max-w-5xl mx-auto">
        {{-- Page Header --}}
        <div class="mb-6 flex items-center gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Contact Information</h1>
                <p class="text-sm text-gray-600">{{ $contact->contactType->platform_name }}</p>
            </div>
        </div>

        <div class="bg-white shadow-sm sm:rounded-xl">
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
                                class="block w-full">
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
                               class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-soft-gray-900 focus:ring-soft-gray-900 sm:text-sm @error('contact_value') border-red-500 @enderror">
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
                                   class="mt-1 rounded border-gray-200 text-orange-600 focus:ring-soft-gray-900">
                            <div>
                                <span class="text-sm font-medium text-gray-700">Set as Primary Contact</span>
                                <p class="text-xs text-gray-500">This will be the main contact method displayed prominently</p>
                            </div>
                        </label>
                    </div>

                    {{-- Submit Buttons --}}
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="{{ route('businesses.show', $business) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 hover:text-gray-900 rounded-xl transition duration-150">
    Cancel
</a>
                        <div class="flex items-center gap-3">
                            <button type="button" 
                                    onclick="if(confirm('Delete this contact?')) document.getElementById('delete-form').submit();"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition duration-150">
                                <i class="bi bi-trash me-2"></i>
                                Delete
                            </button>

                            <button type="submit" 
                                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-900 hover:bg-gray-800 text-white font-semibold rounded-xl shadow-md hover:shadow-lg transition-all duration-200">
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
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const contactTypeSelect = document.getElementById("contact_type_id");
            if (contactTypeSelect && window.TomSelect) {
                new TomSelect(contactTypeSelect, {
                    create: false,
                    placeholder: "-- Select Contact Type --",
                    searchField: ["text"],
                });
            }
        });
    </script>
    @endpush
</x-app-layout>