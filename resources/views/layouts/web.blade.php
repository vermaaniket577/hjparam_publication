<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'HJPARAM Publication')</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">

    <!-- SEO & Open Graph Metadata -->
    @include('partials.og-metadata')

    <!-- Google AdSense -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2772066538696984"
         crossorigin="anonymous"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-700 bg-white flex flex-col min-h-screen">

    @include('components.web-loader')

    <!-- Top Academic Ecosystem Bar -->
    <div class="text-white relative z-[60] transition-all duration-300 transform"
        style="background-color: #0b132b; border-bottom: 1px solid rgba(255, 255, 255, 0.08); padding: 7px 0; min-height: 40px;"
        x-data="{ visible: true }" x-show="visible"
        x-init="window.addEventListener('scroll', () => { visible = window.scrollY < 100 })"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="-translate-y-full opacity-0"
        x-transition:enter-end="translate-y-0 opacity-100" x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="-translate-y-full opacity-0">
        <div class="container mx-auto px-4 lg:px-8 flex flex-wrap justify-between items-center gap-2" style="max-width: 1400px;">
            <!-- Left Ecosystem Links -->
            <div class="flex items-center overflow-x-auto py-0.5 no-scrollbar" style="display: flex; align-items: center; gap: 20px; flex-wrap: nowrap; -webkit-overflow-scrolling: touch;">
                <a href="{{ route('sciforum.index') }}"
                    style="font-size: 11px; font-weight: 800; letter-spacing: 0.05em; color: #ffffff; text-transform: uppercase; text-decoration: none; white-space: nowrap;"
                    class="hover:text-blue-400 transition-colors">Sciforum</a>
                <a href="{{ route('preprints.index') }}"
                    style="font-size: 11px; font-weight: 800; letter-spacing: 0.05em; color: #ffffff; text-transform: uppercase; text-decoration: none; white-space: nowrap;"
                    class="hover:text-blue-400 transition-colors">Preprints</a>
                <a href="{{ route('scilit.index') }}"
                    style="font-size: 11px; font-weight: 800; letter-spacing: 0.05em; color: #ffffff; text-transform: uppercase; text-decoration: none; white-space: nowrap;"
                    class="hover:text-blue-400 transition-colors">Scilit</a>
                <a href="{{ route('sciprofiles.index') }}"
                    style="font-size: 11px; font-weight: 800; letter-spacing: 0.05em; color: #ffffff; text-transform: uppercase; text-decoration: none; white-space: nowrap;"
                    class="hover:text-blue-400 transition-colors">SciProfiles</a>
                <a href="{{ route('encyclopedia.index') }}"
                    style="font-size: 11px; font-weight: 800; letter-spacing: 0.05em; color: #ffffff; text-transform: uppercase; text-decoration: none; white-space: nowrap;"
                    class="hover:text-blue-400 transition-colors">Encyclopedia</a>
                <a href="{{ route('jams.index') }}"
                    style="font-size: 10.5px; font-weight: 800; letter-spacing: 0.06em; background: #1e3a8a; color: #60a5fa; padding: 2.5px 8px; border-radius: 4px; text-transform: uppercase; text-decoration: none; white-space: nowrap;"
                    class="hover:bg-blue-600 hover:text-white transition-all">JAMS</a>
            </div>

            <!-- Right Top Group -->
            <div class="flex items-center" style="display: flex; align-items: center; gap: 12px;">
                <!-- Orange Pay Fee Pill -->
                <a href="{{ route('payments.create') }}"
                    style="background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%); color: #ffffff; padding: 3.5px 13px; border-radius: 9999px; font-weight: 900; font-size: 10.5px; text-transform: uppercase; letter-spacing: 0.05em; display: inline-flex; align-items: center; gap: 5px; text-decoration: none; box-shadow: 0 2px 8px rgba(234, 88, 12, 0.45);"
                    class="hover:opacity-95 hover:scale-105 transition-all">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>Pay Fee</span>
                </a>

                <div class="hidden sm:flex items-center" style="display: flex; align-items: center; gap: 10px;">
                    <a href="{{ route('rss.feed') }}"
                        style="font-size: 11px; font-weight: 700; letter-spacing: 0.05em; color: #94a3b8; text-transform: uppercase; text-decoration: none;"
                        class="hover:text-blue-300 transition-colors">RSS</a>
                    <span style="color: rgba(255,255,255,0.15); font-size: 10px; user-select: none;">•</span>
                    <a href="{{ route('about.page', 'contact') }}"
                        style="font-size: 11px; font-weight: 700; letter-spacing: 0.05em; color: #94a3b8; text-transform: uppercase; text-decoration: none;"
                        class="hover:text-blue-300 transition-colors">Contact</a>
                </div>

                <span style="color: rgba(255,255,255,0.18); font-size: 11px; user-select: none;">|</span>

                @auth
                    <div class="flex items-center" style="display: flex; align-items: center; gap: 9px;" x-data="{ notificationOpen: false }">
                        <!-- Notification Bell -->
                        <div class="relative">
                            <button @click="notificationOpen = !notificationOpen"
                                class="text-slate-300 hover:text-white relative transition-colors p-1 rounded-full hover:bg-white/10"
                                title="Notifications">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                    </path>
                                </svg>
                                @php
                                    $pendingReviewCount = Auth::user()->reviews()->whereNull('completed_at')->count();
                                    $activeSubmissionCount = Auth::user()->submissions()->whereNotIn('status', ['published', 'rejected'])->count();
                                    $totalNotifs = $pendingReviewCount + $activeSubmissionCount;
                                @endphp
                                @if($totalNotifs > 0)
                                    <span
                                        class="absolute top-0 right-0 block h-2 w-2 rounded-full ring-1 ring-slate-900 bg-red-500"></span>
                                @endif
                            </button>

                            <div x-show="notificationOpen" @click.away="notificationOpen = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 w-80 mt-3 origin-top-right bg-white rounded-xl shadow-2xl ring-1 ring-black/10 z-[100] overflow-hidden text-gray-800"
                                x-cloak>
                                <div
                                    class="px-4 py-3 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500">Notifications</h3>
                                    <span
                                        class="text-[10px] bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full font-bold">{{ $totalNotifs }}
                                        New</span>
                                </div>
                                <div class="max-h-80 overflow-y-auto">
                                    @if($pendingReviewCount > 0)
                                        <a href="{{ route('reviews.index') }}"
                                            class="block px-4 py-3 hover:bg-blue-50 border-b border-gray-50 transition group">
                                            <div class="flex items-start">
                                                <div
                                                    class="flex-shrink-0 bg-blue-100 text-blue-600 p-2 rounded-lg group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-[13px] font-bold text-gray-800">Pending Reviews</p>
                                                    <p class="text-[11px] text-gray-500">You have {{ $pendingReviewCount }}
                                                        assignments to complete.</p>
                                                </div>
                                            </div>
                                        </a>
                                    @endif

                                    @if($activeSubmissionCount > 0)
                                        <a href="{{ route('submission.index') }}"
                                            class="block px-4 py-3 hover:bg-green-50 border-b border-gray-50 transition group">
                                            <div class="flex items-start">
                                                <div
                                                    class="flex-shrink-0 bg-green-100 text-green-600 p-2 rounded-lg group-hover:bg-green-600 group-hover:text-white transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-[13px] font-bold text-gray-800">Active Submissions</p>
                                                    <p class="text-[11px] text-gray-500">Your manuscripts are in progress.</p>
                                                </div>
                                            </div>
                                        </a>
                                    @endif

                                    @if($totalNotifs == 0)
                                        <div class="px-4 py-8 text-center text-gray-400">
                                            <p class="text-xs">No unread notifications</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="px-4 py-2 border-t border-gray-100 bg-gray-50 text-center">
                                    <a href="{{ route('dashboard') }}"
                                        class="text-[10px] font-bold text-blue-600 hover:text-blue-800 uppercase tracking-widest transition-colors">Go
                                        to Dashboard</a>
                                </div>
                            </div>
                        </div>

                        <!-- Reviewer Portal Button -->
                        <a href="{{ route('reviews.index') }}"
                            style="background-color: #4f46e5; color: #ffffff; padding: 4px 12px; border-radius: 6px; font-weight: 800; font-size: 10px; text-transform: uppercase; letter-spacing: 0.05em; display: inline-flex; align-items: center; gap: 5px; text-decoration: none; box-shadow: 0 2px 6px rgba(79, 70, 229, 0.35);"
                            class="hover:bg-indigo-600 transition-all">
                            <svg class="w-3.5 h-3.5 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            <span>Reviewer Portal</span>
                        </a>

                        <!-- Dashboard Button -->
                        <a href="{{ route('dashboard') }}"
                            style="background-color: #0284c7; color: #ffffff; padding: 4px 12px; border-radius: 6px; font-weight: 800; font-size: 10px; text-transform: uppercase; letter-spacing: 0.05em; display: inline-flex; align-items: center; text-decoration: none; box-shadow: 0 2px 6px rgba(2, 132, 199, 0.35);"
                            class="hover:bg-sky-500 transition-all">Dashboard</a>

                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                style="font-size: 11px; font-weight: 700; letter-spacing: 0.05em; color: #cbd5e1; text-transform: uppercase;"
                                class="hover:text-red-400 transition-colors ml-1">Logout</button>
                        </form>
                    </div>
                @else
                    <div class="flex items-center" style="display: flex; align-items: center; gap: 8px;">
                        <!-- Reviewer Portal Link for Guests -->
                        <a href="{{ route('login') }}"
                            style="background-color: #4f46e5; color: #ffffff; padding: 4px 12px; border-radius: 6px; font-weight: 800; font-size: 10px; text-transform: uppercase; letter-spacing: 0.05em; display: inline-flex; align-items: center; gap: 5px; text-decoration: none; box-shadow: 0 2px 6px rgba(79, 70, 229, 0.35);"
                            class="hover:bg-indigo-600 transition-all">
                            <svg class="w-3.5 h-3.5 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            <span>Reviewer Portal</span>
                        </a>

                        <a href="{{ route('login') }}"
                            style="background-color: #0284c7; color: #ffffff; padding: 4px 12px; border-radius: 6px; font-weight: 800; font-size: 10px; text-transform: uppercase; letter-spacing: 0.05em; text-decoration: none;"
                            class="hover:bg-sky-500 transition-all">Login</a>
                        <a href="{{ route('register') }}"
                            style="font-size: 11px; font-weight: 700; letter-spacing: 0.05em; color: #cbd5e1; text-transform: uppercase; text-decoration: none;"
                            class="hover:text-blue-300 transition-colors ml-1">Register</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <!-- Main Navigation (Standard Clean Academic Style) -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50 transition-all duration-300 shadow-xs"
        :class="{ 'shadow-md': scrolled }"
        x-data="{ open: false, activeDropdown: null, searchOpen: false, advancedSearchOpen: false, scrolled: false }"
        x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 20 })">
        <div class="container mx-auto px-4 lg:px-8" style="max-width: 1400px;">
            <div class="flex justify-between items-center transition-all duration-300"
                style="height: 76px;">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group min-w-max" style="text-decoration: none;">
                    <img src="{{ asset('images/logo.png') }}" alt="HJPARAM Logo"
                        style="height: 48px; max-height: 48px; width: auto; object-fit: contain; flex-shrink: 0;"
                        class="transition-all duration-300">
                    <div class="flex flex-col justify-center">
                        <span style="font-size: 24px; font-weight: 900; font-family: ui-serif, Georgia, Cambria, 'Times New Roman', Times, serif; color: #0f172a; line-height: 1; letter-spacing: -0.02em;"
                            class="group-hover:text-blue-900 transition">HJPARAM</span>
                        <span style="font-size: 8.5px; font-weight: 700; color: #64748b; letter-spacing: 0.12em; text-transform: uppercase; margin-top: 3px; white-space: nowrap;">Academic Open Access Publishing</span>
                    </div>
                </a>

                <!-- Desktop Menu -->
                <nav
                    class="hidden lg:flex items-center h-full"
                    style="display: flex; align-items: center; gap: 20px; white-space: nowrap; margin-left: 20px;">

                    <!-- Journals Dropdown -->
                    <div class="relative h-full flex items-center" @mouseenter="activeDropdown = 'journals'"
                        @mouseleave="activeDropdown = null" style="display: flex; align-items: center;">
                        <button
                            class="hover:text-blue-700 transition flex items-center h-full border-b-2 border-transparent hover:border-blue-700"
                            style="font-size: 13.5px; font-weight: 700; color: #1e293b; white-space: nowrap; padding: 0 4px; display: flex; align-items: center; gap: 4px;"
                            :class="{ 'text-blue-700 border-blue-700': activeDropdown === 'journals' }">
                            <span>Journals</span>
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="activeDropdown === 'journals'" x-transition.opacity.duration.200ms
                            class="absolute top-full left-0 w-64 bg-white border border-slate-200 shadow-xl py-2 rounded-b-xl z-50">
                            <a href="{{ route('journals.index') }}"
                                class="block px-4 py-2 hover:bg-slate-50 hover:text-blue-800 normal-case font-medium text-xs">All
                                Journals (A-Z)</a>
                            <a href="{{ route('journals.subjects') }}"
                                class="block px-4 py-2 hover:bg-slate-50 hover:text-blue-800 normal-case font-medium text-xs">Journals
                                by Subject</a>
                            <div class="border-t border-slate-100 my-1"></div>
                            <span class="block px-4 py-1 text-[11px] text-slate-400 font-bold uppercase">Featured
                                Journals</span>
                            @foreach($featured_journals as $journal)
                                <a href="{{ route('journals.show', $journal->slug) }}"
                                    class="block px-4 py-2 hover:bg-blue-50 hover:text-blue-800 normal-case text-xs overflow-hidden truncate transition-colors">{{ $journal->title }}</a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Topics Dropdown -->
                    <div class="relative h-full flex items-center" @mouseenter="activeDropdown = 'topics'"
                        @mouseleave="activeDropdown = null" style="display: flex; align-items: center;">
                        <button
                            class="hover:text-blue-700 transition flex items-center h-full border-b-2 border-transparent hover:border-blue-700"
                            style="font-size: 13.5px; font-weight: 700; color: #1e293b; white-space: nowrap; padding: 0 4px; display: flex; align-items: center; gap: 4px;"
                            :class="{ 'text-blue-700 border-blue-700': activeDropdown === 'topics' }">
                            <span>Topics</span>
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="activeDropdown === 'topics'" x-transition.opacity.duration.200ms
                            class="absolute top-full left-0 w-56 bg-white border border-slate-200 shadow-xl py-2 rounded-b-xl z-50">
                            @foreach($global_topics as $topic)
                                <a href="{{ route('topics.show', $topic->slug) }}"
                                    class="block px-4 py-2 hover:bg-slate-50 hover:text-blue-800 normal-case font-medium text-xs">{{ $topic->name }}</a>
                            @endforeach
                            @if($global_topics->isEmpty())
                                <span class="block px-4 py-2 text-xs text-slate-500">No topics added yet.</span>
                            @endif
                        </div>
                    </div>

                    <!-- Conferences Dropdown -->
                    <div class="relative h-full flex items-center" @mouseenter="activeDropdown = 'conferences'"
                        @mouseleave="activeDropdown = null" style="display: flex; align-items: center;">
                        <button
                            class="hover:text-blue-700 transition flex items-center h-full border-b-2 border-transparent hover:border-blue-700"
                            style="font-size: 13.5px; font-weight: 700; color: #1e293b; white-space: nowrap; padding: 0 4px; display: flex; align-items: center; gap: 4px;"
                            :class="{ 'text-blue-700 border-blue-700': activeDropdown === 'conferences' }">
                            <span>Conferences</span>
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="activeDropdown === 'conferences'" x-transition.opacity.duration.200ms
                            class="absolute top-full left-0 w-60 bg-white border border-slate-200 shadow-xl py-2 rounded-b-xl z-50">
                            <a href="{{ route('conferences.index') }}"
                                class="block px-4 py-2 hover:bg-slate-50 hover:text-blue-800 normal-case font-medium text-xs">All Conferences</a>
                            <a href="{{ route('conferences.index') }}?type=upcoming"
                                class="block px-4 py-2 hover:bg-slate-50 hover:text-blue-800 normal-case font-medium text-xs">Upcoming Conferences</a>
                            <div class="border-t border-slate-100 my-1"></div>
                            <a href="{{ route('info.page', 'proposals') }}"
                                class="block px-4 py-2 hover:bg-blue-50 text-blue-700 font-bold normal-case text-xs">+ Propose Conference</a>
                        </div>
                    </div>

                    <!-- Author Services Dropdown -->
                    <div class="relative h-full flex items-center" @mouseenter="activeDropdown = 'authors'"
                        @mouseleave="activeDropdown = null" style="display: flex; align-items: center;">
                        <button
                            class="hover:text-blue-700 transition flex items-center h-full border-b-2 border-transparent hover:border-blue-700"
                            style="font-size: 13.5px; font-weight: 700; color: #1e293b; white-space: nowrap; padding: 0 4px; display: flex; align-items: center; gap: 4px;"
                            :class="{ 'text-blue-700 border-blue-700': activeDropdown === 'authors' }">
                            <span>Author Services</span>
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="activeDropdown === 'authors'" x-transition.opacity.duration.200ms
                            class="absolute top-full left-0 w-64 bg-white border border-slate-200 shadow-xl py-2 rounded-b-xl z-50">
                            <a href="{{ route('author.submit') }}"
                                class="block px-4 py-2 hover:bg-slate-50 hover:text-blue-800 normal-case font-medium text-xs">Submit
                                Manuscript</a>
                            <a href="{{ route('author.guidelines') }}"
                                class="block px-4 py-2 hover:bg-slate-50 hover:text-blue-800 normal-case font-medium text-xs">Author
                                Guidelines</a>
                            @foreach($menu_author as $page)
                                <a href="{{ route('author.page', $page->slug) }}"
                                    class="block px-4 py-2 hover:bg-slate-50 hover:text-blue-800 normal-case font-medium text-xs">{{ $page->title }}</a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Initiatives Dropdown -->
                    <div class="relative h-full flex items-center" @mouseenter="activeDropdown = 'initiatives'"
                        @mouseleave="activeDropdown = null" style="display: flex; align-items: center;">
                        <button
                            class="hover:text-blue-700 transition flex items-center h-full border-b-2 border-transparent hover:border-blue-700"
                            style="font-size: 13.5px; font-weight: 700; color: #1e293b; white-space: nowrap; padding: 0 4px; display: flex; align-items: center; gap: 4px;"
                            :class="{ 'text-blue-700 border-blue-700': activeDropdown === 'initiatives' }">
                            <span>Initiatives</span>
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="activeDropdown === 'initiatives'" x-transition.opacity.duration.200ms
                            class="absolute top-full left-0 w-56 bg-white border border-slate-200 shadow-xl py-2 rounded-b-xl z-50">
                            @foreach($menu_initiatives as $page)
                                <a href="{{ route('initiatives.show', $page->slug) }}"
                                    class="block px-4 py-2 hover:bg-slate-50 hover:text-blue-800 normal-case font-medium text-xs">{{ $page->title }}</a>
                            @endforeach
                            @if($menu_initiatives->isEmpty())
                                <span class="block px-4 py-2 text-xs text-slate-400">No initiatives</span>
                            @endif
                        </div>
                    </div>

                    <!-- About Dropdown -->
                    <div class="relative h-full flex items-center" @mouseenter="activeDropdown = 'about'"
                        @mouseleave="activeDropdown = null" style="display: flex; align-items: center;">
                        <button
                            class="hover:text-blue-700 transition flex items-center h-full border-b-2 border-transparent hover:border-blue-700"
                            style="font-size: 13.5px; font-weight: 700; color: #1e293b; white-space: nowrap; padding: 0 4px; display: flex; align-items: center; gap: 4px;"
                            :class="{ 'text-blue-700 border-blue-700': activeDropdown === 'about' }">
                            <span>About</span>
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="activeDropdown === 'about'" x-transition.opacity.duration.200ms
                            class="absolute top-full left-0 w-56 bg-white border border-slate-200 shadow-xl py-2 rounded-b-xl z-50">
                            @foreach($menu_about as $page)
                                <a href="{{ route('about.page', $page->slug) }}"
                                    class="block px-4 py-2 hover:bg-slate-50 hover:text-blue-800 normal-case font-medium text-xs">{{ $page->title }}</a>
                            @endforeach
                            @if($menu_about->isEmpty())
                                <span class="block px-4 py-2 text-xs text-slate-500">No pages added.</span>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons: Round Search + Pay Fee + Submit -->
                    <div style="display: flex; align-items: center; gap: 10px; margin-left: 8px; flex-shrink: 0;">
                        <!-- Round Dark Blue Search Button -->
                        <button @click="searchOpen = !searchOpen"
                            style="width: 38px; height: 38px; min-width: 38px; border-radius: 9999px; background-color: #1e293b; color: #ffffff; display: flex; align-items: center; justify-content: center; border: none; cursor: pointer; flex-shrink: 0; box-shadow: 0 2px 8px rgba(30,41,59,0.25);"
                            class="hover:bg-blue-600 transition-all hover:scale-105"
                            title="Search">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>

                        <!-- Orange / Amber Gradient Pay Fee Pill Button -->
                        <a href="{{ route('payments.create') }}"
                            style="background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%); color: #ffffff; padding: 8px 18px; border-radius: 9999px; font-weight: 800; font-size: 11.5px; text-transform: uppercase; letter-spacing: 0.04em; display: inline-flex; align-items: center; gap: 5px; text-decoration: none; white-space: nowrap; flex-shrink: 0; box-shadow: 0 4px 12px rgba(234, 88, 12, 0.35);"
                            class="hover:opacity-95 hover:scale-105 transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span>Pay Fee</span>
                        </a>

                        <!-- Emerald / Teal Gradient Submit Button -->
                        <a href="{{ route('author.submit') }}"
                            style="background: linear-gradient(135deg, #0284c7 0%, #059669 100%); color: #ffffff; padding: 8px 20px; border-radius: 9999px; font-weight: 800; font-size: 11.5px; text-transform: uppercase; letter-spacing: 0.04em; display: inline-flex; align-items: center; gap: 5px; text-decoration: none; white-space: nowrap; flex-shrink: 0; box-shadow: 0 4px 12px rgba(5, 150, 105, 0.35);"
                            class="hover:opacity-95 hover:scale-105 transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                            <span>Submit</span>
                        </a>
                    </div>

                    <!-- Search Overlay Form -->
                    <div x-show="searchOpen" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-2"
                        class="absolute top-[76px] left-0 w-full bg-white border-b border-gray-200 shadow-md p-4 z-40"
                        @click.away="searchOpen = false">
                        <div class="container mx-auto max-w-4xl">
                            <form action="{{ route('search') }}" method="GET" class="flex gap-3">
                                <div class="relative flex-grow">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </span>
                                    <input type="text" name="q"
                                        placeholder="Search for journals, articles, authors or DOIs..."
                                        class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg leading-5 bg-gray-50 placeholder-gray-500 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-150 ease-in-out"
                                        autofocus>
                                </div>
                                <button type="button" @click="advancedSearchOpen = !advancedSearchOpen"
                                    class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-blue-900 flex items-center gap-1 group">
                                    <span x-text="advancedSearchOpen ? 'Simple' : 'Advanced'">Advanced</span>
                                    <svg class="w-4 h-4" :class="advancedSearchOpen ? 'rotate-180' : ''" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <button type="submit"
                                    class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-bold rounded-lg text-white bg-blue-900 hover:bg-blue-800 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-900 transition duration-150 ease-in-out">
                                    Search
                                </button>
                            </form>

                            <!-- Inline Advanced Filters -->
                            <div x-show="advancedSearchOpen" x-transition
                                class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4 border-t pt-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Author</label>
                                    <input type="text" name="author"
                                        class="w-full text-sm border-gray-300 rounded-md focus:ring-blue-500"
                                        placeholder="Author name">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Journal</label>
                                    <select name="journal"
                                        class="w-full text-sm border-gray-300 rounded-md focus:ring-blue-500">
                                        <option value="">All Journals</option>
                                        @foreach($global_journals ?? \App\Models\Journal::all() as $journal)
                                            <option value="{{ $journal->id }}">{{ $journal->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Year
                                        range</label>
                                    <div class="flex gap-2">
                                        <input type="number" name="year_from"
                                            class="w-1/2 text-sm border-gray-300 rounded-md focus:ring-blue-500"
                                            placeholder="From">
                                        <input type="number" name="year_to"
                                            class="w-1/2 text-sm border-gray-300 rounded-md focus:ring-blue-500"
                                            placeholder="To">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </nav>

                <!-- Mobile Menu Button -->
                <button @click="open = !open" class="lg:hidden text-gray-500 hover:text-blue-800 focus:outline-none">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div x-show="open" class="lg:hidden bg-white border-t border-gray-200 shadow-inner p-4 space-y-4">
            <div class="grid grid-cols-2 gap-3 pb-3 border-b border-gray-100">
                <a href="{{ route('author.submit') }}" class="py-2.5 px-3 bg-blue-600 text-white rounded-xl text-center font-bold text-xs shadow-md">
                    Submit Manuscript
                </a>
                <a href="{{ route('payments.create') }}" class="py-2.5 px-3 bg-slate-100 text-slate-800 rounded-xl text-center font-bold text-xs border border-slate-200">
                    Pay Fees
                </a>
            </div>
            <a href="{{ route('journals.index') }}" class="block font-bold text-gray-800">Journals</a>
            <a href="{{ route('topics.index') }}" class="block font-bold text-gray-800">Topics</a>
            <a href="{{ route('info.page', 'about') }}" class="block font-bold text-gray-800">Information</a>
            <a href="{{ route('author.submit') }}" class="block font-bold text-gray-800">Author Services</a>
            <a href="{{ route('about.page', 'contact') }}" class="block font-bold text-gray-800">About</a>
        </div>
    </header>

    <main class="flex-grow">
        @yield('content')
    </main>

    @include('partials.footer')

</body>

</html>