<x-app-layout>
    <div class="space-y-6">
        {{-- Welcome Card --}}
        <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
            <div class="bg-gradient-to-r from-orange-500 to-yellow-500 p-8 text-white">
                <h1 class="text-3xl font-bold mb-2">Welcome back, {{ Auth::user()->name }}! 👋</h1>
                <p class="text-orange-100">
                    @if(Auth::user()->isAdmin())
                        You're logged in as <span class="font-semibold">Administrator</span>
                    @elseif(Auth::user()->isStudent())
                        You're logged in as <span class="font-semibold">Student</span>
                    @else
                        You're logged in as <span class="font-semibold">Alumni</span>
                    @endif
                </p>
            </div>
        </div>

        {{-- Quick Stats Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- My Businesses --}}
            @if(!Auth::user()->isAdmin())
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="bi bi-briefcase text-2xl text-orange-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">My Businesses</p>
                            <p class="text-2xl font-bold text-gray-900">{{ Auth::user()->businesses->count() }}</p>
                        </div>
                    </div>
                    <a href="{{ route('businesses.index') }}" class="mt-4 inline-flex items-center text-sm text-orange-600 hover:text-orange-700">
                        View all
                        <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            @endif

            {{-- Total Businesses (Admin) --}}
            @if(Auth::user()->isAdmin())
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                            <i class="bi bi-building text-2xl text-indigo-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Businesses</p>
                            <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Business::count() }}</p>
                        </div>
                    </div>
                    <a href="{{ route('businesses.index') }}" class="mt-4 inline-flex items-center text-sm text-indigo-600 hover:text-indigo-700">
                        Manage all
                        <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="bi bi-people text-2xl text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Users</p>
                            <p class="text-2xl font-bold text-gray-900">{{ \App\Models\User::count() }}</p>
                        </div>
                    </div>
                    <a href="{{ route('users.index') }}" class="mt-4 inline-flex items-center text-sm text-green-600 hover:text-green-700">
                        Manage users
                        <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            @endif

            {{-- Browse Directory --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="bi bi-shop text-2xl text-yellow-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Business Directory</p>
                        <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Business::count() }}</p>
                    </div>
                </div>
                <a href="{{ route('businesses.index') }}" class="mt-4 inline-flex items-center text-sm text-yellow-600 hover:text-yellow-700">
                    Browse all
                    <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @if(!Auth::user()->isAdmin())
                    <a href="{{ route('businesses.create') }}" class="flex items-center gap-3 p-4 border border-gray-200 rounded-lg hover:border-orange-500 hover:shadow-md transition duration-150">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="bi bi-plus-lg text-orange-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Add Business</p>
                            <p class="text-xs text-gray-600">Create new listing</p>
                        </div>
                    </a>
                @endif

                <a href="{{ route('businesses.index') }}" class="flex items-center gap-3 p-4 border border-gray-200 rounded-lg hover:border-orange-500 hover:shadow-md transition duration-150">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="bi bi-search text-blue-600"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Browse</p>
                        <p class="text-xs text-gray-600">Explore directory</p>
                    </div>
                </a>

                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 p-4 border border-gray-200 rounded-lg hover:border-orange-500 hover:shadow-md transition duration-150">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="bi bi-person text-purple-600"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Profile</p>
                        <p class="text-xs text-gray-600">Edit your info</p>
                    </div>
                </a>

                @if(Auth::user()->isAdmin())
                    <a href="{{ route('users.index') }}" class="flex items-center gap-3 p-4 border border-gray-200 rounded-lg hover:border-orange-500 hover:shadow-md transition duration-150">
                        <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                            <i class="bi bi-shield-check text-indigo-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Admin Panel</p>
                            <p class="text-xs text-gray-600">Manage platform</p>
                        </div>
                    </a>
                @endif
            </div>
        </div>

        {{-- Recent Activity / Your Businesses --}}
        @if(!Auth::user()->isAdmin() && Auth::user()->businesses->count() > 0)
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Your Recent Businesses</h2>
                    <a href="{{ route('businesses.index') }}" class="text-sm text-orange-600 hover:text-orange-700">
                        View all
                        <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach(Auth::user()->businesses->take(3) as $business)
                        <a href="{{ route('businesses.show', $business) }}" class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition duration-150">
                            @if($business->photos->first())
                                <img src="{{ asset('storage/' . $business->photos->first()->photo_url) }}" 
                                     alt="{{ $business->name }}" 
                                     class="w-full h-32 object-cover">
                            @else
                                <div class="w-full h-32 bg-gradient-to-br from-orange-100 to-yellow-100 flex items-center justify-center">
                                    <i class="bi bi-briefcase text-4xl text-orange-300"></i>
                                </div>
                            @endif
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 mb-1">{{ $business->name }}</h3>
                                <p class="text-xs text-gray-600">{{ $business->businessType->name }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
