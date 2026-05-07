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
        <a href="{{ route('home') }}" class="flex items-center gap-4 group flex-shrink-0">
            <div class="relative transition-transform duration-300 group-hover:scale-105">
                <img src="{{ asset('images/favicon.png') }}" alt="HJPARAM Logo" class="h-10 w-auto lg:h-12 object-contain">
                <div class="absolute -inset-2 bg-blue-500/5 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </div>
            <div class="flex flex-col">
                <span class="text-xl lg:text-2xl font-black tracking-tighter text-[#1E3A8A] leading-tight">HJPARAM</span>
                <span class="text-[10px] lg:text-[11px] font-medium text-gray-500 tracking-wider uppercase">Academic Open Access Publishing</span>
            </div>
        </a>

        <!-- Center: Main Navigation -->
        <nav class="hidden lg:flex items-center space-x-0.5 flex-grow justify-center px-2">
            <!-- Journals -->
            <a href="{{ route('journals.index') }}" 
                class="relative px-2 py-2 text-[12px] font-bold text-gray-700 hover:text-[#1E3A8A] transition-colors group">
                Journals
                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-[#1E3A8A] transition-all duration-300 group-hover:w-full"></span>
            </a>

            <!-- Conferences -->
            <a href="{{ route('conferences.index') }}" 
                class="relative px-2 py-2 text-[12px] font-bold text-gray-700 hover:text-[#1E3A8A] transition-colors group">
                Conferences
                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-[#1E3A8A] transition-all duration-300 group-hover:w-full"></span>
            </a>

            <!-- Participate Dropdown -->
            <div class="relative group h-full py-4 text-[12px]" @mouseenter="activeDropdown = 'participate'" @mouseleave="activeDropdown = null">
                <button class="flex items-center gap-1 px-2 py-2 font-bold text-gray-700 hover:text-[#1E3A8A] transition-colors">
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
            <div class="relative group h-full py-4 text-[12px]" @mouseenter="activeDropdown = 'topics'" @mouseleave="activeDropdown = null">
                <button class="flex items-center gap-1 px-2 py-2 font-bold text-gray-700 hover:text-[#1E3A8A] transition-colors">
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
            <div class="relative group h-full py-4 text-[12px]" @mouseenter="activeDropdown = 'authors'" @mouseleave="activeDropdown = null">
                <button class="flex items-center gap-1 px-2 py-2 font-bold text-gray-700 hover:text-[#1E3A8A] transition-colors">
                    Author Services
                </button>
                <div x-show="activeDropdown === 'authors'" 
                    class="absolute top-full left-0 w-64 bg-white rounded-xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] border border-gray-100 py-3 z-50 text-left" x-cloak>
                    <a href="{{ route('author.submit') }}" class="block px-6 py-2.5 text-sm font-medium text-gray-600 hover:text-[#1E3A8A] hover:bg-gray-50">Submit Manuscript</a>
                    <a href="{{ route('author.guidelines') }}" class="block px-6 py-2.5 text-sm font-medium text-gray-600 hover:text-[#1E3A8A] hover:bg-gray-50">Guidelines</a>
                    @foreach($menu_author as $page)
                        <a href="{{ route('author.page', $page->slug) }}" class="block px-6 py-2.5 text-sm font-medium text-gray-600 hover:text-[#1E3A8A] hover:bg-gray-50">{{ $page->title }}</a>
                    @endforeach
                </div>
            </div>

            <!-- Information -->
            <div class="relative group h-full py-4 text-[12px]" @mouseenter="activeDropdown = 'info'" @mouseleave="activeDropdown = null">
                <button class="flex items-center gap-1 px-2 py-2 font-bold text-gray-700 hover:text-[#1E3A8A] transition-colors">
                    Info
                </button>
                <div x-show="activeDropdown === 'info'" 
                    class="absolute top-full left-0 w-64 bg-white rounded-xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] border border-gray-100 py-3 z-50 text-left" x-cloak>
                    @foreach($menu_info as $page)
                        <a href="{{ route('info.page', $page->slug) }}" class="block px-6 py-2.5 text-sm font-medium text-gray-600 hover:text-[#1E3A8A] hover:bg-gray-50 transition">{{ $page->title }}</a>
                    @endforeach
                </div>
            </div>

            <!-- About -->
            <div class="relative group h-full py-4 text-[12px]" @mouseenter="activeDropdown = 'about'" @mouseleave="activeDropdown = null">
                <button class="flex items-center gap-1 px-2 py-2 font-bold text-gray-700 hover:text-[#1E3A8A] transition-colors group">
                    About
                </button>
                <div x-show="activeDropdown === 'about'" 
                    class="absolute top-full left-0 w-64 bg-white rounded-xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] border border-gray-100 py-3 z-50 text-left" x-cloak>
                    @foreach($menu_about as $page)
                        <a href="{{ route('about.page', $page->slug) }}" class="block px-6 py-2.5 text-sm font-medium text-gray-600 hover:text-[#1E3A8A] hover:bg-gray-50 transition">{{ $page->title }}</a>
                    @endforeach
                </div>
            </div>
        </nav>

        <!-- Right: Actions Area -->
        <div class="flex items-center gap-2 flex-shrink-0">
            <!-- Search Icon & Expanded Form -->
            <div class="relative flex items-center h-full" x-data="{ localSearchOpen: false }">
                <button @click="localSearchOpen = !localSearchOpen; if(localSearchOpen) $nextTick(() => $refs.searchInput.focus())" 
                    class="p-1.5 text-gray-500 hover:text-[#1E3A8A] transition-colors relative z-20">
                    <svg x-show="!localSearchOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <svg x-show="localSearchOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
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

            <!-- CTA: Submit Paper -->
            <a href="{{ route('submission.create') }}" 
                class="hidden lg:flex items-center gap-1.5 px-4 py-2 bg-gradient-to-r from-[#1E3A8A] to-[#10B981] text-white rounded-full text-[12px] font-bold uppercase tracking-wider hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 flex-shrink-0">
                <span>Submit Paper</span>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
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
            <a href="{{ route('conferences.index') }}" class="block text-lg font-bold text-gray-800 py-2 border-b border-gray-50">Conferences</a>
            
            <div x-data="{ dOpen: false }">
                <button @click="dOpen = !dOpen" class="w-full flex justify-between items-center text-lg font-bold text-gray-800 py-2 border-b border-gray-50">
                    Participate
                </button>
                <div x-show="dOpen" class="pl-4 py-2 space-y-2" x-transition>
                    <a href="{{ route('submission.create') }}" class="block text-sm font-medium text-gray-500">Submit Journal</a>
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

        <div class="p-6 bg-gray-50">
            <a href="{{ route('submission.create') }}" class="w-full block text-center py-4 bg-gradient-to-r from-[#1E3A8A] to-[#10B981] text-white rounded-xl font-bold uppercase tracking-widest text-xs">Submit Paper</a>
        </div>
    </div>
</header>
