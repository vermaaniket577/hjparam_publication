@extends('layouts.web')
@section('title', 'Plagiarism Check | HJPARAM Publication')

@section('content')
    <div class="bg-slate-50 py-24">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <span
                    class="inline-block px-3 py-1 bg-red-100 text-red-700 text-[10px] font-bold uppercase tracking-[0.2em] rounded-full mb-6">Scientific
                    Integrity</span>
                <h1 class="text-4xl md:text-5xl font-serif font-bold text-slate-900 mb-8">Plagiarism <span
                        class="text-red-600">Check</span></h1>

                <div class="bg-white p-8 md:p-12 rounded-[2rem] shadow-sm border border-slate-100 mb-12">
                    <div class="prose prose-slate max-w-none">
                        <p class="text-lg text-slate-600 mb-12 leading-relaxed">
                            A cornerstone of scientific ethics is originality. HJPARAM utilizes world-leading detection
                            software to ensure that all published research is original and properly cited.
                        </p>

                        <div class="p-8 bg-slate-50 rounded-3xl border border-slate-100 mb-12">
                            <h3 class="text-xl font-bold text-slate-900 mb-4">How it Works</h3>
                            <div class="space-y-6">
                                <div class="flex gap-4">
                                    <div class="font-serif italic text-4xl text-slate-200 shrink-0">01</div>
                                    <div>
                                        <h4 class="font-bold text-slate-800">Global Database Cross-Check</h4>
                                        <p class="text-sm text-slate-500">Every manuscript is scanned against 50+ billion
                                            web pages and 80+ million scholarly articles.</p>
                                    </div>
                                </div>
                                <div class="flex gap-4">
                                    <div class="font-serif italic text-4xl text-slate-200 shrink-0">02</div>
                                    <div>
                                        <h4 class="font-bold text-slate-800">Detailed Similarity Reports</h4>
                                        <p class="text-sm text-slate-500">We provide authors with a comprehensive report
                                            highlighting overlapping text and potential citation issues.</p>
                                    </div>
                                </div>
                                <div class="flex gap-4">
                                    <div class="font-serif italic text-4xl text-slate-200 shrink-0">03</div>
                                    <div>
                                        <h4 class="font-bold text-slate-800">AI Detection (Coming Soon)</h4>
                                        <p class="text-sm text-slate-500">New advanced algorithms to identify AI-generated
                                            content and maintain human-led research integrity.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-slate-100 pt-12 text-center">
                            <h3 class="text-2xl font-bold text-slate-900 mb-4">Submission Screening</h3>
                            <p class="text-slate-500 max-w-xl mx-auto text-sm leading-relaxed mb-8">All manuscripts
                                submitted to HJPARAM journals undergo mandatory screening. Manuscripts with excessive
                                similarity scores will be returned to authors for revision or rejected immediately.</p>
                            <a href="{{ route('author.submit') }}"
                                class="inline-flex px-10 py-4 bg-red-600 text-white font-bold rounded-full hover:bg-slate-900 transition-all shadow-xl shadow-red-200">Upload
                                Manuscript</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection