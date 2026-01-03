<section class="space-y-6">
    <!-- Warning Message -->
    <div class="p-5 bg-red-50 border-l-4 border-red-500 rounded-xl">
        <div class="flex gap-4">
            <div class="flex-shrink-0">
                <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-sm font-bold text-red-800 mb-1">
                    Permanent Account Deletion
                </h3>
                <p class="text-sm text-red-700">
                    Once your account is deleted, all of its resources and data will be permanently removed. This action cannot be undone. Please download any important data before proceeding.
                </p>
            </div>
        </div>
    </div>

    <!-- Delete Button -->
    <div class="flex justify-start">
        <button
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold rounded-xl shadow-md hover:shadow-lg hover:from-red-700 hover:to-red-800 transition-all duration-200 transform hover:scale-105"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            Delete Account Permanently
        </button>
    </div>

    <!-- Confirmation Modal -->
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8">
            @csrf
            @method('delete')

            <!-- Modal Header -->
            <div class="text-center mb-6">
                <div class="mx-auto w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">
                    Are you absolutely sure?
                </h2>
                <p class="text-sm text-gray-600">
                    This action cannot be undone. This will permanently delete your account and remove all your data from our servers.
                </p>
            </div>

            <!-- Warning Box -->
            <div class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-xl">
                <p class="text-sm text-gray-700">
                    <span class="font-semibold">⚠️ Please be aware:</span> Once deleted, you will lose access to all your businesses, products, services, and other associated data.
                </p>
            </div>

            <!-- Password Confirmation -->
            <div class="mb-6 space-y-2">
                <label for="password" class="block text-sm font-semibold text-gray-700">
                    Enter your password to confirm
                </label>
                <div class="relative">
                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm transition-all duration-200 focus:border-red-500 focus:ring-2 focus:ring-red-500/20"
                        placeholder="Enter your password"
                        required
                    />
                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                </div>
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 justify-end">
                <button 
                    type="button"
                    x-on:click="$dispatch('close')"
                    class="px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-colors duration-200"
                >
                    Cancel
                </button>
                <button 
                    type="submit"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold rounded-xl shadow-md hover:shadow-lg hover:from-red-700 hover:to-red-800 transition-all duration-200"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Yes, Delete My Account
                </button>
            </div>
        </form>
    </x-modal>
</section>
