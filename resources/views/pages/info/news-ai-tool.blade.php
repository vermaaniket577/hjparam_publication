@extends('layouts.web')
@section('title', 'AI Authenticity Tool | HJPARAM News')

@section('content')
    <div class="bg-white py-20">
        <div class="container mx-auto px-4 max-w-4xl">
            <div class="mb-12">
                <span
                    class="inline-block px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold uppercase tracking-wider rounded-full mb-6">Technology</span>
                <h1 class="text-4xl md:text-5xl font-serif font-bold text-slate-900 mb-6">HJParam Launches New AI
                    Authenticity Tool</h1>
                <div
                    class="flex items-center gap-4 text-slate-400 text-sm font-bold uppercase tracking-widest border-y border-slate-100 py-4">
                    <span>09 Jan 2026</span>
                    <span>•</span>
                    <span>Management Team</span>
                </div>
            </div>

            <div class="prose prose-slate max-w-none prose-lg leading-[1.85]">
                <p class="lead text-2xl text-slate-600 mb-12">Implementing state-of-the-art AI detection to verify research
                    integrity and content authenticity.</p>

                <p class="mb-10 text-slate-600">At HJPARAM, we are committed to maintaining the highest standards of
                    academic integrity. To meet the
                    challenges of the modern research landscape, we have officially launched our proprietary <strong>AI
                        Authenticity Tool</strong>, integrated directly into the manuscript submission system (JAMS).</p>

                <h3 class="text-3xl font-serif font-black mt-20 mb-8 text-slate-900 border-l-4 border-blue-600 pl-6">Key
                    Features:</h3>
                <ul class="list-disc pl-8 space-y-6 mb-16 text-slate-600">
                    <li><strong class="text-slate-900">99.4% Detection Accuracy:</strong> Trained on millions of academic
                        papers to distinguish
                        between human-authored and AI-generated content.</li>
                    <li><strong class="text-slate-900">Dynamic Reporting:</strong> Detailed reports providing editors with a
                        "Confidence Score" for
                        every submission.</li>
                    <li><strong class="text-slate-900">Style Consistency Analysis:</strong> Benchmarking current submissions
                        against an author's
                        previous body of work.</li>
                </ul>

                <div class="bg-slate-900 rounded-[3rem] p-16 my-16 text-white relative overflow-hidden shadow-2xl">
                    <div
                        class="absolute top-0 right-0 w-48 h-48 bg-blue-500/10 rounded-full blur-3xl -translate-y-24 translate-x-24">
                    </div>
                    <div class="relative z-10">
                        <h4 class="text-2xl font-serif font-bold mb-6 text-blue-400">Empowering Editors</h4>
                        <p class="text-lg leading-relaxed opacity-90 italic">"This tool isn't just about detection; it's
                            about trust. It provides our
                            editors with the data they need to make informed decisions about paper quality."</p>
                        <div
                            class="mt-8 pt-8 border-t border-white/5 text-[10px] font-black uppercase tracking-[0.3em] text-slate-500">
                            Chief Technical Officer • HJPARAM Infrastructure
                        </div>
                    </div>
                </div>

                <p class="text-slate-600">This tool is now active for all new submissions starting from January 2026.
                    Authors are encouraged to
                    review their AI usage in accordance with our updated
                    <a href="{{ route('info.page', 'process') }}"
                        class="text-blue-600 font-bold underline decoration-blue-100 decoration-4 underline-offset-4 hover:decoration-blue-500 transition-all">Editorial
                        Guidelines</a>.
                </p>
            </div>

            <div class="mt-20 pt-10 border-t border-slate-100 flex justify-between">
                <a href="{{ route('info.page', 'news') }}"
                    class="text-slate-400 hover:text-blue-600 font-bold uppercase tracking-widest text-xs flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                    Back to News
                </a>
                <a href="{{ route('info.page', 'process') }}"
                    class="text-blue-600 hover:text-slate-900 font-bold uppercase tracking-widest text-xs">LEARN ABOUT OUR
                    PROCESS →</a>
            </div>
        </div>
    </div>
@endsection