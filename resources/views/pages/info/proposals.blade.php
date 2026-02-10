@extends('layouts.web')
@section('title', 'Submit a Conference Proposal')
@section('meta_description', 'Partner with HJPARAM to publish your conference proceedings. We provide global reach, DOI assignment, and fast-track indexing.')

@section('content')
    <div class="bg-slate-50 py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <!-- Header -->
                <div class="bg-white rounded-[40px] shadow-sm border border-slate-100 p-12 mb-12 relative overflow-hidden">
                    <div
                        class="absolute top-0 right-0 w-64 h-64 bg-blue-50/50 rounded-full -translate-y-32 translate-x-32 blur-3xl">
                    </div>
                    <div class="relative z-10">
                        <span
                            class="inline-block px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold uppercase tracking-wider rounded-full mb-6">Partnership</span>
                        <h1 class="text-4xl md:text-5xl font-serif font-bold text-slate-900 mb-6">Submit a <span
                                class="text-blue-600">Proposal</span></h1>
                        <p class="text-lg text-slate-500 leading-relaxed">
                            Are you organizing a scientific conference? HJPARAM offers a dedicated platform for publishing
                            proceedings with maximum impact.
                            Tell us about your event and start the journey to global recognition.
                        </p>
                    </div>
                </div>

                <!-- Proposal Form -->
                <div class="bg-white rounded-[40px] shadow-xl shadow-slate-200/50 p-12 border border-slate-100">
                    <form action="#" method="POST" class="space-y-8">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label
                                    class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Conference
                                    Name</label>
                                <input type="text" name="conference_name"
                                    placeholder="e.g. International Energy Summit 2026"
                                    class="w-full bg-slate-50 border-0 rounded-2xl py-4 px-6 focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-900">
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Expected
                                    Date</label>
                                <input type="date" name="event_date"
                                    class="w-full bg-slate-50 border-0 rounded-2xl py-4 px-6 focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-900">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Your
                                Email</label>
                            <input type="email" name="email" placeholder="contact@university.edu"
                                class="w-full bg-slate-50 border-0 rounded-2xl py-4 px-6 focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-900">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Brief
                                Overview</label>
                            <textarea name="overview" rows="5"
                                placeholder="Describe the scope and objective of your conference..."
                                class="w-full bg-slate-50 border-0 rounded-2xl py-4 px-6 focus:ring-2 focus:ring-blue-500 transition-all font-medium text-slate-900"></textarea>
                        </div>

                        <div class="bg-blue-50 p-8 rounded-3xl border border-blue-100/50 flex items-start gap-4">
                            <div class="bg-blue-500 text-white rounded-lg p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>
                            <p class="text-xs text-blue-700 leading-relaxed font-medium">Our editorial team will review your
                                proposal and get back to you within 3-5 business days to discuss the partnership details.
                            </p>
                        </div>

                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-5 rounded-3xl shadow-lg shadow-blue-200 transition-all transform hover:-translate-y-1">
                            Submit Proposal for Review
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection