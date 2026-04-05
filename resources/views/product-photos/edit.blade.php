<x-app-layout>
    <div class="max-w-5xl mx-auto">
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
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4 mt-2 mb-4">
                            <!-- Photo Previews Area -->
                            <div class="flex items-center gap-3 p-3 bg-gray-50/80 border border-gray-200/60 rounded-xl">
                                <!-- Current Photo -->
                                <div class="flex flex-col items-center gap-1.5">
                                    <span class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Current</span>
                                    <div class="w-20 h-20 rounded-lg bg-white border border-gray-200 flex items-center justify-center overflow-hidden shadow-sm p-1.5">
                                        <img :src="currentImage" alt="{{ $photo->caption }}" class="max-w-full max-h-full object-contain">
                                    </div>
                                </div>

                                <!-- Arrow icon -->
                                <template x-if="newImagePreview">
                                    <div class="flex items-center justify-center px-1 pt-4">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                        </svg>
                                    </div>
                                </template>

                                <!-- New Photo Preview -->
                                <template x-if="newImagePreview">
                                    <div class="flex flex-col items-center gap-1.5">
                                        <span class="text-[10px] font-bold tracking-wider text-blue-500 uppercase">New</span>
                                        <div class="relative group">
                                            <div class="w-20 h-20 rounded-lg bg-blue-50 border-2 border-blue-400 flex items-center justify-center overflow-hidden shadow-md transition-all duration-300 p-1.5">
                                                <img :src="newImagePreview" class="max-w-full max-h-full object-contain">
                                            </div>
                                            <button type="button" @click="removeFile()" 
                                                    class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white p-1 rounded-full shadow-lg transform transition-all hover:scale-110 focus:outline-none" 
                                                    title="Cancel new selection">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Upload Actions -->
                            <div class="flex-1 flex flex-col items-start gap-2"
                                 @dragover.prevent="isDragging = true" 
                                 @dragleave.prevent="isDragging = false" 
                                 @drop.prevent="handleDrop($event)">
                                <label for="photo" 
                                       :class="isDragging ? 'bg-blue-50 border-blue-400' : 'bg-white hover:bg-gray-50 border-gray-300'"
                                       class="cursor-pointer inline-flex items-center gap-2 px-5 py-2.5 border rounded-xl text-sm font-semibold text-gray-700 transition-all shadow-sm focus-within:ring-2 focus-within:ring-soft-gray-900 focus-within:border-soft-gray-900">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                    <span x-text="newImagePreview ? 'Change Selection' : 'Upload New Photo'"></span>
                                    <input type="file" name="photo" id="photo" accept="image/*" class="sr-only" x-ref="fileInput" @change="fileSelected">
                                </label>
                                <div class="text-[11px] text-gray-500 font-medium">
                                    <p>Click to replace or drag & drop.</p>
                                    <p>JPG, PNG, GIF allowed (Max 10MB).</p>
                                </div>
                            </div>
                        </div>
                        @error('photo')
                            <p class="mb-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <x-image-preview
                            input-id="photo"
                            preview-id="pp-edit"
                            :max-size="10"
                            height="h-64"
                            placeholder="Click or drag & drop a replacement photo"
                            hint="JPG, PNG, GIF — max 10MB"
                        />
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

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => ucoInitImagePreview('photo', 'pp-edit', 10, false));
    </script>
    @endpush
</x-app-layout>