@extends('layouts.web')
@section('title', $preprint->title . ' | Preprints')

@section('content')
    <div class="bg-slate-50 min-h-screen py-12">
        <div class="container mx-auto px-4">
            <!-- Breadcrumb -->
            <nav class="flex items-center text-sm text-slate-500 mb-8" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-blue-900 transition">Home</a>
                <svg class="w-4 h-4 mx-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <a href="{{ route('preprints.index') }}" class="hover:text-blue-900 transition">Preprints</a>
                <svg class="w-4 h-4 mx-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-slate-800 font-medium truncate max-w-xs sm:max-w-md">{{ $preprint->title }}</span>
            </nav>

            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white p-8 sm:p-10 rounded-3xl shadow-sm border border-slate-200">
                        <div class="flex items-center space-x-2 mb-4">
                            <span class="px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-bold rounded uppercase">
                                Version {{ $preprint->version }}
                            </span>
                            <span class="text-xs text-slate-400 font-medium">
                                Posted {{ $preprint->created_at ? $preprint->created_at->format('M d, Y') : 'Recently' }}
                            </span>
                        </div>

                        <h1 class="text-3xl font-serif font-bold text-slate-900 mb-4">{{ $preprint->title }}</h1>

                        <p class="text-slate-600 text-sm mb-6 pb-6 border-b border-slate-100 italic">
                            By <span class="font-semibold text-slate-800">{{ $preprint->user->name ?? 'Academic Author' }}</span>
                        </p>

                        <div class="space-y-6">
                            <div>
                                <h2 class="text-lg font-bold text-slate-900 mb-3">Abstract</h2>
                                <div class="text-slate-700 text-base leading-relaxed whitespace-pre-line">
                                    {{ $preprint->abstract }}
                                </div>
                            </div>

                            @if($preprint->doi)
                                <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 flex items-center gap-2 text-sm text-slate-600">
                                    <span class="font-bold text-slate-800">DOI:</span>
                                    <a href="https://doi.org/{{ $preprint->doi }}" target="_blank" class="text-blue-600 hover:underline">
                                        {{ $preprint->doi }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200">
                        <h3 class="text-lg font-bold text-slate-900 mb-6 pb-3 border-b border-slate-100">Preprint Details</h3>

                        <dl class="space-y-4 text-sm">
                            <div>
                                <dt class="text-xs uppercase tracking-wider text-slate-400 font-semibold">Author</dt>
                                <dd class="text-slate-800 font-medium mt-1">{{ $preprint->user->name ?? 'N/A' }}</dd>
                            </div>

                            <div>
                                <dt class="text-xs uppercase tracking-wider text-slate-400 font-semibold">Status</dt>
                                <dd class="text-slate-800 font-bold capitalize mt-1">{{ $preprint->status }}</dd>
                            </div>

                            <div>
                                <dt class="text-xs uppercase tracking-wider text-slate-400 font-semibold">Version</dt>
                                <dd class="text-slate-800 font-medium mt-1">v{{ $preprint->version }}</dd>
                            </div>

                            <div>
                                <dt class="text-xs uppercase tracking-wider text-slate-400 font-semibold">Publication Date</dt>
                                <dd class="text-slate-800 font-medium mt-1">
                                    {{ $preprint->created_at ? $preprint->created_at->format('F d, Y') : 'N/A' }}
                                </dd>
                            </div>
                        </dl>

                        @if($preprint->file_path)
                            <div class="mt-8">
                                <a href="{{ asset('storage/' . $preprint->file_path) }}" target="_blank"
                                    class="w-full inline-flex items-center justify-center px-6 py-3.5 bg-blue-900 text-white font-bold rounded-xl hover:bg-blue-800 transition shadow-lg text-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Download Full PDF
                                </a>
                            </div>
                        @endif

                        <div class="mt-6 pt-6 border-t border-slate-100">
                            <a href="{{ route('preprints.index') }}"
                                class="inline-flex items-center text-sm font-semibold text-blue-900 hover:underline">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Back to All Preprints
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
