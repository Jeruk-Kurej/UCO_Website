<x-app-layout>
    <div class="max-w-5xl mx-auto py-12 px-4">
        
        {{-- Page Header --}}
        <div class="bg-gradient-to-br from-slate-50 to-blue-50 border border-slate-200 rounded-xl shadow-sm px-8 py-10 mb-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('businesses.show', $business) }}" 
                   class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-white border border-slate-300 hover:border-slate-400 text-slate-600 hover:text-slate-900 transition-all shadow-sm">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-slate-800">Edit Business</h1>
                    <p class="text-slate-600 mt-2">{{ $business->name }}</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('businesses.update', $business) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- SECTION 1: BASIC INFORMATION --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-l-4 border-blue-400 px-6 py-4">
                    <h3 class="text-xl font-bold text-slate-700">📋 Informasi Dasar</h3>
                </div>

                <div class="p-6 space-y-5">
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
                               class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-100 @error('name') border-red-300 @enderror transition"
                               placeholder="e.g., Warung Kopi Senja">
                        @error('name')
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
                                    class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-100 @error('business_type_id') border-red-300 @enderror transition">
                                <option value="">Pilih Kategori</option>
                                @foreach($businessTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('business_type_id', $business->business_type_id) == $type->id ? 'selected' : '' }}>
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
                                    class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:border-orange-400 focus:ring-2 focus:ring-orange-100 @error('business_mode') border-red-300 @enderror transition">
                                <option value="">Pilih Jenis Offering</option>
                                <option value="product" {{ old('business_mode', $business->business_mode) == 'product' ? 'selected' : '' }}>Product Only</option>
                                <option value="service" {{ old('business_mode', $business->business_mode) == 'service' ? 'selected' : '' }}>Service Only</option>
                                <option value="both" {{ old('business_mode', $business->business_mode) == 'both' ? 'selected' : '' }}>Product & Service</option>
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
                                  class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-100 @error('description') border-red-300 @enderror transition"
                                  placeholder="Deskripsikan business Anda...">{{ old('description', $business->description) }}</textarea>
                        @error('description')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Location Fields --}}
                    <div class="grid md:grid-cols-2 gap-5">
                        <div>
                            <label for="city" class="block text-sm font-medium text-slate-700 mb-2">
                                Kota
                            </label>
                            <input type="text" 
                                   name="city" 
                                   id="city" 
                                   value="{{ old('city', $business->city) }}"
                                   class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-100 @error('city') border-red-300 @enderror transition"
                                   placeholder="e.g., Jakarta">
                            @error('city')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="province" class="block text-sm font-medium text-slate-700 mb-2">
                                Provinsi
                            </label>
                            <input type="text" 
                                   name="province" 
                                   id="province" 
                                   value="{{ old('province', $business->province) }}"
                                   class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-100 @error('province') border-red-300 @enderror transition"
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
                                  class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-100 @error('address') border-red-300 @enderror transition"
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
                                   class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-100 @error('phone') border-red-300 @enderror transition"
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
                                   class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-100 @error('email') border-red-300 @enderror transition"
                                   placeholder="business@example.com">
                            @error('email')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Logo Upload - FULLY CLICKABLE --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Logo Business</label>
                        
                        @if($business->logo_url)
                            <div class="mb-4 p-4 bg-slate-50 rounded-lg border border-slate-200">
                                <p class="text-xs font-medium text-slate-700 mb-2">Logo Saat Ini:</p>
                                <img src="{{ asset('storage/' . $business->logo_url) }}" 
                                     alt="{{ $business->name }} logo" 
                                     class="h-24 w-24 object-contain rounded-lg border border-slate-300">
                            </div>
                        @endif
                        
                        <input type="file" name="logo" id="logo" accept="image/*" class="hidden" onchange="previewLogo(event)">
                        <label for="logo" class="block border-2 border-dashed border-slate-300 rounded-lg p-8 text-center hover:border-blue-400 hover:bg-blue-50 transition cursor-pointer">
                            <div class="text-slate-400 mb-3">
                                <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <p class="text-sm text-slate-600 font-medium">{{ $business->logo_url ? 'Klik untuk ganti logo' : 'Klik untuk upload logo' }}</p>
                            <p class="text-xs text-slate-400 mt-1">PNG, JPG up to 2MB</p>
                        </label>
                        <div id="logoPreview" class="mt-4 hidden">
                            <img src="" alt="Logo Preview" class="max-w-xs rounded-lg shadow-md mx-auto">
                        </div>
                        @error('logo')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Website & Social Media --}}
                    <div>
                        <label for="website" class="block text-sm font-medium text-slate-700 mb-2">
                            Website
                        </label>
                        <input type="url" 
                               name="website" 
                               id="website" 
                               value="{{ old('website', $business->website) }}"
                               class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-100 @error('website') border-red-300 @enderror transition"
                               placeholder="https://example.com">
                        @error('website')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

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
                                       class="block w-full pl-8 pr-4 py-3 border border-slate-300 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-100 @error('instagram_handle') border-red-300 @enderror transition"
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
                                   class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-100 @error('whatsapp_number') border-red-300 @enderror transition"
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
                                class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-100 @error('user_id') border-red-300 @enderror transition">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', $business->user_id) == $user->id ? 'selected' : '' }}>
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
            </div>

            {{-- SECTION 2: PRODUCTS & SERVICES --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="bg-gradient-to-br from-purple-50 to-pink-50 border-l-4 border-purple-400 px-6 py-4">
                    <h3 class="text-xl font-bold text-slate-700">🛍️ Produk & Layanan</h3>
                </div>

                <div class="p-6 space-y-5">
                    <div>
                        <label for="product_name" class="block text-sm font-medium text-slate-700 mb-2">
                            Nama Produk/Layanan Utama
                        </label>
                        <input type="text" 
                               name="product_name" 
                               id="product_name" 
                               value="{{ old('product_name', $business->product_name) }}"
                               class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-100 @error('product_name') border-red-300 @enderror transition"
                               placeholder="e.g., Kopi Arabica Premium">
                        @error('product_name')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="product_description" class="block text-sm font-medium text-slate-700 mb-2">
                            Deskripsi Produk/Layanan
                        </label>
                        <textarea name="product_description" 
                                  id="product_description" 
                                  rows="4"
                                  class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-100 @error('product_description') border-red-300 @enderror transition"
                                  placeholder="Jelaskan produk/layanan yang Anda tawarkan...">{{ old('product_description', $business->product_description) }}</textarea>
                        @error('product_description')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="unique_value_proposition" class="block text-sm font-medium text-slate-700 mb-2">
                            Keunikan/Nilai Lebih Produk
                        </label>
                        <textarea name="unique_value_proposition" 
                                  id="unique_value_proposition" 
                                  rows="3"
                                  class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-100 @error('unique_value_proposition') border-red-300 @enderror transition"
                                  placeholder="Apa yang membuat produk Anda berbeda dari kompetitor?">{{ old('unique_value_proposition', $business->unique_value_proposition) }}</textarea>
                        @error('unique_value_proposition')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="target_market" class="block text-sm font-medium text-slate-700 mb-2">
                            Target Market
                        </label>
                        <input type="text" 
                               name="target_market" 
                               id="target_market" 
                               value="{{ old('target_market', $business->target_market) }}"
                               class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-100 @error('target_market') border-red-300 @enderror transition"
                               placeholder="e.g., Milenial, Profesional muda, Pecinta kopi">
                        @error('target_market')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="customer_base_size" class="block text-sm font-medium text-slate-700 mb-2">
                            Jumlah Customer Aktif
                        </label>
                        <input type="number" 
                               name="customer_base_size" 
                               id="customer_base_size" 
                               value="{{ old('customer_base_size', $business->customer_base_size) }}"
                               min="0"
                               class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-100 @error('customer_base_size') border-red-300 @enderror transition"
                               placeholder="e.g., 500">
                        @error('customer_base_size')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- SECTION 3: BUSINESS DEVELOPMENT --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="bg-gradient-to-br from-emerald-50 to-teal-50 border-l-4 border-emerald-400 px-6 py-4">
                    <h3 class="text-xl font-bold text-slate-700">📈 Perkembangan Business</h3>
                </div>

                <div class="p-6 space-y-5">
                    <div class="grid md:grid-cols-2 gap-5">
                        <div>
                            <label for="establishment_date" class="block text-sm font-medium text-slate-700 mb-2">
                                Tanggal Berdiri
                            </label>
                            <input type="date" 
                                   name="establishment_date" 
                                   id="establishment_date" 
                                   value="{{ old('establishment_date', $business->establishment_date?->format('Y-m-d')) }}"
                                   class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-100 @error('establishment_date') border-red-300 @enderror transition">
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
                                    class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-100 @error('operational_status') border-red-300 @enderror transition">
                                <option value="">Pilih Status</option>
                                <option value="active" {{ old('operational_status', $business->operational_status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ old('operational_status', $business->operational_status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                <option value="seasonal" {{ old('operational_status', $business->operational_status) == 'seasonal' ? 'selected' : '' }}>Musiman</option>
                            </select>
                            @error('operational_status')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

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
                                   class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-100 @error('employee_count') border-red-300 @enderror transition"
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
                                    class="block w-full px-4 py-3 border border-slate-300 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-100 @error('revenue_range') border-red-300 @enderror transition">
                                <option value="">Pilih Range</option>
                                <option value="< 10jt" {{ old('revenue_range', $business->revenue_range) == '< 10jt' ? 'selected' : '' }}>< 10 Juta</option>
                                <option value="10jt - 50jt" {{ old('revenue_range', $business->revenue_range) == '10jt - 50jt' ? 'selected' : '' }}>10 - 50 Juta</option>
                                <option value="50jt - 100jt" {{ old('revenue_range', $business->revenue_range) == '50jt - 100jt' ? 'selected' : '' }}>50 - 100 Juta</option>
                                <option value="> 100jt" {{ old('revenue_range', $business->revenue_range) == '> 100jt' ? 'selected' : '' }}>> 100 Juta</option>
                            </select>
                            @error('revenue_range')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Business Challenges --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Tantangan Business
                        </label>
                        
                        @if(count($challenges) > 0)
                            <div class="mb-3 space-y-2">
                                @foreach($challenges as $index => $challenge)
                                    <div class="flex items-center gap-2 p-3 bg-amber-50 rounded-lg border border-amber-200">
                                        <svg class="w-5 h-5 text-amber-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        <span class="flex-1 text-sm text-slate-700">{{ $challenge }}</span>
                                        <button type="button" 
                                                onclick="this.parentElement.remove()"
                                                class="text-red-600 hover:text-red-800 p-1">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                        <input type="hidden" name="business_challenges[]" value="{{ $challenge }}">
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div id="challengesContainer" class="space-y-3"></div>
                        <button type="button" 
                                onclick="addChallenge()"
                                class="mt-3 px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition text-sm font-medium">
                            + Tambah Tantangan
                        </button>
                        @error('business_challenges')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- SECTION 4: DOCUMENTS & CERTIFICATIONS --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="bg-gradient-to-br from-amber-50 to-orange-50 border-l-4 border-amber-400 px-6 py-4">
                    <h3 class="text-xl font-bold text-slate-700">📄 Dokumen & Sertifikasi</h3>
                </div>

                <div class="p-6 space-y-6">
                    {{-- Current Legal Documents --}}
                    @if(count($legalDocs) > 0)
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Dokumen Legal Saat Ini</label>
                            <div class="space-y-2">
                                @foreach($legalDocs as $index => $doc)
                                    <div class="flex items-center gap-2 p-3 bg-purple-50 rounded-lg border border-purple-200">
                                        <svg class="w-5 h-5 text-purple-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span class="flex-1 text-sm text-slate-700">
                                            {{ $doc['original_name'] ?? 'Dokumen ' . ($loop->iteration) }}
                                        </span>
                                        @if(isset($doc['file_path']))
                                            <a href="{{ asset('storage/' . $doc['file_path']) }}" 
                                               target="_blank"
                                               class="text-xs text-purple-600 hover:text-purple-800 font-medium">
                                                Lihat
                                            </a>
                                        @endif
                                        <label class="flex items-center gap-1 text-xs text-red-600 cursor-pointer">
                                            <input type="checkbox" 
                                                   name="remove_legal_docs[]" 
                                                   value="{{ $index }}"
                                                   class="rounded border-red-300">
                                            Hapus
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Upload Legal Documents - FULLY CLICKABLE --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            {{ count($legalDocs) > 0 ? 'Tambah Dokumen Legal' : 'Dokumen Legal (Optional)' }}
                        </label>
                        <input type="file" name="legal_documents[]" id="legal_documents" multiple accept=".pdf,image/*" class="hidden" onchange="previewLegalDocs(event)">
                        <label for="legal_documents" class="block border-2 border-dashed border-slate-300 rounded-lg p-8 text-center hover:border-blue-400 hover:bg-blue-50 transition cursor-pointer">
                            <div class="text-slate-400 mb-3">
                                <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <p class="text-sm text-slate-600 font-medium">Klik area ini untuk upload dokumen</p>
                            <p class="text-xs text-slate-400 mt-1">PDF, Image up to 5MB per file</p>
                        </label>
                        <div id="legalDocsPreview" class="mt-4 grid grid-cols-3 gap-4"></div>
                        @error('legal_documents')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Current Product Certifications --}}
                    @if(count($certifications) > 0)
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Sertifikasi Produk Saat Ini</label>
                            <div class="space-y-2">
                                @foreach($certifications as $index => $cert)
                                    <div class="flex items-center gap-2 p-3 bg-green-50 rounded-lg border border-green-200">
                                        <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                        </svg>
                                        <span class="flex-1 text-sm text-slate-700">
                                            {{ $cert['original_name'] ?? 'Sertifikat ' . ($loop->iteration) }}
                                        </span>
                                        @if(isset($cert['file_path']))
                                            <a href="{{ asset('storage/' . $cert['file_path']) }}" 
                                               target="_blank"
                                               class="text-xs text-green-600 hover:text-green-800 font-medium">
                                                Lihat
                                            </a>
                                        @endif
                                        <label class="flex items-center gap-1 text-xs text-red-600 cursor-pointer">
                                            <input type="checkbox" 
                                                   name="remove_certifications[]" 
                                                   value="{{ $index }}"
                                                   class="rounded border-red-300">
                                            Hapus
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Upload Product Certifications - FULLY CLICKABLE --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            {{ count($certifications) > 0 ? 'Tambah Sertifikasi Produk' : 'Sertifikasi Produk (Optional)' }}
                        </label>
                        <input type="file" name="product_certifications[]" id="product_certifications" multiple accept=".pdf,image/*" class="hidden" onchange="previewCertifications(event)">
                        <label for="product_certifications" class="block border-2 border-dashed border-slate-300 rounded-lg p-8 text-center hover:border-blue-400 hover:bg-blue-50 transition cursor-pointer">
                            <div class="text-slate-400 mb-3">
                                <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                </svg>
                            </div>
                            <p class="text-sm text-slate-600 font-medium">Klik area ini untuk upload sertifikat</p>
                            <p class="text-xs text-slate-400 mt-1">PDF, Image up to 5MB per file</p>
                        </label>
                        <div id="certificationsPreview" class="mt-4 grid grid-cols-3 gap-4"></div>
                        @error('product_certifications')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Submit Buttons --}}
            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-4 px-6 rounded-lg shadow-md transition duration-200">
                    💾 Update Business
                </button>
                <a href="{{ route('businesses.show', $business) }}" class="flex-1 bg-gradient-to-r from-slate-400 to-slate-500 hover:from-slate-500 hover:to-slate-600 text-white font-semibold py-4 px-6 rounded-lg shadow-md transition duration-200 text-center flex items-center justify-center">
                    ✖ Batal
                </a>
                @if(auth()->user()->isAdmin() || auth()->id() === $business->user_id)
                    <button type="button" 
                            onclick="if(confirm('Yakin ingin hapus business ini? Tindakan ini tidak bisa dibatalkan.')) document.getElementById('delete-form').submit();"
                            class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold py-4 px-6 rounded-lg shadow-md transition duration-200">
                        🗑️ Hapus
                    </button>
                @endif
            </div>
        </form>

        @if(auth()->user()->isAdmin() || auth()->id() === $business->user_id)
            <form id="delete-form" action="{{ route('businesses.destroy', $business) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        @endif
    </div>

    {{-- JavaScript for File Previews and Dynamic Fields --}}
    <script>
        function previewLogo(event) {
            const file = event.target.files[0];
            if (file) {
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
            const container = document.getElementById('legalDocsPreview');
            container.innerHTML = '';
            
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
            const container = document.getElementById('certificationsPreview');
            container.innerHTML = '';
            
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
                       class="flex-1 px-4 py-3 border border-slate-300 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition"
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
