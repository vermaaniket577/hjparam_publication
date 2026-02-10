@extends('layouts.web')
@section('title', 'Global Sustainability Initiative')

@section('content')
    <div class="bg-slate-50 py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto">
                <!-- Hero Header -->
                <div
                    class="bg-emerald-900 rounded-[3rem] shadow-2xl shadow-emerald-200/50 p-12 md:p-20 border border-emerald-800 mb-12 relative overflow-hidden text-white">
                    <div class="absolute top-0 right-0 w-96 h-96 bg-emerald-800/50 rounded-full blur-[100px] -mr-48 -mt-48">
                    </div>
                    <div
                        class="absolute bottom-0 left-0 w-64 h-64 bg-emerald-400/10 rounded-full blur-[80px] -ml-32 -mb-32">
                    </div>

                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-8">
                            <span
                                class="px-4 py-1.5 bg-emerald-400/20 text-emerald-300 text-[10px] font-bold uppercase tracking-[0.2em] rounded-full border border-emerald-400/30">Science
                                for Earth</span>
                        </div>
                        <h1 class="text-4xl md:text-6xl font-serif font-bold mb-8 leading-tight">Global <span
                                class="text-emerald-400">Sustainability</span> Initiative</h1>
                        <p class="text-xl text-emerald-100/80 leading-relaxed max-w-2xl font-light">
                            HJPARAM is committed to advancing research that addresses the world's most pressing
                            environmental and social challenges.
                        </p>
                    </div>
                </div>

                <!-- Impact Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                    <div
                        class="bg-white p-10 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/40 text-center group hover:border-emerald-200 transition-all duration-500">
                        <div
                            class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Eco-Publishing</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Our digital-first strategy eliminates the carbon
                            footprint of traditional print journals.</p>
                    </div>

                    <div
                        class="bg-white p-10 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/40 text-center group hover:border-emerald-200 transition-all duration-500">
                        <div
                            class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">SDG Alignment</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">We prioritize and fast-track research supporting
                            UN Sustainable Development Goals.</p>
                    </div>

                    <div
                        class="bg-white p-10 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/40 text-center group hover:border-emerald-200 transition-all duration-500">
                        <div
                            class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">WAIVER Program</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">100% discount on APCs for authors from low-income
                            countries to ensure voices are heard.</p>
                    </div>
                </div>

                <!-- Detailed Section -->
                <div
                    class="bg-white rounded-[3rem] shadow-2xl shadow-slate-200/40 border border-slate-100 overflow-hidden mb-16">
                    <div class="flex flex-col lg:flex-row">
                        <div class="lg:w-1/2 p-12 md:p-16">
                            <h2 class="text-3xl font-serif font-bold text-slate-900 mb-8 leading-tight">Driving Impact
                                Through <span class="text-emerald-600">Open Science</span></h2>
                            <div class="prose prose-slate max-w-none">
                                <p class="text-slate-600 mb-6">Sustainability is not just a topic we publish about; it's a
                                    core operational value. We believe that by removing financial and technical barriers to
                                    scientific knowledge, we can accelerate the journey toward a zero-carbon future.</p>

                                <h4 class="font-bold text-slate-900 mb-4 uppercase tracking-wider text-xs">Key Focus Areas:
                                </h4>
                                <ul class="space-y-4 text-slate-600">
                                    <li class="flex items-center gap-3">
                                        <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                                        <span>Renewable Energy Systems & Storage</span>
                                    </li>
                                    <li class="flex items-center gap-3">
                                        <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                                        <span>Biodiversity Conservation Tech</span>
                                    </li>
                                    <li class="flex items-center gap-3">
                                        <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                                        <span>Circular Economy Models</span>
                                    </li>
                                    <li class="flex items-center gap-3">
                                        <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                                        <span>Climate Resilience in Agriculture</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="lg:w-1/2 bg-emerald-50 flex items-center justify-center p-12">
                            <div class="relative">
                                <div class="absolute inset-0 bg-emerald-200 rounded-full blur-3xl opacity-30 animate-pulse">
                                </div>
                                <img src="https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80"
                                    alt="Sustainability"
                                    class="w-full h-80 object-cover rounded-[2rem] shadow-2xl relative z-10 transition-transform hover:scale-105 duration-700">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Call to Action -->
                <div class="bg-slate-900 rounded-[3rem] p-12 md:p-16 text-center text-white relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-emerald-600/20 to-transparent"></div>
                    <div class="relative z-10">
                        <h2 class="text-3xl md:text-4xl font-serif font-bold mb-6">Submit Your Sustainability Research</h2>
                        <p class="text-slate-400 max-w-2xl mx-auto mb-10">We offer specialized editorial tracks for research
                            contributing to global environmental resilience. Submit today and reach a global audience.</p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="{{ route('author.submit') }}"
                                class="px-8 py-4 bg-emerald-500 hover:bg-emerald-600 text-white rounded-2xl font-bold transition shadow-lg shadow-emerald-500/20">Submit
                                Manuscript</a>
                            <a href="{{ route('about.page', 'contact-information') }}"
                                class="px-8 py-4 bg-white/10 hover:bg-white/20 text-white rounded-2xl font-bold transition backdrop-blur-sm border border-white/10">Partner
                                with Us</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection