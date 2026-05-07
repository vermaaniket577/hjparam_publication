@extends('layouts.web')
@section('title', 'Subscribe to Academic Alerts | HJPARAM')

@section('content')
<div class="bg-slate-50 min-h-screen py-20">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            
            <div class="bg-white rounded-[3rem] shadow-2xl border border-slate-100 overflow-hidden flex flex-col md:flex-row">
                <!-- Left: Branding -->
                <div class="md:w-1/3 bg-[#0f172a] p-12 text-white flex flex-col justify-center relative overflow-hidden">
                    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center mb-8 shadow-xl shadow-blue-600/30">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </div>
                        <h1 class="text-3xl font-serif font-black mb-6 tracking-tight leading-tight">Stay Ahead <br><span class="text-blue-400 italic">Globally</span></h1>
                        <p class="text-slate-400 text-sm font-medium leading-relaxed">Join 50k+ researchers receiving tailored updates on conferences, journals, and funding opportunities.</p>
                    </div>
                </div>

                <!-- Right: Form -->
                <div class="md:w-2/3 p-12 md:p-16">
                    @if(session('success'))
                        <div class="bg-emerald-50 border border-emerald-100 p-8 rounded-[2rem] text-center mb-8 animate-bounce-short">
                            <div class="w-16 h-16 bg-emerald-500 text-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M5 13l4 4L19 7" stroke-width="3"></path>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-black text-emerald-900 mb-2">Registration Confirmed!</h2>
                            <p class="text-emerald-700 font-medium">{{ session('success') }}</p>
                        </div>
                    @else
                        <form action="{{ route('subscribe.store') }}" method="POST" class="space-y-10">
                            @csrf
                            
                            <div>
                                <h2 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-10 pb-4 border-b">Subscription Protocol</h2>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 ml-1">Academic Email Terminal</label>
                                <input type="email" name="email" value="{{ old('email') }}" placeholder="Ex: researcher@university.edu" required
                                       class="w-full px-8 py-5 rounded-2xl bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-bold text-slate-700 shadow-sm">
                                @error('email') <p class="mt-2 text-xs font-bold text-red-500 ml-2 italic line-clamp-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-6 ml-1">Research Field Intelligence (Optional)</label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    @foreach($topics as $topic)
                                        <label class="flex items-center gap-4 py-3 px-5 bg-slate-50 border border-slate-100 rounded-xl cursor-pointer hover:bg-blue-50 transition-colors group">
                                            <input type="checkbox" name="interests[]" value="{{ $topic->id }}" class="w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500 transition-all">
                                            <span class="text-sm font-bold text-slate-600 group-hover:text-blue-900 transition-colors">{{ $topic->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="pt-6">
                                <button type="submit" class="w-full py-5 bg-[#0f172a] hover:bg-blue-600 text-white font-black rounded-2xl shadow-2xl transition-all duration-500 hover:-translate-y-1 active:scale-95 uppercase tracking-[0.2em] text-[11px] flex items-center justify-center gap-3 group">
                                    Activate Intelligence Feed
                                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </button>
                                <p class="text-center text-slate-400 text-[9px] mt-6 font-medium uppercase tracking-widest">Secure protocol. Zero data leak policy.</p>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Perks Summary -->
            <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-10">
                <div class="text-center">
                    <div class="w-12 h-12 bg-white rounded-2xl shadow-lg border border-slate-50 flex items-center justify-center mx-auto mb-6 text-blue-600 font-black italic">!</div>
                    <h3 class="text-xs font-black uppercase tracking-widest text-slate-900 mb-2">Priority Alerts</h3>
                    <p class="text-slate-500 text-[11px] font-medium leading-relaxed">Be the first to know about early-bird deadlines and grant openings.</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-white rounded-2xl shadow-lg border border-slate-50 flex items-center justify-center mx-auto mb-6 text-emerald-600 font-black italic">✓</div>
                    <h3 class="text-xs font-black uppercase tracking-widest text-slate-900 mb-2">Verified Only</h3>
                    <p class="text-slate-500 text-[11px] font-medium leading-relaxed">We only list double-blind peer-reviewed events and journals.</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-white rounded-2xl shadow-lg border border-slate-50 flex items-center justify-center mx-auto mb-6 text-slate-900 font-black italic">@</div>
                    <h3 class="text-xs font-black uppercase tracking-widest text-slate-900 mb-2">Tailored Feed</h3>
                    <p class="text-slate-500 text-[11px] font-medium leading-relaxed">Algorithm-driven selection based on your specific research interests.</p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
