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

                <!-- Date of Conference: Start Date -->
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-6">Conference Start Date <span class="text-blue-500">*</span></label>
                    <input type="date" name="start_date" value="{{ old('start_date', $conference->start_date ? $conference->start_date->format('Y-m-d') : '') }}" required
                           class="w-full px-8 py-5 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 transition-all font-bold text-slate-800 dark:text-white appearance-none">
                    @error('start_date') <p class="mt-4 text-[11px] font-black text-red-500 italic">{{ $message }}</p> @enderror
                </div>

                <!-- Date of Conference: End Date -->
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-6">Conference End Date <span class="text-blue-500">*</span></label>
                    <input type="date" name="end_date" value="{{ old('end_date', $conference->end_date ? $conference->end_date->format('Y-m-d') : '') }}" required
                           class="w-full px-8 py-5 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 transition-all font-bold text-slate-800 dark:text-white appearance-none">
                    @error('end_date') <p class="mt-4 text-[11px] font-black text-red-500 italic">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-1" x-data="{
                    previewUrl: '{{ $conference->banner_url ?? '' }}',
                    existingUrl: '{{ $conference->banner_url ?? '' }}',
                    fileName: '',
                    fileSize: '',
                    isNew: false,
                    hasError: false,
                    handleFileSelect(event) {
                        const file = event.target.files[0];
                        if (file) {
                            if (file.size > 2 * 1024 * 1024) {
                                alert('File size exceeds 2MB limit.');
                                event.target.value = '';
                                return;
                            }
                            this.previewUrl = URL.createObjectURL(file);
                            this.fileName = file.name;
                            this.fileSize = (file.size / 1024).toFixed(0) + ' KB';
                            this.isNew = true;
                            this.hasError = false;
                        }
                    },
                    clearNewFile(inputEl) {
                        if (inputEl) inputEl.value = '';
                        this.previewUrl = this.existingUrl;
                        this.fileName = '';
                        this.fileSize = '';
                        this.isNew = false;
                        this.hasError = false;
                    }
                }">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-6">Visual Banner (Optional)</label>
                    <div class="flex flex-col gap-4">
                        <template x-if="previewUrl && !hasError">
                            <div class="w-full h-32 rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-700 relative flex-shrink-0 shadow-md">
                                <img :src="previewUrl" x-on:error="hasError = true" class="w-full h-full object-cover">
                                <div class="absolute bottom-2 left-2 bg-slate-900/80 text-white text-[9px] font-black uppercase px-2 py-0.5 rounded" x-text="isNew ? 'New' : 'Current'"></div>
                                <template x-if="isNew">
                                    <button type="button" @click="clearNewFile($refs.orgBannerInput)" class="absolute top-2 right-2 p-1 bg-red-600 hover:bg-red-700 text-white rounded-full shadow">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </template>
                            </div>
                        </template>
                        <div class="relative group/zone h-32 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-dashed border-slate-200 dark:border-gray-600 flex flex-col items-center justify-center cursor-pointer hover:border-blue-400 hover:bg-white dark:hover:bg-gray-700 transition-all p-4 text-center">
                            <input type="file" name="banner_image" x-ref="orgBannerInput" @change="handleFileSelect($event)" class="absolute inset-0 opacity-0 cursor-pointer overflow-hidden z-10" accept="image/*">
                            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 group-hover/zone:text-blue-600 transition-all text-center" x-text="previewUrl ? 'Replace Banner' : 'Upload Banner'"></span>
                            <template x-if="fileName">
                                <span class="text-xs font-bold text-emerald-600 mt-1" x-text="fileName + ' (' + fileSize + ')'"></span>
                            </template>
                            <template x-if="!fileName">
                                <span class="text-[9px] text-slate-400 mt-1 font-semibold">PNG, JPG, WEBP up to 2MB</span>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Brochure Document Upload & Preview -->
                <div class="md:col-span-1" x-data="{
                    fileName: '',
                    fileSize: '',
                    handleFileSelect(event) {
                        const file = event.target.files[0];
                        if (file) {
                            if (file.size > 20 * 1024 * 1024) {
                                alert('File size exceeds 20MB limit.');
                                event.target.value = '';
                                return;
                            }
                            this.fileName = file.name;
                            this.fileSize = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
                        }
                    },
                    clearFile(inputEl) {
                        if (inputEl) inputEl.value = '';
                        this.fileName = '';
                        this.fileSize = '';
                    }
                }">
                    <div class="flex items-center justify-between mb-6">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400">Brochure Document (PDF)</label>
                        @if($conference->brochure_url)
                            <a href="{{ $conference->brochure_url }}" target="_blank" class="text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:underline flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Current PDF
                            </a>
                        @endif
                    </div>
                    <div class="flex flex-col gap-4">
                        <template x-if="fileName">
                            <div class="flex items-center justify-between w-full h-32 px-5 py-3 bg-emerald-50 dark:bg-emerald-950/40 border-2 border-emerald-200 dark:border-emerald-800 rounded-2xl relative">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center flex-shrink-0 shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div class="text-left min-w-0">
                                        <span class="inline-block px-1.5 py-0.5 bg-emerald-100 dark:bg-emerald-900 text-emerald-700 dark:text-emerald-300 rounded text-[9px] font-black uppercase mb-0.5">New Document</span>
                                        <p class="text-xs font-bold text-slate-800 dark:text-white truncate" x-text="fileName"></p>
                                        <p class="text-[10px] text-emerald-600 font-semibold" x-text="fileSize"></p>
                                    </div>
                                </div>
                                <button type="button" @click="clearFile($refs.orgEditBrochureInput)" class="p-1.5 bg-red-100 hover:bg-red-200 text-red-600 rounded-full transition-all flex-shrink-0" title="Remove brochure">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </template>
                        <template x-if="!fileName">
                            <div class="relative group/zone h-32 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-dashed border-slate-200 dark:border-gray-600 flex flex-col items-center justify-center cursor-pointer hover:border-emerald-400 hover:bg-white dark:hover:bg-gray-700 transition-all p-4 text-center">
                                <input type="file" name="brochure_file" x-ref="orgEditBrochureInput" @change="handleFileSelect($event)" class="absolute inset-0 opacity-0 cursor-pointer overflow-hidden z-10" accept=".pdf,.doc,.docx,application/pdf">
                                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 group-hover/zone:text-emerald-600 transition-all text-center">{{ $conference->brochure_file ? 'Replace Current PDF' : 'Upload Brochure PDF' }}</span>
                                <span class="text-[9px] text-slate-400 mt-1 font-semibold">PDF, DOC, DOCX up to 20MB</span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fieldset 02: Logistics & Coordinates -->
        <div class="bg-white dark:bg-gray-800 rounded-[3rem] p-10 md:p-14 shadow-sm border border-slate-100 dark:border-slate-700/30 overflow-hidden group">
            <h2 class="text-[10px] font-black uppercase tracking-[0.3em] text-emerald-500 mb-12">Venue & Coordinates</h2>
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
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-[3rem] p-10 md:p-14 shadow-sm border border-slate-100 dark:border-slate-700/30 space-y-10">
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-8">Narrative Abstract Registry <span class="text-red-500">*</span></label>
                <textarea name="description" rows="10" required
                          class="w-full px-10 py-10 rounded-[3rem] bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 focus:border-blue-500 transition-all text-slate-700 dark:text-gray-200 font-medium leading-[2.2] shadow-sm">{{ old('description', $conference->description) }}</textarea>
                @error('description') <p class="mt-2 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-6">Host Entity Designation <span class="text-red-500">*</span></label>
                    <input type="text" name="organizer_name" value="{{ old('organizer_name', $conference->organizer_name) }}" placeholder="Organization or Lab Name" required
                           class="w-full px-8 py-5 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 transition-all font-bold text-slate-700 dark:text-white">
                    @error('organizer_name') <p class="mt-2 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-6">Portal Access (External URL)</label>
                    <input type="url" name="external_link" value="{{ old('external_link', $conference->external_link) }}" placeholder="https://portal-link.com"
                           class="w-full px-8 py-5 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 transition-all font-bold text-slate-700 dark:text-white">
                    @error('external_link') <p class="mt-2 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-6">Contact Email</label>
                    <input type="email" name="contact_email" value="{{ old('contact_email', $conference->contact_email) }}" placeholder="contact@conference.org"
                           class="w-full px-8 py-5 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 transition-all font-bold text-slate-700 dark:text-white">
                    @error('contact_email') <p class="mt-2 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-6">Contact No. / Phone</label>
                    <input type="text" name="contact_phone" value="{{ old('contact_phone', $conference->contact_phone) }}" placeholder="+1 (555) 000-0000"
                           class="w-full px-8 py-5 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 transition-all font-bold text-slate-700 dark:text-white">
                    @error('contact_phone') <p class="mt-2 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                </div>
            </div>
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
