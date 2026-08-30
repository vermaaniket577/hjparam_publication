@extends('layouts.web')
@section('title', 'Live International Conferences | HJPARAM')

@section('content')
<div class="bg-slate-50 min-h-screen" x-data="conferenceApp()">
    <!-- Header Section with Cinematic Academic Auditorium Hero -->
    <div class="relative py-20 md:py-28 overflow-hidden" style="background-color: #071324 !important; color: #ffffff !important; position: relative;">
        <!-- Cinematic Background Video Container with Fallback Poster -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-color: #071324;">
            <video autoplay muted loop playsinline 
                   poster="{{ asset('images/conference-hero-bg.jpg') }}"
                   class="w-full h-full object-cover object-center select-none scale-105"
                   style="width: 100%; height: 100%; object-fit: cover; opacity: 0.82;">
                <source src="{{ asset('videos/conference-hero.mp4') }}" type="video/mp4">
                <source src="{{ asset('videos/conference-hero.webm.mp4') }}" type="video/mp4">
                <img src="{{ asset('images/conference-hero-bg.jpg') }}" alt="International Academic Conference Auditorium" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.82;">
            </video>
            
            <!-- Soft Gradient Scrim to keep text readable while keeping video vivid and clear -->
            <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(to right, rgba(7, 19, 36, 0.82) 0%, rgba(7, 19, 36, 0.45) 45%, rgba(7, 19, 36, 0.12) 100%);"></div>
            <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(to top, rgba(7, 19, 36, 0.85) 0%, transparent 40%, rgba(7, 19, 36, 0.25) 100%);"></div>
        </div>
        
        <div class="container mx-auto px-6 relative z-10 text-center md:text-left" style="position: relative; z-index: 10;">
            <div class="max-w-4xl">
                <!-- Red Prefix Header Badge -->
                <div style="background-color: #e11d48 !important; color: #ffffff !important; border: 1px solid rgba(253, 164, 175, 0.4); box-shadow: 0 4px 12px rgba(225, 29, 72, 0.35); display: inline-flex; align-items: center; gap: 8px; padding: 6px 16px; border-radius: 9999px; margin-bottom: 20px;">
                    <span style="width: 8px; height: 8px; border-radius: 9999px; background-color: #ffffff; display: inline-block;"></span>
                    <span style="font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.1em; color: #ffffff;">International Conferences & Symposiums</span>
                </div>

                <!-- Main Title with Clean, Bright Typography -->
                <h1 style="color: #ffffff !important; font-weight: 900; line-height: 1.15; letter-spacing: -0.02em; margin-bottom: 16px; text-shadow: 0 2px 10px rgba(0,0,0,0.6);"
                    class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-serif font-black">
                    Global <span style="color: #67e8f9 !important;">Knowledge</span> Exchanges
                </h1>
                
                <!-- Subtitle in Champagne Gold -->
                <p style="color: #fde047 !important; font-weight: 700; margin-bottom: 16px; text-shadow: 0 1px 4px rgba(0,0,0,0.5);"
                   class="text-lg md:text-2xl font-bold">
                    Hybrid & In-Person Academic Conferences Worldwide
                </p>

                <!-- Description -->
                <p style="color: #cbd5e1 !important; font-size: 16px; line-height: 1.7; max-width: 720px; margin-bottom: 32px;"
                   class="text-base md:text-lg">
                    Access the most prestigious international academic gatherings. Real-time connections, peer-reviewed excellence, Scopus & Web of Science indexing opportunities.
                </p>

                <!-- Partner & Indexing Badges Row -->
                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 36px; flex-wrap: wrap;" class="justify-center md:justify-start">
                    <!-- Academics World Badge -->
                    <div style="padding: 10px 20px; background: #ffffff; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.15); display: flex; align-items: center; justify-content: center; height: 48px; border: 1px solid #f1f5f9;">
                        <span style="font-size: 12px; font-weight: 900; color: #0f172a; line-height: 1;">academics <span style="color: #e11d48;">world</span></span>
                    </div>
                    <!-- Scopus Badge -->
                    <div style="padding: 10px 20px; background: #ffffff; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.15); display: flex; align-items: center; justify-content: center; height: 48px; border: 1px solid #f1f5f9;">
                        <span style="font-size: 14px; font-weight: 900; color: #f97316; line-height: 1;">Scopus</span>
                    </div>
                    <!-- Clarivate Web of Science Badge -->
                    <div style="padding: 10px 20px; background: #ffffff; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.15); display: flex; flex-direction: column; align-items: center; justify-content: center; height: 48px; border: 1px solid #f1f5f9;">
                        <span style="font-size: 8px; font-weight: 700; color: #94a3b8; text-transform: uppercase; line-height: 1;">Clarivate Analytics</span>
                        <span style="font-size: 10px; font-weight: 900; color: #1e293b; text-transform: uppercase; letter-spacing: -0.02em;">WEB OF SCIENCE™</span>
                    </div>
                </div>

                <!-- Action Buttons Row -->
                <div style="display: flex; flex-wrap: wrap; gap: 16px; padding-top: 8px;" class="justify-center md:justify-start">
                    <a href="#conference-feed" 
                       style="background: #2563eb !important; color: #ffffff !important; font-weight: 800; padding: 14px 28px; border-radius: 16px; text-decoration: none; text-transform: uppercase; font-size: 12px; letter-spacing: 0.05em; display: inline-flex; align-items: center; gap: 10px; box-shadow: 0 8px 20px rgba(37, 99, 235, 0.4);"
                       class="hover:opacity-95 hover:scale-105 transition-all">
                        <span>Explore Conferences</span>
                        <svg style="width: 16px; height: 16px; stroke: #ffffff;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                    <a href="{{ route('author.submit') }}" 
                       style="background: #e11d48 !important; color: #ffffff !important; font-weight: 800; padding: 14px 28px; border-radius: 16px; text-decoration: none; text-transform: uppercase; font-size: 12px; letter-spacing: 0.05em; display: inline-flex; align-items: center; gap: 10px; box-shadow: 0 8px 20px rgba(225, 29, 72, 0.4);"
                       class="hover:opacity-95 hover:scale-105 transition-all">
                        <span>Paper Submission</span>
                        <svg style="width: 16px; height: 16px; stroke: #ffffff;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </a>
                    <button onclick="window.print()" 
                       style="background: rgba(15, 23, 42, 0.9) !important; color: #ffffff !important; font-weight: 700; padding: 14px 28px; border-radius: 16px; border: 1px solid #334155; text-transform: uppercase; font-size: 12px; letter-spacing: 0.05em; display: inline-flex; align-items: center; gap: 10px; cursor: pointer; box-shadow: 0 8px 20px rgba(0,0,0,0.3);"
                       class="hover:opacity-95 hover:scale-105 transition-all">
                        <span>Download Schedule</span>
                        <svg style="width: 16px; height: 16px; stroke: #ffffff;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes cinematicSlowZoom {
            0% { transform: scale(1) translate(0, 0); }
            50% { transform: scale(1.05) translate(-6px, -3px); }
            100% { transform: scale(1) translate(0, 0); }
        }
        .animate-cinematic-zoom {
            animation: cinematicSlowZoom 26s ease-in-out infinite alternate;
        }
    </style>

    <!-- Filter & Search Section -->
    <div class="container mx-auto px-6 -mt-12 relative z-20">
        <div class="bg-white p-2 rounded-[3rem] shadow-2xl shadow-slate-200/50 border border-slate-100 mb-16 overflow-hidden">
            <div class="p-6 md:p-8 flex flex-col lg:flex-row gap-6">
                <div class="flex-grow">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-4">Advanced Search</label>
                    <div class="relative group">
                        <input type="text" x-model="search" @input.debounce.300ms="filterConferences()" 
                            placeholder="Search by Title, Topic, or Keywords..." 
                            class="w-full pl-14 pr-8 py-5 rounded-[2rem] bg-slate-50 border-none focus:ring-4 focus:ring-blue-500/10 transition-all font-bold text-slate-700 placeholder:text-slate-300">
                        <div class="absolute left-6 top-1/2 -translate-y-1/2 w-6 h-6 text-slate-400 group-focus-within:text-blue-500 transition-colors">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="min-w-[200px]">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-4">Category</label>
                        <div class="relative">
                            <select x-model="topic" @change="filterConferences()" class="w-full pl-6 pr-12 py-5 rounded-[2rem] bg-slate-50 border-none focus:ring-4 focus:ring-blue-500/10 font-black text-slate-700 appearance-none cursor-pointer text-sm">
                                <option value="">All Topics</option>
                                @foreach($topics as $t)
                                    <option value="{{ $t->slug }}">{{ $t->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="min-w-[200px]">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-4">Location</label>
                        <div class="relative">
                            <select x-model="country" @change="filterConferences()" class="w-full pl-6 pr-12 py-5 rounded-[2rem] bg-slate-50 border-none focus:ring-4 focus:ring-blue-500/10 font-black text-slate-700 appearance-none cursor-pointer text-sm">
                                <option value="">Anywhere</option>
                                @foreach($countries as $c)
                                    <option value="{{ $c->slug }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Main Display Content -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 pb-32">
            
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-3 space-y-8">
                <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 hover:shadow-xl transition-all duration-500">
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em] mb-8 pb-4 border-b">Core Sessions</h3>
                    <div class="space-y-2">
                        @foreach($topics->take(12) as $t)
                            <button @click="topic = '{{ $t->slug }}'; filterConferences()" 
                                :class="topic == '{{ $t->slug }}' ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-500 hover:bg-slate-50'"
                                class="w-full flex items-center justify-between px-5 py-4 rounded-2xl transition-all group">
                                <span class="text-xs font-bold leading-none tracking-tight">{{ $t->name }}</span>
                                <svg class="w-3 h-3 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-[2.5rem] p-10 shadow-2xl text-white relative overflow-hidden group">
                    <div class="absolute inset-0 bg-white/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                        </div>
                        <h3 class="text-xl font-black mb-4 tracking-tight">Post Your Event</h3>
                        <p class="text-blue-100 text-sm mb-8 leading-relaxed font-medium">Connect with a global audience of researchers and academics. List your conference on HJParam today.</p>
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-3 px-8 py-4 bg-white text-blue-700 rounded-2xl font-black uppercase tracking-widest text-[10px] hover:translate-x-1 transition-all">
                            Submit Now
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 5l7 7-7 7M3 12h18" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Conference Listing Feed -->
            <div class="lg:col-span-9" id="conference-feed">
                <!-- Loading Skeleton -->
                <div x-show="loading" class="space-y-8">
                    <template x-for="i in 3">
                        <div class="animate-pulse bg-white rounded-[3rem] p-10 flex gap-10">
                            <div class="w-32 h-32 bg-slate-100 rounded-[2rem]"></div>
                            <div class="flex-grow space-y-4">
                                <div class="h-4 bg-slate-100 rounded-full w-1/4"></div>
                                <div class="h-8 bg-slate-100 rounded-full w-3/4"></div>
                                <div class="h-4 bg-slate-100 rounded-full w-1/2"></div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Actual Content -->
                <div x-show="!loading" class="space-y-8" x-transition:enter="transition ease-out duration-300">
                    @forelse($conferences as $conf)
                        <div class="group bg-white rounded-[3rem] border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-slate-200/50 transition-all duration-700 p-8 md:p-10 flex flex-col lg:flex-row gap-10 relative overflow-hidden">
                            <!-- Premium Decor -->
                            <div class="absolute -top-10 -right-10 w-40 h-40 bg-blue-50 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-700 blur-2xl"></div>
                            
                            <!-- Date Sphere -->
                            <div class="flex-shrink-0 flex items-center justify-center">
                                <div class="w-36 h-36 bg-slate-50 rounded-full flex flex-col items-center justify-center border border-slate-100 group-hover:bg-[#0f172a] group-hover:border-[#0f172a] transition-all duration-700 shadow-xl shadow-slate-200/5">
                                    <span class="text-[11px] font-black text-slate-400 group-hover:text-blue-400 uppercase tracking-[0.2em] mb-1">{{ $conf->start_date->format('M') }}</span>
                                    <span class="text-4xl font-black text-slate-900 group-hover:text-white leading-none tracking-tighter">{{ $conf->start_date->format('d') }}</span>
                                    <span class="text-[11px] font-black text-slate-300 group-hover:text-slate-500 mt-1">{{ $conf->start_date->format('Y') }}</span>
                                </div>
                            </div>

                            <div class="flex-grow">
                                <div class="flex items-center gap-3 mb-6 flex-wrap">
                                    <span class="px-4 py-1.5 bg-emerald-50 text-emerald-700 text-[10px] font-black rounded-full border border-emerald-100 uppercase tracking-widest shadow-sm">{{ $conf->type }}</span>
                                    @if($conf->categories && $conf->categories->count() > 0)
                                        @foreach($conf->categories as $cat)
                                            <span class="px-4 py-1.5 bg-blue-50 text-blue-700 text-[10px] font-black rounded-full border border-blue-100 uppercase tracking-widest shadow-sm">{{ $cat->name }}</span>
                                        @endforeach
                                    @elseif($conf->category)
                                        <span class="px-4 py-1.5 bg-blue-50 text-blue-700 text-[10px] font-black rounded-full border border-blue-100 uppercase tracking-widest shadow-sm">{{ $conf->category->name }}</span>
                                    @endif
                                    <span class="text-slate-200 px-1">•</span>
                                    <span class="text-slate-500 text-[11px] font-black uppercase tracking-[0.15em] flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke-width="2.5"/></svg>
                                        {{ $conf->city }}, {{ $conf->country->name }}
                                    </span>
                                </div>
                                <h2 class="text-2xl md:text-3xl font-serif font-black text-slate-900 mb-6 group-hover:text-blue-700 transition-colors leading-[1.15] tracking-tight">
                                    <a href="{{ route('conferences.show', $conf->slug) }}">{{ $conf->title }}</a>
                                </h2>
                                <p class="text-slate-500 text-base mb-8 line-clamp-2 font-medium leading-[1.6]">
                                    {{ $conf->description }}
                                </p>
                                <div class="pt-6 border-t border-slate-50 flex flex-wrap items-center gap-8">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-blue-50 group-hover:text-blue-500 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" stroke-width="2"/></svg>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest leading-none mb-1">Organizer</span>
                                            <span class="text-xs font-black text-slate-700">{{ $conf->organizer_name }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-emerald-50 group-hover:text-emerald-500 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2"/></svg>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest leading-none mb-1">Timeframe</span>
                                            <span class="text-xs font-black text-slate-700">Until {{ $conf->end_date->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-auto flex items-center gap-4">
                                        <div class="hidden md:flex -space-x-3">
                                            <template x-for="i in 3">
                                                <div class="w-10 h-10 rounded-full border-4 border-white bg-slate-100 flex items-center justify-center text-slate-400">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                                                </div>
                                            </template>
                                            <div class="w-10 h-10 rounded-full border-4 border-white bg-blue-600 flex items-center justify-center text-[10px] font-black text-white">+{{ rand(50,200) }}</div>
                                        </div>
                                        <a href="{{ route('conferences.show', $conf->slug) }}" class="inline-flex items-center justify-center h-14 w-14 bg-[#0f172a] hover:bg-blue-600 text-white rounded-2xl transition-all duration-500 shadow-xl shadow-slate-900/10 hover:shadow-blue-600/20 group/btn">
                                            <svg class="w-6 h-6 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7l5 5-5 5M6 12h12" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white rounded-[4rem] p-32 text-center border-2 border-dashed border-slate-100">
                            <div class="w-32 h-32 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-10 text-slate-200">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <h3 class="text-3xl font-black text-slate-800 mb-6">No Records Match Your Sync</h3>
                            <p class="text-slate-500 max-w-sm mx-auto mb-10 text-lg font-medium">The criteria you've selected hasn't populated any local nodes yet.</p>
                            <a href="{{ route('conferences.index') }}" class="inline-flex px-10 py-5 bg-blue-600 text-white rounded-[2rem] font-black uppercase tracking-widest text-xs hover:bg-slate-900 transition-colors shadow-2xl shadow-blue-600/20">Reset Core Filters</a>
                        </div>
                    @endforelse

                    <div class="pt-10">
                        {{ $conferences->onEachSide(1)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Academic Partners & CPD Accreditation Showcase Section -->
    <div class="py-16 bg-slate-50 border-t border-slate-100 relative z-20">
        <div class="container mx-auto px-6 max-w-4xl text-center">
            <!-- Academic Partners Header -->
            <div class="mb-10">
                <h2 class="text-3xl md:text-4xl font-serif font-black text-slate-900 mb-6 tracking-tight">
                    Academic <span class="text-rose-600">Partners</span>
                </h2>
                <div class="inline-flex flex-col items-center justify-center">
                    <div class="flex items-center gap-3 px-6 py-3 rounded-2xl bg-white border border-slate-200 shadow-md">
                        <div style="background: #1e3a8a; color: #ffffff; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 900; box-shadow: 0 2px 6px rgba(30, 58, 138, 0.3);">
                            IRAJ
                        </div>
                        <span class="font-extrabold text-slate-900 text-base tracking-tight">IRAJ International</span>
                    </div>
                </div>
            </div>

            <!-- CPD Accreditation Showcase Card Container -->
            <div class="bg-white rounded-[2.5rem] p-8 md:p-12 shadow-xl border border-slate-100 text-center">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center mb-8">
                    <!-- Left: CPD Accreditation Certificate Card -->
                    <div class="p-6 rounded-2xl border border-slate-200 shadow-sm text-left relative overflow-hidden flex items-center gap-4" style="background: #f8fafc;">
                        <div style="background: linear-gradient(135deg, #0284c7 0%, #0d9488 100%) !important; color: #ffffff !important; width: 64px; height: 64px; border-radius: 16px; display: flex; flex-direction: column; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 6px 16px rgba(2, 132, 199, 0.35);">
                            <span style="color: #ffffff !important; font-size: 18px; font-weight: 900; line-height: 1;">13</span>
                            <span style="color: #cffafe !important; font-size: 8px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; margin-top: 3px;">CPD Hrs</span>
                        </div>
                        <div>
                            <span class="block text-xs font-black uppercase tracking-tight" style="color: #0284c7;">CPD ACCREDITATION</span>
                            <span class="block text-base font-serif font-black text-slate-900 mt-0.5">Certificate of Completion</span>
                            <span class="block text-[11px] text-slate-500 font-medium mt-1">Standard Accredited Learning Activity</span>
                        </div>
                    </div>

                    <!-- Right: The CPD Standards Office -->
                    <div class="p-6 rounded-2xl border border-slate-200 shadow-sm text-center flex items-center justify-between gap-4" style="background: #f8fafc;">
                        <div class="text-left">
                            <span class="block text-base font-black text-slate-900 tracking-tight leading-snug">The CPD Standards Office</span>
                            <span class="block text-[11px] font-extrabold uppercase tracking-wider mt-1" style="color: #0d9488;">CPD PROVIDER: 22862</span>
                            <span class="block text-[10px] text-slate-400 font-semibold mt-0.5">2025-2027</span>
                            <span class="block text-[11px] font-bold mt-0.5" style="color: #16a34a;">www.cpdstandards.com</span>
                        </div>
                        <div style="background: linear-gradient(135deg, #0284c7 0%, #0d9488 50%, #15803d 100%) !important; color: #ffffff !important; width: 68px; height: 68px; border-radius: 50%; display: flex; flex-direction: column; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 6px 16px rgba(13, 148, 136, 0.35); border: 2.5px solid #ffffff;">
                            <span style="color: #cffafe !important; font-size: 7px; font-weight: 800; text-transform: uppercase; line-height: 1;">Accredited</span>
                            <span style="color: #ffffff !important; font-size: 14px; font-weight: 900; line-height: 1.1;">cpd</span>
                            <span style="color: #bbf7d0 !important; font-size: 7px; font-weight: 800; text-transform: uppercase; line-height: 1;">Activity</span>
                        </div>
                    </div>
                </div>

                <!-- Bottom: CPD Credit Hours Bar -->
                <div class="rounded-2xl p-3 flex flex-col sm:flex-row items-center justify-between gap-4 border border-slate-200" style="background: #f1f5f9;">
                    <div class="flex items-center gap-2.5 pl-4">
                        <span class="text-sm font-black text-slate-900 uppercase tracking-wider">13 CPD CREDIT HOURS</span>
                        <div style="background: #0284c7; color: #ffffff; width: 20px; height: 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 900;">
                            i
                        </div>
                    </div>
                    <span style="background: linear-gradient(135deg, #0284c7 0%, #0d9488 50%, #16a34a 100%) !important; color: #ffffff !important; padding: 12px 28px; border-radius: 14px; font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.08em; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 6px 18px rgba(13, 148, 136, 0.4);">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Your Certificate On Completion
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Glimpses of Our Past Conferences & Our Associates Section -->
    <div class="py-20 bg-white border-t border-slate-100">
        <div class="container mx-auto px-6 max-w-7xl">
            <!-- 1. Glimpses of Our Past Conferences (Gallery) -->
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-serif font-black text-slate-900 mb-10 tracking-tight">
                    Glimpses of <span class="text-rose-600">Our Past Conferences</span>
                </h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-5">
                    <!-- Card 1 -->
                    <div class="group rounded-3xl shadow-lg border border-slate-100 overflow-hidden" 
                         style="position: relative; width: 100%; min-height: 190px; height: 190px; background-color: #0f172a; border-radius: 1.25rem; overflow: hidden;">
                        <img src="{{ asset('images/past-conf-1.jpg') }}" alt="Past Conference Delegates" 
                             style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover;" 
                             class="group-hover:scale-110 transition-transform duration-700">
                        <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(15,23,42,0.85) 0%, transparent 60%); display: flex; align-items: flex-end; padding: 16px; text-align: left;">
                            <span style="color: #ffffff !important; font-size: 12px; font-weight: 800; letter-spacing: 0.02em;">International Delegation</span>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="group rounded-3xl shadow-lg border border-slate-100 overflow-hidden" 
                         style="position: relative; width: 100%; min-height: 190px; height: 190px; background-color: #0f172a; border-radius: 1.25rem; overflow: hidden;">
                        <img src="{{ asset('images/past-conf-2.jpg') }}" alt="Keynote Session" 
                             style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover;" 
                             class="group-hover:scale-110 transition-transform duration-700">
                        <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(15,23,42,0.85) 0%, transparent 60%); display: flex; align-items: flex-end; padding: 16px; text-align: left;">
                            <span style="color: #ffffff !important; font-size: 12px; font-weight: 800; letter-spacing: 0.02em;">Keynote Lecture</span>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="group rounded-3xl shadow-lg border border-slate-100 overflow-hidden" 
                         style="position: relative; width: 100%; min-height: 190px; height: 190px; background-color: #0f172a; border-radius: 1.25rem; overflow: hidden;">
                        <img src="{{ asset('images/past-conf-3.jpg') }}" alt="Award Ceremony" 
                             style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover;" 
                             class="group-hover:scale-110 transition-transform duration-700">
                        <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(15,23,42,0.85) 0%, transparent 60%); display: flex; align-items: flex-end; padding: 16px; text-align: left;">
                            <span style="color: #ffffff !important; font-size: 12px; font-weight: 800; letter-spacing: 0.02em;">Award Felicitation</span>
                        </div>
                    </div>

                    <!-- Card 4 -->
                    <div class="group rounded-3xl shadow-lg border border-slate-100 overflow-hidden" 
                         style="position: relative; width: 100%; min-height: 190px; height: 190px; background-color: #0f172a; border-radius: 1.25rem; overflow: hidden;">
                        <img src="{{ asset('images/past-conf-4.jpg') }}" alt="Plenary Hall" 
                             style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover;" 
                             class="group-hover:scale-110 transition-transform duration-700">
                        <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(15,23,42,0.85) 0%, transparent 60%); display: flex; align-items: flex-end; padding: 16px; text-align: left;">
                            <span style="color: #ffffff !important; font-size: 12px; font-weight: 800; letter-spacing: 0.02em;">Plenary Convention</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Our Associates (Publishing Partners & Indexing Logos) -->
            <div class="text-center pt-8">
                <h2 class="text-2xl md:text-3xl font-serif font-black text-[#0f172a] mb-8 tracking-tight">
                    Our Associates
                </h2>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4 items-center">
                    <!-- Scopus -->
                    <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition-all flex items-center justify-center h-20 group">
                        <span class="text-base font-black tracking-tight text-[#f97316] group-hover:scale-105 transition-transform">Scopus</span>
                    </div>
                    <!-- Clarivate Web of Science -->
                    <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition-all flex flex-col items-center justify-center h-20 group">
                        <span class="text-[10px] font-bold text-slate-400 uppercase leading-none">Clarivate</span>
                        <span class="text-sm font-black text-slate-800 group-hover:scale-105 transition-transform">Web of Science</span>
                    </div>
                    <!-- Elsevier -->
                    <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition-all flex items-center justify-center h-20 group">
                        <span class="text-base font-black text-[#FF6C00] tracking-wider uppercase group-hover:scale-105 transition-transform">ELSEVIER</span>
                    </div>
                    <!-- Springer -->
                    <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition-all flex items-center justify-center h-20 group">
                        <span class="text-base font-black text-blue-900 tracking-tight group-hover:scale-105 transition-transform">Springer</span>
                    </div>
                    <!-- Inderscience -->
                    <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition-all flex flex-col items-center justify-center h-20 group">
                        <span class="text-xs font-black text-blue-700 leading-none">INDERSCIENCE</span>
                        <span class="text-[8px] font-bold text-slate-500 uppercase tracking-widest mt-0.5">Publishers</span>
                    </div>
                    <!-- ScienceDirect -->
                    <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition-all flex items-center justify-center h-20 group">
                        <span class="text-sm font-black text-emerald-700 tracking-tight group-hover:scale-105 transition-transform">ScienceDirect</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Subscription CTA -->
    <div class="bg-[#0f172a] py-32 relative overflow-hidden">
        <div class="absolute inset-0 opacity-5 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        <div class="container mx-auto px-6 relative z-10">
            <div class="max-w-4xl mx-auto bg-gradient-to-r from-blue-600/10 to-transparent p-12 md:p-20 rounded-[4rem] border border-white/5 backdrop-blur-3xl text-center">
                <h2 class="text-3xl md:text-5xl font-serif font-black text-white mb-8 leading-tight">Sync With Global <span class="text-blue-400">Intelligence</span></h2>
                <p class="text-slate-400 text-lg md:text-xl font-medium mb-12 max-w-2xl mx-auto">Get bespoke live alerts for conferences in your field. High-impact updates delivered straight to your node.</p>
                <div class="flex flex-col md:flex-row gap-4 justify-center">
                    <input type="email" placeholder="Enter your academic email..." class="px-8 py-5 rounded-2xl bg-white/5 border border-white/10 text-white placeholder:text-slate-600 focus:ring-4 focus:ring-blue-500/20 w-full md:w-96 font-bold">
                    <button class="px-12 py-5 bg-blue-600 hover:bg-blue-500 text-white font-black rounded-2xl transition-all shadow-2xl shadow-blue-600/20 uppercase tracking-widest text-xs">Authorize Feeds</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function conferenceApp() {
    return {
        search: '{{ request('search') }}',
        topic: '{{ request('topic') }}',
        country: '{{ request('country') }}',
        loading: false,
        
        filterConferences() {
            this.loading = true;
            
            // Build URL with parameters
            let url = new URL('{{ route('conferences.index') }}');
            if (this.search) url.searchParams.append('search', this.search);
            if (this.topic) url.searchParams.append('topic', this.topic);
            if (this.country) url.searchParams.append('country', this.country);

            // Fetch results via Turbo/AJAX
            // For simplicity in this demo, we can just reload the page or use fetch
            // But to make it truly "Live" we'd fetch and replace the #conference-feed
            
            // For now, let's just use window.location to simulate the filter if not using full AJAX
            window.location.href = url.toString();
        }
    }
}
</script>

<style>
/* Custom Scrollbar for premium feel */
::-webkit-scrollbar { width: 8px; }
::-webkit-scrollbar-track { background: #f1f5f9; }
::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

.font-serif { font-family: 'Outfit', sans-serif; } /* Matching the premium look */
</style>
@endsection
