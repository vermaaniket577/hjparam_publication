@extends('layouts.web')
@section('title', 'Benefits for Reviewers | HJPARAM Publication')

@section('content')
    <div class="bg-slate-50 py-24">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <span
                    class="inline-block px-3 py-1 bg-purple-100 text-purple-700 text-[10px] font-bold uppercase tracking-[0.2em] rounded-full mb-6">Recognition</span>
                <h1 class="text-4xl md:text-5xl font-serif font-bold text-slate-900 mb-8">Benefits for <span
                        class="text-purple-600">Reviewers</span></h1>

                <div class="bg-white p-8 md:p-12 rounded-[2rem] shadow-sm border border-slate-100 mb-12">
                    <div class="prose prose-slate max-w-none">
                        <p class="text-lg text-slate-600 mb-12 leading-relaxed">
                            Peer review is a voluntary service that requires time and expertise. At HJPARAM, we believe this
                            service should be officially recognized and rewarded.
                        </p>

                        <div class="space-y-8 mb-16">
                            <div
                                class="flex gap-6 p-8 bg-slate-50 rounded-3xl border border-slate-100 hover:border-purple-200 transition-colors">
                                <div
                                    class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-purple-600 shrink-0 shadow-sm text-2xl">
                                    🏆</div>
                                <div>
                                    <h4 class="text-xl font-bold text-slate-900 mb-2">Annual Awards</h4>
                                    <p class="text-slate-600 text-sm leading-relaxed">Top reviewers for each journal are
                                        eligible for our "Outstanding Reviewer Award" which includes a monetary prize and a
                                        public feature.</p>
                                </div>
                            </div>

                            <div
                                class="flex gap-6 p-8 bg-slate-50 rounded-3xl border border-slate-100 hover:border-purple-200 transition-colors">
                                <div
                                    class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-purple-600 shrink-0 shadow-sm text-2xl">
                                    📄</div>
                                <div>
                                    <h4 class="text-xl font-bold text-slate-900 mb-2">Reviewer Certificates</h4>
                                    <p class="text-slate-600 text-sm leading-relaxed">Instantly download personalized,
                                        verified certificates for every completed review to add to your academic portfolio
                                        or CV.</p>
                                </div>
                            </div>

                            <div
                                class="flex gap-6 p-8 bg-slate-50 rounded-3xl border border-slate-100 hover:border-purple-200 transition-colors">
                                <div
                                    class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-purple-600 shrink-0 shadow-sm text-2xl">
                                    💰</div>
                                <div>
                                    <h4 class="text-xl font-bold text-slate-900 mb-2">Publication Discounts</h4>
                                    <p class="text-slate-600 text-sm leading-relaxed">Active reviewers receive significant
                                        discounts (vouchers) on their own subsequent Article Processing Charges (APCs).</p>
                                </div>
                            </div>
                        </div>

                        <div class="text-center p-10 bg-purple-900 rounded-[3rem] text-white overflow-hidden relative">
                            <div
                                class="absolute top-0 right-0 w-32 h-32 bg-purple-800 rotate-45 translate-x-16 -translate-y-16 opacity-50">
                            </div>
                            <h3 class="text-2xl font-bold mb-4 relative z-10">Start Your Reviewer Journey</h3>
                            <a href="{{ route('info.page', 'volunteer-reviewers') }}"
                                class="inline-block px-10 py-4 bg-white text-purple-900 font-bold rounded-full hover:bg-slate-100 transition-all relative z-10">Apply
                                to Volunteer</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection