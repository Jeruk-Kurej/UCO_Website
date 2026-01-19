@php
// Data sudah auto-cast ke array di Model, tidak perlu json_decode lagi
$personalData = $userToEdit->personal_data ?? [];
$academicData = $userToEdit->academic_data ?? [];
$fatherData = $userToEdit->father_data ?? [];
$motherData = $userToEdit->mother_data ?? [];
$graduationData = $userToEdit->graduation_data ?? [];
@endphp
<x-app-layout>
    <div class="max-w-6xl mx-auto" x-data="{ activeTab: 'basic' }">
        
        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-xl shadow-sm flex items-start gap-3">
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
        <div class="mb-6 flex items-center gap-4">
            <a href="/users" 
               class="group inline-flex items-center gap-2.5 px-4 py-2.5 bg-white hover:bg-gray-900 border border-gray-200 hover:border-gray-900 text-gray-700 hover:text-white rounded-xl font-medium text-sm shadow-sm hover:shadow-md transition-all duration-200">
                <i class="bi bi-arrow-left text-base group-hover:-translate-x-0.5 transition-transform duration-200"></i>
                <span>Back</span>
            </a>
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-900">Edit User</h1>
                <p class="text-sm text-gray-600">{{ $userToEdit->name }} ({{ $userToEdit->username }})</p>
            </div>
        </div>

        <form method="POST" action="{{ route('users.update', $userToEdit) }}" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Tab Navigation - Ultra Clean --}}
            <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
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

            {{-- Basic Information Tab --}}
            <div x-show="activeTab === 'basic'" class="bg-white shadow-sm rounded-lg p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b-2 border-gray-100">
                    Basic Information
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Username --}}
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                            Username <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="username" id="username" value="{{ old('username', $userToEdit->username) }}" required
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('username') border-red-300 @enderror">
                        @error('username')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Full Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $userToEdit->name) }}" required
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('name') border-red-300 @enderror">
                        @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email', $userToEdit->email) }}" required
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('email') border-red-300 @enderror">
                        @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password" id="password" required
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900 @error('password') border-red-300 @enderror">
                        @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Password Confirmation --}}
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirm Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                    </div>

                    {{-- Role --}}
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <select name="role" id="role" required
                                class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                            <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Student</option>
                            <option value="alumni" {{ old('role') === 'alumni' ? 'selected' : '' }}>Alumni</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Birth Date --}}
                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">Birth Date</label>
                        <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $userToEdit->birth_date) }}"
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                    </div>

                    {{-- Birth City --}}
                    <div>
                        <label for="birth_city" class="block text-sm font-medium text-gray-700 mb-2">Birth City</label>
                        <input type="text" name="birth_city" id="birth_city" value="{{ old('birth_city', $userToEdit->birth_city) }}"
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                    </div>

                    {{-- Religion --}}
                    <div>
                        <label for="religion" class="block text-sm font-medium text-gray-700 mb-2">Religion</label>
                        <input type="text" name="religion" id="religion" value="{{ old('religion', $userToEdit->religion) }}"
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                    </div>

                    {{-- Phone Number --}}
                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="tel" name="phone_number" id="phone_number" value="{{ old('phone_number', $userToEdit->phone_number) }}"
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                    </div>

                    {{-- Mobile Number --}}
                    <div>
                        <label for="mobile_number" class="block text-sm font-medium text-gray-700 mb-2">Mobile Number</label>
                        <input type="tel" name="mobile_number" id="mobile_number" value="{{ old('mobile_number', $userToEdit->mobile_number) }}"
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                    </div>

                    {{-- WhatsApp --}}
                    <div>
                        <label for="whatsapp" class="block text-sm font-medium text-gray-700 mb-2">WhatsApp</label>
                        <input type="tel" name="whatsapp" id="whatsapp" value="{{ old('whatsapp', $userToEdit->whatsapp) }}"
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                    </div>

                    {{-- Status --}}
                    <div class="md:col-span-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $userToEdit->is_active) ? 'checked' : '' }}
                                   class="w-4 h-4 text-soft-gray-900 border-gray-300 rounded focus:ring-soft-gray-900">
                            <span class="text-sm font-medium text-gray-700">Active Status</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Personal Information Tab --}}
            <div x-show="activeTab === 'personal'" class="bg-white shadow-sm rounded-lg p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b-2 border-gray-100">
                    Personal & Contact Information
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Gender --}}
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                        <select name="personal_data[gender]" id="gender"
                                class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>

                    {{-- Citizenship --}}
                    <div>
                        <label for="citizenship" class="block text-sm font-medium text-gray-700 mb-2">Citizenship</label>
                        <input type="text" name="personal_data[citizenship]" id="citizenship" value="{{ old('personal_data.citizenship', $personalData['citizenship'] ?? 'Indonesia') }}"
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                    </div>

                    {{-- Citizenship No / ID --}}
                    <div>
                        <label for="citizenship_no" class="block text-sm font-medium text-gray-700 mb-2">ID Number (KTP/NIK)</label>
                        <input type="text" name="personal_data[citizenship_no]" id="citizenship_no" value="{{ old('personal_data.citizenship_no', $personalData['citizenship_no'] ?? '') }}"
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                    </div>

                    {{-- Address --}}
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                        <textarea name="personal_data[address]" id="address" rows="2"
                                  class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">{{ old('personal_data.address', $personalData['address'] ?? '') }}</textarea>
                    </div>

                    {{-- Address City --}}
                    <div>
                        <label for="address_city" class="block text-sm font-medium text-gray-700 mb-2">City</label>
                        <input type="text" name="personal_data[address_city]" id="address_city" value="{{ old('personal_data.address_city', $personalData['address_city'] ?? '') }}"
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                    </div>

                    {{-- Province --}}
                    <div>
                        <label for="province" class="block text-sm font-medium text-gray-700 mb-2">Province</label>
                        <input type="text" name="personal_data[province]" id="province" value="{{ old('personal_data.province', $personalData['province'] ?? '') }}"
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                    </div>

                    {{-- Country --}}
                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                        <input type="text" name="personal_data[country]" id="country" value="{{ old('personal_data.country', $personalData['country'] ?? 'Indonesia') }}"
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                    </div>

                    {{-- Zip Code --}}
                    <div>
                        <label for="zip_code" class="block text-sm font-medium text-gray-700 mb-2">Zip Code</label>
                        <input type="text" name="personal_data[zip_code]" id="zip_code" value="{{ old('personal_data.zip_code', $personalData['zip_code'] ?? '') }}"
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                    </div>

                    {{-- Social Media Section --}}
                    <div class="md:col-span-2 border-t pt-6 mt-4">
                        <h3 class="text-md font-semibold text-gray-800 mb-4">Social Media</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="line" class="block text-sm font-medium text-gray-700 mb-2">LINE ID</label>
                                <input type="text" name="personal_data[line]" id="line" value="{{ old('personal_data.line', $personalData['line'] ?? '') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                            </div>
                            <div>
                                <label for="facebook" class="block text-sm font-medium text-gray-700 mb-2">Facebook</label>
                                <input type="text" name="personal_data[facebook]" id="facebook" value="{{ old('personal_data.facebook', $personalData['facebook'] ?? '') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                            </div>
                            <div>
                                <label for="twitter" class="block text-sm font-medium text-gray-700 mb-2">Twitter</label>
                                <input type="text" name="personal_data[twitter]" id="twitter" value="{{ old('personal_data.twitter', $personalData['twitter'] ?? '') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                            </div>
                            <div>
                                <label for="instagram" class="block text-sm font-medium text-gray-700 mb-2">Instagram</label>
                                <input type="text" name="personal_data[instagram]" id="instagram" value="{{ old('personal_data.instagram', $personalData['instagram'] ?? '') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Academic Information Tab --}}
            <div x-show="activeTab === 'academic'" class="bg-white shadow-sm rounded-lg p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b-2 border-gray-100">
                    Academic Information
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- NIS --}}
                    <div>
                        <label for="NIS" class="block text-sm font-medium text-gray-700 mb-2">NIS</label>
                        <input type="text" name="NIS" id="NIS" value="{{ old('NIS', $userToEdit->NIS) }}"
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                    </div>

                    {{-- NISN --}}
                    <div>
                        <label for="nisn" class="block text-sm font-medium text-gray-700 mb-2">NISN</label>
                        <input type="text" name="academic_data[nisn]" id="nisn" value="{{ old('academic_data.nisn', $academicData['nisn'] ?? '') }}"
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                    </div>

                    {{-- Student Year --}}
                    <div>
                        <label for="Student_Year" class="block text-sm font-medium text-gray-700 mb-2">Student Year</label>
                        <input type="text" name="Student_Year" id="Student_Year" value="{{ old('Student_Year', $userToEdit->Student_Year) }}"
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900"
                               placeholder="2023">
                    </div>

                    {{-- Major --}}
                    <div>
                        <label for="Major" class="block text-sm font-medium text-gray-700 mb-2">Major</label>
                        <input type="text" name="Major" id="Major" value="{{ old('Major', $userToEdit->Major) }}"
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                    </div>

                    {{-- Program (Prodi) --}}
                    <div>
                        <label for="prodi" class="block text-sm font-medium text-gray-700 mb-2">Program (Prodi)</label>
                        <input type="text" name="academic_data[prodi]" id="prodi" value="{{ old('academic_data.prodi', $academicData['prodi'] ?? '') }}"
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                    </div>

                    {{-- Education Level --}}
                    <div>
                        <label for="edu_level" class="block text-sm font-medium text-gray-700 mb-2">Education Level</label>
                        <select name="academic_data[edu_level]" id="edu_level"
                                class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                            <option value="">Select Level</option>
                            <option value="S1">S1 (Bachelor)</option>
                            <option value="S2">S2 (Master)</option>
                            <option value="S3">S3 (Doctorate)</option>
                        </select>
                    </div>

                    {{-- Academic Advisor --}}
                    <div>
                        <label for="academic_advisor" class="block text-sm font-medium text-gray-700 mb-2">Academic Advisor</label>
                        <input type="text" name="academic_data[academic_advisor]" id="academic_advisor" value="{{ old('academic_data.academic_advisor', $academicData['academic_advisor'] ?? '') }}"
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                    </div>

                    {{-- CGPA --}}
                    <div>
                        <label for="CGPA" class="block text-sm font-medium text-gray-700 mb-2">CGPA</label>
                        <input type="number" step="0.01" name="CGPA" id="CGPA" value="{{ old('CGPA', $userToEdit->CGPA) }}"
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900"
                               placeholder="3.85">
                    </div>

                    {{-- Is Graduate --}}
                    <div class="md:col-span-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="Is_Graduate" value="1" {{ old('Is_Graduate', $userToEdit->Is_Graduate) ? 'checked' : '' }}
                                   class="w-4 h-4 text-soft-gray-900 border-gray-300 rounded focus:ring-soft-gray-900">
                            <span class="text-sm font-medium text-gray-700">Is Graduate</span>
                        </label>
                    </div>

                    {{-- Previous School Section --}}
                    <div class="md:col-span-2 border-t pt-6 mt-4">
                        <h3 class="text-md font-semibold text-gray-800 mb-4">Previous Education</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="previous_school_name" class="block text-sm font-medium text-gray-700 mb-2">School Name</label>
                                <input type="text" name="academic_data[previous_school_name]" id="previous_school_name" value="{{ old('academic_data.previous_school_name', $academicData['previous_school_name'] ?? '') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                            </div>
                            <div>
                                <label for="school_city" class="block text-sm font-medium text-gray-700 mb-2">School City</label>
                                <input type="text" name="academic_data[school_city]" id="school_city" value="{{ old('academic_data.school_city', $academicData['school_city'] ?? '') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                            </div>
                            <div>
                                <label for="start_year" class="block text-sm font-medium text-gray-700 mb-2">Start Year</label>
                                <input type="text" name="academic_data[start_year]" id="start_year" value="{{ old('academic_data.start_year', $academicData['start_year'] ?? '') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                            </div>
                            <div>
                                <label for="end_year" class="block text-sm font-medium text-gray-700 mb-2">End Year</label>
                                <input type="text" name="academic_data[end_year]" id="end_year" value="{{ old('academic_data.end_year', $academicData['end_year'] ?? '') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                            </div>
                        </div>
                    </div>

                    {{-- Graduation Data Section --}}
                    <div class="md:col-span-2 border-t pt-6 mt-4">
                        <h3 class="text-md font-semibold text-gray-800 mb-4">Graduation Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="official_email" class="block text-sm font-medium text-gray-700 mb-2">Official Email</label>
                                <input type="email" name="graduation_data[official_email]" id="official_email" value="{{ old('graduation_data.official_email', $graduationData['official_email'] ?? '') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900"
                                       placeholder="student@university.edu">
                            </div>
                            <div>
                                <label for="current_status" class="block text-sm font-medium text-gray-700 mb-2">Current Status</label>
                                <input type="text" name="graduation_data[current_status]" id="current_status" value="{{ old('graduation_data.current_status', $graduationData['current_status'] ?? '') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                            </div>
                            <div>
                                <label for="class_semester" class="block text-sm font-medium text-gray-700 mb-2">Class/Semester</label>
                                <input type="text" name="graduation_data[class_semester]" id="class_semester" value="{{ old('graduation_data.class_semester', $graduationData['class_semester'] ?? '') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                            </div>
                            <div>
                                <label for="form_no" class="block text-sm font-medium text-gray-700 mb-2">Form Number</label>
                                <input type="text" name="graduation_data[form_no]" id="form_no" value="{{ old('graduation_data.form_no', $graduationData['form_no'] ?? '') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Parents Information Tab --}}
            <div x-show="activeTab === 'parents'" class="bg-white shadow-sm rounded-lg p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b-2 border-gray-100">
                    Parents Information
                </h2>
                
                {{-- Father Information --}}
                <div class="mb-8">
                    <h3 class="text-md font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Father's Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <input type="text" name="father_data[name]" value="{{ old('father_data.name', $fatherData['name'] ?? '') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Birth City</label>
                            <input type="text" name="father_data[birth_city]" value="{{ old('father_data.birth_city', $fatherData['birth_city'] ?? '') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Birthday</label>
                            <input type="date" name="father_data[birthday]" value="{{ old('father_data.birthday', $fatherData['birthday'] ?? '') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Religion</label>
                            <input type="text" name="father_data[religion]" value="{{ old('father_data.religion', $fatherData['religion'] ?? '') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Citizenship</label>
                            <input type="text" name="father_data[citizenship]" value="{{ old('father_data.citizenship', $fatherData['citizenship'] ?? 'Indonesia') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ID Number</label>
                            <input type="text" name="father_data[citizenship_no]" value="{{ old('father_data.citizenship_no', $fatherData['citizenship_no'] ?? '') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                            <textarea name="father_data[address]" rows="2"
                                      class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">{{ old('father_data.address', $fatherData['address'] ?? '') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input type="tel" name="father_data[phone]" value="{{ old('father_data.phone', $fatherData['phone'] ?? '') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mobile</label>
                            <input type="tel" name="father_data[mobile]" value="{{ old('father_data.mobile', $fatherData['mobile'] ?? '') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="father_data[email]" value="{{ old('father_data.email', $fatherData['email'] ?? '') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Education Level</label>
                            <input type="text" name="father_data[education]" value="{{ old('father_data.education', $fatherData['education'] ?? '') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Profession</label>
                            <input type="text" name="father_data[profession]" value="{{ old('father_data.profession', $fatherData['profession'] ?? '') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Business Name</label>
                            <input type="text" name="father_data[business_name]" value="{{ old('father_data.business_name', $fatherData['business_name'] ?? '') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                    </div>
                </div>

                {{-- Mother Information --}}
                <div>
                    <h3 class="text-md font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Mother's Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <input type="text" name="mother_data[name]" value="{{ old('mother_data.name', $motherData['name'] ?? '') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Birth City</label>
                            <input type="text" name="mother_data[birth_city]" value="{{ old('mother_data.birth_city', $motherData['birth_city'] ?? '') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Birthday</label>
                            <input type="date" name="mother_data[birthday]" value="{{ old('mother_data.birthday', $motherData['birthday'] ?? '') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Religion</label>
                            <input type="text" name="mother_data[religion]" value="{{ old('mother_data.religion', $motherData['religion'] ?? '') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Citizenship</label>
                            <input type="text" name="mother_data[citizenship]" value="{{ old('mother_data.citizenship', $motherData['citizenship'] ?? 'Indonesia') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ID Number</label>
                            <input type="text" name="mother_data[citizenship_no]" value="{{ old('mother_data.citizenship_no', $motherData['citizenship_no'] ?? '') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                            <textarea name="mother_data[address]" rows="2"
                                      class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">{{ old('mother_data.address', $motherData['address'] ?? '') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input type="tel" name="mother_data[phone]" value="{{ old('mother_data.phone', $motherData['phone'] ?? '') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mobile</label>
                            <input type="tel" name="mother_data[mobile]" value="{{ old('mother_data.mobile', $motherData['mobile'] ?? '') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="mother_data[email]" value="{{ old('mother_data.email', $motherData['email'] ?? '') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Education Level</label>
                            <input type="text" name="mother_data[education]" value="{{ old('mother_data.education', $motherData['education'] ?? '') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Profession</label>
                            <input type="text" name="mother_data[profession]" value="{{ old('mother_data.profession', $motherData['profession'] ?? '') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Business Name</label>
                            <input type="text" name="mother_data[business_name]" value="{{ old('mother_data.business_name', $motherData['business_name'] ?? '') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-soft-gray-900 focus:border-soft-gray-900">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-between pb-6">
                <a href="/users" 
                   class="inline-flex items-center px-6 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium transition duration-150">
                    <i class="bi bi-x-lg me-2"></i>
                    Cancel
                </a>
                                @if(auth()->id() !== $userToEdit->id)
                    <button type="button" 
                            onclick="if(confirm('Are you sure you want to delete this user?')) document.getElementById('delete-form').submit();"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition duration-200">
                        <i class="bi bi-trash me-2"></i>
                        Delete User
                    </button>
                @endif

                <button type='submit' 
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-soft-gray-900 hover:bg-soft-gray-800 text-white font-semibold rounded-xl shadow-md hover:shadow-lg transition-all duration-200">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    Update User
                </button>
            </div>
        </form>
    </div>
    </div>
        @if(auth()->id() !== $userToEdit->id)
            <form id="delete-form" action="{{ route('users.destroy', $userToEdit) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        @endif
</x-app-layout>