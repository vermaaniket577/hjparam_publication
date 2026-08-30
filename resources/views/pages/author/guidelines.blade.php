@extends('layouts.web')
@section('title', 'Author Guidelines | Scholar Services')

@section('content')
    <div class="bg-slate-50 py-20 min-h-screen">
        <div class="container mx-auto px-4">
            <div class="max-w-7xl mx-auto">
                {{-- Elite Header --}}
                <div class="bg-[#020617] rounded-[3rem] shadow-2xl p-16 mb-12 relative overflow-hidden">
                    <div class="absolute inset-0 opacity-20">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-600/20 to-transparent"></div>
                        <div
                            class="absolute inset-0 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:40px_40px] opacity-10">
                        </div>
                    </div>
                    <div class="relative z-10">
                        <span
                            class="inline-block px-4 py-1.5 bg-blue-500/10 text-blue-400 text-[10px] font-black uppercase tracking-[0.3em] rounded-full mb-8 border border-blue-500/20">
                            Scholarly Infrastructure
                        </span>
                        <h1 class="text-5xl md:text-7xl font-serif font-bold text-white mb-8 tracking-tight">
                            Author <span class="text-blue-500 italic">Guidelines</span>
                        </h1>
                        <p class="text-xl text-slate-400 leading-relaxed max-w-2xl font-light">
                            A comprehensive standard for manuscript preparation, ethical compliance, and structural
                            integrity for HJPARAM publications.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                    {{-- Sticky Structural Navigation --}}
                    <div class="lg:col-span-3">
                        <div class="sticky top-24 space-y-8">
                            <div class="bg-white rounded-3xl border border-slate-100 p-8 shadow-sm">
                                <h3
                                    class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 mb-6 flex items-center gap-2">
                                    <span class="w-1 h-4 bg-blue-600 rounded-full"></span>
                                    Structure
                                </h3>
                                <nav class="space-y-1">
                                    @foreach(['Submission', 'Preparation', 'Ethics & Disclosure', 'Formatting', 'Templates'] as $item)
                                        <a href="#{{ Str::slug($item) }}"
                                            class="flex items-center px-4 py-3 text-xs font-bold text-slate-500 hover:text-blue-600 hover:bg-blue-50/50 rounded-xl transition-all group">
                                            <span class="group-hover:translate-x-1 transition-transform">{{ $item }}</span>
                                        </a>
                                    @endforeach
                                </nav>
                            </div>

                            <div
                                class="bg-blue-600 rounded-3xl p-8 text-white shadow-xl shadow-blue-600/20 relative overflow-hidden group">
                                <div
                                    class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                                    <svg class="w-16 h-16 rotate-12" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 4v16m8-8H4" stroke-width="2.5"></path>
                                    </svg>
                                </div>
                                <h4 class="text-sm font-black uppercase tracking-widest mb-4">Start Research</h4>
                                <p class="text-xs text-blue-100 mb-6 leading-relaxed">Ready to present your findings?
                                    Initialize your submission today.</p>
                                <a href="{{ route('author.submit') }}"
                                    class="block w-full text-center py-4 bg-white text-blue-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 transition-colors shadow-lg shadow-black/5">Submit
                                    Now</a>
                            </div>
                        </div>
                    </div>

                    {{-- Content Flow --}}
                    <div class="lg:col-span-9 space-y-12">
                        <div id="submission"
                            class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-12 md:p-16">
                            <h2 class="text-3xl font-serif font-black text-slate-900 mb-8 flex items-center gap-4">
                                <span class="text-blue-600 text-xl font-mono opacity-30">01</span>
                                Manuscript Submission
                            </h2>
                            <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed">
                                <p class="text-lg">HJPARAM journals operate exclusively through a world-class digital
                                    submission portal. This ensures rigorous tracking, peer-review transparency, and data
                                    integrity.</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 my-10">
                                    <div
                                        class="p-8 bg-slate-50 rounded-[2rem] border border-slate-100 group hover:border-blue-200 transition-all">
                                        <h4 class="text-sm font-black uppercase tracking-widest text-slate-900 mb-4">Initial
                                            Registration</h4>
                                        <p class="text-xs text-slate-500 mb-6">Create your scholar account to access the
                                            submission dashboard and track your research in real-time.</p>
                                        <a href="{{ route('register') }}"
                                            class="inline-flex items-center gap-2 text-[10px] font-black text-blue-600 uppercase tracking-widest group-hover:gap-4 transition-all">Register
                                            Now <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-width="2.5"></path>
                                            </svg></a>
                                    </div>
                                    <div
                                        class="p-8 bg-slate-50 rounded-[2rem] border border-slate-100 group hover:border-blue-200 transition-all">
                                        <h4 class="text-sm font-black uppercase tracking-widest text-slate-900 mb-4">Author
                                            Dashboard</h4>
                                        <p class="text-xs text-slate-500 mb-6">Already have an account? Access your current
                                            manuscripts and reviewer feedback.</p>
                                        <a href="{{ route('login') }}"
                                            class="inline-flex items-center gap-2 text-[10px] font-black text-blue-600 uppercase tracking-widest group-hover:gap-4 transition-all">Portal
                                            Login <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-width="2.5"></path>
                                            </svg></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="preparation"
                            class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-12 md:p-16">
                            <h2 class="text-3xl font-serif font-black text-slate-900 mb-8 flex items-center gap-4">
                                <span class="text-blue-600 text-xl font-mono opacity-30">02</span>
                                Preparation Requirements
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                                <div class="p-8 bg-blue-50/30 rounded-3xl border border-blue-50">
                                    <svg class="w-8 h-8 text-blue-600 mb-6" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                            stroke-width="2"></path>
                                    </svg>
                                    <h4 class="text-sm font-black uppercase tracking-widest text-slate-900 mb-3">Technical
                                        Format</h4>
                                    <p class="text-xs text-slate-500 leading-relaxed">Manuscripts must be submitted in
                                        Microsoft Word (.docx) or LaTeX. Scientific figures require a minimum resolution of
                                        300 DPI.</p>
                                </div>
                                <div class="p-8 bg-emerald-50/30 rounded-3xl border border-emerald-50">
                                    <svg class="w-8 h-8 text-emerald-600 mb-6" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"
                                            stroke-width="2"></path>
                                    </svg>
                                    <h4 class="text-sm font-black uppercase tracking-widest text-slate-900 mb-3">Linguistic
                                        Standard</h4>
                                    <p class="text-xs text-slate-500 leading-relaxed">Professional, concise academic English
                                        is mandatory. Consistency between British or American spellings must be maintained.
                                    </p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                @foreach(['Title Page (Including Affiliations)', 'Abstract (Max 250 words)', 'Keywords (5-10 technical terms)', 'Sectional Hierarchy (IMRAD format)', 'Institutional Data Availability Statement'] as $rule)
                                    <div
                                        class="flex items-center gap-4 p-4 rounded-xl border border-slate-50 hover:bg-slate-50 transition-colors">
                                        <div class="w-2 h-2 bg-blue-600 rounded-full"></div>
                                        <span class="text-sm font-medium text-slate-600">{{ $rule }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div id="ethics-disclosure"
                            class="bg-[#020617] rounded-[2.5rem] shadow-2xl p-12 md:p-16 text-white relative overflow-hidden">
                            <div class="absolute top-0 right-0 p-12 opacity-5">
                                <svg class="w-40 h-40" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                        stroke-width="2"></path>
                                </svg>
                            </div>
                            <h2 class="text-3xl font-serif font-black mb-8 flex items-center gap-4">
                                <span class="text-blue-500 text-xl font-mono opacity-50">03</span>
                                Ethics & Disclosure
                            </h2>
                            <p class="text-slate-400 mb-8 leading-relaxed max-w-2xl">Scholarly integrity is our highest
                                priority. All manuscripts must include explicit ethical statements regarding human/animal
                                subjects and clear financial disclosure.</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div
                                    class="p-6 bg-white/5 rounded-2xl border border-white/10 hover:bg-white/10 transition-colors">
                                    <h5 class="text-[10px] font-black uppercase tracking-widest text-blue-400 mb-2">
                                        Requirement A</h5>
                                    <p class="text-xs text-slate-200">Institutional Review Board (IRB) approval details.</p>
                                </div>
                                <div
                                    class="p-6 bg-white/5 rounded-2xl border border-white/10 hover:bg-white/10 transition-colors">
                                    <h5 class="text-[10px] font-black uppercase tracking-widest text-emerald-400 mb-2">
                                        Requirement B</h5>
                                    <p class="text-xs text-slate-200">Comprehensive conflict of interest declaration.</p>
                                </div>
                            </div>
                        </div>

                        <div id="templates"
                            class="bg-blue-600 rounded-[2.5rem] shadow-xl shadow-blue-600/20 p-12 md:p-16 text-white text-center">
                            <h2 class="text-3xl font-serif font-black mb-4">Registry Templates</h2>
                            <p class="text-blue-100 mb-10 max-w-xl mx-auto">Download our official scholarly templates to
                                ensure your manuscript meets HJPARAM structural standards immediately.</p>
                            <div class="flex flex-col sm:flex-row justify-center gap-6">
                                <a href="{{ $settings['manuscript_template_url'] ?? '#' }}"
                                    class="bg-white text-blue-600 px-10 py-5 rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-blue-50 transition-all shadow-xl shadow-black/10">Download
                                    .DOCX Template</a>
                                <a href="#"
                                    class="bg-blue-700 text-white border border-blue-500 px-10 py-5 rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-blue-800 transition-all">Download
                                    LaTeX Package</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection