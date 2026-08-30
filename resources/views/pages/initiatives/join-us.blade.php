@extends('layouts.web')
@section('title', 'Join Us | HJPARAM Publication')

@section('content')
    <div class="bg-slate-50 py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <!-- Hero Header -->
                <div
                    class="bg-gradient-to-br from-blue-900 via-indigo-900 to-slate-900 rounded-[3rem] shadow-2xl shadow-blue-900/30 p-10 md:p-16 border border-blue-800 mb-12 relative overflow-hidden text-white">
                    <div class="absolute top-0 right-0 w-96 h-96 bg-blue-600/30 rounded-full blur-[100px] -mr-48 -mt-48">
                    </div>
                    <div
                        class="absolute bottom-0 left-0 w-64 h-64 bg-indigo-500/20 rounded-full blur-[80px] -ml-32 -mb-32">
                    </div>

                    <div class="relative z-10 text-center max-w-2xl mx-auto">
                        <span
                            class="inline-block px-4 py-1.5 bg-blue-400/20 text-blue-300 text-[10px] font-bold uppercase tracking-[0.2em] rounded-full border border-blue-400/30 mb-6">
                            Join Our Academic Community
                        </span>
                        <h1 class="text-3xl md:text-5xl font-serif font-bold mb-6 leading-tight">
                            Join <span class="text-blue-400">HJPARAM</span> Community
                        </h1>
                        <p class="text-base md:text-lg text-blue-100/90 leading-relaxed font-light">
                            Collaborate with global scholars, peer reviewers, and editors to advance open access research with integrity and worldwide impact.
                        </p>
                    </div>
                </div>

                <!-- Join Us Form Container -->
                <div x-data="joinUsHandler()" 
                    id="joinUsContainer"
                    class="bg-white rounded-[3rem] p-8 md:p-14 border border-slate-200/80 shadow-2xl mb-12 relative overflow-hidden">
                    
                    <!-- Standard Blur Background Loader Overlay -->
                    <div x-show="submitting" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 backdrop-blur-none"
                         x-transition:enter-end="opacity-100 backdrop-blur-md"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 backdrop-blur-md"
                         x-transition:leave-end="opacity-0 backdrop-blur-none"
                         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-md"
                         style="display: none;">
                        
                        <div class="bg-white/95 backdrop-blur-xl p-8 md:p-10 rounded-3xl shadow-2xl border border-white/40 max-w-sm w-full mx-4 text-center transform transition-all animate-in fade-in zoom-in-95 duration-200">
                            <!-- Spinner Graphic -->
                            <div class="relative w-20 h-20 mx-auto mb-6">
                                <div class="absolute inset-0 rounded-full border-4 border-blue-100"></div>
                                <div class="absolute inset-0 rounded-full border-4 border-blue-600 border-t-transparent animate-spin"></div>
                                <div class="absolute inset-2 rounded-full border-4 border-indigo-200/60"></div>
                                <div class="absolute inset-2 rounded-full border-4 border-indigo-600 border-b-transparent animate-spin" style="animation-direction: reverse; animation-duration: 1.5s;"></div>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </div>
                            </div>

                            <h3 class="text-xl font-bold font-serif text-slate-900 mb-2">Processing Details</h3>
                            <p class="text-xs text-slate-500 mb-5 leading-relaxed">Please wait while we submit your application to the HJPARAM editorial system...</p>
                            
                            <!-- Shimmer Bar -->
                            <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                                <div class="bg-gradient-to-r from-blue-600 via-indigo-500 to-blue-600 h-full rounded-full w-2/3 animate-pulse"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Thank You / Success Confirmation Message -->
                    <div x-show="submitted" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" class="text-center py-6 md:py-10" style="display: none;">
                        <div class="relative w-24 h-24 mx-auto mb-8">
                            <div class="absolute inset-0 bg-emerald-400/20 rounded-full animate-ping"></div>
                            <div class="relative w-24 h-24 bg-gradient-to-tr from-emerald-600 to-teal-500 text-white rounded-full flex items-center justify-center shadow-2xl shadow-emerald-500/30">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>

                        <span class="inline-block px-4 py-1.5 bg-emerald-50 text-emerald-700 text-xs font-black uppercase tracking-widest rounded-full mb-4 border border-emerald-200">
                            Submission Successful
                        </span>

                        <h2 class="text-4xl md:text-5xl font-serif font-black text-slate-900 mb-3">Thank You!</h2>
                        <p class="text-lg md:text-xl font-medium text-slate-700 mb-6">
                            Your application to join HJPARAM has been successfully received.
                        </p>

                        <!-- Summary Card -->
                        <div class="max-w-xl mx-auto bg-slate-50 border border-slate-200/80 rounded-2xl p-6 text-left mb-8 shadow-sm">
                            <div class="flex items-center gap-3 pb-4 mb-4 border-b border-slate-200">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm">✓</div>
                                <div>
                                    <div class="text-xs text-slate-400 font-bold uppercase tracking-wider">Application Type</div>
                                    <div class="text-sm font-black text-slate-800 capitalize">
                                        <span x-show="role === 'author'">Author / Researcher Contributor</span>
                                        <span x-show="role === 'reviewer'">Peer Reviewer Panel</span>
                                        <span x-show="role === 'editor_societies'">Editorial Board / Society Partnership</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-xs text-slate-500 leading-relaxed space-y-1">
                                <p><strong class="text-slate-700">What happens next?</strong> Our editorial and community coordinator will review your submitted credentials and send a confirmation to your email address within 24–48 hours.</p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-wrap items-center justify-center gap-4">
                            <a href="{{ route('home') }}" class="px-8 py-3.5 bg-slate-900 hover:bg-black text-white font-bold rounded-xl text-xs uppercase tracking-wider transition shadow-md">
                                Return to Homepage
                            </a>
                            <a href="{{ route('author.submit') }}" class="px-8 py-3.5 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl text-xs uppercase tracking-wider transition shadow-md shadow-blue-500/20">
                                Submit a Manuscript
                            </a>
                            <button @click="submitted = false" type="button" class="px-6 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs uppercase tracking-wider transition border border-slate-200">
                                Submit Another Application
                            </button>
                        </div>
                    </div>

                    <!-- Main Form -->
                    <form id="joinUsForm" x-show="!submitted" @submit.prevent="submitForm($event)" class="space-y-8">
                        @csrf
                        
                        <!-- Step 1: Radio Button Role Selection -->
                        <div>
                            <div class="text-center mb-6">
                                <span class="text-xs font-black uppercase tracking-widest text-blue-600 bg-blue-50 px-3 py-1 rounded-full">Step 1</span>
                                <h2 class="text-2xl font-bold font-serif text-slate-900 mt-2">Select Your Role</h2>
                                <p class="text-slate-500 text-xs mt-1">Choose how you would like to participate in the HJPARAM ecosystem</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Option 1: Author -->
                                <label 
                                    @click="role = 'author'"
                                    class="relative p-6 rounded-2xl border-2 cursor-pointer transition-all duration-300 flex flex-col justify-between"
                                    :class="role === 'author' ? 'border-blue-600 bg-blue-50/50 shadow-lg shadow-blue-500/10 ring-2 ring-blue-600/20' : 'border-slate-200 hover:border-slate-300 bg-slate-50/50'">
                                    
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="w-12 h-12 rounded-xl flex items-center justify-center"
                                             :class="role === 'author' ? 'bg-blue-600 text-white shadow-md' : 'bg-blue-100 text-blue-700'">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                        </div>
                                        <input type="radio" name="role_type" value="author" x-model="role" class="w-5 h-5 text-blue-600 focus:ring-blue-500 border-slate-300 mt-1">
                                    </div>
                                    
                                    <div>
                                        <h3 class="font-bold text-slate-900 text-base mb-1" :class="role === 'author' ? 'text-blue-900' : ''">Join as an Author</h3>
                                        <p class="text-slate-500 text-xs leading-relaxed">
                                            Publish open-access research, submit manuscripts to specialized issues, and track review stages.
                                        </p>
                                    </div>
                                </label>

                                <!-- Option 2: Reviewer -->
                                <label 
                                    @click="role = 'reviewer'"
                                    class="relative p-6 rounded-2xl border-2 cursor-pointer transition-all duration-300 flex flex-col justify-between"
                                    :class="role === 'reviewer' ? 'border-purple-600 bg-purple-50/50 shadow-lg shadow-purple-500/10 ring-2 ring-purple-600/20' : 'border-slate-200 hover:border-slate-300 bg-slate-50/50'">
                                    
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="w-12 h-12 rounded-xl flex items-center justify-center"
                                             :class="role === 'reviewer' ? 'bg-purple-600 text-white shadow-md' : 'bg-purple-100 text-purple-700'">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                            </svg>
                                        </div>
                                        <input type="radio" name="role_type" value="reviewer" x-model="role" class="w-5 h-5 text-purple-600 focus:ring-purple-500 border-slate-300 mt-1">
                                    </div>
                                    
                                    <div>
                                        <h3 class="font-bold text-slate-900 text-base mb-1" :class="role === 'reviewer' ? 'text-purple-900' : ''">Join as a Reviewer</h3>
                                        <p class="text-slate-500 text-xs leading-relaxed">
                                            Evaluate research in your domain, maintain academic rigor, and receive reviewer recognition & credits.
                                        </p>
                                    </div>
                                </label>

                                <!-- Option 3: Editor & Societies -->
                                <label 
                                    @click="role = 'editor_societies'"
                                    class="relative p-6 rounded-2xl border-2 cursor-pointer transition-all duration-300 flex flex-col justify-between"
                                    :class="role === 'editor_societies' ? 'border-emerald-600 bg-emerald-50/50 shadow-lg shadow-emerald-500/10 ring-2 ring-emerald-600/20' : 'border-slate-200 hover:border-slate-300 bg-slate-50/50'">
                                    
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="w-12 h-12 rounded-xl flex items-center justify-center"
                                             :class="role === 'editor_societies' ? 'bg-emerald-600 text-white shadow-md' : 'bg-emerald-100 text-emerald-700'">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                        </div>
                                        <input type="radio" name="role_type" value="editor_societies" x-model="role" class="w-5 h-5 text-emerald-600 focus:ring-emerald-500 border-slate-300 mt-1">
                                    </div>
                                    
                                    <div>
                                        <h3 class="font-bold text-slate-900 text-base mb-1" :class="role === 'editor_societies' ? 'text-emerald-900' : ''">Join as an Editor & Societies</h3>
                                        <p class="text-slate-500 text-xs leading-relaxed">
                                            Propose special issues, serve on the editorial board, or establish society/institutional partnerships.
                                        </p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Step 2: Information Form -->
                        <div class="pt-6 border-t border-slate-100">
                            <div class="text-center mb-8">
                                <span class="text-xs font-black uppercase tracking-widest text-blue-600 bg-blue-50 px-3 py-1 rounded-full">Step 2</span>
                                <h2 class="text-2xl font-bold font-serif text-slate-900 mt-2">Your Information</h2>
                                <p class="text-slate-500 text-xs mt-1">Please provide your academic and professional details</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Full Name -->
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">
                                        Full Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="name" required placeholder="e.g. Dr. Jane Doe"
                                        class="w-full px-4 py-3.5 rounded-xl bg-slate-50 border border-slate-200 focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/10 text-sm font-medium text-slate-800 transition">
                                </div>

                                <!-- Email -->
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">
                                        Academic / Official Email <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" name="email" required placeholder="e.g. j.doe@university.edu"
                                        class="w-full px-4 py-3.5 rounded-xl bg-slate-50 border border-slate-200 focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/10 text-sm font-medium text-slate-800 transition">
                                </div>

                                <!-- Institution -->
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">
                                        Institution / Organization <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="institution" required placeholder="e.g. Oxford University / Institute of Technology"
                                        class="w-full px-4 py-3.5 rounded-xl bg-slate-50 border border-slate-200 focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/10 text-sm font-medium text-slate-800 transition">
                                </div>

                                <!-- Academic Field / Specialty -->
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">
                                        Research Field / Specialty <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="field" required placeholder="e.g. Computer Science, Medicine, Environmental Science"
                                        class="w-full px-4 py-3.5 rounded-xl bg-slate-50 border border-slate-200 focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/10 text-sm font-medium text-slate-800 transition">
                                </div>

                                <!-- Country -->
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">
                                        Country / Region <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="country" required placeholder="e.g. United Kingdom, United States, India"
                                        class="w-full px-4 py-3.5 rounded-xl bg-slate-50 border border-slate-200 focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/10 text-sm font-medium text-slate-800 transition">
                                </div>

                                <!-- ORCID / Profile Link -->
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">
                                        ORCID / Google Scholar / Profile URL <span class="text-slate-400 font-normal text-[10px]">(Optional)</span>
                                    </label>
                                    <input type="url" name="profile_url" placeholder="https://orcid.org/0000-..."
                                        class="w-full px-4 py-3.5 rounded-xl bg-slate-50 border border-slate-200 focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/10 text-sm font-medium text-slate-800 transition">
                                </div>
                            </div>

                            <!-- Message / Statement of Interest -->
                            <div class="mt-6">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">
                                    <span x-show="role === 'author'">Manuscript Title or Research Interest</span>
                                    <span x-show="role === 'reviewer'">Peer Review Experience & Keywords</span>
                                    <span x-show="role === 'editor_societies'">Proposal Summary / Society Details</span>
                                    <span class="text-slate-400 font-normal text-[10px]">(Optional)</span>
                                </label>
                                <textarea name="message" rows="4" placeholder="Provide any details, links, or specific journals of interest..."
                                    class="w-full px-4 py-3.5 rounded-xl bg-slate-50 border border-slate-200 focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/10 text-sm font-medium text-slate-800 transition"></textarea>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="pt-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div class="text-xs text-slate-500 flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>No submission fees for registration or editorial inquiries.</span>
                            </div>

                            <button type="submit" :disabled="submitting"
                                class="w-full sm:w-auto px-8 py-4 bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white font-bold rounded-xl shadow-lg shadow-blue-500/25 hover:shadow-blue-500/40 hover:scale-[1.02] active:scale-[0.98] transition-all duration-200 flex items-center justify-center gap-2 uppercase tracking-wider text-xs">
                                <svg x-show="submitting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span x-show="!submitting">Submit Application</span>
                                <span x-show="submitting">Processing...</span>
                                <svg x-show="!submitting" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Ecosystem Benefits -->
                <div class="bg-white rounded-[3rem] p-10 md:p-14 border border-slate-200/80 shadow-xl mb-12">
                    <div class="text-center max-w-2xl mx-auto mb-10">
                        <h2 class="text-2xl md:text-3xl font-serif font-bold text-slate-900 mb-3">Why Join HJPARAM?</h2>
                        <p class="text-slate-500 text-sm">Empowering scholars and institutions with tools, high visibility, and ethical publishing standards.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                        <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                            <div class="text-2xl font-bold text-blue-600 mb-1">Open Access</div>
                            <p class="text-xs text-slate-500">Unrestricted worldwide visibility for all accepted articles</p>
                        </div>
                        <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                            <div class="text-2xl font-bold text-purple-600 mb-1">Fast Review</div>
                            <p class="text-xs text-slate-500">Rigorous double-blind peer review with timely editorial feedback</p>
                        </div>
                        <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                            <div class="text-2xl font-bold text-teal-600 mb-1">JAMS Portal</div>
                            <p class="text-xs text-slate-500">Seamless manuscript submission & status tracking ecosystem</p>
                        </div>
                        <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                            <div class="text-2xl font-bold text-amber-600 mb-1">Global Impact</div>
                            <p class="text-xs text-slate-500">Cross-indexed with Scilit, SciProfiles, and partner libraries</p>
                        </div>
                    </div>
                </div>

                <!-- Direct Action Banner -->
                <div class="bg-slate-900 rounded-3xl p-8 md:p-12 text-white text-center flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="text-left">
                        <h3 class="text-2xl font-bold font-serif mb-2">Already have a manuscript ready?</h3>
                        <p class="text-slate-400 text-sm">You can directly submit your research to any of our indexed journals.</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-4">
                        <a href="{{ route('author.submit') }}"
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl text-sm transition flex items-center gap-2">
                            Submit Manuscript
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                        <a href="{{ route('about.page', 'contact') }}"
                            class="px-6 py-3 bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold rounded-xl text-sm border border-slate-700 transition">
                            Contact Us
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Isolated Script Handler -->
    <script>
        function joinUsHandler() {
            return {
                role: 'author',
                submitted: false,
                submitting: false,
                submitForm(e) {
                    this.submitting = true;
                    try {
                        const form = (e && e.target) ? e.target : document.getElementById('joinUsForm');
                        const formData = new FormData(form);

                        fetch("{{ route('initiatives.join-us.store') }}", {
                            method: "POST",
                            body: formData,
                            headers: {
                                "X-Requested-With": "XMLHttpRequest",
                                "Accept": "application/json"
                            }
                        })
                        .then(async (response) => {
                            const data = await response.json().catch(() => ({}));
                            if (!response.ok) {
                                const errText = data.message || (data.errors ? Object.values(data.errors).flat().join('\n') : 'Submission failed. Please check the fields.');
                                throw new Error(errText);
                            }
                            return data;
                        })
                        .then((data) => {
                            setTimeout(() => {
                                this.submitting = false;
                                this.submitted = true;
                                this.$nextTick(() => {
                                    const container = document.getElementById('joinUsContainer');
                                    if (container) {
                                        container.scrollIntoView({ behavior: 'smooth', block: 'start' });
                                    }
                                });
                            }, 800);
                        })
                        .catch((err) => {
                            this.submitting = false;
                            alert(err.message || 'An unexpected error occurred. Please try again.');
                        });
                    } catch (err) {
                        this.submitting = false;
                        alert('Error preparing form: ' + err.message);
                    }
                }
            };
        }
    </script>
@endsection
