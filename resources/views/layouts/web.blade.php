<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">

    <!-- SEO & Open Graph Metadata -->
    @include('partials.og-metadata')

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-700 bg-white flex flex-col min-h-screen">

    @include('components.web-loader')

    <!-- Premium Ecosystem Top Bar -->
    <div class="bg-[#0f172a] text-white text-[11px] py-2 relative z-[60] font-bold uppercase tracking-[0.2em] border-b border-white/5 shadow-sm">
        <div class="container mx-auto px-6 lg:px-12 flex justify-between items-center">
            <div class="flex items-center space-x-10">
                <a href="{{ route('sciforum.index') }}" 
                    class="hover:text-emerald-400 transition-all duration-300 {{ request()->routeIs('sciforum.*') ? 'text-emerald-400' : 'text-white/90' }}">
                    Sciforum
                </a>
                <a href="{{ route('preprints.index') }}" 
                    class="hover:text-emerald-400 transition-all duration-300 {{ request()->routeIs('preprints.*') ? 'text-emerald-400' : 'text-white/90' }}">
                    Preprints
                </a>
                <a href="{{ route('scilit.index') }}" 
                    class="hover:text-emerald-400 transition-all duration-300 {{ request()->routeIs('scilit.*') ? 'text-emerald-400' : 'text-white/90' }}">
                    Scilit
                </a>
                <span class="w-px h-3 bg-white/10 mx-2"></span>
                <a href="{{ route('payments.create') }}" 
                    class="hover:text-emerald-400 transition-all duration-300 flex items-center gap-2 {{ request()->routeIs('payments.*') ? 'text-emerald-400' : 'text-white/70' }}">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Publication Payment
                </a>
            </div>
            <div class="flex items-center space-x-8">
                @auth
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 hover:text-emerald-400 transition-all duration-300">
                        <div class="w-5 h-5 bg-emerald-500/20 rounded-full flex items-center justify-center">
                            <svg class="w-3 h-3 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        My Dashboard
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="hover:text-red-400 transition-colors uppercase tracking-[0.2em] font-bold opacity-80 hover:opacity-100">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:text-emerald-400 transition-all duration-300">Login</a>
                    <span class="text-white/10">/</span>
                    <a href="{{ route('register') }}" class="hover:text-emerald-400 transition-all duration-300">Register</a>
                @endauth
            </div>
        </div>
    </div>

    <x-header />

    <main class="flex-grow">

        @yield('content')
    </main>

    @include('partials.footer')

</body>

</html>
