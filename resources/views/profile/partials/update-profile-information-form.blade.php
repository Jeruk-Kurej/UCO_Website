<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <!-- Name Field -->
        <div class="space-y-2">
            <label for="name" class="block text-sm font-semibold text-gray-700">
                Full Name
            </label>
            <input 
                id="name" 
                name="name" 
                type="text" 
                value="{{ old('name', $user->name) }}" 
                required 
                autofocus 
                autocomplete="name"
                class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm transition-all duration-200 focus:border-uco-orange focus:ring-2 focus:ring-uco-orange/20 hover:border-gray-400"
                placeholder="Enter your full name"
            />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- Email Field -->
        <div class="space-y-2">
            <label for="email" class="block text-sm font-semibold text-gray-700">
                Email Address
            </label>
            <input 
                id="email" 
                name="email" 
                type="email" 
                value="{{ old('email', $user->email) }}" 
                required 
                autocomplete="username"
                class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm transition-all duration-200 focus:border-uco-orange focus:ring-2 focus:ring-uco-orange/20 hover:border-gray-400"
                placeholder="your.email@example.com"
            />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
                    <p class="text-sm text-gray-700">
                        <span class="font-semibold">⚠️ Email not verified.</span>
                        <button 
                            form="send-verification" 
                            class="ml-2 text-uco-orange hover:text-uco-orange/80 font-medium underline transition-colors"
                        >
                            Click here to resend verification
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm text-green-600 font-medium flex items-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Verification link sent to your email!
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Username Field (if exists) -->
        @if(Schema::hasColumn('users', 'username'))
        <div class="space-y-2">
            <label for="username" class="block text-sm font-semibold text-gray-700">
                Username
            </label>
            <input 
                id="username" 
                name="username" 
                type="text" 
                value="{{ old('username', $user->username ?? '') }}" 
                autocomplete="username"
                class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm transition-all duration-200 focus:border-uco-orange focus:ring-2 focus:ring-uco-orange/20 hover:border-gray-400"
                placeholder="Your unique username"
            />
            <x-input-error class="mt-2" :messages="$errors->get('username')" />
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
            <div class="flex items-center gap-4">
                <button 
                    type="submit"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gray-900 text-white font-semibold rounded-lg hover:bg-gray-800 transition-colors shadow-sm"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save Changes
                </button>

                @if (session('status') === 'profile-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 3000)"
                        class="text-sm font-medium text-green-600 flex items-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Profile updated successfully!
                    </p>
                @endif
            </div>
        </div>
    </form>
</section>
