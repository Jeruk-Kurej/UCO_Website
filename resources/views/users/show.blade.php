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

        {{-- Tabs --}}
        <div class="bg-white shadow-sm rounded-lg mb-6 overflow-hidden">
            <nav class="flex border-b border-gray-200">
                <button @click="activeTab = 'basic'" :class="activeTab === 'basic' ? 'border-b-2 border-gray-900 text-gray-900 font-semibold' : 'text-gray-500'" class="flex-1 py-3 px-4 text-sm">Basic</button>
                <button @click="activeTab = 'personal'" :class="activeTab === 'personal' ? 'border-b-2 border-gray-900 text-gray-900 font-semibold' : 'text-gray-500'" class="flex-1 py-3 px-4 text-sm">Personal</button>
                <button @click="activeTab = 'academic'" :class="activeTab === 'academic' ? 'border-b-2 border-gray-900 text-gray-900 font-semibold' : 'text-gray-500'" class="flex-1 py-3 px-4 text-sm">Academic</button>
                <button @click="activeTab = 'parents'" :class="activeTab === 'parents' ? 'border-b-2 border-gray-900 text-gray-900 font-semibold' : 'text-gray-500'" class="flex-1 py-3 px-4 text-sm">Parents</button>
                <button @click="activeTab = 'business'" :class="activeTab === 'business' ? 'border-b-2 border-gray-900 text-gray-900 font-semibold' : 'text-gray-500'" class="flex-1 py-3 px-4 text-sm">Business</button>
                <button @click="activeTab = 'security'" :class="activeTab === 'security' ? 'border-b-2 border-gray-900 text-gray-900 font-semibold' : 'text-gray-500'" class="flex-1 py-3 px-4 text-sm">Security</button>
            </nav>
        </div>

        <div class="space-y-6">
            <div x-show="activeTab === 'basic'" class="bg-white shadow-sm rounded-lg p-6">
                <h2 class="text-lg font-bold mb-4">Basic Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500">Full name</p>
                        <p class="mt-1 text-gray-900">{{ $user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Username</p>
                        <p class="mt-1 text-gray-900">{{ $user->username }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="mt-1 text-gray-900">{{ $user->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Role</p>
                        <p class="mt-1 text-gray-900">{{ ucfirst($user->role) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Profile photo</p>
                        <p class="mt-1">
                            @if($user->profile_photo_url)
                                <img src="{{ Storage::url($user->profile_photo_url) }}" class="w-20 h-20 object-cover rounded-md" alt="photo">
                            @else
                                <span class="text-gray-600">No photo</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div x-show="activeTab === 'personal'" class="bg-white shadow-sm rounded-lg p-6">
                <h2 class="text-lg font-bold mb-4">Personal & Contact</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500">Gender</p>
                        <p class="mt-1 text-gray-900">{{ $personalData['gender'] ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Phone</p>
                        <p class="mt-1 text-gray-900">{{ $user->phone_number ?? ($personalData['phone'] ?? '-') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">WhatsApp</p>
                        <p class="mt-1 text-gray-900">{{ $personalData['whatsapp'] ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Address</p>
                        <p class="mt-1 text-gray-900">{{ $personalData['address'] ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div x-show="activeTab === 'academic'" class="bg-white shadow-sm rounded-lg p-6">
                <h2 class="text-lg font-bold mb-4">Academic</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500">NIS</p>
                        <p class="mt-1 text-gray-900">{{ $academicData['nis'] ?? ($user->extended_data->nis ?? '-') ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Major</p>
                        <p class="mt-1 text-gray-900">{{ $academicData['major'] ?? ($user->major ?? '-') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Student Year</p>
                        <p class="mt-1 text-gray-900">{{ $academicData['student_year'] ?? ($user->student_year ?? '-') }}</p>
                    </div>
                </div>
            </div>

            <div x-show="activeTab === 'parents'" class="bg-white shadow-sm rounded-lg p-6">
                <h2 class="text-lg font-bold mb-4">Parents</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500">Father</p>
                        <p class="mt-1 text-gray-900">{{ $fatherData['name'] ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Mother</p>
                        <p class="mt-1 text-gray-900">{{ $motherData['name'] ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div x-show="activeTab === 'business'" class="bg-white shadow-sm rounded-lg p-6">
                <h2 class="text-lg font-bold mb-4">Business / Employment</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500">Employment status</p>
                        <p class="mt-1 text-gray-900">{{ $user->current_employment_status ?? ($graduationData['employment_status'] ?? '-') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Owned businesses</p>
                        <div class="mt-2 space-y-2">
                            @forelse($user->businesses as $b)
                                <a href="{{ route('businesses.show', $b) }}" class="block p-3 border rounded-lg hover:shadow-sm">{{ $b->name }}</a>
                            @empty
                                <p class="text-gray-600">No businesses</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div x-show="activeTab === 'security'" class="bg-white shadow-sm rounded-lg p-6">
                <h2 class="text-lg font-bold mb-4">Security & Account</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500">Email verified</p>
                        <p class="mt-1 text-gray-900">{{ $user->email_verified_at ? $user->email_verified_at->toDateTimeString() : 'No' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Last updated</p>
                        <p class="mt-1 text-gray-900">{{ $user->updated_at->toDateTimeString() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>