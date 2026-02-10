@extends('layouts.web')
@section('title', 'Contact Information')

@section('content')
    <div class="bg-slate-50 py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <!-- Header -->
                <div
                    class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 p-12 border border-slate-100 mb-8 relative overflow-hidden text-center">
                    <div class="absolute -top-10 -left-10 w-40 h-40 bg-blue-50 rounded-full opacity-50"></div>
                    <div class="relative z-10">
                        <span
                            class="inline-block px-3 py-1 bg-blue-50 text-blue-700 text-[10px] font-bold uppercase tracking-wider rounded-full mb-6">Support
                            Center</span>
                        <h1 class="text-4xl md:text-5xl font-serif font-bold text-slate-900 mb-6">Contact Information</h1>
                        <p class="text-lg text-slate-500 leading-relaxed max-w-2xl mx-auto">
                            Have questions about submission, formatting, or peer review? Our dedicated support team is here
                            to help.
                        </p>
                    </div>
                </div>

                <!-- Contact Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                    <!-- Editorial Office -->
                    <div
                        class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 p-10 border border-slate-100 group hover:border-blue-200 transition">
                        <div
                            class="w-14 h-14 bg-blue-600 text-white rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-blue-100">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-900 mb-4 font-serif">Editorial Office</h2>
                        <p class="text-slate-500 mb-6 text-sm">For questions related to article status, reviewer comments,
                            or editorial policies.</p>
                        <div class="space-y-3">
                            <a href="mailto:{{ $settings['editorial_email'] ?? 'editorial@hjparam.com' }}"
                                class="text-blue-600 font-bold hover:underline block">{{ $settings['editorial_email'] ??
                                'editorial@hjparam.com' }}</a>
                            <p class="text-slate-400 text-xs">
                                {{ $settings['editorial_response_time'] ?? 'Response time: Within 24-48 hours' }}</p>
                        </div>
                    </div>

                    <!-- Technical Support -->
                    <div
                        class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 p-10 border border-slate-100 group hover:border-blue-200 transition">
                        <div
                            class="w-14 h-14 bg-slate-800 text-white rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-slate-100">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-900 mb-4 font-serif">Technical Support</h2>
                        <p class="text-slate-500 mb-6 text-sm">For issues regarding the submission portal, account login, or
                            digital archiving.</p>
                        <div class="space-y-3">
                            <a href="mailto:{{ $settings['support_email'] ?? 'support@hjparam.com' }}"
                                class="text-blue-600 font-bold hover:underline block">{{ $settings['support_email'] ??
                                'support@hjparam.com' }}</a>
                            <p class="text-slate-400 text-xs">
                                {{ $settings['support_hours'] ?? 'Available Mon-Fri, 9AM-5PM GMT' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Physical Office (Optional but looks professional) -->
                <div
                    class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 p-12 border border-slate-100 flex flex-col md:flex-row items-center gap-10">
                    <div class="w-full md:w-1/3 bg-slate-100 h-48 rounded-2xl flex items-center justify-center">
                        <svg class="w-20 h-20 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="flex-1 text-center md:text-left">
                        <h3 class="text-2xl font-bold text-slate-900 mb-4 font-serif">Global Headquarters</h3>
                        <address class="not-italic text-slate-600 space-y-2 mb-6 leading-relaxed">
                            {!! nl2br(e($settings['office_address'] ?? "HJPARAM Publication Services\n124 Academic Way, Tech District\nInnovation Park, UK 50493")) !!}
                        </address>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection