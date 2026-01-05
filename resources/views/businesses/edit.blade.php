<x-app-layout>
    <div class="max-w-5xl mx-auto py-6 sm:py-12 px-4 sm:px-6 lg:px-8" x-data="{ activeTab: 'basic' }">
        
        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 sm:px-6 py-4 rounded-xl shadow-sm flex items-start gap-3">
                <i class="bi bi-check-circle-fill text-green-600 text-xl flex-shrink-0 mt-0.5"></i>
                <div class="flex-1">
                    <p class="font-semibold">Success!</p>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-xl shadow-sm">
                <div class="flex items-start gap-3">
                    <i class="bi bi-exclamation-triangle-fill text-red-600 text-xl flex-shrink-0 mt-0.5"></i>
                    <div class="flex-1">
                        <p class="font-semibold mb-2">Please fix the following errors:</p>
                        <ul class="list-disc list-inside space-y-1 text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- Page Header --}}
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm px-4 sm:px-8 py-6 sm:py-10 mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                <a href="{{ route('businesses.show', $business) }}" 
                   class="group inline-flex items-center justify-center sm:justify-start gap-2.5 px-4 py-2.5 bg-white hover:bg-gray-900 border border-gray-200 hover:border-gray-900 text-gray-700 hover:text-white rounded-xl font-medium text-sm shadow-sm hover:shadow-md transition-all duration-200">
                    <i class="bi bi-arrow-left text-base group-hover:-translate-x-0.5 transition-transform duration-200"></i>
                    <span>Back</span>
                </a>
                <div class="flex-1 text-center sm:text-left">
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-800">Edit Business</h1>
                    <p class="text-slate-600 mt-2 text-sm sm:text-base">{{ $business->name }}</p>
                </div>
            </div>
        </div>

        {{-- Tab Navigation --}}
        <div class="bg-white border border-slate-200 rounded-t-xl shadow-sm mb-0">
            <div class="flex border-b border-slate-200 overflow-x-auto">
                <button type="button" 
                        @click="activeTab = 'basic'"
                        :class="activeTab === 'basic' ? 'border-b-2 border-gray-900 text-gray-900 font-semibold' : 'text-slate-500 hover:text-slate-700'"
                        class="flex-1 px-4 sm:px-6 py-4 text-xs sm:text-sm transition-colors whitespace-nowrap">
                    Informasi Dasar
                </button>
                <button type="button" 
                        @click="activeTab = 'products'"
                        :class="activeTab === 'products' ? 'border-b-2 border-gray-900 text-gray-900 font-semibold' : 'text-slate-500 hover:text-slate-700'"
                        class="flex-1 px-4 sm:px-6 py-4 text-xs sm:text-sm transition-colors whitespace-nowrap">
                    Produk & Layanan
                </button>
                <button type="button" 
                        @click="activeTab = 'development'"
                        :class="activeTab === 'development' ? 'border-b-2 border-gray-900 text-gray-900 font-semibold' : 'text-slate-500 hover:text-slate-700'"
                        class="flex-1 px-4 sm:px-6 py-4 text-xs sm:text-sm transition-colors whitespace-nowrap">
                    Perkembangan Business
                </button>
                <button type="button" 
                        @click="activeTab = 'documents'"
                        :class="activeTab === 'documents' ? 'border-b-2 border-gray-900 text-gray-900 font-semibold' : 'text-slate-500 hover:text-slate-700'"
                        class="flex-1 px-4 sm:px-6 py-4 text-xs sm:text-sm transition-colors whitespace-nowrap">
                    Dokumen & Sertifikasi
                </button>
            </div>
        </div>

        <form method="POST" action="{{ route('businesses.update', $business) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- TAB 1: BASIC INFORMATION --}}
            <div x-show="activeTab === 'basic'" class="bg-white border-x border-b border-slate-200 rounded-b-xl shadow-sm p-8 space-y-5">
                {{-- Business Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-2">
                        Nama Business <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name', $business->name) }}"
                           required
                           class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('name') border-red-300 @enderror transition"
                           placeholder="e.g., Warung Kopi Senja">
                    @error('name')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Position --}}
                <div>
                    <label for="position" class="block text-sm font-medium text-slate-700 mb-2">
                        Posisi Anda
                    </label>
                    <input type="text" 
                           name="position" 
                           id="position" 
                           value="{{ old('position', $business->position) }}"
                           class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('position') border-red-300 @enderror transition"
                           placeholder="e.g., CEO, Co-Founder, Marketing Manager, Staff">
                    @error('position')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Business Type & Mode --}}
                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label for="business_type_id" class="block text-sm font-medium text-slate-700 mb-2">
                            Kategori Business <span class="text-red-500">*</span>
                        </label>
                        <select name="business_type_id" 
                                id="business_type_id"
                                required
                                class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('business_type_id') border-red-300 @enderror transition">
                            <option value="">Pilih Kategori</option>
                            @foreach($businessTypes as $type)
                                <option value="{{ $type->id }}" {{ (old('business_type_id', $business->business_type_id) == $type->id || $business->business_type_id == $type->id) ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('business_type_id')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="business_mode" class="block text-sm font-medium text-slate-700 mb-2">
                            Jenis Offering <span class="text-red-500">*</span>
                        </label>
                        <select name="business_mode" 
                                id="business_mode"
                                required
                                class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('business_mode') border-red-300 @enderror transition">
                            <option value="">Pilih Jenis Offering</option>
                            <option value="product" {{ (old('business_mode', $business->business_mode) == 'product' || $business->business_mode == 'product') ? 'selected' : '' }}>Product Only</option>
                            <option value="service" {{ (old('business_mode', $business->business_mode) == 'service' || $business->business_mode == 'service') ? 'selected' : '' }}>Service Only</option>
                            <option value="both" {{ (old('business_mode', $business->business_mode) == 'both' || $business->business_mode == 'both') ? 'selected' : '' }}>Product & Service</option>
                        </select>
                        @error('business_mode')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-slate-700 mb-2">
                        Deskripsi <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" 
                              id="description" 
                              rows="4"
                              required
                              class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('description') border-red-300 @enderror transition"
                              placeholder="Deskripsikan business Anda...">{{ old('description', $business->description) }}</textarea>
                    @error('description')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Location Fields --}}
                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label for="city" class="block text-sm font-medium text-slate-700 mb-2">
                            Kota <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="city" 
                               id="city" 
                               value="{{ old('city', $business->city) }}"
                               required
                               class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('city') border-red-300 @enderror transition"
                               placeholder="e.g., Jakarta">
                        @error('city')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="province" class="block text-sm font-medium text-slate-700 mb-2">
                            Provinsi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="province" 
                               id="province" 
                               value="{{ old('province', $business->province) }}"
                               required
                               class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('province') border-red-300 @enderror transition"
                               placeholder="e.g., DKI Jakarta">
                        @error('province')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Full Address --}}
                <div>
                    <label for="address" class="block text-sm font-medium text-slate-700 mb-2">
                        Alamat Lengkap
                    </label>
                    <textarea name="address" 
                              id="address" 
                              rows="3"
                              class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('address') border-red-300 @enderror transition"
                              placeholder="Alamat lengkap business...">{{ old('address', $business->address) }}</textarea>
                    @error('address')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Contact Info --}}
                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label for="phone" class="block text-sm font-medium text-slate-700 mb-2">
                            Nomor Telepon
                        </label>
                        <input type="text" 
                               name="phone" 
                               id="phone" 
                               value="{{ old('phone', $business->phone) }}"
                               class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('phone') border-red-300 @enderror transition"
                               placeholder="e.g., 0812-3456-7890">
                        @error('phone')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-2">
                            Email
                        </label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="{{ old('email', $business->email) }}"
                               class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('email') border-red-300 @enderror transition"
                               placeholder="business@example.com">
                        @error('email')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Logo Upload - FULLY CLICKABLE --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Logo Business</label>
                    @if($business->logo)
                        <div class="mb-3">
                            <p class="text-sm text-slate-600 mb-2">Current Logo:</p>
                            <img src="{{ asset('storage/' . $business->logo) }}" alt="Current Logo" class="max-w-xs rounded-lg shadow-md">
                        </div>
                    @endif
                    <input type="file" name="logo" id="logo" accept="image/*" class="hidden" onchange="previewLogo(event)">
                    <label for="logo" class="block border-2 border-dashed border-slate-300 rounded-lg p-8 text-center hover:border-soft-gray-900 hover:bg-slate-50 transition cursor-pointer">
                        <div class="text-slate-400 mb-3">
                            <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <p class="text-sm text-slate-600 font-medium">Klik area ini untuk upload logo baru</p>
                        <p class="text-xs text-slate-400 mt-1">PNG, JPG up to 2MB</p>
                    </label>
                    <div id="logoPreview" class="mt-4 hidden">
                        <img src="" alt="Logo Preview" class="max-w-xs rounded-lg shadow-md mx-auto">
                    </div>
                    @error('logo')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Website --}}
                <div>
                    <label for="website" class="block text-sm font-medium text-slate-700 mb-2">
                        Website
                    </label>
                    <input type="url" 
                           name="website" 
                           id="website" 
                           value="{{ old('website', $business->website) }}"
                           class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('website') border-red-300 @enderror transition"
                           placeholder="https://example.com">
                    @error('website')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Social Media --}}
                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label for="instagram_handle" class="block text-sm font-medium text-slate-700 mb-2">
                            Instagram Handle
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-3.5 text-slate-400">@</span>
                            <input type="text" 
                                   name="instagram_handle" 
                                   id="instagram_handle" 
                                   value="{{ old('instagram_handle', $business->instagram_handle) }}"
                                   class="block w-full pl-8 pr-4 py-3 border border-slate-300 rounded-lg focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('instagram_handle') border-red-300 @enderror transition"
                                   placeholder="username">
                        </div>
                        @error('instagram_handle')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="whatsapp_number" class="block text-sm font-medium text-slate-700 mb-2">
                            WhatsApp Number
                        </label>
                        <input type="text" 
                               name="whatsapp_number" 
                               id="whatsapp_number" 
                               value="{{ old('whatsapp_number', $business->whatsapp_number) }}"
                               class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('whatsapp_number') border-red-300 @enderror transition"
                               placeholder="628123456789">
                        @error('whatsapp_number')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                @if(auth()->user()->role === 'admin')
                <div>
                    <label for="user_id" class="block text-sm font-medium text-slate-700 mb-2">
                        Owner (Admin Only)
                    </label>
                    <select name="user_id" 
                            id="user_id"
                            class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('user_id') border-red-300 @enderror transition">
                        <option value="">Select Owner</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ (old('user_id', $business->user_id) == $user->id || $business->user_id == $user->id) ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                @endif
            </div>

            {{-- TAB 2: PRODUCTS & SERVICES --}}
            <div x-show="activeTab === 'products'" class="bg-white border-x border-b border-slate-200 rounded-b-xl shadow-sm p-8 space-y-5">
                {{-- Product Name --}}
                <div>
                    <label for="product_name" class="block text-sm font-medium text-slate-700 mb-2">
                        Nama Produk/Layanan Utama
                    </label>
                    <input type="text" 
                           name="product_name" 
                           id="product_name" 
                           value="{{ old('product_name', $business->product_name) }}"
                           class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('product_name') border-red-300 @enderror transition"
                           placeholder="e.g., Kopi Arabica Premium">
                    @error('product_name')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Product Description --}}
                <div>
                    <label for="product_description" class="block text-sm font-medium text-slate-700 mb-2">
                        Deskripsi Produk/Layanan
                    </label>
                    <textarea name="product_description" 
                              id="product_description" 
                              rows="4"
                              class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('product_description') border-red-300 @enderror transition"
                              placeholder="Jelaskan produk/layanan yang Anda tawarkan...">{{ old('product_description', $business->product_description) }}</textarea>
                    @error('product_description')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Unique Value Proposition --}}
                <div>
                    <label for="unique_value_proposition" class="block text-sm font-medium text-slate-700 mb-2">
                        Keunikan/Nilai Lebih Produk
                    </label>
                    <textarea name="unique_value_proposition" 
                              id="unique_value_proposition" 
                              rows="3"
                              class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('unique_value_proposition') border-red-300 @enderror transition"
                              placeholder="Apa yang membuat produk Anda berbeda dari kompetitor?">{{ old('unique_value_proposition', $business->unique_value_proposition) }}</textarea>
                    @error('unique_value_proposition')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Target Market --}}
                <div>
                    <label for="target_market" class="block text-sm font-medium text-slate-700 mb-2">
                        Target Market
                    </label>
                    <input type="text" 
                           name="target_market" 
                           id="target_market" 
                           value="{{ old('target_market', $business->target_market) }}"
                           class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('target_market') border-red-300 @enderror transition"
                           placeholder="e.g., Milenial, Profesional muda, Pecinta kopi">
                    @error('target_market')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Customer Base --}}
                <div>
                    <label for="customer_base_size" class="block text-sm font-medium text-slate-700 mb-2">
                        Jumlah Customer Aktif
                    </label>
                    <input type="number" 
                           name="customer_base_size" 
                           id="customer_base_size" 
                           value="{{ old('customer_base_size', $business->customer_base_size) }}"
                           min="0"
                           class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('customer_base_size') border-red-300 @enderror transition"
                           placeholder="e.g., 500">
                    @error('customer_base_size')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- TAB 3: BUSINESS DEVELOPMENT --}}
            <div x-show="activeTab === 'development'" class="bg-white border-x border-b border-slate-200 rounded-b-xl shadow-sm p-8 space-y-5">
                {{-- Establishment Date & Operational Status --}}
                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label for="establishment_date" class="block text-sm font-medium text-slate-700 mb-2">
                            Tanggal Berdiri
                        </label>
                        <input type="date" 
                               name="establishment_date" 
                               id="establishment_date" 
                               value="{{ old('establishment_date', $business->establishment_date) }}"
                               class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('establishment_date') border-red-300 @enderror transition">
                        @error('establishment_date')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="operational_status" class="block text-sm font-medium text-slate-700 mb-2">
                            Status Operasional
                        </label>
                        <select name="operational_status" 
                                id="operational_status"
                                class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('operational_status') border-red-300 @enderror transition">
                            <option value="">Pilih Status</option>
                            <option value="active" {{ (old('operational_status', $business->operational_status) == 'active' || $business->operational_status == 'active') ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ (old('operational_status', $business->operational_status) == 'inactive' || $business->operational_status == 'inactive') ? 'selected' : '' }}>Tidak Aktif</option>
                            <option value="seasonal" {{ (old('operational_status', $business->operational_status) == 'seasonal' || $business->operational_status == 'seasonal') ? 'selected' : '' }}>Musiman</option>
                        </select>
                        @error('operational_status')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Employee Count & Revenue Range --}}
                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label for="employee_count" class="block text-sm font-medium text-slate-700 mb-2">
                            Jumlah Karyawan
                        </label>
                        <input type="number" 
                               name="employee_count" 
                               id="employee_count" 
                               value="{{ old('employee_count', $business->employee_count) }}"
                               min="0"
                               class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('employee_count') border-red-300 @enderror transition"
                               placeholder="e.g., 5">
                        @error('employee_count')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="revenue_range" class="block text-sm font-medium text-slate-700 mb-2">
                            Range Pendapatan (per bulan)
                        </label>
                        <select name="revenue_range" 
                                id="revenue_range"
                                class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('revenue_range') border-red-300 @enderror transition">
                            <option value="">Pilih Range</option>
                            <option value="< 10jt" {{ (old('revenue_range', $business->revenue_range) == '< 10jt' || $business->revenue_range == '< 10jt') ? 'selected' : '' }}>< 10 Juta</option>
                            <option value="10jt - 50jt" {{ (old('revenue_range', $business->revenue_range) == '10jt - 50jt' || $business->revenue_range == '10jt - 50jt') ? 'selected' : '' }}>10 - 50 Juta</option>
                            <option value="50jt - 100jt" {{ (old('revenue_range', $business->revenue_range) == '50jt - 100jt' || $business->revenue_range == '50jt - 100jt') ? 'selected' : '' }}>50 - 100 Juta</option>
                            <option value="> 100jt" {{ (old('revenue_range', $business->revenue_range) == '> 100jt' || $business->revenue_range == '> 100jt') ? 'selected' : '' }}>> 100 Juta</option>
                        </select>
                        @error('revenue_range')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Business Challenges (Dynamic) --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Tantangan Business
                    </label>
                    <div id="challengesContainer" class="space-y-3">
                        @if($business->business_challenges && count($business->business_challenges) > 0)
                            @foreach($business->business_challenges as $challenge)
                                <div class="flex gap-2">
                                    <input type="text" 
                                           name="business_challenges[]" 
                                           value="{{ $challenge }}"
                                           class="flex-1 px-4 py-3 border border-slate-300 rounded-lg focus:ring-soft-gray-900 focus:border-soft-gray-900 transition"
                                           placeholder="e.g., Keterbatasan modal">
                                    <button type="button" 
                                            onclick="this.parentElement.remove()"
                                            class="px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition">
                                        ✖
                                    </button>
                                </div>
                            @endforeach
                        @else
                            <div class="flex gap-2">
                                <input type="text" 
                                       name="business_challenges[]" 
                                       class="flex-1 px-4 py-3 border border-slate-300 rounded-lg focus:ring-soft-gray-900 focus:border-soft-gray-900 transition"
                                       placeholder="e.g., Keterbatasan modal">
                            </div>
                        @endif
                    </div>
                    <button type="button" 
                            onclick="addChallenge()"
                            class="mt-3 px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition text-sm font-medium">
                        + Tambah Tantangan
                    </button>
                    @error('business_challenges')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- TAB 4: DOCUMENTS & CERTIFICATIONS --}}
            <div x-show="activeTab === 'documents'" class="bg-white border-x border-b border-slate-200 rounded-b-xl shadow-sm p-8 space-y-6">
                {{-- Legal Documents Upload - FULLY CLICKABLE --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Dokumen Legal (Optional)</label>
                    @if($business->legal_documents && count($business->legal_documents) > 0)
                        <div class="mb-3">
                            <p class="text-sm text-slate-600 mb-2">Current Documents:</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($business->legal_documents as $index => $doc)
                                    <div class="border border-slate-200 rounded-lg p-3 bg-slate-50 relative">
                                        <div class="mb-2">
                                            @if(is_array($doc) && isset($doc['file_path']))
                                                <a href="{{ asset('storage/' . $doc['file_path']) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm truncate block">
                                                    📄 {{ $doc['original_name'] ?? basename($doc['file_path']) }}
                                                </a>
                                            @else
                                                <a href="{{ asset('storage/' . $doc) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm truncate block">
                                                    📄 {{ basename($doc) }}
                                                </a>
                                            @endif
                                        </div>
                                        <label class="flex items-center gap-1.5 text-xs text-red-600 cursor-pointer hover:text-red-800">
                                            <input type="checkbox" 
                                                   name="remove_legal_docs[]" 
                                                   value="{{ $index }}"
                                                   class="rounded border-red-300 text-red-600 focus:ring-red-500">
                                            <span>Hapus file ini</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <input type="file" name="legal_documents[]" id="legal_documents" multiple accept=".pdf,image/*" class="hidden" onchange="previewLegalDocs(event)">
                    <label for="legal_documents" class="block border-2 border-dashed border-slate-300 rounded-lg p-8 text-center hover:border-soft-gray-900 hover:bg-slate-50 transition cursor-pointer">
                        <div class="text-slate-400 mb-3">
                            <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <p class="text-sm text-slate-600 font-medium">Klik area ini untuk upload dokumen baru</p>
                        <p class="text-xs text-slate-400 mt-1">PDF, Image up to 5MB per file</p>
                    </label>
                    <div id="legalDocsPreview" class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"></div>
                    @error('legal_documents')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Product Certifications Upload - FULLY CLICKABLE --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Sertifikasi Produk (Optional)</label>
                    @if($business->product_certifications && count($business->product_certifications) > 0)
                        <div class="mb-3">
                            <p class="text-sm text-slate-600 mb-2">Current Certifications:</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($business->product_certifications as $index => $cert)
                                    <div class="border border-slate-200 rounded-lg p-3 bg-slate-50 relative">
                                        <div class="mb-2">
                                            @if(is_array($cert) && isset($cert['file_path']))
                                                <a href="{{ asset('storage/' . $cert['file_path']) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm truncate block">
                                                    🏆 {{ $cert['original_name'] ?? basename($cert['file_path']) }}
                                                </a>
                                            @else
                                                <a href="{{ asset('storage/' . $cert) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm truncate block">
                                                    🏆 {{ basename($cert) }}
                                                </a>
                                            @endif
                                        </div>
                                        <label class="flex items-center gap-1.5 text-xs text-red-600 cursor-pointer hover:text-red-800">
                                            <input type="checkbox" 
                                                   name="remove_certifications[]" 
                                                   value="{{ $index }}"
                                                   class="rounded border-red-300 text-red-600 focus:ring-red-500">
                                            <span>Hapus file ini</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <input type="file" name="product_certifications[]" id="product_certifications" multiple accept=".pdf,image/*" class="hidden" onchange="previewCertifications(event)">
                    <label for="product_certifications" class="block border-2 border-dashed border-slate-300 rounded-lg p-8 text-center hover:border-soft-gray-900 hover:bg-slate-50 transition cursor-pointer">
                        <div class="text-slate-400 mb-3">
                            <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                            </svg>
                        </div>
                        <p class="text-sm text-slate-600 font-medium">Klik area ini untuk upload sertifikat baru</p>
                        <p class="text-xs text-slate-400 mt-1">PDF, Image up to 5MB per file</p>
                    </label>
                    <div id="certificationsPreview" class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"></div>
                    @error('product_certifications')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Submit Buttons --}}
            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 bg-soft-gray-900 hover:bg-soft-gray-800 text-white font-semibold py-4 px-6 rounded-lg shadow-md transition duration-200">
                    Update Business
                </button>
                <a href="{{ route('businesses.show', $business) }}" class="flex-1 bg-slate-400 hover:bg-slate-500 text-white font-semibold py-4 px-6 rounded-lg shadow-md transition duration-200 text-center flex items-center justify-center">
                    Batal
                </a>
            </div>
        </form>

        {{-- Delete Business Form --}}
        <form method="POST" action="{{ route('businesses.destroy', $business) }}" class="mt-8" onsubmit="return confirm('Are you sure you want to delete this business? This action cannot be undone.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-lg shadow-md transition duration-200">
                Delete Business
            </button>
        </form>
    </div>

    {{-- JavaScript for File Previews and Dynamic Fields --}}
    <script>
        function previewLogo(event) {
            const file = event.target.files[0];
            const maxSize = 10 * 1024 * 1024; // 10MB
            
            if (file) {
                if (file.size > maxSize) {
                    alert('Logo must not be larger than 10MB. Please choose a smaller file.');
                    event.target.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('logoPreview');
                    preview.querySelector('img').src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        function previewLegalDocs(event) {
            const files = event.target.files;
            const maxSize = 5 * 1024 * 1024; // 5MB per file
            const container = document.getElementById('legalDocsPreview');
            container.innerHTML = '';
            
            // Validate file sizes
            for (let file of files) {
                if (file.size > maxSize) {
                    alert(`File "${file.name}" is too large. Each file must not exceed 5MB.`);
                    event.target.value = '';
                    return;
                }
            }
            
            Array.from(files).forEach((file, index) => {
                const div = document.createElement('div');
                div.className = 'border border-slate-200 rounded-lg p-3 bg-slate-50';
                
                if (file.type.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.className = 'w-full h-32 object-cover rounded mb-2';
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        img.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                    div.appendChild(img);
                } else {
                    div.innerHTML = `<div class="h-32 flex items-center justify-center text-slate-400">
                        <svg class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>`;
                }
                
                const name = document.createElement('p');
                name.className = 'text-xs text-slate-600 truncate mt-2';
                name.textContent = file.name;
                div.appendChild(name);
                
                container.appendChild(div);
            });
        }

        function previewCertifications(event) {
            const files = event.target.files;
            const maxSize = 5 * 1024 * 1024; // 5MB per file
            const container = document.getElementById('certificationsPreview');
            container.innerHTML = '';
            
            // Validate file sizes
            for (let file of files) {
                if (file.size > maxSize) {
                    alert(`File "${file.name}" is too large. Each file must not exceed 5MB.`);
                    event.target.value = '';
                    return;
                }
            }
            
            Array.from(files).forEach((file, index) => {
                const div = document.createElement('div');
                div.className = 'border border-slate-200 rounded-lg p-3 bg-slate-50';
                
                if (file.type.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.className = 'w-full h-32 object-cover rounded mb-2';
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        img.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                    div.appendChild(img);
                } else {
                    div.innerHTML = `<div class="h-32 flex items-center justify-center text-slate-400">
                        <svg class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>`;
                }
                
                const name = document.createElement('p');
                name.className = 'text-xs text-slate-600 truncate mt-2';
                name.textContent = file.name;
                div.appendChild(name);
                
                container.appendChild(div);
            });
        }

        function addChallenge() {
            const container = document.getElementById('challengesContainer');
            const newChallenge = document.createElement('div');
            newChallenge.className = 'flex gap-2';
            newChallenge.innerHTML = `
                <input type="text" 
                       name="business_challenges[]" 
                       class="flex-1 px-4 py-3 border border-slate-300 rounded-lg focus:ring-soft-gray-900 focus:border-soft-gray-900 transition"
                       placeholder="Masukkan tantangan">
                <button type="button" 
                        onclick="this.parentElement.remove()"
                        class="px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition">
                    ✖
                </button>
            `;
            container.appendChild(newChallenge);
        }
    </script>
</x-app-layout>
