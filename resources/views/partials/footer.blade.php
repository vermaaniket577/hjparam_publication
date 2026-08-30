<footer class="bg-slate-900 text-slate-300 font-sans relative pt-16 pb-8 border-t border-slate-800">
    <!-- Top Decorative Line -->
    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-600 via-indigo-500 to-blue-600"></div>

    <div class="container mx-auto px-6 lg:px-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 mb-12">

            <!-- 1. About HJParam Publication -->
            <div class="lg:col-span-1">
                <a href="{{ route('home') }}" class="flex items-center gap-3 mb-6 group">
                    @if(file_exists(public_path('images/logo.png')))
                        <img src="{{ asset('images/logo.png') }}" alt="HJPARAM" class="h-10 w-auto brightness-200">
                    @else
                        <span class="text-2xl font-serif font-bold text-white tracking-wide">HJPARAM</span>
                    @endif
                </a>
                <p class="text-sm leading-relaxed text-slate-400 mb-6 font-light">
                    HJParam Publication is a leading global publisher of peer-reviewed, open-access journals. We are
                    dedicated to the dissemination of high-quality scientific research, fostering ethical publishing
                    practices, and bridging the gap between academia and society.
                </p>
                <div class="flex gap-4">
                    <!-- Social Icons -->
                    <a href="{{ $settings['linkedin_url'] ?? 'javascript:void(0)' }}"
                        class="w-8 h-8 rounded bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-blue-600 hover:text-white transition-all duration-300">
                        <span class="sr-only">LinkedIn</span>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
                        </svg>
                    </a>
                    <a href="{{ $settings['twitter_url'] ?? 'javascript:void(0)' }}"
                        class="w-8 h-8 rounded bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-blue-600 hover:text-white transition-all duration-300">
                        <span class="sr-only">Twitter</span>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.84 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- 2. Quick Links -->
            <div>
                <h3
                    class="text-white font-serif font-bold tracking-wide uppercase text-sm mb-6 border-b border-slate-700 pb-2 inline-block">
                    Quick Links</h3>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ route('home') }}"
                            class="hover:text-blue-400 transition-colors flex items-center gap-2"><span
                                class="w-1 h-1 bg-blue-500 rounded-full opacity-0 hover:opacity-100 transition-opacity"></span>Home</a>
                    </li>
                    <li><a href="{{ route('about.page', 'about') }}"
                            class="hover:text-blue-400 transition-colors flex items-center gap-2">About Us</a></li>
                    <li><a href="{{ route('journals.index') }}"
                            class="hover:text-blue-400 transition-colors flex items-center gap-2">Journals</a></li>
                    <li><a href="{{ route('author.page', 'call-for-papers') }}"
                            class="hover:text-blue-400 transition-colors flex items-center gap-2">Call for Papers</a>
                    </li>
                    <li><a href="{{ route('about.page', 'editorial-board') }}"
                            class="hover:text-blue-400 transition-colors flex items-center gap-2">Editorial Board</a>
                    </li>
                    <li><a href="{{ route('info.page', 'ethics') }}"
                            class="hover:text-blue-400 transition-colors flex items-center gap-2">Publication Ethics</a>
                    </li>
                    <li><a href="{{ route('about.page', 'contact') }}"
                            class="hover:text-blue-400 transition-colors flex items-center gap-2">Contact Us</a></li>
                </ul>
            </div>

            <!-- 3. Author Resources -->
            <div>
                <h3
                    class="text-white font-serif font-bold tracking-wide uppercase text-sm mb-6 border-b border-slate-700 pb-2 inline-block">
                    Author Resources</h3>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ route('author.guidelines') }}"
                            class="hover:text-blue-400 transition-colors">Submission Guidelines</a></li>
                    <li><a href="{{ route('author.page', 'apc') }}"
                            class="hover:text-blue-400 transition-colors">Publication Fees</a></li>
                    <li><a href="{{ route('info.page', 'peer-review-process') }}"
                            class="hover:text-blue-400 transition-colors">Review Process</a></li>
                    <li><a href="{{ route('info.page', 'copyright') }}"
                            class="hover:text-blue-400 transition-colors">Copyright & Licensing</a></li>
                    <li><a href="{{ route('info.page', 'plagiarism-check') }}"
                            class="hover:text-blue-400 transition-colors">Plagiarism Policy</a></li>
                    <li><a href="{{ route('info.page', 'open-access-policy') }}"
                            class="hover:text-blue-400 transition-colors">Open Access Policy</a></li>
                </ul>
            </div>

            <!-- 4. Legal & Policies -->
            <div>
                <h3
                    class="text-white font-serif font-bold tracking-wide uppercase text-sm mb-6 border-b border-slate-700 pb-2 inline-block">
                    Legal & Policies</h3>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ route('info.page', 'privacy') }}"
                            class="hover:text-blue-400 transition-colors">Privacy Policy</a></li>
                    <li><a href="{{ route('info.page', 'terms') }}" class="hover:text-blue-400 transition-colors">Terms
                            & Conditions</a></li>
                    <li><a href="{{ route('info.page', 'disclaimer') }}"
                            class="hover:text-blue-400 transition-colors">Disclaimer</a></li>
                    <li><a href="{{ route('info.page', 'retraction') }}"
                            class="hover:text-blue-400 transition-colors">Retraction Policy</a></li>
                    <li><a href="{{ route('info.page', 'conflict-interest') }}"
                            class="hover:text-blue-400 transition-colors">Conflict of Interest</a></li>
                </ul>
            </div>

            <!-- 5. Contact Information -->
            <div>
                <h3
                    class="text-white font-serif font-bold tracking-wide uppercase text-sm mb-6 border-b border-slate-700 pb-2 inline-block">
                    Contact</h3>
                <ul class="space-y-4 text-sm">
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <div>
                            <span class="block text-xs uppercase text-slate-500 font-bold mb-1">Official Email</span>
                            <a href="mailto:{{ $settings['contact_email'] ?? 'info@hjparam.com' }}"
                                class="text-white hover:text-blue-400 transition-colors font-medium">{{
                                $settings['contact_email'] ?? 'info@hjparam.com' }}</a>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <div>
                            <span class="block text-xs uppercase text-slate-500 font-bold mb-1">Support</span>
                            <a href="mailto:{{ $settings['support_email'] ?? 'support@hjparam.com' }}"
                                class="text-white hover:text-blue-400 transition-colors">{{ $settings['support_email']
                                ?? 'support@hjparam.com' }}</a>
                        </div>
                    </li>
                    @if(isset($settings['contact_phone']))
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <div>
                                <span class="block text-xs uppercase text-slate-500 font-bold mb-1">Phone</span>
                                <a href="tel:{{ $settings['contact_phone'] }}"
                                    class="text-white hover:text-blue-400 transition-colors">{{ $settings['contact_phone'] }}</a>
                            </div>
                        </li>
                    @endif
                    @if(isset($settings['office_address']))
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <div>
                                <span class="block text-xs uppercase text-slate-500 font-bold mb-1">Office Address</span>
                                <span
                                    class="text-white text-sm whitespace-pre-line leading-relaxed block">{{ $settings['office_address'] }}</span>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>

        </div>

        <!-- Bottom Bar -->
        <div
            class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-slate-500">
            <div class="text-center md:text-left">
                <p>&copy; {{ date('Y') }} HJParam Publication. All Rights Reserved.</p>
            </div>

            <div class="flex items-center gap-6">
                @if(($settings['enable_rss'] ?? '0') == '1')
                    <a href="{{ route('rss.feed') }}"
                        class="hover:text-blue-400 transition-colors flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M6.18,15.64A2.18,2.18,0,0,1,8.36,17.82,2.18,2.18,0,0,1,6.18,20,2.18,2.18,0,0,1,4,17.82,2.18,2.18,0,0,1,6.18,15.64ZM4,4.44A15.56,15.56,0,0,1,19.56,20h-2.83A12.73,12.73,0,0,0,4,7.27Zm0,5.66a9.9,9.9,0,0,1,9.9,9.9H11.07A7.07,7.07,0,0,0,4,12.93Z" />
                        </svg>
                        RSS
                    </a>
                @endif
                <span>Open Access. Peer Reviewed. Global Impact.</span>
            </div>
        </div>
    </div>
</footer>