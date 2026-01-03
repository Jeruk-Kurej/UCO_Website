<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-soft-gray-50 to-white py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Page Header --}}
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-uco-orange-500 to-uco-yellow-500 flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-soft-gray-900">Profile Settings</h1>
                        <p class="text-sm text-soft-gray-600 mt-1">Manage your account information and preferences</p>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                {{-- Profile Information Card --}}
                <div class="bg-white rounded-2xl shadow-lg border border-soft-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-uco-orange-50 to-uco-yellow-50 px-6 py-4 border-b border-soft-gray-100">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-uco-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h2 class="text-lg font-bold text-soft-gray-900">Profile Information</h2>
                        </div>
                        <p class="text-xs text-soft-gray-600 mt-1">Update your account's profile information and email address</p>
                    </div>
                    <div class="p-6">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                {{-- Update Password Card --}}
                <div class="bg-white rounded-2xl shadow-lg border border-soft-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-uco-orange-50 to-uco-yellow-50 px-6 py-4 border-b border-soft-gray-100">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-uco-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <h2 class="text-lg font-bold text-soft-gray-900">Update Password</h2>
                        </div>
                        <p class="text-xs text-soft-gray-600 mt-1">Ensure your account is using a long, random password to stay secure</p>
                    </div>
                    <div class="p-6">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                {{-- Delete Account Card --}}
                <div class="bg-white rounded-2xl shadow-lg border border-red-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-red-50 to-orange-50 px-6 py-4 border-b border-red-100">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <h2 class="text-lg font-bold text-red-900">Delete Account</h2>
                        </div>
                        <p class="text-xs text-red-600 mt-1">Permanently delete your account and all associated data</p>
                    </div>
                    <div class="p-6">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
