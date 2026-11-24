@extends('layouts.web')
@section('title', $event->title . ' | Sciforum')

@section('content')
    <div class="bg-slate-50 min-h-screen py-12">
        <div class="container mx-auto px-4">
            <!-- Breadcrumb -->
            <nav class="flex items-center text-sm text-slate-500 mb-8" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-blue-900 transition">Home</a>
                <svg class="w-4 h-4 mx-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <a href="{{ route('sciforum.index') }}" class="hover:text-blue-900 transition">Sciforum</a>
                <svg class="w-4 h-4 mx-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-slate-800 font-medium truncate max-w-xs sm:max-w-md">{{ $event->title }}</span>
            </nav>

            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Banner & Title Card -->
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="h-64 sm:h-80 bg-slate-200 relative overflow-hidden">
                            @if($event->banner_image)
                                <img src="{{ asset('storage/' . $event->banner_image) }}" alt="{{ $event->title }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-r from-blue-900 via-blue-800 to-indigo-900 text-white p-8 text-center">
                                    <span class="px-3 py-1 bg-white/20 backdrop-blur rounded-full text-xs font-bold uppercase tracking-widest mb-4">
                                        {{ $event->type }}
                                    </span>
                                    <h2 class="text-3xl sm:text-4xl font-serif font-bold max-w-2xl">{{ $event->title }}</h2>
                                </div>
                            @endif
                            <div class="absolute top-4 left-4">
                                <span class="px-3 py-1 bg-white/95 backdrop-blur rounded-full text-xs font-bold text-blue-900 uppercase tracking-widest shadow-sm">
                                    {{ $event->type }}
                                </span>
                            </div>
                        </div>

                        <div class="p-8">
                            <h1 class="text-3xl font-serif font-bold text-slate-900 mb-4">{{ $event->title }}</h1>

                            <div class="flex flex-wrap gap-6 text-sm text-slate-600 pb-6 border-b border-slate-100">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span>
                                        {{ $event->start_date ? \Carbon\Carbon::parse($event->start_date)->format('M d, Y') : 'TBA' }}
                                        @if($event->end_date && $event->end_date != $event->start_date)
                                            &ndash; {{ \Carbon\Carbon::parse($event->end_date)->format('M d, Y') }}
                                        @endif
                                    </span>
                                </div>

                                @if($event->location)
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z">
                                            </path>
                                        </svg>
                                        <span>{{ $event->location }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-8">
                                <h2 class="text-xl font-bold text-slate-900 mb-4">About this Event</h2>
                                <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed whitespace-pre-line">
                                    {{ $event->description }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200">
                        <h3 class="text-lg font-bold text-slate-900 mb-6 pb-3 border-b border-slate-100">Event Overview</h3>

                        <dl class="space-y-4 text-sm">
                            <div>
                                <dt class="text-xs uppercase tracking-wider text-slate-400 font-semibold">Event Type</dt>
                                <dd class="text-slate-800 font-bold capitalize mt-1">{{ $event->type }}</dd>
                            </div>

                            <div>
                                <dt class="text-xs uppercase tracking-wider text-slate-400 font-semibold">Start Date</dt>
                                <dd class="text-slate-800 font-medium mt-1">
                                    {{ $event->start_date ? \Carbon\Carbon::parse($event->start_date)->format('F d, Y') : 'TBA' }}
                                </dd>
                            </div>

                            @if($event->end_date)
                                <div>
                                    <dt class="text-xs uppercase tracking-wider text-slate-400 font-semibold">End Date</dt>
                                    <dd class="text-slate-800 font-medium mt-1">
                                        {{ \Carbon\Carbon::parse($event->end_date)->format('F d, Y') }}
                                    </dd>
                                </div>
                            @endif

                            @if($event->location)
                                <div>
                                    <dt class="text-xs uppercase tracking-wider text-slate-400 font-semibold">Location / Platform</dt>
                                    <dd class="text-slate-800 font-medium mt-1">{{ $event->location }}</dd>
                                </div>
                            @endif
                        </dl>

                        @if($event->submission_link)
                            <div class="mt-8">
                                <a href="{{ $event->submission_link }}" target="_blank" rel="noopener noreferrer"
                                    class="w-full inline-flex items-center justify-center px-6 py-3.5 bg-blue-900 text-white font-bold rounded-xl hover:bg-blue-800 transition shadow-lg text-sm">
                                    Submit Abstract / Paper
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                </a>
                            </div>
                        @endif

                        <div class="mt-6 pt-6 border-t border-slate-100">
                            <a href="{{ route('sciforum.index') }}"
                                class="inline-flex items-center text-sm font-semibold text-blue-900 hover:underline">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Back to All Events
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
