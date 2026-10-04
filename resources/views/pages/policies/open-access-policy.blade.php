@extends('layouts.web')
@section('title', 'Open Access Policy | HJPARAM Publication')

@section('content')
<div class="bg-slate-50/60 py-12 md:py-16">
    <div class="container mx-auto px-4 lg:px-8 max-w-7xl">
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-8">
            <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Home</a>
            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('policies.index') }}" class="hover:text-blue-600 transition">Journal Policies</a>
            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-blue-900 font-bold">Open Access Policy</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-4 lg:sticky lg:top-24">
                @include('pages.policies.sidebar')
            </div>

            <!-- Policy Content -->
            <div class="lg:col-span-8 space-y-8">
                <!-- Header Banner -->
                <div class="bg-white rounded-3xl p-8 md:p-12 border border-slate-200/80 shadow-sm relative overflow-hidden">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-bold uppercase tracking-wider rounded-full mb-4">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                        Unrestricted Knowledge Dissemination
                    </div>
                    <h1 class="text-3xl md:text-4xl font-serif font-black text-slate-900 mb-4">
                        Gold Open Access <span class="text-emerald-600">Policy</span>
                    </h1>
                    <p class="text-slate-600 text-sm md:text-base leading-relaxed">
                        HJPARAM Publication is committed to the principle that research funded by public and institutional resources should be freely accessible to humanity without monetary, legal, or technical barriers.
                    </p>
                </div>

                <!-- Main Content Body -->
                <div class="bg-white rounded-3xl p-8 md:p-12 border border-slate-200/80 shadow-sm space-y-10 text-slate-700 text-sm md:text-base leading-relaxed">
                    <!-- Standard Statement -->
                    <div class="flex items-start gap-4 p-6 bg-emerald-50/60 rounded-3xl border border-emerald-100">
                        <div class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center text-emerald-600 shadow-sm shrink-0 border border-emerald-200/60">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14.5v-9l6 4.5-6 4.5z"></path>
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs font-black uppercase tracking-wider text-emerald-800 block mb-1">Budapest Open Access Initiative (BOAI) Definition</span>
                            <p class="text-xs md:text-sm text-slate-700 leading-relaxed">
                                Free availability on the public internet, permitting any users to read, download, copy, distribute, print, search, or link to the full texts of these articles, crawl them for indexing, pass them as data to software, or use them for any lawful purpose, without financial or technical barriers.
                            </p>
                        </div>
                    </div>

                    <!-- Section 1 -->
                    <div>
                        <h2 class="text-xl font-serif font-bold text-slate-900 mb-3 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 font-bold text-sm flex items-center justify-center shrink-0">1</span>
                            Creative Commons Attribution License (CC BY 4.0)
                        </h2>
                        <p class="text-slate-600 leading-relaxed mb-4">
                            All original research articles, reviews, case studies, and letters published in HJPARAM journals are distributed under the terms of the <strong>Creative Commons Attribution 4.0 International License (CC BY 4.0)</strong>.
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/70">
                                <h4 class="font-bold text-slate-900 text-sm mb-1">Share Unconditionally</h4>
                                <p class="text-xs text-slate-600 leading-relaxed">
                                    Users are free to copy, redistribute, stream, and transmit the published material in any medium or digital format.
                                </p>
                            </div>
                            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/70">
                                <h4 class="font-bold text-slate-900 text-sm mb-1">Adapt and Build Upon</h4>
                                <p class="text-xs text-slate-600 leading-relaxed">
                                    Users are free to remix, transform, and build upon the work for any lawful purpose, including commercial applications, provided proper attribution is given.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2 -->
                    <div>
                        <h2 class="text-xl font-serif font-bold text-slate-900 mb-3 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 font-bold text-sm flex items-center justify-center shrink-0">2</span>
                            Author Copyright Retention
                        </h2>
                        <p class="text-slate-600 leading-relaxed mb-4">
                            Unlike traditional subscription models that demand authors transfer copyright, <strong>authors publishing with HJPARAM retain unrestricted copyright and intellectual property rights</strong> to their work.
                        </p>
                        <ul class="list-disc pl-6 space-y-2 text-slate-600 text-sm">
                            <li>Authors grant HJPARAM Publication an irrevocable, non-exclusive license to publish and identify itself as the original publisher.</li>
                            <li>Authors grant any third party the right to use the article freely as long as integrity is maintained and the original authors and citation details are credited.</li>
                        </ul>
                    </div>

                    <!-- Section 3 -->
                    <div>
                        <h2 class="text-xl font-serif font-bold text-slate-900 mb-3 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 font-bold text-sm flex items-center justify-center shrink-0">3</span>
                            Self-Archiving & Repository Deposit (Green Open Access)
                        </h2>
                        <p class="text-slate-600 leading-relaxed mb-4">
                            HJPARAM actively encourages self-archiving across all manuscript lifecycle stages with <strong>zero embargo</strong>:
                        </p>
                        <div class="space-y-3">
                            <div class="flex items-start gap-3 p-4 rounded-xl bg-slate-50 border border-slate-200/60">
                                <span class="px-2 py-0.5 bg-blue-100 text-blue-800 rounded font-bold text-xs shrink-0">Pre-Print</span>
                                <p class="text-xs text-slate-600">Authors may share pre-review versions on preprint servers (arXiv, bioRxiv, Preprints.org, Sciforum) at any time.</p>
                            </div>
                            <div class="flex items-start gap-3 p-4 rounded-xl bg-slate-50 border border-slate-200/60">
                                <span class="px-2 py-0.5 bg-purple-100 text-purple-800 rounded font-bold text-xs shrink-0">Accepted (AAM)</span>
                                <p class="text-xs text-slate-600">Authors may deposit peer-reviewed accepted manuscripts into institutional or subject repositories immediately upon acceptance.</p>
                            </div>
                            <div class="flex items-start gap-3 p-4 rounded-xl bg-slate-50 border border-slate-200/60">
                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 rounded font-bold text-xs shrink-0">Published PDF</span>
                                <p class="text-xs text-slate-600">The final publisher's Version of Record (PDF & XML) may be freely deposited anywhere, including institutional repositories, ResearchGate, and personal websites.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Section 4 -->
                    <div>
                        <h2 class="text-xl font-serif font-bold text-slate-900 mb-3 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 font-bold text-sm flex items-center justify-center shrink-0">4</span>
                            Article Processing Charges (APC) Transparency & Waivers
                        </h2>
                        <p class="text-slate-600 leading-relaxed mb-3">
                            To sustain high-level peer review, DOI minting, XML production, and permanent archiving without paywalling readers, an Article Processing Charge (APC) is assessed upon formal peer-review acceptance. There are no submission fees or color charges.
                        </p>
                        <div class="p-4 bg-emerald-50/40 rounded-2xl border border-emerald-100 text-xs text-slate-600">
                            <strong>Waiver Policy:</strong> Authors from World Bank designated Low-Income and Lower-Middle-Income economies are eligible for partial or full APC waivers. Inability to pay fees will never influence editorial decisions.
                        </div>
                    </div>

                    <!-- Bottom Nav -->
                    <div class="pt-6 border-t border-slate-100 flex flex-wrap items-center justify-between gap-4">
                        <a href="{{ route('policies.show', 'disclaimer') }}" class="text-xs font-bold text-slate-500 hover:text-blue-600 transition flex items-center gap-1">
                            &larr; Previous: Disclaimer
                        </a>
                        <a href="{{ route('policies.show', 'peer-review-policy') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition flex items-center gap-1">
                            Next: Peer Review Policy &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
