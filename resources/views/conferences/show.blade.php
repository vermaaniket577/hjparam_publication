@extends('layouts.web')
@section('title', $conference->title . ' | International Conferences | HJPARAM')

@section('content')
<div class="bg-slate-50 min-h-screen" x-data="{ showEnquiryModal: false }">

    <!-- Flash Alert for Enquiry Success -->
    @if(session('enquiry_success'))
        <div class="bg-emerald-600 text-white py-3 px-6 text-center text-xs md:text-sm font-bold shadow-lg flex items-center justify-center gap-2 sticky top-0 z-50">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('enquiry_success') }}</span>
        </div>
    @endif

    @if(session('success'))
        <div class="bg-blue-600 text-white py-3 px-6 text-center text-xs md:text-sm font-bold shadow-lg flex items-center justify-center gap-2 sticky top-0 z-50">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Hero Banner with Cinematic Background & Rich Details -->
    <div class="relative py-20 md:py-28 overflow-hidden text-white" style="background-color: #0a192f !important; color: #ffffff !important; position: relative;">
        <!-- Background Image with Dark Contrast Overlay -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none" style="position: absolute; inset: 0; background: #0a192f;">
            <img src="{{ $conference->banner_or_fallback }}" 
                 onerror="this.onerror=null; this.src='{{ asset('images/conference-hero-bg.jpg') }}';"
                 alt="{{ $conference->title }}" 
                 class="w-full h-full object-cover object-center select-none scale-105"
                 style="width: 100%; height: 100%; object-fit: cover; opacity: 0.75;">
            <div style="position: absolute; inset: 0; background: linear-gradient(to top, #0a192f 0%, rgba(10, 25, 47, 0.4) 100%);"></div>
            <div style="position: absolute; inset: 0; background: linear-gradient(to right, rgba(10, 25, 47, 0.85) 0%, rgba(10, 25, 47, 0.45) 50%, rgba(10, 25, 47, 0.15) 100%);"></div>
        </div>

        <div class="container mx-auto px-6 relative z-10" style="position: relative; z-index: 10;">
            <div class="max-w-4xl space-y-4">
                <!-- Red/Rose Prefix Header -->
                <span class="block font-extrabold text-lg md:text-2xl tracking-tight leading-snug" style="color: #f43f5e !important;">
                    International Conference on
                </span>

                <!-- Main Conference Title -->
                <h1 class="text-3xl md:text-5xl lg:text-6xl font-serif font-black leading-tight tracking-tight drop-shadow-md" style="color: #ffffff !important;">
                    {{ $conference->title }}
                </h1>

                <!-- Mode Subtitle (Hybrid Mode / Physical Mode) -->
                <div class="text-xl md:text-3xl font-serif font-extrabold tracking-tight pt-1" style="color: #fde047 !important;">
                    {{ ucfirst($conference->type) }} Mode
                </div>

                <!-- Location Badge Pill -->
                <div class="pt-2">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs md:text-sm font-bold shadow-lg" style="background-color: #e11d48 !important; color: #ffffff !important;">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                        <span>{{ $conference->city ?? 'Virtual' }}, {{ $conference->country->name }}</span>
                    </div>
                </div>

                <!-- Organizer & Collaboration Details -->
                <div class="space-y-1 pt-3 text-slate-200 text-sm md:text-base font-medium">
                    <p class="flex items-center gap-2">
                        <span class="text-slate-400">Organized by :</span>
                        <strong class="text-white font-bold">{{ $conference->organizer_name }}</strong>
                    </p>
                    <p class="flex items-center gap-2">
                        <span class="text-slate-400">In collaboration with:</span>
                        <strong class="text-white font-bold">HJParam Academic & IRAJ International</strong>
                    </p>
                </div>

                <!-- Indexing & Partner Badges Row -->
                <div class="flex items-center gap-3 pt-3 flex-wrap">
                    <div class="px-4 py-2 bg-white text-slate-900 rounded-xl shadow-md border border-slate-200 flex items-center justify-center h-11">
                        <span class="text-xs font-black tracking-tight text-slate-900 leading-none">academics <span class="text-rose-600">world</span></span>
                    </div>
                    <div class="px-4 py-2 bg-white text-slate-900 rounded-xl shadow-md border border-slate-200 flex items-center justify-center h-11">
                        <span class="text-sm font-black tracking-tight text-[#f97316]">Scopus</span>
                    </div>
                    <div class="px-4 py-2 bg-white text-slate-900 rounded-xl shadow-md border border-slate-200 flex flex-col items-center justify-center h-11">
                        <span class="text-[8px] font-bold text-slate-400 uppercase leading-none">Clarivate Analytics</span>
                        <span class="text-[10px] font-black text-slate-800 uppercase tracking-tight">WEB OF SCIENCE™</span>
                    </div>
                </div>

                <!-- Action Button Row (Paper Submission, Send Enquiry, Download Brochure, Paper Formats) -->
                <div class="flex flex-wrap items-center gap-3.5 pt-6">
                    <a href="{{ route('conferences.submit', $conference->slug) }}" 
                       style="background-color: #1d4ed8 !important; color: #ffffff !important; font-weight: 800; font-size: 12px; text-transform: uppercase; letter-spacing: 0.06em; padding: 14px 26px; border-radius: 14px; text-decoration: none; display: inline-flex; align-items: center; box-shadow: 0 4px 14px rgba(29, 78, 216, 0.45);"
                       class="hover:opacity-95 hover:scale-105 transition-all">
                        Paper Submission
                    </a>
                    
                    <button type="button" @click="showEnquiryModal = true"
                       style="background-color: #e11d48 !important; color: #ffffff !important; font-weight: 800; font-size: 12px; text-transform: uppercase; letter-spacing: 0.06em; padding: 14px 26px; border-radius: 14px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(225, 29, 72, 0.45); cursor: pointer;"
                       class="hover:opacity-95 hover:scale-105 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        Send Enquiry
                    </button>

                    @if($conference->brochure_url)
                        <a href="{{ $conference->brochure_url }}" target="_blank" download 
                           style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800; font-size: 12px; text-transform: uppercase; letter-spacing: 0.06em; padding: 14px 26px; border-radius: 14px; border: 1px solid #334155; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(0, 0, 0, 0.3);"
                           class="hover:opacity-95 hover:scale-105 transition-all">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Download Brochure
                        </a>
                    @endif

                    @if($conference->paper_template_url)
                        <a href="{{ $conference->paper_template_url }}" target="_blank" download 
                           class="px-4 py-3 bg-white/10 hover:bg-white/20 text-white rounded-xl border border-white/20 text-xs font-bold transition-all inline-flex items-center gap-2 backdrop-blur-md">
                            <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Paper Template
                        </a>
                    @endif

                    @if($conference->meeting_link)
                        <a href="{{ $conference->meeting_link }}" target="_blank"
                           class="px-4 py-3 bg-emerald-600/80 hover:bg-emerald-600 text-white rounded-xl border border-emerald-400/40 text-xs font-bold transition-all inline-flex items-center gap-2 shadow-lg shadow-emerald-950/40">
                            <svg class="w-4 h-4 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            Online Meeting Room
                        </a>
                    @endif

                    @if($conference->paper_format_1_url)
                        <a href="{{ $conference->paper_format_1_url }}" target="_blank" download 
                           class="px-4 py-3 bg-white/10 hover:bg-white/20 text-white rounded-xl border border-white/20 text-xs font-bold transition-all inline-flex items-center gap-2 backdrop-blur-md">
                            <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            Paper Format 1
                        </a>
                    @endif

                    @if($conference->paper_format_2_url)
                        <a href="{{ $conference->paper_format_2_url }}" target="_blank" download 
                           class="px-4 py-3 bg-white/10 hover:bg-white/20 text-white rounded-xl border border-white/20 text-xs font-bold transition-all inline-flex items-center gap-2 backdrop-blur-md">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            Paper Format 2
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Container -->
    <div class="container mx-auto px-4 -mt-10 md:-mt-12 relative z-20 pb-32">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
            
            <!-- Sidebar: Key Details & Quick Action -->
            <div class="lg:col-span-4 space-y-8">
                <div class="bg-white rounded-[2.5rem] p-8 md:p-10 shadow-2xl border border-slate-100 sticky top-32 overflow-hidden space-y-8">
                    <!-- Status Badge -->
                    @if($conference->early_bird_deadline > now())
                        <div class="absolute top-0 right-0 bg-emerald-500 text-white text-[9px] font-black px-6 py-2 uppercase tracking-widest rounded-bl-3xl shadow-lg">Early Bird Open</div>
                    @endif

                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] pb-4 border-b">Event Details</h3>
                    
                    <div class="space-y-6">
                        <div class="flex items-start gap-4 group">
                            <div class="w-12 h-12 bg-slate-50 flex items-center justify-center rounded-2xl border border-slate-100 group-hover:bg-blue-600 group-hover:border-blue-500 transition-colors duration-500 flex-shrink-0">
                                <svg class="w-5 h-5 text-slate-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Conference Date</span>
                                <span class="text-sm font-bold text-slate-800 leading-tight">{{ $conference->start_date->format('F d, Y') }} @if($conference->start_date != $conference->end_date) - {{ $conference->end_date->format('F d, Y') }} @endif</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-4 group">
                            <div class="w-12 h-12 bg-slate-50 flex items-center justify-center rounded-2xl border border-slate-100 group-hover:bg-blue-600 group-hover:border-blue-500 transition-colors duration-500 flex-shrink-0">
                                <svg class="w-5 h-5 text-slate-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Location</span>
                                <span class="text-sm font-bold text-slate-800 leading-tight">{{ $conference->venue }}, {{ $conference->city }}</span>
                                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-tight mt-0.5">{{ $conference->country->name }}</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-4 group">
                            <div class="w-12 h-12 bg-slate-50 flex items-center justify-center rounded-2xl border border-slate-100 group-hover:bg-blue-600 group-hover:border-blue-500 transition-colors duration-500 flex-shrink-0">
                                <svg class="w-5 h-5 text-slate-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Organizers</span>
                                <span class="text-sm font-bold text-slate-800 leading-tight">{{ $conference->organizer_name }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Direct Connect Buttons: Call or Text & Email -->
                    <div class="pt-6 border-t border-slate-100 space-y-3">
                        <span class="block text-[10px] font-black text-slate-400 uppercase tracking-wider text-center">Fast Contact & Enquiry</span>
                        
                        <div class="grid grid-cols-2 gap-2">
                            <a href="tel:{{ $conference->contact_phone ?? '+15551234567' }}" 
                               class="py-3 px-3 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 transition-all text-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                Call
                            </a>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $conference->contact_phone ?? '15551234567') }}" target="_blank"
                               class="py-3 px-3 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 transition-all text-center">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                                Text
                            </a>
                        </div>

                        <a href="mailto:{{ $conference->contact_email ?? 'enquiry@hjparam.org' }}?subject=Enquiry for {{ rawurlencode($conference->title) }}" 
                           class="w-full py-3 px-4 bg-slate-900 hover:bg-blue-600 text-white rounded-xl text-xs font-bold flex items-center justify-center gap-2 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            Email Secretariat
                        </a>
                    </div>

                    <div class="pt-4 space-y-3">
                        <a href="{{ route('conferences.submit', $conference->slug) }}" class="block w-full text-center py-4 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-2xl shadow-xl shadow-blue-600/30 transition-all uppercase tracking-widest text-xs">
                            Submit Manuscript
                        </a>
                        <button type="button" @click="showEnquiryModal = true" class="block w-full text-center py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold rounded-2xl transition-all uppercase tracking-widest text-xs">
                            Open Enquiry Form
                        </button>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="lg:col-span-8 space-y-10">

                <!-- 1. Important Dates Section (4 Distinct Milestone Windows) -->
                <div class="bg-white rounded-[2.5rem] p-8 md:p-12 shadow-xl border border-slate-100 text-center">
                    <h2 class="text-2xl md:text-3xl font-serif font-black text-slate-900 mb-8 tracking-tight">
                        Important <span class="text-rose-600">Dates</span>
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <!-- Abstract Submission -->
                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 flex flex-col justify-between text-left">
                            <span class="text-[10px] font-black uppercase tracking-wider text-blue-600 mb-2">Abstract Submission</span>
                            <div class="space-y-1">
                                <span class="block text-xs font-semibold text-slate-500">Start: <strong class="text-slate-800">{{ $conference->abstract_submission_start_date ? $conference->abstract_submission_start_date->format('d M Y') : 'Open Now' }}</strong></span>
                                <span class="block text-xs font-semibold text-slate-500">End: <strong class="text-rose-600">{{ $conference->abstract_submission_end_date ? $conference->abstract_submission_end_date->format('d M Y') : 'Rolling' }}</strong></span>
                            </div>
                        </div>

                        <!-- Full Paper Submission -->
                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 flex flex-col justify-between text-left">
                            <span class="text-[10px] font-black uppercase tracking-wider text-emerald-600 mb-2">Full Paper Submission</span>
                            <div class="space-y-1">
                                <span class="block text-xs font-semibold text-slate-500">Start: <strong class="text-slate-800">{{ $conference->paper_submission_start_date ? $conference->paper_submission_start_date->format('d M Y') : 'Open Now' }}</strong></span>
                                <span class="block text-xs font-semibold text-slate-500">End: <strong class="text-rose-600">{{ $conference->paper_submission_end_date ? $conference->paper_submission_end_date->format('d M Y') : 'Rolling' }}</strong></span>
                            </div>
                        </div>

                        <!-- Registration Fee Payment -->
                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 flex flex-col justify-between text-left">
                            <span class="text-[10px] font-black uppercase tracking-wider text-purple-600 mb-2">Registration Fee Payment</span>
                            <div class="space-y-1">
                                <span class="block text-xs font-semibold text-slate-500">Start: <strong class="text-slate-800">{{ $conference->registration_start_date ? $conference->registration_start_date->format('d M Y') : 'Open' }}</strong></span>
                                <span class="block text-xs font-semibold text-slate-500">End: <strong class="text-rose-600">{{ $conference->registration_end_date ? $conference->registration_end_date->format('d M Y') : 'Deadline' }}</strong></span>
                            </div>
                        </div>

                        <!-- Conference Date -->
                        <div class="p-5 rounded-2xl bg-slate-900 text-white flex flex-col justify-between text-left">
                            <span class="text-[10px] font-black uppercase tracking-wider text-amber-400 mb-2">Conference Date</span>
                            <div class="space-y-1">
                                <span class="block text-xs font-bold text-slate-300">Start: <strong class="text-white">{{ $conference->start_date->format('d M Y') }}</strong></span>
                                <span class="block text-xs font-bold text-slate-300">End: <strong class="text-white">{{ $conference->end_date->format('d M Y') }}</strong></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Aim & Scope Section -->
                @if($conference->aim_scope)
                    <div class="bg-white rounded-[2.5rem] p-8 md:p-12 shadow-xl border border-slate-100">
                        <h2 class="text-2xl md:text-3xl font-serif font-black text-slate-900 mb-6 tracking-tight flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-blue-600"></span>
                            Aim & <span class="text-rose-600">Scope</span>
                        </h2>
                        <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed space-y-4 text-sm md:text-base">
                            {!! nl2br(e($conference->aim_scope)) !!}
                        </div>
                    </div>
                @endif

                <!-- 3. Guidelines Section -->
                @if($conference->guidelines)
                    <div class="bg-white rounded-[2.5rem] p-8 md:p-12 shadow-xl border border-slate-100">
                        <h2 class="text-2xl md:text-3xl font-serif font-black text-slate-900 mb-6 tracking-tight flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-indigo-600"></span>
                            Author <span class="text-rose-600">Guidelines</span>
                        </h2>
                        <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed space-y-4 text-sm md:text-base">
                            {!! nl2br(e($conference->guidelines)) !!}
                        </div>

                        @if($conference->paper_format_1_url || $conference->paper_format_2_url)
                            <div class="mt-8 pt-6 border-t border-slate-100 flex flex-wrap items-center gap-4">
                                <span class="text-xs font-bold text-slate-800">Download Paper Formats:</span>
                                @if($conference->paper_format_1_url)
                                    <a href="{{ $conference->paper_format_1_url }}" target="_blank" download class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold rounded-xl text-xs transition-all">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        Paper Format Template 1
                                    </a>
                                @endif
                                @if($conference->paper_format_2_url)
                                    <a href="{{ $conference->paper_format_2_url }}" target="_blank" download class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold rounded-xl text-xs transition-all">
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        Paper Format Template 2
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                @endif

                <!-- 4. Fee Structure Card -->
                <div class="bg-white rounded-[2.5rem] p-8 md:p-12 shadow-xl border border-slate-100">
                    <h2 class="text-2xl md:text-3xl font-serif font-black text-slate-900 mb-6 tracking-tight flex items-center gap-3">
                        <span class="w-3 h-3 rounded-full bg-emerald-600"></span>
                        Registration <span class="text-rose-600">Fee Details</span>
                    </h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Only Attendee</span>
                            <span class="block text-base font-black text-slate-900">{{ $conference->fee_attendee ?? '$100 / ₹2,500' }}</span>
                        </div>
                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Presentation</span>
                            <span class="block text-base font-black text-blue-600">{{ $conference->fee_presentation ?? '$200 / ₹4,500' }}</span>
                        </div>
                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Publication</span>
                            <span class="block text-base font-black text-emerald-600">{{ $conference->fee_publication ?? '$300 / ₹7,000' }}</span>
                        </div>
                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Extra Certificate</span>
                            <span class="block text-base font-black text-purple-600">{{ $conference->fee_extra_certificate ?? '$30 / ₹800' }}</span>
                        </div>
                    </div>

                    @if(!empty($conference->custom_fees) && count($conference->custom_fees) > 0)
                        <div class="mt-6 pt-6 border-t border-slate-100">
                            <span class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Additional Fee Categories:</span>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($conference->custom_fees as $fee)
                                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                                        <span class="text-xs font-bold text-slate-700">{{ $fee['name'] ?? 'Tier' }}</span>
                                        <span class="text-xs font-black text-blue-600">{{ $fee['amount'] ?? '-' }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- 5. Committee Section -->
                @if(!empty($conference->committee_members) && count($conference->committee_members) > 0)
                    <div class="bg-white rounded-[2.5rem] p-8 md:p-12 shadow-xl border border-slate-100">
                        <h2 class="text-2xl md:text-3xl font-serif font-black text-slate-900 mb-6 tracking-tight flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-indigo-600"></span>
                            Conference <span class="text-rose-600">Committee</span>
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($conference->committee_members as $member)
                                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold text-sm flex-shrink-0 shadow-sm">
                                        {{ substr($member['name'] ?? 'C', 0, 1) }}
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-bold text-slate-900">{{ $member['name'] ?? 'Committee Member' }}</h3>
                                        <p class="text-xs font-bold text-indigo-600 mt-0.5">{{ $member['role'] ?? 'Member' }}</p>
                                        @if(!empty($member['affiliation']))
                                            <p class="text-[11px] text-slate-500 font-medium mt-0.5">{{ $member['affiliation'] }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- 6. Publication Pathways & Author Proceedings Section -->
                @if($conference->publication_info || $conference->book_publication_title || $conference->journal_publication_title || $conference->submissions()->whereIn('status', ['accepted', 'published'])->exists())
                    <div class="bg-white rounded-[2.5rem] p-8 md:p-12 shadow-xl border border-slate-100 space-y-8">
                        <div>
                            <h2 class="text-2xl md:text-3xl font-serif font-black text-slate-900 tracking-tight flex items-center gap-3">
                                <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                                Publication <span class="text-rose-600">Pathways & Proceedings</span>
                            </h2>
                            <p class="text-xs text-slate-500 mt-1">Official publication details, book proceedings volume, and accepted paper proceedings.</p>
                        </div>

                        @if($conference->publication_info)
                            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80 prose prose-slate max-w-none text-slate-600 leading-relaxed text-sm md:text-base">
                                {!! nl2br(e($conference->publication_info)) !!}
                            </div>
                        @endif

                        <!-- Part 1: Book / Conference Proceedings -->
                        @if($conference->book_publication_title || $conference->book_publication_content)
                            <div class="p-6 md:p-8 rounded-3xl bg-amber-50/60 border border-amber-200/90 shadow-sm space-y-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center font-black shadow-md flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    </div>
                                    <div>
                                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-amber-700 bg-amber-200/60 px-2 py-0.5 rounded-md">Book Proceedings</span>
                                        <h3 class="text-lg md:text-xl font-serif font-black text-slate-900 mt-0.5">
                                            {{ $conference->book_publication_title ?: 'Conference Book & Proceedings Volume' }}
                                        </h3>
                                    </div>
                                </div>

                                @if($conference->book_publication_author)
                                    <div class="text-xs font-bold text-slate-700 flex items-center gap-2">
                                        <span class="text-amber-700">Volume Editors / Series:</span>
                                        <span>{{ $conference->book_publication_author }}</span>
                                    </div>
                                @endif

                                @if($conference->book_publication_abstract)
                                    <div class="text-xs text-slate-600 italic bg-white/80 p-4 rounded-xl border border-amber-200/50">
                                        {{ $conference->book_publication_abstract }}
                                    </div>
                                @endif

                                @if($conference->book_publication_content)
                                    <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed text-xs md:text-sm">
                                        {!! nl2br(e($conference->book_publication_content)) !!}
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Automatic List of All Authors' Accepted Papers / Proceedings -->
                        @php
                            $acceptedPapers = $conference->submissions()->whereIn('status', ['accepted', 'published'])->with('user')->get();
                        @endphp
                        @if($acceptedPapers->isNotEmpty())
                            <div class="space-y-4 pt-2">
                                <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    Accepted Conference Papers & Authors ({{ $acceptedPapers->count() }})
                                </h3>
                                <div class="grid grid-cols-1 gap-4">
                                    @foreach($acceptedPapers as $paper)
                                        @php
                                            $authorNames = is_array($paper->authors_data) && count($paper->authors_data) > 0
                                                ? implode(', ', array_column($paper->authors_data, 'name'))
                                                : ($paper->user->name ?? 'Author');
                                        @endphp
                                        <div class="p-5 rounded-2xl bg-slate-50 hover:bg-blue-50/40 border border-slate-200 transition-all space-y-2">
                                            <div class="flex items-start justify-between gap-4">
                                                <div>
                                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700 uppercase mb-1">
                                                        {{ $paper->article_type ?: 'Conference Paper' }}
                                                    </span>
                                                    <h4 class="text-sm font-bold text-slate-900 hover:text-blue-700 transition">
                                                        {{ $paper->title }}
                                                    </h4>
                                                    <p class="text-xs font-semibold text-indigo-700 mt-1">
                                                        ✍️ {{ $authorNames }}
                                                    </p>
                                                </div>
                                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800">
                                                    Accepted
                                                </span>
                                            </div>
                                            @if($paper->abstract)
                                                <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed pt-1">
                                                    {{ $paper->abstract }}
                                                </p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Part 2: Journal Publication -->
                        @if($conference->journal_publication_title || $conference->journal_publication_content)
                            <div class="p-6 md:p-8 rounded-3xl bg-indigo-50/60 border border-indigo-200/90 shadow-sm space-y-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-black shadow-md flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                    </div>
                                    <div>
                                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-indigo-700 bg-indigo-200/60 px-2 py-0.5 rounded-md">Journal Publication</span>
                                        <h3 class="text-lg md:text-xl font-serif font-black text-slate-900 mt-0.5">
                                            {{ $conference->journal_publication_title ?: 'Partner Journal Special Issue' }}
                                        </h3>
                                    </div>
                                </div>

                                @if($conference->journal_publication_content)
                                    <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed text-xs md:text-sm">
                                        {!! nl2br(e($conference->journal_publication_content)) !!}
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @endif

                <!-- 7. Venue Details & Online Meeting Link -->
                @if($conference->venue_details || $conference->meeting_link)
                    <div class="bg-white rounded-[2.5rem] p-8 md:p-12 shadow-xl border border-slate-100">
                        <h2 class="text-2xl md:text-3xl font-serif font-black text-slate-900 mb-6 tracking-tight flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-rose-600"></span>
                            Venue & <span class="text-blue-600">Meeting Details</span>
                        </h2>
                        @if($conference->venue_details)
                            <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed space-y-4 text-sm md:text-base mb-6">
                                {!! nl2br(e($conference->venue_details)) !!}
                            </div>
                        @endif

                        @if($conference->meeting_link)
                            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center justify-between flex-wrap gap-4">
                                <div>
                                    <span class="block text-xs font-bold text-emerald-900">Virtual / Online Conference Room:</span>
                                    <span class="text-[11px] text-emerald-700">Join the live conference presentations and keynote sessions</span>
                                </div>
                                <a href="{{ $conference->meeting_link }}" target="_blank" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md transition-all inline-flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    Join Meeting Room
                                </a>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- 8. Sample Certificate Preview -->
                @if($conference->sample_certificate_url)
                    <div class="bg-white rounded-[2.5rem] p-8 md:p-12 shadow-xl border border-slate-100">
                        <h2 class="text-2xl md:text-3xl font-serif font-black text-slate-900 mb-4 tracking-tight flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-emerald-600"></span>
                            Sample <span class="text-rose-600">Certificate</span>
                        </h2>
                        <p class="text-xs text-slate-500 mb-6">Official certificate issued to all accepted paper presenters and registered delegates.</p>
                        
                        <div class="rounded-2xl overflow-hidden border border-slate-200 shadow-sm bg-slate-50 p-3 max-w-md">
                            <img src="{{ $conference->sample_certificate_url }}" alt="Sample Certificate" class="w-full h-auto rounded-xl object-contain shadow-xs">
                            <div class="pt-3 text-right">
                                <a href="{{ $conference->sample_certificate_url }}" target="_blank" download class="text-xs font-bold text-blue-600 hover:underline inline-flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Download Sample Certificate
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- 9. Detailed Description -->
                <div class="bg-white rounded-[2.5rem] p-8 md:p-12 shadow-xl border border-slate-100">
                    <h2 class="text-2xl md:text-3xl font-serif font-black text-slate-900 mb-6 tracking-tight">
                        About This <span class="text-blue-600">Conference</span>
                    </h2>
                    <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed space-y-4 text-sm md:text-base">
                        {!! nl2br(e($conference->description)) !!}
                    </div>
                </div>

                <!-- 7. Call for Papers CTA Block -->
                <div class="bg-[#0f172a] rounded-[3rem] p-10 md:p-16 shadow-2xl text-center relative overflow-hidden text-white">
                    <div class="relative z-10 space-y-6">
                        <span class="inline-block px-3 py-1 bg-blue-500/20 text-blue-300 rounded-full text-[10px] font-black uppercase tracking-widest border border-blue-400/20">
                            Submissions Open
                        </span>
                        <h2 class="text-3xl md:text-4xl font-serif font-black">
                            Submit Your Paper to {{ $conference->title }}
                        </h2>
                        <p class="text-slate-400 text-sm max-w-xl mx-auto">
                            Submit your full research manuscript for peer review and global indexing in Scopus and leading scholarly partner journals.
                        </p>
                        <div class="flex flex-wrap items-center justify-center gap-4 pt-2">
                            <a href="{{ route('conferences.submit', $conference->slug) }}" 
                               class="px-8 py-4 bg-blue-600 hover:bg-blue-500 text-white font-black rounded-2xl text-xs uppercase tracking-widest shadow-xl shadow-blue-600/30 transition-all hover:scale-105">
                                Submit Paper Now
                            </a>
                            <button type="button" @click="showEnquiryModal = true" 
                               class="px-8 py-4 bg-white/10 hover:bg-white/20 text-white font-bold rounded-2xl text-xs uppercase tracking-widest border border-white/20 transition-all">
                                Send General Enquiry
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enquiry Modal (Name, Email, Mobile -> Call or Text & Email) -->
    <div x-show="showEnquiryModal" x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="bg-white rounded-3xl p-6 md:p-8 max-w-lg w-full shadow-2xl relative" @click.outside="showEnquiryModal = false">
            <button type="button" @click="showEnquiryModal = false" class="absolute top-5 right-5 p-2 rounded-full text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <div class="mb-6">
                <span class="text-[10px] font-black uppercase tracking-widest text-rose-600 bg-rose-50 px-2.5 py-1 rounded-full border border-rose-100">Conference Enquiry</span>
                <h3 class="text-xl font-serif font-black text-slate-900 mt-2">Send Conference Enquiry</h3>
                <p class="text-xs text-slate-500 mt-1">Leave your details and our conference coordinator will contact you via Call, Text, or Email.</p>
            </div>

            <form action="{{ route('conferences.enquiry', $conference->slug) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required placeholder="Your Full Name" 
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-500/10">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" name="email" required placeholder="your.email@university.edu" 
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-500/10">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Mobile / WhatsApp Number <span class="text-red-500">*</span></label>
                    <input type="tel" name="mobile" required placeholder="+1 (555) 000-0000" 
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-500/10">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Your Question / Message</label>
                    <textarea name="message" rows="3" placeholder="Inquire about registration fees, paper submissions, visa invitation letters, or schedules..."
                              class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-500/10"></textarea>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3.5 bg-rose-600 hover:bg-rose-700 text-white font-black rounded-xl text-xs uppercase tracking-widest shadow-lg shadow-rose-600/30 transition-all">
                        Submit Enquiry
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
