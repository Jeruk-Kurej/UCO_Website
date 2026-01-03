<x-app-layout>
    {{-- ======================================== USERS EDIT ======================================== --}}
    <div class="max-w-3xl mx-auto">
        <div class="mb-6 flex items-center gap-3">
            <a href="{{ route('users.index') }}" class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold">Edit User</h1>
                <p class="text-sm text-gray-500">{{ $userToEdit->name }} ({{ $userToEdit->username }})</p>
            </div>
        </div>

        <form method="POST" action="{{ route('users.update', $userToEdit) }}" class="bg-white border rounded-lg p-6 space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-700">Username</label>
                    <input name="username" value="{{ old('username', $userToEdit->username) }}" required class="mt-1 block w-full border rounded px-3 py-2" />
                </div>
                <div>
                    <label class="block text-sm text-gray-700">Full name</label>
                    <input name="name" value="{{ old('name', $userToEdit->name) }}" required class="mt-1 block w-full border rounded px-3 py-2" />
                </div>
                <div>
                    <label class="block text-sm text-gray-700">Email</label>
                    <input type="email" name="email" value="{{ old('email', $userToEdit->email) }}" required class="mt-1 block w-full border rounded px-3 py-2" />
                </div>
                <div>
                    <label class="block text-sm text-gray-700">Role</label>
                    <select name="role" class="mt-1 block w-full border rounded px-3 py-2">
                        <option value="student" {{ old('role', $userToEdit->role) === 'student' ? 'selected' : '' }}>Student</option>
                        <option value="alumni" {{ old('role', $userToEdit->role) === 'alumni' ? 'selected' : '' }}>Alumni</option>
                        <option value="admin" {{ old('role', $userToEdit->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-700">Account Status</label>
                    <select name="is_active" class="mt-1 block w-full border rounded px-3 py-2">
                        <option value="1" {{ old('is_active', $userToEdit->is_active) == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active', $userToEdit->is_active) == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t">
                <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-200 rounded">Cancel</a>
                <div class="flex items-center gap-3">
                    @if(auth()->id() !== $userToEdit->id)
                        <form id="delete-form" action="{{ route('users.destroy', $userToEdit) }}" method="POST" onsubmit="return confirm('Delete this user?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-2 bg-red-600 text-white rounded">Delete</button>
                        </form>
                    @endif

                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded">Update</button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>