@extends('layouts.web')
@section('title', 'License Terms & Copyright | HJPARAM Publication')

@section('content')
<div class="bg-slate-50/60 py-12 md:py-16">
    <div class="container mx-auto px-4 lg:px-8 max-w-7xl">
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-8">
            <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Home</a>
            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('policies.index') }}" class="hover:text-blue-600 transition">Journal Policies</a>
            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-blue-900 font-bold">License Terms</span>
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
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-50 text-indigo-700 text-[10px] font-bold uppercase tracking-wider rounded-full mb-4">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-600"></span>
                        Creative Commons & Intellectual Property
                    </div>
                    <h1 class="text-3xl md:text-4xl font-serif font-black text-slate-900 mb-4">
                        License Terms & <span class="text-indigo-600">Copyright</span>
                    </h1>
                    <p class="text-slate-600 text-sm md:text-base leading-relaxed">
                        Clear legal agreements protecting author rights while maximizing the global dissemination and societal impact of scholarly discoveries under the Creative Commons framework.
                    </p>
                </div>

                <!-- Main Content Body -->
                <div class="bg-white rounded-3xl p-8 md:p-12 border border-slate-200/80 shadow-sm space-y-10 text-slate-700 text-sm md:text-base leading-relaxed">
                    <!-- CC BY 4.0 License Summary -->
                    <div class="p-6 rounded-3xl bg-indigo-50/60 border border-indigo-100 flex flex-col md:flex-row items-start gap-6">
                        <div class="w-14 h-14 rounded-2xl bg-white border border-indigo-200 flex items-center justify-center text-indigo-600 shrink-0 shadow-xs">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <span class="text-xs font-black uppercase tracking-wider text-indigo-800 block mb-1">Standard Open Access License</span>
                            <h3 class="text-base font-bold text-slate-900 mb-2">Creative Commons Attribution 4.0 International (CC BY 4.0)</h3>
                            <p class="text-xs text-slate-600 leading-relaxed mb-3">
                                This license permits anyone to reproduce, share, transmit, remix, transform, and build upon the work for any purpose, including commercial endeavors, on condition that the original creator(s) and source are properly cited.
                            </p>
                            <a href="https://creativecommons.org/licenses/by/4.0/" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 text-xs font-bold text-indigo-700 hover:text-indigo-900">
                                View Full Legal Code on Creative Commons &rarr;
                            </a>
                        </div>
                    </div>

                    <!-- Author Rights Retained -->
                    <div>
                        <h2 class="text-xl font-serif font-bold text-slate-900 mb-3 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-700 font-bold text-sm flex items-center justify-center shrink-0">1</span>
                            Exclusive Rights Retained by Authors
                        </h2>
                        <p class="text-slate-600 leading-relaxed mb-4">
                            Authors do not sign away their intellectual property. As the copyright holder, authors retain:
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/70 text-xs">
                                <strong class="text-slate-900 block mb-1">Patent & Trademark Rights</strong>
                                Any patentable discoveries, processes, formulas, or software algorithms described in the manuscript remain the exclusive property of the authors or their institutions.
                            </div>
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/70 text-xs">
                                <strong class="text-slate-900 block mb-1">Teaching & Educational Re-use</strong>
                                The unrestricted right to reproduce, present, or distribute the article in academic lectures, course syllabi, student dissertations, and institutional training materials.
                            </div>
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/70 text-xs">
                                <strong class="text-slate-900 block mb-1">Book Anthologies & Compilations</strong>
                                The right to include the work, in whole or in part, in subsequent edited books, anthologies, or author compilations without fees.
                            </div>
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/70 text-xs">
                                <strong class="text-slate-900 block mb-1">Self-Archiving Freedom</strong>
                                The right to deposit the article across any digital archive or repository worldwide with zero embargo.
                            </div>
                        </div>
                    </div>

                    <!-- Non-Exclusive License to Publisher -->
                    <div>
                        <h2 class="text-xl font-serif font-bold text-slate-900 mb-3 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-700 font-bold text-sm flex items-center justify-center shrink-0">2</span>
                            Non-Exclusive Publishing License Granted to HJPARAM
                        </h2>
                        <p class="text-slate-600 leading-relaxed mb-3">
                            Upon manuscript acceptance, authors grant HJPARAM Publication a worldwide, perpetual, royalty-free, non-exclusive license to:
                        </p>
                        <ul class="list-disc pl-6 space-y-2 text-slate-600 text-sm">
                            <li>Publish, typeset, reproduce, display, and distribute the article in print and electronic formats.</li>
                            <li>Deposit the article and rich metadata with discovery databases, indexing agencies (Crossref, PubMed, Scopus, Google Scholar), and archival repositories.</li>
                            <li>Enforce copyright on behalf of the authors in cases of unauthorized plagiarism or infringement by third parties.</li>
                        </ul>
                    </div>

                    <!-- User Attribution Requirements -->
                    <div>
                        <h2 class="text-xl font-serif font-bold text-slate-900 mb-3 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-700 font-bold text-sm flex items-center justify-center shrink-0">3</span>
                            Mandatory Attribution Format for Reusers
                        </h2>
                        <p class="text-slate-600 leading-relaxed mb-3">
                            Third parties utilizing figures, data tables, or excerpts must provide explicit credit in the following format:
                        </p>
                        <div class="p-4 bg-slate-900 text-slate-100 rounded-2xl font-mono text-xs overflow-x-auto">
                            "Reproduced from [Author Names], [Article Title], [Journal Name], [Year], [Volume(Issue)], [Pages/Article ID], DOI: [DOI link], under the Creative Commons Attribution 4.0 International License."
                        </div>
                    </div>

                    <!-- Bottom Nav -->
                    <div class="pt-6 border-t border-slate-100 flex flex-wrap items-center justify-between gap-4">
                        <a href="{{ route('policies.show', 'archiving-policies') }}" class="text-xs font-bold text-slate-500 hover:text-blue-600 transition flex items-center gap-1">
                            &larr; Previous: Archiving Policies
                        </a>
                        <a href="{{ route('policies.show', 'other-policies') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition flex items-center gap-1">
                            Next: Other Policies &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
