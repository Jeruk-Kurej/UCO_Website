<x-app-layout>
    <div class="max-w-3xl mx-auto">
        {{-- Page Header --}}
        <div class="mb-6 flex items-center gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Product Photo</h1>
                <p class="text-sm text-gray-600">{{ $product->name }}</p>
            </div>
        </div>

        <div class="bg-white shadow-sm sm:rounded-xl">
            <div class="p-6">
                <form method="POST" 
                      action="{{ route('products.photos.update', [$product, $photo]) }}" 
                      enctype="multipart/form-data"
                      class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Photo Upload via Alpine.js (Edit Mode) --}}
                    <div x-data="{ 
                            currentImage: '{{ storage_image_url($photo->photo_url) }}',
                            newImagePreview: null,
                            isDragging: false,
                            fileSelected(event) {
                                const file = event.target.files[0];
                                if (file) {
                                    if(file.size > 10 * 1024 * 1024) {
                                        alert('Photo must not be larger than 10MB.');
                                        this.removeFile();
                                        return;
                                    }
                                    const reader = new FileReader();
                                    reader.onload = (e) => { this.newImagePreview = e.target.result; };
                                    reader.readAsDataURL(file);
                                }
                            },
                            removeFile() {
                                this.newImagePreview = null;
                                this.$refs.fileInput.value = '';
                            },
                            handleDrop(event) {
                                this.isDragging = false;
                                const file = event.dataTransfer.files[0];
                                if (file) {
                                    this.$refs.fileInput.files = event.dataTransfer.files;
                                    this.fileSelected({ target: this.$refs.fileInput });
                                }
                            }
                        }">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Photo</label>
                        
                        {{-- Before / After Preview --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4" x-show="newImagePreview" x-cloak>
                            {{-- Current Image (Faded) --}}
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Current</p>
                                <div class="border border-gray-200 rounded-xl p-2 bg-gray-50/50 h-full flex items-center justify-center">
                                    <img :src="currentImage" class="max-w-full h-auto object-contain max-h-64 rounded-lg opacity-50 grayscale transition-all">
                                </div>
                            </div>
                            
                            {{-- New Image --}}
                            <div class="relative">
                                <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-2">New Selection</p>
                                <div class="relative w-full h-full rounded-xl border-2 border-blue-200 overflow-hidden shadow-sm bg-blue-50/50 p-2 flex items-center justify-center">
                                    <img :src="newImagePreview" class="max-w-full h-auto object-contain max-h-64 rounded-lg">
                                    <button type="button" @click="removeFile()" class="absolute top-4 right-4 p-2 bg-white text-red-500 rounded-lg shadow-md hover:bg-red-50 transition-colors z-10 focus:outline-none">
                                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Current Image Only --}}
                        <div x-show="!newImagePreview" class="mb-4">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Current Photo</p>
                            <img :src="currentImage" alt="{{ $photo->caption }}" class="max-w-xs h-auto rounded-xl shadow-sm border border-gray-200">
                        </div>

                        {{-- Upload Area --}}
                        <div class="relative group mt-4" 
                             @dragover.prevent="isDragging = true" 
                             @dragleave.prevent="isDragging = false" 
                             @drop.prevent="handleDrop($event)">
                            <input type="file" name="photo" id="photo" accept="image/*" class="hidden" x-ref="fileInput" @change="fileSelected">
                            <label for="photo" 
                                   :class="isDragging ? 'border-soft-gray-900 bg-soft-gray-50' : 'border-gray-200 bg-white hover:border-soft-gray-400 hover:bg-gray-50'" 
                                   class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed rounded-xl cursor-pointer transition-all duration-200">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <p class="mb-1 text-sm font-medium text-gray-700">Click to replace photo or drag and drop</p>
                                    <p class="text-xs text-gray-500">Accepted formats: JPG, PNG, GIF. Max: 10MB</p>
                                </div>
                            </label>
                        </div>
                        @error('photo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Caption --}}
                    <div>
                        <label for="caption" class="block text-sm font-medium text-gray-700 mb-2">
                            Caption <span class="text-gray-400 text-xs">(Optional)</span>
                        </label>
                        <textarea name="caption" 
                                  id="caption" 
                                  rows="3"
                                  class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-soft-gray-900 focus:ring-soft-gray-900 sm:text-sm @error('caption') border-red-500 @enderror"
                                  placeholder="Add a description for this product photo...">{{ old('caption', $photo->caption) }}</textarea>
                        @error('caption')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit Buttons --}}
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="{{ route('businesses.products.show', [$product->business, $product]) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 hover:text-gray-900 rounded-xl transition duration-150">
    Cancel
</a>
                        <div class="flex items-center gap-3">
                            <button type="button" 
                                    onclick="if(confirm('Delete this photo permanently?')) document.getElementById('delete-form').submit();"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition duration-150">
                                <i class="bi bi-trash me-2"></i>
                                Delete
                            </button>

                            <button type="submit" 
                                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-soft-gray-900 hover:bg-soft-gray-800 text-white font-semibold rounded-xl shadow-md hover:shadow-lg transition-all duration-200">
                                <i class="bi bi-check-lg me-2"></i>
                                Update Photo
                            </button>
                        </div>
                    </div>
                </form>

                <form id="delete-form" action="{{ route('products.photos.destroy', [$product, $photo]) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>


</x-app-layout>