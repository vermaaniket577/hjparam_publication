<div class="w-full bg-academic-blue text-white py-1.5 px-6 text-[11px] font-medium tracking-wide uppercase">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <div class="flex items-center gap-6 divide-x divide-white/20">
            <a href="{{ route('sciforum.index') }}"
                class="hover:text-blue-200 transition-colors {{ request()->routeIs('sciforum.*') ? 'text-blue-200 font-bold' : '' }}">Sciforum</a>
            <a href="{{ route('preprints.index') }}"
                class="pl-6 hover:text-blue-200 transition-colors {{ request()->routeIs('preprints.*') ? 'text-blue-200 font-bold' : '' }}">Preprints</a>
            <a href="{{ route('scilit.index') }}"
                class="pl-6 hover:text-blue-200 transition-colors {{ request()->routeIs('scilit.*') ? 'text-blue-200 font-bold' : '' }}">Scilit</a>
            <a href="{{ route('sciprofiles.index') }}"
                class="pl-6 hover:text-blue-200 transition-colors {{ request()->routeIs('sciprofiles.*') ? 'text-blue-200 font-bold' : '' }}">SciProfiles</a>
            <a href="{{ route('encyclopedia.index') }}"
                class="pl-6 hover:text-blue-200 transition-colors {{ request()->routeIs('encyclopedia.*') ? 'text-blue-200 font-bold' : '' }}">Encyclopedia</a>
            <a href="{{ route('jams.index') }}"
                class="pl-6 hover:text-blue-200 transition-colors {{ request()->routeIs('jams.*') ? 'text-blue-200 font-bold' : '' }}">JAMS</a>
        </div>
        <div>
            <a href="{{ route('dashboard') }}"
                class="hover:text-blue-200 transition-colors {{ request()->routeIs('dashboard') ? 'text-blue-200 font-bold' : '' }}">Dashboard</a>
        </div>
    </div>
</div>