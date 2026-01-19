@use('Illuminate\Support\Facades\Storage')

<x-app-layout>
    {{-- Hero Section with Elegant Back Button --}}
    <div class="mb-8 px-4 sm:px-0">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-6">
            <button onclick="window.history.back()" 
               class="group inline-flex items-center justify-center sm:justify-start gap-2.5 px-4 py-2.5 bg-white hover:bg-gray-900 border border-gray-200 hover:border-gray-900 text-gray-700 hover:text-white rounded-xl font-medium text-sm shadow-sm hover:shadow-md transition-all duration-200">
                <i class="bi bi-arrow-left text-base group-hover:-translate-x-0.5 transition-transform duration-200"></i>
                <span>Back</span>
            </button>
            <div class="flex-1">
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-soft-gray-100 text-soft-gray-700 text-xs font-semibold rounded-lg">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        {{ $business->businessType->name }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-lg
                        {{ $business->isBothMode() ? 'bg-purple-100 text-purple-700' : ($business->isProductMode() ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700') }}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($business->isBothMode())
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-3zM14 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1h-4a1 1 0 01-1-1v-3z"/>
                            @elseif($business->isProductMode())
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            @endif
                        </svg>
                        <span class="hidden sm:inline">{{ $business->isBothMode() ? 'Products & Services' : ($business->isProductMode() ? 'Product-Based' : 'Service-Based') }}</span>
                        <span class="sm:hidden">{{ $business->isBothMode() ? 'Both' : ($business->isProductMode() ? 'Product' : 'Service') }}</span>
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-soft-gray-900 tracking-tight">{{ $business->name }}</h1>
            </div>
            @auth
                @if(auth()->id() === $business->user_id || auth()->user()->isAdmin())
                    <a href="{{ route('businesses.edit', $business) }}" 
                       class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-soft-gray-900 hover:bg-soft-gray-800 text-white rounded-xl font-semibold text-sm shadow-md hover:shadow-lg transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <span class="hidden sm:inline">Edit Business</span>
                        <span class="sm:hidden">Edit</span>
                    </a>
                @endif
            @endauth
        </div>
    </div>

    <div class="space-y-6">
        {{-- Business Overview Card - Professional Design --}}
        <div class="bg-white shadow-lg sm:rounded-2xl overflow-hidden border border-soft-gray-100">
            {{-- Hero Image with Overlay --}}
            <div class="relative h-56">
                @php $firstPhoto = $business->photos->first()?->photo_url; @endphp
                @if($firstPhoto)
                    <img src="{{ storage_image_url($firstPhoto, 'hero') }}" 
                        alt="{{ $business->name }}" 
                        loading="lazy" decoding="async"
                        onload="this.classList.remove('blur-sm')"
                        class="w-full h-full object-cover blur-sm">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>
                @else
                    <div class="w-full h-full bg-gradient-to-br from-soft-gray-100 via-soft-gray-50 to-soft-gray-100 flex items-center justify-center relative">
                        <svg class="w-24 h-24 text-soft-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-black/5 to-transparent"></div>
                    </div>
                @endif
            </div>

            {{-- Business Info Section --}}
            <div class="p-4 sm:p-6 lg:p-8">
                {{-- Owner Info - PROMINENT with Avatar --}}
                <div class="flex flex-col sm:flex-row items-start gap-4 mb-6 pb-6 border-b-2 border-soft-gray-100">
                    @php $ownerPhoto = $business->user->profile_photo_url; @endphp
                        @if($ownerPhoto)
                               <img src="{{ storage_image_url($ownerPhoto, 'profile_thumb') }}" 
                                 alt="{{ $business->user->name }}" 
                                 loading="lazy" decoding="async"
                                 class="flex-shrink-0 w-16 h-16 rounded-2xl object-cover shadow-lg">
                        @else
                        <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-gradient-to-br from-uco-orange-500 to-uco-yellow-500 flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                            {{ strtoupper(substr($business->user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="flex-1">
                        <p class="text-xs font-semibold text-soft-gray-500 uppercase tracking-wider mb-1">Business Owner</p>
                        <h3 class="text-xl font-bold text-soft-gray-900 mb-1">{{ $business->user->name }}</h3>
                        @if($business->position)
                            <div class="flex items-center gap-2">
                                <div class="flex items-center gap-1.5 px-3 py-1.5 bg-soft-gray-100 text-soft-gray-700 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-sm font-semibold">{{ $business->position }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Description --}}
                <div>
                    <h4 class="text-sm font-bold text-soft-gray-900 uppercase tracking-wider mb-3">About This Business</h4>
                    <p class="text-base text-soft-gray-700 leading-relaxed">{{ $business->description }}</p>
                </div>
            </div>
        </div>

        {{-- Tabs Navigation - Elegant Design --}}
        <div x-data="{ 
            activeTab: '{{ session('activeTab', $business->isProductMode() ? 'products' : 'services') }}'
        }" class="bg-white shadow-lg sm:rounded-2xl border border-soft-gray-100">
            <div class="border-b-2 border-soft-gray-100">
                <nav class="flex -mb-px px-6 overflow-x-auto">
                    @if($business->isProductMode())
                        <button @click="activeTab = 'products'" 
                                :class="activeTab === 'products' ? 'border-soft-gray-900 text-soft-gray-900' : 'border-transparent text-soft-gray-500 hover:text-soft-gray-700 hover:border-soft-gray-300'"
                                class="flex items-center gap-2 py-4 px-4 border-b-2 font-semibold text-sm transition duration-150 whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            Products
                            <span :class="activeTab === 'products' ? 'bg-soft-gray-900 text-white' : 'bg-soft-gray-100 text-soft-gray-600'" class="px-2.5 py-0.5 rounded-full text-xs font-semibold transition-colors">{{ $business->products->count() }}</span>
                        </button>
                    @endif

                    @if($business->isServiceMode())
                        <button @click="activeTab = 'services'" 
                                :class="activeTab === 'services' ? 'border-soft-gray-900 text-soft-gray-900' : 'border-transparent text-soft-gray-500 hover:text-soft-gray-700 hover:border-soft-gray-300'"
                                class="flex items-center gap-2 py-4 px-4 border-b-2 font-semibold text-sm transition duration-150 whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            </svg>
                            Services
                            <span :class="activeTab === 'services' ? 'bg-soft-gray-900 text-white' : 'bg-soft-gray-100 text-soft-gray-600'" class="px-2.5 py-0.5 rounded-full text-xs font-semibold transition-colors">{{ $business->services->count() }}</span>
                        </button>
                    @endif

                    <button @click="activeTab = 'photos'" 
                            :class="activeTab === 'photos' ? 'border-soft-gray-900 text-soft-gray-900' : 'border-transparent text-soft-gray-500 hover:text-soft-gray-700 hover:border-soft-gray-300'"
                            class="flex items-center gap-2 py-4 px-4 border-b-2 font-semibold text-sm transition duration-150 whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Photos
                            <span :class="activeTab === 'photos' ? 'bg-soft-gray-900 text-white' : 'bg-soft-gray-100 text-soft-gray-600'" class="px-2.5 py-0.5 rounded-full text-xs font-semibold transition-colors">{{ $business->photos->count() }}</span>
                    </button>

                    <button @click="activeTab = 'contacts'" 
                            :class="activeTab === 'contacts' ? 'border-soft-gray-900 text-soft-gray-900' : 'border-transparent text-soft-gray-500 hover:text-soft-gray-700 hover:border-soft-gray-300'"
                            class="flex items-center gap-2 py-4 px-4 border-b-2 font-semibold text-sm transition duration-150 whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            Contacts
                            <span :class="activeTab === 'contacts' ? 'bg-soft-gray-900 text-white' : 'bg-soft-gray-100 text-soft-gray-600'" class="px-2.5 py-0.5 rounded-full text-xs font-semibold transition-colors">{{ $business->contacts->count() }}</span>
                    </button>
                </nav>
            </div>

            {{-- Tab: Products --}}
            @if($business->isProductMode())
                <div x-show="activeTab === 'products'" class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Products</h3>
                    @auth
                        @if(auth()->id() === $business->user_id || auth()->user()->isAdmin())
                            <div class="flex items-center gap-2">
                                <a href="{{ route('business-types.product-categories.index', $business->businessType) }}" 
                                   class="inline-flex items-center px-3 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition duration-150">
                                    <i class="bi bi-tags me-2"></i>
                                    Manage Categories
                                </a>
                                <a href="{{ route('businesses.products.create', $business) }}" 
                                   class="inline-flex items-center px-3 py-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-lg shadow-sm transition duration-150">
                                    <i class="bi bi-plus-lg me-2"></i>
                                    Add Product
                                </a>
                            </div>
                        @endif
                    @endauth
                </div>

                @if($business->products->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($business->products as $product)
                            <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition duration-150">
                                {{-- Product Image --}}
                                @php $prodPhoto = $product->photos->first()?->photo_url; @endphp
                                @if($prodPhoto)
                                     <img src="{{ storage_image_url($prodPhoto, 'gallery_thumb') }}" 
                                         alt="{{ $product->name }}" 
                                         loading="lazy" decoding="async"
                                         onload="this.classList.remove('blur-sm')"
                                         class="w-full h-40 object-cover blur-sm">
                                @else
                                    <div class="w-full h-40 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                        <i class="bi bi-image text-5xl text-gray-400"></i>
                                    </div>
                                @endif

                                {{-- Product Info --}}
                                <div class="p-4">
                                    <div class="flex items-start justify-between mb-2">
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900 mb-1">{{ $product->name }}</h4>
                                            <p class="text-xs text-gray-500 mb-2">
                                                <i class="bi bi-tag me-1"></i>
                                                {{ $product->productCategory->name }}
                                            </p>
                                        </div>
                                        <span class="text-orange-600 font-bold text-lg">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $product->description }}</p>

                                    {{-- Action Buttons --}}
                                    @auth
                                        @if(auth()->id() === $business->user_id || auth()->user()->isAdmin())
                                            <div class="flex items-center gap-2 pt-3 border-t border-gray-200">
                                                <a href="{{ route('products.photos.index', $product) }}" 
                                                   class="flex-1 inline-flex items-center justify-center px-3 py-1.5 bg-blue-50 text-blue-700 text-xs font-medium rounded hover:bg-blue-100 transition duration-150">
                                                    <i class="bi bi-images me-1"></i>
                                                    Photos ({{ $product->photos->count() }})
                                                </a>
                                                <a href="{{ route('businesses.products.edit', [$business, $product]) }}" 
                                                   class="inline-flex items-center justify-center w-8 h-8 bg-orange-50 text-orange-600 rounded hover:bg-orange-100 transition duration-150"
                                                   title="Edit Product">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('businesses.products.destroy', [$business, $product]) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('Delete {{ $product->name }}?');"
                                                      class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="inline-flex items-center justify-center w-8 h-8 bg-red-50 text-red-600 rounded hover:bg-red-100 transition duration-150"
                                                            title="Delete Product">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-lg">
                        <i class="bi bi-box-seam text-6xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500 text-lg font-medium mb-2">No products yet</p>
                        @auth
                            @if(auth()->id() === $business->user_id || auth()->user()->isAdmin())
                                <p class="text-sm text-gray-400 mb-4">Start adding products to showcase your offerings</p>
                                <a href="{{ route('businesses.products.create', $business) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-lg shadow-sm transition duration-150">
                                    <i class="bi bi-plus-lg me-2"></i>
                                    Add Your First Product
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
            @endif

            {{-- Tab: Services --}}
            @if($business->isServiceMode())
                <div x-show="activeTab === 'services'" class="p-6" style="display: none;">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Services</h3>
                    @auth
                        @if(auth()->id() === $business->user_id || auth()->user()->isAdmin())
                            <a href="{{ route('businesses.services.create', $business) }}" 
                               class="inline-flex items-center px-3 py-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-lg shadow-sm transition duration-150">
                                <i class="bi bi-plus-lg me-2"></i>
                                Add Service
                            </a>
                        @endif
                    @endauth
                </div>

                @if($business->services->count() > 0)
                    <div class="space-y-3">
                        @foreach($business->services as $service)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition duration-150">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900 mb-1">{{ $service->name }}</h4>
                                        <p class="text-sm text-gray-600 mb-3">{{ $service->description }}</p>
                                        <div class="flex items-center gap-2">
                                            <span class="text-orange-600 font-bold">Rp {{ number_format($service->price, 0, ',', '.') }}</span>
                                            <span class="text-xs text-gray-500">/ {{ $service->price_type }}</span>
                                        </div>
                                    </div>

                                    {{-- Action Buttons --}}
                                    @auth
                                        @if(auth()->id() === $business->user_id || auth()->user()->isAdmin())
                                            <div class="flex items-center gap-2 ml-4">
                                                <a href="{{ route('businesses.services.edit', [$business, $service]) }}" 
                                                   class="inline-flex items-center justify-center w-8 h-8 bg-orange-50 text-orange-600 rounded hover:bg-orange-100 transition duration-150"
                                                   title="Edit Service">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('businesses.services.destroy', [$business, $service]) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('Delete {{ $service->name }}?');"
                                                      class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="inline-flex items-center justify-center w-8 h-8 bg-red-50 text-red-600 rounded hover:bg-red-100 transition duration-150"
                                                            title="Delete Service">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-lg">
                        <i class="bi bi-wrench text-6xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500 text-lg font-medium mb-2">No services yet</p>
                        @auth
                            @if(auth()->id() === $business->user_id || auth()->user()->isAdmin())
                                <p class="text-sm text-gray-400 mb-4">Add services to showcase what you offer</p>
                                <a href="{{ route('businesses.services.create', $business) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-lg shadow-sm transition duration-150">
                                    <i class="bi bi-plus-lg me-2"></i>
                                    Add Your First Service
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
            @endif

            {{-- Tab: Photos --}}
            <div x-show="activeTab === 'photos'" class="p-6" style="display: none;">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Business Photo Gallery</h3>
                    @auth
                        @if(auth()->id() === $business->user_id || auth()->user()->isAdmin())
                            <a href="{{ route('businesses.photos.create', $business) }}" 
                               class="inline-flex items-center px-3 py-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-lg shadow-sm transition duration-150">
                                <i class="bi bi-upload me-2"></i>
                                Upload Photo
                            </a>
                        @endif
                    @endauth
                </div>

                @if($business->photos->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($business->photos as $photo)
                            @php $gphoto = $photo->photo_url; $gphotoUrl = null; @endphp
                            <div class="relative group">
                                @if($gphoto)
                                    @php $gphotoUrl = storage_image_url($gphoto, 'gallery_thumb'); @endphp
                                @endif
                                @if($gphotoUrl)
                                    <img src="{{ $gphotoUrl }}" alt="{{ $photo->caption }}" loading="lazy" decoding="async" onload="this.classList.remove('blur-sm')" class="w-full h-48 object-cover rounded-lg blur-sm">
                                @else
                                    <div class="w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center rounded-lg">
                                        <i class="bi bi-image text-4xl text-gray-400"></i>
                                    </div>
                                @endif
                                @if($photo->caption)
                                    <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-60 text-white text-xs p-2 rounded-b-lg">
                                        {{ $photo->caption }}
                                    </div>
                                @endif

                                {{-- Delete Button --}}
                                @auth
                                    @if(auth()->id() === $business->user_id || auth()->user()->isAdmin())
                                        <form action="{{ route('businesses.photos.destroy', [$business, $photo]) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Delete this photo?');"
                                              class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition duration-150">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-red-600 text-white rounded-full hover:bg-red-700 shadow-lg">
                                                <i class="bi bi-trash text-sm"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-lg">
                        <i class="bi bi-images text-6xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500 text-lg font-medium mb-2">No photos yet</p>
                        @auth
                            @if(auth()->id() === $business->user_id || auth()->user()->isAdmin())
                                <p class="text-sm text-gray-400 mb-4">Upload photos to showcase your business</p>
                                <a href="{{ route('businesses.photos.create', $business) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-lg shadow-sm transition duration-150">
                                    <i class="bi bi-upload me-2"></i>
                                    Upload Your First Photo
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>

            {{-- Tab: Contacts --}}
            <div x-show="activeTab === 'contacts'" class="p-6" style="display: none;">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Contact Information</h3>
                    @auth
                        @if(auth()->id() === $business->user_id || auth()->user()->isAdmin())
                            <a href="{{ route('businesses.contacts.create', $business) }}" 
                               class="inline-flex items-center px-3 py-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-lg shadow-sm transition duration-150">
                                <i class="bi bi-plus-lg me-2"></i>
                                Add Contact
                            </a>
                        @endif
                    @endauth
                </div>

                @if($business->contacts->count() > 0)
                    <div class="space-y-3">
                        @foreach($business->contacts as $contact)
                            <div class="flex items-center gap-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-150">
                                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-orange-100 text-orange-600 flex-shrink-0">
                                    <i class="bi {{ $contact->contactType->icon_class }} text-xl"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900">{{ $contact->contactType->platform_name }}</p>
                                    <p class="text-sm text-gray-600 truncate">{{ $contact->contact_value }}</p>
                                </div>
                                @if($contact->is_primary)
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded whitespace-nowrap">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Primary
                                    </span>
                                @endif

                                {{-- Action Buttons --}}
                                @auth
                                    @if(auth()->id() === $business->user_id || auth()->user()->isAdmin())
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('businesses.contacts.edit', [$business, $contact]) }}" 
                                               class="inline-flex items-center justify-center w-8 h-8 bg-orange-50 text-orange-600 rounded hover:bg-orange-100 transition duration-150"
                                               title="Edit Contact">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('businesses.contacts.destroy', [$business, $contact]) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Delete this contact?');"
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="inline-flex items-center justify-center w-8 h-8 bg-red-50 text-red-600 rounded hover:bg-red-100 transition duration-150"
                                                        title="Delete Contact">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                @endauth
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-lg">
                        <i class="bi bi-telephone text-6xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500 text-lg font-medium mb-2">No contact information yet</p>
                        @auth
                            @if(auth()->id() === $business->user_id || auth()->user()->isAdmin())
                                <p class="text-sm text-gray-400 mb-4">Add contact methods so customers can reach you</p>
                                <a href="{{ route('businesses.contacts.create', $business) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-lg shadow-sm transition duration-150">
                                    <i class="bi bi-plus-lg me-2"></i>
                                    Add Your First Contact
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>