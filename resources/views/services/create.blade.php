<x-app-layout>
    {{-- ======================================== SERVICES CREATE ======================================== --}}
    <div class="max-w-3xl mx-auto">
        <div class="mb-6 flex items-center gap-3">
            <a href="{{ route('businesses.show', $business) }}" class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold">Add Service</h1>
                <p class="text-sm text-gray-500">{{ $business->name }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('businesses.services.store', $business) }}" class="bg-white border rounded-lg p-6 space-y-4">
            @csrf

            <div>
                <label class="block text-sm text-gray-700">Service name</label>
                <input name="name" value="{{ old('name') }}" required class="mt-1 block w-full border rounded px-3 py-2" placeholder="e.g., Web Development" />
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

            <div>
                <label class="block text-sm text-gray-700">Price type</label>
                <select name="price_type" class="mt-1 block w-full border rounded px-3 py-2">
                    <option value="fixed">Fixed Price</option>
                    <option value="starting_from">Starting From</option>
                </select>
            </div>

            <div class="flex items-center justify-between pt-4 border-t">
                <a href="{{ route('businesses.show', $business) }}" class="px-4 py-2 bg-gray-200 rounded">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded">Create Service</button>
            </div>
        </form>
    </div>
</x-app-layout>