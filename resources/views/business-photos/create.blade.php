<x-app-layout>
    <div class="max-w-3xl mx-auto">
        {{-- Page Header --}}
        <div class="mb-6 flex items-center gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Add Business Photo</h1>
                <p class="text-sm text-gray-600">{{ $business->business_name }}</p>
            </div>
        </div>

        <div class="bg-white shadow-sm sm:rounded-xl">
            <div class="p-6">
                <form method="POST" 
                      action="{{ route('businesses.photos.store', $business) }}" 
                      enctype="multipart/form-data"
                      class="space-y-6">
                    @csrf

                    {{-- Photo Upload via Alpine.js --}}
                    <div x-data="{ 
                            imagePreview: null,
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
                                    reader.onload = (e) => { this.imagePreview = e.target.result; };
                                    reader.readAsDataURL(file);
                                }
                            },
                            removeFile() {
                                this.imagePreview = null;
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Photo <span class="text-red-500">*</span>
                        </label>
                        
                        <div class="relative group"
                             @dragover.prevent="isDragging = true"
                             @dragleave.prevent="isDragging = false"
                             @drop.prevent="handleDrop($event)">
                             
                            <input type="file" name="photo" id="photo" accept="image/*" required class="hidden" x-ref="fileInput" @change="fileSelected">
                            
                            <template x-if="!imagePreview">
                                <label for="photo" 
                                       :class="isDragging ? 'border-soft-gray-900 bg-soft-gray-50' : 'border-gray-200 bg-white hover:border-soft-gray-400 hover:bg-gray-50'"
                                       class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed rounded-xl cursor-pointer transition-all duration-200 ease-in-out">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <div class="p-3 bg-white rounded-full shadow-sm border border-gray-100 mb-3 group-hover:scale-110 transition-transform duration-200">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <p class="mb-1 text-sm font-medium text-gray-700">Click to upload or drag and drop</p>
                                        <p class="text-xs text-gray-500">Accepted formats: JPG, PNG, GIF. Max: 10MB</p>
                                    </div>
                                </label>
                            </template>

                            <template x-if="imagePreview">
                                <div class="relative w-full h-auto rounded-xl border border-gray-200 overflow-hidden shadow-sm bg-gray-50 p-2">
                                    <img :src="imagePreview" alt="Preview" class="w-full h-auto object-contain max-h-96 rounded-lg pointer-events-none">
                                    <button type="button" @click="removeFile()" class="absolute top-4 right-4 p-2 bg-white text-red-500 rounded-lg shadow-md hover:bg-red-50 transition-colors z-10 focus:outline-none">
                                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </template>
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
                                  placeholder="Add a description for this photo...">{{ old('caption') }}</textarea>
                        @error('caption')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Info Card --}}
                    <div class="bg-gradient-to-br from-blue-50 to-cyan-50 border border-blue-200 rounded-xl p-4">
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
                        <a href="{{ route('businesses.show', $business) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 hover:text-gray-900 rounded-xl transition duration-150">
    Cancel
</a>
                        <button type="submit" 
                                class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-900 hover:bg-gray-800 text-white font-semibold rounded-xl shadow-md hover:shadow-lg transition-all duration-200">
                            <i class="bi bi-upload me-2"></i>
                            Upload Photo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


</x-app-layout>