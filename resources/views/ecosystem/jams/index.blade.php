@extends('layouts.web')
@section('title', 'JAMS | Journal & Manuscript System')

@section('content')
    <div class="bg-slate-900 min-h-screen py-20 flex items-center relative overflow-hidden">
        <div class="absolute inset-0 opacity-20"
            style="background-image: linear-gradient(30deg, #1e3a8a 12%, transparent 12.5%, transparent 87%, #1e3a8a 87.5%, #1e3a8a), linear-gradient(150deg, #1e3a8a 12%, transparent 12.5%, transparent 87%, #1e3a8a 87.5%, #1e3a8a), linear-gradient(30deg, #1e3a8a 12%, transparent 12.5%, transparent 87%, #1e3a8a 87.5%, #1e3a8a), linear-gradient(150deg, #1e3a8a 12%, transparent 12.5%, transparent 87%, #1e3a8a 87.5%, #1e3a8a), linear-gradient(60deg, #3b82f6 25%, transparent 25.5%, transparent 75%, #3b82f6 75%, #3b82f6), linear-gradient(60deg, #3b82f6 25%, transparent 25.5%, transparent 75%, #3b82f6 75%, #3b82f6); background-size: 80px 140px; background-position: 0 0, 0 0, 40px 70px, 40px 70px, 0 0, 40px 70px;">
        </div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-6xl font-serif font-bold text-white mb-6">JAMS</h1>
                <h2 class="text-2xl text-blue-300 font-light mb-12 tracking-wide uppercase tracking-[0.3em]">Journal &
                    Manuscript System</h2>

                <div class="grid md:grid-cols-3 gap-8 mb-16">
                    <div
                        class="bg-white/10 backdrop-blur-md p-8 rounded-3xl border border-white/20 text-white hover:bg-white/20 transition">
                        <div
                            class="w-12 h-12 rounded-2xl bg-blue-600 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-blue-500/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                        </div>
                        <h4 class="font-bold mb-2 uppercase text-xs tracking-widest text-blue-200">Submit</h4>
                        <p class="text-sm text-slate-300">New Manuscript</p>
                    </div>
                    <div
                        class="bg-white/10 backdrop-blur-md p-8 rounded-3xl border border-white/20 text-white hover:bg-white/20 transition">
                        <div
                            class="w-12 h-12 rounded-2xl bg-purple-600 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-purple-500/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                        </div>
                        <h4 class="font-bold mb-2 uppercase text-xs tracking-widest text-purple-200">Review</h4>
                        <p class="text-sm text-slate-300">Track Assigments</p>
                    </div>
                    <div
                        class="bg-white/10 backdrop-blur-md p-8 rounded-3xl border border-white/20 text-white hover:bg-white/20 transition">
                        <div
                            class="w-12 h-12 rounded-2xl bg-teal-600 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-teal-500/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <h4 class="font-bold mb-2 uppercase text-xs tracking-widest text-teal-200">Manage</h4>
                        <p class="text-sm text-slate-300">Editorial Panel</p>
                    </div>
                </div>

                <div class="bg-white p-2 rounded-2xl shadow-2xl flex flex-col md:flex-row gap-2 max-w-2xl mx-auto">
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="flex-grow py-4 bg-slate-900 text-white font-bold rounded-xl hover:bg-black transition text-center uppercase tracking-widest text-sm">Access
                            My Dashboard</a>
                    @else
                        <a href="{{ route('login') }}"
                            class="flex-grow py-4 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-500 transition text-center uppercase tracking-widest text-sm">Login
                            to JAMS</a>
                        <a href="{{ route('register') }}"
                            class="flex-grow py-4 bg-slate-100 text-slate-900 font-bold rounded-xl hover:bg-slate-200 transition text-center uppercase tracking-widest text-sm">Create
                            Account</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
@endsection