<x-app-layout>
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.default.min.css" rel="stylesheet">
        <style>
            .ts-wrapper {
                width: 100% !important;
                display: block !important;
                margin: 0 !important;
                padding: 0 !important;
                box-sizing: border-box !important;
            }

            .ts-wrapper .ts-control {
                border: 1px solid #e2e8f0 !important;
                border-radius: 0.75rem !important;
                padding: 10px 16px !important; 
                min-height: 42px !important;
                width: 100% !important;
                box-sizing: border-box !important;
                transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
                background: white !important;
                display: flex !important;
                align-items: center !important;
            }

            .ts-wrapper.focus .ts-control {
                border-color: #111827 !important; /* Soft Gray 900 */
                box-shadow: 0 0 0 4px rgba(17, 24, 39, 0.05) !important;
                ring: none !important;
            }

            .ts-dropdown {
                background-color: white !important;
                border: 1px solid #e2e8f0 !important;
                border-radius: 1rem !important;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
                margin-top: 6px !important;
                padding: 6px !important;
                z-index: 1000 !important;
            }

            .ts-dropdown .option {
                padding: 8px 12px !important;
                font-size: 13px !important;
                color: #475569 !important;
                border-radius: 0.75rem !important;
                margin-bottom: 2px !important;
                transition: all 0.15s ease !important;
            }

            .ts-dropdown .option.active {
                background-color: #fff7ed !important;
                color: #f97316 !important;
                font-weight: 600 !important;
            }

            .ts-wrapper .ts-control>input {
                font-size: 14px !important;
            }
        </style>
    @endpush
        <div class="mb-6 flex items-center gap-4">
            <a href="{{ route('users.index') }}" 
               class="group inline-flex items-center gap-2.5 px-4 py-2.5 bg-white hover:bg-gray-900 border border-gray-200 hover:border-gray-900 text-gray-700 hover:text-white rounded-xl font-medium text-sm shadow-sm hover:shadow-md transition-all duration-200">
                <i class="bi bi-arrow-left text-base group-hover:-translate-x-0.5 transition-transform duration-200"></i>
                <span>Back</span>
            </a>
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-900">Create New User</h1>
                <p class="text-sm text-gray-600">Add a new user with complete information</p>
                
                @if(auth()->user()->isAdmin())
                    <div class="mt-2 inline-flex items-center gap-2 px-3 py-1 bg-blue-50 border border-blue-100 rounded-lg text-xs font-medium text-blue-700">
                        <i class="bi bi-info-circle-fill"></i>
                        <span>Need to add many users?</span>
                        <a href="{{ route('users.index', ['import' => 1]) }}" class="font-bold underline hover:text-blue-800 transition-colors">Import from Excel Instead</a>
                    </div>
                @endif
            </div>
        </div>

        <form method="POST" action="/users" class="space-y-6">
            @csrf

            {{-- Tab Navigation - Ultra Clean --}}
            <div class="bg-white shadow-sm rounded-xl overflow-hidden mb-6">
                <nav class="flex border-b border-gray-200">
                    <button type="button" @click="activeTab = 'basic'" 
                            :class="activeTab === 'basic' ? 'border-b-2 border-gray-900 text-gray-900 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                            class="flex-1 py-4 px-4 text-sm text-center transition-all duration-200">
                        Basic Info
                    </button>
                    <button type="button" @click="activeTab = 'personal'" 
                            :class="activeTab === 'personal' ? 'border-b-2 border-gray-900 text-gray-900 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                            class="flex-1 py-4 px-4 text-sm text-center transition-all duration-200">
                        Personal
                    </button>
                    <button type="button" @click="activeTab = 'academic'" 
                            :class="activeTab === 'academic' ? 'border-b-2 border-gray-900 text-gray-900 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                            class="flex-1 py-4 px-4 text-sm text-center transition-all duration-200">
                        Academic
                    </button>
                    <button type="button" @click="activeTab = 'parents'" 
                            :class="activeTab === 'parents' ? 'border-b-2 border-gray-900 text-gray-900 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                            class="flex-1 py-4 px-4 text-sm text-center transition-all duration-200">
                        Parents
                    </button>
                </nav>
            </div>

            {{-- Tab Content --}}

            {{-- Tab Content --}}

            {{-- Basic Information Tab --}}
            <div x-show="activeTab === 'basic'" class="bg-white shadow-sm rounded-xl p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b-2 border-gray-100">
                    Basic Information
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Username --}}
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                            Username <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="username" id="username" value="{{ old('username') }}" required
                               class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('username') border-gray-200 @enderror">
                        @error('username')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Full Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('name') border-gray-200 @enderror">
                        @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                               class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('email') border-gray-200 @enderror">
                        @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password" id="password" required
                               class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('password') border-gray-200 @enderror">
                        @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Password Confirmation --}}
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirm Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                    </div>

                    {{-- Role --}}
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <select name="role" id="role" required
                                class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                            <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Student</option>
                            <option value="alumni" {{ old('role') === 'alumni' ? 'selected' : '' }}>Alumni</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Birth Date --}}
                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">Birth Date</label>
                        <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}"
                               class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                    </div>

                    {{-- Birth City --}}
                    <div>
                        <label for="birth_city" class="block text-sm font-medium text-gray-700 mb-2">Birth City</label>
                        <input type="text" name="birth_city" id="birth_city" value="{{ old('birth_city') }}"
                               class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                    </div>

                    {{-- Religion --}}
                    <div>
                        <label for="religion" class="block text-sm font-medium text-gray-700 mb-2">Religion</label>
                        <input type="text" name="religion" id="religion" value="{{ old('religion') }}"
                               class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                    </div>

                    {{-- Phone Number --}}
                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="tel" name="phone_number" id="phone_number" value="{{ old('phone_number') }}"
                               class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                    </div>

                    {{-- Mobile Number --}}
                    <div>
                        <label for="mobile_number" class="block text-sm font-medium text-gray-700 mb-2">Mobile Number</label>
                        <input type="tel" name="mobile_number" id="mobile_number" value="{{ old('mobile_number') }}"
                               class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                    </div>

                    {{-- WhatsApp --}}
                    <div>
                        <label for="whatsapp" class="block text-sm font-medium text-gray-700 mb-2">WhatsApp</label>
                        <input type="tel" name="whatsapp" id="whatsapp" value="{{ old('whatsapp') }}"
                               class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                    </div>

                    {{-- Passport No --}}
                    <div>
                        <label for="personal_data[passport_no]" class="block text-sm font-medium text-gray-700 mb-2">Passport Number</label>
                        <input type="text" name="personal_data[passport_no]" id="personal_data[passport_no]" value="{{ old('personal_data.passport_no') }}"
                               class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                    </div>

                    {{-- Special Needs --}}
                    <div>
                        <label for="personal_data[special_need]" class="block text-sm font-medium text-gray-700 mb-2">Special Needs</label>
                        <input type="text" name="personal_data[special_need]" id="personal_data[special_need]" value="{{ old('personal_data.special_need') }}"
                               class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900" placeholder="e.g. None">
                    </div>

                    {{-- Status --}}
                    <div class="md:col-span-1 py-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                   class="w-4 h-4 text-soft-gray-900 border-gray-200 rounded focus:ring-soft-gray-900">
                            <span class="text-sm font-medium text-gray-700">Active Status</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Personal Information Tab --}}
            <div x-show="activeTab === 'personal'" class="bg-white shadow-sm rounded-xl p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b-2 border-gray-100">
                    Personal & Contact Information
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Gender --}}
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                        <select name="personal_data[gender]" id="gender"
                                class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>

                    {{-- Citizenship --}}
                    <div>
                        <label for="citizenship" class="block text-sm font-medium text-gray-700 mb-2">Citizenship</label>
                        <input type="text" name="personal_data[citizenship]" id="citizenship" value="{{ old('personal_data.citizenship', 'Indonesia') }}"
                               class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                    </div>

                    {{-- Citizenship No / ID --}}
                    <div>
                        <label for="citizenship_no" class="block text-sm font-medium text-gray-700 mb-2">ID Number (KTP/NIK)</label>
                        <input type="text" name="personal_data[citizenship_no]" id="citizenship_no" value="{{ old('personal_data.citizenship_no') }}"
                               class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                    </div>

                    {{-- Address --}}
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                        <textarea name="personal_data[address]" id="address" rows="2"
                                  class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">{{ old('personal_data.address') }}</textarea>
                    </div>

                    {{-- Address City --}}
                    @php
                        $selectedPersonalProvince = old('personal_data.province');
                        $selectedPersonalProvinceId = $selectedPersonalProvince ? optional($provinces->firstWhere('name', $selectedPersonalProvince))->id : null;
                        $selectedPersonalCity = old('personal_data.address_city');
                    @endphp
                    <div>
                        <label for="personal_province" class="block text-sm font-medium text-gray-700 mb-2">Province</label>
                        <select name="personal_data[province]" id="personal_province"
                                class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                            <option value="">Select Province</option>
                            @foreach($provinces as $province)
                                <option value="{{ $province->name }}" data-id="{{ $province->id }}" {{ old('personal_data.province') === $province->name ? 'selected' : '' }}>
                                    {{ $province->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="personal_address_city" class="block text-sm font-medium text-gray-700 mb-2">City</label>
                        <select name="personal_data[address_city]" id="personal_address_city"
                                data-selected-city="{{ $selectedPersonalCity }}"
                                data-selected-province-id="{{ $selectedPersonalProvinceId }}"
                                class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900"
                                {{ $selectedPersonalProvinceId ? '' : 'disabled' }}>
                            <option value="">Select City</option>
                            @if($selectedPersonalCity)
                                <option value="{{ $selectedPersonalCity }}" selected>{{ $selectedPersonalCity }}</option>
                            @endif
                        </select>
                    </div>

                    {{-- Country --}}
                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                        <input type="text" name="personal_data[country]" id="country" value="{{ old('personal_data.country', 'Indonesia') }}"
                               class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                    </div>

                    {{-- Zip Code --}}
                    <div>
                        <label for="zip_code" class="block text-sm font-medium text-gray-700 mb-2">Zip Code</label>
                        <input type="text" name="personal_data[zip_code]" id="zip_code" value="{{ old('personal_data.zip_code') }}"
                               class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                    </div>

                    <div class="md:col-span-2 border-t pt-6 mt-4">
                        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Secondary Address / Home</h3>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Secondary Address (Street)</label>
                        <textarea name="personal_data[address2]" rows="2"
                                  class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">{{ old('personal_data.address2') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">City 2</label>
                        <input type="text" name="personal_data[address_city2]" value="{{ old('personal_data.address_city2') }}"
                               class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Province 2</label>
                        <input type="text" name="personal_data[province2]" value="{{ old('personal_data.province2') }}"
                               class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Country 2</label>
                        <input type="text" name="personal_data[country2]" value="{{ old('personal_data.country2') }}"
                               class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Zip Code 2</label>
                        <input type="text" name="personal_data[zip_code2]" value="{{ old('personal_data.zip_code2') }}"
                               class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                    </div>

                    {{-- Social Media Section --}}
                    <div class="md:col-span-2 border-t pt-6 mt-4">
                        <h3 class="text-md font-semibold text-gray-800 mb-4">Social Media</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="line" class="block text-sm font-medium text-gray-700 mb-2">LINE ID</label>
                                <input type="text" name="personal_data[line]" id="line" value="{{ old('personal_data.line') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                            </div>
                            <div>
                                <label for="facebook" class="block text-sm font-medium text-gray-700 mb-2">Facebook</label>
                                <input type="text" name="personal_data[facebook]" id="facebook" value="{{ old('personal_data.facebook') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                            </div>
                            <div>
                                <label for="twitter" class="block text-sm font-medium text-gray-700 mb-2">Twitter</label>
                                <input type="text" name="personal_data[twitter]" id="twitter" value="{{ old('personal_data.twitter') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                            </div>
                            <div>
                                <label for="instagram" class="block text-sm font-medium text-gray-700 mb-2">Instagram</label>
                                <input type="text" name="personal_data[instagram]" id="instagram" value="{{ old('personal_data.instagram') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                            </div>
                            <div>
                                <label for="twitter_x" class="block text-sm font-medium text-gray-700 mb-2">Twitter (X)</label>
                                <input type="text" name="personal_data[twitter]" id="twitter_x" value="{{ old('personal_data.twitter') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                            </div>
                            <div>
                                <label for="bbm" class="block text-sm font-medium text-gray-700 mb-2">BBM</label>
                                <input type="text" name="personal_data[bbm]" id="bbm" value="{{ old('personal_data.bbm') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Academic Information Tab --}}
            <div x-show="activeTab === 'academic'" class="bg-white shadow-sm rounded-xl p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b-2 border-gray-100">
                    Academic Information
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- NIS --}}
                    <div>
                        <label for="NIS" class="block text-sm font-medium text-gray-700 mb-2">NIS</label>
                        <input type="text" name="NIS" id="NIS" value="{{ old('NIS') }}"
                               class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                    </div>

                    {{-- NISN --}}
                    <div>
                        <label for="nisn" class="block text-sm font-medium text-gray-700 mb-2">NISN</label>
                        <input type="text" name="academic_data[nisn]" id="nisn" value="{{ old('academic_data.nisn') }}"
                               class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                    </div>

                    {{-- Student Year --}}
                    <div>
                        <label for="Student_Year" class="block text-sm font-medium text-gray-700 mb-2">Student Year</label>
                        <input type="text" name="Student_Year" id="Student_Year" value="{{ old('Student_Year') }}"
                               class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900"
                               placeholder="2023">
                    </div>

                    {{-- Major --}}
                    <div>
                        <label for="Major" class="block text-sm font-medium text-gray-700 mb-2">Major</label>
                        <input type="text" name="Major" id="Major" value="{{ old('Major') }}"
                               class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                    </div>

                    {{-- Program (Prodi) --}}
                    <div>
                        <label for="prodi" class="block text-sm font-medium text-gray-700 mb-2">Program (Prodi)</label>
                        <input type="text" name="academic_data[prodi]" id="prodi" value="{{ old('academic_data.prodi') }}"
                               class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                    </div>

                    {{-- Education Level --}}
                    <div>
                        <label for="edu_level" class="block text-sm font-medium text-gray-700 mb-2">Education Level</label>
                        <select name="academic_data[edu_level]" id="edu_level"
                                class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                            <option value="">Select Level</option>
                            <option value="S1">S1 (Bachelor)</option>
                            <option value="S2">S2 (Master)</option>
                            <option value="S3">S3 (Doctorate)</option>
                        </select>
                    </div>

                    {{-- Academic Advisor --}}
                    <div>
                        <label for="academic_advisor" class="block text-sm font-medium text-gray-700 mb-2">Academic Advisor</label>
                        <input type="text" name="academic_data[academic_advisor]" id="academic_advisor" value="{{ old('academic_data.academic_advisor') }}"
                               class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                    </div>

                    {{-- CGPA --}}
                    <div>
                        <label for="CGPA" class="block text-sm font-medium text-gray-700 mb-2">CGPA</label>
                        <input type="number" step="0.01" name="CGPA" id="CGPA" value="{{ old('CGPA') }}"
                               class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900"
                               placeholder="3.85">
                    </div>

                    {{-- Certifications --}}
                    <div>
                        <label for="cert1" class="block text-sm font-medium text-gray-700 mb-2">Certificate No 1</label>
                        <input type="text" name="academic_data[certificate_no_1]" id="cert1" value="{{ old('academic_data.certificate_no_1') }}"
                               class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                    </div>
                    <div>
                        <label for="cert2" class="block text-sm font-medium text-gray-700 mb-2">Certificate No 2</label>
                        <input type="text" name="academic_data[certificate_no_2]" id="cert2" value="{{ old('academic_data.certificate_no_2') }}"
                               class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                    </div>

                    {{-- Is Graduate --}}
                    <div class="md:col-span-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="Is_Graduate" value="1" {{ old('Is_Graduate') ? 'checked' : '' }}
                                   class="w-4 h-4 text-soft-gray-900 border-gray-200 rounded focus:ring-soft-gray-900">
                            <span class="text-sm font-medium text-gray-700">Is Graduate</span>
                        </label>
                    </div>

                    {{-- Previous School Section --}}
                    <div class="md:col-span-2 border-t pt-6 mt-4">
                        <h3 class="text-md font-semibold text-gray-800 mb-4">Previous Education</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="previous_school_name" class="block text-sm font-medium text-gray-700 mb-2">School Name</label>
                                <input type="text" name="academic_data[previous_school_name]" id="previous_school_name" value="{{ old('academic_data.previous_school_name') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                            </div>
                            <div>
                                <label for="school_city" class="block text-sm font-medium text-gray-700 mb-2">School City</label>
                                <input type="text" name="academic_data[school_city]" id="school_city" value="{{ old('academic_data.school_city') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                            </div>
                            <div>
                                <label for="start_year" class="block text-sm font-medium text-gray-700 mb-2">Start Year</label>
                                <input type="text" name="academic_data[start_year]" id="start_year" value="{{ old('academic_data.start_year') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                            </div>
                            <div>
                                <label for="end_year" class="block text-sm font-medium text-gray-700 mb-2">End Year</label>
                                <input type="text" name="academic_data[end_year]" id="end_year" value="{{ old('academic_data.end_year') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                            </div>
                        </div>
                    </div>

                    {{-- Graduation Data Section --}}
                    <div class="md:col-span-2 border-t pt-6 mt-4">
                        <h3 class="text-md font-semibold text-gray-800 mb-4">Graduation & Career Info</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Final Projects --}}
                            <div>
                                <label for="final_project_indonesia" class="block text-sm font-medium text-gray-700 mb-2">Final Project (Indonesia)</label>
                                <input type="text" name="graduation_data[final_project_indonesia]" id="final_project_indonesia" value="{{ old('graduation_data.final_project_indonesia') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                            </div>
                            <div>
                                <label for="final_project_english" class="block text-sm font-medium text-gray-700 mb-2">Final Project (English)</label>
                                <input type="text" name="graduation_data[final_project_english]" id="final_project_english" value="{{ old('graduation_data.final_project_english') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                            </div>

                            {{-- Results --}}
                            <div>
                                <label for="predicate" class="block text-sm font-medium text-gray-700 mb-2">Predicate (Cumlaude, etc.)</label>
                                <input type="text" name="graduation_data[predicate]" id="predicate" value="{{ old('graduation_data.predicate') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                            </div>
                            <div>
                                <label for="judicium_date" class="block text-sm font-medium text-gray-700 mb-2">Judicium Date</label>
                                <input type="date" name="graduation_data[judicium_date]" id="judicium_date" value="{{ old('graduation_data.judicium_date') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                            </div>

                            {{-- Documents --}}
                            <div>
                                <label for="document_no" class="block text-sm font-medium text-gray-700 mb-2">Document No (SK Yudisium/Ijazah)</label>
                                <input type="text" name="graduation_data[document_no]" id="document_no" value="{{ old('graduation_data.document_no') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                            </div>
                            <div>
                                <label for="graduate_period" class="block text-sm font-medium text-gray-700 mb-2">Graduate Period</label>
                                <input type="text" name="graduation_data[graduate_period]" id="graduate_period" value="{{ old('graduation_data.graduate_period') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900"
                                       placeholder="Periode 1 2024">
                            </div>

                            {{-- Professional Status --}}
                            <div>
                                <label for="official_email" class="block text-sm font-medium text-gray-700 mb-2">Official/Professional Email</label>
                                <input type="email" name="graduation_data[official_email]" id="official_email" value="{{ old('graduation_data.official_email') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                            </div>
                            <div>
                                <label for="current_status" class="block text-sm font-medium text-gray-700 mb-2">Current Career Status</label>
                                <input type="text" name="graduation_data[current_status]" id="current_status" value="{{ old('graduation_data.current_status') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Parents Information Tab --}}
            <div x-show="activeTab === 'parents'" class="bg-white shadow-sm rounded-xl p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b-2 border-gray-100">
                    Parents Information
                </h2>
                
                {{-- Father Information --}}
                <div class="mb-8">
                    <h3 class="text-md font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Father's Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <input type="text" name="father_data[name]" value="{{ old('father_data.name') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Birth City</label>
                            <input type="text" name="father_data[birth_city]" value="{{ old('father_data.birth_city') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Birthday</label>
                            <input type="date" name="father_data[birthday]" value="{{ old('father_data.birthday') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Religion</label>
                            <input type="text" name="father_data[religion]" value="{{ old('father_data.religion') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Citizenship</label>
                            <input type="text" name="father_data[citizenship]" value="{{ old('father_data.citizenship', 'Indonesia') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">NPWP Number</label>
                            <input type="text" name="father_data[npwp_no]" value="{{ old('father_data.npwp_no') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">BPJS Number</label>
                            <input type="text" name="father_data[bpjs_no]" value="{{ old('father_data.bpjs_no') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Passport Number</label>
                            <input type="text" name="father_data[passport_no]" value="{{ old('father_data.passport_no') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Citizenship No (ID Card)</label>
                            <input type="text" name="father_data[citizenship_no]" value="{{ old('father_data.citizenship_no') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                            <textarea name="father_data[address]" rows="2"
                                      class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">{{ old('father_data.address') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input type="tel" name="father_data[phone]" value="{{ old('father_data.phone') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mobile</label>
                            <input type="tel" name="father_data[mobile]" value="{{ old('father_data.mobile') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="father_data[email]" value="{{ old('father_data.email') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Education Level</label>
                            <input type="text" name="father_data[education]" value="{{ old('father_data.education') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Profession</label>
                            <input type="text" name="father_data[profession]" value="{{ old('father_data.profession') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Business Name</label>
                            <input type="text" name="father_data[business_name]" value="{{ old('father_data.business_name') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Business Revenue</label>
                            <input type="text" name="father_data[business_revenue]" value="{{ old('father_data.business_revenue') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900"
                                   placeholder="e.g. > 1B">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Business Address</label>
                            <input type="text" name="father_data[business_address]" value="{{ old('father_data.business_address') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                    </div>
                </div>

                {{-- Mother Information --}}
                <div>
                    <h3 class="text-md font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Mother's Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <input type="text" name="mother_data[name]" value="{{ old('mother_data.name') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Birth City</label>
                            <input type="text" name="mother_data[birth_city]" value="{{ old('mother_data.birth_city') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Birthday</label>
                            <input type="date" name="mother_data[birthday]" value="{{ old('mother_data.birthday') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Religion</label>
                            <input type="text" name="mother_data[religion]" value="{{ old('mother_data.religion') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Citizenship</label>
                            <input type="text" name="mother_data[citizenship]" value="{{ old('mother_data.citizenship', 'Indonesia') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">NPWP Number</label>
                            <input type="text" name="mother_data[npwp_no]" value="{{ old('mother_data.npwp_no') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">BPJS Number</label>
                            <input type="text" name="mother_data[bpjs_no]" value="{{ old('mother_data.bpjs_no') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Passport Number</label>
                            <input type="text" name="mother_data[passport_no]" value="{{ old('mother_data.passport_no') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Citizenship No (ID Card)</label>
                            <input type="text" name="mother_data[citizenship_no]" value="{{ old('mother_data.citizenship_no') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                            <textarea name="mother_data[address]" rows="2"
                                      class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">{{ old('mother_data.address') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input type="tel" name="mother_data[phone]" value="{{ old('mother_data.phone') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mobile</label>
                            <input type="tel" name="mother_data[mobile]" value="{{ old('mother_data.mobile') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="mother_data[email]" value="{{ old('mother_data.email') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Education Level</label>
                            <input type="text" name="mother_data[education]" value="{{ old('mother_data.education') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Profession</label>
                            <input type="text" name="mother_data[profession]" value="{{ old('mother_data.profession') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Business Name</label>
                            <input type="text" name="mother_data[business_name]" value="{{ old('mother_data.business_name') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Business Revenue</label>
                            <input type="text" name="mother_data[business_revenue]" value="{{ old('mother_data.business_revenue') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900"
                                   placeholder="e.g. 500M - 1B">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Business Address</label>
                            <input type="text" name="mother_data[business_address]" value="{{ old('mother_data.business_address') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-between pb-6">
                <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 hover:text-gray-900 rounded-xl transition duration-150">
    Cancel
</a>
                <button type="submit" 
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-soft-gray-900 hover:bg-soft-gray-800 text-white font-semibold rounded-xl shadow-md hover:shadow-lg transition-all duration-200">
                    <i class="bi bi-person-plus-fill me-2"></i>
                    Create User
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
        <script>
            const UCO_PROVINCE_MAP = @json($provinces->pluck('id', 'name'));

            async function loadPersonalRegenciesByProvince(provinceId, citySelect, cityTSInstance = null) {
                if (!provinceId) {
                    if (cityTSInstance) {
                        cityTSInstance.clearOptions();
                        cityTSInstance.disable();
                    }
                    citySelect.innerHTML = '<option value="">Select City</option>';
                    citySelect.disabled = true;
                    return;
                }

                citySelect.innerHTML = '<option value="">Loading cities...</option>';
                citySelect.disabled = false;
                
                if (cityTSInstance) {
                    cityTSInstance.clearOptions();
                    cityTSInstance.enable();
                }

                try {
                    const response = await fetch(`{{ route('regions.regencies') }}?province_id=${provinceId}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    const regencies = await response.json();

                    if (cityTSInstance) {
                        const options = regencies.map(r => ({ value: r.name, text: r.name }));
                        cityTSInstance.addOptions(options);
                        cityTSInstance.refreshOptions(false);
                    } else {
                        citySelect.innerHTML = '<option value="">Select City/Kabupaten</option>';
                        regencies.forEach((regency) => {
                            const option = document.createElement('option');
                            option.value = regency.name;
                            option.textContent = regency.name;
                            citySelect.appendChild(option);
                        });
                    }
                } catch (error) {
                    citySelect.disabled = true;
                    if (cityTSInstance) cityTSInstance.disable();
                }
            }

            document.addEventListener('DOMContentLoaded', () => {
                const roleSelect = document.getElementById('role');
                const genderSelect = document.getElementById('gender');
                const provinceSelect = document.getElementById('personal_province');
                const citySelect = document.getElementById('personal_address_city');
                const eduLevelSelect = document.getElementById('edu_level');

                let cityTS = null;

                if (roleSelect && window.TomSelect) {
                    new TomSelect(roleSelect, { create: false, placeholder: "Select Role", searchField: ["text"] });
                }

                if (genderSelect && window.TomSelect) {
                    new TomSelect(genderSelect, { create: false, placeholder: "Select Gender", searchField: ["text"] });
                }

                if (eduLevelSelect && window.TomSelect) {
                    new TomSelect(eduLevelSelect, { create: false, placeholder: "Select education level", searchField: ["text"] });
                }

                if (citySelect && window.TomSelect) {
                    cityTS = new TomSelect(citySelect, { create: false, placeholder: "Select City", searchField: ["text"] });
                }

                if (provinceSelect && window.TomSelect) {
                    const provinceTS = new TomSelect(provinceSelect, {
                        create: false,
                        placeholder: "Select Province",
                        searchField: ["text"]
                    });

                    provinceTS.on('change', function(value) {
                        const provinceId = UCO_PROVINCE_MAP[value] || null;
                        loadPersonalRegenciesByProvince(provinceId, citySelect, cityTS);
                    });

                    // Initial load if province exists
                    if (provinceTS.getValue()) {
                        const provinceId = UCO_PROVINCE_MAP[provinceTS.getValue()] || null;
                        const selectedCity = citySelect.dataset.selectedCity;
                        loadPersonalRegenciesByProvince(provinceId, citySelect, cityTS).then(() => {
                            if (selectedCity && cityTS) cityTS.setValue(selectedCity);
                        });
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>