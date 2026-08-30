@extends('layouts.admin')
@section('title', 'Create New Conference')
@section('breadcrumb', 'Conferences / Create')

@section('content')
<div class="max-w-5xl mx-auto space-y-8 pb-24" x-data="conferenceFormHandler()">
    
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight flex items-center gap-2.5">
                <span class="w-3.5 h-3.5 rounded-full bg-blue-600 shadow-sm shadow-blue-500/50"></span>
                Create New Conference
            </h1>
            <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-1">Register an academic conference with complete event, submission, fee, committee, and schedule configuration</p>
        </div>
        <a href="{{ route('admin.conferences.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all shadow-sm">
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Conferences
        </a>
    </div>

    @if ($errors->any())
        <div class="p-4 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800/60 rounded-2xl">
            <div class="flex items-center gap-2 text-red-700 dark:text-red-400 font-bold text-xs mb-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Please correct the following errors:
            </div>
            <ul class="list-disc list-inside text-xs text-red-600 dark:text-red-400 space-y-0.5 ml-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.conferences.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        
        <!-- ========================================== -->
        <!-- SECTION 1: FORM (EVENT CORE & DETAILS)   -->
        <!-- ========================================== -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 md:p-8 border border-slate-200/80 dark:border-slate-700 shadow-sm space-y-6">
            <div class="border-b border-slate-100 dark:border-slate-700/80 pb-4">
                <h2 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                    1. Conference Form & Narrative Details
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Core title, organizers, scope, paper formats, and academic categories</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title of Conference -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">
                        Title of Conference <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="Ex: International Conference on Advance Computing & Emerging Technologies 2026" required
                           class="w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all font-medium text-slate-900 dark:text-white text-sm">
                </div>

                <!-- Organizers -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">
                        Organizers / Organizing Body <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="organizer_name" value="{{ old('organizer_name') }}" placeholder="Ex: Global Research Association & IRAJ" required
                           class="w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all font-medium text-slate-900 dark:text-white text-sm">
                </div>

                <!-- Event Format / Mode -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">
                        Event Mode / Format <span class="text-red-500">*</span>
                    </label>
                    <select name="type" required class="w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all font-medium text-slate-900 dark:text-white text-sm cursor-pointer">
                        <option value="offline" {{ old('type') == 'offline' ? 'selected' : '' }}>Offline (In-Person / Physical)</option>
                        <option value="online" {{ old('type') == 'online' ? 'selected' : '' }}>Online (Virtual Web Conference)</option>
                        <option value="hybrid" {{ old('type') == 'hybrid' ? 'selected' : '' }}>Hybrid (Physical + Virtual)</option>
                    </select>
                </div>

                <!-- Country & City -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">Country <span class="text-red-500">*</span></label>
                    <select name="country_id" required class="w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all font-medium text-slate-900 dark:text-white text-sm cursor-pointer">
                        <option value="">Select Host Country</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">City / Venue Location</label>
                    <input type="text" name="city" value="{{ old('city') }}" placeholder="Ex: London / Singapore / New Delhi"
                           class="w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all font-medium text-slate-900 dark:text-white text-sm">
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">
                        Description <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" rows="5" placeholder="Provide background, themes, objectives, call for papers overview, and welcoming remarks..." required
                              class="w-full px-4 py-3 rounded-xl bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all font-medium text-slate-900 dark:text-white text-sm leading-relaxed">{{ old('description') }}</textarea>
                </div>

                <!-- Aim & Scope -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">
                        Aim & Scope
                    </label>
                    <textarea name="aim_scope" rows="4" placeholder="Detail the specific research topics, scientific aims, target audience, and academic disciplines covered..."
                              class="w-full px-4 py-3 rounded-xl bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all font-medium text-slate-900 dark:text-white text-sm leading-relaxed">{{ old('aim_scope') }}</textarea>
                </div>

                <!-- Guidelines -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">
                        Guidelines
                    </label>
                    <textarea name="guidelines" rows="4" placeholder="Author instructions, formatting guidelines, presentation rules, ethical guidelines, and publication terms..."
                              class="w-full px-4 py-3 rounded-xl bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all font-medium text-slate-900 dark:text-white text-sm leading-relaxed">{{ old('guidelines') }}</textarea>
                </div>

                <!-- Upload Paper Format – 2 (Format 1 & Format 2) -->
                <div class="md:col-span-2 bg-slate-50 dark:bg-slate-700/30 rounded-2xl p-5 border border-slate-200/80 dark:border-slate-600 space-y-4">
                    <div>
                        <h3 class="text-xs font-bold text-slate-800 dark:text-white uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            Upload Paper Format (2 Files)
                        </h3>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Upload downloadable templates for authors (e.g. DOCX / PDF Template 1 and Template 2)</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Paper Format 1 -->
                        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-600">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Paper Format Template 1</label>
                            <input type="file" name="paper_format_1" accept=".doc,.docx,.pdf"
                                   class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                            <p class="text-[10px] text-slate-400 mt-1">DOCX, DOC, PDF up to 25MB</p>
                        </div>

                        <!-- Paper Format 2 -->
                        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-600">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Paper Format Template 2</label>
                            <input type="file" name="paper_format_2" accept=".doc,.docx,.pdf"
                                   class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                            <p class="text-[10px] text-slate-400 mt-1">DOCX, DOC, PDF up to 25MB</p>
                        </div>
                    </div>
                </div>

                <!-- Fee Structure -->
                <div class="md:col-span-2 bg-gradient-to-br from-slate-50 to-blue-50/40 dark:from-slate-700/30 dark:to-slate-800/40 rounded-2xl p-5 border border-slate-200/80 dark:border-slate-600 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xs font-bold text-slate-800 dark:text-white uppercase tracking-wider flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Registration Fee Structure
                            </h3>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Specify standard registration fee amounts and add custom fee tiers</p>
                        </div>
                        <button type="button" @click="addFeeItem()" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold shadow-xs transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            Add Fee Item
                        </button>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Only Attendee Fee</label>
                            <input type="text" name="fee_attendee" value="{{ old('fee_attendee', '$100 / ₹2,500') }}" placeholder="Ex: $150 / ₹3,000"
                                   class="w-full px-3.5 py-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 text-xs font-bold text-slate-800 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Presentation Fee</label>
                            <input type="text" name="fee_presentation" value="{{ old('fee_presentation', '$200 / ₹4,500') }}" placeholder="Ex: $250 / ₹5,000"
                                   class="w-full px-3.5 py-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 text-xs font-bold text-slate-800 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Publication Fee</label>
                            <input type="text" name="fee_publication" value="{{ old('fee_publication', '$300 / ₹7,000') }}" placeholder="Ex: $350 / ₹8,000"
                                   class="w-full px-3.5 py-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 text-xs font-bold text-slate-800 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Extra Certificate Fee</label>
                            <input type="text" name="fee_extra_certificate" value="{{ old('fee_extra_certificate', '$30 / ₹800') }}" placeholder="Ex: $40 / ₹1,000"
                                   class="w-full px-3.5 py-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 text-xs font-bold text-slate-800 dark:text-white">
                        </div>
                    </div>

                    <!-- Dynamic Custom Fees Repeater -->
                    <template x-if="customFees.length > 0">
                        <div class="space-y-3 pt-3 border-t border-slate-200/80 dark:border-slate-700">
                            <span class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wide">Custom Additional Fee Tiers:</span>
                            <div class="space-y-2.5">
                                <template x-for="(fee, index) in customFees" :key="index">
                                    <div class="flex items-center gap-3 bg-white dark:bg-slate-800 p-3 rounded-xl border border-slate-200 dark:border-slate-600">
                                        <div class="flex-1">
                                            <input type="text" :name="'custom_fees[' + index + '][name]'" x-model="fee.name" placeholder="Fee Tier Name (e.g. Student Attendee / Co-Author)"
                                                   class="w-full px-3 py-1.5 rounded-lg bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-xs font-medium dark:text-white">
                                        </div>
                                        <div class="w-48">
                                            <input type="text" :name="'custom_fees[' + index + '][amount]'" x-model="fee.amount" placeholder="Amount (e.g. $80 / ₹2,000)"
                                                   class="w-full px-3 py-1.5 rounded-lg bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-xs font-bold text-emerald-600 dark:text-emerald-400">
                                        </div>
                                        <button type="button" @click="removeFeeItem(index)" class="p-1.5 text-slate-400 hover:text-red-500 rounded-lg hover:bg-red-50 dark:hover:bg-red-950 transition-colors" title="Delete fee tier">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- SECTION 2: COMMITTEE MEMBERS REPEATER      -->
        <!-- ========================================== -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 md:p-8 border border-slate-200/80 dark:border-slate-700 shadow-sm space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-slate-100 dark:border-slate-700/80 pb-4">
                <div>
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                        2. Committee Members
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Add organizing committee, honorary chairs, keynote speakers, and session leads</p>
                </div>
                <button type="button" @click="addCommitteeMember()" class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-sm shadow-indigo-600/20 transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Add Member
                </button>
            </div>

            <div class="space-y-3">
                <template x-for="(member, index) in committeeMembers" :key="index">
                    <div class="bg-slate-50 dark:bg-slate-700/40 p-4 rounded-xl border border-slate-200/80 dark:border-slate-600/80 relative group">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[11px] font-extrabold uppercase tracking-wider text-indigo-600 dark:text-indigo-400" x-text="'Member #' + (index + 1)"></span>
                            <button type="button" @click="removeCommitteeMember(index)" class="text-slate-400 hover:text-red-500 text-xs font-semibold flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Remove
                            </button>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Name <span class="text-red-500">*</span></label>
                                <input type="text" :name="'committee_members[' + index + '][name]'" x-model="member.name" placeholder="Dr. John Doe / Prof. Jane Smith" required
                                       class="w-full px-3 py-2 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 text-xs font-medium dark:text-white">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Role <span class="text-red-500">*</span></label>
                                <input type="text" :name="'committee_members[' + index + '][role]'" x-model="member.role" placeholder="Conference Chair / Co-Chair / Keynote Speaker" required
                                       class="w-full px-3 py-2 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 text-xs font-medium dark:text-white">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Affiliation / Institution</label>
                                <input type="text" :name="'committee_members[' + index + '][affiliation]'" x-model="member.affiliation" placeholder="University of Oxford / MIT"
                                       class="w-full px-3 py-2 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 text-xs font-medium dark:text-white">
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- SECTION 3: UPLOAD (BANNER & BROCHURE)     -->
        <!-- ========================================== -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 md:p-8 border border-slate-200/80 dark:border-slate-700 shadow-sm space-y-6">
            <div class="border-b border-slate-100 dark:border-slate-700/80 pb-4">
                <h2 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                    3. Media & Document Uploads
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Upload high-resolution conference banner and downloadable official brochure</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Upload Banner -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">Upload Banner Image</label>
                    <div class="border-2 border-dashed border-slate-200 dark:border-slate-600 rounded-2xl p-5 text-center bg-slate-50/50 dark:bg-slate-700/30 hover:border-blue-500 transition-all">
                        <input type="file" name="banner_image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                        <p class="text-[10px] text-slate-400 mt-2">Recommended: 1920x800 PNG, JPG, WEBP up to 5MB</p>
                    </div>
                </div>

                <!-- Upload Brochure -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">Upload Brochure (PDF)</label>
                    <div class="border-2 border-dashed border-slate-200 dark:border-slate-600 rounded-2xl p-5 text-center bg-slate-50/50 dark:bg-slate-700/30 hover:border-emerald-500 transition-all">
                        <input type="file" name="brochure_file" accept=".pdf,.doc,.docx" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                        <p class="text-[10px] text-slate-400 mt-2">PDF, DOC, DOCX up to 25MB</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- SECTION 4: DATES (ABSTRACT & FULL PAPER)   -->
        <!-- ========================================== -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 md:p-8 border border-slate-200/80 dark:border-slate-700 shadow-sm space-y-6">
            <div class="border-b border-slate-100 dark:border-slate-700/80 pb-4">
                <h2 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    4. Submission Dates & Deadlines
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Set Abstract and Full Paper Submission start and end date ranges</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Abstract Submission Date Box -->
                <div class="bg-slate-50 dark:bg-slate-700/40 p-4 rounded-xl border border-slate-200/80 dark:border-slate-600 space-y-3">
                    <span class="block text-xs font-bold text-slate-800 dark:text-white uppercase tracking-wider">Abstract Submission Date</span>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 mb-1">Start Date</label>
                            <input type="date" name="abstract_submission_start_date" value="{{ old('abstract_submission_start_date') }}"
                                   class="w-full px-3 py-2 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 text-xs font-medium dark:text-white">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 mb-1">End Date</label>
                            <input type="date" name="abstract_submission_end_date" value="{{ old('abstract_submission_end_date') }}"
                                   class="w-full px-3 py-2 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 text-xs font-medium dark:text-white">
                        </div>
                    </div>
                </div>

                <!-- Full Paper Submission Date Box -->
                <div class="bg-slate-50 dark:bg-slate-700/40 p-4 rounded-xl border border-slate-200/80 dark:border-slate-600 space-y-3">
                    <span class="block text-xs font-bold text-slate-800 dark:text-white uppercase tracking-wider">Full Paper Submission Date</span>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 mb-1">Start Date</label>
                            <input type="date" name="paper_submission_start_date" value="{{ old('paper_submission_start_date') }}"
                                   class="w-full px-3 py-2 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 text-xs font-medium dark:text-white">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 mb-1">End Date</label>
                            <input type="date" name="paper_submission_end_date" value="{{ old('paper_submission_end_date') }}"
                                   class="w-full px-3 py-2 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 text-xs font-medium dark:text-white">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- SECTION 5: REGISTRATION (FEE PAYMENT DATES)-->
        <!-- ========================================== -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 md:p-8 border border-slate-200/80 dark:border-slate-700 shadow-sm space-y-6">
            <div class="border-b border-slate-100 dark:border-slate-700/80 pb-4">
                <h2 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-purple-600"></span>
                    5. Registration Fee Payment Window
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Set the registration fee payment start and end dates</p>
            </div>

            <div class="bg-slate-50 dark:bg-slate-700/40 p-4 rounded-xl border border-slate-200/80 dark:border-slate-600">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Registration Fee Payment Start Date</label>
                        <input type="date" name="registration_start_date" value="{{ old('registration_start_date') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 text-xs font-medium dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Registration Fee Payment End Date</label>
                        <input type="date" name="registration_end_date" value="{{ old('registration_end_date') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 text-xs font-medium dark:text-white">
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- SECTION 6: CONFERENCE (CONFERENCE DATES)   -->
        <!-- ========================================== -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 md:p-8 border border-slate-200/80 dark:border-slate-700 shadow-sm space-y-6">
            <div class="border-b border-slate-100 dark:border-slate-700/80 pb-4">
                <h2 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-rose-600"></span>
                    6. Conference Event Dates
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">The live execution dates of the conference</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">Conference Start Date <span class="text-red-500">*</span></label>
                    <input type="date" name="start_date" value="{{ old('start_date') }}" required
                           class="w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-sm font-medium dark:text-white">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">Conference End Date <span class="text-red-500">*</span></label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}" required
                           class="w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-sm font-medium dark:text-white">
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- SECTION 7: VENUE, MEETING, PUBLICATION & TEMPLATES -->
        <!-- ========================================== -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 md:p-8 border border-slate-200/80 dark:border-slate-700 shadow-sm space-y-6">
            <div class="border-b border-slate-100 dark:border-slate-700/80 pb-4">
                <h2 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                    7. Venue Details, Meeting Link, Publication & Templates
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Specify conference venue, online meeting link, publication indexing info, and download templates</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Venue Details -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">Venue Details / Physical Location</label>
                    <textarea name="venue_details" rows="3" placeholder="e.g. Auditorium Hall A, University Campus, Building 4, Conference Center..."
                              class="w-full px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-xs font-medium dark:text-white focus:bg-white">{{ old('venue_details') }}</textarea>
                </div>

                <!-- Online Meeting Link -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">Online – Conf. Meeting Link</label>
                    <input type="url" name="meeting_link" value="{{ old('meeting_link') }}" placeholder="https://meet.google.com/xyz or Zoom / Teams Link"
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-xs font-medium dark:text-white focus:bg-white">
                    <p class="text-[10px] text-slate-400 mt-1">Google Meet, Zoom, MS Teams, or Webex link for virtual presentation / attendees</p>
                </div>

                <!-- Publication Information (General Overview) -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">General Publication Overview</label>
                    <textarea name="publication_info" rows="2" placeholder="e.g. All accepted papers will be published in Scopus/WoS indexed special issue journal and conference proceedings with DOI."
                              class="w-full px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-xs font-medium dark:text-white focus:bg-white">{{ old('publication_info') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Publication & Proceedings Section (Book & Journal) -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200/80 dark:border-slate-700 shadow-sm space-y-6">
            <h3 class="text-sm font-bold text-slate-800 dark:text-white flex items-center gap-2 border-b border-slate-100 dark:border-slate-700 pb-3">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                Publication Pathways (Book & Journal Publications)
            </h3>

            <!-- 1. Book / Proceedings Publication -->
            <div class="p-5 rounded-2xl bg-amber-50/50 dark:bg-slate-700/30 border border-amber-200/70 dark:border-slate-600 space-y-4">
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 rounded-lg bg-amber-500 text-white font-bold text-[11px] uppercase tracking-wider">Part 1</span>
                    <h4 class="text-xs font-bold text-slate-900 dark:text-white">Book / Conference Proceedings Publication</h4>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1.5">Book / Proceedings Title</label>
                        <input type="text" name="book_publication_title" value="{{ old('book_publication_title') }}" placeholder="e.g. Advances in Engineering & Applied Sciences (Vol. 4)"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-xs font-medium dark:text-white focus:ring-2 focus:ring-amber-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1.5">Book Author / Editor / Series</label>
                        <input type="text" name="book_publication_author" value="{{ old('book_publication_author') }}" placeholder="e.g. Dr. John Doe, Prof. Jane Smith (Editors)"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-xs font-medium dark:text-white focus:ring-2 focus:ring-amber-500">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1.5">Book Abstract / Overview</label>
                    <textarea name="book_publication_abstract" rows="2" placeholder="Brief summary of the conference proceedings book / volume..."
                              class="w-full px-3.5 py-2 rounded-xl bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-xs font-medium dark:text-white focus:ring-2 focus:ring-amber-500">{{ old('book_publication_abstract') }}</textarea>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1.5">Book Publication Full Details & Guidelines (Large Field)</label>
                    <textarea name="book_publication_content" rows="4" placeholder="Detailed ISBN information, publisher details, author guidelines for chapter publication, indexing with Scopus / Web of Science, DOI assignment details..."
                              class="w-full px-3.5 py-2.5 rounded-xl bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-xs font-medium dark:text-white focus:ring-2 focus:ring-amber-500">{{ old('book_publication_content') }}</textarea>
                </div>
            </div>

            <!-- 2. Journal Publication -->
            <div class="p-5 rounded-2xl bg-indigo-50/50 dark:bg-slate-700/30 border border-indigo-200/70 dark:border-slate-600 space-y-4">
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 rounded-lg bg-indigo-600 text-white font-bold text-[11px] uppercase tracking-wider">Part 2</span>
                    <h4 class="text-xs font-bold text-slate-900 dark:text-white">Journal Publication (Special Issue / Partner Journals)</h4>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1.5">Partner Journal Title / Special Issue</label>
                    <input type="text" name="journal_publication_title" value="{{ old('journal_publication_title') }}" placeholder="e.g. International Journal of Advanced Research (Special Issue: ICAS-2026)"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-xs font-medium dark:text-white focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1.5">Journal Publication Full Details & Indexing (Large Field)</label>
                    <textarea name="journal_publication_content" rows="4" placeholder="Peer review process for special issue selection, indexing bodies (Scopus, WoS, ESCI, PubMed, Crossref), APC fees or waivers, submission instructions..."
                              class="w-full px-3.5 py-2.5 rounded-xl bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-xs font-medium dark:text-white focus:ring-2 focus:ring-indigo-500">{{ old('journal_publication_content') }}</textarea>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200/80 dark:border-slate-700 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-slate-800 dark:text-white flex items-center gap-2 border-b border-slate-100 dark:border-slate-700 pb-3">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                Templates & Certificate Preview
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- Upload Template for Paper -->
                <div class="bg-slate-50 dark:bg-slate-700/40 p-4 rounded-xl border border-slate-200/80 dark:border-slate-600">
                    <label class="block text-xs font-bold text-slate-800 dark:text-white mb-1.5 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Upload Template for Paper
                    </label>
                    <input type="file" name="paper_template" accept=".doc,.docx,.pdf"
                           class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                    <p class="text-[10px] text-slate-400 mt-1.5">Official manuscript template file for author submissions (DOCX, PDF up to 25MB)</p>
                </div>

                <!-- Sample Certificate -->
                <div class="bg-slate-50 dark:bg-slate-700/40 p-4 rounded-xl border border-slate-200/80 dark:border-slate-600">
                    <label class="block text-xs font-bold text-slate-800 dark:text-white mb-1.5 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                        Sample Certificate
                    </label>
                    <input type="file" name="sample_certificate" accept="image/*,.pdf"
                           class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                    <p class="text-[10px] text-slate-400 mt-1.5">Sample presentation or attendee certificate preview (PNG, JPG, PDF up to 15MB)</p>
                </div>
            </div>
        </div>

        <!-- Submission Action Bar -->
        <div class="flex items-center justify-end gap-4 pt-4">
            <a href="{{ route('admin.conferences.index') }}" class="px-6 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">
                Cancel
            </a>
            <button type="submit" class="px-8 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-lg shadow-blue-600/30 transition-all flex items-center gap-2 hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                Publish & Save Conference
            </button>
        </div>
    </form>
</div>

<script>
function conferenceFormHandler() {
    return {
        committeeMembers: [
            { name: '', role: 'Conference Chair', affiliation: '' },
            { name: '', role: 'Co-Chair / Technical Lead', affiliation: '' }
        ],
        customFees: [],

        addCommitteeMember() {
            this.committeeMembers.push({ name: '', role: '', affiliation: '' });
        },

        removeCommitteeMember(index) {
            if (this.committeeMembers.length > 1) {
                this.committeeMembers.splice(index, 1);
            }
        },

        addFeeItem() {
            this.customFees.push({ name: '', amount: '' });
        },

        removeFeeItem(index) {
            this.customFees.splice(index, 1);
        }
    };
}
</script>
@endsection
