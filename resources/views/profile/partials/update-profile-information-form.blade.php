@use('Illuminate\Support\Facades\Storage')

<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('patch')

        <!-- Profile Photo Upload -->
        <div class="space-y-3" x-data="{ 
            photoPreview: null,
            fileSelected(event) {
                const file = event.target.files[0];
                if(file) {
                    if(file.size > 10 * 1024 * 1024) {
                        alert('Photo must not exceed 10MB');
                        this.$refs.photoInput.value = '';
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = (e) => { this.photoPreview = e.target.result; };
                    reader.readAsDataURL(file);
                }
            }
        }">
            <label class="block text-sm font-semibold text-gray-700">
                Profile Photo
            </label>
            <div class="flex items-center gap-4 flex-wrap">
                <!-- Current Photo -->
                <div class="flex flex-col items-center">
                    <p class="text-xs text-gray-500 mb-2 font-medium">Current</p>
                    @php $profilePhoto = $user->profile_photo_url; @endphp
                    
                    <template x-if="photoPreview">
                        <img :src="photoPreview" class="w-24 h-24 rounded-full object-cover border-4 border-gray-200 shadow-md">
                    </template>

                    <template x-if="!photoPreview">
                        <div>
                            @if($profilePhoto)
                                <img 
                                    src="{{ storage_image_url($profilePhoto, ['width' => 256, 'height' => 256, 'crop' => 'thumb', 'quality' => 'auto', 'fetch_format' => 'auto']) }}?t={{ $user->updated_at?->timestamp ?? time() }}" 
                                    alt="Profile Photo" 
                                    class="w-24 h-24 rounded-full object-cover border-4 border-gray-200 shadow-md"
                                />
                            @else
                                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-uco-orange to-uco-yellow flex items-center justify-center border-4 border-gray-200 shadow-md">
                                    <span class="text-white text-3xl font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                </div>
                            @endif
                        </div>
                    </template>
                </div>

                <!-- Arrow (hidden until new photo selected) -->
                <div id="partial-preview-arrow" class="hidden flex-col items-center text-gray-400 self-end pb-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                    <span class="text-xs mt-1">New</span>
                </div>

                <!-- New Photo Preview (hidden until selected) -->
                <div id="partial-new-photo-wrapper" class="hidden flex-col items-center">
                    <p class="text-xs text-gray-500 mb-2 font-medium">New Photo</p>
                    <div class="relative">
                        <img id="partial-new-photo-img"
                             src=""
                             alt="New Photo Preview"
                             class="w-24 h-24 rounded-full object-cover border-4 border-uco-orange-300 shadow-md ring-2 ring-uco-orange-400 ring-offset-2">
                        <span class="absolute -bottom-1 -right-1 bg-uco-orange-500 text-white text-xs px-1.5 py-0.5 rounded-full font-semibold shadow">New</span>
                    </div>
                </div>

                <!-- Upload Button -->
                <div class="flex-1">
                    <label for="profile_photo" class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors shadow-sm focus-within:ring-2 focus-within:ring-soft-gray-900 focus-within:border-soft-gray-900">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Choose Photo
                        <input id="profile_photo" name="profile_photo" type="file" accept="image/*" class="sr-only" x-ref="photoInput" @change="fileSelected">
                    </label>
                    <input
                        id="profile_photo"
                        name="profile_photo"
                        type="file"
                        accept="image/*"
                        class="hidden"
                        onchange="validateAndPreviewPhotoPartial(event)"
                    />
                    <p class="text-xs text-gray-500">JPG, PNG or GIF (Max 10MB)</p>
                    <button type="button" id="partial-cancel-photo" onclick="cancelPartialPhoto()" class="hidden text-xs text-red-500 hover:text-red-700 underline text-left">
                        Cancel new photo
                    </button>
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

<script>
    function validateAndPreviewPhotoPartial(event) {
        const file = event.target.files[0];
        const maxSize = 10 * 1024 * 1024;

        if (file && file.size > maxSize) {
            alert('Profile photo must not be larger than 10MB. Please choose a smaller file.');
            event.target.value = '';
            return;
        }

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('partial-new-photo-img');
                const wrapper = document.getElementById('partial-new-photo-wrapper');
                const arrow = document.getElementById('partial-preview-arrow');
                const cancelBtn = document.getElementById('partial-cancel-photo');

                img.src = e.target.result;
                wrapper.classList.remove('hidden');
                wrapper.classList.add('flex');
                arrow.classList.remove('hidden');
                arrow.classList.add('flex');
                cancelBtn.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    }

    function cancelPartialPhoto() {
        document.getElementById('profile_photo').value = '';

        const wrapper = document.getElementById('partial-new-photo-wrapper');
        const arrow = document.getElementById('partial-preview-arrow');
        const cancelBtn = document.getElementById('partial-cancel-photo');

        wrapper.classList.add('hidden');
        wrapper.classList.remove('flex');
        arrow.classList.add('hidden');
        arrow.classList.remove('flex');
        cancelBtn.classList.add('hidden');
    }
</script>
