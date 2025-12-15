<x-app-layout>
    {{-- Page Header --}}
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('businesses.index') }}" 
                   class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-gray-800 transition duration-150">
                    <i class="bi bi-arrow-left text-lg"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $business->name }}</h1>
                    <p class="text-sm text-gray-600">
                        <i class="bi bi-tag me-1"></i>
                        {{ $business->businessType->name }}
                    </p>
                </div>
            </div>

            @auth
                @if(auth()->id() === $business->user_id || auth()->user()->isAdmin())
                    <a href="{{ route('businesses.edit', $business) }}" 
                       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-orange-500 to-yellow-500 hover:from-orange-600 hover:to-yellow-600 text-white rounded-lg font-semibold text-sm shadow-sm transition duration-150">
                        <i class="bi bi-pencil me-2"></i>
                        Edit Business
                    </a>
                @endif
            @endauth
        </div>
    </div>

    <div class="space-y-6">
        {{-- Business Overview Card --}}
        <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
            @if($business->photos->first())
                <img src="{{ asset('storage/' . $business->photos->first()->photo_url) }}" 
                     alt="{{ $business->name }}" 
                     class="w-full h-64 object-cover">
            @else
                <div class="w-full h-64 bg-gradient-to-br from-orange-100 to-yellow-100 flex items-center justify-center">
                    <i class="bi bi-briefcase text-8xl text-orange-300"></i>
                </div>
            @endif

            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <div class="flex items-center gap-4 text-sm text-gray-600 mb-3">
                            <span class="flex items-center gap-1">
                                <i class="bi bi-tag"></i>
                                {{ $business->businessType->name }}
                            </span>
                            <span class="flex items-center gap-1">
                                <i class="bi bi-person"></i>
                                {{ $business->user->name }}
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium {{ $business->isProductMode() ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800' }}">
                                <i class="bi {{ $business->isProductMode() ? 'bi-box-seam' : 'bi-wrench' }}"></i>
                                {{ $business->isProductMode() ? 'Product-Based' : 'Service-Based' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="prose max-w-none">
                    <p class="text-gray-700 leading-relaxed">{{ $business->description }}</p>
                </div>
            </div>
        </div>

        {{-- Tabs Navigation --}}
        <div x-data="{ activeTab: '{{ session('activeTab', $business->isProductMode() ? 'products' : 'services') }}' }" class="bg-white shadow-sm sm:rounded-lg">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px px-6 overflow-x-auto">
                    @if($business->isProductMode())
                        <button @click="activeTab = 'products'" 
                                :class="activeTab === 'products' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="flex items-center gap-2 py-4 px-4 border-b-2 font-medium text-sm transition duration-150 whitespace-nowrap">
                            <i class="bi bi-box-seam"></i>
                            Products
                            <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full text-xs">{{ $business->products->count() }}</span>
                        </button>
                    @endif

                    @if($business->isServiceMode())
                        <button @click="activeTab = 'services'" 
                                :class="activeTab === 'services' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="flex items-center gap-2 py-4 px-4 border-b-2 font-medium text-sm transition duration-150 whitespace-nowrap">
                            <i class="bi bi-wrench"></i>
                            Services
                            <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full text-xs">{{ $business->services->count() }}</span>
                        </button>
                    @endif

                    <button @click="activeTab = 'photos'" 
                            :class="activeTab === 'photos' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="flex items-center gap-2 py-4 px-4 border-b-2 font-medium text-sm transition duration-150 whitespace-nowrap">
                        <i class="bi bi-images"></i>
                        Photos
                        <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full text-xs">{{ $business->photos->count() }}</span>
                    </button>

                    <button @click="activeTab = 'contacts'" 
                            :class="activeTab === 'contacts' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="flex items-center gap-2 py-4 px-4 border-b-2 font-medium text-sm transition duration-150 whitespace-nowrap">
                        <i class="bi bi-telephone"></i>
                        Contacts
                        <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full text-xs">{{ $business->contacts->count() }}</span>
                    </button>

                    <button @click="activeTab = 'testimonies'" 
                            :class="activeTab === 'testimonies' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="flex items-center gap-2 py-4 px-4 border-b-2 font-medium text-sm transition duration-150 whitespace-nowrap">
                        <i class="bi bi-star"></i>
                        Testimonies
                        <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full text-xs">{{ $approvedTestimonies->count() }}</span>
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
                                   class="inline-flex items-center px-3 py-2 bg-gray-100 text-gray-700 text-sm rounded-md hover:bg-gray-200 transition duration-150">
                                    <i class="bi bi-tags me-2"></i>
                                    Manage Categories
                                </a>
                                <a href="{{ route('businesses.products.create', $business) }}" 
                                   class="inline-flex items-center px-3 py-2 bg-orange-600 text-white text-sm rounded-md hover:bg-orange-700 transition duration-150">
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
                                @if($product->photos->first())
                                    <img src="{{ asset('storage/' . $product->photos->first()->photo_url) }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-40 object-cover">
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
                                   class="inline-flex items-center px-4 py-2 bg-orange-600 text-white text-sm rounded-md hover:bg-orange-700 transition duration-150">
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
                               class="inline-flex items-center px-3 py-2 bg-orange-600 text-white text-sm rounded-md hover:bg-orange-700 transition duration-150">
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
                                   class="inline-flex items-center px-4 py-2 bg-orange-600 text-white text-sm rounded-md hover:bg-orange-700 transition duration-150">
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
                               class="inline-flex items-center px-3 py-2 bg-orange-600 text-white text-sm rounded-md hover:bg-orange-700 transition duration-150">
                                <i class="bi bi-upload me-2"></i>
                                Upload Photo
                            </a>
                        @endif
                    @endauth
                </div>

                @if($business->photos->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($business->photos as $photo)
                            <div class="relative group">
                                <img src="{{ asset('storage/' . $photo->photo_url) }}" 
                                     alt="{{ $photo->caption }}" 
                                     class="w-full h-48 object-cover rounded-lg">
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
                                   class="inline-flex items-center px-4 py-2 bg-orange-600 text-white text-sm rounded-md hover:bg-orange-700 transition duration-150">
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
                               class="inline-flex items-center px-3 py-2 bg-orange-600 text-white text-sm rounded-md hover:bg-orange-700 transition duration-150">
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
                                   class="inline-flex items-center px-4 py-2 bg-orange-600 text-white text-sm rounded-md hover:bg-orange-700 transition duration-150">
                                    <i class="bi bi-plus-lg me-2"></i>
                                    Add Your First Contact
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>

            {{-- Tab: Testimonies --}}
            <div x-show="activeTab === 'testimonies'" class="p-6" style="display: none;">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Customer Testimonies</h3>
                </div>

                @if($approvedTestimonies->count() > 0)
                    <div class="space-y-4">
                        @foreach($approvedTestimonies as $testimony)
                            <div class="border border-gray-200 rounded-lg p-4 bg-white">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ $testimony->customer_name }}</h4>
                                        <div class="flex items-center gap-1 mt-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star{{ $i <= $testimony->rating ? '-fill' : '' }} text-yellow-500"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <span class="text-xs text-gray-500">{{ $testimony->date->format('d M Y') }}</span>
                                </div>
                                <p class="text-sm text-gray-700 leading-relaxed">{{ $testimony->content }}</p>
                                
                                @if($testimony->aiAnalysis)
                                    <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-md">
                                        <p class="text-xs font-semibold text-blue-900 mb-1">
                                            <i class="bi bi-stars me-1"></i>
                                            AI Analysis
                                        </p>
                                        <p class="text-xs text-blue-800">
                                            <strong>Sentiment:</strong> {{ ucfirst($testimony->aiAnalysis->sentiment_label) }} 
                                            ({{ round($testimony->aiAnalysis->sentiment_score * 100) }}%)
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-lg">
                        <i class="bi bi-star text-6xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500 text-lg font-medium">No testimonies yet</p>
                        <p class="text-sm text-gray-400 mt-2">Customer reviews will appear here once submitted and approved</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>