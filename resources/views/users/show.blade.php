<x-app-layout>
    {{-- ======================================== USERS SHOW ======================================== --}}
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('users.index') }}" class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold">{{ $userToShow->name }}</h1>
                    <p class="text-sm text-gray-500">@<!-- -->{{ $userToShow->username }}</p>
                </div>
            </div>
            <a href="{{ route('users.edit', $userToShow) }}" class="px-4 py-2 bg-purple-600 text-white rounded">Edit</a>
        </div>

        <div class="bg-white border rounded-lg p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-xs text-gray-500">Full Name</p>
                <p class="text-sm text-gray-900">{{ $userToShow->name }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Username</p>
                <p class="text-sm text-gray-900">{{ $userToShow->username }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Email</p>
                <p class="text-sm text-gray-900">{{ $userToShow->email }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Role</p>
                <p class="text-sm text-gray-900">{{ ucfirst($userToShow->role) }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Status</p>
                <p class="text-sm text-gray-900">{{ $userToShow->is_active ? 'Active' : 'Inactive' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Total Businesses</p>
                <p class="text-sm text-gray-900">{{ $userToShow->businesses_count ?? $userToShow->businesses->count() }}</p>
            </div>
        </div>

        <div class="bg-white border rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Businesses ({{ $userToShow->businesses_count ?? $userToShow->businesses->count() }})</h2>
            @if(($userToShow->businesses_count ?? $userToShow->businesses->count()) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($userToShow->businesses as $business)
                        <a href="{{ route('businesses.show', $business) }}" class="block border rounded p-4 hover:shadow">
                            <h3 class="font-medium">{{ $business->name }}</h3>
                            <p class="text-xs text-gray-500">{{ $business->businessType->name ?? '—' }}</p>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-500 py-8">No businesses yet.</div>
            @endif
        </div>
    </div>
</x-app-layout>