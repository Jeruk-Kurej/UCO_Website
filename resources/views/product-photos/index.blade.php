@use('Illuminate\Support\Facades\Storage')

<x-app-layout>
    <div class="max-w-6xl mx-auto">
        {{-- Page Header --}}
        <div class="mb-6 flex items-center gap-3">
            {{-- ✅ FIXED: Back to Business Show (Products Tab) --}}
            <a href="{{ route('businesses.show', $product->business) }}#products" 
               class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-gray-800 transition duration-150">
                <i class="bi bi-arrow-left text-lg"></i>
            </a>
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-900">Product Photos</h1>
                <p class="text-sm text-gray-600">{{ $product->name }} - {{ $product->business->name }}</p>
            </div>
            @auth
                @if(auth()->id() === $product->business->user_id || auth()->user()->isAdmin())
                    <a href="{{ route('products.photos.create', $product) }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white rounded-lg font-semibold text-sm shadow-sm transition duration-150">
                        <i class="bi bi-upload me-2"></i>
                        Upload Photo
                    </a>
                @endif
            @endauth
        </div>

        {{-- Product Info Card --}}
        <div class="mb-6 bg-white shadow-sm sm:rounded-lg p-4">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-lg bg-gradient-to-br from-orange-400 to-yellow-400 flex items-center justify-center text-white flex-shrink-0">
                    <i class="bi bi-box-seam text-2xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900">{{ $product->name }}</h3>
                    <p class="text-sm text-gray-600">{{ $product->productCategory->name }} • Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500 uppercase">Total Photos</p>
                    <p class="text-2xl font-bold text-orange-600">{{ $photos->count() }}</p>
                </div>
            </div>
        </div>

        {{-- Photos Grid --}}
        <div class="bg-white shadow-sm sm:rounded-lg">
            @if($photos->count() > 0)
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($photos as $photo)
                            @php $photoUrl = $photo->photo_url; @endphp
                            <div class="relative group">
                                @if($photoUrl)
                                    {{-- LQIP placeholder + IntersectionObserver swap to full image --}}
                                    <img 
                                        src="{{ storage_image_url($photoUrl, 'lqip') }}" 
                                        data-src="{{ storage_image_url($photoUrl, 'gallery_full') }}" 
                                        alt="{{ $photo->caption ?? $product->name }}" 
                                        loading="lazy"
                                        class="w-full h-48 object-cover rounded-lg blur-lg transition duration-300 ease-out"
                                        onload="if(this.dataset && !this.dataset.loaded) { /* keep LQIP until swapped */ }">
                                @else
                                    <div class="w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center rounded-lg">
                                        <i class="bi bi-image text-4xl text-gray-400"></i>
                                    </div>
                                @endif
                                
                                {{-- Caption Overlay --}}
                                @if($photo->caption)
                                    <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-60 text-white text-xs p-2 rounded-b-lg">
                                        {{ $photo->caption }}
                                    </div>
                                @endif

                                {{-- Action Buttons --}}
                                @auth
                                    @if(auth()->id() === $product->business->user_id || auth()->user()->isAdmin())
                                        <div class="absolute top-2 right-2 flex gap-2 opacity-0 group-hover:opacity-100 transition duration-150">
                                            <a href="{{ route('products.photos.edit', [$product, $photo]) }}" 
                                               class="inline-flex items-center justify-center w-8 h-8 bg-blue-600 text-white rounded-full hover:bg-blue-700 shadow-lg">
                                                <i class="bi bi-pencil text-sm"></i>
                                            </a>
                                            <form action="{{ route('products.photos.destroy', [$product, $photo]) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Delete this photo?');"
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="inline-flex items-center justify-center w-8 h-8 bg-red-600 text-white rounded-full hover:bg-red-700 shadow-lg">
                                                    <i class="bi bi-trash text-sm"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                @endauth
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="p-12 text-center">
                    <i class="bi bi-images text-6xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500 text-lg font-medium mb-2">No photos yet</p>
                    @auth
                        @if(auth()->id() === $product->business->user_id || auth()->user()->isAdmin())
                            <p class="text-sm text-gray-400 mb-4">Upload photos to showcase this product</p>
                            <a href="{{ route('products.photos.create', $product) }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-lg shadow-sm transition duration-150">
                                <i class="bi bi-upload me-2"></i>
                                Upload First Photo
                            </a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
    </div>

    {{-- Inline script: swap LQIP placeholders with full images when visible and remove blur on load --}}
    <script>
        (function(){
            function loadImage(img){
                if(!img || !img.dataset || !img.dataset.src) return;
                if(img.dataset.loaded) return;
                img.onload = function(){
                    img.classList.remove('blur-lg');
                    img.dataset.loaded = '1';
                };
                img.src = img.dataset.src;
            }

            function observe(){
                var imgs = document.querySelectorAll('img[data-src]');
                if('IntersectionObserver' in window){
                    var io = new IntersectionObserver(function(entries){
                        entries.forEach(function(entry){
                            if(entry.isIntersecting){
                                loadImage(entry.target);
                                io.unobserve(entry.target);
                            }
                        });
                    },{rootMargin: '200px'});
                    imgs.forEach(function(i){ io.observe(i); });
                } else {
                    // Fallback: load all
                    imgs.forEach(function(i){ loadImage(i); });
                }
            }

            if(document.readyState === 'loading'){
                document.addEventListener('DOMContentLoaded', observe);
            } else {
                observe();
            }
        })();
    </script>
</x-app-layout>