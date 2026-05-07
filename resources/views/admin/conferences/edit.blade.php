@extends('layouts.admin')
@section('title', 'Modify Conference')
@section('breadcrumb', 'Conferences / Edit')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 pb-32">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-900 dark:text-white">Refine Event Registry</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Unique ID: HJ-CONF-{{ str_pad($conference->id, 6, '0', STR_PAD_LEFT) }}</p>
        </div>
        <a href="{{ route('admin.conferences.index') }}" class="px-5 py-2.5 bg-white dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 rounded-2xl text-xs font-black uppercase tracking-widest text-slate-500 hover:border-slate-300 dark:hover:border-slate-500 transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Registry Interface
        </a>
    </div>

    <form action="{{ route('admin.conferences.update', $conference) }}" method="POST" enctype="multipart/form-data" class="space-y-8 group">
        @csrf
        @method('PUT')
        
        <!-- Section: Strategic Controls -->
        <div class="bg-blue-600 rounded-[2.5rem] p-10 shadow-2xl text-white relative overflow-hidden group/controls">
            <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
            <div class="absolute top-0 right-0 w-32 h-full bg-white/5 skew-x-12 translate-x-1/2"></div>
            
            <h2 class="text-[10px] font-black uppercase tracking-[0.3em] text-blue-200 mb-10 pb-6 border-b border-white/10 flex items-center gap-3 relative z-10">
                <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                Strategic Overrides
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative z-10">
                <div class="space-y-4">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-blue-200 ml-1">Promotional Status</label>
                    <div class="flex items-center gap-4 py-2 px-6 bg-white/10 rounded-2xl border border-white/20">
                        <input type="checkbox" name="is_featured" value="1" {{ $conference->is_featured ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm font-black uppercase tracking-widest">Featured Spotlight</span>
                    </div>
                </div>

                <div class="space-y-4">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-blue-200 ml-1">Admin Validation</label>
                    <select name="status" class="w-full px-6 py-3.5 rounded-2xl bg-white/10 border border-white/20 text-sm font-black uppercase tracking-widest appearance-none cursor-pointer focus:bg-white focus:text-blue-900 transition-all">
                        <option value="pending" {{ $conference->status == 'pending' ? 'selected' : '' }}>Pending Protocol</option>
                        <option value="approved" {{ $conference->status == 'approved' ? 'selected' : '' }}>Approved (Live)</option>
                        <option value="rejected" {{ $conference->status == 'rejected' ? 'selected' : '' }}>Rejected (Halt)</option>
                    </select>
                </div>

                <div class="space-y-4">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-blue-200 ml-1">Logistic Services</label>
                    <div class="flex items-center gap-4 py-2 px-6 bg-white/10 rounded-2xl border border-white/20">
                        <input type="checkbox" name="invitation_letter_support" value="1" {{ $conference->invitation_letter_support ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-xs font-black uppercase tracking-widest">Visa Support Protocol</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section: Essential Identity -->
        <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] p-10 shadow-2xl shadow-blue-900/5 border border-slate-100 dark:border-slate-700/30 overflow-hidden">
            <h2 class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 mb-10 pb-6 border-b border-slate-50 dark:border-slate-700 flex items-center gap-3">
                Core Meta Information
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 ml-1">Conference Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $conference->title) }}" required
                           class="w-full px-6 py-4.5 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-bold text-slate-700 dark:text-white">
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 ml-1">Academic Category <span class="text-red-500">*</span></label>
                    <select name="category_id" required class="w-full px-6 py-4.5 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 transition-all font-bold text-slate-700 dark:text-white appearance-none cursor-pointer">
                        @foreach($topics as $topic)
                            <option value="{{ $topic->id }}" {{ $conference->category_id == $topic->id ? 'selected' : '' }}>{{ $topic->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 ml-1">Event Type <span class="text-red-500">*</span></label>
                    <select name="type" required class="w-full px-6 py-4.5 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 transition-all font-bold text-slate-700 dark:text-white appearance-none cursor-pointer">
                        <option value="offline" {{ $conference->type == 'offline' ? 'selected' : '' }}>Offline (On-Site)</option>
                        <option value="online" {{ $conference->type == 'online' ? 'selected' : '' }}>Online (Virtual)</option>
                        <option value="hybrid" {{ $conference->type == 'hybrid' ? 'selected' : '' }}>Hybrid (Mixed)</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 ml-1">Replace Banner Image</label>
                    <div class="flex flex-col md:flex-row gap-8 items-center">
                        @if($conference->banner_image)
                            <div class="w-48 h-32 rounded-2xl overflow-hidden border-2 border-slate-100 flex-shrink-0">
                                <img src="{{ asset('storage/' . $conference->banner_image) }}" class="w-full h-full object-cover">
                            </div>
                        @endif
                        <div class="flex-grow w-full">
                            <div class="relative group/upload h-32 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-dashed border-slate-200 dark:border-gray-600 flex flex-col items-center justify-center cursor-pointer hover:border-blue-400 transition-all">
                                <input type="file" name="banner_image" class="absolute inset-0 opacity-0 cursor-pointer overflow-hidden" accept="image/*">
                                <svg class="w-6 h-6 text-slate-300 group-hover/upload:text-blue-500 transition-all mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                                </svg>
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 group-hover/upload:text-blue-600 transition-all text-center">Drag new visual asset<br>or click to fetch</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section: Logistics -->
        <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] p-10 shadow-2xl shadow-blue-900/5 border border-slate-100 dark:border-slate-700/30 overflow-hidden">
            <h2 class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 mb-10 pb-6 border-b border-slate-50 dark:border-slate-700">Logistic Grid</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 ml-1">Venue Specifics <span class="text-red-500">*</span></label>
                    <input type="text" name="venue" value="{{ old('venue', $conference->venue) }}" required
                           class="w-full px-6 py-4 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 focus:border-slate-900 dark:focus:border-white transition-all font-bold text-slate-700 dark:text-white shadow-sm">
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 ml-1">City <span class="text-red-500">*</span></label>
                    <input type="text" name="city" value="{{ old('city', $conference->city) }}" required
                           class="w-full px-6 py-4 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 font-bold text-slate-700 dark:text-white">
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 ml-1">Country <span class="text-red-500">*</span></label>
                    <select name="country_id" required class="w-full px-6 py-4.5 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 font-bold text-slate-700 dark:text-white appearance-none cursor-pointer">
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" {{ $conference->country_id == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 ml-1">Start Phase</label>
                    <input type="date" name="start_date" value="{{ $conference->start_date->format('Y-m-d') }}" required
                           class="w-full px-6 py-4 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 font-bold text-slate-700 dark:text-white shadow-sm">
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 ml-1">Terminal Phase</label>
                    <input type="date" name="end_date" value="{{ $conference->end_date->format('Y-m-d') }}" required
                           class="w-full px-6 py-4 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 font-bold text-slate-700 dark:text-white shadow-sm">
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] p-10 shadow-2xl shadow-blue-900/5 border border-slate-100 dark:border-slate-700/30">
            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 ml-1">Narrative Content <span class="text-red-500">*</span></label>
            <textarea name="description" rows="12" required
                      class="w-full px-8 py-6 rounded-[2.5rem] bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 focus:border-blue-500 font-medium text-slate-700 dark:text-white leading-relaxed">{{ old('description', $conference->description) }}</textarea>
        </div>

        <div class="flex items-center justify-end gap-6 pt-10 border-t border-slate-100 dark:border-gray-700">
            <button type="submit" class="group/btn relative px-12 py-5 bg-[#0f172a] hover:bg-emerald-600 text-white font-black rounded-2xl shadow-2xl transition-all duration-500 hover:-translate-y-1 active:scale-95 uppercase tracking-[0.2em] text-[11px] flex items-center gap-3">
                Commit Modifications
                <svg class="w-5 h-5 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </form>
</div>
@endsection
