@extends('layouts.web')
@section('title', 'Call for Papers: Sustainable Energy 2026 | HJPARAM News')

@section('content')
    <div class="bg-white py-20">
        <div class="container mx-auto px-4 max-w-4xl">
            <div class="mb-12">
                <span
                    class="inline-block px-3 py-1 bg-amber-50 text-amber-600 text-[10px] font-bold uppercase tracking-wider rounded-full mb-6">Call
                    for Papers</span>
                <h1 class="text-4xl md:text-5xl font-serif font-bold text-slate-900 mb-6">Special Issue: Sustainable Energy
                    & Solar Innovations 2026</h1>
                <div
                    class="flex items-center gap-4 text-slate-400 text-sm font-bold uppercase tracking-widest border-y border-slate-100 py-4">
                    <span>09 Jan 2026</span>
                    <span>•</span>
                    <span>Energy Science Journal</span>
                </div>
            </div>

            <div class="prose prose-slate max-w-none prose-lg leading-[1.85]">
                <p class="lead text-2xl text-slate-600 mb-12">Inviting original research and reviews focusing on the next
                    generation of renewable energy technologies and energy storage systems.</p>

                <p class="mb-10 text-slate-600">Our upcoming special issue on <strong>Sustainable Energy</strong> aims to
                    highlight breakthroughs that
                    address the global climate crisis. We are specifically looking for high-impact studies involving solar
                    cell efficiency, wind turbine materials, and AI-optimized power grids.</p>

                <h3 class="text-3xl font-serif font-black mt-20 mb-8 text-slate-900 border-l-4 border-amber-500 pl-6">
                    Submission Categories:</h3>
                <ul class="list-disc pl-8 space-y-4 mb-16 text-slate-600">
                    <li><strong class="text-slate-900">Photovoltaic Materials:</strong> & Perovskite Innovation</li>
                    <li><strong class="text-slate-900">Grid Optimization:</strong> Decarbonization Strategies</li>
                    <li><strong class="text-slate-900">Energy Storage:</strong> Next-Gen Battery Chemistry</li>
                    <li><strong class="text-slate-900">Socio-Economics:</strong> Policy & Economic Frameworks</li>
                </ul>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 my-16">
                    <div class="bg-slate-900 text-white p-10 rounded-[2.5rem] shadow-2xl relative overflow-hidden group">
                        <div
                            class="absolute top-0 right-0 w-32 h-32 bg-blue-500/10 rounded-full blur-3xl -translate-y-12 translate-x-12">
                        </div>
                        <div class="relative z-10">
                            <div class="text-[11px] font-black text-blue-400 uppercase tracking-[0.3em] mb-4">Submission
                                Deadline</div>
                            <div class="text-3xl font-serif font-bold group-hover:text-blue-400 transition-colors">June 30,
                                2026</div>
                        </div>
                    </div>
                    <div class="bg-blue-600 text-white p-10 rounded-[2.5rem] shadow-2xl relative overflow-hidden group">
                        <div
                            class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-3xl -translate-y-12 translate-x-12">
                        </div>
                        <div class="relative z-10">
                            <div class="text-[11px] font-black text-blue-100 uppercase tracking-[0.3em] mb-4">Impact Factor
                            </div>
                            <div
                                class="text-3xl font-serif font-bold group-hover:scale-105 transition-transform duration-500 origin-left">
                                8.4 <span class="text-sm font-medium opacity-60">(Projected)</span></div>
                        </div>
                    </div>
                </div>

                <p class="text-slate-600">All submitted papers will undergo our rigorous
                    <a href="{{ route('info.page', 'process') }}"
                        class="text-blue-600 font-bold underline decoration-blue-100 decoration-4 underline-offset-4 hover:decoration-blue-500 transition-all">Double-Blind
                        Peer Review</a>
                    process. Papers accepted for this special issue will receive premium placement on our featured articles
                    list.
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
                <a href="{{ route('author.submit') }}"
                    class="bg-amber-600 hover:bg-slate-900 text-white px-8 py-3 rounded-xl font-bold text-sm transition shadow-lg shadow-amber-200">Submit
                    Now</a>
            </div>
        </div>
    </div>
@endsection