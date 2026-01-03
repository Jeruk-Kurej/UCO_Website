<x-app-layout>
    {{-- ======================================== PAGE HEADER ======================================== --}}
    <div class="max-w-5xl mx-auto py-6 sm:py-12 px-4">
        <div class="bg-gradient-to-br from-slate-50 to-blue-50 border border-slate-200 rounded-xl shadow-sm px-4 sm:px-8 py-6 sm:py-10 mb-6 sm:mb-8">
            <div class="flex items-center gap-3 sm:gap-4">
                <a href="{{ route('businesses.show', $business) }}" 
                   class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-white border border-slate-300 hover:border-slate-400 text-slate-600 hover:text-slate-900 transition shadow-sm flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div class="flex-1 min-w-0">
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 truncate">Edit Business</h1>
                    <p class="text-xs sm:text-base text-slate-600 mt-1 sm:mt-2 truncate">{{ $business->name }}</p>
                </div>
            </div>
        </div>

        {{-- ======================================== FORM ======================================== --}}
        <form method="POST" action="{{ route('businesses.update', $business) }}" enctype="multipart/form-data" class="space-y-4 sm:space-y-6">
            @csrf
            @method('PUT')

            {{-- ======================================== BASIC INFORMATION ======================================== --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-l-4 border-blue-400 px-4 sm:px-6 py-3 sm:py-4">
                    <h3 class="text-lg sm:text-xl font-bold text-slate-700">📋 Informasi Dasar</h3>
                </div>

                <div class="p-4 sm:p-6 space-y-4 sm:space-y-5">
                    <div>
                        <label for="name" class="block text-xs sm:text-sm font-medium text-slate-700 mb-2">
                            Nama Business <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $business->name) }}" required
                               class="block w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-slate-300 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-100 @error('name') border-red-300 @enderror"
                               placeholder="e.g., Warung Kopi Senja">
                        @error('name')<p class="mt-1.5 text-xs sm:text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="position" class="block text-xs sm:text-sm font-medium text-slate-700 mb-2">Posisi Anda</label>
                        <input type="text" name="position" id="position" value="{{ old('position', $business->position) }}"
                               class="block w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-slate-300 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-100 @error('position') border-red-300 @enderror"
                               placeholder="e.g., CEO, Co-Founder">
                        @error('position')<p class="mt-1.5 text-xs sm:text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                        <div>
                            <label for="business_type_id" class="block text-xs sm:text-sm font-medium text-slate-700 mb-2">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <select name="business_type_id" id="business_type_id" required
                                    class="block w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-slate-300 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-100 @error('business_type_id') border-red-300 @enderror">
                                <option value="">Pilih Kategori</option>
                                @foreach($businessTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('business_type_id', $business->business_type_id) == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                            @error('business_type_id')<p class="mt-1.5 text-xs sm:text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="business_mode" class="block text-xs sm:text-sm font-medium text-slate-700 mb-2">
                                Jenis Offering <span class="text-red-500">*</span>
                            </label>
                            <select name="business_mode" id="business_mode" required
                                    class="block w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-slate-300 rounded-lg focus:border-orange-400 focus:ring-2 focus:ring-orange-100 @error('business_mode') border-red-300 @enderror">
                                <option value="">Pilih Jenis</option>
                                <option value="product" {{ old('business_mode', $business->business_mode) == 'product' ? 'selected' : '' }}>Product Only</option>
                                <option value="service" {{ old('business_mode', $business->business_mode) == 'service' ? 'selected' : '' }}>Service Only</option>
                                <option value="both" {{ old('business_mode', $business->business_mode) == 'both' ? 'selected' : '' }}>Product & Service</option>
                            </select>
                            @error('business_mode')<p class="mt-1.5 text-xs sm:text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-xs sm:text-sm font-medium text-slate-700 mb-2">
                            Deskripsi <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" id="description" rows="4" required
                                  class="block w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-slate-300 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-100 @error('description') border-red-300 @enderror"
                                  placeholder="Deskripsikan business Anda...">{{ old('description', $business->description) }}</textarea>
                        @error('description')<p class="mt-1.5 text-xs sm:text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                        <div>
                            <label for="city" class="block text-xs sm:text-sm font-medium text-slate-700 mb-2">Kota</label>
                            <input type="text" name="city" id="city" value="{{ old('city', $business->city) }}"
                                   class="block w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-slate-300 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-100 @error('city') border-red-300 @enderror"
                                   placeholder="e.g., Jakarta">
                            @error('city')<p class="mt-1.5 text-xs sm:text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="province" class="block text-xs sm:text-sm font-medium text-slate-700 mb-2">Provinsi</label>
                            <input type="text" name="province" id="province" value="{{ old('province', $business->province) }}"
                                   class="block w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-slate-300 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-100 @error('province') border-red-300 @enderror"
                                   placeholder="e.g., DKI Jakarta">
                            @error('province')<p class="mt-1.5 text-xs sm:text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label for="address" class="block text-xs sm:text-sm font-medium text-slate-700 mb-2">Alamat Lengkap</label>
                        <textarea name="address" id="address" rows="3"
                                  class="block w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-slate-300 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-100 @error('address') border-red-300 @enderror"
                                  placeholder="Alamat lengkap...">{{ old('address', $business->address) }}</textarea>
                        @error('address')<p class="mt-1.5 text-xs sm:text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                        <div>
                            <label for="phone" class="block text-xs sm:text-sm font-medium text-slate-700 mb-2">Telepon</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $business->phone) }}"
                                   class="block w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-slate-300 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-100 @error('phone') border-red-300 @enderror"
                                   placeholder="0812-3456-7890">
                            @error('phone')<p class="mt-1.5 text-xs sm:text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="email" class="block text-xs sm:text-sm font-medium text-slate-700 mb-2">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $business->email) }}"
                                   class="block w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-slate-300 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-100 @error('email') border-red-300 @enderror"
                                   placeholder="business@example.com">
                            @error('email')<p class="mt-1.5 text-xs sm:text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    @if($showOwnerField)
                        <div>
                            <label for="user_id" class="block text-xs sm:text-sm font-medium text-slate-700 mb-2">Owner (Admin Only)</label>
                            <select name="user_id" id="user_id"
                                    class="block w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-slate-300 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-100 @error('user_id') border-red-300 @enderror">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id', $business->user_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')<p class="mt-1.5 text-xs sm:text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    @endif
                </div>
            </div>

            {{-- ======================================== SUBMIT BUTTONS ======================================== --}}
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-4">
                <button type="submit" class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3 sm:py-4 px-6 rounded-lg shadow-md transition text-sm sm:text-base">
                    💾 Update Business
                </button>
                <a href="{{ route('businesses.show', $business) }}" class="flex-1 bg-gradient-to-r from-slate-400 to-slate-500 hover:from-slate-500 hover:to-slate-600 text-white font-semibold py-3 sm:py-4 px-6 rounded-lg shadow-md transition text-center text-sm sm:text-base">
                    ✖ Batal
                </a>
                @if($showDeleteButton)
                    <button type="button" 
                            onclick="if(confirm('Yakin hapus business ini?')) document.getElementById('delete-form').submit();"
                            class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold py-3 sm:py-4 px-4 sm:px-6 rounded-lg shadow-md transition text-sm sm:text-base">
                        🗑️ <span class="hidden sm:inline">Hapus</span>
                    </button>
                @endif
            </div>
        </form>

        @if($showDeleteButton)
            <form id="delete-form" action="{{ route('businesses.destroy', $business) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        @endif
    </div>
</x-app-layout>
