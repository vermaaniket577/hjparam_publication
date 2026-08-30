@extends('layouts.admin')
@section('title', 'View Message')
@section('breadcrumb', 'Contact Intelligence / Details')

@section('content')
<div class="max-w-4xl mx-auto space-y-10 pb-32">
    <div class="flex items-center justify-between mb-8 pb-8 border-b border-slate-100">
        <div>
            <h1 class="text-3xl font-serif font-black text-[#0f172a] mb-4 tracking-tight leading-none">Inquiry <span class="text-blue-500 italic">Terminal Details</span></h1>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 shadow-[0_0_15px_rgba(59,130,246,0.5)]"></span>
                Logged: {{ $contactMessage->created_at->format('M d, Y H:i:s') }}
            </p>
        </div>
        <a href="{{ route('admin.contact-messages.index') }}" class="px-8 py-3.5 bg-white border-2 border-slate-100 rounded-2xl text-[10px] font-black uppercase tracking-widest text-slate-500 hover:border-blue-500 hover:text-blue-600 transition-all duration-500 flex items-center gap-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Registry
        </a>
    </div>

    <!-- Header Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
        <div class="md:col-span-2 bg-white rounded-[2.5rem] p-12 shadow-sm border border-slate-50 group">
            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 ml-1">Inquirer Identity</label>
            <div class="flex items-center gap-6 mb-12">
                <div class="w-14 h-14 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-center text-slate-300 font-black italic shadow-inner">ID</div>
                <div>
                    <h3 class="text-2xl font-black text-slate-900 leading-none mb-1">{{ $contactMessage->name }}</h3>
                    <p class="text-sm font-bold text-blue-600 italic underline decoration-blue-100 decoration-4 underline-offset-4">{{ $contactMessage->email }}</p>
                </div>
            </div>

            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 ml-1">Subject Baseline</label>
            <div class="p-8 bg-slate-50 rounded-[2rem] border border-slate-100 mb-12 group-hover:bg-white transition-all">
                <h4 class="text-xl font-bold text-slate-800 leading-relaxed italic">"{{ $contactMessage->subject }}"</h4>
            </div>

            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 ml-1">Intelligence Content (Message)</label>
            <div class="p-10 bg-white rounded-[2.5rem] border border-slate-100 leading-loose text-slate-700 font-medium text-lg italic shadow-xl shadow-slate-100/50">
                {{ $contactMessage->message }}
            </div>
        </div>

        <div class="space-y-10">
            <div class="bg-[#0f172a] rounded-[2.5rem] p-10 text-white relative overflow-hidden shadow-2xl">
                <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
                <div class="relative z-10">
                    <h4 class="text-[10px] font-black uppercase tracking-widest text-blue-400 mb-6 pb-4 border-b border-white/10">Protocol Status</h4>
                    <div class="flex items-center gap-6 mb-8">
                        <div class="w-10 h-10 bg-white/10 border border-white/20 rounded-xl flex items-center justify-center text-white/50">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2.5"></path>
                                <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-width="2.5"></path>
                            </svg>
                        </div>
                        <div>
                            <span class="block text-xs font-bold">{{ $contactMessage->read_at ? 'Logged Read' : 'Unread Protocol' }}</span>
                            @if($contactMessage->read_at)
                                <span class="block text-[10px] text-white/40">{{ $contactMessage->read_at->format('M d, H:i') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] p-10 border border-slate-100 shadow-sm flex flex-col gap-6">
                <h4 class="text-[10px] font-black text-slate-900 uppercase tracking-widest pb-4 border-b border-slate-50">Strategic Response</h4>
                <p class="text-xs text-slate-500 italic pb-6 leading-relaxed">Respond directly to the inquirer via their secure email terminal listed above.</p>
                <a href="mailto:{{ $contactMessage->email }}?subject=Re: {{ $contactMessage->subject }}" class="w-full py-5 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-2xl shadow-xl shadow-blue-600/20 text-center transition-all uppercase tracking-widest text-[11px] active:scale-95">Open Email terminal</a>
            </div>

            <form action="{{ route('admin.contact-messages.destroy', $contactMessage) }}" method="POST" onsubmit="return confirm('Neutralize record?')">
                @csrf @method('DELETE')
                <button type="submit" class="w-full py-5 bg-white border border-red-100 text-red-500 hover:bg-red-500 hover:text-white rounded-2xl font-black transition-all uppercase tracking-widest text-[11px]">Neutralize Record</button>
            </form>
        </div>
    </div>
</div>
@endsection
