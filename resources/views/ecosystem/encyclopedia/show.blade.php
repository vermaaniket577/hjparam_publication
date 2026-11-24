@extends('layouts.web')
@section('title', $entry->title . ' | Encyclopedia')

@section('content')
    <div class="bg-slate-50 min-h-screen py-12">
        <div class="container mx-auto px-4">
            <!-- Breadcrumb -->
            <nav class="flex items-center text-sm text-slate-500 mb-8" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-blue-900 transition">Home</a>
                <svg class="w-4 h-4 mx-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <a href="{{ route('encyclopedia.index') }}" class="hover:text-blue-900 transition">Encyclopedia</a>
                <svg class="w-4 h-4 mx-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-slate-800 font-medium truncate max-w-xs sm:max-w-md">{{ $entry->title }}</span>
            </nav>

            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white p-8 sm:p-10 rounded-3xl shadow-sm border border-slate-200">
                        <span class="text-xs font-bold text-blue-600 uppercase tracking-widest mb-3 block">
                            {{ $entry->category }}
                        </span>

                        <h1 class="text-3xl sm:text-4xl font-serif font-bold text-slate-900 mb-4">{{ $entry->title }}</h1>

                        <div class="flex items-center gap-4 text-xs text-slate-400 mb-8 pb-6 border-b border-slate-100">
                            <span>Entry curated by <strong class="text-slate-700">{{ $entry->user->name ?? 'Scholar' }}</strong></span>
                            <span>&bull;</span>
                            <span>Version {{ $entry->version ?? '1.0' }}</span>
                        </div>

                        <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed text-base whitespace-pre-line">
                            {{ $entry->content }}
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200">
                        <h3 class="text-lg font-bold text-slate-900 mb-6 pb-3 border-b border-slate-100">Entry Information</h3>

                        <dl class="space-y-4 text-sm">
                            <div>
                                <dt class="text-xs uppercase tracking-wider text-slate-400 font-semibold">Category</dt>
                                <dd class="text-slate-800 font-medium mt-1">{{ $entry->category }}</dd>
                            </div>

                            <div>
                                <dt class="text-xs uppercase tracking-wider text-slate-400 font-semibold">Status</dt>
                                <dd class="text-slate-800 font-bold capitalize mt-1">{{ $entry->status }}</dd>
                            </div>

                            <div>
                                <dt class="text-xs uppercase tracking-wider text-slate-400 font-semibold">Contributor</dt>
                                <dd class="text-slate-800 font-medium mt-1">{{ $entry->user->name ?? 'Academic Contributor' }}</dd>
                            </div>
                        </dl>

                        <div class="mt-8 pt-6 border-t border-slate-100">
                            <a href="{{ route('encyclopedia.index') }}"
                                class="inline-flex items-center text-sm font-semibold text-blue-900 hover:underline">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Back to All Entries
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
