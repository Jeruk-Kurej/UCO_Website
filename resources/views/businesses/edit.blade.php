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
                        :class="activeTab === 'basic' ? 'border-b-2 border-gray-900 text-gray-900 font-semibold' : 'text-slate-500 hover:text-gray-700'"
                        class="flex-1 px-4 sm:px-6 py-4 text-xs sm:text-sm transition-colors whitespace-nowrap">
                    Informasi Dasar
                </button>
                <button type="button" 
                        @click="activeTab = 'products'"
                        :class="activeTab === 'products' ? 'border-b-2 border-gray-900 text-gray-900 font-semibold' : 'text-slate-500 hover:text-gray-700'"
                        class="flex-1 px-4 sm:px-6 py-4 text-xs sm:text-sm transition-colors whitespace-nowrap">
                    Produk & Layanan
                </button>
                <button type="button" 
                        @click="activeTab = 'development'"
                        :class="activeTab === 'development' ? 'border-b-2 border-gray-900 text-gray-900 font-semibold' : 'text-slate-500 hover:text-gray-700'"
                        class="flex-1 px-4 sm:px-6 py-4 text-xs sm:text-sm transition-colors whitespace-nowrap">
                    Perkembangan Business
                </button>
                <button type="button" 
                        @click="activeTab = 'documents'"
                        :class="activeTab === 'documents' ? 'border-b-2 border-gray-900 text-gray-900 font-semibold' : 'text-slate-500 hover:text-gray-700'"
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
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Business <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name', $business->name) }}"
                           required
                           class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('name') border-gray-200 @enderror transition"
                           placeholder="e.g., Warung Kopi Senja">
                    @error('name')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Position --}}
                <div>
                    <label for="position" class="block text-sm font-medium text-gray-700 mb-2">
                        Posisi Anda
                    </label>
                    <input type="text" 
                           name="position" 
                           id="position" 
                           value="{{ old('position', $business->position) }}"
                           class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('position') border-gray-200 @enderror transition"
                           placeholder="e.g., CEO, Co-Founder, Marketing Manager, Staff">
                    @error('position')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Business Type & Mode --}}
                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label for="business_type_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Kategori Business <span class="text-red-500">*</span>
                        </label>
                        <select name="business_type_id" 
                                id="business_type_id"
                                required
                                class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('business_type_id') border-gray-200 @enderror transition">
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
                        <label for="business_mode" class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Offering <span class="text-red-500">*</span>
                        </label>
                        <select name="business_mode" 
                                id="business_mode"
                                required
                                class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('business_mode') border-gray-200 @enderror transition">
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
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" 
                              id="description" 
                              rows="4"
                              maxlength="1000"
                              required
                              class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('description') border-gray-200 @enderror transition"
                              placeholder="Deskripsikan business Anda...">{{ old('description', $business->description) }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Max 1000 characters.</p>
                    @error('description')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Location Fields --}}
                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                            Kota <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="city" 
                               id="city" 
                               value="{{ old('city', $business->city) }}"
                               required
                               class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('city') border-gray-200 @enderror transition"
                               placeholder="e.g., Jakarta">
                        @error('city')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="province" class="block text-sm font-medium text-gray-700 mb-2">
                            Provinsi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="province" 
                               id="province" 
                               value="{{ old('province', $business->province) }}"
                               required
                               class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('province') border-gray-200 @enderror transition"
                               placeholder="e.g., DKI Jakarta">
                        @error('province')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Full Address --}}
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                        Alamat Lengkap
                    </label>
                    <textarea name="address" 
                              id="address" 
                              rows="3"
                              class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('address') border-gray-200 @enderror transition"
                              placeholder="Alamat lengkap business...">{{ old('address', $business->address) }}</textarea>
                    @error('address')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Contact Info --}}
                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor Telepon
                        </label>
                        <input type="text" 
                               name="phone" 
                               id="phone" 
                               value="{{ old('phone', $business->phone) }}"
                               class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('phone') border-gray-200 @enderror transition"
                               placeholder="e.g., 0812-3456-7890">
                        @error('phone')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="{{ old('email', $business->email) }}"
                               class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('email') border-gray-200 @enderror transition"
                               placeholder="business@example.com">
                        @error('email')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Logo Upload via Alpine.js (Edit Mode) --}}
                <div x-data="{ 
                        currentImage: '{{ $business->logo ? storage_image_url($business->logo) : '' }}',
                        newImagePreview: null,
                        isDragging: false,
                        fileSelected(event) {
                            const file = event.target.files[0];
                            if (file) {
                                if(file.size > 10 * 1024 * 1024) {
                                    alert('Logo must not be larger than 10MB.');
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Logo Business</label>
                    
                    {{-- Before / After Preview --}}
                    <template x-if="newImagePreview">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            {{-- Current Image (Faded) --}}
                            <div x-show="currentImage">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Current Logo</p>
                                <div class="border border-gray-200 rounded-xl p-2 bg-gray-50/50 h-full flex items-center justify-center">
                                    <img :src="currentImage" class="max-w-full h-auto object-contain max-h-64 rounded-lg opacity-50 grayscale transition-all">
                                </div>
                            </div>
                            
                            {{-- New Image --}}
                            <div class="relative">
                                <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-2">New Selection</p>
                                <div class="relative w-full h-full rounded-xl border-2 border-blue-200 overflow-hidden shadow-sm bg-blue-50/50 p-2 flex items-center justify-center">
                                    <img :src="newImagePreview" class="max-w-full h-auto object-contain max-h-64 rounded-lg">
                                    <button type="button" @click="removeFile()" class="absolute top-4 right-4 p-2 bg-white text-red-500 rounded-xl shadow-md hover:bg-red-50 transition-colors z-10 focus:outline-none">
                                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>

                    {{-- Current Image Only --}}
                    <div x-show="!newImagePreview && currentImage" class="mb-4">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Current Logo</p>
                        <img :src="currentImage" alt="Current Logo" class="max-w-xs h-auto rounded-xl shadow-sm border border-gray-200">
                    </div>

                    {{-- Upload Area --}}
                    <div class="relative group mt-2" 
                         @dragover.prevent="isDragging = true" 
                         @dragleave.prevent="isDragging = false" 
                         @drop.prevent="handleDrop($event)">
                        
                        <input type="file" name="logo" id="logo" accept="image/*" class="hidden" x-ref="fileInput" @change="fileSelected">
                        
                        <label for="logo" 
                               :class="isDragging ? 'border-soft-gray-900 bg-soft-gray-50' : 'border-gray-200 bg-white hover:border-soft-gray-400 hover:bg-gray-50'" 
                               class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed rounded-xl cursor-pointer transition-all duration-200">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <p class="mb-1 text-sm font-medium text-gray-700">Click to upload new logo or drag and drop</p>
                                <p class="text-xs text-gray-500">PNG, JPG, SVG up to 10MB</p>
                            </div>
                        </label>
                    </div>
                    @error('logo')
                        <p class="mb-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <x-image-preview
                        input-id="logo"
                        preview-id="edit-logo-preview"
                        :max-size="2"
                        shape="square"
                        :side-by-side="true"
                        :current-image="$currentLogoUrl"
                        current-label="Current"
                        new-label="New Logo"
                        hint="PNG, JPG, SVG — max 2MB"
                    />
                </div>

                {{-- Website --}}
                <div>
                    <label for="website" class="block text-sm font-medium text-gray-700 mb-2">
                        Website
                    </label>
                    <input type="url" 
                           name="website" 
                           id="website" 
                           value="{{ old('website', $business->website) }}"
                           class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('website') border-gray-200 @enderror transition"
                           placeholder="https://example.com">
                    @error('website')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Social Media --}}
                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label for="instagram_handle" class="block text-sm font-medium text-gray-700 mb-2">
                            Instagram Handle
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-3.5 text-slate-400">@</span>
                            <input type="text" 
                                   name="instagram_handle" 
                                   id="instagram_handle" 
                                   value="{{ old('instagram_handle', $business->instagram_handle) }}"
                                   class="block w-full pl-8 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('instagram_handle') border-gray-200 @enderror transition"
                                   placeholder="username">
                        </div>
                        @error('instagram_handle')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="whatsapp_number" class="block text-sm font-medium text-gray-700 mb-2">
                            WhatsApp Number
                        </label>
                        <input type="text" 
                               name="whatsapp_number" 
                               id="whatsapp_number" 
                               value="{{ old('whatsapp_number', $business->whatsapp_number) }}"
                               class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('whatsapp_number') border-gray-200 @enderror transition"
                               placeholder="628123456789">
                        @error('whatsapp_number')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                @if(auth()->user()->role === 'admin')
                <div x-data="{
                        search: '',
                        open: false,
                        selectedId: '{{ old('user_id', $business->user_id) }}',
                        selectedText: 'Select Owner',
                        users: [
                            @foreach($users as $user)
                            { id: '{{ $user->id }}', name: '{{ addslashes($user->name) }} ({{ addslashes($user->email) }})' },
                            @endforeach
                        ],
                        get filteredUsers() {
                            if (this.search === '') return this.users;
                            return this.users.filter(u => u.name.toLowerCase().includes(this.search.toLowerCase()));
                        },
                        init() {
                            let match = this.users.find(u => u.id == this.selectedId);
                            if (match) this.selectedText = match.name;
                        },
                        select(id, name) {
                            this.selectedId = id;
                            this.selectedText = name;
                            this.open = false;
                            this.search = '';
                        }
                    }" 
                    @click.away="open = false"
                    class="relative">
                    
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Owner (Admin Only)
                    </label>
                    <input type="hidden" name="user_id" :value="selectedId">
                    
                    <button type="button" @click="open = !open" 
                            class="flex w-full items-center justify-between px-4 py-3 border rounded-xl focus:ring-soft-gray-900 focus:border-soft-gray-900 transition bg-white text-left @error('user_id') border-red-500 @else border-gray-200 hover:border-soft-gray-400 @enderror">
                        <span x-text="selectedText" :class="selectedId ? 'text-gray-900' : 'text-gray-500'" class="block truncate"></span>
                        <svg class="h-5 w-5 text-gray-400 shrink-0 transform transition-transform duration-200" :class="open ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         style="display: none;"
                         class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-xl py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm">
                        <div class="px-3 py-2 border-b border-gray-100 flex items-center sticky top-0 bg-white z-20">
                            <i class="bi bi-search text-gray-400 mr-2 shrink-0"></i>
                            <input type="text" x-model="search" @click.stop class="w-full border-0 focus:ring-0 p-0 text-gray-900 sm:text-sm" placeholder="Search owner...">
                        </div>
                        
                        <ul class="pt-1">
                            <li x-show="filteredUsers.length === 0" class="text-gray-500 px-4 py-2 cursor-default text-sm">No users found</li>
                            <template x-for="user in filteredUsers" :key="user.id">
                                <li @click="select(user.id, user.name)"
                                    class="text-gray-900 cursor-pointer select-none relative py-2.5 pl-4 pr-9 hover:bg-gray-100 transition-colors"
                                    :class="selectedId == user.id ? 'bg-gray-50 font-medium' : ''">
                                    <span x-text="user.name" class="block truncate"></span>
                                    
                                    <span x-show="selectedId == user.id" 
                                          class="text-gray-900 absolute inset-y-0 right-0 flex items-center pr-4">
                                        <i class="bi bi-check-lg text-lg"></i>
                                    </span>
                                </li>
                            </template>
                        </ul>
                    </div>

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
                    <label for="product_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Produk/Layanan Utama
                    </label>
                    <input type="text" 
                           name="product_name" 
                           id="product_name" 
                           value="{{ old('product_name', $business->product_name) }}"
                           class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('product_name') border-gray-200 @enderror transition"
                           placeholder="e.g., Kopi Arabica Premium">
                    @error('product_name')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Product Description --}}
                <div>
                    <label for="product_description" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi Produk/Layanan
                    </label>
                    <textarea name="product_description" 
                              id="product_description" 
                              rows="4"
                              class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('product_description') border-gray-200 @enderror transition"
                              placeholder="Jelaskan produk/layanan yang Anda tawarkan...">{{ old('product_description', $business->product_description) }}</textarea>
                    @error('product_description')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Unique Value Proposition --}}
                <div>
                    <label for="unique_value_proposition" class="block text-sm font-medium text-gray-700 mb-2">
                        Keunikan/Nilai Lebih Produk
                    </label>
                    <textarea name="unique_value_proposition" 
                              id="unique_value_proposition" 
                              rows="3"
                              class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('unique_value_proposition') border-gray-200 @enderror transition"
                              placeholder="Apa yang membuat produk Anda berbeda dari kompetitor?">{{ old('unique_value_proposition', $business->unique_value_proposition) }}</textarea>
                    @error('unique_value_proposition')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Target Market --}}
                <div>
                    <label for="target_market" class="block text-sm font-medium text-gray-700 mb-2">
                        Target Market
                    </label>
                    <input type="text" 
                           name="target_market" 
                           id="target_market" 
                           value="{{ old('target_market', $business->target_market) }}"
                           class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('target_market') border-gray-200 @enderror transition"
                           placeholder="e.g., Milenial, Profesional muda, Pecinta kopi">
                    @error('target_market')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Customer Base --}}
                <div>
                    <label for="customer_base_size" class="block text-sm font-medium text-gray-700 mb-2">
                        Jumlah Customer Aktif
                    </label>
                    <input type="number" 
                           name="customer_base_size" 
                           id="customer_base_size" 
                           value="{{ old('customer_base_size', $business->customer_base_size) }}"
                           min="0"
                           class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('customer_base_size') border-gray-200 @enderror transition"
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
                        <label for="establishment_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Berdiri
                        </label>
                        <input type="date" 
                               name="establishment_date" 
                               id="establishment_date" 
                               value="{{ old('establishment_date', $business->establishment_date) }}"
                               class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('establishment_date') border-gray-200 @enderror transition">
                        @error('establishment_date')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="operational_status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status Operasional
                        </label>
                        <select name="operational_status" 
                                id="operational_status"
                                class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('operational_status') border-gray-200 @enderror transition">
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
                        <label for="employee_count" class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah Karyawan
                        </label>
                        <input type="number" 
                               name="employee_count" 
                               id="employee_count" 
                               value="{{ old('employee_count', $business->employee_count) }}"
                               min="0"
                               class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('employee_count') border-gray-200 @enderror transition"
                               placeholder="e.g., 5">
                        @error('employee_count')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="revenue_range" class="block text-sm font-medium text-gray-700 mb-2">
                            Range Pendapatan (per bulan)
                        </label>
                        <select name="revenue_range" 
                                id="revenue_range"
                                class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('revenue_range') border-gray-200 @enderror transition">
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tantangan Business
                    </label>
                    <div id="challengesContainer" class="space-y-3">
                        @if($business->business_challenges && count($business->business_challenges) > 0)
                            @foreach($business->business_challenges as $challenge)
                                <div class="flex gap-2">
                                    <input type="text" 
                                           name="business_challenges[]" 
                                           value="{{ $challenge }}"
                                           class="flex-1 px-4 py-3 border border-gray-200 rounded-xl focus:ring-soft-gray-900 focus:border-soft-gray-900 transition"
                                           placeholder="e.g., Keterbatasan modal">
                                    <button type="button" 
                                            onclick="this.parentElement.remove()"
                                            class="px-4 py-2 bg-red-100 text-red-700 rounded-xl hover:bg-red-200 transition">
                                        ✖
                                    </button>
                                </div>
                            @endforeach
                        @else
                            <div class="flex gap-2">
                                <input type="text" 
                                       name="business_challenges[]" 
                                       class="flex-1 px-4 py-3 border border-gray-200 rounded-xl focus:ring-soft-gray-900 focus:border-soft-gray-900 transition"
                                       placeholder="e.g., Keterbatasan modal">
                            </div>
                        @endif
                    </div>
                    <button type="button" 
                            onclick="addChallenge()"
                            class="mt-3 px-4 py-2 bg-slate-100 text-gray-700 rounded-xl hover:bg-slate-200 transition text-sm font-medium">
                        + Tambah Tantangan
                    </button>
                    @error('business_challenges')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- TAB 4: DOCUMENTS & CERTIFICATIONS --}}
            <div x-show="activeTab === 'documents'" class="bg-white border-x border-b border-slate-200 rounded-b-xl shadow-sm p-8 space-y-6">
                {{-- Single Legal Document PDF Upload via Alpine.js --}}
                <div x-data="{ 
                        fileName: null,
                        fileSize: null,
                        isDragging: false,
                        existingDoc: {{ $business->legal_document_path ? 'true' : 'false' }},
                        willRemove: false,
                        fileSelected(event) {
                            const file = event.target.files[0];
                            if (file) {
                                if(file.type !== 'application/pdf') {
                                    alert('Only PDF files are allowed.');
                                    this.removeFile();
                                    return;
                                }
                                if(file.size > 5 * 1024 * 1024) {
                                    alert('Document must not be larger than 5MB.');
                                    this.removeFile();
                                    return;
                                }
                                this.fileName = file.name;
                                this.fileSize = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
                                this.willRemove = false;
                            }
                        },
                        removeFile() {
                            this.fileName = null;
                            this.fileSize = null;
                            this.$refs.fileInput.value = '';
                            if (this.existingDoc) {
                                this.willRemove = true;
                            }
                        },
                        cancelRemoval() {
                            this.willRemove = false;
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dokumen Legal (PDF, Max 5MB)</label>
                    <input type="hidden" name="remove_legal_document" :value="willRemove ? '1' : '0'">
                    
                    <div class="relative group mt-2" 
                         @dragover.prevent="isDragging = true" 
                         @dragleave.prevent="isDragging = false" 
                         @drop.prevent="handleDrop($event)">
                        
                        <input type="file" name="legal_document_path" id="legal_document_path" accept=".pdf" class="hidden" x-ref="fileInput" @change="fileSelected">
                        
                        {{-- Keadaan 1: Ada existing doc, tidak/belum dihapus, tidak pilih file baru --}}
                        <template x-if="existingDoc && !willRemove && !fileName">
                            <div class="flex items-center justify-between p-4 bg-gray-50 border border-gray-200 rounded-xl">
                                <div class="flex items-center space-x-3 overflow-hidden">
                                    <div class="p-2 bg-red-100 text-red-600 rounded-lg shrink-0">
                                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8zm1-13h-2v6h6v-2h-4V7z"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            <a href="{{ $business->legal_document_path ? Storage::url($business->legal_document_path) : '#' }}" target="_blank" class="text-blue-600 hover:underline">
                                                Current Document (PDF)
                                            </a>
                                        </p>
                                    </div>
                                </div>
                                <div class="flex space-x-2 shrink-0">
                                    <label for="legal_document_path" class="p-2 text-gray-500 hover:text-soft-gray-900 bg-white border border-gray-200 hover:bg-gray-50 rounded-lg transition-colors focus:outline-none cursor-pointer text-sm font-medium flex items-center">
                                        Ganti
                                    </label>
                                    <button type="button" @click="removeFile()" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors focus:outline-none">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </div>
                        </template>

                        {{-- Keadaan 2: Tidak ada file awal (atau dihapus), dan tidak pilih file baru --}}
                        <template x-if="(!existingDoc || willRemove) && !fileName">
                            <label for="legal_document_path" 
                                   :class="isDragging ? 'border-soft-gray-900 bg-soft-gray-50' : 'border-gray-200 bg-white hover:border-soft-gray-400 hover:bg-gray-50'" 
                                   class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed rounded-xl cursor-pointer transition-all duration-200">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <div class="p-3 bg-white rounded-full shadow-sm border border-gray-100 mb-2 group-hover:scale-110 transition-transform duration-200">
                                        <svg class="mx-auto h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm text-gray-600 font-medium">Klik untuk upload data legal / seret file</p>
                                    <p class="text-xs text-gray-400 mt-1">Hanya PDF hingga 5MB</p>
                                </div>
                                <template x-if="existingDoc && willRemove">
                                    <button type="button" @click.prevent="cancelRemoval()" class="absolute bottom-2 right-2 text-xs text-blue-600 hover:text-blue-800 bg-white px-2 py-1 rounded shadow-sm border border-gray-100">Batalkan penghapusan</button>
                                </template>
                            </label>
                        </template>

                        {{-- Keadaan 3: Memilih file baru --}}
                        <template x-if="fileName">
                            <div class="flex items-center justify-between p-4 bg-gray-50 border border-gray-200 rounded-xl relative">
                                <span class="absolute -top-2.5 left-4 px-2 bg-blue-100 text-blue-700 text-[10px] font-bold uppercase rounded shadow-sm">Baru</span>
                                <div class="flex items-center space-x-3 overflow-hidden mt-1">
                                    <div class="p-2 bg-red-100 text-red-600 rounded-lg shrink-0">
                                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8zm1-13h-2v6h6v-2h-4V7z"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate" x-text="fileName"></p>
                                        <p class="text-xs text-gray-500" x-text="fileSize"></p>
                                    </div>
                                </div>
                                <button type="button" @click="removeFile()" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors focus:outline-none shrink-0" title="Remove file">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>
                        </template>
                    </div>
                    @error('legal_document_path')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Product Certifications Upload via Alpine.js --}}
                <div x-data="{ 
                        fileName: null,
                        fileSize: null,
                        isDragging: false,
                        existingDoc: {{ $business->certification_path ? 'true' : 'false' }},
                        willRemove: false,
                        fileSelected(event) {
                            const file = event.target.files[0];
                            if (file) {
                                if(file.type !== 'application/pdf') {
                                    alert('Only PDF files are allowed.');
                                    this.removeFile();
                                    return;
                                }
                                if(file.size > 5 * 1024 * 1024) {
                                    alert('Document must not be larger than 5MB.');
                                    this.removeFile();
                                    return;
                                }
                                this.fileName = file.name;
                                this.fileSize = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
                                this.willRemove = false;
                            }
                        },
                        removeFile() {
                            this.fileName = null;
                            this.fileSize = null;
                            this.$refs.fileInput.value = '';
                            if (this.existingDoc) {
                                this.willRemove = true;
                            }
                        },
                        cancelRemoval() {
                            this.willRemove = false;
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sertifikasi Produk (PDF, Max 5MB)</label>
                    <input type="hidden" name="remove_certification" :value="willRemove ? '1' : '0'">
                    
                    <div class="relative group mt-2" 
                         @dragover.prevent="isDragging = true" 
                         @dragleave.prevent="isDragging = false" 
                         @drop.prevent="handleDrop($event)">
                        
                        <input type="file" name="certification_path" id="certification_path" accept=".pdf" class="hidden" x-ref="fileInput" @change="fileSelected">
                        
                        {{-- Keadaan 1: Ada existing doc, tidak/belum dihapus, tidak pilih file baru --}}
                        <template x-if="existingDoc && !willRemove && !fileName">
                            <div class="flex items-center justify-between p-4 bg-gray-50 border border-gray-200 rounded-xl">
                                <div class="flex items-center space-x-3 overflow-hidden">
                                    <div class="p-2 bg-blue-100 text-blue-600 rounded-lg shrink-0">
                                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8zm1-13h-2v6h6v-2h-4V7z"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            <a href="{{ $business->certification_path ? Storage::url($business->certification_path) : '#' }}" target="_blank" class="text-blue-600 hover:underline">
                                                Current Certification (PDF)
                                            </a>
                                        </p>
                                    </div>
                                </div>
                                <div class="flex space-x-2 shrink-0">
                                    <label for="certification_path" class="p-2 text-gray-500 hover:text-soft-gray-900 bg-white border border-gray-200 hover:bg-gray-50 rounded-lg transition-colors focus:outline-none cursor-pointer text-sm font-medium flex items-center">
                                        Ganti
                                    </label>
                                    <button type="button" @click="removeFile()" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors focus:outline-none">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </div>
                        </template>

                        {{-- Keadaan 2: Tidak ada file awal (atau dihapus), dan tidak pilih file baru --}}
                        <template x-if="(!existingDoc || willRemove) && !fileName">
                            <label for="certification_path" 
                                   :class="isDragging ? 'border-soft-gray-900 bg-soft-gray-50' : 'border-gray-200 bg-white hover:border-soft-gray-400 hover:bg-gray-50'" 
                                   class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed rounded-xl cursor-pointer transition-all duration-200">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <div class="p-3 bg-white rounded-full shadow-sm border border-gray-100 mb-2 group-hover:scale-110 transition-transform duration-200">
                                        <svg class="mx-auto h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm text-gray-600 font-medium">Klik untuk upload data sertifikasi / seret file</p>
                                    <p class="text-xs text-gray-400 mt-1">Hanya PDF hingga 5MB</p>
                                </div>
                                <template x-if="existingDoc && willRemove">
                                    <button type="button" @click.prevent="cancelRemoval()" class="absolute bottom-2 right-2 text-xs text-blue-600 hover:text-blue-800 bg-white px-2 py-1 rounded shadow-sm border border-gray-100">Batalkan penghapusan</button>
                                </template>
                            </label>
                        </template>

                        {{-- Keadaan 3: Memilih file baru --}}
                        <template x-if="fileName">
                            <div class="flex items-center justify-between p-4 bg-gray-50 border border-gray-200 rounded-xl relative">
                                <span class="absolute -top-2.5 left-4 px-2 bg-blue-100 text-blue-700 text-[10px] font-bold uppercase rounded shadow-sm">Baru</span>
                                <div class="flex items-center space-x-3 overflow-hidden mt-1">
                                    <div class="p-2 bg-blue-100 text-blue-600 rounded-lg shrink-0">
                                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8zm1-13h-2v6h6v-2h-4V7z"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate" x-text="fileName"></p>
                                        <p class="text-xs text-gray-500" x-text="fileSize"></p>
                                    </div>
                                </div>
                                <button type="button" @click="removeFile()" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors focus:outline-none shrink-0" title="Remove file">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>
                        </template>
                    </div>
                    @error('certification_path')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Submit Buttons --}}
            <div class="flex items-center justify-between pt-6 border-t border-gray-200 mt-6">
    <a href="{{ route('businesses.show', $business) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 hover:text-gray-900 rounded-xl transition duration-150">
    Cancel
