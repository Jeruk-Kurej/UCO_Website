@use('Illuminate\Support\Facades\Storage')

<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('patch')

        <!-- Profile Photo Upload -->
        <div class="space-y-3">
            <label class="block text-sm font-semibold text-gray-700">
                Profile Photo
            </label>
            <div class="flex items-center gap-6">
                <!-- Current Photo Preview -->
                <div class="relative">
                    @php $profilePhoto = $user->profile_photo_url; @endphp
                    @if($profilePhoto)
                        <img 
                            id="profile-photo-preview" 
                            src="{{ storage_image_url($profilePhoto, ['width' => 256, 'height' => 256, 'crop' => 'thumb', 'quality' => 'auto', 'fetch_format' => 'auto']) }}?t={{ $user->updated_at?->timestamp ?? time() }}" 
                            alt="Profile Photo" 
                            class="w-24 h-24 rounded-full object-cover border-4 border-gray-200 shadow-md"
                        />
                    @else
                        <div id="profile-photo-preview" class="w-24 h-24 rounded-full bg-gradient-to-br from-uco-orange to-uco-yellow flex items-center justify-center border-4 border-gray-200 shadow-md">
                            <span class="text-white text-3xl font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                    @endif
                </div>

                <!-- Upload Button -->
                <div class="flex-1">
                    <label for="profile_photo" class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Choose Photo
                    </label>
                    <input 
                        id="profile_photo" 
                        name="profile_photo" 
                        type="file" 
                        accept="image/*"
                        class="hidden"
                        onchange="validateAndPreviewPhoto(event)"
                    />
                    <p class="text-xs text-gray-500 mt-2">JPG, PNG or GIF (Max 10MB)</p>
                    <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
                </div>
            </div>
        </div>

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
