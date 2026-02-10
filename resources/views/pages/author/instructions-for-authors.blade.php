@extends('layouts.web')
@section('title', 'Author Guidelines')

@section('content')
    <div class="bg-slate-50 py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <!-- Header -->
                <div
                    class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 p-12 border border-slate-100 mb-8 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-indigo-50 rounded-bl-full opacity-50"></div>
                    <div class="relative z-10">
                        <span
                            class="inline-block px-3 py-1 bg-indigo-50 text-indigo-700 text-[10px] font-bold uppercase tracking-wider rounded-full mb-6">Author
                            Services</span>
                        <h1 class="text-4xl md:text-5xl font-serif font-bold text-slate-900 mb-6">Author Guidelines</h1>
                        <p class="text-lg text-slate-500 leading-relaxed max-w-2xl">
                            Comprehensive instructions for preparing and submitting your manuscript to HJPARAM journals.
                        </p>
                    </div>
                </div>

                <!-- Content Area -->
                <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 p-12 border border-slate-100">
                    <div
                        class="prose prose-slate max-w-none prose-headings:font-serif prose-headings:text-slate-900 prose-p:text-slate-600 prose-p:leading-relaxed">

                        <h2 class="text-2xl font-bold mb-6">1. Manuscript Submission</h2>
                        <p>
                            HJPARAM journals accept manuscripts exclusively through our online submission portal. To start a
                            new submission, please ensure you have registered for an account and have all required documents
                            ready.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 my-8">
                            <a href="{{ route('author.submit') }}"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-2xl flex items-center justify-between transition group shadow-lg shadow-blue-100">
                                <span class="font-bold">Portal Login</span>
                                <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M13 7l5 5m0 0l-5 5m5-5H6" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </a>
                            <a href="{{ route('register') }}"
                                class="flex-1 bg-slate-800 hover:bg-slate-900 text-white p-4 rounded-2xl flex items-center justify-between transition group shadow-lg shadow-slate-200">
                                <span class="font-bold">Create Account</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M18 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>
                        </div>

                        <h2 class="text-2xl font-bold mt-12 mb-6">2. Preparation Requirements</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                            <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100">
                                <h3 class="text-lg font-bold text-slate-900 mb-3 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    File Formats
                                </h3>
                                <p class="text-slate-600 text-sm">Main text should be submitted in Microsoft Word (.doc or
                                    .docx) or LaTeX. Figures should be high-resolution (min 300 DPI).</p>
                            </div>
                            <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100">
                                <h3 class="text-lg font-bold text-slate-900 mb-3 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    Language
                                </h3>
                                <p class="text-slate-600 text-sm">Manuscripts must be in clear, concise English. Authors may
                                    use either British or American spelling, but not a mixture.</p>
                            </div>
                        </div>

                        <h3 class="text-xl font-bold mb-4">Manuscript Structure</h3>
                        <ul class="space-y-3 mb-12">
                            <li class="flex items-start gap-3">
                                <div
                                    class="mt-1 flex-shrink-0 w-5 h-5 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                    </svg>
                                </div>
                                <span class="text-slate-600"><strong>Title Page:</strong> Concise title, full author names,
                                    and affiliations.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <div
                                    class="mt-1 flex-shrink-0 w-5 h-5 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                    </svg>
                                </div>
                                <span class="text-slate-600"><strong>Abstract:</strong> Max 250 words, unreferenced,
                                    summarising objectives and results.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <div
                                    class="mt-1 flex-shrink-0 w-5 h-5 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                    </svg>
                                </div>
                                <span class="text-slate-600"><strong>Keywords:</strong> 5-10 specific terms for indexing and
                                    searchability.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <div
                                    class="mt-1 flex-shrink-0 w-5 h-5 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                    </svg>
                                </div>
                                <span class="text-slate-600"><strong>Sections:</strong> Introduction, Methodology, Results,
                                    Discussion, Conclusion.</span>
                            </li>
                        </ul>

                        <h2 class="text-2xl font-bold mt-12 mb-4">3. Ethics and Disclosures</h2>
                        <p>
                            Authors must provide a signed conflict of interest statement and disclose any funding sources
                            that supported the research. Human and animal research must include ethics committee approval
                            details.
                        </p>

                        <div
                            class="mt-16 pt-8 border-t border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-6">
                            <a href="{{ route('home') }}"
                                class="flex items-center gap-2 text-slate-400 hover:text-blue-600 transition group">
                                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                <span class="text-sm font-bold uppercase tracking-widest">Back to Home</span>
                            </a>
                            <div class="flex gap-4">
                                <a href="{{ $settings['manuscript_template_url'] ?? '#' }}" target="_blank"
                                    class="text-slate-500 hover:text-slate-800 text-sm font-bold underline flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    Download Template (.DOCX)
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection