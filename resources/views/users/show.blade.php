<x-app-layout>
    @php
        $personalData = $user->personal_data ?? [];
        $academicData = $user->academic_data ?? [];
        $fatherData = $user->father_data ?? [];
        $motherData = $user->mother_data ?? [];
        $graduationData = $user->graduation_data ?? [];
    @endphp

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8" x-data="{ activeTab: 'basic' }">
        {{-- HIG Header --}}
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-5">
                <a href="{{ route('users.index') }}" 
                   class="inline-flex items-center justify-center w-10 h-10 bg-white hover:bg-gray-50 border border-gray-100 text-gray-400 hover:text-gray-900 rounded-full transition-all duration-200 shadow-sm">
                    <i class="bi bi-chevron-left"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-black text-gray-900 tracking-tight leading-none">{{ $user->name }}</h1>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="inline-flex items-center px-2 py-0.5 bg-gray-100 text-gray-500 rounded text-[10px] font-bold uppercase tracking-wider">{{ $user->role }}</span>
                        <span class="text-gray-300">•</span>
                        <p class="text-sm font-medium text-gray-500">{{ $user->email }}</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                @can('update', $user)
                    <a href="{{ route('users.edit', $user) }}" 
                       class="inline-flex items-center px-5 py-2.5 bg-gray-900 hover:bg-black text-white rounded-xl font-bold text-sm shadow-lg shadow-gray-200 transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0">
                        <i class="bi bi-pencil-square me-2"></i>
                        Edit Profile
                    </a>
                @endcan
            </div>
        </div>


        {{-- Minimalist Dossier Layout --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            {{-- Left Column: Identity & Account --}}
            <div class="lg:col-span-1 space-y-8">
                {{-- Account Profile Minimalist --}}
                <div class="bg-white shadow-[0_20px_60px_-15px_rgba(0,0,0,0.05)] rounded-[2rem] border border-gray-100 overflow-hidden">
                    <div class="px-6 pt-10 pb-6 text-center border-b border-gray-50">
                        <div class="mb-5">
                            @if($user->profile_photo_url)
                                <img src="{{ filter_var($user->profile_photo_url, FILTER_VALIDATE_URL) ? $user->profile_photo_url : Storage::url($user->profile_photo_url) }}" 
                                     class="w-28 h-28 object-cover rounded-full border border-gray-100 shadow-sm mx-auto" alt="photo">
                            @else
                                <div class="w-28 h-28 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center text-4xl font-black border border-gray-100 mx-auto">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <h2 class="text-xl font-black text-gray-900 tracking-tight">{{ $user->name }}</h2>
                        <div class="flex items-center justify-center gap-2 mt-1">
                            <span class="w-2 h-2 rounded-full {{ $user->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $user->is_active ? 'Active' : 'Inactive' }}</span>
                        </div>
                    </div>
                    <div class="p-6 divide-y divide-gray-50">
                        <div class="py-3 flex flex-col">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Username</span>
                            <span class="text-sm font-bold text-gray-900">{{ $user->username }}</span>
                        </div>
                        <div class="py-3 flex flex-col">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Primary Email</span>
                            <span class="text-sm font-bold text-gray-900 break-all">{{ $user->email }}</span>
                        </div>
                        <div class="py-3 flex flex-col">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Official Email</span>
                            <span class="text-sm font-bold text-indigo-600 break-all">{{ $graduationData['official_email'] ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Socials List --}}
                <div class="bg-white shadow-[0_20px_60px_-15px_rgba(0,0,0,0.05)] rounded-[2rem] border border-gray-100 p-8">
                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6">Social Network</h3>
                    <div class="space-y-5">
                        <div class="flex items-center gap-4">
                            <i class="bi bi-whatsapp text-gray-400 text-lg"></i>
                            <div class="overflow-hidden">
                                <p class="text-[9px] font-bold text-gray-400 uppercase">WhatsApp</p>
                                <p class="text-sm font-black text-gray-900">{{ $user->whatsapp ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <i class="bi bi-instagram text-gray-400 text-lg"></i>
                            <div class="overflow-hidden">
                                <p class="text-[9px] font-bold text-gray-400 uppercase">Instagram</p>
                                <p class="text-sm font-black text-gray-900">{{ $personalData['instagram'] ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <i class="bi bi-facebook text-gray-400 text-lg"></i>
                            <div class="overflow-hidden">
                                <p class="text-[9px] font-bold text-gray-400 uppercase">Facebook</p>
                                <p class="text-sm font-black text-gray-900 truncate px-0.5">{{ $personalData['facebook'] ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            {{-- Center & Right Columns: Navigation & Detailed Content --}}
            <div class="lg:col-span-3 space-y-8">
                
                {{-- Detail Content Section --}}
                <div class="bg-white shadow-[0_20px_60px_-15px_rgba(0,0,0,0.05)] rounded-[2.5rem] border border-gray-100 overflow-hidden min-h-[600px]">
                    <div class="px-8 pt-8 pb-4 flex justify-start overflow-x-auto custom-scrollbar">
                        <div class="flex p-1 bg-gray-100/60 rounded-xl w-fit whitespace-nowrap">
                            <button @click="activeTab = 'basic'"
                                :class="activeTab === 'basic' ? 'bg-white text-gray-900 shadow-sm font-bold' : 'text-gray-500 hover:text-gray-700'"
                                class="px-6 py-2 text-xs font-bold uppercase tracking-widest rounded-lg transition-all duration-300 flex items-center gap-2">
                                <i class="bi bi-person-fill text-sm"></i>
                                <span>Identity</span>
                            </button>
                            <button @click="activeTab = 'personal'"
                                :class="activeTab === 'personal' ? 'bg-white text-gray-900 shadow-sm font-bold' : 'text-gray-500 hover:text-gray-700'"
                                class="px-6 py-2 text-xs font-bold uppercase tracking-widest rounded-lg transition-all duration-300 flex items-center gap-2">
                                <i class="bi bi-geo-alt-fill text-sm"></i>
                                <span>Contact</span>
                            </button>
                            <button @click="activeTab = 'academic'"
                                :class="activeTab === 'academic' ? 'bg-white text-gray-900 shadow-sm font-bold' : 'text-gray-500 hover:text-gray-700'"
                                class="px-6 py-2 text-xs font-bold uppercase tracking-widest rounded-lg transition-all duration-300 flex items-center gap-2">
                                <i class="bi bi-mortarboard-fill text-sm"></i>
                                <span>Academic</span>
                            </button>
                            <button @click="activeTab = 'parents'"
                                :class="activeTab === 'parents' ? 'bg-white text-gray-900 shadow-sm font-bold' : 'text-gray-500 hover:text-gray-700'"
                                class="px-6 py-2 text-xs font-bold uppercase tracking-widest rounded-lg transition-all duration-300 flex items-center gap-2">
                                <i class="bi bi-people-fill text-sm"></i>
                                <span>Parents</span>
                            </button>
                            <button @click="activeTab = 'business'"
                                :class="activeTab === 'business' ? 'bg-white text-gray-900 shadow-sm font-bold' : 'text-gray-500 hover:text-gray-700'"
                                class="px-6 py-2 text-xs font-bold uppercase tracking-widest rounded-lg transition-all duration-300 flex items-center gap-2">
                                <i class="bi bi-briefcase-fill text-sm"></i>
                                <span>Business</span>
                            </button>
                        </div>
                    </div>

                    <div class="p-8">
                        {{-- Identity Tab --}}
                        <div x-show="activeTab === 'basic'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="divide-y divide-gray-50 -mt-2">
                            <div class="py-5">
                                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <i class="bi bi-person-badge"></i>
                                    Institutional Identity
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Student ID (NIS)</span>
                                        <span class="text-sm font-black text-gray-900">{{ $user->NIS ?? '-' }}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">NISN</span>
                                        <span class="text-sm font-black text-gray-900">{{ $academicData['nisn'] ?? '-' }}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Citizenship</span>
                                        <span class="text-sm font-black text-gray-900">{{ $personalData['citizenship'] ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="py-5">
                                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <i class="bi bi-card-text"></i>
                                    Legal Identity
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Identity No (KTP/NIK)</span>
                                        <span class="text-sm font-black text-gray-900">{{ $personalData['citizenship_no'] ?? '-' }}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Passport No</span>
                                        <span class="text-sm font-black text-gray-900">{{ $personalData['passport_no'] ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="py-5">
                                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <i class="bi bi-info-circle"></i>
                                    Demographics
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Gender</span>
                                        <span class="text-sm font-black text-gray-900">{{ $personalData['gender'] ?? '-' }}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Birth Date & City</span>
                                        <span class="text-sm font-black text-gray-900">{{ $user->birth_date instanceof \Carbon\Carbon ? $user->birth_date->format('d M Y') : ($user->birth_date ?? '-') }} ({{ $user->birth_city ?? '-' }})</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Religion</span>
                                        <span class="text-sm font-black text-gray-900">{{ $user->religion ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Contact Tab --}}
                        <div x-show="activeTab === 'personal'" style="display: none;" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="divide-y divide-gray-50 -mt-2">
                            <div class="py-5">
                                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <i class="bi bi-house"></i>
                                    Primary Residential Address
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                                    <div class="flex flex-col md:col-span-2">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Street Address</span>
                                        <span class="text-sm font-black text-gray-900 mb-1">{{ $personalData['address'] ?? '-' }}</span>
                                        <p class="text-xs text-gray-500 font-bold uppercase tracking-tight">{{ $personalData['province'] ?? '-' }}, {{ $personalData['address_city'] ?? '-' }} ({{ $personalData['zip_code'] ?? '-' }})</p>
                                    </div>
                                </div>
                            </div>

                            <div class="py-5">
                                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <i class="bi bi-buildings"></i>
                                    Secondary Address (Home)
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                                    <div class="flex flex-col md:col-span-2">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Street Address 2</span>
                                        <span class="text-sm font-black text-gray-900 mb-1">{{ $personalData['address2'] ?? '-' }}</span>
                                        <p class="text-xs text-gray-500 font-bold uppercase tracking-tight">{{ $personalData['province2'] ?? '-' }}, {{ $personalData['address_city2'] ?? '-' }} ({{ $personalData['zip_code2'] ?? '-' }})</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Academic Tab --}}
                        <div x-show="activeTab === 'academic'" style="display: none;" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="divide-y divide-gray-50 -mt-2">
                            <div class="py-6 flex flex-col md:flex-row md:items-center justify-between gap-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 bg-gray-900 text-white rounded-2xl flex flex-col items-center justify-center shadow-lg shadow-gray-200">
                                        <span class="text-[10px] font-black uppercase leading-none mb-0.5">IPK</span>
                                        <span class="text-lg font-black leading-none">{{ $user->CGPA ?? '0.00' }}</span>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-black text-gray-900 leading-tight">Academic Achievement</h4>
                                        <p class="text-xs text-indigo-600 font-bold uppercase tracking-wider mt-1">{{ $graduationData['predicate'] ?? 'Active' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-900 rounded-lg text-[10px] font-black uppercase tracking-widest">
                                        {{ $user->Is_Graduate ? 'Alumni' : 'Active Student' }}
                                    </span>
                                </div>
                            </div>

                            <div class="py-5">
                                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <i class="bi bi-mortarboard"></i>
                                    Program Details
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Education Level</span>
                                        <span class="text-sm font-black text-gray-900">{{ $academicData['edu_level'] ?? '-' }}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Prodi / Sub-Prodi</span>
                                        <span class="text-sm font-black text-gray-900">{{ $academicData['prodi'] ?? '-' }} / {{ $academicData['sub_prodi'] ?? '-' }}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Academic Advisor</span>
                                        <span class="text-sm font-black text-gray-900">{{ $academicData['academic_advisor'] ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="py-5">
                                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <i class="bi bi-journal-text"></i>
                                    Final Project
                                </h4>
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Thesis Title (IDN)</span>
                                    <p class="text-sm font-bold text-gray-900 italic leading-relaxed">"{{ $graduationData['final_project_indonesia'] ?? 'Not Recorded' }}"</p>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase mt-2 italic">Judicium Date: {{ $graduationData['judicium_date'] ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <div class="py-5">
                                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <i class="bi bi-patch-check"></i>
                                    Certifications
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    @if(!empty($academicData['certificate_no_1']))
                                        <div class="flex flex-col">
                                            <span class="text-sm font-black text-gray-900">{{ $academicData['certificate_no_1'] }}</span>
                                            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider italic mt-0.5">{{ $academicData['certificate_date_1'] ?? '' }}</span>
                                        </div>
                                    @endif
                                    @if(!empty($academicData['certificate_no_2']))
                                        <div class="flex flex-col">
                                            <span class="text-sm font-black text-gray-900">{{ $academicData['certificate_no_2'] }}</span>
                                            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider italic mt-0.5">{{ $academicData['certificate_date_2'] ?? '' }}</span>
                                        </div>
                                    @endif
                                    @if(empty($academicData['certificate_no_1']) && empty($academicData['certificate_no_2']))
                                        <p class="text-xs text-gray-400 italic font-medium">No registered certifications</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Parents Tab --}}
                        <div x-show="activeTab === 'parents'" style="display: none;" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="divide-y divide-gray-50 -mt-2">
                            {{-- Father Section --}}
                            <div class="py-6">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center font-black text-xs">F</div>
                                    <h4 class="text-[10px] font-black text-gray-900 uppercase tracking-widest">Father's Profile</h4>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 ml-11">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Full Name</span>
                                        <span class="text-sm font-black text-gray-900">{{ $fatherData['name'] ?? '-' }}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Business / Profession</span>
                                        <span class="text-sm font-black text-gray-900">{{ $fatherData['business_name'] ?? '-' }}</span>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tight mt-0.5">{{ $fatherData['business_title'] ?? '-' }} • {{ $fatherData['business_line'] ?? 'N/A' }}</p>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Social/ID No</span>
                                        <span class="text-sm font-black text-gray-900">{{ $fatherData['npwp_no'] ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Mother Section --}}
                            <div class="py-6">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-8 h-8 bg-pink-50 text-pink-600 rounded-lg flex items-center justify-center font-black text-xs">M</div>
                                    <h4 class="text-[10px] font-black text-gray-900 uppercase tracking-widest">Mother's Profile</h4>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 ml-11">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Full Name</span>
                                        <span class="text-sm font-black text-gray-900">{{ $motherData['name'] ?? '-' }}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Professional / Business</span>
                                        <span class="text-sm font-black text-gray-900">{{ $motherData['business_name'] ?? ($motherData['profession'] ?? '-') }}</span>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tight mt-0.5">{{ $motherData['business_line'] ?? 'General Professional' }}</p>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">ID (NPWP / Passport)</span>
                                        <span class="text-sm font-black text-gray-900">{{ $motherData['npwp_no'] ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Business Tab --}}
                        <div x-show="activeTab === 'business'" style="display: none;" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="divide-y divide-gray-50 -mt-2">
                            <div class="py-5">
                                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                                    <i class="bi bi-briefcase"></i>
                                    Owned Businesses
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @forelse($user->businesses as $b)
                                        <a href="{{ route('businesses.show', $b) }}" 
                                           class="group flex items-center justify-between p-5 bg-white border border-gray-100 rounded-2xl hover:border-gray-900 transition-all duration-300 shadow-sm hover:shadow-md">
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-12 bg-gray-50 text-gray-900 rounded-xl flex items-center justify-center font-black group-hover:bg-gray-900 group-hover:text-white transition-colors">
                                                    {{ strtoupper(substr($b->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="text-sm font-black text-gray-900">{{ $b->name }}</p>
                                                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider">{{ $b->businessType->name ?? 'General' }}</p>
                                                </div>
                                            </div>
                                            <i class="bi bi-arrow-right text-gray-300 group-hover:text-gray-900 transition-colors"></i>
                                        </a>
                                    @empty
                                        <div class="col-span-2 py-12 text-center">
                                            <div class="w-16 h-16 bg-gray-50 text-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                                                <i class="bi bi-inboxes text-3xl"></i>
                                            </div>
                                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">No Active Businesses</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>