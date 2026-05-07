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

    <!-- Premium Top Bar -->
    <div class="bg-[#0f172a] text-white text-[10px] py-1.5 relative z-[60] font-bold uppercase tracking-widest border-b border-white/5">
        <div class="container mx-auto px-6 lg:px-12 flex justify-between items-center">
            <div class="flex items-center space-x-6">
                <a href="#" class="hover:text-emerald-400 transition-colors">Sciforum</a>
                <a href="#" class="hover:text-emerald-400 transition-colors">Preprints</a>
                <a href="#" class="hover:text-emerald-400 transition-colors">Scilit</a>
                <a href="{{ route('payments.create') }}" class="hover:text-emerald-400 transition-colors">Publication Payment</a>
            </div>
            <div class="flex items-center space-x-6">
                @auth
                    <a href="{{ route('dashboard') }}" class="hover:text-emerald-400 transition-colors">My Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="hover:text-red-400 transition-colors uppercase tracking-widest font-bold">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:text-emerald-400 transition-colors">Login</a>
                    <span class="text-white/20">|</span>
                    <a href="{{ route('register') }}" class="hover:text-emerald-400 transition-colors">Register</a>
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
