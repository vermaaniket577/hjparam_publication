@extends('layouts.web')
@section('title', 'Volunteer as Reviewer | HJPARAM Publication')

@section('content')
    <div class="bg-slate-50 py-24 relative overflow-hidden">
        {{-- Background Decorations --}}
        <div class="absolute top-0 right-0 w-1/2 h-full bg-blue-50/50 skew-x-12 translate-x-32 pointer-events-none"></div>
        <div
            class="absolute bottom-0 left-0 w-64 h-64 bg-slate-100 rounded-full blur-3xl opacity-50 -translate-x-1/2 pointer-events-none">
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center mb-16">
                <span
                    class="inline-block px-3 py-1 bg-blue-100 text-blue-700 text-[10px] font-bold uppercase tracking-[0.2em] rounded-full mb-6">Expert
                    Community</span>
                <h1 class="text-4xl md:text-6xl font-serif font-bold text-slate-900 mb-6 leading-tight">Volunteer as a <span
                        class="text-blue-600">Reviewer</span></h1>
                <p class="text-lg md:text-xl text-slate-500 font-light leading-relaxed">Join our elite network of global
                    experts and contribute to the advancement of high-impact scientific research.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center mb-16">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800 mb-6 border-l-4 border-blue-600 pl-4">Why Become a
                        Reviewer?</h2>
                    <div class="space-y-6">
                        <div class="flex gap-4">
                            <div
                                class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-blue-600 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M5 13l4 4L19 7" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 mb-1">Stay at the Forefront</h4>
                                <p class="text-sm text-slate-500">Access and evaluate groundbreaking research before it
                                    reaches the global stage.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div
                                class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-blue-600 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 mb-1">Career Growth</h4>
                                <p class="text-sm text-slate-500">Enhance your academic profile and receive official
                                    certificates for your contributions.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div
                                class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-blue-600 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                        stroke-width="2"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 mb-1">Community Impact</h4>
                                <p class="text-sm text-slate-500">Play a critical role in maintaining the integrity and
                                    quality of open-access literature.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-10 rounded-[3rem] shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-slate-100">
                    <h3 class="text-2xl font-bold text-slate-900 mb-2">Apply to Join</h3>
                    <p class="text-slate-500 text-sm mb-8">Please fill out the form below. Our editorial board will review
                        your credentials and contact you within 5-7 business days.</p>

                    <form action="#" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Full
                                    Name</label>
                                <input type="text" placeholder="Dr. John Doe"
                                    class="w-full px-4 py-3 bg-slate-50 border-slate-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all text-sm">
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Academic
                                    Title</label>
                                <input type="text" placeholder="Associate Professor"
                                    class="w-full px-4 py-3 bg-slate-50 border-slate-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all text-sm">
                            </div>
                        </div>
                        <div>
                            <label
                                class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Affiliation
                                / Institution</label>
                            <input type="text" placeholder="University of Science & Tech"
                                class="w-full px-4 py-3 bg-slate-50 border-slate-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all text-sm">
                        </div>
                        <div>
                            <label
                                class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Fields
                                of Expertise</label>
                            <input type="text" placeholder="Quantum Physics, AI, Renewable Energy"
                                class="w-full px-4 py-3 bg-slate-50 border-slate-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all text-sm">
                        </div>
                        <button type="button"
                            onclick="alert('Thank you for your interest! This is a demo. Your application has not been sent.')"
                            class="w-full bg-blue-600 hover:bg-slate-900 text-white font-bold py-4 rounded-2xl shadow-lg shadow-blue-200 transition-all flex items-center justify-center gap-2">
                            Submit Application
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <div class="h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent my-20"></div>

            {{-- Requirements Section --}}
            <div class="max-w-3xl mx-auto">
                <h3 class="text-xl font-bold text-slate-900 mb-8 text-center uppercase tracking-widest">Minimal Requirements
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                    <div class="flex items-start gap-4">
                        <div class="w-2 h-2 rounded-full bg-blue-600 mt-2 shrink-0"></div>
                        <p class="text-slate-600 text-sm">Must hold a PhD in a relevant scientific or academic field.</p>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-2 h-2 rounded-full bg-blue-600 mt-2 shrink-0"></div>
                        <p class="text-slate-600 text-sm">Demonstrated record of peer-reviewed publications in reputable
                            journals.</p>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-2 h-2 rounded-full bg-blue-600 mt-2 shrink-0"></div>
                        <p class="text-slate-600 text-sm">Affiliation with a recognized academic institution or research
                            center.</p>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-2 h-2 rounded-full bg-blue-600 mt-2 shrink-0"></div>
                        <p class="text-slate-600 text-sm">Commitment to unbiased, constructive, and timely peer review
                            processes.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Final CTA --}}
    <div class="bg-slate-900 py-16">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-2xl font-serif font-bold text-white mb-4">Already Have an Account?</h2>
            <p class="text-slate-400 mb-8">Log in to your dashboard to manage your existing review assignments.</p>
            <a href="{{ route('login') }}"
                class="inline-flex px-8 py-3 bg-white text-slate-900 font-bold rounded-full hover:bg-blue-50 transition-colors">Go
                to Portal</a>
        </div>
    </div>
@endsection