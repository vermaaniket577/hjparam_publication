<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'HJPARAM Publication') }} - Open Access Research</title>

    <!-- Fonts: Inter & Playfair Display -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap"
        rel="stylesheet">

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <!-- Fallback for development if build is missing -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Inter', 'sans-serif'],
                            serif: ['Playfair Display', 'serif'],
                        },
                        colors: {
                            'academic-blue': '#002855', // Deep Navy Blue
                            'academic-light': '#E6F0FA', // Very Light Blue
                            'academic-accent': '#005fcc', // Bright Blue Accent
                            'slate-gray': '#64748B',
                        },
                        animation: {
                            'float': 'float 6s ease-in-out infinite',
                            'fade-in-up': 'fadeInUp 0.8s ease-out forwards',
                        },
                        keyframes: {
                            float: {
                                '0%, 100%': { transform: 'translateY(0)' },
                                '50%': { transform: 'translateY(-10px)' },
                            },
                            fadeInUp: {
                                '0%': { opacity: '0', transform: 'translateY(20px)' },
                                '100%': { opacity: '1', transform: 'translateY(0)' },
                            }
                        }
                    }
                }
            }
        </script>
    @endif

    <style>
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .glass-morphism {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
</head>

<body
    class="font-sans antialiased text-slate-800 bg-slate-50 selection:bg-blue-100 selection:text-blue-900 overflow-x-hidden">

    <x-ecosystem-nav />

    <!-- Background Elements -->
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <!-- Subtle Gradient Blob -->
        <div
            class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-100 rounded-full blur-[120px] opacity-40 mix-blend-multiply animate-float">
        </div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-indigo-100 rounded-full blur-[120px] opacity-40 mix-blend-multiply animate-float"
            style="animation-delay: 2s;"></div>

        <!-- Network Grid Pattern -->
        <svg class="absolute inset-0 w-full h-full opacity-[0.03]" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="grid-pattern" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M0 40L40 0H20L0 20M40 40V20L20 40" stroke="currentColor" stroke-width="1" fill="none" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#grid-pattern)" />
        </svg>
    </div>

    <!-- Navigation -->
    <nav class="relative z-50 w-full px-6 py-6 transition-all duration-300">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-3 group cursor-pointer">
                <div
                    class="w-10 h-10 bg-academic-blue rounded-lg shadow-lg flex items-center justify-center text-white font-serif font-bold text-xl transform group-hover:scale-105 transition-transform duration-300">
                    HJ
                </div>
                <span
                    class="font-serif font-bold text-2xl text-slate-900 tracking-tight group-hover:text-academic-blue transition-colors">HJPARAM</span>
            </div>

            <div class="hidden md:flex items-center gap-8">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="text-sm font-medium text-slate-600 hover:text-academic-blue transition-colors relative group">
                            Dashboard
                            <span
                                class="absolute -bottom-1 left-0 w-0 h-0.5 bg-academic-blue transition-all group-hover:w-full"></span>
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-sm font-medium text-slate-600 hover:text-academic-blue transition-colors relative group">
                            Log in
                            <span
                                class="absolute -bottom-1 left-0 w-0 h-0.5 bg-academic-blue transition-all group-hover:w-full"></span>
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="px-6 py-2.5 text-sm font-semibold text-white bg-academic-blue rounded-full hover:bg-[#001f40] transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5 transform">
                                Register
                            </a>
                        @endif
                    @endauth
                @endif
            </div>

            <!-- Mobile Menu Button (Placeholder) -->
            <button class="md:hidden text-slate-600 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </button>
        </div>
    </nav>

    <!-- Main Hero Section -->
    <main
        class="relative z-10 flex flex-col items-center justify-center px-4 sm:px-6 lg:px-8 pt-20 pb-32 w-full max-w-7xl mx-auto text-center">

        <!-- Hero Heading & Subtitle -->
        <div class="max-w-4xl mx-auto mb-12 animate-fade-in-up">
            <div
                class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 text-academic-blue text-xs font-semibold uppercase tracking-wide mb-6 border border-blue-100 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-academic-accent mr-2 animate-pulse"></span>
                Global Research Gateway
            </div>
            <h1
                class="font-serif text-5xl sm:text-6xl md:text-7xl font-bold text-slate-900 mb-6 leading-tight tracking-tight drop-shadow-sm">
                Search Scholarly Articles
            </h1>
            <p class="text-lg sm:text-xl md:text-2xl text-slate-600 max-w-2xl mx-auto font-light leading-relaxed">
                Discover <span class="text-academic-blue font-medium border-b-2 border-blue-100">peer-reviewed</span>,
                <span class="text-academic-blue font-medium border-b-2 border-blue-100">open access</span> research
                across disciplines.
            </p>
        </div>

        <!-- Enhanced Search Component -->
        <div class="w-full max-w-3xl mx-auto mb-16 relative z-20 animate-fade-in-up" style="animation-delay: 0.15s;">
            <div
                class="absolute -inset-1 bg-gradient-to-r from-blue-200 via-white to-blue-200 rounded-full opacity-60 blur-lg transition duration-500">
            </div>

            <form action="{{ route('search') }}" method="GET"
                class="relative flex flex-col sm:flex-row items-center bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.06)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.12)] transition-shadow duration-300 p-2 sm:pr-2 border border-slate-100 group focus-within:ring-2 focus-within:ring-blue-100">
                <div class="pl-6 text-slate-400 hidden sm:block">
                    <svg class="w-6 h-6 group-focus-within:text-academic-blue transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" name="q" placeholder="Search by Title, Keyword, Author, DOI…"
                    class="w-full bg-transparent border-none focus:ring-0 text-slate-700 placeholder-slate-400 px-4 py-4 sm:py-3 text-lg h-14 sm:h-12 outline-none rounded-t-[2rem] sm:rounded-none text-center sm:text-left">
                <button type="submit"
                    class="w-full sm:w-auto bg-academic-blue hover:bg-[#001f40] text-white font-medium px-8 py-3.5 rounded-[1.5rem] transition-all duration-200 flex items-center justify-center gap-2 transform active:scale-95 shadow-md">
                    <span>Search</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </button>
            </form>

            <div class="mt-5 text-center flex justify-center gap-6 text-sm font-medium">
                <a href="javascript:void(0)"
                    class="text-slate-500 hover:text-academic-blue transition-colors hover:underline decoration-academic-blue/30 underline-offset-4 flex items-center gap-1 group">
                    Advanced Search
                    <svg class="w-3 h-3 transition-transform group-hover:translate-x-0.5" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Trust Metrics -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-5xl mx-auto mb-24 w-full animate-fade-in-up"
            style="animation-delay: 0.3s;">
            <!-- Journals -->
            <div
                class="flex flex-col items-center p-6 rounded-2xl bg-white/60 backdrop-blur-sm border border-white shadow-sm hover:shadow-md transition-all hover:-translate-y-1 group">
                <div
                    class="w-12 h-12 rounded-full bg-blue-50 text-academic-blue flex items-center justify-center mb-3 group-hover:bg-academic-blue group-hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                </div>
                <span class="text-3xl font-bold text-slate-800 tabular-nums">120+</span>
                <span class="text-xs uppercase tracking-wider font-semibold text-slate-500 mt-1">Journals</span>
            </div>

            <!-- Articles -->
            <div
                class="flex flex-col items-center p-6 rounded-2xl bg-white/60 backdrop-blur-sm border border-white shadow-sm hover:shadow-md transition-all hover:-translate-y-1 group">
                <div
                    class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mb-3 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
                <span class="text-3xl font-bold text-slate-800 tabular-nums">54k+</span>
                <span class="text-xs uppercase tracking-wider font-semibold text-slate-500 mt-1">Articles</span>
            </div>

            <!-- Views -->
            <div
                class="flex flex-col items-center p-6 rounded-2xl bg-white/60 backdrop-blur-sm border border-white shadow-sm hover:shadow-md transition-all hover:-translate-y-1 group">
                <div
                    class="w-12 h-12 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center mb-3 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                        </path>
                    </svg>
                </div>
                <span class="text-3xl font-bold text-slate-800 tabular-nums">2.5M+</span>
                <span class="text-xs uppercase tracking-wider font-semibold text-slate-500 mt-1">Views</span>
            </div>

            <!-- Global Content -->
            <div
                class="flex flex-col items-center p-6 rounded-2xl bg-white/60 backdrop-blur-sm border border-white shadow-sm hover:shadow-md transition-all hover:-translate-y-1 group">
                <div
                    class="w-12 h-12 rounded-full bg-orange-50 text-orange-600 flex items-center justify-center mb-3 group-hover:bg-orange-600 group-hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </div>
                <span class="text-3xl font-bold text-slate-800 tabular-nums">Global</span>
                <span class="text-xs uppercase tracking-wider font-semibold text-slate-500 mt-1">Open Access</span>
            </div>
        </div>

        <!-- Value Proposition Cards -->
        <div class="grid md:grid-cols-3 gap-6 lg:gap-8 max-w-6xl mx-auto w-full animate-fade-in-up"
            style="animation-delay: 0.45s;">
            <!-- Peer Reviewed -->
            <div
                class="bg-white p-8 rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-slate-50 hover:shadow-[0_10px_30px_rgb(0,0,0,0.08)] transition-all hover:-translate-y-2 group text-left relative overflow-hidden">
                <div
                    class="absolute top-0 left-0 w-1 h-full bg-blue-500 transform scale-y-0 group-hover:scale-y-100 transition-transform duration-300 origin-top">
                </div>
                <div
                    class="w-10 h-10 bg-blue-100/50 text-academic-blue rounded-lg flex items-center justify-center mb-6 group-hover:bg-academic-blue group-hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="font-serif text-xl font-bold text-slate-900 mb-3">Peer-Reviewed</h3>
                <p class="text-slate-600 text-sm leading-relaxed">Rigorous scientific review ensuring the highest
                    standards of quality research and academic integrity.</p>
            </div>

            <!-- Open Access -->
            <div
                class="bg-white p-8 rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-slate-50 hover:shadow-[0_10px_30px_rgb(0,0,0,0.08)] transition-all hover:-translate-y-2 group text-left relative overflow-hidden">
                <div
                    class="absolute top-0 left-0 w-1 h-full bg-emerald-500 transform scale-y-0 group-hover:scale-y-100 transition-transform duration-300 origin-top">
                </div>
                <div
                    class="w-10 h-10 bg-emerald-100/50 text-emerald-600 rounded-lg flex items-center justify-center mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 11V7a4 4 0 118 0m-4-6v6m9 0h-2M5 11h14a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2z">
                        </path>
                    </svg>
                </div>
                <h3 class="font-serif text-xl font-bold text-slate-900 mb-3">Open Access</h3>
                <p class="text-slate-600 text-sm leading-relaxed">Immediate, free availability of research worldwide,
                    dismantling barriers to knowledge dissemination.</p>
            </div>

            <!-- Fast Processing -->
            <div
                class="bg-white p-8 rounded-2xl shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-slate-50 hover:shadow-[0_10px_30px_rgb(0,0,0,0.08)] transition-all hover:-translate-y-2 group text-left relative overflow-hidden">
                <div
                    class="absolute top-0 left-0 w-1 h-full bg-amber-500 transform scale-y-0 group-hover:scale-y-100 transition-transform duration-300 origin-top">
                </div>
                <div
                    class="w-10 h-10 bg-amber-100/50 text-amber-600 rounded-lg flex items-center justify-center mb-6 group-hover:bg-amber-600 group-hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="font-serif text-xl font-bold text-slate-900 mb-3">Fast Processing</h3>
                <p class="text-slate-600 text-sm leading-relaxed">Efficient editorial workflow for timely publication
                    without compromising on review quality.</p>
            </div>
        </div>

    </main>
</body>

</html>