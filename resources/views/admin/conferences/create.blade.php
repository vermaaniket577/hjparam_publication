@extends('layouts.admin')
@section('title', 'Create Conference')
@section('breadcrumb', 'Conferences / Create')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 pb-32">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-900 dark:text-white">New Academic <span class="text-blue-600">Event</span></h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Add an international conference to our registry</p>
        </div>
        <a href="{{ route('admin.conferences.index') }}" class="px-5 py-2.5 bg-white dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 rounded-2xl text-xs font-black uppercase tracking-widest text-slate-500 hover:border-slate-300 dark:hover:border-slate-500 transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Registry
        </a>
    </div>

    <form action="{{ route('admin.conferences.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8 group">
        @csrf
        
        <!-- Section: Essential Identity -->
        <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] p-10 shadow-2xl shadow-blue-900/5 ring-1 ring-slate-100 dark:ring-slate-700/30 overflow-hidden relative">
            <div class="absolute top-0 left-0 w-2 h-full bg-blue-600"></div>
            <h2 class="text-xs font-black uppercase tracking-[0.2em] text-blue-600 mb-10 pb-6 border-b border-slate-50 dark:border-slate-700 flex items-center gap-3">
                <span class="w-2 h-2 rounded-full bg-blue-600 shadow-[0_0_10px_rgba(37,99,235,0.5)]"></span>
                Event Identity
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 ml-1">Conference Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="Ex: International Conference on Quantum Computing 2026" required
                           class="w-full px-6 py-4.5 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-bold text-slate-700 dark:text-white placeholder-slate-300 shadow-sm">
                    @error('title') <p class="mt-2 text-xs font-bold text-red-500 ml-2 italic underline decoration-red-100 decoration-4 underline-offset-4">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 ml-1">Academic Category <span class="text-red-500">*</span></label>
                    <select name="category_id" required class="w-full px-6 py-4.5 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-bold text-slate-700 dark:text-white appearance-none cursor-pointer">
                        <option value="">Select Category</option>
                        @foreach($topics as $topic)
                            <option value="{{ $topic->id }}" {{ old('category_id') == $topic->id ? 'selected' : '' }}>{{ $topic->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 ml-1">Event Type <span class="text-red-500">*</span></label>
                    <select name="type" required class="w-full px-6 py-4.5 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-bold text-slate-700 dark:text-white appearance-none cursor-pointer">
                        <option value="offline" {{ old('type') == 'offline' ? 'selected' : '' }}>Offline (On-Site)</option>
                        <option value="online" {{ old('type') == 'online' ? 'selected' : '' }}>Online (Virtual)</option>
                        <option value="hybrid" {{ old('type') == 'hybrid' ? 'selected' : '' }}>Hybrid (Mixed)</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 ml-1">Banner Image (Optional)</label>
                    <div class="relative group/upload h-32 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-dashed border-slate-200 dark:border-gray-600 flex flex-col items-center justify-center cursor-pointer hover:border-blue-400 transition-all">
                        <input type="file" name="banner_image" class="absolute inset-0 opacity-0 cursor-pointer overflow-hidden" accept="image/*">
                        <svg class="w-8 h-8 text-slate-300 group-hover/upload:text-blue-500 transition-all mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 group-hover/upload:text-blue-600 transition-all">Upload Event Banner (Max 2MB)</span>
                    </div>
                    @error('banner_image') <p class="mt-2 text-xs text-red-500 ml-2 font-bold">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Section: Logistics -->
        <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] p-10 shadow-2xl shadow-blue-900/5 ring-1 ring-slate-100 dark:ring-slate-700/30 overflow-hidden relative">
            <div class="absolute top-0 left-0 w-2 h-full bg-emerald-500"></div>
            <h2 class="text-xs font-black uppercase tracking-[0.2em] text-emerald-600 mb-10 pb-6 border-b border-slate-50 dark:border-slate-700 flex items-center gap-3">
                <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]"></span>
                Logistics & Temporal Grid
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 ml-1">Venue Details <span class="text-red-500">*</span></label>
                    <input type="text" name="venue" value="{{ old('venue') }}" placeholder="Ex: Hilton Berlin / Zoom Cloud" required
                           class="w-full px-6 py-4 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-bold text-slate-700 dark:text-white shadow-sm">
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 ml-1">City <span class="text-red-500">*</span></label>
                    <input type="text" name="city" value="{{ old('city') }}" placeholder="Berlin" required
                           class="w-full px-6 py-4 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-bold text-slate-700 dark:text-white shadow-sm">
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 ml-1">Country <span class="text-red-500">*</span></label>
                    <select name="country_id" required class="w-full px-6 py-4.5 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-bold text-slate-700 dark:text-white appearance-none cursor-pointer">
                        <option value="">Select Country</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 ml-1">Commencement Date <span class="text-red-500">*</span></label>
                    <input type="date" name="start_date" value="{{ old('start_date') }}" required
                           class="w-full px-6 py-4 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-bold text-slate-700 dark:text-white shadow-sm">
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 ml-1">Conclusion Date <span class="text-red-500">*</span></label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}" required
                           class="w-full px-6 py-4 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-bold text-slate-700 dark:text-white shadow-sm">
                </div>
            </div>
        </div>

        <!-- Section: Content & Links -->
        <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] p-10 shadow-2xl shadow-blue-900/5 ring-1 ring-slate-100 dark:ring-slate-700/30 overflow-hidden relative">
            <div class="absolute top-0 left-0 w-2 h-full bg-slate-900"></div>
            <h2 class="text-xs font-black uppercase tracking-[0.2em] text-slate-900 dark:text-white mb-10 pb-6 border-b border-slate-50 dark:border-slate-700 flex items-center gap-3">
                <span class="w-1.5 h-1.5 rounded-full bg-slate-900 dark:bg-white shadow-[0_0_10px_rgba(0,0,0,0.5)]"></span>
                In-Depth Narrative & Access
            </h2>

            <div class="space-y-10">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 ml-1">Full Description <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="8" placeholder="Elaborate on the scientific scope, call for papers, and participation criteria..." required
                              class="w-full px-8 py-6 rounded-[2rem] bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 focus:border-slate-900 dark:focus:border-white focus:ring-4 focus:ring-slate-900/5 transition-all font-medium text-slate-700 dark:text-white leading-relaxed">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 ml-1">Organizer Public Name <span class="text-red-500">*</span></label>
                        <input type="text" name="organizer_name" value="{{ old('organizer_name') }}" placeholder="Organization or Institution" required
                               class="w-full px-6 py-4 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 focus:ring-4 focus:ring-slate-900/5 font-bold text-slate-700 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 ml-1">External Portal Link (URL)</label>
                        <input type="url" name="external_link" value="{{ old('external_link') }}" placeholder="https://conference-site.org"
                               class="w-full px-6 py-4 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 focus:ring-4 focus:ring-slate-900/5 font-bold text-slate-700 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 ml-1">Early Bird Cut-off</label>
                        <input type="date" name="early_bird_deadline" value="{{ old('early_bird_deadline') }}"
                               class="w-full px-6 py-4 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 font-bold text-slate-700 dark:text-white">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-6 pt-10 border-t border-slate-100 dark:border-gray-700">
            <button type="reset" class="px-8 py-4 text-xs font-black uppercase tracking-widest text-slate-400 hover:text-slate-900 transition-colors">Discard Draft</button>
            <button type="submit" class="group/btn relative px-12 py-5 bg-[#0f172a] hover:bg-blue-600 text-white font-black rounded-2xl shadow-2xl transition-all duration-500 hover:-translate-y-1 active:scale-95 uppercase tracking-[0.2em] text-[11px] flex items-center gap-3">
                Inject Registry Port
                <svg class="w-5 h-5 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </form>
</div>
@endsection
