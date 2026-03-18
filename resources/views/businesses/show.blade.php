@use('Illuminate\Support\Facades\Storage')

<x-app-layout>
    <div x-data="{ showUserModal: false }" x-init="$watch('showUserModal', val => document.body.style.overflow = val ? 'hidden' : '')">
    {{-- Hero Section with Elegant Back Button --}}
    <div class="mb-8 px-4 sm:px-0">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-6">
            <a href="{{ route('businesses.index') }}" 
               class="group inline-flex items-center justify-center sm:justify-start gap-2.5 px-4 py-2.5 bg-white hover:bg-gray-900 border border-gray-200 hover:border-gray-900 text-gray-700 hover:text-white rounded-xl font-medium text-sm shadow-sm hover:shadow-md transition-all duration-200">
                <i class="bi bi-arrow-left text-base group-hover:-translate-x-0.5 transition-transform duration-200"></i>
                <span>Back</span>
            </a>
            <div class="flex-1">
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-soft-gray-100 text-soft-gray-700 text-xs font-semibold rounded-xl">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        {{ $business->businessType->name }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-xl
                        {{ $business->isBothMode() ? 'bg-purple-100 text-purple-700' : ($business->isProductMode() ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700') }}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($business->isBothMode())
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-3zM14 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1h-4a1 1 0 01-1-1v-3z"/>
                            @elseif($business->isProductMode())
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            @endif
                        </svg>
                        <span class="hidden sm:inline">{{ $business->isBothMode() ? 'Products & Services' : ($business->isProductMode() ? 'Product-Based' : 'Service-Based') }}</span>
                        <span class="sm:hidden">{{ $business->isBothMode() ? 'Both' : ($business->isProductMode() ? 'Product' : 'Service') }}</span>
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-soft-gray-900 tracking-tight">{{ $business->name }}</h1>
            </div>
            @auth
                @if(auth()->id() === $business->user_id || auth()->user()->isAdmin())
                    <a href="{{ route('businesses.edit', $business) }}" 
                       class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-soft-gray-900 hover:bg-soft-gray-800 text-white rounded-xl font-semibold text-sm shadow-md hover:shadow-lg transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <span class="hidden sm:inline">Edit Business</span>
                        <span class="sm:hidden">Edit</span>
                    </a>
                @endif
            @endauth
        </div>
    </div>

    <div class="space-y-6">
        {{-- Business Overview Card - Professional Design --}}
        <div class="bg-white shadow-lg sm:rounded-2xl overflow-hidden border border-soft-gray-100">
            {{-- Hero Photo Carousel (Dynamic & Premium) --}}
            <div class="relative h-64 sm:h-72 lg:h-80 overflow-hidden"
                 @php $heroPhotosCount = $business->photos->count(); @endphp
                 x-data="{ 
                    activeHeroSlide: 0, 
                    heroSlidesCount: {{ $heroPhotosCount }},
                    heroTimer: null,
                    startHeroTimer() {
                        if (this.heroSlidesCount > 1) {
                            this.heroTimer = setInterval(() => {
                                this.activeHeroSlide = (this.activeHeroSlide + 1) % this.heroSlidesCount;
                            }, 5000);
                        }
                    },
                    stopHeroTimer() {
                        if(this.heroTimer) clearInterval(this.heroTimer);
                    }
                 }"
                 x-init="startHeroTimer()"
                 @mouseenter="stopHeroTimer()"
                 @mouseleave="startHeroTimer()">
                
                @forelse($business->photos as $index => $photo)
                    <div x-show="activeHeroSlide === {{ $index }}"
                         x-transition:enter="transition ease-out duration-1000"
                         x-transition:enter-start="opacity-0 scale-105"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-1000"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="absolute inset-0 w-full h-full">
                        <img src="{{ storage_image_url($photo->photo_url, 'hero') }}" 
                             alt="{{ $business->name }} - Hero Photo {{ $index + 1 }}" 
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    </div>
                @empty
                    <div class="w-full h-full bg-gradient-to-br from-soft-gray-100 via-soft-gray-50 to-soft-gray-100 flex items-center justify-center relative">
                        <svg class="w-24 h-24 text-soft-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-black/5 to-transparent"></div>
                    </div>
                @endforelse

                {{-- Hero Controls (only if multiple) --}}
                @if($heroPhotosCount > 1)
                    {{-- Arrows --}}
                    <button @click="activeHeroSlide = (activeHeroSlide - 1 + {{ $heroPhotosCount }}) % {{ $heroPhotosCount }}" 
                            class="absolute left-6 top-1/2 -translate-y-1/2 z-20 w-12 h-12 flex items-center justify-center rounded-full bg-black/20 hover:bg-black/50 text-white opacity-0 group-hover:opacity-100 transition-all duration-300 backdrop-blur-sm border border-white/10">
                        <i class="bi bi-chevron-left text-2xl"></i>
                    </button>
                    <button @click="activeHeroSlide = (activeHeroSlide + 1) % {{ $heroPhotosCount }}" 
                            class="absolute right-6 top-1/2 -translate-y-1/2 z-20 w-12 h-12 flex items-center justify-center rounded-full bg-black/20 hover:bg-black/50 text-white opacity-0 group-hover:opacity-100 transition-all duration-300 backdrop-blur-sm border border-white/10">
                        <i class="bi bi-chevron-right text-2xl"></i>
                    </button>

                    {{-- Hero Dots indicator (centered) --}}
                    <div class="absolute bottom-8 left-0 right-0 flex justify-center gap-2.5 z-20">
                        @foreach($business->photos as $index => $photo)
                            <button @click="activeHeroSlide = {{ $index }}"
                                    class="h-1.5 rounded-full transition-all duration-500 shadow-lg"
                                    :class="activeHeroSlide === {{ $index }} ? 'w-10 bg-white' : 'w-2.5 bg-white/40 hover:bg-white/60 backdrop-blur-sm'"></button>
                        @endforeach
                    </div>
                @endif
            </div>


            {{-- Business Info Section --}}
            <div class="p-4 sm:p-6 lg:p-8">
                {{-- Owner Info - PROMINENT with Avatar --}}
                @php
                    $owner       = $business->user;
                    $ownerPhoto  = $owner->profile_photo_url;
                    $ownerAcad   = $owner->academic_data ?? [];
                    $ownerGrad   = $owner->graduation_data ?? [];
                    $ownerPerso  = $owner->personal_data ?? [];
                    $ownerMajor  = $owner->Major ?? null;
                    $ownerNis    = $owner->extended_data['nis'] ?? ($ownerAcad['nis'] ?? null);
                    $ownerYear   = $ownerAcad['Angkatan'] ?? ($ownerAcad['angkatan'] ?? null);
                    $ownerCgpa   = $owner->CGPA ?? null;
                    $ownerRole   = match($owner->role ?? '') {
                        'admin'   => 'Admin',
                        'alumni'  => 'Alumni',
                        'student' => 'Student',
                        default   => ucfirst($owner->role ?? 'User'),
                    };
                    $ownerBizCount = $owner->businesses()->count();
                    $ownerPhotoUrl = $ownerPhoto
                        ? storage_image_url($ownerPhoto, ['width'=>256,'height'=>256,'crop'=>'thumb','quality'=>'auto','fetch_format'=>'auto'])
                        : null;
                    $ownerPhotoBig = $ownerPhoto
                        ? storage_image_url($ownerPhoto, ['width'=>400,'height'=>400,'crop'=>'thumb','quality'=>'auto','fetch_format'=>'auto'])
                        : null;
                @endphp

                <div x-data="{ open: false, tab: 'basic' }"
                     x-init="open = false"
                     @keydown.escape.window="open = false"
                     class="flex flex-col sm:flex-row items-start gap-4 mb-6 pb-6 border-b-2 border-soft-gray-100">

                    {{-- Avatar --}}
                    @if($ownerPhotoUrl)
                        <img src="{{ $ownerPhotoUrl }}"
                             alt="{{ $owner->name }}"
                             loading="lazy" decoding="async"
                             class="flex-shrink-0 w-16 h-16 rounded-2xl object-cover shadow-lg">
                    @else
                        <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-gradient-to-br from-uco-orange-500 to-uco-yellow-500 flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                            {{ strtoupper(substr($owner->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <p class="text-xs font-semibold text-soft-gray-500 uppercase tracking-wider">Listed by</p>
                            <button @click="showUserModal = true" title="View Profile" class="text-soft-gray-400 hover:text-soft-gray-900 transition-colors p-1 rounded-full hover:bg-soft-gray-100 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        <h3 class="text-xl font-bold text-soft-gray-900 mb-1">{{ $business->user->name }}</h3>
                        @if($business->position)
                            <div class="flex items-center gap-2">
                                <div class="flex items-center gap-1.5 px-3 py-1.5 bg-soft-gray-100 text-soft-gray-700 rounded-xl">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-sm font-semibold">{{ $business->position }}</span>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- ============================================ --}}
                    {{-- POPUP CARD                                   --}}
                    {{-- ============================================ --}}
                    <template x-if="open">
                    <div>

                    {{-- Backdrop --}}
                    <div x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         @click="open = false"
                         class="fixed inset-0 z-40 bg-black/60"></div>

                    {{-- Card --}}
                    <div x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 translate-y-1"
                         @click.stop
                         class="fixed left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-50 w-72">
                        <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">

                            {{-- Top color strip --}}
                            <div class="h-14 bg-gradient-to-r
                                {{ $owner->role === 'admin'   ? 'from-red-500 to-red-400' :
                                   ($owner->role === 'alumni' ? 'from-uco-orange-500 to-uco-yellow-400' :
                                   'from-blue-500 to-blue-400') }}
                                relative">
                                <button @click="open = false"
                                        class="absolute top-2 right-2 w-6 h-6 rounded-lg bg-black/20 hover:bg-black/30 text-white flex items-center justify-center transition-colors duration-150">
                                    <i class="bi bi-x text-sm leading-none"></i>
                                </button>
                            </div>

                            {{-- Avatar centered, overlapping strip --}}
                            <div class="flex justify-center -mt-8 mb-2 relative z-10">
                                @if($ownerPhotoBig)
                                    <img src="{{ $ownerPhotoBig }}" alt="{{ $owner->name }}"
                                         class="w-16 h-16 rounded-2xl object-cover border-[3px] border-white shadow-lg">
                                @else
                                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-uco-orange-500 to-uco-yellow-500 border-[3px] border-white shadow-lg flex items-center justify-center text-white text-2xl font-bold">
                                        {{ strtoupper(substr($owner->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>

                            {{-- Name + role + meta --}}
                            <div class="text-center px-4 pb-3">
                                <p class="text-sm font-bold text-gray-900 leading-tight">{{ $owner->name }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $owner->username }}</p>
                                <div class="flex items-center justify-center gap-1.5 mt-2 flex-wrap">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wide
                                        {{ $owner->role === 'admin'   ? 'bg-red-100 text-red-600' :
                                           ($owner->role === 'alumni' ? 'bg-uco-orange-100 text-uco-orange-600' :
                                           'bg-blue-100 text-blue-600') }}">
                                        {{ $ownerRole }}
                                    </span>
                                    @if($ownerCgpa)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-semibold bg-gray-100 text-gray-600">
                                            <i class="bi bi-award text-[10px]"></i> {{ number_format((float)$ownerCgpa, 2) }}
                                        </span>
                                    @endif
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-semibold bg-gray-100 text-gray-600">
                                        <i class="bi bi-shop text-[10px]"></i> {{ $ownerBizCount }}
                                    </span>
                                </div>
                            </div>

                            {{-- Tabs nav --}}
                            <div class="flex border-t border-b border-gray-100">
                                @php
                                    $popupTabs = [
                                        ['id' => 'basic',    'icon' => 'bi-person',      'title' => 'Basic'],
                                        ['id' => 'personal', 'icon' => 'bi-telephone',   'title' => 'Contact'],
                                        ['id' => 'academic', 'icon' => 'bi-mortarboard', 'title' => 'Academic'],
                                        ['id' => 'parents',  'icon' => 'bi-people',      'title' => 'Parents'],
                                        ['id' => 'business', 'icon' => 'bi-briefcase',   'title' => 'Work'],
                                    ];
                                @endphp
                                @foreach($popupTabs as $pt)
                                <button @click="tab = '{{ $pt['id'] }}'"
                                        :class="tab === '{{ $pt['id'] }}'
                                            ? 'text-gray-900 bg-gray-50 border-b-2 border-gray-900'
                                            : 'text-gray-400 hover:text-gray-600 border-b-2 border-transparent'"
                                        class="flex-1 py-2.5 flex items-center justify-center transition-all duration-150"
                                        title="{{ $pt['title'] }}">
                                    <i class="bi {{ $pt['icon'] }} text-sm"></i>
                                </button>
                                @endforeach
                            </div>

                            {{-- Tab panels --}}
                            <div class="px-3 py-2 h-44 overflow-y-auto">

                                {{-- Basic --}}
                                <div x-show="tab === 'basic'" style="display:none;">
                                    @php
                                        $basicRows = array_filter([
                                            ['icon' => 'bi-envelope',       'label' => 'Email',  'value' => $owner->email],
                                            ['icon' => 'bi-shield-check',   'label' => 'Role',   'value' => $ownerRole],
                                            ['icon' => 'bi-calendar-check', 'label' => 'Joined', 'value' => $owner->created_at?->format('d M Y')],
                                        ], fn($r) => !empty($r['value']));
                                    @endphp
                                    @foreach($basicRows as $row)
                                        @include('businesses._owner_info_row', $row)
                                    @endforeach
                                </div>

                                {{-- Contact --}}
                                <div x-show="tab === 'personal'" style="display:none;">
                                    @php
                                        $personalRows = array_filter([
                                            ['icon' => 'bi-gender-ambiguous', 'label' => 'Gender',    'value' => $ownerPerso['gender'] ?? null],
                                            ['icon' => 'bi-telephone',        'label' => 'Phone',     'value' => $owner->phone_number ?? ($ownerPerso['phone'] ?? null)],
                                            ['icon' => 'bi-whatsapp',         'label' => 'WhatsApp',  'value' => $ownerPerso['whatsapp'] ?? null],
                                            ['icon' => 'bi-geo-alt',          'label' => 'Address',   'value' => $ownerPerso['address'] ?? null],
                                            ['icon' => 'bi-instagram',        'label' => 'Instagram', 'value' => $ownerPerso['instagram'] ?? null],
                                        ], fn($r) => !empty($r['value']));
                                    @endphp
                                    @forelse($personalRows as $row)
                                        @include('businesses._owner_info_row', $row)
                                    @empty
                                        <p class="text-[11px] text-gray-400 text-center py-4">No contact info.</p>
                                    @endforelse
                                </div>

                                {{-- Academic --}}
                                <div x-show="tab === 'academic'" style="display:none;">
                                    @php
                                        $academicRows = array_filter([
                                            ['icon' => 'bi-hash',              'label' => 'NIS',       'value' => $ownerNis],
                                            ['icon' => 'bi-mortarboard',       'label' => 'Major',     'value' => $ownerMajor],
                                            ['icon' => 'bi-calendar3',         'label' => 'Angkatan',  'value' => $ownerYear],
                                            ['icon' => 'bi-award',             'label' => 'GPA',       'value' => $ownerCgpa ? number_format((float)$ownerCgpa, 2) : null],
                                            ['icon' => 'bi-person-check',      'label' => 'Graduate',  'value' => isset($owner->Is_Graduate) ? ($owner->Is_Graduate ? 'Yes' : 'No') : null],
                                            ['icon' => 'bi-journal-text',      'label' => 'Edu Level', 'value' => $ownerAcad['Edu_Level'] ?? ($ownerAcad['edu_level'] ?? null)],
                                            ['icon' => 'bi-person-lines-fill', 'label' => 'Advisor',   'value' => $ownerAcad['Academic_Advisor'] ?? ($ownerAcad['academic_advisor'] ?? null)],
                                        ], fn($r) => !empty($r['value']));
                                    @endphp
                                    @forelse($academicRows as $row)
                                        @include('businesses._owner_info_row', $row)
                                    @empty
                                        <p class="text-[11px] text-gray-400 text-center py-4">No academic info.</p>
                                    @endforelse
                                </div>

                                {{-- Parents --}}
                                <div x-show="tab === 'parents'" style="display:none;">
                                    @php
                                        $ownerFather = $owner->father_data ?? [];
                                        $ownerMother = $owner->mother_data ?? [];
                                        $parentsRows = array_filter([
                                            ['icon' => 'bi-person',   'label' => 'Father',       'value' => $ownerFather['name']  ?? null],
                                            ['icon' => 'bi-telephone','label' => 'Father Phone', 'value' => $ownerFather['phone'] ?? null],
                                            ['icon' => 'bi-briefcase','label' => 'Father Job',   'value' => $ownerFather['job']   ?? null],
                                            ['icon' => 'bi-person',   'label' => 'Mother',       'value' => $ownerMother['name']  ?? null],
                                            ['icon' => 'bi-telephone','label' => 'Mother Phone', 'value' => $ownerMother['phone'] ?? null],
                                            ['icon' => 'bi-briefcase','label' => 'Mother Job',   'value' => $ownerMother['job']   ?? null],
                                        ], fn($r) => !empty($r['value']));
                                    @endphp
                                    @forelse($parentsRows as $row)
                                        @include('businesses._owner_info_row', $row)
                                    @empty
                                        <p class="text-[11px] text-gray-400 text-center py-4">No parent info.</p>
                                    @endforelse
                                </div>

                                {{-- Work --}}
                                <div x-show="tab === 'business'" style="display:none;">
                                    @php
                                        $ownerGradStatus = $owner->current_employment_status
                                            ? $owner->getEmploymentStatusLabel()
                                            : ($ownerGrad['employment_status'] ?? null);
                                        $ownerBusinesses = $owner->businesses()->select('id','name')->get();
                                    @endphp
                                    @if($ownerGradStatus)
                                        @include('businesses._owner_info_row', ['icon' => 'bi-briefcase', 'label' => 'Status', 'value' => $ownerGradStatus])
                                    @endif
                                    <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mt-2 mb-1">Businesses</p>
                                    @forelse($ownerBusinesses as $b)
                                        <a href="{{ route('businesses.show', $b) }}"
                                           class="flex items-center gap-1.5 py-1.5 px-2 rounded-lg hover:bg-uco-orange-50 text-gray-700 hover:text-uco-orange-700 text-xs font-medium transition-colors group">
                                            <i class="bi bi-shop text-[11px] text-gray-400 group-hover:text-uco-orange-500 flex-shrink-0"></i>
                                            <span class="truncate">{{ $b->name }}</span>
                                            <i class="bi bi-arrow-right text-[10px] ml-auto text-gray-300 group-hover:text-uco-orange-400"></i>
                                        </a>
                                    @empty
                                        <p class="text-[11px] text-gray-400 text-center py-3">No businesses yet.</p>
                                    @endforelse
                                </div>

                            </div>
                        </div>
                    </div>
                    {{-- end card --}}
                    </div>
                    </template>

                </div>

                {{-- Description --}}
                <div>
                    <h4 class="text-sm font-bold text-soft-gray-900 uppercase tracking-wider mb-3">About This Business</h4>
                    <p class="text-base text-soft-gray-700 leading-relaxed max-w-4xl">{{ $business->description }}</p>

                    @if($business->legal_document_path || $business->certification_path)
                        <div class="mt-6 pt-5 border-t border-soft-gray-100 flex flex-col sm:flex-row gap-4">
                            @if($business->legal_document_path)
                                <div class="flex items-center gap-3">
                                    <div class="p-2.5 bg-red-50 text-red-600 rounded-xl">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8zm1-13h-2v6h6v-2h-4V7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900 mb-0.5">Dokumen Legalitas</h4>
                                        <a href="{{ Storage::url($business->legal_document_path) }}" target="_blank" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline transition-colors">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                            Lihat / Unduh Dokumen (PDF)
                                        </a>
                                    </div>
                                </div>
                            @endif

                            @if($business->certification_path)
                                <div class="flex items-center gap-3">
                                    <div class="p-2.5 bg-blue-50 text-blue-600 rounded-xl">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8zm1-13h-2v6h6v-2h-4V7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900 mb-0.5">Sertifikasi Produk</h4>
                                        <a href="{{ Storage::url($business->certification_path) }}" target="_blank" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline transition-colors">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                            Lihat / Unduh Sertifikat (PDF)
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Tabs Navigation - Elegant Design --}}
        <div id="business-tabs" x-data="{ 
            activeTab: '{{ session('activeTab', $business->isProductMode() ? 'products' : 'services') }}'
        }" class="bg-white shadow-lg sm:rounded-2xl border border-soft-gray-100">
            <div class="border-b-2 border-soft-gray-100">
                <nav class="flex -mb-px px-6 overflow-x-auto">
                    @if($business->isProductMode())
                        <button @click="activeTab = 'products'" 
                                :class="activeTab === 'products' ? 'border-soft-gray-900 text-soft-gray-900' : 'border-transparent text-soft-gray-500 hover:text-soft-gray-700 hover:border-soft-gray-300'"
                                class="flex items-center gap-2 py-4 px-4 border-b-2 font-semibold text-sm transition duration-150 whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            Products
                            <span :class="activeTab === 'products' ? 'bg-soft-gray-900 text-white' : 'bg-soft-gray-100 text-soft-gray-600'" class="px-2.5 py-0.5 rounded-full text-xs font-semibold transition-colors">{{ $business->products->count() }}</span>
                        </button>
                    @endif

                    @if($business->isServiceMode())
                        <button @click="activeTab = 'services'" 
                                :class="activeTab === 'services' ? 'border-soft-gray-900 text-soft-gray-900' : 'border-transparent text-soft-gray-500 hover:text-soft-gray-700 hover:border-soft-gray-300'"
                                class="flex items-center gap-2 py-4 px-4 border-b-2 font-semibold text-sm transition duration-150 whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            </svg>
                            Services
                            <span :class="activeTab === 'services' ? 'bg-soft-gray-900 text-white' : 'bg-soft-gray-100 text-soft-gray-600'" class="px-2.5 py-0.5 rounded-full text-xs font-semibold transition-colors">{{ $business->services->count() }}</span>
                        </button>
                    @endif

                    <button @click="activeTab = 'photos'" 
                            :class="activeTab === 'photos' ? 'border-soft-gray-900 text-soft-gray-900' : 'border-transparent text-soft-gray-500 hover:text-soft-gray-700 hover:border-soft-gray-300'"
                            class="flex items-center gap-2 py-4 px-4 border-b-2 font-semibold text-sm transition duration-150 whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Photos
                            <span :class="activeTab === 'photos' ? 'bg-soft-gray-900 text-white' : 'bg-soft-gray-100 text-soft-gray-600'" class="px-2.5 py-0.5 rounded-full text-xs font-semibold transition-colors">{{ $business->photos->count() }}</span>
                    </button>

                    <button @click="activeTab = 'contacts'" 
                            :class="activeTab === 'contacts' ? 'border-soft-gray-900 text-soft-gray-900' : 'border-transparent text-soft-gray-500 hover:text-soft-gray-700 hover:border-soft-gray-300'"
                            class="flex items-center gap-2 py-4 px-4 border-b-2 font-semibold text-sm transition duration-150 whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            Contacts
                            <span :class="activeTab === 'contacts' ? 'bg-soft-gray-900 text-white' : 'bg-soft-gray-100 text-soft-gray-600'" class="px-2.5 py-0.5 rounded-full text-xs font-semibold transition-colors">{{ $business->contacts->count() }}</span>
                    </button>
                </nav>
            </div>

            {{-- Tab: Products --}}
            @if($business->isProductMode())
                <div x-show="activeTab === 'products'" class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Products</h3>
                    @auth
                        @if(auth()->id() === $business->user_id || auth()->user()->isAdmin())
                            <div class="flex items-center gap-2">
                                <a href="{{ route('businesses.product-categories.index', $business) }}" 
                                   class="inline-flex items-center px-3 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 transition duration-150">
                                    <i class="bi bi-tags me-2"></i>
                                    Manage Categories
                                </a>
                                <a href="{{ route('businesses.products.create', $business) }}" 
                                   class="inline-flex items-center px-3 py-2 bg-soft-gray-900 hover:bg-soft-gray-800 text-white text-sm font-medium rounded-xl shadow-sm transition duration-150">
                                    <i class="bi bi-plus-lg me-2"></i>
                                    Add Product
                                </a>
                            </div>
                        @endif
                    @endauth
                </div>

                @if($business->products->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($business->products as $product)
                            <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition duration-150">
                                {{-- Product Image / Carousel --}}
                                <div class="relative overflow-hidden group" 
                                     x-data="{ 
                                        currentIndex: 0, 
                                        total: {{ $product->photos->count() }},
                                        timer: null,
                                        startTimer() {
                                            if (this.total > 1) {
                                                this.timer = setInterval(() => {
                                                    this.currentIndex = (this.currentIndex + 1) % this.total;
                                                }, 4000);
                                            }
                                        },
                                        stopTimer() {
                                            if (this.timer) clearInterval(this.timer);
                                        }
                                     }"
                                     x-init="startTimer()"
                                     @mouseenter="stopTimer()"
                                     @mouseleave="startTimer()">
                                    
                                    @if($product->photos->count() > 0)
                                        {{-- Image Container --}}
                                        <div class="relative h-48 bg-gray-100">
                                            @foreach($product->photos as $index => $photo)
                                                <div x-show="currentIndex === {{ $index }}"
                                                     x-transition:enter="transition ease-out duration-500"
                                                     x-transition:enter-start="opacity-0 scale-105"
                                                     x-transition:enter-end="opacity-100 scale-100"
                                                     x-transition:leave="transition ease-in duration-300"
                                                     x-transition:leave-start="opacity-100"
                                                     x-transition:leave-end="opacity-0"
                                                     class="absolute inset-0">
                                                    <img src="{{ storage_image_url($photo->photo_url, 'gallery_thumb') }}" 
                                                         alt="{{ $product->name }}" 
                                                         class="w-full h-full object-cover">
                                                </div>
                                            @endforeach

                                            {{-- Navigation Controls (only if multiple) --}}
                                            @if($product->photos->count() > 1)
                                                {{-- Arrows --}}
                                                <button @click="currentIndex = (currentIndex - 1 + total) % total" 
                                                        class="absolute left-2 top-1/2 -translate-y-1/2 w-8 h-8 flex items-center justify-center rounded-full bg-black/30 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-200 hover:bg-black/50">
                                                    <i class="bi bi-chevron-left"></i>
                                                </button>
                                                <button @click="currentIndex = (currentIndex + 1) % total" 
                                                        class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 flex items-center justify-center rounded-full bg-black/30 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-200 hover:bg-black/50">
                                                    <i class="bi bi-chevron-right"></i>
                                                </button>

                                                {{-- Indicator Dots --}}
                                                <div class="absolute bottom-2 left-0 right-0 flex justify-center gap-1.5">
                                                    @foreach($product->photos as $index => $photo)
                                                        <button @click="currentIndex = {{ $index }}" 
                                                                :class="currentIndex === {{ $index }} ? 'bg-white w-4' : 'bg-white/50 w-1.5'"
                                                                class="h-1.5 rounded-full transition-all duration-300 shadow-sm">
                                                        </button>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                            <i class="bi bi-image text-5xl text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>

                                {{-- Product Info --}}
                                <div class="p-4">
                                    <div class="flex items-start justify-between mb-2">
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900 mb-1">{{ $product->name }}</h4>
                                            <p class="text-xs text-gray-500 mb-2">
                                                <i class="bi bi-tag me-1"></i>
                                                {{ $product->productCategory?->name ?? 'Uncategorized' }}
                                            </p>
                                        </div>
                                        <span class="text-orange-600 font-bold text-lg">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $product->description }}</p>

                                    {{-- Action Buttons --}}
                                    @auth
                                        @if(auth()->id() === $business->user_id || auth()->user()->isAdmin())
                                            <div class="flex items-center gap-2 pt-3 border-t border-gray-200">
                                                <a href="{{ route('products.photos.index', $product) }}" 
                                                   class="flex-1 inline-flex items-center justify-center px-3 py-1.5 bg-blue-50 text-blue-700 text-xs font-medium rounded hover:bg-blue-100 transition duration-150">
                                                    <i class="bi bi-images me-1"></i>
                                                    Photos ({{ $product->photos->count() }})
                                                </a>
                                                <a href="{{ route('businesses.products.edit', [$business, $product]) }}" 
                                                   class="inline-flex items-center justify-center w-8 h-8 bg-orange-50 text-orange-600 rounded hover:bg-orange-100 transition duration-150"
                                                   title="Edit Product">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('businesses.products.destroy', [$business, $product]) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('Delete {{ $product->name }}?');"
                                                      class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="inline-flex items-center justify-center w-8 h-8 bg-red-50 text-red-600 rounded hover:bg-red-100 transition duration-150"
                                                            title="Delete Product">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-lg">
                        <i class="bi bi-box-seam text-6xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500 text-lg font-medium mb-2">No products yet</p>
                        @auth
                            @if(auth()->id() === $business->user_id || auth()->user()->isAdmin())
                                <p class="text-sm text-gray-400 mb-4">Start adding products to showcase your offerings</p>
                                <a href="{{ route('businesses.products.create', $business) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-soft-gray-900 hover:bg-soft-gray-800 text-white text-sm font-medium rounded-xl shadow-sm transition duration-150">
                                    <i class="bi bi-plus-lg me-2"></i>
                                    Add Your First Product
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
            @endif

            {{-- Tab: Services --}}
            @if($business->isServiceMode())
                <div x-show="activeTab === 'services'" class="p-6" style="display: none;">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Services</h3>
                    @auth
                        @if(auth()->id() === $business->user_id || auth()->user()->isAdmin())
                            <a href="{{ route('businesses.services.create', $business) }}" 
                               class="inline-flex items-center px-3 py-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-lg shadow-sm transition duration-150">
                                <i class="bi bi-plus-lg me-2"></i>
                                Add Service
                            </a>
                        @endif
                    @endauth
                </div>

                @if($business->services->count() > 0)
                    <div class="space-y-3">
                        @foreach($business->services as $service)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition duration-150">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900 mb-1">{{ $service->name }}</h4>
                                        <p class="text-sm text-gray-600 mb-3">{{ $service->description }}</p>
                                        <div class="flex items-center gap-2">
                                            <span class="text-orange-600 font-bold">Rp {{ number_format($service->price, 0, ',', '.') }}</span>
                                            <span class="text-xs text-gray-500">/ {{ $service->price_type }}</span>
                                        </div>
                                    </div>

                                    {{-- Action Buttons --}}
                                    @auth
                                        @if(auth()->id() === $business->user_id || auth()->user()->isAdmin())
                                            <div class="flex items-center gap-2 ml-4">
                                                <a href="{{ route('businesses.services.edit', [$business, $service]) }}" 
                                                   class="inline-flex items-center justify-center w-8 h-8 bg-orange-50 text-orange-600 rounded hover:bg-orange-100 transition duration-150"
                                                   title="Edit Service">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('businesses.services.destroy', [$business, $service]) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('Delete {{ $service->name }}?');"
                                                      class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="inline-flex items-center justify-center w-8 h-8 bg-red-50 text-red-600 rounded hover:bg-red-100 transition duration-150"
                                                            title="Delete Service">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-lg">
                        <i class="bi bi-wrench text-6xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500 text-lg font-medium mb-2">No services yet</p>
                        @auth
                            @if(auth()->id() === $business->user_id || auth()->user()->isAdmin())
                                <p class="text-sm text-gray-400 mb-4">Add services to showcase what you offer</p>
                                <a href="{{ route('businesses.services.create', $business) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-soft-gray-900 hover:bg-soft-gray-800 text-white text-sm font-medium rounded-xl shadow-sm transition duration-150">
                                    <i class="bi bi-plus-lg me-2"></i>
                                    Add Your First Service
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
            @endif

            {{-- Tab: Photos --}}
            <div x-show="activeTab === 'photos'" class="p-6" style="display: none;">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Business Photo Gallery</h3>
                    @auth
                        @if(auth()->id() === $business->user_id || auth()->user()->isAdmin())
                            <div class="flex items-center gap-2">
                                <a href="{{ route('businesses.photos.index', $business) }}" 
                                   class="inline-flex items-center px-3 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition duration-150">
                                    <i class="bi bi-images me-2"></i>
                                    Manage Photos
                                </a>
                                <a href="{{ route('businesses.photos.create', $business) }}" 
                                   class="inline-flex items-center px-3 py-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-lg shadow-sm transition duration-150">
                                    <i class="bi bi-upload me-2"></i>
                                    Upload Photo
                                </a>
                            </div>
                        @endif
                    @endauth
                </div>

                @if($business->photos->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($business->photos as $photo)
                            @php $gphoto = $photo->photo_url; $gphotoUrl = null; @endphp
                            <div class="relative group">
                                @if($gphoto)
                                    @php $gphotoUrl = storage_image_url($gphoto, 'gallery_thumb'); @endphp
                                @endif
                                @if($gphotoUrl)
                                    <img src="{{ $gphotoUrl }}" alt="{{ $business->name }} photo" loading="lazy" decoding="async" onload="this.classList.remove('blur-sm')" class="w-full h-48 object-cover rounded-lg blur-sm">
                                @else
                                    <div class="w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center rounded-lg">
                                        <i class="bi bi-image text-4xl text-gray-400"></i>
                                    </div>
                                @endif

                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-lg">
                        <i class="bi bi-images text-6xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500 text-lg font-medium mb-2">No photos yet</p>
                        @auth
                            @if(auth()->id() === $business->user_id || auth()->user()->isAdmin())
                                <p class="text-sm text-gray-400 mb-4">Upload photos to showcase your business</p>
                                <a href="{{ route('businesses.photos.create', $business) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-soft-gray-900 hover:bg-soft-gray-800 text-white text-sm font-medium rounded-xl shadow-sm transition duration-150">
                                    <i class="bi bi-upload me-2"></i>
                                    Upload Your First Photo
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>

            {{-- Tab: Contacts --}}
            <div x-show="activeTab === 'contacts'" class="p-6" style="display: none;">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Contact Information</h3>
                    @auth
                        @if(auth()->id() === $business->user_id || auth()->user()->isAdmin())
                            <a href="{{ route('businesses.contacts.create', $business) }}" 
                               class="inline-flex items-center px-3 py-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-lg shadow-sm transition duration-150">
                                <i class="bi bi-plus-lg me-2"></i>
                                Add Contact
                            </a>
                        @endif
                    @endauth
                </div>

                @if($business->contacts->count() > 0)
                    <div class="space-y-3">
                        @foreach($business->contacts as $contact)
                            <div class="flex items-center gap-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-150">
                                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-orange-100 text-orange-600 flex-shrink-0">
                                    <i class="bi {{ $contact->contactType->icon_class }} text-xl"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900">{{ $contact->contactType->platform_name }}</p>
                                    <p class="text-sm text-gray-600 truncate">{{ $contact->contact_value }}</p>
                                </div>
                                @if($contact->is_primary)
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded whitespace-nowrap">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Primary
                                    </span>
                                @endif

                                {{-- Action Buttons --}}
                                @auth
                                    @if(auth()->id() === $business->user_id || auth()->user()->isAdmin())
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('businesses.contacts.edit', [$business, $contact]) }}" 
                                               class="inline-flex items-center justify-center w-8 h-8 bg-orange-50 text-orange-600 rounded hover:bg-orange-100 transition duration-150"
                                               title="Edit Contact">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('businesses.contacts.destroy', [$business, $contact]) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Delete this contact?');"
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="inline-flex items-center justify-center w-8 h-8 bg-red-50 text-red-600 rounded hover:bg-red-100 transition duration-150"
                                                        title="Delete Contact">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                @endauth
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-lg">
                        <i class="bi bi-telephone text-6xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500 text-lg font-medium mb-2">No contact information yet</p>
                        @auth
                            @if(auth()->id() === $business->user_id || auth()->user()->isAdmin())
                                <p class="text-sm text-gray-400 mb-4">Add contact methods so customers can reach you</p>
                                <a href="{{ route('businesses.contacts.create', $business) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-soft-gray-900 hover:bg-soft-gray-800 text-white text-sm font-medium rounded-xl shadow-sm transition duration-150">
                                    <i class="bi bi-plus-lg me-2"></i>
                                    Add Your First Contact
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- User Profile Modal --}}
    <div x-show="showUserModal" 
         x-cloak
         class="fixed inset-0 z-[100] overflow-y-auto" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            {{-- Background overlay with blur --}}
            <div x-show="showUserModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="showUserModal = false"
                 class="fixed inset-0 transition-opacity bg-gray-900/60 backdrop-blur-sm" 
                 aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            {{-- Modal panel --}}
            <div x-show="showUserModal"
                 x-transition:enter="ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                 class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-gray-100">
                
                {{-- Modal Header & Avatar --}}
                <div class="relative bg-gradient-to-br from-soft-gray-50 to-white px-6 pt-8 pb-6 border-b border-gray-100">
                    <button type="button" 
                            @click="showUserModal = false"
                            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-2 rounded-xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    
                    <div class="flex flex-col items-center">
                        @if($business->user->profile_photo_url)
                            <img src="{{ storage_image_url($business->user->profile_photo_url, 'profile_thumb') }}" 
                                 alt="{{ $business->user->name }}" 
                                 class="w-24 h-24 rounded-2xl object-cover shadow-lg mb-4 border-2 border-white">
                        @else
                            <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-uco-orange-500 to-uco-yellow-500 flex items-center justify-center text-white text-3xl font-bold shadow-lg mb-4 border-2 border-white">
                                {{ strtoupper(substr($business->user->name, 0, 1)) }}
                            </div>
                        @endif
                        <h3 class="text-xl font-bold text-gray-900">{{ $business->user->name }}</h3>
                        <p class="text-sm font-medium text-gray-500 mt-1">
                            @if($business->user->role === 'student') Student
                            @elseif($business->user->role === 'alumni') Alumni
                            @elseif($business->user->role === 'admin') Administrator
                            @else User
                            @endif
                        </p>
                    </div>
                </div>

                {{-- Modal Body: Tabbed Content --}}
                <div x-data="{ userTab: 'basic' }" class="bg-gray-50 rounded-b-2xl">
                    <div class="flex border-b border-gray-200">
                        <button @click="userTab = 'basic'" 
                                :class="userTab === 'basic' ? 'border-soft-gray-900 text-soft-gray-900 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-100/50'"
                                class="flex-1 py-3 px-4 border-b-2 font-semibold text-sm transition-colors text-center">
                            Basic Info
                        </button>
                        <button @click="userTab = 'personal'" 
                                :class="userTab === 'personal' ? 'border-soft-gray-900 text-soft-gray-900 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-100/50'"
                                class="flex-1 py-3 px-4 border-b-2 font-semibold text-sm transition-colors text-center">
                            Personal
                        </button>
                        <button @click="userTab = 'academic'" 
                                :class="userTab === 'academic' ? 'border-soft-gray-900 text-soft-gray-900 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-100/50'"
                                class="flex-1 py-3 px-4 border-b-2 font-semibold text-sm transition-colors text-center">
                            Academic
                        </button>
                    </div>
                    
                    <div class="p-6">
                        {{-- Basic Tab --}}
                        <div x-show="userTab === 'basic'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" class="space-y-4 text-left">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Username</p>
                                    <p class="text-sm font-medium text-gray-900">{{ '@' . $business->user->username }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Full Name</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $business->user->name }}</p>
                                </div>
                            </div>
                            
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Email Address</p>
                                <a href="mailto:{{ $business->user->email }}" class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors bg-blue-50 px-3 py-1.5 rounded-lg hover:bg-blue-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $business->user->email }}
                                </a>
                            </div>

                            <div class="grid grid-cols-2 gap-4 pt-2">
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Phone/Mobile</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $business->user->mobile_number ?? $business->user->phone_number ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">WhatsApp</p>
                                    @if($business->user->whatsapp)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $business->user->whatsapp) }}" target="_blank" class="inline-flex items-center gap-1.5 text-sm font-medium text-green-600 hover:text-green-800 transition-colors">
                                            <i class="bi bi-whatsapp"></i> {{ $business->user->whatsapp }}
                                        </a>
                                    @else
                                        <p class="text-sm font-medium text-gray-900">-</p>
                                    @endif
                                </div>
                            </div>

                            <div class="pt-2 border-t border-gray-200">
                                <p class="text-xs text-gray-400 mt-2">Joined {{ $business->user->created_at->format('M Y') }}</p>
                            </div>
                        </div>

                        {{-- Personal Tab --}}
                        <div x-show="userTab === 'personal'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" class="space-y-4 text-left">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Birth City</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $business->user->birth_city ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Birth Date</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $business->user->birth_date ? $business->user->birth_date->format('d M Y') : '-' }}</p>
                                </div>
                            </div>
                            
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Religion</p>
                                <p class="text-sm font-medium text-gray-900">{{ $business->user->religion ?? '-' }}</p>
                            </div>

                            @if($business->user->bio ?? false)
                                <div class="pt-2">
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Bio</p>
                                    <p class="text-sm text-gray-700 leading-relaxed">{{ $business->user->bio }}</p>
                                </div>
                            @endif
                        </div>

                        {{-- Academic Tab --}}
                        <div x-show="userTab === 'academic'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" class="space-y-4 text-left">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">NIS (Student ID)</p>
                                    <p class="text-sm font-mono font-medium text-gray-900">{{ $business->user->NIS ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Class/Year</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $business->user->Student_Year ?? '-' }}</p>
                                </div>
                            </div>

                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Major/Study Program</p>
                                <p class="text-sm font-medium text-gray-900">{{ $business->user->Major ?? '-' }}</p>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">CGPA</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $business->user->CGPA ? number_format($business->user->CGPA, 2) : '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Status</p>
                                    @if($business->user->Is_Graduate)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-md bg-green-100 text-green-800">
                                            <i class="bi bi-mortarboard-fill"></i> Graduated
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-md bg-blue-100 text-blue-800">
                                            <i class="bi bi-book-half"></i> Active Student
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    
    {{-- Smart Scroll Restoration Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Restore scroll position after a reload/redirect if stored
            const scrollPos = sessionStorage.getItem('uco_business_scroll_pos');
            if (scrollPos) {
                // Use setTimeout to ensure all dynamic elements are rendered
                setTimeout(() => {
                    window.scrollTo({
                        top: parseInt(scrollPos),
                        behavior: 'instant'
                    });
                    sessionStorage.removeItem('uco_business_scroll_pos');
                }, 50);
            }

            // Store scroll position on form submissions or specific actions
            const saveScrollPos = () => {
                sessionStorage.setItem('uco_business_scroll_pos', window.scrollY);
            };

            // Global listener for forms and action buttons that redirect back
            document.querySelectorAll('form, .btn-save-scroll').forEach(el => {
                el.addEventListener('submit', saveScrollPos);
                el.addEventListener('click', (e) => {
                    if (e.currentTarget.tagName === 'A' || e.currentTarget.tagName === 'BUTTON') {
                        saveScrollPos();
                    }
                });
            });
        });
    </script>
</x-app-layout>