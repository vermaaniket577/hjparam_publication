<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">

    <!-- SEO & Open Graph Metadata -->
    @include('partials.og-metadata')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-700 bg-white flex flex-col min-h-screen">

    @include('components.web-loader')

    <div class="bg-slate-900 text-white text-[10px] py-1.5 border-b border-white/5 relative z-[60] transition-all duration-300 transform"
        x-data="{ visible: true }" x-show="visible"
        x-init="window.addEventListener('scroll', () => { visible = window.scrollY < 100 })"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="-translate-y-full opacity-0"
        x-transition:enter-end="translate-y-0 opacity-100" x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="-translate-y-full opacity-0">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <div class="flex items-center space-x-6">
                <a href="{{ route('sciforum.index') }}"
                    class="hover:text-blue-400 transition-colors uppercase tracking-widest font-bold">Sciforum</a>
                <a href="{{ route('preprints.index') }}"
                    class="hover:text-blue-400 transition-colors uppercase tracking-widest font-bold">Preprints</a>
                <a href="{{ route('scilit.index') }}"
                    class="hover:text-blue-400 transition-colors uppercase tracking-widest font-bold">Scilit</a>
                <a href="{{ route('sciprofiles.index') }}"
                    class="hover:text-blue-400 transition-colors uppercase tracking-widest font-bold">SciProfiles</a>
                <a href="{{ route('encyclopedia.index') }}"
                    class="hover:text-blue-400 transition-colors uppercase tracking-widest font-bold">Encyclopedia</a>
                <a href="{{ route('jams.index') }}"
                    class="hover:text-blue-400 transition-colors uppercase tracking-widest font-bold text-blue-300">JAMS</a>
            </div>
            <div class="flex items-center space-x-6">
                <div class="hidden md:flex items-center space-x-4 border-r border-white/10 pr-4 mr-2">
                    <a href="{{ route('rss.feed') }}"
                        class="hover:text-blue-300 transition-colors uppercase tracking-widest font-bold">RSS</a>
                    <a href="{{ route('about.page', 'contact') }}"
                        class="hover:text-blue-300 transition-colors uppercase tracking-widest font-bold">Contact</a>
                </div>
                @auth
                    <div class="flex items-center space-x-3" x-data="{ notificationOpen: false }">
                        <!-- Notification Bell (Web Layout) -->
                        <div class="relative">
                            <button @click="notificationOpen = !notificationOpen"
                                class="text-white/70 hover:text-white relative transition-colors">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                class="absolute right-0 w-80 mt-6 origin-top-right bg-white rounded-lg shadow-2xl ring-1 ring-black ring-opacity-5 z-[100] overflow-hidden text-gray-800"
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

                        <a href="{{ route('dashboard') }}"
                            class="bg-blue-600 px-3 py-0.5 rounded text-white font-bold hover:bg-blue-500 transition shadow-lg shadow-blue-900/40 uppercase tracking-wider text-[9px]">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="hover:text-blue-300 transition-colors font-bold uppercase tracking-widest">Logout</button>
                        </form>
                    </div>
                @else
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}"
                            class="hover:text-blue-300 transition-colors font-bold uppercase tracking-widest">Login</a>
                        <span class="text-white/20">|</span>
                        <a href="{{ route('register') }}"
                            class="hover:text-blue-300 transition-colors font-bold uppercase tracking-widest">Register</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <!-- Main Navigation (MDPI Style) -->
    <header class="bg-white/95 backdrop-blur-md border-b border-gray-200 sticky top-0 z-50 transition-all duration-300"
        :class="{ 'shadow-lg py-1': scrolled, 'shadow-sm py-0': !scrolled }"
        x-data="{ open: false, activeDropdown: null, searchOpen: false, advancedSearchOpen: false, scrolled: false }"
        x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 20 })">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-20 transition-all duration-300"
                :class="{ 'h-16': scrolled }">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center space-x-3 group min-w-max">
                    <img src="{{ asset('images/logo.png') }}" alt="HJPARAM Logo" class="h-12 w-auto object-contain">
                    <div class="flex flex-col justify-center">
                        <span
                            class="text-2xl font-serif font-bold text-gray-800 leading-tight group-hover:text-blue-900 transition">HJPARAM</span>
                        <span class="text-[0.65rem] uppercase font-semibold tracking-widest text-gray-500">Academic Open
                            Access
                            Publishing</span>
                    </div>
                </a>

                <!-- Desktop Menu -->
                <nav
                    class="hidden lg:flex items-center space-x-4 ml-6 text-sm font-bold text-gray-700 uppercase tracking-tight h-full">

                    <!-- Journals Dropdown -->
                    <div class="relative h-full flex items-center" @mouseenter="activeDropdown = 'journals'"
                        @mouseleave="activeDropdown = null">
                        <button
                            class="px-3 py-2 hover:text-blue-800 transition flex items-center h-full border-b-2 border-transparent hover:border-blue-800"
                            :class="{ 'text-blue-800 border-blue-800': activeDropdown === 'journals' }">
                            Journals
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="activeDropdown === 'journals'" x-transition.opacity.duration.200ms
                            class="absolute top-full left-0 w-64 bg-white border border-gray-200 shadow-lg py-2 rounded-b-md z-50">
                            <a href="{{ route('journals.index') }}"
                                class="block px-4 py-2 hover:bg-gray-50 hover:text-blue-800 normal-case font-medium">All
                                Journals (A-Z)</a>
                            <a href="{{ route('journals.subjects') }}"
                                class="block px-4 py-2 hover:bg-gray-50 hover:text-blue-800 normal-case font-medium">Journals
                                by Subject</a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <span class="block px-4 py-1 text-xs text-gray-400 font-bold uppercase">Featured
                                Journals</span>
                            @foreach($featured_journals as $journal)
                                <a href="{{ route('journals.show', $journal->slug) }}"
                                    class="block px-4 py-2 hover:bg-blue-50 hover:text-blue-800 normal-case text-xs overflow-hidden truncate transition-colors">{{ $journal->title }}</a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Topics Dropdown -->
                    <div class="relative h-full flex items-center" @mouseenter="activeDropdown = 'topics'"
                        @mouseleave="activeDropdown = null">
                        <button
                            class="px-3 py-2 hover:text-blue-800 transition flex items-center h-full border-b-2 border-transparent hover:border-blue-800"
                            :class="{ 'text-blue-800 border-blue-800': activeDropdown === 'topics' }">
                            Topics
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="activeDropdown === 'topics'" x-transition.opacity.duration.200ms
                            class="absolute top-full left-0 w-56 bg-white border border-gray-200 shadow-lg py-2 rounded-b-md z-50">
                            @foreach($global_topics as $topic)
                                <a href="{{ route('topics.show', $topic->slug) }}"
                                    class="block px-4 py-2 hover:bg-gray-50 hover:text-blue-800 normal-case font-medium">{{ $topic->name }}</a>
                            @endforeach
                            @if($global_topics->isEmpty())
                                <span class="block px-4 py-2 text-xs text-gray-500">No topics added yet.</span>
                            @endif
                        </div>
                    </div>

                    <!-- Information Dropdown -->
                    <div class="relative h-full flex items-center" @mouseenter="activeDropdown = 'info'"
                        @mouseleave="activeDropdown = null">
                        <button
                            class="px-3 py-2 hover:text-blue-800 transition flex items-center h-full border-b-2 border-transparent hover:border-blue-800"
                            :class="{ 'text-blue-800 border-blue-800': activeDropdown === 'info' }">
                            Information
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="activeDropdown === 'info'" x-transition.opacity.duration.200ms
                            class="absolute top-full left-0 w-56 bg-white border border-gray-200 shadow-lg py-2 rounded-b-md z-50">
                            @foreach($menu_info as $page)
                                <a href="{{ route('info.page', $page->slug) }}"
                                    class="block px-4 py-2 hover:bg-gray-50 hover:text-blue-800 normal-case font-medium">{{ $page->title }}</a>
                            @endforeach
                            @if($menu_info->isEmpty())
                                <span class="block px-4 py-2 text-xs text-gray-400">No pages</span>
                            @endif
                        </div>
                    </div>

                    <!-- Author Services Dropdown -->
                    <div class="relative h-full flex items-center" @mouseenter="activeDropdown = 'authors'"
                        @mouseleave="activeDropdown = null">
                        <button
                            class="px-3 py-2 hover:text-blue-800 transition flex items-center h-full border-b-2 border-transparent hover:border-blue-800"
                            :class="{ 'text-blue-800 border-blue-800': activeDropdown === 'authors' }">
                            Author Services
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="activeDropdown === 'authors'" x-transition.opacity.duration.200ms
                            class="absolute top-full left-0 w-64 bg-white border border-gray-200 shadow-lg py-2 rounded-b-md z-50">
                            <a href="{{ route('author.submit') }}"
                                class="block px-4 py-2 hover:bg-gray-50 hover:text-blue-800 normal-case font-medium">Submit
                                Manuscript</a>
                            <a href="{{ route('author.guidelines') }}"
                                class="block px-4 py-2 hover:bg-gray-50 hover:text-blue-800 normal-case font-medium">Author
                                Guidelines</a>
                            @foreach($menu_author as $page)
                                <a href="{{ route('author.page', $page->slug) }}"
                                    class="block px-4 py-2 hover:bg-gray-50 hover:text-blue-800 normal-case font-medium">{{ $page->title }}</a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Initiatives Dropdown -->
                    <div class="relative h-full flex items-center" @mouseenter="activeDropdown = 'initiatives'"
                        @mouseleave="activeDropdown = null">
                        <button
                            class="px-3 py-2 hover:text-blue-800 transition flex items-center h-full border-b-2 border-transparent hover:border-blue-800"
                            :class="{ 'text-blue-800 border-blue-800': activeDropdown === 'initiatives' }">
                            Initiatives
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="activeDropdown === 'initiatives'" x-transition.opacity.duration.200ms
                            class="absolute top-full left-0 w-56 bg-white border border-gray-200 shadow-lg py-2 rounded-b-md z-50">
                            @foreach($menu_initiatives as $page)
                                <a href="{{ route('initiatives.show', $page->slug) }}"
                                    class="block px-4 py-2 hover:bg-gray-50 hover:text-blue-800 normal-case font-medium">{{ $page->title }}</a>
                            @endforeach
                            @if($menu_initiatives->isEmpty())
                                <span class="block px-4 py-2 text-xs text-gray-400">No initiatives</span>
                            @endif
                        </div>
                    </div>

                    <div class="relative h-full flex items-center" @mouseenter="activeDropdown = 'about'"
                        @mouseleave="activeDropdown = null">
                        <button
                            class="px-3 py-2 hover:text-blue-800 transition flex items-center h-full border-b-2 border-transparent hover:border-blue-800"
                            :class="{ 'text-blue-800 border-blue-800': activeDropdown === 'about' }">
                            About
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="activeDropdown === 'about'" x-transition.opacity.duration.200ms
                            class="absolute top-full left-0 w-56 bg-white border border-gray-200 shadow-lg py-2 rounded-b-md z-50">
                            @foreach($menu_about as $page)
                                <a href="{{ route('about.page', $page->slug) }}"
                                    class="block px-4 py-2 hover:bg-gray-50 hover:text-blue-800 normal-case font-medium">{{ $page->title }}</a>
                            @endforeach
                            @if($menu_about->isEmpty())
                                <span class="block px-4 py-2 text-xs text-gray-500">No pages added.</span>
                            @endif
                        </div>
                    </div>

                    <!-- Search Toggle -->
                    <div class="ml-4 flex items-center">
                        <button @click="searchOpen = !searchOpen"
                            class="text-gray-500 hover:text-blue-800 transition transform hover:scale-110">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Search Overlay Form -->
                    <div x-show="searchOpen" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-2"
                        class="absolute top-20 left-0 w-full bg-white border-b border-gray-200 shadow-md p-4 z-40"
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

        <!-- Mobile Navigation (Simple, could be expanded to accordions later) -->
        <div x-show="open" class="lg:hidden bg-white border-t border-gray-200 shadow-inner p-4 space-y-4">
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