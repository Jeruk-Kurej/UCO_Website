<x-app-layout>
    {{-- ======================================== PRODUCTS CREATE ======================================== --}}
    <div class="max-w-3xl mx-auto">
        <div class="mb-6 flex items-center gap-3">
            <a href="{{ route('businesses.show', $business) }}" class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold">Add Product</h1>
                <p class="text-sm text-gray-500">{{ $business->name }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('businesses.products.store', $business) }}" class="bg-white border rounded-lg p-6 space-y-4">
            @csrf

            <div>
                <label class="block text-sm text-gray-700">Category</label>
                <select name="product_category_id" required class="mt-1 block w-full border rounded px-3 py-2">
                    <option value="">-- Select Category --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('product_category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('product_category_id')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm text-gray-700">Product name</label>
                <input name="name" value="{{ old('name') }}" required class="mt-1 block w-full border rounded px-3 py-2" placeholder="e.g., Nasi Goreng" />
                @error('name')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm text-gray-700">Description</label>
                <textarea name="description" rows="4" required class="mt-1 block w-full border rounded px-3 py-2">{{ old('description') }}</textarea>
                @error('description')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm text-gray-700">Price (Rp)</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                    <input type="number" name="price" value="{{ old('price') }}" min="0" step="0.01" required class="mt-1 block w-full pl-10 border rounded px-3 py-2" />
                </div>
                @error('price')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center justify-between pt-4 border-t">
                <a href="{{ route('businesses.show', $business) }}" class="px-4 py-2 bg-gray-200 rounded">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded">Create Product</button>
            </div>
        </form>
    </div>
</x-app-layout>