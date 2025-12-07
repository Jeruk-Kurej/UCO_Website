<x-app-layout>
    <div class="max-w-3xl mx-auto">
        {{-- Page Header --}}
        <div class="mb-6 flex items-center gap-3">
            <a href="{{ route('businesses.show', $business) }}" 
               class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-gray-800 transition duration-150">
                <i class="bi bi-arrow-left text-lg"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Service</h1>
                <p class="text-sm text-gray-600">{{ $service->name }}</p>
            </div>
        </div>

        <div class="bg-white shadow-sm sm:rounded-lg">
            <div class="p-6">
                <form method="POST" action="{{ route('businesses.services.update', [$business, $service]) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Service Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Service Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name', $service->name) }}"
                               required
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="4" 
                                  required
                                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm @error('description') border-red-500 @enderror">{{ old('description', $service->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Price --}}
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                            Price (Rp) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                            <input type="number" 
                                   name="price" 
                                   id="price" 
                                   value="{{ old('price', $service->price) }}"
                                   min="0"
                                   step="0.01"
                                   required
                                   class="block w-full pl-10 rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm @error('price') border-red-500 @enderror">
                        </div>
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Price Type --}}
                    <div>
                        <label for="price_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Price Type <span class="text-red-500">*</span>
                        </label>
                        <select name="price_type" 
                                id="price_type" 
                                required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm @error('price_type') border-red-500 @enderror">
                            <option value="fixed" {{ old('price_type', $service->price_type) === 'fixed' ? 'selected' : '' }}>Fixed Price</option>
                            <option value="starting_from" {{ old('price_type', $service->price_type) === 'starting_from' ? 'selected' : '' }}>Starting From</option>
                        </select>
                        @error('price_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit Buttons --}}
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="{{ route('businesses.show', $business) }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-150">
                            <i class="bi bi-x-lg me-2"></i>
                            Cancel
                        </a>
                        <div class="flex items-center gap-3">
                            <button type="button" 
                                    onclick="if(confirm('Delete this service?')) document.getElementById('delete-form').submit();"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-150">
                                <i class="bi bi-trash me-2"></i>
                                Delete
                            </button>

                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-orange-500 to-yellow-500 hover:from-orange-600 hover:to-yellow-600 text-white font-semibold rounded-md shadow-sm transition duration-150">
                                <i class="bi bi-check-lg me-2"></i>
                                Update Service
                            </button>
                        </div>
                    </div>
                </form>

                <form id="delete-form" action="{{ route('businesses.services.destroy', [$business, $service]) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
</x-app-layout>