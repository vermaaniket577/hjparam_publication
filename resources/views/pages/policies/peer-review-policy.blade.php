@extends('layouts.web')
@section('title', 'Peer Review Policy | HJPARAM Publication')

@section('content')
<div class="bg-slate-50/60 py-12 md:py-16">
    <div class="container mx-auto px-4 lg:px-8 max-w-7xl">
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-8">
            <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Home</a>
            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('policies.index') }}" class="hover:text-blue-600 transition">Journal Policies</a>
            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-blue-900 font-bold">Peer Review Policy</span>
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
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-purple-50 text-purple-700 text-[10px] font-bold uppercase tracking-wider rounded-full mb-4">
                        <span class="w-1.5 h-1.5 rounded-full bg-purple-600"></span>
                        Quality Assurance & Scientific Rigor
                    </div>
                    <h1 class="text-3xl md:text-4xl font-serif font-black text-slate-900 mb-4">
                        Double-Blind Peer Review <span class="text-purple-600">Policy</span>
                    </h1>
                    <p class="text-slate-600 text-sm md:text-base leading-relaxed">
                        Peer review is the foundational cornerstone of scientific publishing. HJPARAM enforces a rigorous, impartial, double-blind peer review system to ensure unbiased assessment of scientific validity, methodology, and significance.
                    </p>
                </div>

                <!-- Main Content Body -->
                <div class="bg-white rounded-3xl p-8 md:p-12 border border-slate-200/80 shadow-sm space-y-10 text-slate-700 text-sm md:text-base leading-relaxed">
                    <!-- Complete 5-Step Process Flow -->
                    <div>
                        <div class="flex items-center justify-between pb-3 mb-6 border-b border-slate-100">
                            <div>
                                <span class="text-[10px] font-black uppercase tracking-widest text-purple-600 block">Workflow</span>
                                <h2 class="text-xl font-serif font-bold text-slate-900">Step-by-Step Peer Review Process Flow</h2>
                            </div>
                            <span class="text-xs font-bold text-purple-700 bg-purple-50 px-3 py-1 rounded-full">Standard Flow</span>
                        </div>

                        <div class="space-y-6">
                            <!-- Step 1 -->
                            <div class="flex gap-4 p-5 rounded-2xl bg-slate-50 border border-slate-200/70 group hover:border-purple-200 transition">
                                <div class="w-10 h-10 rounded-xl bg-purple-600 text-white font-bold text-base flex items-center justify-center shrink-0 shadow-sm">1</div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-base mb-1">Desk Screening & Originality Verification</h4>
                                    <p class="text-xs md:text-sm text-slate-600 leading-relaxed mb-2">
                                        The Managing Editor checks the manuscript for conformity with Author Guidelines, scope, and ethical disclosures. Automated similarity screening is conducted via <strong>iThenticate / Crossref Similarity Check</strong>. Manuscripts with unoriginal content or out-of-scope topics are rejected at this stage.
                                    </p>
                                    <span class="inline-block text-[11px] font-semibold text-slate-500 bg-white px-2 py-0.5 rounded border border-slate-200">Timeline: 2–4 Business Days</span>
                                </div>
                            </div>

                            <!-- Step 2 -->
                            <div class="flex gap-4 p-5 rounded-2xl bg-slate-50 border border-slate-200/70 group hover:border-purple-200 transition">
                                <div class="w-10 h-10 rounded-xl bg-purple-600 text-white font-bold text-base flex items-center justify-center shrink-0 shadow-sm">2</div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-base mb-1">Editor Assignment & Reviewer Invitation</h4>
                                    <p class="text-xs md:text-sm text-slate-600 leading-relaxed mb-2">
                                        The Editor-in-Chief assigns an Academic Section Editor with relevant subject expertise. The Editor invites a minimum of <strong>two independent, external peer reviewers</strong> who have not co-authored with the authors in the preceding 3 years.
                                    </p>
                                    <span class="inline-block text-[11px] font-semibold text-slate-500 bg-white px-2 py-0.5 rounded border border-slate-200">Confidentiality: Double-Blind (Author & Reviewer IDs hidden)</span>
                                </div>
                            </div>

                            <!-- Step 3 -->
                            <div class="flex gap-4 p-5 rounded-2xl bg-slate-50 border border-slate-200/70 group hover:border-purple-200 transition">
                                <div class="w-10 h-10 rounded-xl bg-purple-600 text-white font-bold text-base flex items-center justify-center shrink-0 shadow-sm">3</div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-base mb-1">Expert Peer Review Assessment</h4>
                                    <p class="text-xs md:text-sm text-slate-600 leading-relaxed mb-2">
                                        Reviewers critically evaluate study design, experimental protocols, statistical analyses, clarity of data visualizations, and contextualization within modern literature. Each reviewer submits an itemized report with a recommendation (Accept, Minor Revision, Major Revision, or Reject).
                                    </p>
                                    <span class="inline-block text-[11px] font-semibold text-slate-500 bg-white px-2 py-0.5 rounded border border-slate-200">Timeline: 2–3 Weeks per review round</span>
                                </div>
                            </div>

                            <!-- Step 4 -->
                            <div class="flex gap-4 p-5 rounded-2xl bg-slate-50 border border-slate-200/70 group hover:border-purple-200 transition">
                                <div class="w-10 h-10 rounded-xl bg-purple-600 text-white font-bold text-base flex items-center justify-center shrink-0 shadow-sm">4</div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-base mb-1">Author Revision & Point-by-Point Rebuttal</h4>
                                    <p class="text-xs md:text-sm text-slate-600 leading-relaxed mb-2">
                                        Authors are given 10–20 days to address comments. Revised manuscripts must include a detailed response letter answering every reviewer concern and highlighting tracked text modifications in the revised file.
                                    </p>
                                </div>
                            </div>

                            <!-- Step 5 -->
                            <div class="flex gap-4 p-5 rounded-2xl bg-slate-50 border border-slate-200/70 group hover:border-purple-200 transition">
                                <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white font-bold text-base flex items-center justify-center shrink-0 shadow-sm">5</div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-base mb-1">Final Editorial Decision & Production</h4>
                                    <p class="text-xs md:text-sm text-slate-600 leading-relaxed mb-2">
                                        The Academic Editor renders the definitive decision. If accepted, the paper enters English language copyediting, XML typesetting, author proof approvals, and immediate online publication with permanent DOI assignment.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reviewer Code of Conduct -->
                    <div>
                        <h2 class="text-xl font-serif font-bold text-slate-900 mb-3 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-purple-100 text-purple-700 font-bold text-sm flex items-center justify-center shrink-0">!</span>
                            Reviewer Obligations & COPE Guidelines
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-4 rounded-2xl bg-purple-50/40 border border-purple-100 text-xs text-slate-600">
                                <strong class="text-slate-900 block mb-1">Absolute Confidentiality</strong>
                                Manuscripts received for review are privileged documents and must not be shared, discussed, or used for personal research advantages.
                            </div>
                            <div class="p-4 rounded-2xl bg-purple-50/40 border border-purple-100 text-xs text-slate-600">
                                <strong class="text-slate-900 block mb-1">Conflict of Interest</strong>
                                Reviewers must recuse themselves if they have competitive, collaborative, or financial relationships with authors or institutions.
                            </div>
                            <div class="p-4 rounded-2xl bg-purple-50/40 border border-purple-100 text-xs text-slate-600">
                                <strong class="text-slate-900 block mb-1">Constructive Tone</strong>
                                Reviews must be objective, scientifically substantiated, and free of derogatory remarks or unsubstantiated accusations.
                            </div>
                            <div class="p-4 rounded-2xl bg-purple-50/40 border border-purple-100 text-xs text-slate-600">
                                <strong class="text-slate-900 block mb-1">No AI Ghostwriting</strong>
                                Reviewers must not upload privileged unpublished manuscripts into generative AI models without explicit editorial authorization.
                            </div>
                        </div>
                    </div>

                    <!-- Appeals Procedure -->
                    <div>
                        <h2 class="text-xl font-serif font-bold text-slate-900 mb-3">Appeals & Reconsideration</h2>
                        <p class="text-slate-600 leading-relaxed text-xs md:text-sm">
                            Authors who believe their manuscript was rejected due to a factual misunderstanding or reviewer error may submit a formal appeal to the Editor-in-Chief within 14 days of the decision. The appeal must clearly detail technical refutations without ad hominem arguments. If warranted, an independent third reviewer is appointed.
                        </p>
                    </div>

                    <!-- Bottom Nav -->
                    <div class="pt-6 border-t border-slate-100 flex flex-wrap items-center justify-between gap-4">
                        <a href="{{ route('policies.show', 'open-access-policy') }}" class="text-xs font-bold text-slate-500 hover:text-blue-600 transition flex items-center gap-1">
                            &larr; Previous: Open Access Policy
                        </a>
                        <a href="{{ route('policies.show', 'crossmark-policy') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition flex items-center gap-1">
                            Next: CrossMark Policy &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