</a>
    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-soft-gray-900 hover:bg-soft-gray-800 text-white font-semibold rounded-xl shadow-md transition duration-200">
                    Update Business
                </button>
</div>
        </form>

        {{-- Delete Business Form --}}
        <form method="POST" action="{{ route('businesses.destroy', $business) }}" class="mt-8" onsubmit="return confirm('Are you sure you want to delete this business? This action cannot be undone.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-xl shadow-md transition duration-200">
                Delete Business
            </button>
        </form>
    </div>

    {{-- JavaScript for File Previews and Dynamic Fields --}}
    <script>
        // Logo preview
        document.addEventListener('DOMContentLoaded', () => ucoInitImagePreview('logo', 'edit-logo-preview', 2, true));

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
                div.className = 'border border-slate-200 rounded-xl p-3 bg-slate-50';
                
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
                div.className = 'border border-slate-200 rounded-xl p-3 bg-slate-50';
                
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
                       class="flex-1 px-4 py-3 border border-gray-200 rounded-xl focus:ring-soft-gray-900 focus:border-soft-gray-900 transition"
                       placeholder="Masukkan tantangan">
                <button type="button" 
                        onclick="this.parentElement.remove()"
                        class="px-4 py-2 bg-red-100 text-red-700 rounded-xl hover:bg-red-200 transition">
                    ✖
                </button>
            `;
            container.appendChild(newChallenge);
        }
    </script>
</x-app-layout>
