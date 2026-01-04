<x-app-layout>
    <div class="max-w-3xl mx-auto">
        {{-- Page Header --}}
        <div class="mb-6 flex items-center gap-3">
            <a href="{{ route('business-types.product-categories.index', $businessType) }}" 
               class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-gray-800 transition duration-150">
                <i class="bi bi-arrow-left text-lg"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Create Product Category</h1>
                <p class="text-sm text-gray-600">{{ $businessType->name }}</p>
            </div>
        </div>

        <div class="bg-white shadow-sm sm:rounded-lg">
            <div class="p-6">
                <form method="POST" action="{{ route('business-types.product-categories.store', $businessType) }}" class="space-y-6">
                    @csrf

                    {{-- Category Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Category Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name') }}"
                               required
                               autofocus
                               placeholder="e.g., Beverages, Appetizers, Main Course"
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Info Card --}}
                    <div class="bg-gradient-to-br from-yellow-50 to-orange-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex gap-3">
                            <i class="bi bi-lightbulb text-yellow-600 text-xl flex-shrink-0"></i>
                            <div class="text-sm text-yellow-800">
                                <p class="font-semibold mb-1">Category Naming Tips</p>
                                <ul class="space-y-1 text-xs">
                                    <li>• Use clear, descriptive names that users will understand</li>
                                    <li>• Keep it short and concise (1-3 words)</li>
                                    <li>• Examples for Restaurant: "Beverages", "Appetizers", "Desserts"</li>
                                    <li>• Examples for Fashion: "Shirts", "Pants", "Accessories"</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Submit Buttons --}}
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="{{ route('business-types.product-categories.index', $businessType) }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-150">
                            <i class="bi bi-x-lg me-2"></i>
                            Cancel
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center gap-2 px-6 py-2.5 bg-soft-gray-900 hover:bg-soft-gray-800 text-white font-semibold rounded-xl shadow-md hover:shadow-lg transition-all duration-200">
                            <i class="bi bi-check-lg me-2"></i>
                            Create Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>