<x-app-layout>
    {{-- ======================================== USERS CREATE ======================================== --}}
    <div class="max-w-3xl mx-auto">
        <div class="mb-6 flex items-center gap-3">
            <a href="{{ route('users.index') }}" class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold">Create User</h1>
                <p class="text-sm text-gray-500">Essential fields only — advanced fields are managed separately.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('users.store') }}" class="space-y-6 bg-white border rounded-lg p-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-700">Username <span class="text-red-500">*</span></label>
                    <input name="username" value="{{ old('username') }}" required class="mt-1 block w-full border rounded px-3 py-2" />
                    @error('username')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Full name <span class="text-red-500">*</span></label>
                    <input name="name" value="{{ old('name') }}" required class="mt-1 block w-full border rounded px-3 py-2" />
                    @error('name')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 block w-full border rounded px-3 py-2" />
                    @error('email')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Role</label>
                    <select name="role" class="mt-1 block w-full border rounded px-3 py-2">
                        <option value="student">Student</option>
                        <option value="alumni">Alumni</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required class="mt-1 block w-full border rounded px-3 py-2" />
                    @error('password')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Active</label>
                    <select name="is_active" class="mt-1 block w-full border rounded px-3 py-2">
                        <option value="1" selected>Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-between mt-4">
                <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-200 rounded">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded">Create User</button>
            </div>
        </form>
    </div>
                            <div>
                                <label for="twitter" class="block text-sm font-medium text-gray-700 mb-2">Twitter</label>
                                <input type="text" name="personal_data[twitter]" id="twitter" value="{{ old('personal_data.twitter') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                            </div>
                            <div>
                                <label for="instagram" class="block text-sm font-medium text-gray-700 mb-2">Instagram</label>
                                <input type="text" name="personal_data[instagram]" id="instagram" value="{{ old('personal_data.instagram') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Academic Information Tab --}}
            <div x-show="activeTab === 'academic'" class="bg-white shadow-sm rounded-lg p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-mortarboard text-orange-500"></i>
                    Academic Information
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- NIS --}}
                    <div>
                        <label for="NIS" class="block text-sm font-medium text-gray-700 mb-2">NIS</label>
                        <input type="text" name="NIS" id="NIS" value="{{ old('NIS') }}"
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    </div>

                    {{-- NISN --}}
                    <div>
                        <label for="nisn" class="block text-sm font-medium text-gray-700 mb-2">NISN</label>
                        <input type="text" name="academic_data[nisn]" id="nisn" value="{{ old('academic_data.nisn') }}"
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    </div>

                    {{-- Student Year --}}
                    <div>
                        <label for="Student_Year" class="block text-sm font-medium text-gray-700 mb-2">Student Year</label>
                        <input type="text" name="Student_Year" id="Student_Year" value="{{ old('Student_Year') }}"
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                               placeholder="2023">
                    </div>

                    {{-- Major --}}
                    <div>
                        <label for="Major" class="block text-sm font-medium text-gray-700 mb-2">Major</label>
                        <input type="text" name="Major" id="Major" value="{{ old('Major') }}"
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    </div>

                    {{-- Program (Prodi) --}}
                    <div>
                        <label for="prodi" class="block text-sm font-medium text-gray-700 mb-2">Program (Prodi)</label>
                        <input type="text" name="academic_data[prodi]" id="prodi" value="{{ old('academic_data.prodi') }}"
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    </div>

                    {{-- Education Level --}}
                    <div>
                        <label for="edu_level" class="block text-sm font-medium text-gray-700 mb-2">Education Level</label>
                        <select name="academic_data[edu_level]" id="edu_level"
                                class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
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
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    </div>

                    {{-- CGPA --}}
                    <div>
                        <label for="CGPA" class="block text-sm font-medium text-gray-700 mb-2">CGPA</label>
                        <input type="number" step="0.01" name="CGPA" id="CGPA" value="{{ old('CGPA') }}"
                               class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                               placeholder="3.85">
                    </div>

                    {{-- Is Graduate --}}
                    <div class="md:col-span-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="Is_Graduate" value="1" {{ old('Is_Graduate') ? 'checked' : '' }}
                                   class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
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
                                       class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                            </div>
                            <div>
                                <label for="school_city" class="block text-sm font-medium text-gray-700 mb-2">School City</label>
                                <input type="text" name="academic_data[school_city]" id="school_city" value="{{ old('academic_data.school_city') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                            </div>
                            <div>
                                <label for="start_year" class="block text-sm font-medium text-gray-700 mb-2">Start Year</label>
                                <input type="text" name="academic_data[start_year]" id="start_year" value="{{ old('academic_data.start_year') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                            </div>
                            <div>
                                <label for="end_year" class="block text-sm font-medium text-gray-700 mb-2">End Year</label>
                                <input type="text" name="academic_data[end_year]" id="end_year" value="{{ old('academic_data.end_year') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                            </div>
                        </div>
                    </div>

                    {{-- Graduation Data Section --}}
                    <div class="md:col-span-2 border-t pt-6 mt-4">
                        <h3 class="text-md font-semibold text-gray-800 mb-4">Graduation Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="official_email" class="block text-sm font-medium text-gray-700 mb-2">Official Email</label>
                                <input type="email" name="graduation_data[official_email]" id="official_email" value="{{ old('graduation_data.official_email') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                       placeholder="student@university.edu">
                            </div>
                            <div>
                                <label for="current_status" class="block text-sm font-medium text-gray-700 mb-2">Current Status</label>
                                <input type="text" name="graduation_data[current_status]" id="current_status" value="{{ old('graduation_data.current_status') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                            </div>
                            <div>
                                <label for="class_semester" class="block text-sm font-medium text-gray-700 mb-2">Class/Semester</label>
                                <input type="text" name="graduation_data[class_semester]" id="class_semester" value="{{ old('graduation_data.class_semester') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                            </div>
                            <div>
                                <label for="form_no" class="block text-sm font-medium text-gray-700 mb-2">Form Number</label>
                                <input type="text" name="graduation_data[form_no]" id="form_no" value="{{ old('graduation_data.form_no') }}"
                                       class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Parents Information Tab --}}
            <div x-show="activeTab === 'parents'" class="bg-white shadow-sm rounded-lg p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-people text-orange-500"></i>
                    Parents Information
                </h2>
                
                {{-- Father Information --}}
                <div class="mb-8">
                    <h3 class="text-md font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Father's Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <input type="text" name="father_data[name]" value="{{ old('father_data.name') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Birth City</label>
                            <input type="text" name="father_data[birth_city]" value="{{ old('father_data.birth_city') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Birthday</label>
                            <input type="date" name="father_data[birthday]" value="{{ old('father_data.birthday') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Religion</label>
                            <input type="text" name="father_data[religion]" value="{{ old('father_data.religion') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Citizenship</label>
                            <input type="text" name="father_data[citizenship]" value="{{ old('father_data.citizenship', 'Indonesia') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ID Number</label>
                            <input type="text" name="father_data[citizenship_no]" value="{{ old('father_data.citizenship_no') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                            <textarea name="father_data[address]" rows="2"
                                      class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">{{ old('father_data.address') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input type="tel" name="father_data[phone]" value="{{ old('father_data.phone') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mobile</label>
                            <input type="tel" name="father_data[mobile]" value="{{ old('father_data.mobile') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="father_data[email]" value="{{ old('father_data.email') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Education Level</label>
                            <input type="text" name="father_data[education]" value="{{ old('father_data.education') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Profession</label>
                            <input type="text" name="father_data[profession]" value="{{ old('father_data.profession') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Business Name</label>
                            <input type="text" name="father_data[business_name]" value="{{ old('father_data.business_name') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
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
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Birth City</label>
                            <input type="text" name="mother_data[birth_city]" value="{{ old('mother_data.birth_city') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Birthday</label>
                            <input type="date" name="mother_data[birthday]" value="{{ old('mother_data.birthday') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Religion</label>
                            <input type="text" name="mother_data[religion]" value="{{ old('mother_data.religion') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Citizenship</label>
                            <input type="text" name="mother_data[citizenship]" value="{{ old('mother_data.citizenship', 'Indonesia') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ID Number</label>
                            <input type="text" name="mother_data[citizenship_no]" value="{{ old('mother_data.citizenship_no') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                            <textarea name="mother_data[address]" rows="2"
                                      class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">{{ old('mother_data.address') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input type="tel" name="mother_data[phone]" value="{{ old('mother_data.phone') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mobile</label>
                            <input type="tel" name="mother_data[mobile]" value="{{ old('mother_data.mobile') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="mother_data[email]" value="{{ old('mother_data.email') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Education Level</label>
                            <input type="text" name="mother_data[education]" value="{{ old('mother_data.education') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Profession</label>
                            <input type="text" name="mother_data[profession]" value="{{ old('mother_data.profession') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Business Name</label>
                            <input type="text" name="mother_data[business_name]" value="{{ old('mother_data.business_name') }}"
                                   class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Business Assignment Tab --}}
            <div x-show="activeTab === 'business'" class="bg-white shadow-sm rounded-lg p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-briefcase text-orange-500"></i>
                    Business Assignment
                </h2>

                @if($availableBusinesses->count() > 0)
                    {{-- Business Ownership Transfer --}}
                    <div class="mb-8">
                        <h3 class="text-md font-semibold text-gray-800 mb-4">Transfer Business Ownership</h3>
                        <p class="text-sm text-gray-600 mb-4">Select businesses to transfer ownership to this user. This changes the <code class="bg-gray-100 px-1 rounded">user_id</code> of the business.</p>
                        
                        <div class="space-y-2 max-h-64 overflow-y-auto border border-gray-200 rounded-lg p-4">
                            @foreach($availableBusinesses as $business)
                                <label class="flex items-start gap-3 p-3 hover:bg-gray-50 rounded-lg cursor-pointer transition">
                                    <input type="checkbox" name="owned_businesses[]" value="{{ $business->id }}"
                                           class="mt-1 w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900">{{ $business->name }}</p>
                                        <p class="text-xs text-gray-500">Owner: {{ $business->user->name }} • Type: {{ $business->businessType->name ?? 'N/A' }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Team Member Assignment --}}
                    <div>
                        <h3 class="text-md font-semibold text-gray-800 mb-4">Add as Team Member</h3>
                        <p class="text-sm text-gray-600 mb-4">Add this user to businesses as employee/partner without changing ownership (uses <code class="bg-gray-100 px-1 rounded">user_businesses_details</code>).</p>
                        
                        <div class="space-y-3" id="team-assignments">
                            @foreach($availableBusinesses->take(5) as $index => $business)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center gap-3 mb-3">
                                        <input type="checkbox" name="team_member[{{ $index }}][enabled]" value="1"
                                               class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500"
                                               onchange="document.getElementById('team-details-{{ $index }}').classList.toggle('hidden')">
                                        <label class="text-sm font-semibold text-gray-900">{{ $business->name }}</label>
                                    </div>

                                    <div id="team-details-{{ $index }}" class="hidden grid grid-cols-2 gap-3 ml-7">
                                        <input type="hidden" name="team_member[{{ $index }}][business_id]" value="{{ $business->id }}">
                                        
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Role</label>
                                            <select name="team_member[{{ $index }}][role_type]" 
                                                    class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg">
                                                <option value="employee">Employee</option>
                                                <option value="co_founder">Co-Founder</option>
                                                <option value="partner">Partner</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Position</label>
                                            <input type="text" name="team_member[{{ $index }}][Position_name]" 
                                                   class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Start Date</label>
                                            <input type="date" name="team_member[{{ $index }}][Working_Date]" 
                                                   class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg">
                                        </div>

                                        <div class="flex items-end">
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="checkbox" name="team_member[{{ $index }}][is_current]" value="1" checked
                                                       class="w-4 h-4 text-green-600 border-gray-300 rounded">
                                                <span class="text-xs font-medium text-gray-700">Currently Active</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="bi bi-briefcase text-gray-300 text-5xl mb-3"></i>
                        <p class="text-gray-500">No businesses available for assignment</p>
                    </div>
                @endif
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-between pb-6">
                <a href="/users" 
                   class="inline-flex items-center px-6 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium transition duration-150">
                    <i class="bi bi-x-lg me-2"></i>
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-orange-500 to-yellow-500 hover:from-orange-600 hover:to-yellow-600 text-white font-bold rounded-lg shadow-sm transition duration-150">
                    <i class="bi bi-person-plus-fill me-2"></i>
                    Create User
                </button>
            </div>
        </form>
    </div>
</x-app-layout>