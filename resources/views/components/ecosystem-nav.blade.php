<!-- Premium Ecosystem Navigation Bar -->
<div class="w-full bg-[#0f172a] text-white py-2 relative z-[100] font-bold uppercase tracking-[0.2em] border-b border-white/5 shadow-lg">
    <div class="max-w-7xl mx-auto px-6 lg:px-12 flex justify-between items-center">
        <!-- Main Links -->
        <div class="flex items-center space-x-10">
            <a href="{{ route('sciforum.index') }}"
                class="text-[11px] hover:text-emerald-400 transition-all duration-300 {{ request()->routeIs('sciforum.*') ? 'text-emerald-400' : 'text-white/90' }}">
                Sciforum
            </a>
            <a href="{{ route('preprints.index') }}"
                class="text-[11px] hover:text-emerald-400 transition-all duration-300 {{ request()->routeIs('preprints.*') ? 'text-emerald-400' : 'text-white/90' }}">
                Preprints
            </a>
            <a href="{{ route('scilit.index') }}"
                class="text-[11px] hover:text-emerald-400 transition-all duration-300 {{ request()->routeIs('scilit.*') ? 'text-emerald-400' : 'text-white/90' }}">
                Scilit
            </a>
            
            <div class="hidden lg:flex items-center space-x-10 border-l border-white/10 pl-10">
                <a href="{{ route('sciprofiles.index') }}"
                    class="text-[11px] hover:text-emerald-400 transition-all duration-300 {{ request()->routeIs('sciprofiles.*') ? 'text-emerald-400' : 'text-white/70' }}">
                    SciProfiles
                </a>
                <a href="{{ route('encyclopedia.index') }}"
                    class="text-[11px] hover:text-emerald-400 transition-all duration-300 {{ request()->routeIs('encyclopedia.*') ? 'text-emerald-400' : 'text-white/70' }}">
                    Encyclopedia
                </a>
                <a href="{{ route('jams.index') }}"
                    class="text-[11px] hover:text-emerald-400 transition-all duration-300 {{ request()->routeIs('jams.*') ? 'text-emerald-400' : 'text-white/70' }}">
                    JAMS
                </a>
            </div>
        </div>

        <!-- Action Links -->
        <div class="flex items-center space-x-8">
            @auth
                <a href="{{ route('dashboard') }}" 
                    class="text-[11px] flex items-center gap-2 hover:text-emerald-400 transition-all duration-300 {{ request()->routeIs('dashboard') ? 'text-emerald-400' : 'text-white/90' }}">
                    <div class="w-5 h-5 bg-emerald-500/20 rounded-full flex items-center justify-center">
                        <svg class="w-3 h-3 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" 
                    class="text-[11px] hover:text-emerald-400 transition-all duration-300 text-white/90">
                    Login
                </a>
                <span class="text-white/10">/</span>
                <a href="{{ route('register') }}" 
                    class="text-[11px] hover:text-emerald-400 transition-all duration-300 text-white/90">
                    Register
                </a>
            @endauth
        </div>
    </div>
</div>