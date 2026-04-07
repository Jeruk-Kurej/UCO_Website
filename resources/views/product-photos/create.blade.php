<x-app-layout>
    <div class="max-w-5xl mx-auto">
        {{-- Page Header --}}
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center gap-4">
            <a href="{{ route('products.photos.index', $product) }}" 
               class="group inline-flex items-center justify-center sm:justify-start gap-2.5 px-4 py-2.5 bg-white hover:bg-gray-900 border border-gray-200 hover:border-gray-900 text-gray-700 hover:text-white rounded-xl font-medium text-sm shadow-sm hover:shadow-md transition-all duration-200">
                <i class="bi bi-arrow-left text-base group-hover:-translate-x-0.5 transition-transform duration-200"></i>
                <span>Back</span>
            </a>
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-900">Add Product Photo</h1>
                <p class="text-sm text-gray-600">{{ $product->name }}</p>
            </div>
        </div>

        <div class="bg-white shadow-sm sm:rounded-xl">
            <div class="p-6">
                <form method="POST" 
                      action="{{ route('products.photos.store', $product) }}" 
                      enctype="multipart/form-data"
                      class="space-y-6">
                    @csrf

                    {{-- Photo Upload via Alpine.js Component --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Photos <span class="text-red-500">*</span>
                        </label>
                        
                        <x-image-preview
                            input-id="photos"
                            preview-id="pp-create"
                            :max-size="10"
                            height="h-72"
                            placeholder="Click or drag & drop your product photos here"
                            hint="JPG, PNG, GIF — max 10MB per file (Max 10 files)"
                            multiple="true"
                        />
                        <input type="file" name="photos[]" id="photos" accept="image/*" multiple class="sr-only">

                        @error('photos')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('photos.*')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>



                    {{-- Submit Buttons --}}
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="{{ route('products.photos.index', $product) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 hover:text-gray-900 rounded-xl transition duration-150">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-900 hover:bg-gray-800 text-white font-semibold rounded-xl shadow-md hover:shadow-lg transition-all duration-200">
                            <i class="bi bi-cloud-arrow-up-fill text-lg"></i>
                            Upload Photos
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => ucoInitImagePreview('photos', 'pp-create', 10, false));
    </script>
    @endpush
</x-app-layout>