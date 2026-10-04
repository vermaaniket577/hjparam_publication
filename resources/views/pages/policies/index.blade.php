@extends('layouts.web')
@section('title', 'Journal Policies & Editorial Governance | HJPARAM Publication')

@section('content')
<div class="bg-slate-50/60 py-12 md:py-16">
    <div class="container mx-auto px-4 lg:px-8 max-w-7xl">
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-8">
            <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Home</a>
            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-blue-900 font-bold">Journal Policies</span>
        </nav>

        <!-- Hero Header -->
        <div class="bg-gradient-to-r from-blue-950 via-slate-900 to-indigo-950 rounded-[2.5rem] p-8 md:p-14 text-white shadow-2xl relative overflow-hidden mb-12">
            <div class="absolute -right-16 -top-16 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-16 -bottom-16 w-80 h-80 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="relative z-10 max-w-3xl">
                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-blue-500/20 border border-blue-400/30 rounded-full text-xs font-bold uppercase tracking-wider text-blue-300 mb-6">
                    <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                    Scholarly Publishing Governance
                </span>
                <h1 class="text-3xl md:text-5xl font-serif font-black tracking-tight text-white mb-6">
                    Journal Policies & <span class="text-blue-400">Editorial Standards</span>
                </h1>
                <p class="text-slate-300 text-base md:text-lg leading-relaxed mb-8">
                    HJPARAM Publication operates under strict international publishing ethics and transparent governance. Explore our comprehensive policies covering peer review, open access licensing, permanent digital preservation, CrossMark versioning, and editorial integrity.
                </p>

                <!-- Badges -->
                <div class="flex flex-wrap items-center gap-3 pt-2 text-xs font-semibold text-slate-200">
                    <div class="flex items-center gap-1.5 px-3 py-1.5 bg-white/10 rounded-xl backdrop-blur-xs">
                        <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                        <span>COPE Member Best Practices</span>
                    </div>
                    <div class="flex items-center gap-1.5 px-3 py-1.5 bg-white/10 rounded-xl backdrop-blur-xs">
                        <svg class="w-4 h-4 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                        <span>Gold Open Access (CC BY 4.0)</span>
                    </div>
                    <div class="flex items-center gap-1.5 px-3 py-1.5 bg-white/10 rounded-xl backdrop-blur-xs">
                        <svg class="w-4 h-4 text-indigo-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                        <span>Crossref & CrossMark Signatory</span>
                    </div>
                    <div class="flex items-center gap-1.5 px-3 py-1.5 bg-white/10 rounded-xl backdrop-blur-xs">
                        <svg class="w-4 h-4 text-purple-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                        <span>CLOCKSS / Portico Preserved</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Sidebar -->
            <div class="lg:col-span-4 lg:sticky lg:top-24">
                @include('pages.policies.sidebar')
            </div>

            <!-- Main Policy Directory & Flow -->
            <div class="lg:col-span-8 space-y-10">
                <!-- Publication Lifecycle Flow Diagram -->
                <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm">
                    <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-100">
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-widest text-blue-600 block">Editorial Architecture</span>
                            <h2 class="text-xl font-serif font-bold text-slate-900">Complete Process Flow of Manuscript Governance</h2>
                        </div>
                        <span class="text-xs font-bold text-slate-400 bg-slate-100 px-3 py-1 rounded-full">End-to-End</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="p-4 rounded-2xl bg-blue-50/60 border border-blue-100 relative group hover:shadow-md transition">
                            <div class="w-7 h-7 rounded-lg bg-blue-600 text-white font-bold text-xs flex items-center justify-center mb-3">1</div>
                            <h4 class="font-bold text-sm text-slate-900 mb-1">Pre-Check & Screening</h4>
                            <p class="text-xs text-slate-600 leading-relaxed">Scope validation, iThenticate plagiarism screening, and ethical approvals verification.</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-purple-50/60 border border-purple-100 relative group hover:shadow-md transition">
                            <div class="w-7 h-7 rounded-lg bg-purple-600 text-white font-bold text-xs flex items-center justify-center mb-3">2</div>
                            <h4 class="font-bold text-sm text-slate-900 mb-1">Double-Blind Review</h4>
                            <p class="text-xs text-slate-600 leading-relaxed">External peer review by at least two independent specialists with anonymized identities.</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-amber-50/60 border border-amber-100 relative group hover:shadow-md transition">
                            <div class="w-7 h-7 rounded-lg bg-amber-600 text-white font-bold text-xs flex items-center justify-center mb-3">3</div>
                            <h4 class="font-bold text-sm text-slate-900 mb-1">Editorial Decision</h4>
                            <p class="text-xs text-slate-600 leading-relaxed">Revision or acceptance determined solely by Academic Editor based on reviewers' findings.</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-emerald-50/60 border border-emerald-100 relative group hover:shadow-md transition">
                            <div class="w-7 h-7 rounded-lg bg-emerald-600 text-white font-bold text-xs flex items-center justify-center mb-3">4</div>
                            <h4 class="font-bold text-sm text-slate-900 mb-1">Open Access & License</h4>
                            <p class="text-xs text-slate-600 leading-relaxed">Published under CC BY 4.0 terms; authors retain full intellectual copyright.</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-sky-50/60 border border-sky-100 relative group hover:shadow-md transition">
                            <div class="w-7 h-7 rounded-lg bg-sky-600 text-white font-bold text-xs flex items-center justify-center mb-3">5</div>
                            <h4 class="font-bold text-sm text-slate-900 mb-1">Permanent Archiving</h4>
                            <p class="text-xs text-slate-600 leading-relaxed">Deposited immediately in CLOCKSS, Portico, and DOI assignment through Crossref.</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-indigo-50/60 border border-indigo-100 relative group hover:shadow-md transition">
                            <div class="w-7 h-7 rounded-lg bg-indigo-600 text-white font-bold text-xs flex items-center justify-center mb-3">6</div>
                            <h4 class="font-bold text-sm text-slate-900 mb-1">CrossMark Updates</h4>
                            <p class="text-xs text-slate-600 leading-relaxed">Permanent tracking of errata, corrigenda, and version of record transparency.</p>
                        </div>
                    </div>
                </div>

                <!-- 7 Journal Policies Cards Grid -->
                <div class="space-y-4">
                    <h3 class="text-lg font-serif font-bold text-slate-900">Explore Our 7 Official Policies</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        
                        <!-- 1. Disclaimer -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-10 h-10 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    </div>
                                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Policy 01</span>
                                </div>
                                <h4 class="text-lg font-serif font-bold text-slate-900 mb-2">Disclaimer</h4>
                                <p class="text-xs text-slate-600 leading-relaxed mb-6">
                                    Legal limitations of liability, authorial opinions vs publisher stances, third-party content, and medical/scientific guidance disclaimers.
                                </p>
                            </div>
                            <a href="{{ route('policies.show', 'disclaimer') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-rose-700 hover:text-rose-900 transition">
                                Read Disclaimer Policy &rarr;
                            </a>
                        </div>

                        <!-- 2. Open Access Policy -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                                    </div>
                                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Policy 02</span>
                                </div>
                                <h4 class="text-lg font-serif font-bold text-slate-900 mb-2">Open Access Policy</h4>
                                <p class="text-xs text-slate-600 leading-relaxed mb-6">
                                    Immediate, free, and unrestricted access to all peer-reviewed research under the Creative Commons Attribution (CC BY 4.0) license.
                                </p>
                            </div>
                            <a href="{{ route('policies.show', 'open-access-policy') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 hover:text-emerald-900 transition">
                                Read Open Access Policy &rarr;
                            </a>
                        </div>

                        <!-- 3. Peer Review Policy -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    </div>
                                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Policy 03</span>
                                </div>
                                <h4 class="text-lg font-serif font-bold text-slate-900 mb-2">Peer Review Policy</h4>
                                <p class="text-xs text-slate-600 leading-relaxed mb-6">
                                    Detailed double-blind evaluation protocols, ethical duties of reviewers, conflict of interest safeguards, and editorial decision timelines.
                                </p>
                            </div>
                            <a href="{{ route('policies.show', 'peer-review-policy') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-purple-700 hover:text-purple-900 transition">
                                Read Peer Review Policy &rarr;
                            </a>
                        </div>

                        <!-- 4. CrossMark Policy -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    </div>
                                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Policy 04</span>
                                </div>
                                <h4 class="text-lg font-serif font-bold text-slate-900 mb-2">CrossMark Policy</h4>
                                <p class="text-xs text-slate-600 leading-relaxed mb-6">
                                    Crossref CrossMark system implementation, permanent DOI version of record tracking, errata, corrigenda, and retraction mechanisms.
                                </p>
                            </div>
                            <a href="{{ route('policies.show', 'crossmark-policy') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-700 hover:text-blue-900 transition">
                                Read CrossMark Policy &rarr;
                            </a>
                        </div>

                        <!-- 5. Archiving Policies -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                                    </div>
                                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Policy 05</span>
                                </div>
                                <h4 class="text-lg font-serif font-bold text-slate-900 mb-2">Archiving Policies</h4>
                                <p class="text-xs text-slate-600 leading-relaxed mb-6">
                                    Long-term digital preservation via CLOCKSS and Portico, green open access self-archiving rules, and zero-embargo institutional repository deposits.
                                </p>
                            </div>
                            <a href="{{ route('policies.show', 'archiving-policies') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-amber-700 hover:text-amber-900 transition">
                                Read Archiving Policies &rarr;
                            </a>
                        </div>

                        <!-- 6. License Terms -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Policy 06</span>
                                </div>
                                <h4 class="text-lg font-serif font-bold text-slate-900 mb-2">License Terms</h4>
                                <p class="text-xs text-slate-600 leading-relaxed mb-6">
                                    Authors retain copyright without restrictions. Clear legal parameters for reproduction, dissemination, citation standards, and commercial licensing.
                                </p>
                            </div>
                            <a href="{{ route('policies.show', 'license-terms') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-700 hover:text-indigo-900 transition">
                                Read License Terms &rarr;
                            </a>
                        </div>

                        <!-- 7. Other Policies -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition flex flex-col justify-between md:col-span-2">
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-10 h-10 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    </div>
                                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Policy 07</span>
                                </div>
                                <h4 class="text-lg font-serif font-bold text-slate-900 mb-2">Other Policies & Publication Ethics</h4>
                                <p class="text-xs text-slate-600 leading-relaxed mb-6">
                                    COPE-compliant ethical statement, human & animal research ethics clearances, informed consent, conflict of interest declarations, data availability requirements, and whistleblower protections.
                                </p>
                            </div>
                            <a href="{{ route('policies.show', 'other-policies') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-teal-700 hover:text-teal-900 transition">
                                Read Other Editorial Policies &rarr;
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
