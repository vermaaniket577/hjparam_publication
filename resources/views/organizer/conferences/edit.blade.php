@extends('layouts.admin')
@section('title', 'Refine Conference')
@section('breadcrumb', 'Conferences / Refine')

@section('content')
<div class="max-w-4xl mx-auto space-y-10 pb-32 animate-fade-in-up">
    <div class="flex items-center justify-between mb-8 pb-8 border-b border-slate-100 dark:border-slate-700/30">
        <div>
            <h1 class="text-3xl md:text-5xl font-serif font-black text-[#0f172a] dark:text-white mb-4 tracking-tight leading-none">Journal <span class="text-blue-500 italic">& Event</span> Refinement</h1>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] flex items-center gap-2">
                <span class="w-2 h-2 rounded-full {{ $conference->status == 'approved' ? 'bg-emerald-500 shadow-[0_0_15px_rgba(16,185,129,0.5)]' : 'bg-amber-500 shadow-[0_0_15px_rgba(245,158,11,0.5)] animate-pulse' }}"></span>
                Status: {{ strtoupper($conference->status) }} PROTOCOL
            </p>
        </div>
        <a href="{{ route('organizer.conferences.index') }}" class="px-8 py-3.5 bg-white border-2 border-slate-100 dark:border-slate-700/50 rounded-2xl text-[10px] font-black uppercase tracking-widest text-slate-500 hover:border-blue-500 hover:text-blue-600 dark:bg-gray-800 transition-all duration-500 flex items-center gap-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Registry Interface
        </a>
    </div>

    @if($conference->status == 'pending')
        <div class="bg-amber-50 border border-amber-100 p-8 rounded-[2rem] text-amber-900 font-bold text-sm shadow-sm flex items-center gap-6">
            <div class="w-12 h-12 bg-amber-500 text-white rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-amber-500/20">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="flex-grow leading-relaxed">This entry is currently in the <span class="font-black uppercase tracking-widest text-[#a16207]">Verification Loop</span>. You may modify the data before it goes Live.</p>
        </div>
    @endif

    <form action="{{ route('organizer.conferences.update', $conference) }}" method="POST" enctype="multipart/form-data" class="space-y-12">
        @csrf
        @method('PUT')
        
        <div class="bg-white dark:bg-gray-800 rounded-[3rem] p-10 md:p-14 shadow-sm border border-slate-100 dark:border-slate-700/30 group">
            <h2 class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 mb-12 flex items-center gap-4">
                Core Identity Registry
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-x-14 md:gap-y-12">
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-6">Conference Designation</label>
                    <input type="text" name="title" value="{{ old('title', $conference->title) }}" required
                           class="w-full px-8 py-5 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 focus:border-blue-500 focus:ring-8 focus:ring-blue-500/5 transition-all text-slate-800 dark:text-white font-bold">
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-6">Taxonomy</label>
                    <select name="category_id" required class="w-full px-8 py-5 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 transition-all font-bold appearance-none cursor-pointer text-slate-700 dark:text-gray-200">
                        @foreach($topics as $topic)
                            <option value="{{ $topic->id }}" {{ $conference->category_id == $topic->id ? 'selected' : '' }}>{{ $topic->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-6">Interaction Interface</label>
                    <select name="type" required class="w-full px-8 py-5 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 transition-all font-bold appearance-none cursor-pointer text-slate-700 dark:text-gray-200">
                        <option value="offline" {{ $conference->type == 'offline' ? 'selected' : '' }}>Physical Venue</option>
                        <option value="online" {{ $conference->type == 'online' ? 'selected' : '' }}>Digital Terminal</option>
                        <option value="hybrid" {{ $conference->type == 'hybrid' ? 'selected' : '' }}>Dual / Hybrid</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-6">Replace Visual Content</label>
                    <div class="flex flex-col md:flex-row gap-10 items-center">
                        @if($conference->banner_image)
                            <div class="w-64 h-40 rounded-[2rem] overflow-hidden border border-slate-100 dark:border-slate-700 flex-shrink-0 group-hover:scale-[1.02] transition-transform duration-700 shadow-xl">
                                <img src="{{ asset('storage/' . $conference->banner_image) }}" class="w-full h-full object-cover">
                            </div>
                        @endif
                        <div class="flex-grow w-full">
                            <div class="relative group/zone h-40 rounded-[2rem] bg-slate-50 dark:bg-gray-700/50 border-2 border-dashed border-slate-200 dark:border-gray-600 flex flex-col items-center justify-center cursor-pointer hover:border-blue-400 hover:bg-white transition-all duration-700">
                                <input type="file" name="banner_image" class="absolute inset-0 opacity-0 cursor-pointer overflow-hidden z-10" accept="image/*">
                                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 group-hover/zone:text-blue-600 transition-all text-center">Replace Visual Asset<br>or click to fetch</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fieldset 02: Logistics -->
        <div class="bg-white dark:bg-gray-800 rounded-[3rem] p-10 md:p-14 shadow-sm border border-slate-100 dark:border-slate-700/30 overflow-hidden group">
            <h2 class="text-[10px] font-black uppercase tracking-[0.3em] text-emerald-500 mb-12">Logistics Terminal Registry</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-x-14 md:gap-y-12">
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-6">Detailed Venue</label>
                    <input type="text" name="venue" value="{{ old('venue', $conference->venue) }}" required
                           class="w-full px-8 py-5 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 focus:border-emerald-500 transition-all text-slate-800 dark:text-white font-bold">
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-6">City Designation</label>
                    <input type="text" name="city" value="{{ old('city', $conference->city) }}" required
                           class="w-full px-8 py-5 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 transition-all font-bold text-slate-800 dark:text-white">
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-6">Country Terminal</label>
                    <select name="country_id" required class="w-full px-8 py-5 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 transition-all font-bold appearance-none cursor-pointer text-slate-700 dark:text-gray-200">
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" {{ $conference->country_id == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-6">Activation</label>
                    <input type="date" name="start_date" value="{{ $conference->start_date->format('Y-m-d') }}" required
                           class="w-full px-8 py-5 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 transition-all font-bold text-slate-800 dark:text-white">
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-6">Termination</label>
                    <input type="date" name="end_date" value="{{ $conference->end_date->format('Y-m-d') }}" required
                           class="w-full px-8 py-5 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 transition-all font-bold text-slate-800 dark:text-white">
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-[3rem] p-10 md:p-14 shadow-sm border border-slate-100 dark:border-slate-700/30">
            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-8">Narrative Abstract Registry</label>
            <textarea name="description" rows="12" required
                      class="w-full px-10 py-10 rounded-[3rem] bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 focus:border-blue-500 transition-all text-slate-700 dark:text-gray-200 font-medium leading-[2.2] shadow-sm">{{ old('description', $conference->description) }}</textarea>
        </div>

        <div class="pt-10 flex items-center justify-end gap-10">
            <button type="submit" class="group/launch relative px-14 py-6 bg-[#0f172a] hover:bg-emerald-600 text-white font-black rounded-[2rem] shadow-2xl transition-all duration-700 hover:-translate-y-2 active:scale-95 uppercase tracking-[0.3em] text-[11px] flex items-center gap-4 overflow-hidden">
                Update Protocol Registry
                <svg class="w-6 h-6 animate-pulse group-hover/launch:translate-x-2 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </form>
</div>
@endsection
