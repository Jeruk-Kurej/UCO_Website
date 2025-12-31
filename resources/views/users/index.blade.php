<x-app-layout>
    <div class="space-y-6" x-data="{ showImportModal: false }">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">User Management</h1>
                <p class="text-sm text-gray-600">Manage platform users and permissions</p>
            </div>
            <div class="flex gap-2">
                <button @click="showImportModal = true"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white rounded-lg font-semibold text-sm shadow-sm transition duration-150">
                    <i class="bi bi-file-earmark-excel me-2"></i>
                    Import Excel
                </button>
                <a href="{{ route('users.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-orange-500 to-yellow-500 hover:from-orange-600 hover:to-yellow-600 text-white rounded-lg font-semibold text-sm shadow-sm transition duration-150">
                    <i class="bi bi-person-plus me-2"></i>
                    Create User
                </a>
            </div>
        </div>

        {{-- Import Modal --}}
        <div x-show="showImportModal" 
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto" 
             aria-labelledby="modal-title" 
             role="dialog" 
             aria-modal="true">
            {{-- Background overlay --}}
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showImportModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click="showImportModal = false"
                     class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" 
                     aria-hidden="true"></div>

                {{-- Center modal --}}
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                {{-- Modal panel --}}
                <div x-show="showImportModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    
                    <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="px-6 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                            {{-- Modal Header --}}
                            <div class="flex items-start">
                                <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-green-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                                    <i class="bi bi-file-earmark-excel text-green-600 text-xl"></i>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                                    <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">
                                        Import Users from Excel
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">
                                            Upload an Excel file (.xlsx) with user data to bulk import users into the system.
                                        </p>
                                    </div>
                                </div>
                                <button type="button" 
                                        @click="showImportModal = false"
                                        class="text-gray-400 hover:text-gray-500">
                                    <i class="bi bi-x-lg text-xl"></i>
                                </button>
                            </div>

                            {{-- File Upload --}}
                            <div class="mt-5">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Select Excel File
                                </label>
                                <input type="file" 
                                       name="file" 
                                       accept=".xlsx,.xls"
                                       required
                                       class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500">
                                <p class="mt-1 text-xs text-gray-500">
                                    Accepted formats: .xlsx, .xls (Max: 10MB)
                                </p>
                            </div>

                            {{-- Template Download --}}
                            <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                                <div class="flex items-start">
                                    <i class="bi bi-info-circle text-blue-600 text-lg mt-0.5 mr-3"></i>
                                    <div class="flex-1">
                                        <p class="text-sm text-blue-800 font-medium">Need a template?</p>
                                        <p class="text-xs text-blue-700 mt-1">Download our Excel template with all required columns and a sample row.</p>
                                        <a href="{{ route('users.template') }}" 
                                           class="inline-flex items-center mt-2 text-xs font-semibold text-blue-600 hover:text-blue-800">
                                            <i class="bi bi-download mr-1"></i>
                                            Download Template
                                        </a>
                                    </div>
                                </div>
                            </div>

                            {{-- Import Notes --}}
                            <div class="mt-4 text-xs text-gray-600 space-y-1">
                                <p><strong>Note:</strong></p>
                                <ul class="list-disc list-inside pl-2 space-y-0.5">
                                    <li>Users with existing emails will be skipped</li>
                                    <li>Missing passwords will default to "password123"</li>
                                    <li>Invalid data will cause import to fail</li>
                                </ul>
                            </div>
                        </div>

                        {{-- Modal Footer --}}
                        <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                            <button type="submit"
                                    class="inline-flex justify-center w-full px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 border border-transparent rounded-lg shadow-sm sm:w-auto sm:text-sm">
                                <i class="bi bi-upload mr-2"></i>
                                Import Users
                            </button>
                            <button type="button"
                                    @click="showImportModal = false"
                                    class="inline-flex justify-center w-full px-4 py-2 mt-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
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
                                {{-- ✅ CHANGED: User Info WITHOUT Avatar Icon --}}
                                <td class="px-4 py-4">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500">@<!-- -->{{ $user->username }}</p>
                                    </div>
                                </td>

                                {{-- Email --}}
                                <td class="px-4 py-4">
                                    <div class="text-sm text-gray-600">{{ $user->email }}</div>
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