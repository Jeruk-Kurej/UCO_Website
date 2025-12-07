<x-app-layout>
    {{-- ✅ REMOVED: <x-slot name="header"> section --}}

    {{-- ✅ NEW: Inline Back Button + Page Info --}}
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
                        </div>
                    </div>
                </div>

                <div class="prose max-w-none">
                    <p class="text-gray-700 leading-relaxed">{{ $business->description }}</p>
                </div>
            </div>
        </div>

        {{-- Tabs Navigation --}}
        <div x-data="{ activeTab: 'products' }" class="bg-white shadow-sm sm:rounded-lg">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px px-6">
                    <button @click="activeTab = 'products'" 
                            :class="activeTab === 'products' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="flex items-center gap-2 py-4 px-4 border-b-2 font-medium text-sm transition duration-150">
                        <i class="bi bi-box-seam"></i>
                        Products
                        <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full text-xs">{{ $business->products->count() }}</span>
                    </button>

                    <button @click="activeTab = 'services'" 
                            :class="activeTab === 'services' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="flex items-center gap-2 py-4 px-4 border-b-2 font-medium text-sm transition duration-150">
                        <i class="bi bi-wrench"></i>
                        Services
                        <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full text-xs">{{ $business->services->count() }}</span>
                    </button>

                    <button @click="activeTab = 'photos'" 
                            :class="activeTab === 'photos' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="flex items-center gap-2 py-4 px-4 border-b-2 font-medium text-sm transition duration-150">
                        <i class="bi bi-images"></i>
                        Photos
                        <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full text-xs">{{ $business->photos->count() }}</span>
                    </button>

                    <button @click="activeTab = 'contacts'" 
                            :class="activeTab === 'contacts' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="flex items-center gap-2 py-4 px-4 border-b-2 font-medium text-sm transition duration-150">
                        <i class="bi bi-telephone"></i>
                        Contacts
                        <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full text-xs">{{ $business->contacts->count() }}</span>
                    </button>

                    <button @click="activeTab = 'testimonies'" 
                            :class="activeTab === 'testimonies' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="flex items-center gap-2 py-4 px-4 border-b-2 font-medium text-sm transition duration-150">
                        <i class="bi bi-star"></i>
                        Testimonies
                        <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full text-xs">{{ $approvedTestimonies->count() }}</span>
                    </button>
                </nav>
            </div>

            {{-- Tab: Products --}}
            <div x-show="activeTab === 'products'" class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Products</h3>
                    @auth
                        @if(auth()->id() === $business->user_id || auth()->user()->isAdmin())
                            <a href="{{ route('businesses.products.create', $business) }}" 
                               class="inline-flex items-center px-3 py-2 bg-orange-600 text-white text-sm rounded-md hover:bg-orange-700 transition duration-150">
                                <i class="bi bi-plus-lg me-2"></i>
                                Add Product
                            </a>
                        @endif
                    @endauth
                </div>

                @if($business->products->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($business->products as $product)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition duration-150">
                                @if($product->photos->first())
                                    <img src="{{ asset('storage/' . $product->photos->first()->photo_url) }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-32 object-cover rounded-md mb-3">
                                @endif
                                <h4 class="font-semibold text-gray-900 mb-1">{{ $product->name }}</h4>
                                <p class="text-sm text-gray-600 mb-2 line-clamp-2">{{ $product->description }}</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-orange-600 font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    <span class="text-xs text-gray-500">{{ $product->productCategory->name }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="bi bi-box-seam text-4xl mb-2"></i>
                        <p>No products yet.</p>
                    </div>
                @endif
            </div>

            {{-- Tab: Services --}}
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
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h4 class="font-semibold text-gray-900 mb-1">{{ $service->name }}</h4>
                                        <p class="text-sm text-gray-600 mb-2">{{ $service->description }}</p>
                                        <div class="flex items-center gap-2">
                                            <span class="text-orange-600 font-bold">Rp {{ number_format($service->price, 0, ',', '.') }}</span>
                                            <span class="text-xs text-gray-500">/ {{ $service->price_type }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="bi bi-wrench text-4xl mb-2"></i>
                        <p>No services yet.</p>
                    </div>
                @endif
            </div>

            {{-- Tab: Photos --}}
            <div x-show="activeTab === 'photos'" class="p-6" style="display: none;">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Photo Gallery</h3>
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
                                    <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-2 rounded-b-lg opacity-0 group-hover:opacity-100 transition duration-150">
                                        {{ $photo->caption }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="bi bi-images text-4xl mb-2"></i>
                        <p>No photos yet.</p>
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
                            <div class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-150">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-orange-100 text-orange-600">
                                    <i class="bi {{ $contact->contactType->icon_class }}"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $contact->contactType->platform_name }}</p>
                                    <p class="text-sm text-gray-600">{{ $contact->contact_value }}</p>
                                </div>
                                @if($contact->is_primary)
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Primary</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="bi bi-telephone text-4xl mb-2"></i>
                        <p>No contact information yet.</p>
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
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-start justify-between mb-2">
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ $testimony->customer_name }}</h4>
                                        <div class="flex items-center gap-1 mt-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star{{ $i <= $testimony->rating ? '-fill' : '' }} text-yellow-500 text-sm"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <span class="text-xs text-gray-500">{{ $testimony->date->format('d M Y') }}</span>
                                </div>
                                <p class="text-sm text-gray-700">{{ $testimony->content }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="bi bi-star text-4xl mb-2"></i>
                        <p>No testimonies yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>