<x-app-layout>
    @php
        $personalData = $user->personal_data ?? [];
        $academicData = $user->academic_data ?? [];
        $fatherData = $user->father_data ?? [];
        $motherData = $user->mother_data ?? [];
        $graduationData = $user->graduation_data ?? [];
    @endphp

    <div class="max-w-[1600px] mx-auto py-8" x-data="{ activeTab: 'basic' }">
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('users.index') }}" 
                   class="group inline-flex items-center gap-2.5 px-4 py-2.5 bg-white hover:bg-gray-900 border border-gray-200 hover:border-gray-900 text-gray-700 hover:text-white rounded-xl font-medium text-sm shadow-sm hover:shadow-md transition-all duration-200">
                    <i class="bi bi-arrow-left text-base group-hover:-translate-x-0.5 transition-transform duration-200"></i>
                    <span>Back</span>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                    <p class="text-sm text-gray-600">{{ $user->email }} • {{ $user->username }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                @can('update', $user)
                    <a href="{{ route('users.edit', $user) }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white rounded-lg font-semibold text-sm shadow-sm transition duration-150">
                        <i class="bi bi-pencil me-2"></i>
                        Edit User
                    </a>
                @endcan
            </div>
        </div>


        {{-- Dossier Layout --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Left Column: Primary & Account --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Account Profile card --}}
                <div class="bg-white shadow-sm rounded-2xl overflow-hidden">
                    <div class="bg-white px-6 py-8 text-center border-b border-gray-50">
                        <div class="relative inline-block mb-4">
                            @if($user->profile_photo_url)
                                <img src="{{ filter_var($user->profile_photo_url, FILTER_VALIDATE_URL) ? $user->profile_photo_url : Storage::url($user->profile_photo_url) }}" 
                                     class="w-32 h-32 object-cover rounded-full border-4 border-gray-50 shadow-sm" alt="photo">
                            @else
                                <div class="w-24 h-24 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center text-3xl font-bold border-2 border-white shadow-sm mx-auto">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            @endif
                            <span class="absolute bottom-1 right-1 w-6 h-6 rounded-full border-2 border-white {{ $user->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-center py-2 border-b border-gray-50">
                            <span class="text-sm text-gray-500">Username</span>
                            <span class="text-sm font-medium text-gray-900">{{ $user->username }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-50">
                            <span class="text-sm text-gray-500">Email</span>
                            <span class="text-sm font-medium text-gray-900">{{ $user->email }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-2">
                            <span class="text-sm text-gray-500">Official Email</span>
                            <span class="text-sm font-medium text-gray-900">{{ $graduationData['official_email'] ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Quick Socials --}}
                <div class="bg-white shadow-sm rounded-2xl p-6">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Social Network</h3>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center text-green-600">
                                <i class="bi bi-whatsapp"></i>
                            </div>
                            <span class="text-sm text-gray-700">{{ $user->whatsapp ?? '-' }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-pink-50 flex items-center justify-center text-pink-600">
                                <i class="bi bi-instagram"></i>
                            </div>
                            <span class="text-sm text-gray-700">{{ $personalData['instagram'] ?? '-' }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                                <i class="bi bi-facebook"></i>
                            </div>
                            <span class="text-sm text-gray-700">{{ $personalData['facebook'] ?? '-' }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-sky-50 flex items-center justify-center text-sky-600">
                                <i class="bi bi-twitter-x"></i>
                            </div>
                            <span class="text-sm text-gray-700">{{ $personalData['twitter'] ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Center & Right Columns: Navigation & Detailed Content --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Detail Content Section --}}
                <div class="bg-white shadow-sm rounded-2xl overflow-hidden min-h-[600px]">
                    <div class="border-b border-gray-100">
                        <nav class="flex px-4 overflow-x-auto">
                            <button @click="activeTab = 'basic'" :class="activeTab === 'basic' ? 'border-b-2 border-gray-900 text-gray-900 font-bold' : 'text-gray-500'" class="whitespace-nowrap px-6 py-4 text-sm tracking-tight transition-all uppercase">Identity</button>
                            <button @click="activeTab = 'personal'" :class="activeTab === 'personal' ? 'border-b-2 border-gray-900 text-gray-900 font-bold' : 'text-gray-500'" class="whitespace-nowrap px-6 py-4 text-sm tracking-tight transition-all uppercase">Contact</button>
                            <button @click="activeTab = 'academic'" :class="activeTab === 'academic' ? 'border-b-2 border-gray-900 text-gray-900 font-bold' : 'text-gray-500'" class="whitespace-nowrap px-6 py-4 text-sm tracking-tight transition-all uppercase">Academic</button>
                            <button @click="activeTab = 'parents'" :class="activeTab === 'parents' ? 'border-b-2 border-gray-900 text-gray-900 font-bold' : 'text-gray-500'" class="whitespace-nowrap px-6 py-4 text-sm tracking-tight transition-all uppercase">Parents</button>
                            <button @click="activeTab = 'business'" :class="activeTab === 'business' ? 'border-b-2 border-gray-900 text-gray-900 font-bold' : 'text-gray-500'" class="whitespace-nowrap px-6 py-4 text-sm tracking-tight transition-all uppercase">Business</button>
                            <button @click="activeTab = 'security'" :class="activeTab === 'security' ? 'border-b-2 border-gray-900 text-gray-900 font-bold' : 'text-gray-500'" class="whitespace-nowrap px-6 py-4 text-sm tracking-tight transition-all uppercase">Security</button>
                        </nav>
                    </div>

                    <div class="p-8">
                        {{-- Identity Tab --}}
                        <div x-show="activeTab === 'basic'" class="space-y-10 animate-fade-in">
                            <section>
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6">Government & Institutional Identity</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">NIS (Student ID)</p>
                                        <p class="text-sm font-bold text-gray-900">{{ $user->NIS ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">NISN</p>
                                        <p class="text-sm font-bold text-gray-900">{{ $academicData['nisn'] ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Prodi / Sub-Prodi</p>
                                        <p class="text-sm font-bold text-gray-900">{{ $academicData['prodi'] ?? '-' }} / {{ $academicData['sub_prodi'] ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Identity No (KTP/NIK)</p>
                                        <p class="text-sm font-bold text-gray-900">{{ $personalData['citizenship_no'] ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Passport No</p>
                                        <p class="text-sm font-bold text-gray-900">{{ $personalData['passport_no'] ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Citizenship</p>
                                        <p class="text-sm font-bold text-gray-900">{{ $personalData['citizenship'] ?? '-' }}</p>
                                    </div>
                                </div>
                            </section>

                            <section>
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6">Demographics</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Gender</p>
                                        <p class="text-sm font-bold text-gray-900">{{ $personalData['gender'] ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Birth Date & City</p>
                                        <p class="text-sm font-bold text-gray-900">{{ $user->birth_date instanceof \Carbon\Carbon ? $user->birth_date->format('d M Y') : ($user->birth_date ?? '-') }} ({{ $user->birth_city ?? '-' }})</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Religion</p>
                                        <p class="text-sm font-bold text-gray-900">{{ $user->religion ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Special Needs</p>
                                        <p class="text-sm font-bold text-gray-900">{{ $personalData['special_need'] ?? 'None' }}</p>
                                    </div>
                                </div>
                            </section>
                        </div>

                        {{-- Contact Tab --}}
                        <div x-show="activeTab === 'personal'" class="space-y-10 animate-fade-in">
                            <section>
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6">Primary Residential Address</h4>
                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Street Address</p>
                                        <p class="text-sm font-bold text-gray-900">{{ $personalData['address'] ?? '-' }}</p>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                        <div>
                                            <p class="text-xs text-gray-500 mb-1">City</p>
                                            <p class="text-sm font-bold text-gray-900">{{ $personalData['address_city'] ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 mb-1">Province</p>
                                            <p class="text-sm font-bold text-gray-900">{{ $personalData['province'] ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 mb-1">Zip Code</p>
                                            <p class="text-sm font-bold text-gray-900">{{ $personalData['zip_code'] ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <section>
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6 border-t pt-8 mt-4">Secondary Address (Home)</h4>
                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Street Address 2</p>
                                        <p class="text-sm font-bold text-gray-900">{{ $personalData['address2'] ?? '-' }}</p>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                        <div>
                                            <p class="text-xs text-gray-500 mb-1">City</p>
                                            <p class="text-sm font-bold text-gray-900">{{ $personalData['address_city2'] ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 mb-1">Province</p>
                                            <p class="text-sm font-bold text-gray-900">{{ $personalData['province2'] ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 mb-1">Zip Code 2</p>
                                            <p class="text-sm font-bold text-gray-900">{{ $personalData['zip_code2'] ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>

                        {{-- Academic Tab --}}
                        <div x-show="activeTab === 'academic'" class="space-y-10 animate-fade-in">
                            <section>
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6">Current Academic Status</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                                    <div class="bg-gray-50 p-4 rounded-xl">
                                        <p class="text-xs text-gray-500 mb-1 uppercase tracking-tighter">CGPA / IPK</p>
                                        <p class="text-2xl font-black text-gray-900">{{ $user->CGPA ?? '0.00' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Education Level</p>
                                        <p class="text-sm font-bold text-gray-900">{{ $academicData['edu_level'] ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Status</p>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $user->Is_Graduate ? 'bg-indigo-100 text-indigo-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $user->Is_Graduate ? 'Alumni' : 'Active Student' }}
                                        </span>
                                    </div>
                                </div>
                            </section>

                            <section>
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6 border-t pt-8 mt-4">Graduation & Achievements</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Predicate</p>
                                        <p class="text-sm font-bold text-indigo-600">{{ $graduationData['predicate'] ?? 'Not Evaluated' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Judicium Date</p>
                                        <p class="text-sm font-bold text-gray-900">{{ $graduationData['judicium_date'] ?? '-' }}</p>
                                    </div>
                                    <div class="md:col-span-2">
                                        <p class="text-xs text-gray-500 mb-1">Thesis Title (IDN)</p>
                                        <p class="text-sm italic text-gray-800 font-medium">"{{ $graduationData['final_project_indonesia'] ?? 'Not Recorded' }}"</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Academic Advisor</p>
                                        <p class="text-sm font-bold text-gray-900">{{ $academicData['academic_advisor'] ?? '-' }}</p>
                                    </div>
                                </div>
                            </section>

                            <section>
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6 border-t pt-8 mt-4">Professional Certifications</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="border border-gray-100 p-4 rounded-xl">
                                        <p class="text-[10px] text-gray-400 font-bold uppercase mb-2">Certificate #1</p>
                                        <p class="text-sm font-bold text-gray-900">{{ $academicData['certificate_no_1'] ?? '-' }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $academicData['certificate_date_1'] ?? '' }}</p>
                                    </div>
                                    <div class="border border-gray-100 p-4 rounded-xl">
                                        <p class="text-[10px] text-gray-400 font-bold uppercase mb-2">Certificate #2</p>
                                        <p class="text-sm font-bold text-gray-900">{{ $academicData['certificate_no_2'] ?? '-' }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $academicData['certificate_date_2'] ?? '' }}</p>
                                    </div>
                                </div>
                            </section>
                        </div>

                        {{-- Parents Tab --}}
                        <div x-show="activeTab === 'parents'" class="space-y-12 animate-fade-in">
                            {{-- Father Section --}}
                            <section>
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center font-bold">F</div>
                                    <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Father's Detailed Profile</h4>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 ml-13">
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Full Name</p>
                                        <p class="text-sm font-bold text-gray-900">{{ $fatherData['name'] ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">ID (NPWP / Passport)</p>
                                        <p class="text-sm font-bold text-gray-900">{{ $fatherData['npwp_no'] ?? '-' }} / {{ $fatherData['passport_no'] ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">BPJS No</p>
                                        <p class="text-sm font-bold text-gray-900">{{ $fatherData['bpjs_no'] ?? '-' }}</p>
                                    </div>
                                    <div class="bg-gray-50 p-3 rounded-lg col-span-1 md:col-span-2">
                                        <p class="text-xs text-gray-500 mb-1">Professional / Business</p>
                                        <p class="text-sm font-bold text-gray-900">{{ $fatherData['business_name'] ?? '-' }} ({{ $fatherData['business_title'] ?? 'Owner' }})</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $fatherData['business_line'] ?? '' }} • {{ $fatherData['business_revenue'] ?? '' }}</p>
                                    </div>
                                </div>
                            </section>

                            {{-- Mother Section --}}
                            <section class="border-t pt-10">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-10 h-10 bg-pink-50 text-pink-600 rounded-full flex items-center justify-center font-bold">M</div>
                                    <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Mother's Detailed Profile</h4>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 ml-13">
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Full Name</p>
                                        <p class="text-sm font-bold text-gray-900">{{ $motherData['name'] ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">ID (NPWP / Passport)</p>
                                        <p class="text-sm font-bold text-gray-900">{{ $motherData['npwp_no'] ?? '-' }} / {{ $motherData['passport_no'] ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Profession</p>
                                        <p class="text-sm font-bold text-gray-900">{{ $motherData['profession'] ?? '-' }}</p>
                                    </div>
                                    <div class="bg-gray-50 p-3 rounded-lg col-span-1 md:col-span-2">
                                        <p class="text-xs text-gray-500 mb-1">Business Name</p>
                                        <p class="text-sm font-bold text-gray-900">{{ $motherData['business_name'] ?? '-' }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $motherData['business_line'] ?? '' }}</p>
                                    </div>
                                </div>
                            </section>
                        </div>

                        {{-- Business Tab --}}
                        <div x-show="activeTab === 'business'" class="space-y-8 animate-fade-in">
                            <section>
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6">Platform Involvement</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="p-6 border-2 border-dashed border-gray-100 rounded-2xl">
                                        <p class="text-xs text-gray-500 mb-2 uppercase font-black">Owned Businesses</p>
                                        <div class="space-y-4">
                                            @forelse($user->businesses as $b)
                                                <a href="{{ route('businesses.show', $b) }}" 
                                                   class="flex items-center justify-between p-4 bg-white border border-gray-100 rounded-xl hover:shadow-md transition-shadow group">
                                                    <div>
                                                        <p class="text-sm font-bold text-gray-900 group-hover:text-soft-gray-900">{{ $b->name }}</p>
                                                        <p class="text-xs text-gray-500">{{ $b->businessType->name ?? 'General' }}</p>
                                                    </div>
                                                    <i class="bi bi-chevron-right text-gray-300"></i>
                                                </a>
                                            @empty
                                                <p class="text-sm text-gray-400 italic">No business registered as owner.</p>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>

                        {{-- Security Tab --}}
                        <div x-show="activeTab === 'security'" class="space-y-8 animate-fade-in">
                            <section>
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6">Account Security & Access</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="p-6 border border-gray-100 rounded-2xl bg-gray-50">
                                        <p class="text-xs text-gray-500 mb-2 uppercase font-black">Account Status</p>
                                        <div class="flex items-center gap-3">
                                            <span class="w-3 h-3 rounded-full {{ $user->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                            <p class="text-sm font-bold text-gray-900">{{ $user->is_active ? 'Active Account' : 'Suspended / Inactive' }}</p>
                                        </div>
                                    </div>
                                    <div class="p-6 border border-gray-100 rounded-2xl bg-gray-50">
                                        <p class="text-xs text-gray-500 mb-2 uppercase font-black">Role & Permissions</p>
                                        <p class="text-sm font-bold text-gray-900 capitalize">{{ $user->role }} Access Level</p>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>