@extends('layouts.web')
@section('title', 'CrossMark Policy | HJPARAM Publication')

@section('content')
<div class="bg-slate-50/60 py-12 md:py-16">
    <div class="container mx-auto px-4 lg:px-8 max-w-7xl">
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-8">
            <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Home</a>
            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('policies.index') }}" class="hover:text-blue-600 transition">Journal Policies</a>
            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-blue-900 font-bold">CrossMark Policy</span>
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
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-blue-50 text-blue-700 text-[10px] font-bold uppercase tracking-wider rounded-full mb-4">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                        Permanent Record Integrity
                    </div>
                    <h1 class="text-3xl md:text-4xl font-serif font-black text-slate-900 mb-4">
                        CrossMark <span class="text-blue-600">Policy</span>
                    </h1>
                    <p class="text-slate-600 text-sm md:text-base leading-relaxed">
                        CrossMark is a multi-publisher initiative by Crossref to provide a standard way for readers to verify that they are reading the authoritative, most up-to-date Version of Record (VoR) of a published document.
                    </p>
                </div>

                <!-- Main Content Body -->
                <div class="bg-white rounded-3xl p-8 md:p-12 border border-slate-200/80 shadow-sm space-y-10 text-slate-700 text-sm md:text-base leading-relaxed">
                    <!-- CrossMark Badge Box -->
                    <div class="flex flex-col sm:flex-row items-center gap-6 p-6 bg-slate-900 text-white rounded-3xl shadow-lg relative overflow-hidden">
                        <div class="w-20 h-20 rounded-2xl bg-white/10 flex items-center justify-center shrink-0 border border-white/20">
                            <svg class="w-12 h-12 text-sky-400" viewBox="0 0 24 24" fill="currentColor">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/>
                                <path d="M7 12l3 3 7-7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                            </svg>
                        </div>
                        <div class="space-y-1 text-center sm:text-left">
                            <span class="text-[11px] font-black uppercase tracking-widest text-sky-400">Crossref CrossMark Official Badge</span>
                            <h3 class="text-lg font-serif font-bold text-white">Click for Updates</h3>
                            <p class="text-xs text-slate-300 leading-relaxed max-w-xl">
                                Clicking the CrossMark icon on any HJPARAM article immediately informs the reader of the current status of the document, including subsequent corrigenda, additions, or retractions, alongside permanent metadata.
                            </p>
                        </div>
                    </div>

                    <!-- Section 1 -->
                    <div>
                        <h2 class="text-xl font-serif font-bold text-slate-900 mb-3 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-blue-100 text-blue-700 font-bold text-sm flex items-center justify-center shrink-0">1</span>
                            Maintaining the Integrity of the Scholarly Record
                        </h2>
                        <p class="text-slate-600 leading-relaxed mb-4">
                            HJPARAM recognizes the critical importance of permanence and transparency in scientific publications. Once an article is published with a unique Digital Object Identifier (DOI), it becomes part of the permanent scholarly record. Under no circumstances will an article be silently edited or altered after publication.
                        </p>
                        <p class="text-slate-600 leading-relaxed">
                            Any modifications to a published manuscript are formally handled via public, citable notices linked directly to the original work.
                        </p>
                    </div>

                    <!-- Section 2: Updates Taxonomy -->
                    <div>
                        <h2 class="text-xl font-serif font-bold text-slate-900 mb-4 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-blue-100 text-blue-700 font-bold text-sm flex items-center justify-center shrink-0">2</span>
                            Categories of Post-Publication Changes
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Erratum -->
                            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/70">
                                <span class="px-2.5 py-1 bg-amber-100 text-amber-800 rounded font-bold text-xs inline-block mb-2">Erratum</span>
                                <h4 class="font-bold text-slate-900 text-sm mb-1">Publisher Correction</h4>
                                <p class="text-xs text-slate-600 leading-relaxed">
                                    Published when a significant error was introduced during journal copyediting, typesetting, layout design, or metadata indexing that compromises the scientific interpretation.
                                </p>
                            </div>

                            <!-- Corrigendum -->
                            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/70">
                                <span class="px-2.5 py-1 bg-blue-100 text-blue-800 rounded font-bold text-xs inline-block mb-2">Corrigendum</span>
                                <h4 class="font-bold text-slate-900 text-sm mb-1">Author Correction</h4>
                                <p class="text-xs text-slate-600 leading-relaxed">
                                    Published when authors detect an unintentional omission, calculation error, or mislabeled figure/table in their published manuscript that requires formal clarification.
                                </p>
                            </div>

                            <!-- Addendum -->
                            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/70">
                                <span class="px-2.5 py-1 bg-purple-100 text-purple-800 rounded font-bold text-xs inline-block mb-2">Addendum</span>
                                <h4 class="font-bold text-slate-900 text-sm mb-1">Supplementary Information</h4>
                                <p class="text-xs text-slate-600 leading-relaxed">
                                    Published when supplementary experimental data, omitted acknowledgments, or key funding statements need to be added to enrich the original paper.
                                </p>
                            </div>

                            <!-- Retraction -->
                            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/70">
                                <span class="px-2.5 py-1 bg-rose-100 text-rose-800 rounded font-bold text-xs inline-block mb-2">Retraction</span>
                                <h4 class="font-bold text-slate-900 text-sm mb-1">Formal Article Retraction</h4>
                                <p class="text-xs text-slate-600 leading-relaxed">
                                    Conducted strictly according to <strong>COPE Retraction Guidelines</strong> in cases of proven data fabrication, major plagiarism, unethical human experimentation, or redundant publication.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Process Flow -->
                    <div>
                        <h2 class="text-xl font-serif font-bold text-slate-900 mb-3 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-blue-100 text-blue-700 font-bold text-sm flex items-center justify-center shrink-0">3</span>
                            CrossMark Update Process Flow
                        </h2>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-blue-50/50 border border-blue-100 text-xs">
                                <span class="w-6 h-6 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center shrink-0">1</span>
                                <span class="font-bold text-slate-800">Inquiry / Notice:</span>
                                <span class="text-slate-600">Issue identified by reader, author, or editorial audit.</span>
                            </div>
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-blue-50/50 border border-blue-100 text-xs">
                                <span class="w-6 h-6 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center shrink-0">2</span>
                                <span class="font-bold text-slate-800">Editorial Investigation:</span>
                                <span class="text-slate-600">Editor-in-Chief reviews findings with peer reviewers or authors.</span>
                            </div>
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-blue-50/50 border border-blue-100 text-xs">
                                <span class="w-6 h-6 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center shrink-0">3</span>
                                <span class="font-bold text-slate-800">Notice Publication:</span>
                                <span class="text-slate-600">A separate citable notice is published with its own dedicated DOI.</span>
                            </div>
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-blue-50/50 border border-blue-100 text-xs">
                                <span class="w-6 h-6 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center shrink-0">4</span>
                                <span class="font-bold text-slate-800">Crossref Metadata Deposit:</span>
                                <span class="text-slate-600">CrossMark metadata is pushed via Crossref API linking notice and original article bidirectionally.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Nav -->
                    <div class="pt-6 border-t border-slate-100 flex flex-wrap items-center justify-between gap-4">
                        <a href="{{ route('policies.show', 'peer-review-policy') }}" class="text-xs font-bold text-slate-500 hover:text-blue-600 transition flex items-center gap-1">
                            &larr; Previous: Peer Review Policy
                        </a>
                        <a href="{{ route('policies.show', 'archiving-policies') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition flex items-center gap-1">
                            Next: Archiving Policies &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
