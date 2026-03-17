<x-app-layout>
    @php
        $personalData = $user->personal_data ?? [];
        $academicData = $user->academic_data ?? [];
        $fatherData = $user->father_data ?? [];
        $motherData = $user->mother_data ?? [];
        $graduationData = $user->graduation_data ?? [];
    @endphp

    <div class="max-w-6xl mx-auto py-8" x-data="{ activeTab: 'basic' }">
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('users.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm">
                    ← Back
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                    <p class="text-sm text-gray-600">{{ $user->email }} • {{ $user->username }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                @can('update', $user)
                    <a href="{{ route('users.edit', $user) }}" class="ml-3 inline-flex items-center px-4 py-2 bg-gray-900 text-white rounded-lg text-sm">Edit</a>
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
<x-app-layout>
    <div class="max-w-4xl mx-auto">
        {{-- Page Header --}}
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="/users" 
                   class="group inline-flex items-center gap-2.5 px-4 py-2.5 bg-white hover:bg-gray-900 border border-gray-200 hover:border-gray-900 text-gray-700 hover:text-white rounded-xl font-medium text-sm shadow-sm hover:shadow-md transition-all duration-200">
                    <i class="bi bi-arrow-left text-base group-hover:-translate-x-0.5 transition-transform duration-200"></i>
                    <span>Back</span>
                </a>
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $userToShow->name }}</h1>
                    <p class="text-sm text-gray-600">@<!-- -->{{ $userToShow->username }}</p>
                </div>
            </div>

            <a href="{{ route('users.edit', $userToShow) }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-900 hover:bg-gray-800 text-white rounded-xl font-semibold text-sm shadow-md hover:shadow-lg transition-all duration-200">
                <i class="bi bi-pencil"></i>
                Edit User
            </a>
        </div>

        {{-- User Information Card --}}
        <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">User Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Full Name --}}
                <div>
                    <p class="text-sm font-medium text-gray-500">Full Name</p>
                    <p class="mt-1 text-sm text-gray-900">{{ $userToShow->name }}</p>
                </div>

                {{-- Username --}}
                <div>
                    <p class="text-sm font-medium text-gray-500">Username</p>
                    <p class="mt-1 text-sm text-gray-900">{{ $userToShow->username }}</p>
                </div>

                {{-- Email --}}
                <div>
                    <p class="text-sm font-medium text-gray-500">Email</p>
                    <p class="mt-1 text-sm text-gray-900">{{ $userToShow->email }}</p>
                </div>

                {{-- Role --}}
                <div>
                    <p class="text-sm font-medium text-gray-500">Role</p>
                    <p class="mt-1">
                        @if($userToShow->role === 'admin')
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                <i class="bi bi-shield-check me-1"></i>
                                Admin
                            </span>
                        @elseif($userToShow->role === 'student')
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                <i class="bi bi-mortarboard me-1"></i>
                                Student
                            </span>
                        @else
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                <i class="bi bi-person-check me-1"></i>
                                Alumni
                            </span>
                        @endif
                    </p>
                </div>

                {{-- Status --}}
                <div>
                    <p class="text-sm font-medium text-gray-500">Status</p>
                    <p class="mt-1">
                        @if($userToShow->is_active)
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                <i class="bi bi-check-circle me-1"></i>
                                Active
                            </span>
                        @else
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                <i class="bi bi-x-circle me-1"></i>
                                Inactive
                            </span>
                        @endif
                    </p>
                </div>

                {{-- Total Businesses --}}
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Businesses</p>
                    <p class="mt-1 text-sm text-gray-900">{{ $userToShow->businesses->count() }}</p>
                </div>
            </div>
        </div>

        {{-- User's Businesses --}}
        @if($userToShow->businesses->count() > 0)
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Businesses ({{ $userToShow->businesses->count() }})</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($userToShow->businesses as $business)
                        <a href="{{ route('businesses.show', $business) }}" 
                           class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition duration-150">
                            <h3 class="font-semibold text-gray-900 mb-1">{{ $business->name }}</h3>
                            <p class="text-xs text-gray-600 mb-2">{{ $business->businessType->name }}</p>
                            <p class="text-xs text-gray-500">
                                <i class="bi bi-box-seam me-1"></i>
                                {{ $business->products->count() }} products
                            </p>
                        </a>
                    @endforeach
                </div>
            </div>
        @else
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="text-center py-8">
                    <i class="bi bi-briefcase text-6xl text-gray-300"></i>
                    <p class="mt-4 text-gray-500 text-lg font-medium">No businesses yet</p>
                    <p class="text-sm text-gray-400">This user hasn't created any businesses.</p>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>