<x-app-layout>
    <div class="space-y-20 pb-8">
        {{-- Hero --}}
        <section
            class="relative overflow-hidden rounded-3xl border border-uco-orange-100 bg-white px-6 py-12 shadow-sm md:px-10 md:py-16">
            <div class="uco-hero-mesh"></div>
            <div class="uco-float-orb uco-float-orb--one"></div>
            <div class="uco-float-orb uco-float-orb--two"></div>

            <div class="relative z-10 grid grid-cols-1 gap-8 lg:grid-cols-2 lg:items-center">
                <div x-data="{
                    words: ['Innovative', 'Local', 'Student-Led', 'Alumni-Built'],
                    current: 0,
                    timer: null,
                    reducedMotion: window.matchMedia('(prefers-reduced-motion: reduce)').matches,
                    startRotation() {
                        if (this.reducedMotion) return;
                        this.timer = setInterval(() => {
                            this.current = (this.current + 1) % this.words.length;
                        }, 3400);
                    },
                    stopRotation() {
                        if (this.timer) clearInterval(this.timer);
                    }
                }" x-init="startRotation();
                window.addEventListener('beforeunload', () => stopRotation(), { once: true })" class="space-y-6 reveal-on-scroll">
                    <span
                        class="inline-flex items-center rounded-full border border-uco-orange-200 bg-uco-orange-50 px-4 py-1.5 text-xs font-semibold uppercase tracking-wide text-uco-orange-700">
                        UCO Business Showcase
                    </span>

                    <div class="space-y-3">
                        <h1 class="text-4xl font-extrabold text-soft-gray-900 md:text-5xl">
                            <span class="block leading-tight">Discover</span>
                            <span
                                class="relative mt-1 block h-[1.05em] min-w-[12ch] overflow-hidden leading-none text-uco-orange-600 md:min-w-[13ch]">
                                <template x-for="(word, index) in words" :key="word">
                                    <span x-show="index === current"
                                        x-transition:enter="transition ease-out duration-420"
                                        x-transition:enter-start="opacity-0 translate-y-2 blur-[1px]"
                                        x-transition:enter-end="opacity-100 translate-y-0 blur-0"
                                        x-transition:leave="transition ease-in duration-300"
                                        x-transition:leave-start="opacity-100 translate-y-0 blur-0"
                                        x-transition:leave-end="opacity-0 -translate-y-2 blur-[1px]"
                                        class="absolute inset-0 leading-none" x-text="word"></span>
                                </template>
                            </span>
                            <span class="mt-1 block leading-tight">Businesses from UCO Students & Alumni</span>
                        </h1>
                        <p class="max-w-2xl text-base leading-relaxed text-soft-gray-600 md:text-lg">
                            Explore a vibrant ecosystem of product and service ventures built by our community — from
                            first launch to growing brands.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('businesses.index') }}"
                            class="inline-flex items-center gap-2 rounded-xl bg-uco-orange-500 px-5 py-3 text-sm font-semibold text-white shadow-md shadow-uco-orange-200 transition hover:-translate-y-0.5 hover:bg-uco-orange-600">
                            Explore Businesses
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 reveal-on-scroll" style="transition-delay: 150ms;">
                    @foreach ($spotlightBusinesses as $business)
                        <a href="{{ route('businesses.show', $business) }}"
                            class="group overflow-hidden rounded-2xl border border-soft-gray-100 bg-white p-3 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg">
                            <div class="h-28 overflow-hidden rounded-xl bg-soft-gray-100">
                                @if ($business->photos->first())
                                    <img src="{{ storage_image_url($business->photos->first()->photo_url, 'gallery_thumb') }}"
                                        alt="{{ $business->name }}"
                                        class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                @else
                                    <div
                                        class="flex h-full w-full items-center justify-center bg-gradient-to-br from-uco-orange-100 to-uco-yellow-100">
                                        <i class="bi bi-shop text-2xl text-uco-orange-600"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="pt-3">
                                <p class="line-clamp-1 text-sm font-bold text-soft-gray-900">{{ $business->name }}</p>
                                <p class="line-clamp-1 text-xs text-soft-gray-500"
                                    title="{{ $business->businessType->name ?? 'Business' }}">
                                    {{ $business->display_category ?? ($business->businessType->name ?? 'Business') }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Featured Businesses --}}
        <section id="featured-businesses" class="space-y-8">
            <div
                class="flex flex-col gap-4 border-b border-soft-gray-200 pb-5 md:flex-row md:items-end md:justify-between reveal-on-scroll">
                <div>
                    <h2 class="text-3xl font-bold text-soft-gray-900">UCO Student & Alumni Ventures</h2>
                    <p class="mt-2 text-sm text-soft-gray-600">Curated ventures from our UCO entrepreneurial community.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 xl:gap-8">
                @foreach ($featuredBusinesses as $business)
                    <a href="{{ route('businesses.show', $business) }}"
                        class="group overflow-hidden rounded-2xl border border-soft-gray-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1.5 hover:border-uco-orange-300 hover:shadow-xl reveal-on-scroll flex flex-col">
                        <div class="relative h-48 overflow-hidden sm:h-52">
                            @if ($business->photos->first())
                                <img src="{{ storage_image_url($business->photos->first()->photo_url, 'gallery_full') }}"
                                    alt="{{ $business->name }}"
                                    class="h-full w-full object-cover transition duration-700 group-hover:scale-105">
                            @else
                                <div
                                    class="flex h-full w-full items-center justify-center bg-gradient-to-br from-uco-orange-100 to-uco-yellow-100">
                                    <i class="bi bi-briefcase text-5xl text-uco-orange-500"></i>
                                </div>
                            @endif
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-soft-gray-900/70 via-soft-gray-900/10 to-transparent">
                            </div>
                            <div class="absolute left-4 top-4">
                                <span
                                    class="rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-soft-gray-800 backdrop-blur"
                                    title="{{ $business->businessType->name ?? 'Business' }}">
                                    {{ $business->display_category ?? \Illuminate\Support\Str::limit($business->businessType->name ?? 'Business', 32) }}
                                </span>
                            </div>

                        </div>

                        <div class="flex flex-1 flex-col justify-between space-y-4 p-5">
                            <div>
                                <h3 class="line-clamp-1 text-lg font-extrabold text-soft-gray-900 md:text-xl">
                                    {{ $business->name }}</h3>
                                <p class="mt-2 line-clamp-3 text-sm leading-relaxed text-soft-gray-600">
                                    {{ $business->display_description }}</p>
                            </div>

                            <div class="flex items-center justify-between rounded-xl bg-soft-gray-50 px-3 py-2">
                                <div class="flex items-center gap-2">
                                    @if ($business->user && $business->user->profile_photo_url)
                                        <img src="{{ storage_image_url($business->user->profile_photo_url, 'profile_thumb') }}"
                                            class="h-9 w-9 rounded-full object-cover"
                                            alt="{{ $business->user->name }}">
                                    @else
                                        <div
                                            class="flex h-9 w-9 items-center justify-center rounded-full bg-uco-orange-100 text-sm font-bold text-uco-orange-700">
                                            {{ strtoupper(substr($business->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-semibold text-soft-gray-900">
                                            {{ $business->user->name ?? 'UCO Member' }}</p>
                                        <p class="text-xs text-soft-gray-500">{{ ucfirst($business->business_mode) }}
                                            business</p>
                                    </div>
                                </div>
                                <span class="text-uco-orange-600 transition group-hover:translate-x-1">
                                    <i class="bi bi-arrow-right-circle-fill text-lg"></i>
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

        {{-- UCO Support Journey --}}
        <section
            class="rounded-3xl border border-uco-yellow-100 bg-gradient-to-br from-uco-yellow-50 to-white p-6 md:p-8">
            <div class="mb-8 reveal-on-scroll">
                <h2 class="text-3xl font-bold text-soft-gray-900">How UCO Supports Entrepreneurial Growth</h2>
                <p class="mt-2 text-sm text-soft-gray-600">From first idea to scaling up, we support founders through
                    each phase.</p>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                <div class="uco-step-card reveal-on-scroll">
                    <div class="uco-step-icon"><i class="bi bi-lightbulb"></i></div>
                    <h3>Discover</h3>
                    <p>Students and alumni surface ideas from real community needs.</p>
                </div>
                <div class="uco-step-card reveal-on-scroll" style="transition-delay: 80ms;">
                    <div class="uco-step-icon"><i class="bi bi-people"></i></div>
                    <h3>Connect</h3>
                    <p>Mentors, peers, and alumni collaborate to shape business direction.</p>
                </div>
                <div class="uco-step-card reveal-on-scroll" style="transition-delay: 160ms;">
                    <div class="uco-step-icon"><i class="bi bi-rocket-takeoff"></i></div>
                    <h3>Launch</h3>
                    <p>Founders publish and showcase ventures to build initial traction.</p>
                </div>
                <div class="uco-step-card reveal-on-scroll" style="transition-delay: 240ms;">
                    <div class="uco-step-icon"><i class="bi bi-graph-up-arrow"></i></div>
                    <h3>Grow</h3>
                    <p>Continuous visibility opens opportunities for partnerships and sales.</p>
                </div>
            </div>
        </section>

        {{-- Testimonials --}}
        @if ($testimonies->count() > 0)
            <section class="space-y-6" x-data="{ index: 0, total: {{ $testimonies->count() }}, timer: null, next() { this.index = (this.index + 1) % this.total }, prev() { this.index = (this.index - 1 + this.total) % this.total }, start() { this.timer = setInterval(() => this.next(), 4500) }, stop() { if (this.timer) clearInterval(this.timer) } }" x-init="start()" @mouseenter="stop()"
                @mouseleave="start()">
                <div class="flex items-end justify-between reveal-on-scroll">
                    <div>
                        <h2 class="text-3xl font-bold text-soft-gray-900">Success Stories</h2>
                        <p class="mt-2 text-sm text-soft-gray-600">Voices from our student and alumni founders.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="prev()"
                            class="rounded-full border border-soft-gray-300 p-2 text-soft-gray-600 transition hover:border-uco-orange-300 hover:text-uco-orange-600">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button @click="next()"
                            class="rounded-full border border-soft-gray-300 p-2 text-soft-gray-600 transition hover:border-uco-orange-300 hover:text-uco-orange-600">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>

                <div class="relative overflow-hidden rounded-3xl border border-soft-gray-200 bg-white p-6 shadow-sm md:p-8 reveal-on-scroll"
                    style="transition-delay: 100ms;">
                    @foreach ($testimonies as $i => $testimony)
                        <article x-show="index === {{ $i }}"
                            x-transition:enter="transition ease-out duration-500"
                            x-transition:enter-start="opacity-0 translate-x-2"
                            x-transition:enter-end="opacity-100 translate-x-0" class="space-y-4">
                            <div class="flex items-center gap-4">
                                @if($testimony->user && $testimony->user->profile_photo_url)
                                    <img src="{{ storage_image_url($testimony->user->profile_photo_url, 'profile_thumb') }}" class="h-14 w-14 rounded-full object-cover border-2 border-uco-orange-100 shadow-sm" alt="">
                                @else
                                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-uco-orange-100 text-lg font-bold text-uco-orange-700 border-2 border-uco-orange-100 shadow-sm">
                                        {{ strtoupper(substr($testimony->user->name ?? $testimony->customer_name ?? 'U', 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-bold text-soft-gray-900">{{ $testimony->user->name ?? $testimony->customer_name }}</p>
                                    <p class="text-xs text-soft-gray-500">{{ optional($testimony->date)->format('F Y') }}</p>
                                </div>
                            </div>
                            <p class="text-lg leading-relaxed text-soft-gray-700 italic md:text-xl">
                                “{{ $testimony->display_content ?? \Illuminate\Support\Str::limit($testimony->content, 260) }}”
                            </p>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Partner Ecosystem --}}
        <section class="space-y-6">
            <div class="reveal-on-scroll">
                <h2 class="text-3xl font-bold text-soft-gray-900">Partner & Ecosystem</h2>
                <p class="mt-2 text-sm text-soft-gray-600">A collaborative network helping founders move faster.</p>
            </div>

            <div class="relative overflow-hidden rounded-2xl border border-soft-gray-200 bg-white py-5 reveal-on-scroll"
                style="transition-delay: 100ms;">
                <div class="uco-marquee">
                    <div class="uco-marquee-track">
                        @foreach ($partners as $partner)
                            <span class="uco-partner-chip">{{ $partner }}</span>
                        @endforeach
                        @foreach ($partners as $partner)
                            <span class="uco-partner-chip">{{ $partner }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        {{-- WhatsApp CTA --}}
        <section
            class="overflow-hidden rounded-3xl border border-uco-orange-200 bg-gradient-to-r from-uco-orange-50 via-white to-uco-yellow-50 p-6 md:p-8 reveal-on-scroll">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-soft-gray-900 md:text-3xl">Are you a UCO student, and have a business?</h2>
                    <p class="mt-2 text-sm text-soft-gray-600">Reach out and become part of our growing student &
                        alumni business directory.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ auth()->check() ? route('businesses.create') : route('login') }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-uco-orange-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-uco-orange-600">
                        {{ auth()->check() ? 'Submit Business' : 'Login to Submit' }}
                        <i class="bi bi-plus-circle"></i>
                    </a>
                    <a href="https://wa.me/?text={{ urlencode('Hi UCO team, I want to showcase my business on the platform.') }}"
                        target="_blank" rel="noopener"
                        class="inline-flex items-center gap-2 rounded-xl border border-uco-yellow-400 bg-uco-yellow-50 px-5 py-3 text-sm font-semibold text-uco-yellow-800 transition hover:bg-uco-yellow-100">
                        WhatsApp
                        <i class="bi bi-whatsapp"></i>
                    </a>
                </div>
            </div>
        </section>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const revealTargets = document.querySelectorAll('.reveal-on-scroll');
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-visible');
                        }
                    });
                }, {
                    threshold: 0.12
                });

                revealTargets.forEach((target) => observer.observe(target));
            });
        </script>
    @endpush
</x-app-layout>
