@extends('layouts.web')
@section('title', 'Certificate Verification | HJPARAM')

@section('content')
<div class="bg-slate-50 min-h-screen py-16">
    <div class="container mx-auto px-4 max-w-xl">
        <div class="bg-white rounded-3xl p-8 md:p-10 shadow-2xl border border-slate-100 text-center space-y-6">
            
            @if($submission)
                <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto shadow-md">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </div>

                <div class="space-y-1">
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-black uppercase tracking-widest rounded-full border border-emerald-200">
                        Authentic Verified Certificate
                    </span>
                    <h1 class="text-2xl font-serif font-black text-slate-900 pt-2">
                        Official Academic Record
                    </h1>
                    <p class="text-xs font-mono font-bold text-slate-500">ID: {{ $code }}</p>
                </div>

                <div class="p-6 bg-slate-50 rounded-2xl border border-slate-200/80 text-left space-y-3 text-xs">
                    <div>
                        <span class="text-slate-400 font-bold uppercase text-[10px] block">Certificate Type</span>
                        <span class="font-bold text-slate-900 text-sm">{{ $isPresentation ? 'Oral / Paper Presentation Certificate' : 'Delegate Attendance Certificate' }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 font-bold uppercase text-[10px] block">Participant / Presenter</span>
                        <span class="font-bold text-slate-800 text-sm">
                            @if(!empty($submission->scholars_data[0]['first_name']))
                                {{ $submission->scholars_data[0]['first_name'] }} {{ $submission->scholars_data[0]['last_name'] ?? '' }}
                            @else
                                {{ $submission->user->name }}
                            @endif
                        </span>
                    </div>

                    @if($isPresentation)
                        <div>
                            <span class="text-slate-400 font-bold uppercase text-[10px] block">Paper Title</span>
                            <span class="font-medium text-slate-700 italic">"{{ $submission->title }}"</span>
                        </div>
                    @endif

                    <div>
                        <span class="text-slate-400 font-bold uppercase text-[10px] block">Conference</span>
                        <span class="font-bold text-blue-900">{{ $submission->conference ? $submission->conference->title : 'HJParam Academic Event' }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 font-bold uppercase text-[10px] block">Organized By</span>
                        <span class="font-medium text-slate-700">{{ $submission->conference ? $submission->conference->organizer_name : 'HJParam Publications' }}</span>
                    </div>
                </div>

                <div class="pt-2">
                    <a href="{{ $isPresentation ? route('certificates.presentation', $submission) : route('certificates.attendee', $submission) }}" target="_blank"
                       class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs shadow-md transition-all">
                        View Certificate
                    </a>
                </div>
            @else
                <div class="w-16 h-16 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto shadow-md">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>

                <div class="space-y-1">
                    <h1 class="text-xl font-serif font-black text-slate-900">Certificate Not Found</h1>
                    <p class="text-xs text-slate-500">The verification code <code class="bg-slate-100 px-2 py-0.5 rounded font-mono">{{ $code }}</code> could not be matched against any valid issued credentials.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
