<footer class="bg-[var(--color-indigo-dark)] text-white/80 pt-20 pb-10 relative overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none opacity-10">
        <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-[var(--color-pma-green)] blur-[100px]"></div>
        <div class="absolute bottom-[10%] right-[5%] w-[30%] h-[30%] rounded-full bg-[var(--color-ochre)] blur-[80px]"></div>
    </div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-12 mb-16">
            <!-- Brand Column -->
            <div class="lg:col-span-3">
                <div class="relative inline-block mb-6 group">
                    <a href="{{ route('home') }}">
                        <img src="{{ url('images/PMALogoWhiteText.png') }}" alt="Pioneer Missions Africa" class="h-12 brightness-0 invert opacity-90">
                    </a>
                    <a href="{{ route('filament.admin.auth.login') }}" class="absolute -bottom-5 left-0 text-[10px] text-white/0 group-hover:text-white/30 transition-all duration-300">
                        Admin
                    </a>
                </div>
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
                    <a href="https://open.spotify.com/artist/1WJPk0nqgN8pBPqXUk1Wjj" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-[#1DB954] hover:text-white transition-all duration-300">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.66 0 12 0zm5.521 17.34c-.24.359-.66.48-1.021.24-2.82-1.74-6.36-2.101-10.561-1.141-.418.122-.779-.179-.899-.539-.12-.421.18-.78.54-.9 4.56-1.021 8.52-.6 11.64 1.32.42.18.479.659.301 1.02zm1.44-3.3c-.301.42-.841.6-1.262.3-3.239-1.98-8.159-2.58-11.939-1.38-.479.12-1.02-.12-1.14-.6-.12-.48.12-1.021.6-1.141C9.6 9.9 15 10.561 18.72 12.84c.361.181.54.78.241 1.2zm.12-3.36C15.24 8.4 8.82 8.16 5.16 9.301c-.6.179-1.2-.181-1.38-.721-.18-.601.18-1.2.72-1.381 4.26-1.26 11.28-1.02 15.721 1.621.539.3.719 1.02.419 1.56-.299.421-1.02.599-1.559.3z"/>
                        </svg>
                    </a>
                    <a href="https://music.apple.com/za/artist/pma-worship/1865116757" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-[#FA243C] hover:text-white transition-all duration-300">
                        <img src="{{ asset('images/apple-music-icon.png') }}" alt="Apple Music" class="w-5 h-5">
                    </a>
                    <a href="https://music.youtube.com/channel/UCb5kpOoNiwCCAFgFm76oNTQ" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-[#FF0000] hover:text-white transition-all duration-300">
                        <img src="{{ asset('images/youtube-music-icon.png') }}" alt="YouTube Music" class="w-5 h-5">
                    </a>
                </div>
            </div>

            <!-- Discover -->
            <div class="lg:col-span-3">
                <h4 class="text-white font-bold text-lg mb-6">Discover</h4>
                <ul class="space-y-4">
                    <li><a href="{{ route('about') }}" class="hover:text-[var(--color-ochre)] transition-colors">About Us</a></li>
                    <li><a href="{{ route('about.principles') }}" class="hover:text-[var(--color-ochre)] transition-colors">Our Principles</a></li>
                    <li><a href="{{ route('network.index') }}" class="hover:text-[var(--color-ochre)] transition-colors">The Network</a></li>
                    <li><a href="{{ route('prayer-room.index') }}" class="hover:text-[var(--color-ochre)] transition-colors">Prayer Room</a></li>
                    <li><a href="{{ route('kingdom-kids') }}" class="hover:text-[var(--color-ochre)] transition-colors">Kingdom Kids</a></li>
                </ul>
            </div>

            <!-- Media & Resources -->
            <div class="lg:col-span-3">
                <h4 class="text-white font-bold text-lg mb-6">Media</h4>
                <ul class="space-y-4">
                    <li><a href="{{ route('sermons') }}" class="hover:text-[var(--color-ochre)] transition-colors">Sermons</a></li>
                    <li><a href="{{ route('shorts') }}" class="hover:text-[var(--color-ochre)] transition-colors">Shorts</a></li>
                    <li><a href="{{ route('studies') }}" class="hover:text-[var(--color-ochre)] transition-colors">Bible Studies</a></li>
                    <li><a href="{{ route('gallery') }}" class="hover:text-[var(--color-ochre)] transition-colors">Gallery</a></li>
                    <li><a href="{{ route('resources') }}" class="hover:text-[var(--color-ochre)] transition-colors">Resources</a></li>
                </ul>
            </div>

            <!-- Support -->
            <div class="lg:col-span-3">
                <h4 class="text-white font-bold text-lg mb-6">Support</h4>
                <ul class="space-y-4">
                    <li><a href="{{ route('donate') }}" class="hover:text-[var(--color-ochre)] transition-colors">Donate</a></li>
                    <li><a href="{{ route('pledge') }}" class="hover:text-[var(--color-ochre)] transition-colors">Monthly Pledge</a></li>
                    <li><a href="{{ route('about.support') }}" class="hover:text-[var(--color-ochre)] transition-colors">Volunteer</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-[var(--color-ochre)] transition-colors">Contact</a></li>
                    <li><a href="{{ route('privacy') }}" class="hover:text-[var(--color-ochre)] transition-colors">Privacy Policy</a></li>
                </ul>
            </div>
        </div>

        <!-- Newsletter -->
        <div class="lg:col-span-12 mt-12">
            <h4 class="text-white font-bold text-lg mb-6">Stay Connected</h4>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div>
                    <p class="text-gray-400 mb-4 text-sm">Join our newsletter to receive updates on new studies and mission reports.</p>
                    <livewire:newsletter-signup variant="footer" />
                </div>
                <div>
                    <div class="p-4 bg-white/5 rounded-l border border-white/10">
                        <p class="text-sm text-gray-400 mb-1">Need prayer?</p>
                        <a href="mailto:prayers@pioneermissionsafrica.co.za" class="text-white font-medium hover:text-[var(--color-ochre)] transition-colors flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            prayers@pioneermissionsafrica.co.za
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-gray-500">
            <p>&copy; {{ date('Y') }} Pioneer Missions Africa. All rights reserved.</p>
            <div class="flex gap-6">
                <a href="{{ route('terms') }}" class="hover:text-white transition-colors">Terms of Service</a>
                <a href="{{ route('privacy') }}" class="hover:text-white transition-colors">Privacy Policy</a>
            </div>
        </div>
    </div>
</footer>
