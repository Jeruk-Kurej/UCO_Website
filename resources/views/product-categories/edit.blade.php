<x-app-layout>
    <div class="max-w-3xl mx-auto">
        {{-- Page Header --}}
        <div class="mb-6 flex items-center gap-3">
            <a href="{{ route('business-types.product-categories.index', $businessType) }}" 
               class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-gray-800 transition duration-150">
                <i class="bi bi-arrow-left text-lg"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Product Category</h1>
                <p class="text-sm text-gray-600">{{ $businessType->name }}</p>
            </div>
        </div>

        <div class="bg-white shadow-sm sm:rounded-lg">
            <div class="p-6">
                <form method="POST" action="{{ route('business-types.product-categories.update', [$businessType, $productCategory]) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Category Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Category Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name', $productCategory->name) }}"
                               required
                               autofocus
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Usage Stats --}}
                    <div class="bg-gradient-to-br from-blue-50 to-cyan-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex gap-3">
                            <i class="bi bi-info-circle text-blue-600 text-xl flex-shrink-0"></i>
                            <div class="text-sm text-blue-800">
                                <p class="font-semibold mb-2">Category Usage</p>
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        <i class="bi bi-box-seam me-2"></i>
                                        {{ $productCategory->products->count() }} {{ Str::plural('product', $productCategory->products->count()) }} using this category
                                    </span>
                                </div>
                                @if($productCategory->products->count() > 0)
                                    <p class="text-xs mt-2 text-blue-700">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        Renaming this category will affect all products using it.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Submit Buttons --}}
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="{{ route('business-types.product-categories.index', $businessType) }}" 
                           class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-150">
                            <i class="bi bi-x-lg me-2"></i>
                            Cancel
                        </a>
                        <div class="flex items-center gap-3">
                            @if($productCategory->products->count() === 0)
                                <button type="button" 
                                        onclick="if(confirm('Delete category {{ $productCategory->name }}?')) document.getElementById('delete-form').submit();"
                                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-150">
                                    <i class="bi bi-trash me-2"></i>
                                    Delete
                                </button>
                            @endif

                            <button type="submit" 
                                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-900 hover:bg-gray-800 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                                <i class="bi bi-check-lg me-2"></i>
                                Update Category
                            </button>
                        </div>
                    </div>
                </form>

                @if($productCategory->products->count() === 0)
                    <form id="delete-form" action="{{ route('business-types.product-categories.destroy', [$businessType, $productCategory]) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>