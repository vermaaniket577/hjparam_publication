@extends('layouts.admin')
@section('title', 'Submit New Conference')
@section('breadcrumb', 'Conferences / New Entry')

@section('content')
<div class="max-w-4xl mx-auto space-y-10 pb-32 animate-fade-in-up">
    <div class="flex items-center justify-between mb-8 pb-8 border-b border-slate-100">
        <div>
            <h1 class="text-3xl md:text-5xl font-serif font-black text-[#0f172a] mb-4 tracking-tight leading-none">Journal <span class="text-blue-500 italic">& Event</span> Protocol</h1>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_15px_rgba(16,185,129,0.5)]"></span>
                Public Submission Portal
            </p>
        </div>
        <a href="{{ route('organizer.conferences.index') }}" class="px-8 py-3.5 bg-white border-2 border-slate-100 dark:border-slate-700/50 rounded-2xl text-[10px] font-black uppercase tracking-widest text-slate-500 hover:border-blue-500 hover:text-blue-600 dark:bg-gray-800 transition-all duration-500 flex items-center gap-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Cancel Protocol
        </a>
    </div>

    <!-- Instructions Alert -->
    <div class="bg-blue-600 rounded-[2rem] p-8 md:p-10 shadow-2xl shadow-blue-900/10 text-white relative overflow-hidden flex flex-col md:flex-row items-center gap-8">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
        <div class="w-20 h-20 bg-white/10 rounded-3xl flex items-center justify-center flex-shrink-0 animate-pulse relative z-10 border border-white/20">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="relative z-10">
            <h3 class="text-lg font-black uppercase tracking-widest mb-3">Submission Directive</h3>
            <p class="text-white/70 text-sm leading-relaxed font-medium">Your entry will be subjected to our verification protocol. Approved events are Broadcasted Live across our global network within 24-48 hours. Ensure maximum precision in venue and temporal data.</p>
        </div>
    </div>

    <form action="{{ route('organizer.conferences.store') }}" method="POST" enctype="multipart/form-data" class="space-y-12">
        @csrf
        
        <!-- Fieldset 01: Context -->
        <div class="bg-white dark:bg-gray-800 rounded-[3rem] p-10 md:p-14 shadow-sm border border-slate-100 dark:border-slate-700/30 overflow-hidden group">
            <h2 class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 mb-12 flex items-center gap-4">
                <span class="w-1.5 h-1.5 bg-blue-600 rounded-full group-hover:scale-150 transition-all"></span>
                Strategic Event Identity
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-x-14 md:gap-y-12">
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-6 flex items-center justify-between">
                        Conference Designation <span class="text-blue-500 uppercase tracking-normal">Mandatory Terminal*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="Ex: 5th Annual Summit on Distributed Ledger Protocols" required
                           class="w-full px-8 py-5 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 focus:border-blue-500 focus:ring-8 focus:ring-blue-500/5 transition-all text-slate-800 dark:text-white font-bold placeholder-slate-300">
                    @error('title') <p class="mt-4 text-[11px] font-black text-red-500 italic underline decoration-red-100 decoration-4 underline-offset-8">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-6">Taxonomy / Core Field</label>
                    <select name="category_id" required class="w-full px-8 py-5 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 focus:border-blue-500 transition-all appearance-none cursor-pointer font-bold text-slate-700 dark:text-gray-200">
                        <option value="">Select Domain</option>
                        @foreach($topics as $topic)
                            <option value="{{ $topic->id }}" {{ old('category_id') == $topic->id ? 'selected' : '' }}>{{ $topic->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-6">Interaction Format</label>
                    <select name="type" required class="w-full px-8 py-5 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 focus:border-blue-500 transition-all font-bold appearance-none cursor-pointer text-slate-700 dark:text-gray-200">
                        <option value="offline" {{ old('type') == 'offline' ? 'selected' : '' }}>Static / Physical Venue</option>
                        <option value="online" {{ old('type') == 'online' ? 'selected' : '' }}>Virtual / Digital Interface</option>
                        <option value="hybrid" {{ old('type') == 'hybrid' ? 'selected' : '' }}>Hybrid / Dual Entry</option>
                    </select>
                </div>

                <!-- Date of Conference: Start Date -->
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-6">Conference Start Date <span class="text-blue-500">*</span></label>
                    <input type="date" name="start_date" value="{{ old('start_date') }}" required
                           class="w-full px-8 py-5 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 focus:border-blue-500 transition-all font-bold text-slate-800 dark:text-white appearance-none">
                    @error('start_date') <p class="mt-4 text-[11px] font-black text-red-500 italic">{{ $message }}</p> @enderror
                </div>

                <!-- Date of Conference: End Date -->
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-6">Conference End Date <span class="text-blue-500">*</span></label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}" required
                           class="w-full px-8 py-5 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 focus:border-blue-500 transition-all font-bold text-slate-800 dark:text-white appearance-none">
                    @error('end_date') <p class="mt-4 text-[11px] font-black text-red-500 italic">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-1" x-data="{
                    previewUrl: '',
                    fileName: '',
                    fileSize: '',
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
                            this.hasError = false;
                        }
                    },
                    clearFile(inputEl) {
                        if (inputEl) inputEl.value = '';
                        this.previewUrl = '';
                        this.fileName = '';
                        this.fileSize = '';
                        this.hasError = false;
                    }
                }">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-6">Visual Banner (Optional)</label>
                    <div class="flex flex-col gap-4">
                        <template x-if="previewUrl && !hasError">
                            <div class="w-full h-32 rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-700 relative flex-shrink-0 shadow-md">
                                <img :src="previewUrl" x-on:error="hasError = true" class="w-full h-full object-cover">
                                <button type="button" @click="clearFile($refs.orgCreateBannerInput)" class="absolute top-2 right-2 p-1 bg-red-600 hover:bg-red-700 text-white rounded-full shadow">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </template>
                        <div class="relative group/zone h-32 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-dashed border-slate-200 dark:border-gray-600 flex flex-col items-center justify-center cursor-pointer hover:border-blue-400 hover:bg-white dark:hover:bg-gray-700 transition-all p-4 text-center">
                            <input type="file" name="banner_image" x-ref="orgCreateBannerInput" @change="handleFileSelect($event)" class="absolute inset-0 opacity-0 cursor-pointer overflow-hidden z-10" accept="image/*">
                            <div class="w-9 h-9 bg-white dark:bg-slate-800 text-slate-400 rounded-xl flex items-center justify-center mb-1.5 shadow-xs group-hover/zone:scale-110 group-hover/zone:bg-blue-600 group-hover/zone:text-white transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span class="text-[9px] font-black uppercase tracking-[0.15em] text-slate-500 group-hover/zone:text-blue-600 transition-all" x-text="previewUrl ? 'Change Banner' : 'Select Banner (Max 2MB)'"></span>
                            <template x-if="fileName">
                                <span class="text-xs font-bold text-emerald-600 mt-0.5 truncate max-w-full" x-text="fileName + ' (' + fileSize + ')'"></span>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Brochure Document Upload -->
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
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-6">Brochure Document (PDF / Optional)</label>
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
                                        <span class="inline-block px-1.5 py-0.5 bg-emerald-100 dark:bg-emerald-900 text-emerald-700 dark:text-emerald-300 rounded text-[9px] font-black uppercase mb-0.5">Ready to Upload</span>
                                        <p class="text-xs font-bold text-slate-800 dark:text-white truncate" x-text="fileName"></p>
                                        <p class="text-[10px] text-emerald-600 font-semibold" x-text="fileSize"></p>
                                    </div>
                                </div>
                                <button type="button" @click="clearFile($refs.orgCreateBrochureInput)" class="p-1.5 bg-red-100 hover:bg-red-200 text-red-600 rounded-full transition-all flex-shrink-0" title="Remove brochure">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </template>
                        <template x-if="!fileName">
                            <div class="relative group/zone h-32 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-dashed border-slate-200 dark:border-gray-600 flex flex-col items-center justify-center cursor-pointer hover:border-emerald-400 hover:bg-white dark:hover:bg-gray-700 transition-all p-4 text-center">
                                <input type="file" name="brochure_file" x-ref="orgCreateBrochureInput" @change="handleFileSelect($event)" class="absolute inset-0 opacity-0 cursor-pointer overflow-hidden z-10" accept=".pdf,.doc,.docx,application/pdf">
                                <div class="w-9 h-9 bg-white dark:bg-slate-800 text-slate-400 rounded-xl flex items-center justify-center mb-1.5 shadow-xs group-hover/zone:scale-110 group-hover/zone:bg-emerald-600 group-hover/zone:text-white transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <span class="text-[9px] font-black uppercase tracking-[0.15em] text-slate-500 group-hover/zone:text-emerald-600 transition-all">Upload Brochure PDF (Max 20MB)</span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fieldset 02: Logistics & Coordinates -->
        <div class="bg-white dark:bg-gray-800 rounded-[3rem] p-10 md:p-14 shadow-sm border border-slate-100 dark:border-slate-700/30 overflow-hidden group">
            <h2 class="text-[10px] font-black uppercase tracking-[0.3em] text-emerald-500 mb-12 flex items-center gap-4">
                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full group-hover:scale-150 transition-all"></span>
                Venue & Territory Coordinates
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-x-14 md:gap-y-12">
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-6">Venue Protocol (Full Address/URL)</label>
                    <input type="text" name="venue" value="{{ old('venue') }}" placeholder="Ex: Grand Marina Hall, South San Francisco / Zoom encrypted link" required
                           class="w-full px-8 py-5 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 focus:border-emerald-500 focus:ring-8 focus:ring-emerald-500/5 transition-all text-slate-800 dark:text-white font-bold shadow-sm">
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-6">Geo-Terminal (City)</label>
                    <input type="text" name="city" value="{{ old('city') }}" placeholder="San Francisco" required
                           class="w-full px-8 py-5 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 focus:border-emerald-500 focus:ring-8 focus:ring-emerald-500/5 transition-all text-slate-800 dark:text-white font-bold">
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-6">National Registry (Country)</label>
                    <select name="country_id" required class="w-full px-8 py-5 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 focus:border-emerald-500 transition-all font-bold appearance-none cursor-pointer text-slate-700 dark:text-gray-200">
                        <option value="">Select Territory</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Fieldset 03: Narrative -->
        <div class="bg-white dark:bg-gray-800 rounded-[3rem] p-10 md:p-14 shadow-sm border border-slate-100 dark:border-slate-700/30 group">
            <h2 class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 mb-12 flex items-center gap-4">
                <span class="w-1.5 h-1.5 bg-[#0f172a] dark:bg-white rounded-full group-hover:scale-150 transition-all"></span>
                Extended Intelligence (Description)
            </h2>
            <div class="space-y-12">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-6">Narrative Abstract / Full Scope</label>
                    <textarea name="description" rows="12" placeholder="Elaborate on the scientific methodology, session details, and global impact of this event..." required
                              class="w-full px-10 py-8 rounded-[2.5rem] bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 focus:border-[#0f172a] dark:focus:border-white focus:ring-8 focus:ring-[#0f172a]/5 transition-all text-slate-700 dark:text-gray-200 font-medium leading-loose shadow-sm">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-6">Host Entity Designation <span class="text-red-500">*</span></label>
                        <input type="text" name="organizer_name" value="{{ old('organizer_name') }}" placeholder="Organization or Lab Name" required
                               class="w-full px-8 py-5 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 transition-all font-bold text-slate-700 dark:text-white">
                        @error('organizer_name') <p class="mt-2 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-6">Portal Access (External URL)</label>
                        <input type="url" name="external_link" value="{{ old('external_link') }}" placeholder="https://portal-link.com"
                               class="w-full px-8 py-5 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 transition-all font-bold text-slate-700 dark:text-white">
                        @error('external_link') <p class="mt-2 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-6">Contact Email</label>
                        <input type="email" name="contact_email" value="{{ old('contact_email') }}" placeholder="contact@conference.org"
                               class="w-full px-8 py-5 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 transition-all font-bold text-slate-700 dark:text-white">
                        @error('contact_email') <p class="mt-2 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-6">Contact No. / Phone</label>
                        <input type="text" name="contact_phone" value="{{ old('contact_phone') }}" placeholder="+1 (555) 000-0000"
                               class="w-full px-8 py-5 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border-2 border-slate-100 dark:border-gray-600 transition-all font-bold text-slate-700 dark:text-white">
                        @error('contact_phone') <p class="mt-2 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-10 flex items-center justify-end gap-10">
            <button type="reset" class="text-[10px] font-black uppercase tracking-[0.4em] text-slate-400 hover:text-red-500 transition-colors">Wipe Form Data</button>
            <button type="submit" class="group/launch relative px-14 py-6 bg-[#0f172a] hover:bg-blue-600 text-white font-black rounded-[2rem] shadow-2xl transition-all duration-700 hover:-translate-y-2 active:scale-95 uppercase tracking-[0.3em] text-[11px] flex items-center gap-4 overflow-hidden">
                <span class="absolute inset-0 bg-white/5 skew-x-12 translate-x-full group-hover/launch:-translate-x-full transition-all duration-1000"></span>
                Commit to Registry
                <svg class="w-6 h-6 animate-pulse group-hover/launch:translate-x-2 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7-7 7M5 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </form>
</div>
@endsection
