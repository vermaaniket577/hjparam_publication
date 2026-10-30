@extends('layouts.web')

@section('title', 'User Profile - HJPARAM Publication')

@section('content')
<div class="bg-slate-50 min-h-screen py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto space-y-6">

        <!-- Breadcrumb & Page Header -->
        <div class="flex items-center justify-between border-b border-slate-200 pb-5">
            <div>
                <nav class="flex items-center text-xs font-semibold text-slate-500 gap-2 mb-1">
                    <a href="{{ route('home') }}" class="hover:text-blue-600 transition-colors">Home</a>
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-slate-800 font-bold">Profile Settings</span>
                </nav>
                <h1 class="text-2xl font-serif font-black text-slate-900 tracking-tight">Account Profile</h1>
            </div>
            <div>
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 rounded-xl text-xs font-bold uppercase tracking-wider transition-all shadow-xs">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Dashboard</span>
                </a>
            </div>
        </div>

        <div class="p-6 sm:p-8 bg-white border border-slate-200 shadow-sm rounded-2xl">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="p-6 sm:p-8 bg-white border border-slate-200 shadow-sm rounded-2xl">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        @unless(Auth::user()->isAdmin())
            <div class="p-6 sm:p-8 bg-white border border-rose-100 shadow-sm rounded-2xl">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        @endunless

    </div>
</div>
@endsection