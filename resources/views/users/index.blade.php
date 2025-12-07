<x-app-layout>
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">User Management</h1>
                <p class="text-sm text-gray-600">Manage platform users and permissions</p>
            </div>
            <a href="{{ route('users.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-orange-500 to-yellow-500 hover:from-orange-600 hover:to-yellow-600 text-white rounded-lg font-semibold text-sm shadow-sm transition duration-150">
                <i class="bi bi-person-plus me-2"></i>
                Create User
            </a>
        </div>

        {{-- Users Table Card --}}
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            {{-- Table Container --}}
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[15%]">
                                User
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[20%]">
                                Email
                            </th>
                            <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-[12%]">
                                Role
                            </th>
                            <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-[12%]">
                                Status
                            </th>
                            <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-[12%]">
                                Businesses
                            </th>
                            <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-[15%]">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                {{-- User Info --}}
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-400 to-yellow-400 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $user->name }}</p>
                                            <p class="text-xs text-gray-500 truncate">{{ $user->username }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Email --}}
                                <td class="px-4 py-4">
                                    <div class="text-sm text-gray-600 truncate">{{ $user->email }}</div>
                                </td>

                                {{-- Role --}}
                                <td class="px-4 py-4 text-center">
                                    @if($user->role === 'admin')
                                        <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800 whitespace-nowrap">
                                            <i class="bi bi-shield-check"></i>
                                            Admin
                                        </span>
                                    @elseif($user->role === 'student')
                                        <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 whitespace-nowrap">
                                            <i class="bi bi-mortarboard"></i>
                                            Student
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 whitespace-nowrap">
                                            <i class="bi bi-person-check"></i>
                                            Alumni
                                        </span>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td class="px-4 py-4 text-center">
                                    @if($user->is_active)
                                        <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 whitespace-nowrap">
                                            <i class="bi bi-check-circle-fill"></i>
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 whitespace-nowrap">
                                            <i class="bi bi-x-circle-fill"></i>
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                {{-- Businesses Count --}}
                                <td class="px-4 py-4 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <i class="bi bi-briefcase text-gray-400 text-sm"></i>
                                        <span class="text-sm text-gray-700 font-medium">{{ $user->businesses_count ?? 0 }}</span>
                                    </div>
                                </td>

                                {{-- Actions --}}
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('users.show', $user) }}" 
                                           class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-blue-600 hover:bg-blue-50 transition duration-150"
                                           title="View Details">
                                            <i class="bi bi-eye text-lg"></i>
                                        </a>
                                        <a href="{{ route('users.edit', $user) }}" 
                                           class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-orange-600 hover:bg-orange-50 transition duration-150"
                                           title="Edit User">
                                            <i class="bi bi-pencil text-lg"></i>
                                        </a>
                                        @if(auth()->id() !== $user->id)
                                            <form action="{{ route('users.destroy', $user) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('⚠️ Delete {{ $user->name }}?\n\nThis will also delete:\n- All their businesses\n- All their products\n- All their data\n\nThis action cannot be undone!');"
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-red-600 hover:bg-red-50 transition duration-150"
                                                        title="Delete User">
                                                    <i class="bi bi-trash text-lg"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-300 cursor-not-allowed" 
                                                  title="Cannot delete yourself">
                                                <i class="bi bi-trash text-lg"></i>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="bi bi-people text-6xl text-gray-300 mb-3"></i>
                                        <p class="text-gray-500 text-lg font-medium">No users found</p>
                                        <p class="text-gray-400 text-sm">Create your first user to get started</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($users->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

        {{-- Stats Summary --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-indigo-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $users->total() }}</p>
                    </div>
                    <i class="bi bi-people text-3xl text-indigo-200"></i>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-indigo-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Admins</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $users->where('role', 'admin')->count() }}</p>
                    </div>
                    <i class="bi bi-shield-check text-3xl text-indigo-200"></i>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Students</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $users->where('role', 'student')->count() }}</p>
                    </div>
                    <i class="bi bi-mortarboard text-3xl text-green-200"></i>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Alumni</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $users->where('role', 'alumni')->count() }}</p>
                    </div>
                    <i class="bi bi-person-check text-3xl text-blue-200"></i>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>