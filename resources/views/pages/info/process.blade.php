@extends('layouts.web')
@section('title', 'Publication Process & Workflow')
@section('meta_description', 'Understand the complete end-to-end journey of a manuscript at HJPARAM, from submission to global impact.')

@section('content')
    <div class="bg-white">
        <!-- Hero Section -->
        <div class="relative py-24 bg-slate-900 overflow-hidden">
            <div class="absolute inset-0 opacity-20">
                <div class="absolute inset-0"
                    style="background-image: radial-gradient(#3b82f6 1px, transparent 1px); background-size: 40px 40px;">
                </div>
            </div>
            <div class="container mx-auto px-4 relative z-10 text-center">
                <span
                    class="inline-block px-4 py-1.5 bg-blue-500/10 text-blue-400 text-[10px] font-bold uppercase tracking-[0.2em] rounded-full mb-6 border border-blue-500/20">How
                    it Works</span>
                <h1 class="text-4xl md:text-6xl font-serif font-bold text-white mb-6">Complete Flow of <span
                        class="text-blue-400">Process</span></h1>
                <p class="text-lg md:text-xl text-slate-400 max-w-2xl mx-auto font-light leading-relaxed">
                    From initial submission to global dissemination, discover the rigorous standards and innovative
                    ecosystem that powers HJPARAM.
                </p>
            </div>
        </div>

        <!-- Visual Workflow Section -->
        <div class="py-24">
            <div class="container mx-auto px-4">
                <div class="max-w-5xl mx-auto">

                    <!-- Stage 1: Submission -->
                    <div class="relative flex flex-col md:flex-row gap-12 items-center mb-32">
                        <div class="w-full md:w-1/2">
                            <div class="relative group">
                                <div
                                    class="absolute -inset-4 bg-blue-100/50 rounded-[40px] blur-2xl group-hover:bg-blue-200/50 transition-all duration-500">
                                </div>
                                <div class="relative bg-white border border-slate-100 p-10 rounded-[32px] shadow-sm">
                                    <div
                                        class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center text-white font-bold text-2xl mb-8 shadow-lg shadow-blue-200">
                                        01</div>
                                    <h3 class="text-2xl font-bold text-slate-900 mb-4">Manuscript Submission</h3>
                                    <p class="text-slate-500 leading-relaxed mb-6">Authors submit their research papers via
                                        our proprietary JAMS (Journal & Article Management System). Every submission
                                        requires metadata, abstract, and a blinded manuscript file.</p>
                                    <ul class="space-y-3 text-sm font-medium text-slate-700">
                                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-blue-500"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z">
                                                </path>
                                            </svg> Dashboard Tracking</li>
                                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-blue-500"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z">
                                                </path>
                                            </svg> Real-time Notifications</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="w-full md:w-1/2 overflow-hidden rounded-3xl shadow-xl shadow-blue-100">
                            <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&q=80&w=800"
                                alt="JAMS Submission Interface"
                                class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-700">
                        </div>
                        <!-- Connection Line -->
                        <div
                            class="hidden md:block absolute -bottom-16 left-1/2 -translate-x-1/2 h-32 w-px bg-gradient-to-b from-blue-200 to-purple-200">
                        </div>
                    </div>

                    <!-- Stage 2: Peer Review -->
                    <div class="relative flex flex-col md:flex-row-reverse gap-12 items-center mb-32">
                        <div class="w-full md:w-1/2">
                            <div class="relative group">
                                <div
                                    class="absolute -inset-4 bg-purple-100/50 rounded-[40px] blur-2xl group-hover:bg-purple-200/50 transition-all duration-500">
                                </div>
                                <div class="relative bg-white border border-slate-100 p-10 rounded-[32px] shadow-sm">
                                    <div
                                        class="w-16 h-16 bg-purple-600 rounded-2xl flex items-center justify-center text-white font-bold text-2xl mb-8 shadow-lg shadow-purple-200">
                                        02</div>
                                    <h3 class="text-2xl font-bold text-slate-900 mb-4">Double-Blind Peer Review</h3>
                                    <p class="text-slate-500 leading-relaxed mb-6">The core of our quality control. Each
                                        paper is evaluated by at least two independent subject experts. Neither the author
                                        nor the reviewer knows each other's identity.</p>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="bg-purple-50 p-4 rounded-xl border border-purple-100">
                                            <div class="text-[10px] font-bold text-purple-600 uppercase mb-1">Pass Rate
                                            </div>
                                            <div class="text-lg font-bold text-slate-900">35% Avg.</div>
                                        </div>
                                        <div class="bg-purple-50 p-4 rounded-xl border border-purple-100">
                                            <div class="text-[10px] font-bold text-purple-600 uppercase mb-1">Timeframe
                                            </div>
                                            <div class="text-lg font-bold text-slate-900">4-6 Weeks</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-full md:w-1/2 overflow-hidden rounded-3xl shadow-xl shadow-purple-100">
                            <img src="https://images.unsplash.com/photo-1551836022-4c4c79ecbb51?auto=format&fit=crop&q=80&w=800"
                                alt="Editorial Decision Logic"
                                class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-700">
                        </div>
                        <!-- Connection Line -->
                        <div
                            class="hidden md:block absolute -bottom-16 left-1/2 -translate-x-1/2 h-32 w-px bg-gradient-to-b from-purple-200 to-emerald-200">
                        </div>
                    </div>

                    <!-- Stage 3: Production & Impact -->
                    <div class="relative flex flex-col md:flex-row gap-12 items-center">
                        <div class="w-full md:w-1/2">
                            <div class="relative group">
                                <div
                                    class="absolute -inset-4 bg-emerald-100/50 rounded-[40px] blur-2xl group-hover:bg-emerald-200/50 transition-all duration-500">
                                </div>
                                <div class="relative bg-white border border-slate-100 p-10 rounded-[32px] shadow-sm">
                                    <div
                                        class="w-16 h-16 bg-emerald-600 rounded-2xl flex items-center justify-center text-white font-bold text-2xl mb-8 shadow-lg shadow-emerald-200">
                                        03</div>
                                    <h3 class="text-2xl font-bold text-slate-900 mb-4">Production & Global Impact</h3>
                                    <p class="text-slate-500 leading-relaxed mb-6">Accepted papers undergo professional
                                        copy-editing, XML conversion, and PDF generation. Once published, the article is
                                        disseminated across our academic ecosystem.</p>
                                    <div class="flex flex-wrap gap-2">
                                        <span
                                            class="px-2 py-1 bg-slate-100 rounded text-[10px] font-bold uppercase text-slate-500 tracking-wider">DOIs</span>
                                        <span
                                            class="px-2 py-1 bg-slate-100 rounded text-[10px] font-bold uppercase text-slate-500 tracking-wider">Scilit
                                            Indexing</span>
                                        <span
                                            class="px-2 py-1 bg-slate-100 rounded text-[10px] font-bold uppercase text-slate-500 tracking-wider">Social
                                            Sharing</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-full md:w-1/2 overflow-hidden rounded-3xl shadow-xl shadow-emerald-100">
                            <img src="https://images.unsplash.com/photo-1507679799987-c73779587ccf?auto=format&fit=crop&q=80&w=800"
                                alt="Knowledge Network Integration"
                                class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-700">
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Technical Connectivity -->
        <div class="bg-slate-50 py-24">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto text-center mb-16">
                    <h2 class="text-3xl font-serif font-bold text-slate-900 mb-4">The Connectivity Flow</h2>
                    <p class="text-slate-500">How we ensure your research reaches the world instantly.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm text-center">
                        <div
                            class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mx-auto mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                        <h4 class="font-bold text-slate-900 mb-2">Sitemap.xml</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">Auto-generates paths for all research, ensuring
                            search engines catch every new publication immediately.</p>
                    </div>
                    <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm text-center">
                        <div
                            class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center mx-auto mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                        <h4 class="font-bold text-slate-900 mb-2">OG Metadata</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">Optimizes research for LinkedIn and ResearchGate
                            with premium-formatted previews and academic snippets.</p>
                    </div>
                    <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm text-center">
                        <div
                            class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mx-auto mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                        <h4 class="font-bold text-slate-900 mb-2">Robots Control</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">Strategic indexing that protects administrative
                            data while maximizing the visibility of your scholarly work.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Final CTA -->
        <div class="py-24 text-center">
            <h2 class="text-3xl font-serif font-bold text-slate-900 mb-8">Ready to start your journey?</h2>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('author.submit') }}"
                    class="bg-blue-600 text-white font-bold py-4 px-10 rounded-full hover:bg-blue-700 transition shadow-xl shadow-blue-200">Submit Manuscript</a>
                <a href="{{ route('info.page', 'proposals') }}"
                    class="bg-white border-2 border-blue-600 text-blue-600 font-bold py-4 px-10 rounded-full hover:bg-blue-50 transition">Submit a Proposal</a>
            </div>
        </div>
    </div>
@endsection