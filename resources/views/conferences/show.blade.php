@extends('layouts.web')
@section('title', $conference->title . ' | International Conferences | HJPARAM')

@section('content')
<div class="bg-slate-50 min-h-screen">
    <!-- Hero Banner -->
    <div class="relative h-[400px] md:h-[500px] overflow-hidden">
        @if($conference->banner_image)
            <img src="{{ asset('storage/' . $conference->banner_image) }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full bg-[#0f172a] flex items-center justify-center p-12 text-center relative overflow-hidden">
                <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
                <div class="absolute top-0 right-0 w-2/3 h-full bg-blue-600/10 -skew-x-12 translate-x-1/4 blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-1/3 h-full bg-indigo-600/10 skew-x-12 -translate-x-1/4 blur-3xl"></div>
                <div class="relative z-10 max-w-4xl">
                    <h1 class="text-3xl md:text-5xl font-serif font-black text-white mb-6 leading-tight tracking-tight">{{ $conference->title }}</h1>
                    <div class="flex flex-wrap justify-center gap-6">
                        <div class="px-6 py-2 bg-white/10 backdrop-blur border border-white/20 rounded-full text-white text-xs font-black uppercase tracking-widest">{{ $conference->type }}</div>
                        <div class="px-6 py-2 bg-blue-600/20 backdrop-blur border border-blue-500/30 rounded-full text-blue-400 text-xs font-black uppercase tracking-widest">{{ $conference->category->name }}</div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Main Content Container -->
    <div class="container mx-auto px-4 -mt-32 relative z-20 pb-32">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
            
            <!-- Sidebar: Key Details -->
            <div class="lg:col-span-4 space-y-8">
                <div class="bg-white rounded-[2.5rem] p-10 shadow-2xl border border-slate-100 sticky top-32 overflow-hidden">
                    <!-- Status Badge -->
                    @if($conference->early_bird_deadline > now())
                        <div class="absolute top-0 right-0 bg-emerald-500 text-white text-[9px] font-black px-6 py-2 uppercase tracking-widest rounded-bl-3xl shadow-lg">Early Bird Open</div>
                    @endif

                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-10 pb-4 border-b">Event Details</h3>
                    
                    <div class="space-y-10">
                        <div class="flex items-start gap-6 group">
                            <div class="w-14 h-14 bg-slate-50 flex items-center justify-center rounded-2xl border border-slate-100 group-hover:bg-blue-600 group-hover:border-blue-500 transition-colors duration-500">
                                <svg class="w-6 h-6 text-slate-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">When</span>
                                <span class="text-sm font-bold text-slate-800 leading-tight">{{ $conference->start_date->format('F d, Y') }} @if($conference->start_date != $conference->end_date) - {{ $conference->end_date->format('F d, Y') }} @endif</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-6 group">
                            <div class="w-14 h-14 bg-slate-50 flex items-center justify-center rounded-2xl border border-slate-100 group-hover:bg-blue-600 group-hover:border-blue-500 transition-colors duration-500">
                                <svg class="w-6 h-6 text-slate-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Where</span>
                                <span class="text-sm font-bold text-slate-800 leading-tight">{{ $conference->venue }}, {{ $conference->city }}</span>
                                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-tight mt-1">{{ $conference->country->name }}</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-6 group">
                            <div class="w-14 h-14 bg-slate-50 flex items-center justify-center rounded-2xl border border-slate-100 group-hover:bg-blue-600 group-hover:border-blue-500 transition-colors duration-500">
                                <svg class="w-6 h-6 text-slate-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Official Organizer</span>
                                <span class="text-sm font-bold text-slate-800 leading-tight">{{ $conference->organizer_name }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 pt-10 border-t border-slate-50 space-y-6">
                        <a href="{{ $conference->external_link }}" target="_blank" class="block w-full text-center py-5 bg-[#0f172a] hover:bg-blue-700 text-white font-black rounded-2xl shadow-xl shadow-slate-900/10 hover:shadow-blue-600/30 transition-all duration-300 uppercase tracking-widest text-[11px] hover:-translate-y-1">Register Now</a>
                        <button onclick="window.print()" class="block w-full text-center py-5 bg-white border border-slate-200 text-slate-600 font-bold rounded-2xl hover:bg-slate-50 transition-all uppercase tracking-widest text-[10px]">Print Brochures</button>
                    </div>

                    @if($conference->invitation_letter_support)
                        <div class="mt-8 bg-blue-50 rounded-2xl p-6 flex items-start gap-4 border border-blue-100">
                            <div class="w-10 h-10 bg-blue-600 text-white flex items-center justify-center rounded-xl shadow-lg ring-4 ring-blue-500/10 flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-black text-blue-900 uppercase tracking-widest mb-1.5">Visa Support</h4>
                                <p class="text-[11px] text-blue-700 font-medium leading-relaxed">Invitation letter support is available for international participants.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Content Area -->
            <div class="lg:col-span-8 space-y-12">
                <div class="bg-white rounded-[3rem] p-10 md:p-16 shadow-2xl border border-slate-100 overflow-hidden group">
                    <!-- Heading -->
                    <div class="mb-12">
                        <h2 class="text-3xl font-serif font-black text-slate-900 mb-6 tracking-tight flex items-center gap-4">
                            Event <span class="text-blue-900 italic underline decoration-blue-100 decoration-8 underline-offset-8">Description</span>
                        </h2>
                        <div class="prose prose-slate lg:prose-xl max-w-none text-slate-600 leading-relaxed space-y-6">
                            {!! nl2br(e($conference->description)) !!}
                        </div>
                    </div>

                    <!-- Additional Details Table -->
                    <div class="mt-16 pt-12 border-t border-slate-50">
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-10">Administrative Highlights</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 group-hover:bg-white transition-colors duration-500">
                                <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Registration ID</span>
                                <span class="block font-bold text-slate-900">HJ-CONF-{{ str_pad($conference->id, 6, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 group-hover:bg-white transition-colors duration-500">
                                <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Participation Mode</span>
                                <span class="block font-bold text-slate-900 capitalize">{{ $conference->type }} Conference</span>
                            </div>
                            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 group-hover:bg-white transition-colors duration-500">
                                <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Early Bird Deadline</span>
                                <span class="block font-bold text-slate-900">{{ $conference->early_bird_deadline ? $conference->early_bird_deadline->format('M d, Y') : 'N/A' }}</span>
                            </div>
                            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 group-hover:bg-white transition-colors duration-500">
                                <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Contact Support</span>
                                <span class="block font-bold text-blue-600 cursor-pointer">Inquire with Organizer</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Call for Papers Mockup -->
                <div class="bg-[#0f172a] rounded-[3rem] p-12 md:p-20 shadow-2xl text-center relative overflow-hidden group/cta">
                    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
                    <div class="absolute -top-32 -left-32 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl group-hover/cta:bg-blue-500/20 transition-all duration-700"></div>
                    <div class="relative z-10">
                        <h2 class="text-3xl md:text-5xl font-serif font-black text-white mb-8 tracking-tight">Call for <span class="text-blue-400">Papers</span></h2>
                        <p class="text-slate-400 text-lg md:text-xl max-w-2xl mx-auto mb-12 leading-relaxed font-medium">
                            The organizing committee invites submissions of high-quality research papers, posters, and workshops. Be part of this global dialogue.
                        </p>
                        <div class="flex flex-wrap justify-center gap-6">
                            <a href="{{ route('author.submit') }}" class="px-12 py-5 bg-white text-slate-900 font-black rounded-2xl shadow-2xl hover:bg-blue-400 hover:text-white transition-all transform hover:-translate-y-1 uppercase tracking-widest text-[11px]">Submit Abstract</a>
                            <a href="{{ $conference->external_link }}" class="px-12 py-5 bg-transparent border border-white/20 text-white font-black rounded-2xl hover:bg-white/5 transition-all uppercase tracking-widest text-[11px]">Submission Portal</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
