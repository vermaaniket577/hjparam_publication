@extends('layouts.web')
@section('title', 'Live International Conferences | HJPARAM')

@section('content')
<div class="bg-slate-50 min-h-screen" x-data="conferenceApp()">
    <!-- Header Section -->
    <div class="bg-[#0f172a] py-24 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/asfalt-dark.png')]"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-indigo-600/10 rounded-full blur-3xl"></div>
        
        <div class="container mx-auto px-6 relative z-10 text-center md:text-left">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 mb-6">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                </span>
                <span class="text-[10px] font-black text-blue-400 uppercase tracking-widest">Live Updates</span>
            </div>
            <h1 class="text-5xl md:text-7xl font-serif font-black text-white mb-8 tracking-tighter leading-none">
                Global <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">Knowledge</span> Exchanges
            </h1>
            <p class="text-slate-400 text-lg md:text-xl max-w-3xl font-medium leading-relaxed mb-10">
                Access the most prestigious international academic gatherings. Real-time connections, peer-reviewed excellence, and worldwide scientific impact.
            </p>
            <div class="flex flex-wrap justify-center md:justify-start gap-4">
                <div class="flex items-center gap-2 px-4 py-2 bg-white/5 border border-white/10 rounded-xl">
                    <span class="text-blue-400 font-black text-xl">{{ $conferences->total() }}+</span>
                    <span class="text-slate-500 text-xs font-bold uppercase tracking-wider">Active Events</span>
                </div>
                <div class="flex items-center gap-2 px-4 py-2 bg-white/5 border border-white/10 rounded-xl">
                    <span class="text-emerald-400 font-black text-xl">{{ $countries->count() }}</span>
                    <span class="text-slate-500 text-xs font-bold uppercase tracking-wider">Countries</span>
                </div>
            </div>
        </div>
    </div>

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
                                    <span class="px-4 py-1.5 bg-blue-50 text-blue-700 text-[10px] font-black rounded-full border border-blue-100 uppercase tracking-widest shadow-sm">{{ $conf->category->name }}</span>
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
