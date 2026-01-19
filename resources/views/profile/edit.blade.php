@use('Illuminate\Support\Facades\Storage')

<x-app-layout>
    <div class="max-w-6xl mx-auto py-8" x-data="{ activeTab: 'basic' }">
        
        {{-- Success/Error Messages --}}
        @if(session('status') === 'profile-updated')
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-xl shadow-sm flex items-start gap-3">
                <i class="bi bi-check-circle-fill text-green-600 text-xl flex-shrink-0 mt-0.5"></i>
                <div class="flex-1">
                    <p class="font-semibold">Success!</p>
                    <p class="text-sm">Your profile has been updated successfully.</p>
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
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Edit My Profile</h1>
            <p class="text-sm text-gray-600">Manage your personal information and account settings</p>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            {{-- Tab Navigation --}}
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
                    <button type="button" @click="activeTab = 'security'" 
                            :class="activeTab === 'security' ? 'border-b-2 border-gray-900 text-gray-900 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                            class="flex-1 py-4 px-4 text-sm text-center transition-all duration-200">
                        Security
                    </button>
                </nav>
            </div>

            {{-- Tab Content --}}

            {{-- Basic Information Tab --}}
            <div x-show="activeTab === 'basic'" class="bg-white shadow-sm rounded-lg p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b-2 border-gray-100">
                    Basic Information
                </h2>
                
                {{-- Profile Photo --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Profile Photo</label>
                    <div class="flex items-center gap-6">
                        <div class="relative">
                            @php
                                $profilePhoto = $user->profile_photo_url ?? null;
                                $profilePhotoUrl = $profilePhoto ? (storage_image_url($profilePhoto, ['width' => 256, 'height' => 256, 'crop' => 'thumb', 'quality' => 'auto', 'fetch_format' => 'auto']) . '?t=' . ($user->updated_at?->timestamp ?? time())) : null;
                            @endphp
                            @if($profilePhotoUrl)
                                <img id="profile-photo-preview" 
                                     src="{{ $profilePhotoUrl }}" 
                                     alt="Profile Photo" 
                                     class="w-24 h-24 rounded-full object-cover border-4 border-gray-200 shadow-md">
                            @else
                                <div id="profile-photo-preview" class="w-24 h-24 rounded-full bg-gradient-to-br from-uco-orange to-uco-yellow flex items-center justify-center border-4 border-gray-200 shadow-md">
                                    <span class="text-white text-3xl font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <label for="profile_photo" class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                                <i class="bi bi-camera"></i>
                                Choose Photo
                            </label>
                            <input id="profile_photo" name="profile_photo" type="file" accept="image/*" class="hidden" onchange="validateAndPreviewPhoto(event)">
                            <p class="text-xs text-gray-500 mt-2">JPG, PNG or GIF (Max 10MB)</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Username --}}
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                        <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}"
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-gray-900">
                    </div>

                    {{-- Full Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-gray-900">
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-gray-900">
                    </div>

                    {{-- Phone Number --}}
                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}"
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-gray-900"
                               placeholder="e.g., 081234567890">
                    </div>

                    {{-- Mobile Number --}}
                    <div>
                        <label for="mobile_number" class="block text-sm font-medium text-gray-700 mb-2">Mobile Number</label>
                        <input type="text" name="mobile_number" id="mobile_number" value="{{ old('mobile_number', $user->mobile_number) }}"
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-gray-900"
                               placeholder="e.g., 081234567890">
                    </div>

                    {{-- WhatsApp --}}
                    <div>
                        <label for="whatsapp" class="block text-sm font-medium text-gray-700 mb-2">WhatsApp</label>
                        <input type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp', $user->whatsapp) }}"
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-gray-900"
                               placeholder="e.g., 081234567890">
                    </div>
                </div>
            </div>

            {{-- Personal Information Tab --}}
            <div x-show="activeTab === 'personal'" class="bg-white shadow-sm rounded-lg p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b-2 border-gray-100">
                    Personal Information
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Birth Date --}}
                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">Birth Date</label>
                        <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $user->birth_date) }}"
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-gray-900">
                    </div>

                    {{-- Birth City --}}
                    <div>
                        <label for="birth_city" class="block text-sm font-medium text-gray-700 mb-2">Birth City</label>
                        <input type="text" name="birth_city" id="birth_city" value="{{ old('birth_city', $user->birth_city) }}"
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-gray-900"
                               placeholder="e.g., Jakarta">
                    </div>

                    {{-- Religion --}}
                    <div>
                        <label for="religion" class="block text-sm font-medium text-gray-700 mb-2">Religion</label>
                        <select name="religion" id="religion" 
                                class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-gray-900">
                            <option value="">-- Select Religion --</option>
                            <option value="Islam" {{ old('religion', $user->religion) == 'Islam' ? 'selected' : '' }}>Islam</option>
                            <option value="Kristen" {{ old('religion', $user->religion) == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                            <option value="Katolik" {{ old('religion', $user->religion) == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                            <option value="Hindu" {{ old('religion', $user->religion) == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                            <option value="Buddha" {{ old('religion', $user->religion) == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                            <option value="Konghucu" {{ old('religion', $user->religion) == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                        </select>
                    </div>

                    {{-- Current Role --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Role</label>
                        <input type="text" value="{{ ucfirst($user->role) }}" readonly
                               class="block w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed">
                        <p class="text-xs text-gray-500 mt-1">Contact admin to change your role</p>
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
                        <label for="NIS" class="block text-sm font-medium text-gray-700 mb-2">NIS (Student ID)</label>
                        <input type="text" name="NIS" id="NIS" value="{{ old('NIS', $user->NIS) }}"
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-gray-900"
                               placeholder="e.g., 2019123456">
                    </div>

                    {{-- Student Year --}}
                    <div>
                        <label for="Student_Year" class="block text-sm font-medium text-gray-700 mb-2">Student Year</label>
                        <input type="text" name="Student_Year" id="Student_Year" value="{{ old('Student_Year', $user->Student_Year) }}"
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-gray-900"
                               placeholder="e.g., 2019">
                    </div>

                    {{-- Major --}}
                    <div>
                        <label for="Major" class="block text-sm font-medium text-gray-700 mb-2">Major</label>
                        <input type="text" name="Major" id="Major" value="{{ old('Major', $user->Major) }}"
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-gray-900"
                               placeholder="e.g., Computer Science">
                    </div>

                    {{-- CGPA --}}
                    <div>
                        <label for="CGPA" class="block text-sm font-medium text-gray-700 mb-2">CGPA</label>
                        <input type="number" step="0.01" min="0" max="4" name="CGPA" id="CGPA" value="{{ old('CGPA', $user->CGPA) }}"
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-gray-900"
                               placeholder="e.g., 3.75">
                    </div>

                    {{-- Graduation Status --}}
                    <div class="md:col-span-2">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="Is_Graduate" value="1" {{ old('Is_Graduate', $user->Is_Graduate) ? 'checked' : '' }}
                                   class="w-5 h-5 text-gray-900 border-gray-300 rounded focus:ring-2 focus:ring-gray-900">
                            <span class="text-sm font-medium text-gray-700">I have graduated</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Security Tab --}}
            <div x-show="activeTab === 'security'" class="bg-white shadow-sm rounded-lg p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-6 pb-3 border-b-2 border-gray-100">
                    Security Settings
                </h2>
                
                <div class="space-y-6">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <i class="bi bi-info-circle-fill text-blue-600 text-xl mt-0.5"></i>
                            <div>
                                <p class="font-semibold text-blue-900">Change Password</p>
                                <p class="text-sm text-blue-700 mt-1">Leave password fields empty if you don't want to change your password.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Current Password --}}
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                        <input type="password" name="current_password" id="current_password" autocomplete="current-password"
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-gray-900"
                               placeholder="Enter your current password">
                        <p class="text-xs text-gray-500 mt-1">Required to change password</p>
                    </div>

                    {{-- New Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                        <input type="password" name="password" id="password" autocomplete="new-password"
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-gray-900"
                               placeholder="Enter new password">
                        <p class="text-xs text-gray-500 mt-1">Minimum 8 characters</p>
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password"
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-gray-900"
                               placeholder="Confirm new password">
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="mt-6 flex items-center justify-between bg-white shadow-sm rounded-lg p-6">
                <a href="{{ route('dashboard') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="bi bi-x-lg"></i>
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gray-900 text-white font-semibold rounded-lg hover:bg-gray-800 transition-colors shadow-md hover:shadow-lg">
                    <i class="bi bi-check-lg"></i>
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    {{-- JavaScript for Photo Preview --}}
    <script>
        function validateAndPreviewPhoto(event) {
            const file = event.target.files[0];
            const maxSize = 10 * 1024 * 1024; // 10MB
            
            if (file && file.size > maxSize) {
                alert('Profile photo must not be larger than 10MB. Please choose a smaller file.');
                event.target.value = '';
                return;
            }
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('profile-photo-preview');
                    preview.innerHTML = `<img src="${e.target.result}" alt="Profile Photo" class="w-24 h-24 rounded-full object-cover border-4 border-gray-200 shadow-md">`;
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-app-layout>
