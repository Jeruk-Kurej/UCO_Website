<x-app-layout>
    <div class="max-w-3xl mx-auto">
        {{-- Page Header --}}
        <div class="mb-6 flex items-center gap-3">
            <a href="{{ route('businesses.show', $business) }}" 
               class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-gray-800 transition duration-150">
                <i class="bi bi-arrow-left text-lg"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Add Business Photo</h1>
                <p class="text-sm text-gray-600">{{ $business->business_name }}</p>
            </div>
        </div>

        <div class="bg-white shadow-sm sm:rounded-lg">
            <div class="p-6">
                <form method="POST" 
                      action="{{ route('businesses.photos.store', $business) }}" 
                      enctype="multipart/form-data"
                      class="space-y-6">
                    @csrf

                    {{-- Photo Upload --}}
                    <div>
                        <label for="photo" class="block text-sm font-medium text-gray-700 mb-2">
                            Photo <span class="text-red-500">*</span>
                        </label>
                        <input type="file" 
                               name="photo" 
                               id="photo" 
                               accept="image/*"
                               required
                               class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:border-orange-500 focus:ring-orange-500 @error('photo') border-red-500 @enderror">
                        <p class="mt-1 text-xs text-gray-500">Accepted formats: JPG, PNG, GIF. Max size: 5MB</p>
                        @error('photo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Image Preview --}}
                    <div id="preview-container" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Preview</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4">
                            <img id="preview-image" class="max-w-full h-auto max-h-96 mx-auto rounded-lg" alt="Preview">
                        </div>
                    </div>

                    {{-- Caption --}}
                    <div>
                        <label for="caption" class="block text-sm font-medium text-gray-700 mb-2">
                            Caption <span class="text-gray-400 text-xs">(Optional)</span>
                        </label>
                        <textarea name="caption" 
                                  id="caption" 
                                  rows="3"
                                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm @error('caption') border-red-500 @enderror"
                                  placeholder="Add a description for this photo...">{{ old('caption') }}</textarea>
                        @error('caption')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Info Card --}}
                    <div class="bg-gradient-to-br from-blue-50 to-cyan-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex gap-3">
                            <i class="bi bi-info-circle text-blue-600 text-xl flex-shrink-0"></i>
                            <div class="text-sm text-blue-800">
                                <p class="font-semibold mb-1">Photo Guidelines</p>
                                <ul class="space-y-1 text-xs">
                                    <li>• Use high-quality images that showcase your business</li>
                                    <li>• Ensure photos are well-lit and professional</li>
                                    <li>• Avoid copyrighted images or watermarks</li>
                                    <li>• The first photo will be your business cover image</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Submit Buttons --}}
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="{{ route('businesses.show', $business) }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-150">
                            <i class="bi bi-x-lg me-2"></i>
                            Cancel
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-orange-500 to-yellow-500 hover:from-orange-600 hover:to-yellow-600 text-white font-semibold rounded-md shadow-sm transition duration-150">
                            <i class="bi bi-upload me-2"></i>
                            Upload Photo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Image preview
        document.getElementById('photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const preview = document.getElementById('preview-image');
                    const container = document.getElementById('preview-container');
                    preview.src = event.target.result;
                    container.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
    @endpush
</x-app-layout>