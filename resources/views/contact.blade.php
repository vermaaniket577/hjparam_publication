@extends('layouts.web')
@section('title', 'Contact Support & Resource Intelligence | HJPARAM')

@section('content')
<div class="bg-slate-50 min-h-screen">
    <!-- Hero Banner -->
    <div class="bg-[#0f172a] py-32 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-blue-600/10 rounded-full blur-3xl"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-3xl">
                <h1 class="text-4xl md:text-6xl font-serif font-black text-white mb-6 tracking-tight">Global Support <br><span class="text-blue-400">Intelligence Port</span></h1>
                <p class="text-slate-400 text-lg md:text-xl font-medium leading-relaxed max-w-2xl">
                    Bridge the gap between your research and the platform. Our elite response team handles technical, editorial, and logistic inquiries worldwide.
                </p>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 -mt-20 relative z-20 pb-20">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            <!-- Sidebar: Comms Info -->
            <div class="lg:col-span-4 space-y-8">
                <div class="bg-white rounded-[2.5rem] p-10 shadow-2xl border border-slate-100 flex flex-col gap-10 sticky top-32">
                    <h2 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-4 pb-4 border-b">Direct Access Grid</h2>
                    
                    <div class="flex items-start gap-6 group">
                        <div class="w-14 h-14 bg-slate-50 flex items-center justify-center rounded-2xl border border-slate-100 group-hover:bg-[#0f172a] group-hover:border-[#0f172a] transition-all duration-500">
                            <svg class="w-6 h-6 text-slate-400 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Editorial Query</span>
                            <span class="block text-sm font-bold text-slate-800">editor@hjparam-pub.org</span>
                        </div>
                    </div>

                    <div class="flex items-start gap-6 group">
                        <div class="w-14 h-14 bg-slate-50 flex items-center justify-center rounded-2xl border border-slate-100 group-hover:bg-[#0f172a] group-hover:border-[#0f172a] transition-all duration-500">
                            <svg class="w-6 h-6 text-slate-400 group-hover:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Logistic Base</span>
                            <span class="block text-sm font-bold text-slate-800">Main Research Complex</span>
                            <span class="block text-[11px] text-slate-400 mt-1">Silicon Valley Tech Hub, California, US</span>
                        </div>
                    </div>

                    <div class="flex items-start gap-6 group">
                        <div class="w-14 h-14 bg-slate-50 flex items-center justify-center rounded-2xl border border-slate-100 group-hover:bg-[#0f172a] group-hover:border-[#0f172a] transition-all duration-500">
                            <svg class="w-6 h-6 text-slate-400 group-hover:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Availability Loop</span>
                            <span class="block text-sm font-bold text-slate-800">24/7 Priority Support</span>
                            <span class="block text-[11px] text-slate-400 mt-1">Average Response: ~6 Hours</span>
                        </div>
                    </div>

                    <div class="mt-8 p-8 bg-blue-50 rounded-[2rem] border border-blue-100 text-center">
                        <p class="text-[10px] font-black text-blue-900 uppercase tracking-widest mb-4 italic">Social Connectivity Portals</p>
                        <div class="flex justify-center gap-6">
                            <a href="#" class="p-2.5 bg-white text-blue-600 rounded-xl shadow-lg border border-blue-100 hover:bg-blue-600 hover:text-white transition-all scale-110">LinkedIn</a>
                            <a href="#" class="p-2.5 bg-white text-blue-400 rounded-xl shadow-lg border border-blue-100 hover:bg-blue-400 hover:text-white transition-all scale-110">Twitter</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Area -->
            <div class="lg:col-span-8">
                <div class="bg-white rounded-[3rem] p-10 md:p-16 shadow-2xl border border-slate-100 group">
                    @if(session('success'))
                        <div class="p-10 bg-emerald-50 rounded-[2.5rem] text-center border-2 border-emerald-100 animate-bounce-short">
                            <div class="w-12 h-12 bg-emerald-500 text-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl ring-8 ring-emerald-50">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M5 13l4 4L19 7" stroke-width="3"></path>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-black text-emerald-900 mb-2">Message Neutralized</h2>
                            <p class="text-emerald-700 font-medium leading-relaxed italic max-w-sm mx-auto">{{ session('success') }}</p>
                        </div>
                    @else
                        <h2 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-12 pb-4 border-b">Communications Uplink</h2>
                        
                        <form action="{{ route('contact.store') }}" method="POST" class="space-y-10">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                <div>
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 ml-1">Full Identity <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Dr. Jane Smith" required
                                           class="w-full px-6 py-4.5 rounded-2xl bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-bold text-slate-700">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 ml-1">Secure Email Uplink <span class="text-red-500">*</span></label>
                                    <input type="email" name="email" value="{{ old('email') }}" placeholder="jane.smith@mit.edu" required
                                           class="w-full px-6 py-4.5 rounded-2xl bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-bold text-slate-700">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 ml-1">Inquiry Vector (Subject) <span class="text-red-500">*</span></label>
                                <input type="text" name="subject" value="{{ old('subject') }}" placeholder="Sub-category: Technical Support Protocol" required
                                       class="w-full px-6 py-4.5 rounded-2xl bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-bold text-slate-700">
                            </div>

                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 ml-1">Detailed Log Content <span class="text-red-500">*</span></label>
                                <textarea name="message" rows="8" placeholder="Please describe your technical or editorial bottleneck with maximum precision..." required
                                          class="w-full px-8 py-6 rounded-[2rem] bg-slate-50 border-2 border-slate-100 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-medium text-slate-700 leading-relaxed">{{ old('message') }}</textarea>
                            </div>

                            <button type="submit" class="w-full py-5 bg-[#0f172a] hover:bg-blue-600 text-white font-black rounded-2xl shadow-2xl transition-all duration-500 hover:-translate-y-1 active:scale-95 uppercase tracking-[0.2em] text-[11px] flex items-center justify-center gap-3">
                                Pulse Message Terminal
                                <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                                </svg>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
