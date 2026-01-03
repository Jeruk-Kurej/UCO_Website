<x-app-layout>
    {{-- ======================================== SERVICES EDIT ======================================== --}}
    <div class="max-w-3xl mx-auto">
        <div class="mb-6 flex items-center gap-3">
            <a href="{{ route('businesses.show', $business) }}" class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold">Edit Service</h1>
                <p class="text-sm text-gray-500">{{ $service->name }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('businesses.services.update', [$business, $service]) }}" class="bg-white border rounded-lg p-6 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm text-gray-700">Service name</label>
                <input name="name" value="{{ old('name', $service->name) }}" required class="mt-1 block w-full border rounded px-3 py-2" />
                @error('name')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm text-gray-700">Description</label>
                <textarea name="description" rows="4" required class="mt-1 block w-full border rounded px-3 py-2">{{ old('description', $service->description) }}</textarea>
                @error('description')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm text-gray-700">Price (Rp)</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                    <input type="number" name="price" value="{{ old('price', $service->price) }}" min="0" step="0.01" required class="mt-1 block w-full pl-10 border rounded px-3 py-2" />
                </div>
                @error('price')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm text-gray-700">Price type</label>
                <select name="price_type" class="mt-1 block w-full border rounded px-3 py-2">
                    <option value="fixed" {{ old('price_type', $service->price_type) === 'fixed' ? 'selected' : '' }}>Fixed Price</option>
                    <option value="starting_from" {{ old('price_type', $service->price_type) === 'starting_from' ? 'selected' : '' }}>Starting From</option>
                </select>
            </div>

            <div class="flex items-center justify-between pt-4 border-t">
                <a href="{{ route('businesses.show', $business) }}" class="px-4 py-2 bg-gray-200 rounded">Cancel</a>
                <div class="flex items-center gap-3">
                    <form id="delete-form" action="{{ route('businesses.services.destroy', [$business, $service]) }}" method="POST" onsubmit="return confirm('Delete this service?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-2 bg-red-600 text-white rounded">Delete</button>
                    </form>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded">Update</button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>