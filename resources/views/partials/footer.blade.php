<footer class="bg-[var(--color-indigo-dark)] text-white/80 pt-20 pb-10 relative overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none opacity-10">
        <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-[var(--color-pma-green)] blur-[100px]"></div>
        <div class="absolute bottom-[10%] right-[5%] w-[30%] h-[30%] rounded-full bg-[var(--color-ochre)] blur-[80px]"></div>
    </div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-12 mb-16">
            <!-- Brand Column -->
            <div class="lg:col-span-4">
                <a href="{{ route('home') }}" class="inline-block mb-6">
                    <img src="{{ url('images/PMALogoWhiteText.png') }}" alt="Pioneer Missions Africa" class="h-12 brightness-0 invert opacity-90">
                </a>
                <p class="text-lg leading-relaxed mb-8 max-w-sm text-gray-400">
                    Proclaiming the Everlasting Gospel and spreading the knowledge of the one true God across Africa.
                </p>
                
                <div class="flex gap-4">
                    <a href="https://www.facebook.com/pioneermissionsafrica" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-[var(--color-pma-green)] hover:text-white transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>
                    </a>
                    <a href="https://www.youtube.com/@pioneermissionsafrica3344" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-[var(--color-pma-green)] hover:text-white transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="lg:col-span-2">
                <h4 class="text-white font-bold text-lg mb-6">Discover</h4>
                <ul class="space-y-4">
                    <li><a href="{{ route('about') }}" class="hover:text-[var(--color-ochre)] transition-colors">About Us</a></li>
                    <li><a href="{{ route('studies') }}" class="hover:text-[var(--color-ochre)] transition-colors">Bible Studies</a></li>
                    <li><a href="{{ route('sermons') }}" class="hover:text-[var(--color-ochre)] transition-colors">Sermons</a></li>
                    <li><a href="{{ route('resources') }}" class="hover:text-[var(--color-ochre)] transition-colors">Resources</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-[var(--color-ochre)] transition-colors">Contact</a></li>
                </ul>
            </div>

            <!-- Support -->
            <div class="lg:col-span-2">
                <h4 class="text-white font-bold text-lg mb-6">Support</h4>
                <ul class="space-y-4">
                    <li><a href="{{ route('donate') }}" class="hover:text-[var(--color-ochre)] transition-colors">Donate</a></li>
                    <li><a href="{{ route('pledge') }}" class="hover:text-[var(--color-ochre)] transition-colors">Monthly Pledge</a></li>
                    <li><a href="{{ route('about.support') }}" class="hover:text-[var(--color-ochre)] transition-colors">Volunteer</a></li>
                    <li><a href="{{ route('privacy') }}" class="hover:text-[var(--color-ochre)] transition-colors">Privacy Policy</a></li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div class="lg:col-span-4">
                <h4 class="text-white font-bold text-lg mb-6">Stay Connected</h4>
                <p class="text-gray-400 mb-4 text-sm">Join our newsletter to receive updates on new studies and mission reports.</p>
                
                <form action="{{ route('newsletter.subscribe') }}" method="POST" class="relative">
                    @csrf
                    <div class="relative">
                        <input type="email" name="email" placeholder="Enter your email address" 
                               class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3.5 text-white placeholder-gray-500 focus:outline-none focus:border-[var(--color-pma-green)] focus:ring-1 focus:ring-[var(--color-pma-green)] transition-all">
                        <button type="submit" class="absolute right-1.5 top-1.5 bg-[var(--color-pma-green)] hover:bg-[var(--color-pma-green-light)] text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            Subscribe
                        </button>
                    </div>
                </form>

                <div class="mt-8 p-4 bg-white/5 rounded-xl border border-white/10">
                    <p class="text-sm text-gray-400 mb-1">Need prayer?</p>
                    <a href="mailto:prayer@pioneermissionsafrica.co.za" class="text-white font-medium hover:text-[var(--color-ochre)] transition-colors flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        prayer@pioneermissionsafrica.co.za
                    </a>
                </div>
            </div>
        </div>

        <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-gray-500">
            <p>&copy; {{ date('Y') }} Pioneer Missions Africa. All rights reserved.</p>
            <div class="flex gap-6">
                <a href="{{ route('terms') }}" class="hover:text-white transition-colors">Terms of Service</a>
                <a href="{{ route('privacy') }}" class="hover:text-white transition-colors">Privacy Policy</a>
            </div>
        </div>
    </div>
</footer>
