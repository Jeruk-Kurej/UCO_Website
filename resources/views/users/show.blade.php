<x-app-layout>
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-10">
        {{-- Header --}}
        <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-6">
                <a href="{{ route('users.index') }}" class="w-12 h-12 bg-white border rounded-2xl flex items-center justify-center text-gray-400 hover:text-uco-orange-500 hover:border-uco-orange-100 transition shadow-sm">
                    <i class="bi bi-chevron-left text-xl"></i>
                </a>
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <h1 class="text-3xl font-black text-gray-900">{{ $user->full_titled_name }}</h1>
                        <span class="px-2 py-0.5 bg-gray-100 text-[10px] font-bold uppercase tracking-widest text-gray-500 rounded">{{ $user->role }}</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-widest {{ $user->is_visible ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' }}">
                            {{ $user->is_visible ? 'Visible' : 'Hidden' }}
                        </span>
                    </div>
                    <p class="text-gray-500 font-medium">{{ $user->email }} • {{ $user->display_status }} • Registered {{ $user->created_at->format('M d, Y') }}</p>
                </div>
            </div>
            @if(Auth::check() && Auth::user()->isAdmin())
                <div class="flex gap-3">
                    <a href="{{ route('users.edit', $user) }}" class="px-4 py-2 bg-uco-orange-50 text-uco-orange-600 font-bold rounded-lg border border-uco-orange-200 hover:bg-uco-orange-100 transition">
                        <i class="bi bi-pencil me-2"></i>Edit User
                    </a>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Profile Card --}}
            <div class="space-y-8">
                <div class="bg-white border rounded-[2.5rem] p-8 shadow-sm text-center relative overflow-hidden">
                    <div class="uco-hero-mesh opacity-20"></div>
                    <div class="relative z-10">
                        <div class="w-32 h-32 rounded-full border-4 border-white shadow-xl mx-auto overflow-hidden bg-gray-50 flex items-center justify-center mb-6">
                            @if($user->profile_photo_url)
                                <img src="{{ $user->profile_photo_url }}" class="w-full h-full object-cover">
                            @else
                                <i class="bi bi-person text-5xl text-gray-200"></i>
                            @endif
                        </div>
                        <h2 class="text-2xl font-black text-gray-900">{{ $user->name }}</h2>
                        <p class="text-uco-orange-500 font-bold uppercase tracking-widest text-xs mt-1">{{ $user->display_status }}</p>
                        
                        <div class="mt-8 space-y-4 text-left border-t pt-8">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-gray-400 uppercase">NIS / ID</span>
                                <span class="text-sm font-bold text-gray-900">{{ $user->nis ?: '-' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-gray-400 uppercase">Peminatan</span>
                                <span class="text-sm font-bold text-gray-900">{{ $user->major ?: '-' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-gray-400 uppercase">Year of Enrollment</span>
                                <span class="text-sm font-bold text-gray-900">{{ $user->year_of_enrollment ?: '-' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-gray-400 uppercase">Graduate Year</span>
                                <span class="text-sm font-bold text-gray-900">{{ $user->graduate_year ?: 'Active Student' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-gray-400 uppercase">Admin Status</span>
                                <span class="text-sm font-bold text-gray-900 capitalize">{{ $user->student_status }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Contact Info --}}
                <div class="bg-white border rounded-2xl p-6 shadow-sm space-y-4">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest">Contact</h3>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3 text-sm">
                            <i class="bi bi-envelope text-gray-400"></i>
                            <span class="text-gray-700">{{ $user->email }}</span>
                        </div>
                        @if($user->personal_email)
                            <div class="flex items-center gap-3 text-sm">
                                <i class="bi bi-envelope-at text-gray-400"></i>
                                <span class="text-gray-700">{{ $user->personal_email }}</span>
                            </div>
                        @endif
                        @if($user->whatsapp)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->whatsapp) }}" target="_blank" class="flex items-center gap-3 text-sm hover:text-green-600 transition">
                                <i class="bi bi-whatsapp text-green-500"></i>
                                <span class="text-gray-700">{{ $user->whatsapp }}</span>
                            </a>
                        @endif
                        @if($user->linkedin)
                            <a href="{{ $user->linkedin }}" target="_blank" class="flex items-center gap-3 text-sm hover:text-blue-600 transition">
                                <i class="bi bi-linkedin text-blue-600"></i>
                                <span class="text-gray-700">LinkedIn Profile</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Right Section: Businesses + Testimony --}}
            <div class="lg:col-span-2 space-y-8">
                {{-- Testimony --}}
                @if($user->testimony)
                    <div class="bg-white border rounded-3xl p-8 shadow-sm">
                        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Testimony</h3>
                        <blockquote class="text-lg text-gray-700 italic leading-relaxed border-l-4 border-uco-orange-200 pl-6">
                            "{{ $user->testimony }}"
                        </blockquote>
                    </div>
                @endif

                {{-- Entrepreneur Businesses --}}
                @if($user->businesses->count())
                    <div class="bg-white border rounded-3xl p-8 shadow-sm">
                        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-6">Businesses (Entrepreneur)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($user->businesses as $biz)
                                <a href="{{ route('businesses.show', $biz) }}" class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl border hover:border-uco-orange-300 hover:shadow-md transition">
                                    <div class="w-12 h-12 bg-white rounded-xl border flex items-center justify-center overflow-hidden flex-shrink-0">
                                        @if($biz->logo_url)
                                            <img src="{{ $biz->logo_url }}" class="w-full h-full object-contain">
                                        @else
                                            <i class="bi bi-building text-gray-300"></i>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-bold text-gray-900 truncate">{{ $biz->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $biz->category->name ?? 'Business' }}</p>
                                    </div>
                                    <i class="bi bi-arrow-right text-gray-400"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Intrapreneur Companies --}}
                @if($user->companies->count())
                    <div class="bg-white border rounded-3xl p-8 shadow-sm">
                        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-6">Companies (Intrapreneur)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($user->companies as $company)
                                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl border">
                                    <div class="w-12 h-12 bg-white rounded-xl border flex items-center justify-center overflow-hidden flex-shrink-0">
                                        @if($company->logo_url)
                                            <img src="{{ $company->logo_url }}" class="w-full h-full object-contain">
                                        @else
                                            <i class="bi bi-building text-gray-300"></i>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-bold text-gray-900 truncate">{{ $company->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $company->position }} · {{ $company->category->name ?? 'Industry' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Skills --}}
                @if($user->skills->count())
                    <div class="bg-white border rounded-3xl p-8 shadow-sm">
                        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Skills</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($user->skills as $skill)
                                <span class="px-3 py-1.5 bg-gray-100 text-gray-700 text-xs font-bold rounded-lg uppercase">{{ $skill->name }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>