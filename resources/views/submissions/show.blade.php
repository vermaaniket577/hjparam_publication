@extends('layouts.web')

@section('title', 'Manuscript Status #' . $submission->id . ' - ' . Str::limit($submission->title, 40))

@section('content')
<div class="bg-slate-50 min-h-screen py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto space-y-8">

        <!-- Breadcrumb & Back Navigation -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <nav class="flex items-center text-xs font-semibold text-slate-500 gap-2">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors">Dashboard</a>
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <a href="{{ route('submission.index') }}" class="hover:text-blue-600 transition-colors">My Submissions</a>
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-slate-800 font-bold">Manuscript #{{ $submission->id }}</span>
            </nav>

            <div class="flex items-center gap-3">
                @if($submission->status === 'accepted')
                    <a href="{{ route('payments.create', ['submission_id' => $submission->id, 'journal_id' => $submission->journal_id]) }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-black uppercase tracking-wider transition-all shadow-md shadow-emerald-600/30 animate-pulse">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span>Pay Fees Now</span>
                    </a>
                @endif
                <a href="{{ route('submission.download', $submission) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 hover:text-blue-600 border border-slate-200 rounded-xl text-xs font-bold uppercase tracking-wider transition-all shadow-xs">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Download Manuscript
                </a>
                <a href="{{ route('submission.copyright', $submission) }}" target="_blank"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white hover:bg-emerald-50 text-emerald-700 hover:text-emerald-800 border border-emerald-300 rounded-xl text-xs font-bold uppercase tracking-wider transition-all shadow-xs"
                    title="Download / Print Copyright Agreement Form">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Copyright Form</span>
                </a>
                <a href="{{ route('submission.index') }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-200/80 hover:bg-slate-300 text-slate-700 rounded-xl text-xs font-bold uppercase tracking-wider transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    All Submissions
                </a>
            </div>
        </div>

        <!-- Manuscript Overview Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80 relative overflow-hidden">
            <div class="absolute top-0 right-0 transform translate-x-12 -translate-y-8 pointer-events-none opacity-5">
                <svg class="w-72 h-72 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z" />
                </svg>
            </div>

            <div class="flex flex-wrap items-center gap-3 mb-4">
                <span class="px-3 py-1 bg-slate-900 text-white text-[11px] font-black uppercase tracking-widest rounded-lg">
                    Tracking ID: #{{ $submission->id }}
                </span>
                <a href="{{ route('journals.show', $submission->journal->slug) }}"
                    class="px-3 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 text-[11px] font-bold uppercase tracking-wider rounded-lg transition-colors border border-blue-200/60 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    {{ $submission->journal->title }}
                </a>

                @php
                    $statusStyles = [
                        'submitted' => [
                            'bg' => 'bg-blue-100 text-blue-800 border-blue-200',
                            'dot' => 'bg-blue-600 animate-ping',
                            'label' => 'Manuscript Submitted',
                        ],
                        'under_review' => [
                            'bg' => 'bg-amber-100 text-amber-800 border-amber-200',
                            'dot' => 'bg-amber-600 animate-ping',
                            'label' => 'Under Peer Review',
                        ],
                        'accepted' => [
                            'bg' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                            'dot' => 'bg-emerald-600',
                            'label' => 'Accepted for Publication',
                        ],
                        'rejected' => [
                            'bg' => 'bg-rose-100 text-rose-800 border-rose-200',
                            'dot' => 'bg-rose-600',
                            'label' => 'Declined / Rejected',
                        ],
                        'published' => [
                            'bg' => 'bg-purple-100 text-purple-800 border-purple-200',
                            'dot' => 'bg-purple-600',
                            'label' => 'Published Online',
                        ],
                    ];
                    $currentStatus = $statusStyles[$submission->status] ?? [
                        'bg' => 'bg-slate-100 text-slate-800 border-slate-200',
                        'dot' => 'bg-slate-600',
                        'label' => ucfirst(str_replace('_', ' ', $submission->status)),
                    ];
                @endphp

                <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wider border {{ $currentStatus['bg'] }}">
                    <span class="relative flex h-2 w-2">
                        <span class="{{ $currentStatus['dot'] }} absolute inline-flex h-full w-full rounded-full opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-current"></span>
                    </span>
                    {{ $currentStatus['label'] }}
                </span>
            </div>

            <h1 class="text-2xl sm:text-3xl font-serif font-bold text-slate-900 tracking-tight leading-snug mb-4">
                {{ $submission->title }}
            </h1>

            <div class="flex flex-wrap items-center gap-y-2 gap-x-6 text-xs text-slate-500">
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Submitted on <strong class="text-slate-700">{{ $submission->created_at->format('M d, Y') }}</strong> ({{ $submission->created_at->diffForHumans() }})</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Last Updated: <strong class="text-slate-700">{{ $submission->updated_at->format('M d, Y - h:i A') }}</strong></span>
                </div>
                @if($submission->file_size)
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>File: <strong class="text-slate-700">{{ $submission->formatted_size }} ({{ strtoupper($submission->extension ?? 'DOCX') }})</strong></span>
                    </div>
                @endif
            </div>

            @if($submission->status === 'accepted')
                <!-- Publication Fee & Acceptance Pay Now CTA -->
                <div class="mt-6 p-5 sm:p-6 rounded-2xl bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-700 text-white shadow-lg shadow-emerald-600/20 flex flex-col md:flex-row md:items-center justify-between gap-5 border border-emerald-500/30">
                    <div class="space-y-1.5">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 bg-white/20 text-white text-[10px] font-black uppercase tracking-widest rounded-full">Publication Fee Required</span>
                            <span class="text-xs font-bold text-emerald-100">Step 05 — Production & APC</span>
                        </div>
                        <h3 class="text-lg font-bold font-serif">Your manuscript has been Accepted for Publication!</h3>
                        <p class="text-xs text-emerald-50/90 leading-relaxed max-w-2xl">
                            Congratulations! The editorial peer review is approved. Please complete the Article Processing Charge (APC) payment to initiate final digital proofing, DOI minting, and online indexation.
                        </p>
                    </div>
                    <a href="{{ route('payments.create', ['submission_id' => $submission->id, 'journal_id' => $submission->journal_id]) }}"
                        class="shrink-0 px-6 py-3.5 bg-white hover:bg-emerald-50 text-emerald-800 font-black text-xs uppercase tracking-wider rounded-xl transition-all shadow-md flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span>Pay Fees Now</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            @endif
        </div>

        <!-- Publication Lifecycle Stepper (Complete Flow of Process) -->
        <div class="bg-white rounded-3xl p-6 sm:p-10 shadow-sm border border-slate-200/80">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 pb-8 mb-8 border-b border-slate-100">
                <div>
                    <span class="inline-block px-3 py-1 bg-indigo-50 text-indigo-700 text-[10px] font-black uppercase tracking-widest rounded-md mb-1.5">Editorial Workflow</span>
                    <h2 class="text-xl font-bold font-serif text-slate-900">Complete Publication Lifecycle</h2>
                    <p class="text-xs text-slate-500">Live progress tracking from initial submission to online indexation.</p>
                </div>
                <div class="text-right">
                    <span class="text-xs font-semibold text-slate-400">Current Phase:</span>
                    <span class="block text-sm font-black text-slate-800">{{ $currentStatus['label'] }}</span>
                </div>
            </div>

            @php
                // Stage determination
                // 1: Submitted
                // 2: Editorial Screening
                // 3: Peer Review
                // 4: Editorial Decision
                // 5: Production & APC
                // 6: Published

                $statusCode = $submission->status;
                $activeStep = 1;

                if ($statusCode === 'submitted') {
                    $activeStep = 2; // Under preliminary editorial check
                } elseif ($statusCode === 'under_review') {
                    $activeStep = 3;
                } elseif ($statusCode === 'accepted') {
                    $activeStep = 5; // Ready for production & payment
                } elseif ($statusCode === 'published') {
                    $activeStep = 6;
                } elseif ($statusCode === 'rejected') {
                    $activeStep = 4;
                }

                $steps = [
                    [
                        'number' => 1,
                        'title' => 'Manuscript Submission',
                        'subtitle' => 'File & metadata received',
                        'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                        'state' => 'completed',
                        'date' => $submission->created_at->format('M d, Y'),
                    ],
                    [
                        'number' => 2,
                        'title' => 'Editorial Screening',
                        'subtitle' => 'Scope, plagiarism & technical check',
                        'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
                        'state' => ($activeStep > 2) ? 'completed' : (($activeStep == 2) ? 'active' : 'pending'),
                        'date' => ($activeStep >= 2) ? 'In Progress' : 'Pending',
                    ],
                    [
                        'number' => 3,
                        'title' => 'Peer Review Assessment',
                        'subtitle' => 'Blind evaluation by reviewers',
                        'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
                        'state' => ($activeStep > 3) ? 'completed' : (($activeStep == 3) ? 'active' : 'pending'),
                        'date' => ($activeStep >= 3) ? ($activeStep == 3 ? 'In Progress' : 'Completed') : 'Pending',
                    ],
                    [
                        'number' => 4,
                        'title' => 'Editorial Decision',
                        'subtitle' => $statusCode === 'rejected' ? 'Declined by Editorial Board' : 'Accepted for Publication',
                        'icon' => 'M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3',
                        'state' => ($statusCode === 'rejected') ? 'rejected' : (($activeStep > 4) ? 'completed' : (($activeStep == 4) ? 'active' : 'pending')),
                        'date' => ($activeStep >= 4) ? 'Evaluated' : 'Pending',
                    ],
                    [
                        'number' => 5,
                        'title' => 'Production & APC',
                        'subtitle' => 'Typesetting, proofs & publication fee',
                        'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
                        'state' => ($statusCode === 'rejected') ? 'disabled' : (($activeStep > 5) ? 'completed' : (($activeStep == 5) ? 'active' : 'pending')),
                        'date' => ($activeStep >= 5 && $statusCode !== 'rejected') ? ($activeStep == 5 ? 'Action Required' : 'Completed') : 'Pending',
                    ],
                    [
                        'number' => 6,
                        'title' => 'Published & Indexed',
                        'subtitle' => 'Assigned Volume, Issue & DOI',
                        'icon' => 'M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9',
                        'state' => ($activeStep == 6) ? 'completed' : (($statusCode === 'rejected') ? 'disabled' : 'pending'),
                        'date' => ($activeStep == 6) ? 'Online Live' : 'Pending',
                    ],
                ];
            @endphp

            <!-- Stepper Container (Desktop & Tablet Horizontal Grid) -->
            <div class="relative grid grid-cols-1 md:grid-cols-6 gap-6">
                <!-- Connector Line behind steps (Desktop only) -->
                <div class="hidden md:block absolute top-7 left-8 right-8 h-1 bg-slate-100 z-0">
                    @php
                        $progressPercent = min(100, max(0, (($activeStep - 1) / 5) * 100));
                        if ($statusCode === 'rejected') $progressPercent = 60;
                    @endphp
                    <div class="h-full bg-blue-600 transition-all duration-700" style="width: {{ $progressPercent }}%;"></div>
                </div>

                @foreach($steps as $index => $step)
                    <div class="relative z-10 flex md:flex-col items-start md:items-center text-left md:text-center group">
                        
                        <!-- Step Icon Node -->
                        <div class="shrink-0 mr-4 md:mr-0 md:mb-3">
                            @if($step['state'] === 'completed')
                                <div class="w-14 h-14 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shadow-lg shadow-emerald-600/20 ring-4 ring-emerald-50">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            @elseif($step['state'] === 'active')
                                <div class="w-14 h-14 rounded-2xl bg-blue-600 text-white flex items-center justify-center shadow-lg shadow-blue-600/30 ring-4 ring-blue-100 animate-pulse">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $step['icon'] }}" />
                                    </svg>
                                </div>
                            @elseif($step['state'] === 'rejected')
                                <div class="w-14 h-14 rounded-2xl bg-rose-600 text-white flex items-center justify-center shadow-lg shadow-rose-600/30 ring-4 ring-rose-100">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>
                            @elseif($step['state'] === 'disabled')
                                <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-300 flex items-center justify-center border border-slate-200/60">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $step['icon'] }}" />
                                    </svg>
                                </div>
                            @else
                                <div class="w-14 h-14 rounded-2xl bg-white text-slate-400 flex items-center justify-center border-2 border-slate-200 shadow-xs">
                                    <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $step['icon'] }}" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Step Info -->
                        <div class="flex-1">
                            <div class="flex items-center md:justify-center gap-1.5 mb-1">
                                <span class="text-[10px] font-black uppercase tracking-wider {{ $step['state'] === 'active' ? 'text-blue-600' : ($step['state'] === 'completed' ? 'text-emerald-600' : 'text-slate-400') }}">
                                    Step 0{{ $step['number'] }}
                                </span>
                            </div>
                            <h3 class="text-xs font-bold text-slate-800 leading-tight mb-1">
                                {{ $step['title'] }}
                            </h3>
                            <p class="text-[11px] text-slate-500 font-normal leading-tight hidden md:block">
                                {{ $step['subtitle'] }}
                            </p>
                            <span class="inline-block mt-2 text-[10px] font-bold px-2 py-0.5 rounded-full {{ $step['state'] === 'active' ? 'bg-blue-100 text-blue-700' : ($step['state'] === 'completed' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500') }}">
                                {{ $step['date'] }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Dynamic Contextual Action Banner based on active status -->
            <div class="mt-10 p-6 rounded-2xl border transition-all 
                @if($submission->status === 'submitted') bg-blue-50/70 border-blue-200 text-blue-900
                @elseif($submission->status === 'under_review') bg-amber-50/70 border-amber-200 text-amber-900
                @elseif($submission->status === 'accepted') bg-emerald-50 border-emerald-300 text-emerald-900
                @elseif($submission->status === 'published') bg-purple-50 border-purple-200 text-purple-900
                @elseif($submission->status === 'rejected') bg-rose-50 border-rose-200 text-rose-900
                @else bg-slate-50 border-slate-200 text-slate-900 @endif">

                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 
                            @if($submission->status === 'submitted') bg-blue-600 text-white
                            @elseif($submission->status === 'under_review') bg-amber-600 text-white
                            @elseif($submission->status === 'accepted') bg-emerald-600 text-white
                            @elseif($submission->status === 'published') bg-purple-600 text-white
                            @else bg-rose-600 text-white @endif">
                            @if($submission->status === 'submitted')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            @elseif($submission->status === 'under_review')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            @elseif($submission->status === 'accepted')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            @elseif($submission->status === 'published')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            @endif
                        </div>
                        <div>
                            <h4 class="text-sm font-bold">
                                @if($submission->status === 'submitted')
                                    Initial Submission Acknowledged — Editorial Screening in Progress
                                @elseif($submission->status === 'under_review')
                                    Peer Review Assessment Active
                                @elseif($submission->status === 'accepted')
                                    Manuscript Accepted! Ready for Production & APC Verification
                                @elseif($submission->status === 'published')
                                    Manuscript Published Online & Fully Indexed!
                                @elseif($submission->status === 'rejected')
                                    Editorial Decision: Manuscript Declined
                                @else
                                    Manuscript Status: {{ ucfirst($submission->status) }}
                                @endif
                            </h4>
                            <p class="text-xs opacity-90 mt-1">
                                @if($submission->status === 'submitted')
                                    Your manuscript has been safely received. The editorial board is conducting initial technical checks (plagiarism screening, scope alignment, and formatting verification). Typical turnaround: 2 to 4 business days.
                                @elseif($submission->status === 'under_review')
                                    Your manuscript is being evaluated by peer reviewers. Once reviews are returned, the Chief Editor will finalize the publication decision.
                                @elseif($submission->status === 'accepted')
                                    Congratulations! Your manuscript has met all peer-review criteria. Please proceed with the Article Processing Charge (APC) payment to initiate final typesetting and digital proofing.
                                @elseif($submission->status === 'published')
                                    Your paper has been typeset, assigned a digital DOI, and is publicly available in the journal's current volume.
                                @elseif($submission->status === 'rejected')
                                    The editorial team has concluded evaluation. A detailed notice regarding reviewer recommendations has been logged.
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Call to action button inside alert -->
                    <div class="shrink-0 flex items-center gap-2">
                        @if($submission->status === 'accepted')
                            <a href="{{ route('payments.create', ['submission_id' => $submission->id, 'journal_id' => $submission->journal_id]) }}"
                                class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-md shadow-emerald-600/30 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                <span>Pay Publication Fee Now</span>
                            </a>
                        @elseif($submission->status === 'published' && $submission->article)
                            <a href="{{ route('articles.show', [$submission->journal->slug, $submission->article->slug]) }}"
                                class="px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-md shadow-purple-600/30 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                View Published Article
                            </a>
                        @else
                            <a href="{{ route('contact.index') }}"
                                class="px-4 py-2 bg-white/80 hover:bg-white text-slate-700 text-xs font-bold uppercase tracking-wider rounded-xl transition-all border border-slate-300/60 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                Contact Editorial Desk
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- 2-Column Content Grid: Manuscript Details & Timeline Log -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column (2 Cols): Metadata, Authors, Abstract, File -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Abstract & Scientific Scope -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80">
                    <div class="flex items-center gap-3 pb-4 mb-5 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-800 uppercase tracking-wider font-serif">Abstract</h3>
                    </div>

                    <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed text-sm">
                        {!! nl2br(e($submission->abstract)) !!}
                    </div>

                    @if($submission->keywords)
                        <div class="mt-6 pt-5 border-t border-slate-100">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Keywords</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach(explode(',', $submission->keywords) as $keyword)
                                    @if(trim($keyword))
                                        <span class="px-3 py-1 bg-slate-100 text-slate-700 text-xs font-semibold rounded-lg">
                                            {{ trim($keyword) }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Authors & Contributors Attribution -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80">
                    <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <h3 class="text-base font-bold text-slate-800 uppercase tracking-wider font-serif">Authors & Affiliations</h3>
                        </div>
                        <span class="text-xs font-semibold text-slate-500">
                            {{ is_array($submission->authors_data) ? count($submission->authors_data) : 1 }} Contributor(s)
                        </span>
                    </div>

                    @php
                        $authors = $submission->authors_data;
                        if (is_string($authors)) {
                            $authors = json_decode($authors, true);
                        }
                    @endphp

                    @if(!empty($authors) && is_array($authors))
                        <div class="space-y-4">
                            @foreach($authors as $idx => $author)
                                <div class="p-5 rounded-2xl border transition-all {{ !empty($author['is_corresponding']) ? 'border-blue-300 bg-blue-50/20' : 'border-slate-200/80 bg-slate-50/40' }}">
                                    <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                                        <div class="flex items-center gap-2">
                                            <span class="w-6 h-6 rounded-full bg-slate-800 text-white text-[11px] font-bold flex items-center justify-center">
                                                {{ $idx + 1 }}
                                            </span>
                                            <h4 class="text-sm font-bold text-slate-900">
                                                {{ $author['prefix'] ?? '' }} {{ $author['name'] ?? 'Unnamed Author' }}
                                            </h4>
                                        </div>
                                        @if(!empty($author['is_corresponding']))
                                            <span class="px-2.5 py-0.5 bg-blue-100 text-blue-700 rounded-full text-[10px] font-black uppercase tracking-wider">
                                                Corresponding Author
                                            </span>
                                        @endif
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-2 gap-x-4 text-xs text-slate-600 mt-3">
                                        @if(!empty($author['institution']))
                                            <div>
                                                <span class="text-slate-400 font-semibold">Affiliation:</span>
                                                <span class="text-slate-700 font-medium">{{ $author['institution'] }}</span>
                                                @if(!empty($author['department']))
                                                    ({{ $author['department'] }})
                                                @endif
                                            </div>
                                        @endif

                                        @if(!empty($author['email']))
                                            <div>
                                                <span class="text-slate-400 font-semibold">Email:</span>
                                                <a href="mailto:{{ $author['email'] }}" class="text-blue-600 hover:underline">{{ $author['email'] }}</a>
                                            </div>
                                        @endif

                                        @if(!empty($author['city_state_country']))
                                            <div>
                                                <span class="text-slate-400 font-semibold">Location:</span>
                                                <span class="text-slate-700">{{ $author['city_state_country'] }} {{ $author['postal_code'] ?? '' }}</span>
                                            </div>
                                        @endif

                                        @if(!empty($author['orcid']))
                                            <div>
                                                <span class="text-slate-400 font-semibold">ORCID:</span>
                                                <a href="https://orcid.org/{{ $author['orcid'] }}" target="_blank" class="text-emerald-600 hover:underline font-mono">{{ $author['orcid'] }}</a>
                                            </div>
                                        @endif
                                    </div>

                                    @if(!empty($author['roles']) && is_array($author['roles']))
                                        <div class="mt-3 pt-3 border-t border-slate-200/60 flex flex-wrap items-center gap-1.5">
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mr-1">CRediT Roles:</span>
                                            @foreach($author['roles'] as $role)
                                                <span class="px-2 py-0.5 bg-white border border-slate-200 text-slate-600 text-[10px] font-semibold rounded-md">
                                                    {{ $role }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Fallback to primary submitter account info -->
                        <div class="p-5 rounded-2xl border border-blue-200 bg-blue-50/20">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-sm font-bold text-slate-900">{{ $submission->user->name ?? 'Author' }}</h4>
                                <span class="px-2.5 py-0.5 bg-blue-100 text-blue-700 rounded-full text-[10px] font-black uppercase tracking-wider">
                                    Corresponding Author
                                </span>
                            </div>
                            <div class="text-xs text-slate-600 space-y-1">
                                <p><span class="text-slate-400 font-semibold">Email:</span> {{ $submission->user->email ?? 'N/A' }}</p>
                                @if($submission->user->affiliation)
                                    <p><span class="text-slate-400 font-semibold">Affiliation:</span> {{ $submission->user->affiliation }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Peer Review Comments & Evaluation Reports -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80">
                    <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-800 uppercase tracking-wider font-serif">Peer Review Comments & Feedback</h3>
                                <p class="text-xs text-slate-500 font-normal mt-0.5">Evaluation reports and recommendations from appointed expert reviewers.</p>
                            </div>
                        </div>
                        @php
                            $completedReviews = $submission->reviews->whereNotNull('completed_at');
                        @endphp
                        <span class="text-xs font-bold px-3 py-1 bg-purple-50 text-purple-700 border border-purple-200/60 rounded-full">
                            {{ $completedReviews->count() }} Report(s) Available
                        </span>
                    </div>

                    @if($completedReviews->count() > 0)
                        <div class="space-y-6">
                            @foreach($completedReviews as $idx => $review)
                                @php
                                    $recStyles = [
                                        'accept' => [
                                            'bg' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                            'label' => 'Accept Submission',
                                            'icon' => 'M5 13l4 4L19 7'
                                        ],
                                        'minor_revision' => [
                                            'bg' => 'bg-amber-50 text-amber-800 border-amber-200',
                                            'label' => 'Minor Revisions Required',
                                            'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'
                                        ],
                                        'major_revision' => [
                                            'bg' => 'bg-orange-50 text-orange-800 border-orange-200',
                                            'label' => 'Major Revisions Required',
                                            'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'
                                        ],
                                        'reject' => [
                                            'bg' => 'bg-rose-50 text-rose-800 border-rose-200',
                                            'label' => 'Reject Submission',
                                            'icon' => 'M6 18L18 6M6 6l12 12'
                                        ],
                                    ];
                                    $rec = $recStyles[$review->recommendation] ?? [
                                        'bg' => 'bg-slate-50 text-slate-800 border-slate-200',
                                        'label' => ucfirst(str_replace('_', ' ', $review->recommendation ?? 'Evaluation Complete')),
                                        'icon' => 'M9 12l2 2 4-4'
                                    ];
                                @endphp
                                <div class="p-6 rounded-2xl border border-slate-200 bg-slate-50/50 shadow-xs space-y-4">
                                    <div class="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-slate-200/80">
                                        <div class="flex items-center gap-2.5">
                                            <span class="w-7 h-7 rounded-lg bg-slate-900 text-white text-xs font-bold flex items-center justify-center">
                                                R{{ $loop->iteration }}
                                            </span>
                                            <div>
                                                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                                                    Reviewer #{{ $loop->iteration }} Report
                                                </h4>
                                                <span class="text-[11px] text-slate-400">
                                                    Submitted on {{ $review->completed_at->format('M d, Y - h:i A') }}
                                                </span>
                                            </div>
                                        </div>

                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border {{ $rec['bg'] }}">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $rec['icon'] }}" /></svg>
                                            {{ $rec['label'] }}
                                        </span>
                                    </div>

                                    <!-- Comments Content -->
                                    <div>
                                        <h5 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Reviewer Remarks & Recommendations:</h5>
                                        <div class="bg-white p-4 sm:p-5 rounded-xl border border-slate-200 text-sm text-slate-700 leading-relaxed font-normal whitespace-pre-line shadow-xs">
{{ $review->comments }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- In Progress or Pending Reviews Notice -->
                        <div class="p-6 rounded-2xl bg-amber-50/60 border border-amber-200 text-amber-900 flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-sm shadow-amber-500/30">
                                <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <div class="text-xs space-y-1">
                                <h4 class="font-bold text-sm text-amber-950">Peer Review Assessment in Progress</h4>
                                <p class="text-amber-800 leading-relaxed">
                                    @if($submission->reviews->count() > 0)
                                        Manuscript is currently under review with assigned subject specialists. Once the review reports and editorial assessments are submitted, the complete feedback comments will be displayed here.
                                    @else
                                        Manuscript has been received and is undergoing preliminary screening. Reviewers will be assigned shortly by the Editorial Board.
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Submitted File Repository & Author Documents -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80 space-y-4">
                    
                    <!-- Section Header -->
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-slate-900 text-white flex items-center justify-center shadow-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm sm:text-base font-bold text-slate-900 tracking-tight">Manuscript Documents & Author Forms</h3>
                                <p class="text-xs text-slate-500">Official manuscript archive, copyright agreements, and submission assets.</p>
                            </div>
                        </div>
                    </div>

                    <!-- 1. Original Manuscript File -->
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 bg-slate-50/80 hover:bg-slate-50 rounded-2xl border border-slate-200/80 transition-all gap-4">
                        <div class="flex items-center gap-3.5">
                            <div class="w-11 h-11 rounded-xl bg-blue-600 text-white flex items-center justify-center font-black text-xs uppercase tracking-wider shadow-xs shrink-0">
                                {{ strtoupper($submission->extension ?? 'DOCX') }}
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <h4 class="text-sm font-bold text-slate-900 truncate">{{ $submission->filename ?? 'manuscript_' . $submission->id . '.' . ($submission->extension ?? 'docx') }}</h4>
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-200/70 text-slate-700 uppercase tracking-wider">Original File</span>
                                </div>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    {{ $submission->formatted_size }} • {{ $submission->mime_type ?? 'Document File' }}
                                </p>
                            </div>
                        </div>

                        <a href="{{ route('submission.download', $submission) }}"
                            class="shrink-0 inline-flex items-center gap-2 px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            <span>Download Manuscript</span>
                        </a>
                    </div>

                    <!-- 2. Step 1: Download Copyright Form -->
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 bg-emerald-50/40 hover:bg-emerald-50/60 rounded-2xl border border-emerald-200/80 transition-all gap-4">
                        <div class="flex items-center gap-3.5">
                            <div class="w-11 h-11 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold text-base shadow-xs shrink-0">
                                📜
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <h4 class="text-sm font-bold text-slate-900">Copyright Transfer Agreement</h4>
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase tracking-wider border border-emerald-200">Step 1 • Download</span>
                                </div>
                                <p class="text-xs text-slate-600 mt-0.5">
                                    Official pre-filled copyright and author declaration agreement. Download, sign, and upload below.
                                </p>
                            </div>
                        </div>

                        <a href="{{ route('submission.copyright', $submission) }}" target="_blank"
                            class="shrink-0 inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            <span>Download Copyright Form</span>
                        </a>
                    </div>

                    <!-- 3. Step 2: Upload Filled & Signed Copyright Form -->
                    <div class="p-4 sm:p-5 rounded-2xl border {{ $submission->copyright_file_path ? 'bg-emerald-50/50 border-emerald-200' : 'bg-slate-50/90 border-slate-200' }}"
                        x-data="{ showUploadForm: {{ $submission->copyright_file_path ? 'false' : 'true' }} }">
                        
                        @if($submission->copyright_file_path)
                            <!-- Uploaded State View -->
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-11 h-11 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold text-base shadow-xs shrink-0">
                                        ✓
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <h4 class="text-sm font-bold text-slate-900">Signed Copyright Form</h4>
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase tracking-wider border border-emerald-200">Verified • Submitted</span>
                                        </div>
                                        <p class="text-xs text-slate-600 mt-0.5">
                                            <span class="font-semibold text-slate-800">{{ $submission->copyright_filename ?? 'signed_copyright_form.pdf' }}</span>
                                            @if($submission->copyright_uploaded_at)
                                                <span class="text-slate-400">•</span> Uploaded {{ $submission->copyright_uploaded_at->format('M d, Y - h:i A') }}
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="flex flex-wrap items-center gap-2 shrink-0">
                                    <a href="{{ route('submission.copyright.download-file', $submission) }}"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-xs">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        <span>View Signed Copy</span>
                                    </a>
                                    <button @click="showUploadForm = !showUploadForm" type="button"
                                        class="inline-flex items-center gap-1 px-3 py-2 bg-white hover:bg-slate-100 text-slate-700 border border-slate-300 text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-2xs">
                                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                        <span x-text="showUploadForm ? 'Cancel' : 'Replace File'">Replace File</span>
                                    </button>
                                </div>
                            </div>
                        @else
                            <!-- Initial Upload Prompt -->
                            <div class="flex items-center gap-3.5 mb-3">
                                <div class="w-11 h-11 rounded-xl bg-slate-900 text-white flex items-center justify-center font-bold text-base shadow-xs shrink-0">
                                    ✍️
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <h4 class="text-sm font-bold text-slate-900">Attach Signed Copyright Form</h4>
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-100 text-amber-900 uppercase tracking-wider border border-amber-200">Step 2 • Upload</span>
                                    </div>
                                    <p class="text-xs text-slate-600 mt-0.5">
                                        Upload your scanned or signed PDF/image agreement so the editorial office can verify and publish your paper.
                                    </p>
                                </div>
                            </div>
                        @endif

                        <!-- Upload File Input Box -->
                        <div x-show="showUploadForm" x-transition class="mt-3 pt-3 border-t {{ $submission->copyright_file_path ? 'border-emerald-200' : 'border-slate-200' }}">
                            <form action="{{ route('submission.copyright.upload', $submission) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
                                    <div class="flex-1">
                                        <input type="file" name="copyright_file" required accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                            class="block w-full text-xs text-slate-700 bg-white border border-slate-300 rounded-xl cursor-pointer file:mr-3 file:py-2 file:px-3.5 file:rounded-l-xl file:border-0 file:text-xs file:font-bold file:bg-slate-900 file:text-white hover:file:bg-slate-800 shadow-2xs focus:outline-hidden">
                                    </div>
                                    <button type="submit"
                                        class="shrink-0 inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-sm cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                        <span>Submit Signed Form</span>
                                    </button>
                                </div>
                                <p class="text-[11px] text-slate-400 mt-1.5">Supported formats: PDF, DOCX, JPG, PNG (Max 50MB)</p>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Right Column (1 Col): Activity Log, Journal Desk & Support Info -->
            <div class="space-y-8">

                <!-- Activity Log & Milestones -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80">
                    <div class="flex items-center gap-3 pb-4 mb-6 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-800 uppercase tracking-wider font-serif">Milestone History</h3>
                    </div>

                    <div class="relative border-l-2 border-slate-100 ml-3.5 space-y-6">
                        
                        <!-- Event 1: Initial Submission -->
                        <div class="relative pl-6">
                            <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-emerald-600 ring-4 ring-emerald-50"></div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600">{{ $submission->created_at->format('M d, Y - h:i A') }}</span>
                            <h4 class="text-xs font-bold text-slate-800 mt-0.5">Manuscript Submitted</h4>
                            <p class="text-xs text-slate-500 mt-0.5">Uploaded manuscript and author attribution verified by portal.</p>
                        </div>

                        @if($submission->status !== 'submitted')
                            <!-- Event 2: Under Review / Decision -->
                            <div class="relative pl-6">
                                <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full {{ $submission->status === 'rejected' ? 'bg-rose-600 ring-rose-50' : 'bg-blue-600 ring-blue-50' }} ring-4"></div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ $submission->updated_at->format('M d, Y - h:i A') }}</span>
                                <h4 class="text-xs font-bold text-slate-800 mt-0.5">Status Transition</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Manuscript status updated to <strong>{{ ucfirst(str_replace('_', ' ', $submission->status)) }}</strong>.</p>
                            </div>
                        @else
                            <div class="relative pl-6">
                                <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-blue-500 ring-4 ring-blue-50 animate-pulse"></div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-blue-600">Active</span>
                                <h4 class="text-xs font-bold text-slate-800 mt-0.5">Editorial Screening</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Editor conducting preliminary verification.</p>
                            </div>
                        @endif

                        @if($submission->article)
                            <div class="relative pl-6">
                                <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-purple-600 ring-4 ring-purple-50"></div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-purple-600">Published</span>
                                <h4 class="text-xs font-bold text-slate-800 mt-0.5">Online Publication Released</h4>
                                <p class="text-xs text-slate-500 mt-0.5">DOI: {{ $submission->article->doi ?? 'Assigned' }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Journal Editorial Office Card -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80">
                    <div class="flex items-center gap-3 pb-4 mb-4 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-800 uppercase tracking-wider font-serif">Journal Office</h3>
                    </div>

                    <div class="space-y-3 text-xs text-slate-600">
                        <div>
                            <span class="text-slate-400 font-semibold block">Journal:</span>
                            <a href="{{ route('journals.show', $submission->journal->slug) }}" class="text-slate-800 font-bold hover:text-blue-600 transition-colors">
                                {{ $submission->journal->title }}
                            </a>
                        </div>
                        @if($submission->journal->issn)
                            <div>
                                <span class="text-slate-400 font-semibold block">ISSN:</span>
                                <span class="text-slate-700 font-mono">{{ $submission->journal->issn }}</span>
                            </div>
                        @endif
                        <div>
                            <span class="text-slate-400 font-semibold block">Publisher:</span>
                            <span class="text-slate-700 font-semibold">HJPARAM Publications</span>
                        </div>
                        <div>
                            <span class="text-slate-400 font-semibold block">Editorial Support:</span>
                            <a href="mailto:support@hjparam.com" class="text-blue-600 hover:underline">support@hjparam.com</a>
                        </div>
                    </div>
                </div>

                <!-- Support & Inquiries -->
                <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white rounded-3xl p-6 sm:p-8 shadow-sm">
                    <h4 class="text-sm font-bold uppercase tracking-wider mb-2">Need Assistance?</h4>
                    <p class="text-xs text-slate-300 leading-relaxed mb-5">
                        If you have questions regarding reviewer feedback, turnaround time, or proof alterations, reach out directly with your Manuscript ID: <strong>#{{ $submission->id }}</strong>.
                    </p>
                    <a href="{{ route('contact.index') }}"
                        class="inline-flex items-center justify-center w-full px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-md">
                        Submit Editorial Inquiry
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
