<footer class="bg-gradient-to-br from-orange-500 via-orange-400 to-yellow-500 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Column 1: About UCO -->
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <x-application-logo class="h-12 w-auto fill-current text-white" />
                    <div>
                        <h3 class="font-bold text-lg">UCO Platform</h3>
                        <p class="text-sm text-orange-100">Student & Alumni Community</p>
                    </div>
                </div>
                <p class="text-sm text-orange-50 leading-relaxed">
                    Connecting students and alumni to build a stronger entrepreneurial community and foster business collaboration.
                </p>
            </div>

            <!-- Column 2: Quick Links -->
            <div>
                <h4 class="font-bold text-base mb-4 border-b-2 border-orange-300 pb-2 inline-block">Quick Links</h4>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('dashboard') }}" class="text-sm text-orange-50 hover:text-white hover:underline transition duration-150 flex items-center gap-2">
                            <i class="bi bi-speedometer2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('businesses.index') }}" class="text-sm text-orange-50 hover:text-white hover:underline transition duration-150 flex items-center gap-2">
                            <i class="bi bi-briefcase"></i>
                            Businesses Directory
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('business-types.index') }}" class="text-sm text-orange-50 hover:text-white hover:underline transition duration-150 flex items-center gap-2">
                            <i class="bi bi-tags"></i>
                            Business Categories
                        </a>
                    </li>
                    @auth
                        <li>
                            <a href="{{ route('profile.edit') }}" class="text-sm text-orange-50 hover:text-white hover:underline transition duration-150 flex items-center gap-2">
                                <i class="bi bi-person"></i>
                                My Profile
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>

            <!-- Column 3: Resources -->
            <div>
                <h4 class="font-bold text-base mb-4 border-b-2 border-orange-300 pb-2 inline-block">Resources</h4>
                <ul class="space-y-2">
                    <li>
                        <a href="#" class="text-sm text-orange-50 hover:text-white hover:underline transition duration-150 flex items-center gap-2">
                            <i class="bi bi-book"></i>
                            User Guide
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-sm text-orange-50 hover:text-white hover:underline transition duration-150 flex items-center gap-2">
                            <i class="bi bi-question-circle"></i>
                            FAQ
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-sm text-orange-50 hover:text-white hover:underline transition duration-150 flex items-center gap-2">
                            <i class="bi bi-shield-check"></i>
                            Privacy Policy
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-sm text-orange-50 hover:text-white hover:underline transition duration-150 flex items-center gap-2">
                            <i class="bi bi-file-text"></i>
                            Terms of Service
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Column 4: Contact -->
            <div>
                <h4 class="font-bold text-base mb-4 border-b-2 border-orange-300 pb-2 inline-block">Contact Us</h4>
                <ul class="space-y-3 text-sm text-orange-50">
                    <li class="flex items-start gap-2">
                        <i class="bi bi-geo-alt-fill text-white mt-1"></i>
                        <span>UC Surabaya, CitraLand CBD Boulevard, Made, Kec. Sambikerep, Surabaya, Jawa Timur 60219</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="bi bi-envelope-fill text-white"></i>
                        <a href="mailto:info@uco.ac.id" class="hover:text-white hover:underline">info@uco.ac.id</a>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="bi bi-telephone-fill text-white"></i>
                        <a href="tel:+62317000000" class="hover:text-white hover:underline">+62 (31) 700-0000</a>
                    </li>
                </ul>

                <!-- Social Media Icons -->
                <div class="mt-4">
                    <h5 class="font-semibold text-sm mb-2">Follow Us:</h5>
                    <div class="flex gap-3">
                        <a href="#" class="w-8 h-8 bg-white text-orange-500 rounded-full flex items-center justify-center hover:bg-orange-100 transition duration-150">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="#" class="w-8 h-8 bg-white text-orange-500 rounded-full flex items-center justify-center hover:bg-orange-100 transition duration-150">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="#" class="w-8 h-8 bg-white text-orange-500 rounded-full flex items-center justify-center hover:bg-orange-100 transition duration-150">
                            <i class="bi bi-twitter-x"></i>
                        </a>
                        <a href="#" class="w-8 h-8 bg-white text-orange-500 rounded-full flex items-center justify-center hover:bg-orange-100 transition duration-150">
                            <i class="bi bi-linkedin"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="border-t border-orange-300/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-col md:flex-row justify-between items-center gap-2 text-sm text-orange-50">
                <p>&copy; {{ date('Y') }} UCO Student & Alumni Platform. All rights reserved.</p>
                <div class="flex items-center gap-1">
                    <span>Powered by</span>
                    <span class="font-semibold text-white">Universitas Ciputra</span>
                </div>
            </div>
        </div>
    </div>
</footer>