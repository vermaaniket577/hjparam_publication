@extends('layouts.web')
@section('title', 'Disclaimer | HJPARAM Publication')

@section('content')
<div class="bg-slate-50/60 py-12 md:py-16">
    <div class="container mx-auto px-4 lg:px-8 max-w-7xl">
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-8">
            <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Home</a>
            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('policies.index') }}" class="hover:text-blue-600 transition">Journal Policies</a>
            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-blue-900 font-bold">Disclaimer</span>
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
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-rose-50 text-rose-700 text-[10px] font-bold uppercase tracking-wider rounded-full mb-4">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span>
                        Legal Terms & Liability
                    </div>
                    <h1 class="text-3xl md:text-4xl font-serif font-black text-slate-900 mb-4">
                        Publisher & Author <span class="text-rose-600">Disclaimer</span>
                    </h1>
                    <p class="text-slate-600 text-sm md:text-base leading-relaxed">
                        This disclaimer governs the access, interpretation, and use of scholarly articles, editorial commentary, and digital resources published across all HJPARAM journals.
                    </p>
                </div>

                <!-- Main Content Body -->
                <div class="bg-white rounded-3xl p-8 md:p-12 border border-slate-200/80 shadow-sm space-y-10 text-slate-700 text-sm md:text-base leading-relaxed">
                    <!-- Section 1 -->
                    <div>
                        <h2 class="text-xl font-serif font-bold text-slate-900 mb-3 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-rose-100 text-rose-700 font-bold text-sm flex items-center justify-center shrink-0">1</span>
                            Authorial Opinions and Findings
                        </h2>
                        <p class="text-slate-600 leading-relaxed mb-3">
                            The opinions, experimental results, analyses, and conclusions expressed in published articles are solely those of the individual authors and contributing researchers. They do not necessarily reflect the official viewpoints, endorsements, or policy positions of <strong>HJPARAM Publication</strong>, its Editors-in-Chief, Editorial Board members, or affiliated institutions.
                        </p>
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/70 text-xs text-slate-600">
                            <strong>Note:</strong> Publication of research or commercial product names does not constitute an endorsement, guarantee, or warranty by the publisher regarding the efficacy, safety, or claims of any product, service, or scientific apparatus.
                        </div>
                    </div>

                    <!-- Section 2 -->
                    <div>
                        <h2 class="text-xl font-serif font-bold text-slate-900 mb-3 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-rose-100 text-rose-700 font-bold text-sm flex items-center justify-center shrink-0">2</span>
                            Medical, Clinical & Engineering Advice Disclaimer
                        </h2>
                        <p class="text-slate-600 leading-relaxed mb-3">
                            Articles published in HJPARAM biomedical, clinical, pharmacological, or engineering journals are intended exclusively for academic, research, and educational advancement:
                        </p>
                        <ul class="list-disc pl-6 space-y-2 text-slate-600 text-sm">
                            <li><strong>Not Medical Advice:</strong> Research findings should never be used as a substitute for professional medical advice, clinical diagnosis, or customized patient treatment plans.</li>
                            <li><strong>Dosages & Protocols:</strong> While every effort is made to verify experimental dosage data and technical specifications, practitioners must independently verify official drug package inserts and regulatory guidelines before clinical administration.</li>
                            <li><strong>Engineering & Safety:</strong> Methodologies involving hazardous materials, high-voltage apparatus, or structural simulations require certified institutional safety verification before replication.</li>
                        </ul>
                    </div>

                    <!-- Section 3 -->
                    <div>
                        <h2 class="text-xl font-serif font-bold text-slate-900 mb-3 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-rose-100 text-rose-700 font-bold text-sm flex items-center justify-center shrink-0">3</span>
                            Accuracy and Information Completeness
                        </h2>
                        <p class="text-slate-600 leading-relaxed mb-3">
                            While HJPARAM Publication and our expert peer reviewers exercise rigorous diligence to ensure the scientific integrity of published manuscripts, neither the publisher nor the editors make any warranties, express or implied, regarding:
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 my-4">
                            <div class="p-4 rounded-2xl bg-rose-50/40 border border-rose-100 text-xs">
                                <span class="font-bold text-slate-900 block mb-1">Fitness for Purpose</span>
                                Suitability of published theoretical algorithms or empirical methods for any specific industrial or commercial application.
                            </div>
                            <div class="p-4 rounded-2xl bg-rose-50/40 border border-rose-100 text-xs">
                                <span class="font-bold text-slate-900 block mb-1">Scientific Inerrancy</span>
                                Unforeseen historical or subsequent experimental discrepancies emerging after the peer-review snapshot date.
                            </div>
                        </div>
                    </div>

                    <!-- Section 4 -->
                    <div>
                        <h2 class="text-xl font-serif font-bold text-slate-900 mb-3 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-rose-100 text-rose-700 font-bold text-sm flex items-center justify-center shrink-0">4</span>
                            External Hyperlinks and Third-Party Repositories
                        </h2>
                        <p class="text-slate-600 leading-relaxed">
                            Articles may contain outbound links to third-party data repositories (e.g., GitHub, Zenodo, Figshare, GenBank) or external websites. HJPARAM Publication maintains no editorial control over, and assumes no responsibility for, the availability, integrity, or security of external third-party servers.
                        </p>
                    </div>

                    <!-- Section 5 -->
                    <div>
                        <h2 class="text-xl font-serif font-bold text-slate-900 mb-3 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-rose-100 text-rose-700 font-bold text-sm flex items-center justify-center shrink-0">5</span>
                            Limitation of Liability
                        </h2>
                        <p class="text-slate-600 leading-relaxed">
                            To the maximum extent permitted by applicable international law, HJPARAM Publication, its officers, editorial board, reviewers, and staff shall not be liable for any direct, indirect, incidental, consequential, or punitive damages arising out of the use of, or inability to use, materials published in our journals.
                        </p>
                    </div>

                    <!-- Bottom Nav -->
                    <div class="pt-6 border-t border-slate-100 flex flex-wrap items-center justify-between gap-4">
                        <a href="{{ route('policies.index') }}" class="text-xs font-bold text-slate-500 hover:text-blue-600 transition flex items-center gap-1">
                            &larr; Back to Policies Hub
                        </a>
                        <a href="{{ route('policies.show', 'open-access-policy') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition flex items-center gap-1">
                            Next: Open Access Policy &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
