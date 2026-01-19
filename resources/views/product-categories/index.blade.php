<x-app-layout>
    <div class="max-w-5xl mx-auto">
        {{-- Page Header --}}
        <div class="mb-6 flex items-center gap-3">
            <a href="{{ url()->previous() }}" 
               class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-gray-800 transition duration-150">
                <i class="bi bi-arrow-left text-lg"></i>
            </a>
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-900">Product Categories</h1>
                <p class="text-sm text-gray-600">{{ $businessType->name }}</p>
            </div>
            {{-- ✅ FIXED: All authenticated users can add --}}
            @auth
                <a href="{{ route('business-types.product-categories.create', $businessType) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white rounded-lg font-semibold text-sm shadow-sm transition duration-150">
                    <i class="bi bi-plus-lg me-2"></i>
                    Add Category
                </a>
            @endauth
        </div>

        {{-- Info Banner --}}
        <div class="mb-6 bg-gradient-to-br from-blue-50 to-cyan-50 border border-blue-200 rounded-lg p-4">
            <div class="flex gap-3">
                <i class="bi bi-info-circle text-blue-600 text-xl flex-shrink-0"></i>
                <div class="text-sm text-blue-800">
                    <p class="font-semibold mb-1">About Product Categories</p>
                    <p class="text-xs">These categories are specific to <strong>{{ $businessType->name }}</strong> businesses. When users create products for this business type, they can choose from these categories.</p>
                </div>
            </div>
        </div>

        {{-- Categories List --}}
        <div class="bg-white shadow-sm sm:rounded-lg">
            @if($categories->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="w-[10%] px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                <th class="w-[40%] px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category Name</th>
                                <th class="w-[20%] px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Products Using</th>
                                <th class="w-[15%] px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                {{-- ✅ FIXED: All authenticated users can manage --}}
                                @auth
                                    <th class="w-[15%] px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                @endauth
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($categories as $category)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <div class="flex items-center justify-center w-8 h-8 rounded bg-orange-100 text-orange-600">
                                                <i class="bi bi-tag text-sm"></i>
                                            </div>
                                            <span class="text-sm font-medium text-gray-900">{{ $category->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="bi bi-box-seam me-1"></i>
                                            {{ $category->products_count }} {{ Str::plural('product', $category->products_count) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $category->created_at->format('d M Y') }}
                                    </td>
                                    {{-- ✅ FIXED: All authenticated users can edit/delete --}}
                                    @auth
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('business-types.product-categories.edit', [$businessType, $category]) }}" 
                                                   class="inline-flex items-center justify-center w-9 h-9 bg-orange-50 text-orange-600 rounded hover:bg-orange-100 transition duration-150"
                                                   title="Edit Category">
                                                    <i class="bi bi-pencil"></i>
                                                </a>

                                                @if($category->products_count == 0)
                                                    <form action="{{ route('business-types.product-categories.destroy', [$businessType, $category]) }}" 
                                                          method="POST" 
                                                          onsubmit="return confirm('Delete category {{ $category->name }}?');"
                                                          class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="inline-flex items-center justify-center w-9 h-9 bg-red-50 text-red-600 rounded hover:bg-red-100 transition duration-150"
                                                                title="Delete Category">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <button type="button" 
                                                            disabled
                                                            class="inline-flex items-center justify-center w-9 h-9 bg-gray-100 text-gray-400 rounded cursor-not-allowed"
                                                            title="Cannot delete - category is in use">
                                                        <i class="bi bi-lock"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    @endauth
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Summary Stats --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-orange-100 text-orange-600">
                                <i class="bi bi-tags text-lg"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Total Categories</p>
                                <p class="text-lg font-bold text-gray-900">{{ $categories->count() }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-100 text-blue-600">
                                <i class="bi bi-box-seam text-lg"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Total Products</p>
                                <p class="text-lg font-bold text-gray-900">{{ $categories->sum('products_count') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-green-100 text-green-600">
                                <i class="bi bi-graph-up text-lg"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Avg Products/Category</p>
                                <p class="text-lg font-bold text-gray-900">
                                    {{ $categories->count() > 0 ? number_format($categories->sum('products_count') / $categories->count(), 1) : '0' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <i class="bi bi-tags text-6xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500 text-lg font-medium mb-2">No categories yet</p>
                    {{-- ✅ FIXED: All authenticated users can create --}}
                    @auth
                        <p class="text-sm text-gray-400 mb-4">Create categories to organize products for this business type</p>
                        <a href="{{ route('business-types.product-categories.create', $businessType) }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-lg shadow-sm transition duration-150">
                            <i class="bi bi-plus-lg me-2"></i>
                            Create First Category
                        </a>
                    @endauth
                </div>
            @endif
        </div>
    </div>
</x-app-layout>