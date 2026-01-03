<x-app-layout>
    {{-- ======================================== PAGE HEADER ======================================== --}}
    <div class="mb-4 sm:mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="/businesses" 
                   class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-gray-800 transition flex-shrink-0">
                    <i class="bi bi-arrow-left text-lg"></i>
                </a>
                <div class="min-w-0">
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 truncate">{{ $business->name }}</h1>
                    <p class="text-xs sm:text-sm text-gray-600">
                        <i class="bi bi-tag me-1"></i>
                        {{ $business->businessType->name }}
                    </p>
                </div>
            </div>

            @if($canEdit)
                <a href="{{ route('businesses.edit', $business) }}" 
                   class="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-orange-500 to-yellow-500 hover:from-orange-600 hover:to-yellow-600 text-white rounded-lg font-semibold text-xs sm:text-sm shadow-sm transition">
                    <i class="bi bi-pencil me-2"></i>
                    <span class="hidden sm:inline">Edit Business</span>
                    <span class="sm:hidden">Edit</span>
                </a>
            @endif
        </div>
    </div>

    <div class="space-y-4 sm:space-y-6">
        {{-- ======================================== BUSINESS OVERVIEW ======================================== --}}
        <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
            @if($business->photos->first())
                <img src="{{ asset('storage/' . $business->photos->first()->photo_url) }}" 
                     alt="{{ $business->name }}" 
                     class="w-full h-48 sm:h-64 object-cover">
            @else
                <div class="w-full h-48 sm:h-64 bg-gradient-to-br from-orange-100 to-yellow-100 flex items-center justify-center">
                    <i class="bi bi-briefcase text-6xl sm:text-8xl text-orange-300"></i>
                </div>
            @endif

            <div class="p-4 sm:p-6">
                <div class="flex flex-wrap items-center gap-2 sm:gap-4 text-xs sm:text-sm text-gray-600 mb-3 sm:mb-4">
                    <span class="flex items-center gap-1">
                        <i class="bi bi-tag"></i>
                        {{ $business->businessType->name }}
                    </span>
                    <span class="flex items-center gap-1">
                        <i class="bi bi-person"></i>
                        {{ $business->user->name }}
                    </span>
                    @if($business->position)
                        <span class="flex items-center gap-1">
                            <i class="bi bi-briefcase"></i>
                            {{ $business->position }}
                        </span>
                    @endif
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium {{ $businessModeClass }}">
                        <i class="bi {{ $businessModeIcon }}"></i>
                        {{ $businessModeText }}
                    </span>
                </div>

                <div class="prose max-w-none">
                    <p class="text-sm sm:text-base text-gray-700 leading-relaxed">{{ $business->description }}</p>
                </div>
            </div>
        </div>

        {{-- ======================================== TABS NAVIGATION ======================================== --}}
        <div x-data="{ activeTab: '{{ $activeTab }}' }" class="bg-white shadow-sm sm:rounded-lg">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px px-4 sm:px-6 overflow-x-auto">
                    @if($showProductsTab)
                        <button @click="activeTab = 'products'" 
                                :class="activeTab === 'products' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="flex items-center gap-2 py-3 sm:py-4 px-3 sm:px-4 border-b-2 font-medium text-xs sm:text-sm transition whitespace-nowrap">
                            <i class="bi bi-box-seam"></i>
                            <span class="hidden sm:inline">Products</span>
                            <span class="sm:hidden">Products</span>
                            <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full text-xs">{{ $productsCount }}</span>
                        </button>
                    @endif

                    @if($showServicesTab)
                        <button @click="activeTab = 'services'" 
                                :class="activeTab === 'services' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="flex items-center gap-2 py-3 sm:py-4 px-3 sm:px-4 border-b-2 font-medium text-xs sm:text-sm transition whitespace-nowrap">
                            <i class="bi bi-wrench"></i>
                            <span class="hidden sm:inline">Services</span>
                            <span class="sm:hidden">Services</span>
                            <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full text-xs">{{ $servicesCount }}</span>
                        </button>
                    @endif

                    <button @click="activeTab = 'photos'" 
                            :class="activeTab === 'photos' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="flex items-center gap-2 py-3 sm:py-4 px-3 sm:px-4 border-b-2 font-medium text-xs sm:text-sm transition whitespace-nowrap">
                        <i class="bi bi-images"></i>
                        <span class="hidden sm:inline">Photos</span>
                        <span class="sm:hidden">Photos</span>
                        <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full text-xs">{{ $photosCount }}</span>
                    </button>

                    <button @click="activeTab = 'contacts'" 
                            :class="activeTab === 'contacts' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="flex items-center gap-2 py-3 sm:py-4 px-3 sm:px-4 border-b-2 font-medium text-xs sm:text-sm transition whitespace-nowrap">
                        <i class="bi bi-telephone"></i>
                        <span class="hidden sm:inline">Contacts</span>
                        <span class="sm:hidden">Contacts</span>
                        <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full text-xs">{{ $contactsCount }}</span>
                    </button>
                </nav>
            </div>

            {{-- ======================================== PRODUCTS TAB ======================================== --}}
            @if($showProductsTab)
                <div x-show="activeTab === 'products'" class="p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Products</h3>
                        @if($canEdit)
                            <div class="flex items-center gap-2">
                                <a href="{{ route('business-types.product-categories.index', $business->businessType) }}" 
                                   class="inline-flex items-center px-3 py-2 bg-gray-100 text-gray-700 text-xs sm:text-sm rounded-md hover:bg-gray-200 transition">
                                    <i class="bi bi-tags me-2"></i>
                                    <span class="hidden sm:inline">Manage Categories</span>
                                    <span class="sm:hidden">Categories</span>
                                </a>
                                <a href="{{ route('businesses.products.create', $business) }}" 
                                   class="inline-flex items-center px-3 py-2 bg-orange-600 text-white text-xs sm:text-sm rounded-md hover:bg-orange-700 transition">
                                    <i class="bi bi-plus-lg me-2"></i>
                                    <span class="hidden sm:inline">Add Product</span>
                                    <span class="sm:hidden">Add</span>
                                </a>
                            </div>
                        @endif
                    </div>

                    @if($business->products->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($business->products as $product)
                                <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition">
                                    @if($product->photos->first())
                                        <img src="{{ asset('storage/' . $product->photos->first()->photo_url) }}" 
                                             alt="{{ $product->name }}" 
                                             class="w-full h-32 sm:h-40 object-cover">
                                    @else
                                        <div class="w-full h-32 sm:h-40 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                            <i class="bi bi-image text-4xl sm:text-5xl text-gray-400"></i>
                                        </div>
                                    @endif

                                    <div class="p-3 sm:p-4">
                                        <div class="flex items-start justify-between mb-2 gap-2">
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-semibold text-sm sm:text-base text-gray-900 mb-1 truncate">{{ $product->name }}</h4>
                                                <p class="text-xs text-gray-500 mb-2">
                                                    <i class="bi bi-tag me-1"></i>
                                                    {{ $product->productCategory->name }}
                                                </p>
                                            </div>
                                            <span class="text-orange-600 font-bold text-sm sm:text-lg flex-shrink-0">
                                                Rp {{ number_format($product->price, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        <p class="text-xs sm:text-sm text-gray-600 mb-3 line-clamp-2">{{ $product->description }}</p>

                                        @if($canEdit)
                                            <div class="flex items-center gap-2 pt-3 border-t border-gray-200">
                                                <a href="{{ route('products.photos.index', $product) }}" 
                                                   class="flex-1 inline-flex items-center justify-center px-2 sm:px-3 py-1.5 bg-blue-50 text-blue-700 text-xs font-medium rounded hover:bg-blue-100 transition">
                                                    <i class="bi bi-images me-1"></i>
                                                    <span class="hidden sm:inline">Photos ({{ $product->photos->count() }})</span>
                                                    <span class="sm:hidden">{{ $product->photos->count() }}</span>
                                                </a>
                                                <a href="{{ route('businesses.products.edit', [$business, $product]) }}" 
                                                   class="inline-flex items-center justify-center w-8 h-8 bg-orange-50 text-orange-600 rounded hover:bg-orange-100 transition">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('businesses.products.destroy', [$business, $product]) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('Delete {{ $product->name }}?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="inline-flex items-center justify-center w-8 h-8 bg-red-50 text-red-600 rounded hover:bg-red-100 transition">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 bg-gray-50 rounded-lg">
                            <i class="bi bi-box-seam text-5xl sm:text-6xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500 text-sm sm:text-lg font-medium mb-2">No products yet</p>
                            @if($canEdit)
                                <p class="text-xs sm:text-sm text-gray-400 mb-4">Add products to showcase</p>
                                <a href="{{ route('businesses.products.create', $business) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-orange-600 text-white text-xs sm:text-sm rounded-md hover:bg-orange-700 transition">
                                    <i class="bi bi-plus-lg me-2"></i>
                                    Add First Product
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            @endif

            {{-- ======================================== SERVICES TAB ======================================== --}}
            @if($showServicesTab)
                <div x-show="activeTab === 'services'" class="p-4 sm:p-6" style="display: none;">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Services</h3>
                        @if($canEdit)
                            <a href="{{ route('businesses.services.create', $business) }}" 
                               class="inline-flex items-center px-3 py-2 bg-orange-600 text-white text-xs sm:text-sm rounded-md hover:bg-orange-700 transition">
                                <i class="bi bi-plus-lg me-2"></i>
                                <span class="hidden sm:inline">Add Service</span>
                                <span class="sm:hidden">Add</span>
                            </a>
                        @endif
                    </div>

                    @if($business->services->count() > 0)
                        <div class="space-y-3">
                            @foreach($business->services as $service)
                                <div class="border border-gray-200 rounded-lg p-3 sm:p-4 hover:bg-gray-50 transition">
                                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-semibold text-sm sm:text-base text-gray-900 mb-1">{{ $service->name }}</h4>
                                            <p class="text-xs sm:text-sm text-gray-600 mb-3">{{ $service->description }}</p>
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <span class="text-orange-600 font-bold text-sm sm:text-base">Rp {{ number_format($service->price, 0, ',', '.') }}</span>
                                                <span class="text-xs text-gray-500">/ {{ $service->price_type }}</span>
                                            </div>
                                        </div>

                                        @if($canEdit)
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('businesses.services.edit', [$business, $service]) }}" 
                                                   class="inline-flex items-center justify-center w-8 h-8 bg-orange-50 text-orange-600 rounded hover:bg-orange-100 transition">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('businesses.services.destroy', [$business, $service]) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('Delete {{ $service->name }}?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="inline-flex items-center justify-center w-8 h-8 bg-red-50 text-red-600 rounded hover:bg-red-100 transition">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 bg-gray-50 rounded-lg">
                            <i class="bi bi-wrench text-5xl sm:text-6xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500 text-sm sm:text-lg font-medium mb-2">No services yet</p>
                            @if($canEdit)
                                <p class="text-xs sm:text-sm text-gray-400 mb-4">Add services to showcase</p>
                                <a href="{{ route('businesses.services.create', $business) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-orange-600 text-white text-xs sm:text-sm rounded-md hover:bg-orange-700 transition">
                                    <i class="bi bi-plus-lg me-2"></i>
                                    Add First Service
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            @endif

            {{-- ======================================== PHOTOS TAB ======================================== --}}
            <div x-show="activeTab === 'photos'" class="p-4 sm:p-6" style="display: none;">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900">Photo Gallery</h3>
                    @if($canEdit)
                        <a href="{{ route('businesses.photos.create', $business) }}" 
                           class="inline-flex items-center px-3 py-2 bg-orange-600 text-white text-xs sm:text-sm rounded-md hover:bg-orange-700 transition">
                            <i class="bi bi-upload me-2"></i>
                            <span class="hidden sm:inline">Upload Photo</span>
                            <span class="sm:hidden">Upload</span>
                        </a>
                    @endif
                </div>

                @if($business->photos->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
                        @foreach($business->photos as $photo)
                            <div class="relative group">
                                <img src="{{ asset('storage/' . $photo->photo_url) }}" 
                                     alt="{{ $photo->caption }}" 
                                     class="w-full h-32 sm:h-48 object-cover rounded-lg">
                                @if($photo->caption)
                                    <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-60 text-white text-xs p-2 rounded-b-lg">
                                        {{ $photo->caption }}
                                    </div>
                                @endif

                                @if($canEdit)
                                    <form action="{{ route('businesses.photos.destroy', [$business, $photo]) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Delete photo?');"
                                          class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center justify-center w-8 h-8 bg-red-600 text-white rounded-full hover:bg-red-700 shadow-lg">
                                            <i class="bi bi-trash text-sm"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-lg">
                        <i class="bi bi-images text-5xl sm:text-6xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500 text-sm sm:text-lg font-medium mb-2">No photos yet</p>
                        @if($canEdit)
                            <p class="text-xs sm:text-sm text-gray-400 mb-4">Upload photos to showcase</p>
                            <a href="{{ route('businesses.photos.create', $business) }}" 
                               class="inline-flex items-center px-4 py-2 bg-orange-600 text-white text-xs sm:text-sm rounded-md hover:bg-orange-700 transition">
                                <i class="bi bi-upload me-2"></i>
                                Upload First Photo
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            {{-- ======================================== CONTACTS TAB ======================================== --}}
            <div x-show="activeTab === 'contacts'" class="p-4 sm:p-6" style="display: none;">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900">Contact Information</h3>
                    @if($canEdit)
                        <a href="{{ route('businesses.contacts.create', $business) }}" 
                           class="inline-flex items-center px-3 py-2 bg-orange-600 text-white text-xs sm:text-sm rounded-md hover:bg-orange-700 transition">
                            <i class="bi bi-plus-lg me-2"></i>
                            <span class="hidden sm:inline">Add Contact</span>
                            <span class="sm:hidden">Add</span>
                        </a>
                    @endif
                </div>

                @if($business->contacts->count() > 0)
                    <div class="space-y-3">
                        @foreach($business->contacts as $contact)
                            <div class="flex items-center gap-3 p-3 sm:p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                                <div class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-orange-100 text-orange-600 flex-shrink-0">
                                    <i class="{{ $contact->contactType->icon_class }} text-lg sm:text-xl"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs sm:text-sm font-semibold text-gray-900">{{ $contact->contactType->platform_name }}</p>
                                    <p class="text-xs sm:text-sm text-gray-600 truncate">{{ $contact->contact_value }}</p>
                                </div>
                                @if($contact->is_primary)
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded whitespace-nowrap">
                                        <i class="bi bi-check-circle me-1"></i>
                                        <span class="hidden sm:inline">Primary</span>
                                    </span>
                                @endif

                                @if($canEdit)
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('businesses.contacts.edit', [$business, $contact]) }}" 
                                           class="inline-flex items-center justify-center w-8 h-8 bg-orange-50 text-orange-600 rounded hover:bg-orange-100 transition">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('businesses.contacts.destroy', [$business, $contact]) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Delete contact?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-red-50 text-red-600 rounded hover:bg-red-100 transition">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-lg">
                        <i class="bi bi-telephone text-5xl sm:text-6xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500 text-sm sm:text-lg font-medium mb-2">No contacts yet</p>
                        @if($canEdit)
                            <p class="text-xs sm:text-sm text-gray-400 mb-4">Add contact info</p>
                            <a href="{{ route('businesses.contacts.create', $business) }}" 
                               class="inline-flex items-center px-4 py-2 bg-orange-600 text-white text-xs sm:text-sm rounded-md hover:bg-orange-700 transition">
                                <i class="bi bi-plus-lg me-2"></i>
                                Add First Contact
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
