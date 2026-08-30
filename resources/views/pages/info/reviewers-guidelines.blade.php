@extends('layouts.web')
@section('title', 'Reviewer Guidelines | HJPARAM Publication')

@section('content')
    <div class="bg-slate-50 py-24">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <span
                    class="inline-block px-3 py-1 bg-blue-100 text-blue-700 text-[10px] font-bold uppercase tracking-[0.2em] rounded-full mb-6">Quality
                    Control</span>
                <h1 class="text-4xl md:text-5xl font-serif font-bold text-slate-900 mb-8">Reviewer <span
                        class="text-blue-600">Guidelines</span></h1>

                <div class="bg-white p-8 md:p-12 rounded-[2rem] shadow-sm border border-slate-100 mb-12">
                    <div class="prose prose-slate max-w-none">
                        <p class="text-lg text-slate-600 mb-12 leading-relaxed">
                            The peer review process is the bedrock of academic publishing. These guidelines are designed to
                            help our reviewers provide consistent, high-quality, and ethical evaluations.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
                            <div class="p-6 bg-slate-50 rounded-2xl border-t-4 border-blue-600">
                                <h4 class="font-bold text-slate-900 mb-2">1. Confidentiality</h4>
                                <p class="text-xs text-slate-500 leading-relaxed">Reviewers must treat the manuscript and
                                    all related data as strictly confidential. Do not share or discuss the content with
                                    anyone.</p>
                            </div>
                            <div class="p-6 bg-slate-50 rounded-2xl border-t-4 border-blue-600">
                                <h4 class="font-bold text-slate-900 mb-2">2. Timeliness</h4>
                                <p class="text-xs text-slate-500 leading-relaxed">We respect our authors' time. Please
                                    ensure reviews are completed within the requested timeframe (usually 14 days).</p>
                            </div>
                            <div class="p-6 bg-slate-50 rounded-2xl border-t-4 border-blue-600">
                                <h4 class="font-bold text-slate-900 mb-2">3. Objectivity</h4>
                                <p class="text-xs text-slate-500 leading-relaxed">Reviews should be conducted objectively.
                                    Personal criticism of the author is inappropriate and prohibited.</p>
                            </div>
                            <div class="p-6 bg-slate-50 rounded-2xl border-t-4 border-blue-600">
                                <h4 class="font-bold text-slate-900 mb-2">4. Constructive Feedback</h4>
                                <p class="text-xs text-slate-500 leading-relaxed">Focus on helping authors improve their
                                    work. Be specific about strengths and weaknesses.</p>
                            </div>
                        </div>

                        <h3 class="text-2xl font-bold text-slate-900 mb-6 underline decoration-blue-100 underline-offset-8">
                            Evaluation Criteria</h3>
                        <ul class="space-y-4 mb-12">
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5 shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path d="M5 13l4 4L19 7" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                </svg>
                                <span class="text-slate-700"><strong>Originality:</strong> Does the work present new
                                    findings or perspectives?</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5 shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path d="M5 13l4 4L19 7" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                </svg>
                                <span class="text-slate-700"><strong>Methodology:</strong> Is the research design robust and
                                    appropriate?</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5 shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path d="M5 13l4 4L19 7" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                </svg>
                                <span class="text-slate-700"><strong>Significance:</strong> Does the article contribute
                                    meaningfully to its field?</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection