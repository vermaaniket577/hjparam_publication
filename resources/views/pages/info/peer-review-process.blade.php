@extends('layouts.web')
@section('title', 'Peer Review Process')

@section('content')
    <div class="bg-slate-50 py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <!-- Header -->
                <div
                    class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 p-12 border border-slate-100 mb-8 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-purple-50 rounded-bl-full opacity-50"></div>
                    <div class="relative z-10">
                        <span
                            class="inline-block px-3 py-1 bg-purple-50 text-purple-700 text-[10px] font-bold uppercase tracking-wider rounded-full mb-6">Quality
                            Assurance</span>
                        <h1 class="text-4xl md:text-5xl font-serif font-bold text-slate-900 mb-6">Peer Review Process</h1>
                        <p class="text-lg text-slate-500 leading-relaxed max-w-2xl">
                            Ensuring the excellence and integrity of scholarly research through rigorous, double-blind
                            independent evaluation.
                        </p>
                    </div>
                </div>

                <!-- Content Area -->
                <div class="bg-white rounded-[3rem] shadow-2xl shadow-slate-200/40 p-12 md:p-16 border border-slate-100">
                    <div class="prose prose-slate max-w-none leading-[1.85]">

                        <h2 class="text-3xl font-serif font-black text-slate-900 mb-8 border-l-4 border-purple-500 pl-6">
                            Process Workflow</h2>
                        <p class="text-lg text-slate-600 mb-12">
                            HJPARAM follows a strict <strong>Double-Blind Peer Review</strong> process. Both the reviewer
                            and the author remain anonymous throughout the process. Below is the journey of a manuscript
                            from submission to decision:
                        </p>

                        <div class="space-y-12 my-16">
                            <!-- Step 1 -->
                            <div class="flex flex-col md:flex-row gap-8 items-start group">
                                <div
                                    class="flex-shrink-0 w-16 h-16 bg-blue-600 text-white rounded-2xl flex items-center justify-center font-bold text-2xl shadow-xl shadow-blue-200 group-hover:scale-110 transition-transform duration-500">
                                    1</div>
                                <div>
                                    <h3 class="text-2xl font-serif font-bold text-slate-900 mb-4">Initial Screening</h3>
                                    <p class="text-slate-500 leading-relaxed mb-0 text-base">The Editor-in-Chief or Section
                                        Editor performs an initial
                                        check for scope, originality, and basic technical quality. Plagiarism detection
                                        tools are used at this stage.</p>
                                </div>
                            </div>

                            <!-- Step 2 -->
                            <div class="flex flex-col md:flex-row gap-8 items-start group">
                                <div
                                    class="flex-shrink-0 w-16 h-16 bg-purple-600 text-white rounded-2xl flex items-center justify-center font-bold text-2xl shadow-xl shadow-purple-200 group-hover:scale-110 transition-transform duration-500">
                                    2</div>
                                <div>
                                    <h3 class="text-2xl font-serif font-bold text-slate-900 mb-4">Independent Review</h3>
                                    <p class="text-slate-500 leading-relaxed mb-0 text-base">The manuscript is sent to at
                                        least two independent expert
                                        reviewers. Reviewers assess scientific rigor, methodology, significance of results,
                                        and clarity.</p>
                                </div>
                            </div>

                            <!-- Step 3 -->
                            <div class="flex flex-col md:flex-row gap-8 items-start group">
                                <div
                                    class="flex-shrink-0 w-16 h-16 bg-indigo-600 text-white rounded-2xl flex items-center justify-center font-bold text-2xl shadow-xl shadow-indigo-200 group-hover:scale-110 transition-transform duration-500">
                                    3</div>
                                <div>
                                    <h3 class="text-2xl font-serif font-bold text-slate-900 mb-4">Editorial Decision</h3>
                                    <p class="text-slate-500 leading-relaxed mb-0 text-base">Based on reviewer feedback, the
                                        editor makes a decision:
                                        Accept, Revisions Required (Minor/Major), or Reject.</p>
                                </div>
                            </div>
                        </div>

                        <h2 class="text-3xl font-serif font-black text-slate-900 mt-24 mb-6">Ethics and Standards</h2>
                        <p class="text-slate-600 mb-10 text-lg">
                            Our review process follows the Committee on Publication Ethics (COPE) guidelines. Reviewers are
                            required to disclose any potential conflicts of interest and must treat manuscripts with strict
                            confidentiality.
                        </p>

                        <div
                            class="bg-slate-50 rounded-[2.5rem] p-12 border border-slate-100 my-12 relative overflow-hidden">
                            <div
                                class="absolute top-0 right-0 w-32 h-32 bg-slate-200/50 rounded-full blur-3xl -translate-y-12 translate-x-12">
                            </div>
                            <h3 class="text-xl font-serif font-bold text-slate-900 mb-8 relative z-10">Reviewer Requirements
                            </h3>
                            <ul class="list-disc pl-8 space-y-4 text-slate-600 relative z-10">
                                <li><strong class="text-slate-900">Academic Standing:</strong> Hold a PhD or equivalent in a
                                    relevant field.</li>
                                <li><strong class="text-slate-900">Proven Expertise:</strong> Have a proven track record of
                                    research and publication.</li>
                                <li><strong class="text-slate-900">Professional Rigor:</strong> Provide constructive,
                                    evidence-based feedback.</li>
                                <li><strong class="text-slate-900">Timeline Commitment:</strong> Submit review reports
                                    within specified timelines (2-3 weeks).</li>
                            </ul>
                        </div>

                        <div
                            class="mt-20 pt-10 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-8">
                            <a href="{{ route('home') }}"
                                class="flex items-center gap-3 text-slate-400 hover:text-blue-600 transition-all font-black uppercase tracking-[0.2em] text-[11px] group">
                                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M15 19l-7-7 7-7" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                Back to Institutional Home
                            </a>
                            <a href="{{ route('author.submit') }}"
                                class="bg-[#0f172a] hover:bg-blue-700 text-white px-10 py-4 rounded-2xl font-black uppercase tracking-[0.2em] text-[11px] transition shadow-2xl hover:-translate-y-1 active:scale-95">Submit
                                Manuscript</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection