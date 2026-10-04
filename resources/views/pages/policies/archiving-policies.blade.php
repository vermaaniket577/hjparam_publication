@extends('layouts.web')
@section('title', 'Archiving Policies | HJPARAM Publication')

@section('content')
<div class="bg-slate-50/60 py-12 md:py-16">
    <div class="container mx-auto px-4 lg:px-8 max-w-7xl">
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-8">
            <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Home</a>
            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('policies.index') }}" class="hover:text-blue-600 transition">Journal Policies</a>
            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-blue-900 font-bold">Archiving Policies</span>
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
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-amber-50 text-amber-700 text-[10px] font-bold uppercase tracking-wider rounded-full mb-4">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span>
                        Perpetual Preservation & Access
                    </div>
                    <h1 class="text-3xl md:text-4xl font-serif font-black text-slate-900 mb-4">
                        Digital Archiving & <span class="text-amber-600">Preservation</span>
                    </h1>
                    <p class="text-slate-600 text-sm md:text-base leading-relaxed">
                        To guarantee the perpetual availability and integrity of scientific research, HJPARAM implements redundant dark-archive preservation networks and comprehensive green open-access self-archiving provisions.
                    </p>
                </div>

                <!-- Main Content Body -->
                <div class="bg-white rounded-3xl p-8 md:p-12 border border-slate-200/80 shadow-sm space-y-10 text-slate-700 text-sm md:text-base leading-relaxed">
                    <!-- Dark Archive Networks -->
                    <div>
                        <h2 class="text-xl font-serif font-bold text-slate-900 mb-3 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 font-bold text-sm flex items-center justify-center shrink-0">1</span>
                            Long-Term Digital Preservation Networks
                        </h2>
                        <p class="text-slate-600 leading-relaxed mb-4">
                            All articles published across HJPARAM journals are automatically deposited into leading international digital archives. Even in the unlikely event of system failures or catastrophic publisher cessation, published research remains permanently accessible to the global scientific community.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-5 rounded-2xl bg-amber-50/40 border border-amber-100">
                                <h4 class="font-bold text-slate-900 text-sm mb-1">CLOCKSS & LOCKSS</h4>
                                <p class="text-xs text-slate-600 leading-relaxed">
                                    Controlled Lots of Copies Keep Stuff Safe (CLOCKSS) provides a distributed dark archive across leading research libraries worldwide, ensuring permanent decentralized survival.
                                </p>
                            </div>
                            <div class="p-5 rounded-2xl bg-amber-50/40 border border-amber-100">
                                <h4 class="font-bold text-slate-900 text-sm mb-1">Portico Digital Repository</h4>
                                <p class="text-xs text-slate-600 leading-relaxed">
                                    Full-text content and high-resolution figures are preserved in Portico's certified trust-worthy digital preservation service with format migration capabilities.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Author Self-Archiving Rights -->
                    <div>
                        <h2 class="text-xl font-serif font-bold text-slate-900 mb-3 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 font-bold text-sm flex items-center justify-center shrink-0">2</span>
                            Author Self-Archiving Rights (Green Open Access)
                        </h2>
                        <p class="text-slate-600 leading-relaxed mb-4">
                            HJPARAM champions open scholarship by granting authors comprehensive self-archiving rights across institutional, governmental, and subject-specific repositories with <strong>no embargo period</strong>:
                        </p>

                        <div class="overflow-hidden rounded-2xl border border-slate-200">
                            <table class="min-w-full divide-y divide-slate-200 text-xs">
                                <thead class="bg-slate-50 text-slate-700 font-bold">
                                    <tr>
                                        <th class="py-3 px-4 text-left">Manuscript Stage</th>
                                        <th class="py-3 px-4 text-left">Allowed Repositories</th>
                                        <th class="py-3 px-4 text-left">Embargo</th>
                                        <th class="py-3 px-4 text-left">Required Citation</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-slate-600">
                                    <tr>
                                        <td class="py-3 px-4 font-semibold text-slate-800">Pre-Print (Pre-Review)</td>
                                        <td class="py-3 px-4">arXiv, bioRxiv, Preprints.org, Sciforum, personal site</td>
                                        <td class="py-3 px-4 font-bold text-emerald-600">None (0 months)</td>
                                        <td class="py-3 px-4">Statement indicating under review</td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 px-4 font-semibold text-slate-800">Accepted Manuscript (Post-Print)</td>
                                        <td class="py-3 px-4">University repository, PubMed Central, subject databases</td>
                                        <td class="py-3 px-4 font-bold text-emerald-600">None (0 months)</td>
                                        <td class="py-3 px-4">Citation + DOI link to HJPARAM</td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 px-4 font-semibold text-slate-800">Published PDF (Version of Record)</td>
                                        <td class="py-3 px-4">Any public or commercial repository (CC BY 4.0)</td>
                                        <td class="py-3 px-4 font-bold text-emerald-600">None (0 months)</td>
                                        <td class="py-3 px-4">Official DOI & Creative Commons link</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Permanent DOI Persistence -->
                    <div>
                        <h2 class="text-xl font-serif font-bold text-slate-900 mb-3 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 font-bold text-sm flex items-center justify-center shrink-0">3</span>
                            Crossref DOI Persistence & Metadata Deposit
                        </h2>
                        <p class="text-slate-600 leading-relaxed mb-3">
                            Every article receives an immutable Digital Object Identifier (DOI) registered with Crossref upon publication. This ensures that even if URL architectures or server hosting locations change over time, scholarly citations and inbound hyperlinks will resolve accurately to the Version of Record forever.
                        </p>
                    </div>

                    <!-- Bottom Nav -->
                    <div class="pt-6 border-t border-slate-100 flex flex-wrap items-center justify-between gap-4">
                        <a href="{{ route('policies.show', 'crossmark-policy') }}" class="text-xs font-bold text-slate-500 hover:text-blue-600 transition flex items-center gap-1">
                            &larr; Previous: CrossMark Policy
                        </a>
                        <a href="{{ route('policies.show', 'license-terms') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition flex items-center gap-1">
                            Next: License Terms &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
