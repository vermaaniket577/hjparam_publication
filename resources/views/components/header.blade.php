@props(['scrolled' => false])

<header x-data="{ 
    open: false, 
    scrolled: false, 
    searchOpen: false, 
    activeDropdown: null 
}" 
    x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 20 })"
    :class="{ 'h-20 shadow-lg': scrolled, 'h-24 shadow-sm': !scrolled }"
    class="bg-white sticky top-0 z-[100] transition-all duration-300 border-b border-gray-100 flex items-center px-6 lg:px-12 font-inter">
    
    <div class="container mx-auto flex justify-between items-center w-full">
        <!-- Left: Logo Area -->
        <a href="{{ route('home') }}" class="flex items-center gap-3.5 group brand-logo-link flex-shrink-0 py-1 focus:outline-none">
            <div class="relative flex items-center justify-center flex-shrink-0" style="height: 48px; width: 48px;">
                <div class="absolute -inset-1 bg-gradient-to-r from-blue-600/20 via-indigo-500/20 to-emerald-500/20 rounded-full blur-md opacity-0 group-hover:opacity-100 transition-all duration-300 pointer-events-none"></div>
                <img src="{{ asset('images/logo.png') }}?v=3" 
                     alt="HJPARAM Logo" 
                     style="height: 46px; max-height: 46px; width: auto; max-width: 46px;"
                     class="h-[46px] max-h-[46px] w-auto object-contain relative z-10 brand-logo-wrap">
            </div>
            <div class="flex flex-col justify-center">
                <span class="text-xl lg:text-2xl font-serif font-extrabold tracking-tight leading-none brand-title">
                    HJPARAM
                </span>
                <span class="text-[9px] lg:text-[10px] font-bold tracking-[0.16em] uppercase mt-1 brand-subtitle">
                    Academic Open Access Publishing
                </span>
            </div>
        </a>

        <!-- Center: Main Navigation -->
        <nav class="hidden lg:flex items-center space-x-2 xl:space-x-4 flex-grow justify-center px-2">
            <!-- Journals -->
            <a href="{{ route('journals.index') }}" 
                class="relative px-2.5 py-2 text-[14px] xl:text-[15px] font-bold text-gray-700 hover:text-[#1E3A8A] transition-colors group">
                Journals
                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-[#1E3A8A] transition-all duration-300 group-hover:w-full"></span>
            </a>

            <!-- Participate Dropdown -->
            <div class="relative group h-full py-4 text-[14px] xl:text-[15px]" @mouseenter="activeDropdown = 'participate'" @mouseleave="activeDropdown = null">
                <button class="flex items-center gap-1.5 px-2.5 py-2 font-bold text-gray-700 hover:text-[#1E3A8A] transition-colors">
                    Participate
                </button>
                <div x-show="activeDropdown === 'participate'" 
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-2"
                    class="absolute top-full left-0 w-64 bg-white rounded-xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] border border-gray-100 py-3 z-50 text-left" x-cloak>
                    <a href="{{ route('submission.create') }}" class="flex items-center gap-4 px-5 py-3 hover:bg-[#F3F4F6] transition group">
                        <div class="w-9 h-9 bg-blue-50 text-[#1E3A8A] rounded-lg flex items-center justify-center font-bold group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <div>
                            <span class="block text-sm font-bold text-gray-800">Submit Journal</span>
                            <span class="block text-[10px] text-gray-400">Protocol for authors</span>
                        </div>
                    </a>
                    <a href="{{ route('payments.create') }}" class="flex items-center gap-4 px-5 py-3 hover:bg-amber-50/50 transition group">
                        <div class="w-9 h-9 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center font-bold group-hover:bg-amber-600 group-hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <div>
                            <span class="block text-sm font-bold text-gray-800">Pay Publication Fee</span>
                            <span class="block text-[10px] text-amber-600 font-semibold">APC & Paper Fees</span>
                        </div>
                    </a>
                    <a href="{{ route('subscribe.page') }}" class="flex items-center gap-4 px-5 py-3 hover:bg-[#F3F4F6] transition group">
                        <div class="w-9 h-9 bg-emerald-50 text-[#10B981] rounded-lg flex items-center justify-center font-bold group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <div>
                            <span class="block text-sm font-bold text-gray-800">Alert Subs</span>
                            <span class="block text-[10px] text-gray-400">Email intelligence feed</span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Topics Dropdown -->
            <div class="relative group h-full py-4 text-[14px] xl:text-[15px]" @mouseenter="activeDropdown = 'topics'" @mouseleave="activeDropdown = null">
                <button class="flex items-center gap-1.5 px-2.5 py-2 font-bold text-gray-700 hover:text-[#1E3A8A] transition-colors">
                    Topics
                </button>
                <div x-show="activeDropdown === 'topics'" 
                    class="absolute top-full left-0 w-64 bg-white rounded-xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] border border-gray-100 py-3 z-50 overflow-hidden text-left" x-cloak>
                    <div class="max-h-[70vh] overflow-y-auto scrollbar-hide">
                        @foreach($global_topics as $topic)
                            <a href="{{ route('topics.show', $topic->slug) }}" class="block px-6 py-2.5 text-sm font-medium text-gray-600 hover:text-[#1E3A8A] hover:bg-gray-50 transition">
                                {{ $topic->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Author Services -->
            <div class="relative group h-full py-4 text-[14px] xl:text-[15px]" @mouseenter="activeDropdown = 'authors'" @mouseleave="activeDropdown = null">
                <button class="flex items-center gap-1.5 px-2.5 py-2 font-bold text-gray-700 hover:text-[#1E3A8A] transition-colors">
                    Author Services
                </button>
                <div x-show="activeDropdown === 'authors'" 
                    class="absolute top-full left-0 w-64 bg-white rounded-xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] border border-gray-100 py-3 z-50 text-left" x-cloak>
                    <a href="{{ route('author.submit') }}" class="block px-6 py-2.5 text-sm font-medium text-gray-600 hover:text-[#1E3A8A] hover:bg-gray-50">Submit Manuscript</a>
                    <a href="{{ route('author.guidelines') }}" class="block px-6 py-2.5 text-sm font-medium text-gray-600 hover:text-[#1E3A8A] hover:bg-gray-50">Guidelines</a>
                    <a href="{{ route('payments.create') }}" class="block px-6 py-2.5 text-sm font-bold text-amber-600 hover:text-amber-700 hover:bg-amber-50/50">Pay Fees / APC</a>
                    @foreach($menu_author as $page)
                        <a href="{{ route('author.page', $page->slug) }}" class="block px-6 py-2.5 text-sm font-medium text-gray-600 hover:text-[#1E3A8A] hover:bg-gray-50">{{ $page->title }}</a>
                    @endforeach
                </div>
            </div>

            <!-- Conferences Dropdown (Replaces Information) -->
            <div class="relative group h-full py-4 text-[14px] xl:text-[15px]" @mouseenter="activeDropdown = 'conferences'" @mouseleave="activeDropdown = null">
                <button class="flex items-center gap-1.5 px-2.5 py-2 font-bold text-gray-700 hover:text-[#1E3A8A] transition-colors focus:outline-none"
                    aria-expanded="false" 
                    :aria-expanded="activeDropdown === 'conferences' ? 'true' : 'false'" 
                    aria-haspopup="true"
                    id="conferences-menu-button">
                    <span>Conferences</span>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180 text-[#1E3A8A]': activeDropdown === 'conferences' }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="activeDropdown === 'conferences'" 
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                    class="absolute top-full left-0 w-80 bg-white rounded-2xl shadow-[0_20px_50px_rgba(30,58,138,0.15)] border border-slate-100 p-3 z-50 text-left" 
                    role="menu" 
                    aria-orientation="vertical" 
                    aria-labelledby="conferences-menu-button"
                    x-cloak>
                    
                    <!-- 1. Upcoming Conferences -->
                    <div class="p-2">
                        <div class="flex items-center justify-between mb-1.5 px-2">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-blue-600 flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                                Upcoming Conferences
                            </span>
                            <span class="text-[9px] font-semibold bg-blue-50 text-blue-700 px-1.5 py-0.5 rounded-full">Future</span>
                        </div>
                        <a href="{{ route('conferences.index', ['filter' => 'upcoming']) }}" class="flex items-start gap-3 p-2 rounded-xl hover:bg-blue-50/70 transition-all duration-150 group/item" role="menuitem">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center flex-shrink-0 group-hover/item:bg-blue-600 group-hover/item:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div class="flex-grow min-w-0">
                                <span class="block text-xs font-bold text-gray-800 group-hover/item:text-blue-700 transition-colors">Upcoming Events & Summits</span>
                                <span class="block text-[10px] text-gray-500 truncate">Call for papers & open registrations</span>
                            </div>
                        </a>
                    </div>

                    <div class="h-px bg-gray-100 my-1"></div>

                    <!-- 2. Past Conferences -->
                    <div class="p-2">
                        <div class="flex items-center justify-between mb-1.5 px-2">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-600 flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Past Conferences
                            </span>
                            <span class="text-[9px] font-semibold bg-emerald-50 text-emerald-700 px-1.5 py-0.5 rounded-full">Completed</span>
                        </div>
                        <a href="{{ route('conferences.index', ['filter' => 'past']) }}" class="flex items-start gap-3 p-2 rounded-xl hover:bg-emerald-50/70 transition-all duration-150 group/item" role="menuitem">
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0 group-hover/item:bg-emerald-600 group-hover/item:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div class="flex-grow min-w-0">
                                <span class="block text-xs font-bold text-gray-800 group-hover/item:text-emerald-700 transition-colors">Recent Past Events</span>
                                <span class="block text-[10px] text-gray-500 truncate">Proceedings & post-event summaries</span>
                            </div>
                        </a>
                    </div>

                    <div class="h-px bg-gray-100 my-1"></div>

                    <!-- 3. Old Conferences -->
                    <div class="p-2">
                        <div class="flex items-center justify-between mb-1.5 px-2">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-500 flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                Old Conferences
                            </span>
                            <span class="text-[9px] font-semibold bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded-full">Archived</span>
                        </div>
                        <a href="{{ route('conferences.index', ['filter' => 'archived']) }}" class="flex items-start gap-3 p-2 rounded-xl hover:bg-slate-100/70 transition-all duration-150 group/item" role="menuitem">
                            <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center flex-shrink-0 group-hover/item:bg-slate-700 group-hover/item:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                            </div>
                            <div class="flex-grow min-w-0">
                                <span class="block text-xs font-bold text-gray-800 group-hover/item:text-slate-800 transition-colors">Legacy & Conference Archives</span>
                                <span class="block text-[10px] text-gray-500 truncate">Historical repository & prior editions</span>
                            </div>
                        </a>
                    </div>

                    <!-- Footer Link -->
                    <div class="mt-2 pt-2 border-t border-slate-100 px-2 flex items-center justify-between">
                        <a href="{{ route('conferences.index') }}" class="text-[11px] font-bold text-[#1E3A8A] hover:underline flex items-center gap-1">
                            Browse All Conferences
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- About -->
            <div class="relative group h-full py-4 text-[14px] xl:text-[15px]" @mouseenter="activeDropdown = 'about'" @mouseleave="activeDropdown = null">
                <button class="flex items-center gap-1.5 px-2.5 py-2 font-bold text-gray-700 hover:text-[#1E3A8A] transition-colors group">
                    About
                </button>
                <div x-show="activeDropdown === 'about'" 
                    class="absolute top-full left-0 w-64 bg-white rounded-xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] border border-gray-100 py-3 z-50 text-left" x-cloak>
                    @foreach($menu_about as $page)
                        <a href="{{ route('about.page', $page->slug) }}" class="block px-6 py-2.5 text-sm font-medium text-gray-600 hover:text-[#1E3A8A] hover:bg-gray-50 transition">{{ $page->title }}</a>
                    @endforeach
                </div>
            </div>

            <!-- Pay Fees Link -->
            <a href="{{ route('payments.create') }}" 
                class="relative px-2.5 py-2 text-[14px] xl:text-[15px] font-bold text-amber-600 hover:text-amber-700 transition-colors group flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Pay Fees
                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-amber-500 transition-all duration-300 group-hover:w-full"></span>
            </a>
        </nav>

        <!-- Right: Actions Area -->
        <div class="flex items-center gap-2 flex-shrink-0">
            <!-- Search Icon & Expanded Form -->
            <div class="relative flex items-center h-full" x-data="{ localSearchOpen: false }">
                <button @click="localSearchOpen = !localSearchOpen; if(localSearchOpen) $nextTick(() => $refs.searchInput.focus())" 
                    style="background-color: #1E3A8A !important; color: #ffffff !important; width: 38px; height: 38px; border-radius: 9999px; display: flex; align-items: center; justify-content: center; border: none; cursor: pointer; box-shadow: 0 2px 8px rgba(30, 58, 138, 0.25);"
                    class="hover:opacity-90 hover:scale-105 transition-all duration-200 relative z-20 flex-shrink-0"
                    title="Search">
                    <svg x-show="!localSearchOpen" style="width: 16px; height: 16px; stroke: #ffffff;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <svg x-show="localSearchOpen" style="width: 16px; height: 16px; stroke: #ffffff;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
                
                <div x-show="localSearchOpen" 
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
                    class="absolute top-12 right-0 w-[280px] lg:w-[400px] bg-white p-4 rounded-xl shadow-2xl border border-gray-100 z-10" x-cloak @click.away="localSearchOpen = false">
                    <form action="{{ route('search') }}" method="GET" class="flex gap-2">
                        <input type="text" name="q" x-ref="searchInput" placeholder="Search..." 
                            class="flex-grow px-3 py-2 bg-gray-50 border-none focus:ring-2 focus:ring-[#1E3A8A]/20 rounded-lg text-sm font-medium text-gray-700">
                        <button type="submit" class="bg-[#1E3A8A] text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-[#162a63] transition-colors">Find</button>
                    </form>
                </div>
            </div>

            <!-- CTA: Pay Fee Button -->
            <a href="{{ route('payments.create') }}" 
                style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 50%, #ea580c 100%) !important; color: #ffffff !important; box-shadow: 0 4px 14px rgba(217, 119, 6, 0.35); text-decoration: none;"
                class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-full text-xs font-black uppercase tracking-wider hover:opacity-95 hover:scale-105 transition-all duration-200 flex-shrink-0">
                <svg style="width: 14px; height: 14px; stroke: #ffffff;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span style="color: #ffffff !important; font-weight: 900;">Pay Fee</span>
            </a>

            <!-- CTA: Submit Paper -->
            <a href="{{ route('submission.create') }}" 
                style="background: linear-gradient(135deg, #0284c7 0%, #0d9488 50%, #16a34a 100%) !important; color: #ffffff !important; box-shadow: 0 4px 14px rgba(13, 148, 136, 0.35); margin-right: 8px;"
                class="hidden sm:inline-flex items-center gap-1.5 px-5 py-2.5 rounded-full text-xs font-black uppercase tracking-wider hover:opacity-95 hover:scale-105 transition-all duration-200 flex-shrink-0 mr-2">
                <span>Submit</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            </a>

            <!-- Mobile Hamburger -->
            <button @click="open = !open" class="lg:hidden p-2 text-gray-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
        </div>
    </div>

    <!-- Mobile Drawer -->
    <div x-show="open" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[110] lg:hidden" x-cloak @click="open = false"></div>
    
    <div x-show="open" 
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="fixed inset-y-0 right-0 w-80 bg-white shadow-2xl z-[120] lg:hidden flex flex-col pt-8" x-cloak>
        
        <div class="px-6 flex justify-between items-center mb-8">
            <span class="text-xl font-black text-[#1E3A8A]">Navigation</span>
            <button @click="open = false" class="p-2 text-gray-400 hover:text-red-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
        </div>

        <div class="flex-grow overflow-y-auto px-6 space-y-4">
            <a href="{{ route('journals.index') }}" class="block text-lg font-bold text-gray-800 py-2 border-b border-gray-50">Journals</a>
            <div x-data="{ confOpen: false }">
                <button @click="confOpen = !confOpen" class="w-full flex justify-between items-center text-lg font-bold text-gray-800 py-2 border-b border-gray-50">
                    <span>Conferences</span>
                    <svg class="w-4 h-4 transform transition-transform duration-200 text-gray-400" :class="{ 'rotate-180 text-blue-600': confOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="confOpen" class="pl-4 py-2 space-y-2.5 bg-slate-50/50 rounded-xl my-1" x-transition>
                    <a href="{{ route('conferences.index') }}" class="block text-sm font-bold text-[#1E3A8A]">All Conferences</a>
                    <a href="{{ route('conferences.index', ['filter' => 'upcoming']) }}" class="flex items-center justify-between text-sm font-medium text-gray-600 hover:text-blue-600">
                        <span>Upcoming Conferences</span>
                        <span class="text-[9px] bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full font-bold">Future</span>
                    </a>
                    <a href="{{ route('conferences.index', ['filter' => 'past']) }}" class="flex items-center justify-between text-sm font-medium text-gray-600 hover:text-emerald-600">
                        <span>Past Conferences</span>
                        <span class="text-[9px] bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-full font-bold">Completed</span>
                    </a>
                    <a href="{{ route('conferences.index', ['filter' => 'archived']) }}" class="flex items-center justify-between text-sm font-medium text-gray-600 hover:text-slate-700">
                        <span>Old Conferences</span>
                        <span class="text-[9px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full font-bold">Archived</span>
                    </a>
                </div>
            </div>
            <a href="{{ route('payments.create') }}" class="block text-lg font-bold text-amber-600 py-2 border-b border-gray-50 flex items-center justify-between">
                <span>Pay Publication Fee</span>
                <span class="text-[10px] bg-amber-100 text-amber-800 px-2 py-0.5 rounded-full font-bold uppercase">Online</span>
            </a>
            
            <div x-data="{ dOpen: false }">
                <button @click="dOpen = !dOpen" class="w-full flex justify-between items-center text-lg font-bold text-gray-800 py-2 border-b border-gray-50">
                    Participate
                </button>
                <div x-show="dOpen" class="pl-4 py-2 space-y-2" x-transition>
                    <a href="{{ route('submission.create') }}" class="block text-sm font-medium text-gray-500">Submit Journal</a>
                    <a href="{{ route('payments.create') }}" class="block text-sm font-medium text-amber-600">Pay Publication Fee</a>
                    <a href="{{ route('subscribe.page') }}" class="block text-sm font-medium text-gray-500">Alert Subs</a>
                </div>
            </div>

            <div x-data="{ dOpen: false }">
                <button @click="dOpen = !dOpen" class="w-full flex justify-between items-center text-lg font-bold text-gray-800 py-2 border-b border-gray-50">
                    Topics
                </button>
                <div x-show="dOpen" class="pl-4 py-2 max-h-48 overflow-y-auto" x-transition>
                    @foreach($global_topics as $topic)
                        <a href="{{ route('topics.show', $topic->slug) }}" class="block text-sm font-medium text-gray-500 py-1">{{ $topic->name }}</a>
                    @endforeach
                </div>
            </div>

            <a href="{{ route('contact.index') }}" class="block text-lg font-bold text-gray-800 py-2 border-b border-gray-50">Contact</a>
        </div>

        <div class="p-6 bg-gray-50 space-y-3">
            <a href="{{ route('payments.create') }}" class="w-full block text-center py-3 bg-amber-500 text-white rounded-xl font-bold uppercase tracking-widest text-xs shadow-md">Pay Publication Fee</a>
            <a href="{{ route('submission.create') }}" class="w-full block text-center py-3.5 bg-gradient-to-r from-[#1E3A8A] to-[#10B981] text-white rounded-xl font-bold uppercase tracking-widest text-xs">Submit Paper</a>
        </div>
    </div>
</header>
