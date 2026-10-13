@extends('layouts.web')

@section('title', 'About HJPARAM Publication')
@section('meta_description', 'Learn about HJPARAM Publication, our mission, publishing ethics, and our commitment to advancing open access research globally.')

@section('content')
    <!-- Hero Section with Integrated Showcase Video -->
    <section class="relative bg-slate-950 text-white py-16 lg:py-24 overflow-hidden border-b border-slate-800/80">
        <!-- Subtle Ambient Background Glows -->
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 -right-32 w-96 h-96 bg-emerald-600/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute inset-0 bg-[radial-gradient(#1e293b_1px,transparent_1px)] [background-size:24px_24px] opacity-25 pointer-events-none"></div>

        <div class="container mx-auto px-6 lg:px-12 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-center">
                
                <!-- Left Column: Title, Subtitle & Key Highlights -->
                <div class="lg:col-span-7 space-y-6">
                    <!-- Category Badge -->
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-black uppercase tracking-[0.25em]">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        About HJPARAM Publication
                    </div>

                    <!-- Main Heading -->
                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-serif font-black tracking-tight leading-[1.15]">
                        Advancing the Frontiers of <span class="bg-gradient-to-r from-blue-400 via-cyan-300 to-emerald-400 bg-clip-text text-transparent">Open Science</span> through Integrity and Innovation.
                    </h1>

                    <!-- Lead Paragraph -->
                    <p class="text-base sm:text-lg lg:text-xl leading-relaxed text-slate-300 font-light max-w-2xl">
                        HJPARAM is a premier academic publishing platform dedicated to accelerating the global dissemination of peer-reviewed research. We empower scholars with the tools, ethical rigor, and international visibility needed to make an enduring impact across disciplines.
                    </p>

                    <!-- Trust Signals / Metric Badges -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-2">
                        <div class="bg-slate-900/80 border border-slate-800 rounded-xl p-3 text-center backdrop-blur-sm">
                            <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Review</div>
                            <div class="text-sm font-black text-emerald-400 mt-0.5">Double-Blind</div>
                        </div>
                        <div class="bg-slate-900/80 border border-slate-800 rounded-xl p-3 text-center backdrop-blur-sm">
                            <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Access</div>
                            <div class="text-sm font-black text-blue-400 mt-0.5">100% Open</div>
                        </div>
                        <div class="bg-slate-900/80 border border-slate-800 rounded-xl p-3 text-center backdrop-blur-sm">
                            <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">License</div>
                            <div class="text-sm font-black text-amber-400 mt-0.5">CC BY 4.0</div>
                        </div>
                        <div class="bg-slate-900/80 border border-slate-800 rounded-xl p-3 text-center backdrop-blur-sm">
                            <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Ethics</div>
                            <div class="text-sm font-black text-purple-400 mt-0.5">COPE Aligned</div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap items-center gap-4 pt-2">
                        <a href="{{ route('journals.index') }}" 
                           class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-sm shadow-lg shadow-blue-600/30 transition-all hover:scale-[1.02] active:scale-[0.98]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            Explore Journals
                        </a>
                        <a href="{{ route('about.page', 'editorial-board') }}" 
                           class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl bg-slate-800/80 hover:bg-slate-800 text-slate-200 border border-slate-700 font-bold text-sm transition-all hover:scale-[1.02]">
                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Editorial Board
                        </a>
                    </div>
                </div>

                <!-- Right Column: Interactive Video Showcase Player -->
                <div class="lg:col-span-5">
                    <div class="relative group">
                        <!-- Glowing Backlight / Ambient Aura -->
                        <div class="absolute -inset-2 bg-gradient-to-tr from-blue-600 via-indigo-500 to-emerald-500 rounded-3xl blur-2xl opacity-35 group-hover:opacity-55 transition duration-700"></div>

                        <!-- Card Wrapper -->
                        <div class="relative rounded-2xl sm:rounded-3xl overflow-hidden border border-white/20 bg-slate-900/95 shadow-2xl shadow-blue-950/80 backdrop-blur-xl p-3 sm:p-4">
                            
                            <!-- Video Container (16:9) - Pure & Unobstructed -->
                            <div class="relative aspect-video w-full rounded-xl sm:rounded-2xl overflow-hidden bg-black select-none cursor-pointer group/video" id="aboutVideoWrapper">
                                <video id="aboutHeroVideo"
                                       autoplay
                                       muted
                                       loop
                                       playsinline
                                       preload="auto"
                                       class="w-full h-full object-cover">
                                    <source src="{{ asset('videos/about-hero.mp4') }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>

                                <!-- Play/Pause Center Indicator (shows on hover or when paused) -->
                                <div id="centerPlayIndicator"
                                     class="absolute inset-0 m-auto w-16 h-16 rounded-full bg-blue-600/90 text-white flex items-center justify-center shadow-xl shadow-blue-600/50 backdrop-blur-md border border-white/30 opacity-0 group-hover/video:opacity-100 transition-all duration-300 transform group-hover/video:scale-105 pointer-events-none z-10">
                                    <svg id="centerPlayIcon" class="w-7 h-7 ml-0.5 hidden" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z"/>
                                    </svg>
                                    <svg id="centerPauseIcon" class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                                    </svg>
                                </div>

                                <!-- Floating Sound Toggle Pill (Top-Right, compact, unobtrusive) -->
                                <button type="button" 
                                        id="quickSoundBtn"
                                        aria-label="Toggle Sound"
                                        class="absolute top-3 right-3 z-20 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-950/80 hover:bg-slate-900 text-xs font-semibold text-slate-200 border border-white/20 backdrop-blur-md shadow-lg transition-all hover:scale-105 cursor-pointer">
                                    <svg id="quickMutedIcon" class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"/>
                                    </svg>
                                    <svg id="quickUnmutedIcon" class="w-3.5 h-3.5 text-emerald-400 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                                    </svg>
                                    <span id="quickSoundText">Unmute</span>
                                </button>
                            </div>

                            <!-- Dedicated External Control Deck (Located BELOW video so video text is NEVER blocked) -->
                            <div class="mt-3 px-1.5 pt-1 pb-1 space-y-2">
                                <!-- Progress Bar (Scrubber) -->
                                <div class="relative w-full bg-slate-800 hover:bg-slate-700/80 rounded-full h-1.5 overflow-hidden cursor-pointer transition-colors group/progress" id="videoProgressBar" title="Click to seek">
                                    <div id="videoProgressFill" class="bg-gradient-to-r from-blue-500 to-cyan-400 h-full w-0 transition-all duration-100 rounded-full"></div>
                                </div>

                                <!-- Control Buttons & Timers Row -->
                                <div class="flex items-center justify-between text-xs text-slate-300">
                                    <!-- Left: Play/Pause & Time -->
                                    <div class="flex items-center gap-2.5">
                                        <button type="button" 
                                                id="ctrlPlayBtn" 
                                                class="w-7 h-7 rounded-lg bg-white/10 hover:bg-blue-600 hover:text-white flex items-center justify-center transition-all focus:outline-none cursor-pointer"
                                                aria-label="Play/Pause">
                                            <svg id="ctrlPlayIcon" class="w-3.5 h-3.5 hidden ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z"/>
                                            </svg>
                                            <svg id="ctrlPauseIcon" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                                            </svg>
                                        </button>
                                        <span id="videoTimeDisplay" class="font-mono text-[11px] text-slate-400">00:00 / 00:10</span>
                                    </div>

                                    <!-- Right: Sound & Fullscreen -->
                                    <div class="flex items-center gap-2">
                                        <button type="button" 
                                                id="ctrlMuteBtn" 
                                                class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-[11px] font-semibold text-slate-200 transition-colors focus:outline-none cursor-pointer" 
                                                aria-label="Toggle Sound">
                                            <svg id="ctrlMutedIcon" class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"/>
                                            </svg>
                                            <svg id="ctrlUnmutedIcon" class="w-3.5 h-3.5 text-emerald-400 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                                            </svg>
                                            <span id="ctrlMuteText">Unmute</span>
                                        </button>

                                        <button type="button" 
                                                id="ctrlFullscreenBtn" 
                                                class="w-7 h-7 rounded-lg bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition-colors focus:outline-none cursor-pointer" 
                                                aria-label="Fullscreen" 
                                                title="Fullscreen">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-5h-4m4 0v4m0-4l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="py-24 bg-white">
        <div class="container mx-auto px-6 lg:px-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-20">
                <div class="lg:col-span-8 space-y-16">
                    {{-- Introduction --}}
                    <article class="prose prose-slate prose-lg max-w-none">
                        <h2 class="text-3xl font-serif font-bold text-slate-900 mb-8 border-l-4 border-blue-600 pl-6">Our Vision for Academic Excellence</h2>
                        <p class="text-slate-600 leading-relaxed text-lg">
                            Founded on the principles of transparency and accessibility, HJPARAM Publication serves as a vital bridge between groundbreaking research and the global scientific community. In an era where information is abundant but quality is paramount, we maintain a steadfast commitment to rigorous peer review and ethical publishing standards.
                        </p>
                        <p class="text-slate-600 leading-relaxed text-lg">
                            Our platform is designed to meet the evolving needs of modern researchers. By integrating advanced digital workflows with traditional scholarly values, we ensure that every article published under the HJPARAM banner undergoes a thorough evaluation by experts in the field. This commitment to quality ensures that our contributors gain the recognition they deserve while providing readers with trustworthy, actionable knowledge.
                        </p>

                        <h2 class="text-3xl font-serif font-bold text-slate-900 mt-16 mb-8 border-l-4 border-emerald-600 pl-6">Publishing Ethics & Integrity</h2>
                        <p class="text-slate-600 leading-relaxed text-lg">
                            Integrity is the cornerstone of academic publishing. HJPARAM strictly adheres to the guidelines set forth by the Committee on Publication Ethics (COPE). We implement robust plagiarism detection systems and double-blind peer-review processes to safeguard the originality and technical accuracy of our publications.
                        </p>
                        <p class="text-slate-600 leading-relaxed text-lg">
                            Our editorial board consists of distinguished scholars who provide oversight and strategic direction, ensuring that our journals remain at the forefront of their respective fields. We advocate for reviewer accountability and provide authors with constructive, detailed feedback to help refine and enhance their scholarly work.
                        </p>

                        <h2 class="text-3xl font-serif font-bold text-slate-900 mt-16 mb-8 border-l-4 border-amber-600 pl-6">Commitment to Open Access</h2>
                        <p class="text-slate-600 leading-relaxed text-lg">
                            We believe that knowledge should have no boundaries. As a champion of the Open Access movement, HJPARAM ensures that all published research is freely available to the public immediately upon publication. This model maximizes the reach and impact of research, fostering collaboration across borders and disciplines.
                        </p>
                        <p class="text-slate-600 leading-relaxed text-lg">
                            By removing paywalls, we enable researchers from developing regions, policy makers, and the general public to engage with the latest scientific discoveries. Our Open Access policy is compliant with major funding mandates, including Plan S and various national research council requirements.
                        </p>

                        <h2 class="text-3xl font-serif font-bold text-slate-900 mt-16 mb-8 border-l-4 border-purple-600 pl-6">Global Research Scope</h2>
                        <p class="text-slate-600 leading-relaxed text-lg">
                            HJPARAM hosts a diverse portfolio of journals covering Science, Engineering, Medicine, Social Sciences, and the Humanities. Our interdisciplinary approach encourages the cross-pollination of ideas, leading to innovative solutions for global challenges. Whether it is sustainable energy, advanced medical diagnostics, or socio-economic policy, our journals provide a platform for research that matters.
                        </p>
                    </article>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 py-12 border-y border-slate-100">
                        <div class="flex gap-6">
                            <div class="flex-shrink-0 w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center font-bold">01</div>
                            <div>
                                <h4 class="font-bold text-slate-900 mb-2">Rapid Publication</h4>
                                <p class="text-sm text-slate-500">Efficient editorial workflows ensure timely review and publication without compromising quality.</p>
                            </div>
                        </div>
                        <div class="flex gap-6">
                            <div class="flex-shrink-0 w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center font-bold">02</div>
                            <div>
                                <h4 class="font-bold text-slate-900 mb-2">Global Indexing</h4>
                                <p class="text-sm text-slate-500">Integration with Google Scholar, Scopus, and DOAJ to maximize your research visibility.</p>
                            </div>
                        </div>
                        <div class="flex gap-6">
                            <div class="flex-shrink-0 w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center font-bold">03</div>
                            <div>
                                <h4 class="font-bold text-slate-900 mb-2">Expert Board</h4>
                                <p class="text-sm text-slate-500">Guided by an international panel of experts dedicated to scholarly excellence.</p>
                            </div>
                        </div>
                        <div class="flex gap-6">
                            <div class="flex-shrink-0 w-12 h-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center font-bold">04</div>
                            <div>
                                <h4 class="font-bold text-slate-900 mb-2">Impact Analytics</h4>
                                <p class="text-sm text-slate-500">Real-time metrics for views, downloads, and citations to track your research influence.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-4">
                    <aside class="sticky top-24 space-y-8">
                        {{-- Trust Signals --}}
                        <div class="rounded-3xl bg-slate-50 border border-slate-100 p-8">
                            <h3 class="text-lg font-black text-slate-900 mb-6 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Trust & Standards
                            </h3>
                            <ul class="space-y-4">
                                <li class="flex items-center gap-3 text-sm font-bold text-slate-600">
                                    <span class="w-1.5 h-1.5 bg-blue-600 rounded-full"></span>
                                    COPE Compliant
                                </li>
                                <li class="flex items-center gap-3 text-sm font-bold text-slate-600">
                                    <span class="w-1.5 h-1.5 bg-blue-600 rounded-full"></span>
                                    Crossref Member
                                </li>
                                <li class="flex items-center gap-3 text-sm font-bold text-slate-600">
                                    <span class="w-1.5 h-1.5 bg-blue-600 rounded-full"></span>
                                    ORCID Integration
                                </li>
                                <li class="flex items-center gap-3 text-sm font-bold text-slate-600">
                                    <span class="w-1.5 h-1.5 bg-blue-600 rounded-full"></span>
                                    Creative Commons (CC BY)
                                </li>
                            </ul>
                        </div>

                        {{-- Quick Navigation --}}
                        <div class="rounded-3xl bg-[#0f172a] p-8 text-white">
                            <h3 class="text-sm font-black uppercase tracking-widest mb-6">Resources</h3>
                            <div class="space-y-3">
                                <a href="{{ route('author.guidelines') }}" class="flex items-center justify-between p-4 rounded-xl bg-white/5 border border-white/5 hover:bg-white/10 transition-all text-sm font-bold">
                                    Author Guidelines
                                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2.5"></path></svg>
                                </a>
                                <a href="{{ route('info.page', 'peer-review-process') }}" class="flex items-center justify-between p-4 rounded-xl bg-white/5 border border-white/5 hover:bg-white/10 transition-all text-sm font-bold">
                                    Review Process
                                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2.5"></path></svg>
                                </a>
                                <a href="{{ route('about.page', 'editorial-board') }}" class="flex items-center justify-between p-4 rounded-xl bg-white/5 border border-white/5 hover:bg-white/10 transition-all text-sm font-bold">
                                    Editorial Board
                                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2.5"></path></svg>
                                </a>
                            </div>
                        </div>

                        {{-- Institutional Contact --}}
                        <div class="rounded-3xl bg-blue-600 p-8 text-white shadow-2xl shadow-blue-600/30">
                            <h3 class="text-lg font-black mb-4">Partner with Us</h3>
                            <p class="text-blue-100 text-sm leading-relaxed mb-8">We offer specialized publishing solutions for academic societies and institutions.</p>
                            <a href="{{ route('contact.index') }}" class="block w-full py-4 bg-white text-blue-600 rounded-xl font-black text-xs uppercase tracking-widest text-center hover:bg-blue-50 transition-all">Contact Partnerships</a>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </section>

    <!-- Video Showcase Controller Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const video = document.getElementById('aboutHeroVideo');
            if (!video) return;

            const centerPlayIndicator = document.getElementById('centerPlayIndicator');
            const centerPlayIcon = document.getElementById('centerPlayIcon');
            const centerPauseIcon = document.getElementById('centerPauseIcon');

            const ctrlPlayBtn = document.getElementById('ctrlPlayBtn');
            const ctrlPlayIcon = document.getElementById('ctrlPlayIcon');
            const ctrlPauseIcon = document.getElementById('ctrlPauseIcon');

            const ctrlMuteBtn = document.getElementById('ctrlMuteBtn');
            const ctrlMutedIcon = document.getElementById('ctrlMutedIcon');
            const ctrlUnmutedIcon = document.getElementById('ctrlUnmutedIcon');
            const ctrlMuteText = document.getElementById('ctrlMuteText');

            const quickSoundBtn = document.getElementById('quickSoundBtn');
            const quickMutedIcon = document.getElementById('quickMutedIcon');
            const quickUnmutedIcon = document.getElementById('quickUnmutedIcon');
            const quickSoundText = document.getElementById('quickSoundText');

            const progressBar = document.getElementById('videoProgressBar');
            const progressFill = document.getElementById('videoProgressFill');
            const timeDisplay = document.getElementById('videoTimeDisplay');
            const fullscreenBtn = document.getElementById('ctrlFullscreenBtn');
            const wrapper = document.getElementById('aboutVideoWrapper');

            function formatTime(seconds) {
                if (isNaN(seconds)) return '00:00';
                const m = Math.floor(seconds / 60).toString().padStart(2, '0');
                const s = Math.floor(seconds % 60).toString().padStart(2, '0');
                return `${m}:${s}`;
            }

            function updatePlayState() {
                if (video.paused) {
                    if (centerPlayIcon) centerPlayIcon.classList.remove('hidden');
                    if (centerPauseIcon) centerPauseIcon.classList.add('hidden');
                    if (ctrlPlayIcon) ctrlPlayIcon.classList.remove('hidden');
                    if (ctrlPauseIcon) ctrlPauseIcon.classList.add('hidden');
                    if (centerPlayIndicator) centerPlayIndicator.classList.remove('opacity-0');
                } else {
                    if (centerPlayIcon) centerPlayIcon.classList.add('hidden');
                    if (centerPauseIcon) centerPauseIcon.classList.remove('hidden');
                    if (ctrlPlayIcon) ctrlPlayIcon.classList.add('hidden');
                    if (ctrlPauseIcon) ctrlPauseIcon.classList.remove('hidden');
                    if (centerPlayIndicator) centerPlayIndicator.classList.add('opacity-0');
                }
            }

            function togglePlay() {
                if (video.paused) {
                    video.play();
                } else {
                    video.pause();
                }
                updatePlayState();
            }

            function updateSoundState() {
                if (video.muted) {
                    if (ctrlMutedIcon) ctrlMutedIcon.classList.remove('hidden');
                    if (ctrlUnmutedIcon) ctrlUnmutedIcon.classList.add('hidden');
                    if (ctrlMuteText) ctrlMuteText.textContent = 'Unmute';

                    if (quickMutedIcon) quickMutedIcon.classList.remove('hidden');
                    if (quickUnmutedIcon) quickUnmutedIcon.classList.add('hidden');
                    if (quickSoundText) quickSoundText.textContent = 'Unmute';
                } else {
                    if (ctrlMutedIcon) ctrlMutedIcon.classList.add('hidden');
                    if (ctrlUnmutedIcon) ctrlUnmutedIcon.classList.remove('hidden');
                    if (ctrlMuteText) ctrlMuteText.textContent = 'Mute';

                    if (quickMutedIcon) quickMutedIcon.classList.add('hidden');
                    if (quickUnmutedIcon) quickUnmutedIcon.classList.remove('hidden');
                    if (quickSoundText) quickSoundText.textContent = 'Mute';
                }
            }

            function toggleMute(e) {
                if (e) e.stopPropagation();
                video.muted = !video.muted;
                updateSoundState();
            }

            if (ctrlPlayBtn) ctrlPlayBtn.addEventListener('click', togglePlay);
            if (video) video.addEventListener('click', togglePlay);

            if (ctrlMuteBtn) ctrlMuteBtn.addEventListener('click', toggleMute);
            if (quickSoundBtn) quickSoundBtn.addEventListener('click', toggleMute);

            video.addEventListener('play', updatePlayState);
            video.addEventListener('pause', updatePlayState);

            video.addEventListener('timeupdate', function () {
                if (!video.duration) return;
                const percent = (video.currentTime / video.duration) * 100;
                if (progressFill) progressFill.style.width = percent + '%';
                if (timeDisplay) {
                    timeDisplay.textContent = `${formatTime(video.currentTime)} / ${formatTime(video.duration || 10)}`;
                }
            });

            video.addEventListener('loadedmetadata', function () {
                if (timeDisplay) {
                    timeDisplay.textContent = `00:00 / ${formatTime(video.duration || 10)}`;
                }
            });

            if (progressBar) {
                progressBar.addEventListener('click', function (e) {
                    const rect = progressBar.getBoundingClientRect();
                    const clickPos = (e.clientX - rect.left) / rect.width;
                    if (video.duration) {
                        video.currentTime = clickPos * video.duration;
                    }
                });
            }

            if (fullscreenBtn) {
                fullscreenBtn.addEventListener('click', function () {
                    const target = wrapper || video;
                    if (!document.fullscreenElement) {
                        if (target.requestFullscreen) {
                            target.requestFullscreen();
                        } else if (target.webkitRequestFullscreen) {
                            target.webkitRequestFullscreen();
                        } else if (video.webkitEnterFullscreen) {
                            video.webkitEnterFullscreen();
                        }
                    } else {
                        if (document.exitFullscreen) {
                            document.exitFullscreen();
                        }
                    }
                });
            }
        });
    </script>
@endsection

