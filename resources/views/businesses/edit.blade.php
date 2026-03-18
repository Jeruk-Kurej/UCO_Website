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

        <form method="POST" action="{{ route('businesses.update', $business) }}" enctype="multipart/form-data" id="businessEditForm">
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
                    
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4 mt-2 mb-4">
                        <!-- Photo Previews Area -->
                        <div class="flex items-center gap-3 p-3 bg-gray-50/80 border border-gray-200/60 rounded-xl" x-show="currentImage || newImagePreview">
                            <!-- Current Photo -->
                            <div class="flex flex-col items-center gap-1.5" x-show="currentImage">
                                <span class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Current</span>
                                <div class="w-20 h-20 rounded-lg bg-white border border-gray-200 flex items-center justify-center overflow-hidden shadow-sm p-1.5">
                                    <img :src="currentImage" class="max-w-full max-h-full object-contain">
                                </div>
                            </div>

                            <!-- Arrow icon -->
                            <template x-if="currentImage && newImagePreview">
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
                            <label for="logo" 
                                   :class="isDragging ? 'bg-blue-50 border-blue-400' : 'bg-white hover:bg-gray-50 border-gray-300'"
                                   class="cursor-pointer inline-flex items-center gap-2 px-5 py-2.5 border rounded-xl text-sm font-semibold text-gray-700 transition-all shadow-sm focus-within:ring-2 focus-within:ring-soft-gray-900 focus-within:border-soft-gray-900">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                <span x-text="newImagePreview ? 'Change Selection' : 'Upload New Logo'"></span>
                                <input type="file" name="logo" id="logo" accept="image/*" class="sr-only" x-ref="fileInput" @change="fileSelected">
                            </label>
                            <div class="text-[11px] text-gray-500 font-medium">
                                <p>Click to upload or drag & drop.</p>
                                <p>PNG, JPG, SVG allowed (Max 10MB).</p>
                            </div>
                        </div>
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
                        
                        <div class="flex flex-col sm:flex-row gap-6 mt-2 mb-4">
                            <div class="flex flex-col sm:flex-row items-center gap-3 p-3 bg-gray-50/80 border border-gray-200/60 rounded-xl w-full sm:w-auto" x-show="existingDoc || fileName">
                                <!-- Current Document -->
                                <template x-if="existingDoc && !willRemove">
                                    <div class="relative group flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-lg shadow-sm min-w-[200px] max-w-[250px] transition-all">
                                        <div class="p-2 bg-gray-50 rounded-md shrink-0">
                                            <svg class="h-6 w-6 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8zm1-13h-2v6h6v-2h-4V7z"/></svg>
                                        </div>
                                        <div class="flex flex-col min-w-0 pr-4">
                                            <span class="text-[10px] font-bold tracking-wider text-gray-400 uppercase mb-0.5">Current Document</span>
                                            <a href="{{ $business->legal_document_path ? Storage::url($business->legal_document_path) : '#' }}" target="_blank" class="text-xs font-semibold text-blue-600 hover:text-blue-700 truncate hover:underline">View PDF</a>
                                        </div>
                                        <button type="button" @click="willRemove = true" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 bg-white hover:bg-red-50 p-1.5 rounded-md transition-colors focus:outline-none opacity-0 group-hover:opacity-100" title="Remove current document">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                </template>

                                <template x-if="existingDoc && willRemove && !fileName">
                                    <div class="flex items-center gap-3 p-3 bg-red-50/50 border border-red-200 border-dashed rounded-lg min-w-[200px] max-w-[250px]">
                                        <div class="flex flex-col w-full">
                                            <span class="text-[10px] font-bold tracking-wider text-red-400 uppercase mb-0.5">Removed</span>
                                            <div class="flex items-center justify-between mt-1">
                                                <span class="text-[11px] text-red-600 font-medium">To be deleted</span>
                                                <button type="button" @click="willRemove = false" class="text-[10px] font-semibold text-gray-600 hover:text-gray-800 bg-white border border-gray-200 px-2.5 py-1 rounded-md shadow-sm transition-colors">Undo</button>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <!-- Arrow icon -->
                                <template x-if="existingDoc && !willRemove && fileName">
                                    <div class="flex items-center justify-center px-1">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                        </svg>
                                    </div>
                                </template>

                                <!-- New Document Preview -->
                                <template x-if="fileName">
                                    <div class="relative group flex items-center gap-3 p-3 bg-blue-50/50 border border-blue-200 rounded-lg shadow-sm min-w-[200px] max-w-[250px] transition-all">
                                        <div class="p-2 bg-white rounded-md shadow-sm shrink-0">
                                            <svg class="h-6 w-6 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8zm1-13h-2v6h6v-2h-4V7z"/></svg>
                                        </div>
                                        <div class="flex flex-col min-w-0 pr-6">
                                            <span class="text-[10px] font-bold tracking-wider text-blue-500 uppercase mb-0.5">New Selection</span>
                                            <span class="text-xs font-semibold text-gray-800 truncate" x-text="fileName"></span>
                                            <span class="text-[10px] text-gray-500 mt-0.5" x-text="fileSize"></span>
                                        </div>
                                        
                                        <!-- Cancel/Remove Button -->
                                        <button type="button" @click="removeFile()" 
                                                class="absolute top-2 right-2 text-gray-400 hover:text-red-500 bg-white hover:bg-red-50 p-1.5 rounded-md shadow-sm transition-colors focus:outline-none" 
                                                title="Cancel new selection">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>

                            <!-- Upload Actions -->
                            <div class="flex-1 flex flex-col items-start gap-2">
                                <label for="legal_document_path" 
                                       :class="isDragging ? 'bg-blue-50 border-blue-400' : 'bg-white hover:bg-gray-50 border-gray-300'"
                                       class="cursor-pointer inline-flex items-center gap-2 px-5 py-2.5 border rounded-xl text-sm font-semibold text-gray-700 transition-all shadow-sm focus-within:ring-2 focus-within:ring-soft-gray-900 focus-within:border-soft-gray-900">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                    <span x-text="fileName ? 'Change File' : 'Upload Document'"></span>
                                </label>
                                <div class="text-[11px] text-gray-500 font-medium mt-1">
                                    <p>Click to select or drag & drop.</p>
                                    <p>PDF files only (Max 5MB).</p>
                                </div>
                            </div>
                        </div>
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
                        
                        <div class="flex flex-col sm:flex-row gap-6 mt-2 mb-4">
                            <div class="flex flex-col sm:flex-row items-center gap-3 p-3 bg-gray-50/80 border border-gray-200/60 rounded-xl w-full sm:w-auto" x-show="existingDoc || fileName">
                                <!-- Current Document -->
                                <template x-if="existingDoc && !willRemove">
                                    <div class="relative group flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-lg shadow-sm min-w-[200px] max-w-[250px] transition-all">
                                        <div class="p-2 bg-gray-50 rounded-md shrink-0">
                                            <svg class="h-6 w-6 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8zm1-13h-2v6h6v-2h-4V7z"/></svg>
                                        </div>
                                        <div class="flex flex-col min-w-0 pr-4">
                                            <span class="text-[10px] font-bold tracking-wider text-gray-400 uppercase mb-0.5">Current Certification</span>
                                            <a href="{{ $business->certification_path ? Storage::url($business->certification_path) : '#' }}" target="_blank" class="text-xs font-semibold text-blue-600 hover:text-blue-700 truncate hover:underline">View PDF</a>
                                        </div>
                                        <button type="button" @click="willRemove = true" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 bg-white hover:bg-red-50 p-1.5 rounded-md transition-colors focus:outline-none opacity-0 group-hover:opacity-100" title="Remove current certification">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                </template>

                                <template x-if="existingDoc && willRemove && !fileName">
                                    <div class="flex items-center gap-3 p-3 bg-red-50/50 border border-red-200 border-dashed rounded-lg min-w-[200px] max-w-[250px]">
                                        <div class="flex flex-col w-full">
                                            <span class="text-[10px] font-bold tracking-wider text-red-400 uppercase mb-0.5">Removed</span>
                                            <div class="flex items-center justify-between mt-1">
                                                <span class="text-[11px] text-red-600 font-medium">To be deleted</span>
                                                <button type="button" @click="willRemove = false" class="text-[10px] font-semibold text-gray-600 hover:text-gray-800 bg-white border border-gray-200 px-2.5 py-1 rounded-md shadow-sm transition-colors">Undo</button>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <!-- Arrow icon -->
                                <template x-if="existingDoc && !willRemove && fileName">
                                    <div class="flex items-center justify-center px-1">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                        </svg>
                                    </div>
                                </template>

                                <!-- New Document Preview -->
                                <template x-if="fileName">
                                    <div class="relative group flex items-center gap-3 p-3 bg-blue-50/50 border border-blue-200 rounded-lg shadow-sm min-w-[200px] max-w-[250px] transition-all">
                                        <div class="p-2 bg-white rounded-md shadow-sm shrink-0">
                                            <svg class="h-6 w-6 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8zm1-13h-2v6h6v-2h-4V7z"/></svg>
                                        </div>
                                        <div class="flex flex-col min-w-0 pr-6">
                                            <span class="text-[10px] font-bold tracking-wider text-blue-500 uppercase mb-0.5">New Selection</span>
                                            <span class="text-xs font-semibold text-gray-800 truncate" x-text="fileName"></span>
                                            <span class="text-[10px] text-gray-500 mt-0.5" x-text="fileSize"></span>
                                        </div>
                                        
                                        <!-- Cancel/Remove Button -->
                                        <button type="button" @click="removeFile()" 
                                                class="absolute top-2 right-2 text-gray-400 hover:text-red-500 bg-white hover:bg-red-50 p-1.5 rounded-md shadow-sm transition-colors focus:outline-none" 
                                                title="Cancel new selection">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>

                            <!-- Upload Actions -->
                            <div class="flex-1 flex flex-col items-start gap-2">
                                <label for="certification_path" 
                                       :class="isDragging ? 'bg-blue-50 border-blue-400' : 'bg-white hover:bg-gray-50 border-gray-300'"
                                       class="cursor-pointer inline-flex items-center gap-2 px-5 py-2.5 border rounded-xl text-sm font-semibold text-gray-700 transition-all shadow-sm focus-within:ring-2 focus-within:ring-soft-gray-900 focus-within:border-soft-gray-900">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                    <span x-text="fileName ? 'Change File' : 'Upload Certification'"></span>
                                </label>
                                <div class="text-[11px] text-gray-500 font-medium mt-1">
                                    <p>Click to select or drag & drop.</p>
                                    <p>PDF files only (Max 5MB).</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @error('certification_path')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Validation Error Toast (hidden by default) --}}
            <div id="validationToast" class="hidden fixed top-6 right-6 z-[200] max-w-sm w-full bg-red-50 border border-red-200 rounded-xl shadow-xl p-4 transform transition-all duration-300 translate-y-[-20px] opacity-0">
                <div class="flex items-start gap-3">
                    <div class="p-1.5 bg-red-100 rounded-lg shrink-0">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-red-800">Ada field yang belum diisi</p>
                        <p id="validationToastMsg" class="text-xs text-red-600 mt-0.5">Silakan lengkapi field yang bertanda merah.</p>
                    </div>
                    <button type="button" onclick="hideValidationToast()" class="text-red-400 hover:text-red-600 p-1 rounded-md transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
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

    {{-- JavaScript for Dynamic Fields and Client-Side Validation --}}
    <script>
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

        // Client-side form validation
        let toastTimeout = null;

        function showValidationToast(msg) {
            const toast = document.getElementById('validationToast');
            const toastMsg = document.getElementById('validationToastMsg');
            if (msg) toastMsg.textContent = msg;
            toast.classList.remove('hidden');
            requestAnimationFrame(() => {
                toast.classList.remove('translate-y-[-20px]', 'opacity-0');
                toast.classList.add('translate-y-0', 'opacity-100');
            });
            if (toastTimeout) clearTimeout(toastTimeout);
            toastTimeout = setTimeout(() => hideValidationToast(), 6000);
        }

        function hideValidationToast() {
            const toast = document.getElementById('validationToast');
            toast.classList.remove('translate-y-0', 'opacity-100');
            toast.classList.add('translate-y-[-20px]', 'opacity-0');
            setTimeout(() => toast.classList.add('hidden'), 300);
        }

        document.getElementById('businessEditForm').addEventListener('submit', function(e) {
            // Remove previous error styling
            this.querySelectorAll('.validation-error-border').forEach(el => {
                el.classList.remove('validation-error-border', 'border-red-400', 'ring-2', 'ring-red-100');
            });
            this.querySelectorAll('.validation-error-msg').forEach(el => el.remove());

            const requiredFields = this.querySelectorAll('[required]');
            let firstInvalid = null;
            const emptyLabels = [];

            requiredFields.forEach(field => {
                if (field.type === 'file') return; // skip file inputs
                const val = (field.value || '').trim();
                if (!val || (field.tagName === 'SELECT' && val === '')) {
                    field.classList.add('validation-error-border', 'border-red-400', 'ring-2', 'ring-red-100');
                    
                    // Find label text
                    const wrapper = field.closest('div');
                    const label = wrapper ? wrapper.querySelector('label') : null;
                    const labelText = label ? label.textContent.replace('*', '').trim() : field.name;
                    emptyLabels.push(labelText);

                    // Add inline error message
                    if (wrapper && !wrapper.querySelector('.validation-error-msg')) {
                        const errMsg = document.createElement('p');
                        errMsg.className = 'validation-error-msg mt-1.5 text-sm text-red-600 flex items-center gap-1.5';
                        errMsg.innerHTML = '<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg> <span>' + labelText + ' wajib diisi</span>';
                        field.insertAdjacentElement('afterend', errMsg);
                    }

                    if (!firstInvalid) firstInvalid = field;

                    // Remove error styling on input
                    field.addEventListener('input', function handler() {
                        this.classList.remove('validation-error-border', 'border-red-400', 'ring-2', 'ring-red-100');
                        const nextErr = this.parentElement.querySelector('.validation-error-msg') || (this.nextElementSibling && this.nextElementSibling.classList.contains('validation-error-msg') ? this.nextElementSibling : null);
                        if (nextErr) nextErr.remove();
                        this.removeEventListener('input', handler);
                    }, { once: true });

                    field.addEventListener('change', function handler() {
                        this.classList.remove('validation-error-border', 'border-red-400', 'ring-2', 'ring-red-100');
                        const nextErr = this.parentElement.querySelector('.validation-error-msg') || (this.nextElementSibling && this.nextElementSibling.classList.contains('validation-error-msg') ? this.nextElementSibling : null);
                        if (nextErr) nextErr.remove();
                        this.removeEventListener('change', handler);
                    }, { once: true });
                }
            });

            if (firstInvalid) {
                e.preventDefault();

                // Find which tab the field belongs to and switch to it
                const tabPanel = firstInvalid.closest('[x-show]');
                let tabSwitched = false;
                if (tabPanel) {
                    const xShow = tabPanel.getAttribute('x-show');
                    const match = xShow.match(/activeTab\s*===\s*'([\w]+)'/);
                    if (match) {
                        const targetTab = match[1];
                        // Alpine v3: find the root x-data element and use Alpine.$data()
                        const rootEl = document.querySelector('[x-data*="activeTab"]');
                        if (rootEl && window.Alpine) {
                            Alpine.$data(rootEl).activeTab = targetTab;
                            tabSwitched = true;
                        }
                    }
                }

                // Wait for tab to become visible before scrolling
                const scrollDelay = tabSwitched ? 350 : 100;
                setTimeout(() => {
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstInvalid.focus();
                }, scrollDelay);

                const count = emptyLabels.length;
                showValidationToast('Ada ' + count + ' field wajib yang belum diisi: ' + emptyLabels.slice(0, 3).join(', ') + (count > 3 ? '...' : ''));
            }
        });
    </script>
</x-app-layout>
