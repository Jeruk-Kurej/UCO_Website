<x-app-layout>
    <div class="max-w-4xl mx-auto">
        {{-- Page Header --}}
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="/users" 
                   class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-gray-800 transition duration-150">
                    <i class="bi bi-arrow-left text-lg"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $userToShow->name }}</h1>
                    <p class="text-sm text-gray-600">@<!-- -->{{ $userToShow->username }}</p>
                </div>
            </div>

            <a href="{{ route('users.edit', $userToShow) }}" 
               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-orange-500 to-yellow-500 hover:from-orange-600 hover:to-yellow-600 text-white rounded-lg font-semibold text-sm shadow-sm transition duration-150">
                <i class="bi bi-pencil me-2"></i>
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