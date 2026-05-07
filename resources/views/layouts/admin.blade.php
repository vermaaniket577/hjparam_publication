<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Portal') - HJPARAM Publication</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Micro Loader for Buttons */
        .btn-loading {
            position: relative;
            color: transparent !important;
            pointer-events: none;
        }

        .btn-loading::after {
            content: "";
            position: absolute;
            width: 1em;
            height: 1em;
            top: 50%;
            left: 50%;
            margin-top: -0.5em;
            margin-left: -0.5em;
            border: 2px solid rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-900">

    @include('components.web-loader')

    <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">

        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-20 bg-gray-900 bg-opacity-50 lg:hidden" x-cloak></div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-30 w-64 overflow-y-auto transition-transform duration-300 transform bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 lg:translate-x-0 lg:static lg:inset-0 flex-shrink-0 shadow-sm">
            <div class="flex items-center justify-center h-16 bg-blue-600 dark:bg-blue-800 shadow-md">
                <div class="flex items-center space-x-2 px-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-auto bg-white rounded-sm p-0.5">
                    <span class="text-xl font-bold text-white tracking-wide">HJPARAM Portal</span>
                </div>
            </div>

            <nav class="mt-6 px-4 space-y-1">
                <a href="{{ route('home') }}"
                    class="flex items-center px-4 py-3 text-sm font-medium rounded-md text-blue-600 hover:bg-blue-50 transition-colors duration-150 border border-blue-100 mb-4 shadow-sm">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Visit Website
                </a>

                {{-- Common Links --}}
                <a href="{{ route('dashboard') }}"
                    class="flex items-center px-4 py-3 text-sm font-medium rounded-md transition-colors duration-150 {{ request()->routeIs('dashboard') && !request()->routeIs('admin.*') ? 'bg-blue-50 text-blue-700 dark:bg-gray-700 dark:text-white' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    Home
                </a>

                @auth
                    @if(Auth::user()->isAdmin() || Auth::user()->isEditor())
                        <a href="{{ route('admin.dashboard') }}"
                            class="flex items-center px-4 py-2 text-sm font-medium rounded-md transition-colors duration-150 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700 dark:bg-gray-700 dark:text-white' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                                </path>
                            </svg>
                            Admin Dashboard
                        </a>

                        <div class="pt-4 pb-2">
                            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Management</p>
                        </div>

                        <a href="{{ route('admin.users.index') }}"
                            class="flex items-center px-4 py-2 text-sm font-medium rounded-md transition-colors duration-150 {{ request()->routeIs('admin.users.*') ? 'bg-blue-50 text-blue-700 dark:bg-gray-700 dark:text-white' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                            Users
                        </a>

                        <a href="{{ route('admin.journals.index') }}"
                            class="flex items-center px-4 py-2 text-sm font-medium rounded-md transition-colors duration-150 {{ request()->routeIs('admin.journals.*') ? 'bg-blue-50 text-blue-700 dark:bg-gray-700 dark:text-white' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                                </path>
                            </svg>
                            Journals
                        </a>

                        <a href="{{ route('admin.articles.index') }}"
                            class="flex items-center px-4 py-2 text-sm font-medium rounded-md transition-colors duration-150 {{ request()->routeIs('admin.articles.*') ? 'bg-blue-50 text-blue-700 dark:bg-gray-700 dark:text-white' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Articles
                        </a>

                        <a href="{{ route('admin.conferences.index') }}"
                            class="flex items-center px-4 py-2 text-sm font-medium rounded-md transition-colors duration-150 {{ request()->routeIs('admin.conferences.*') ? 'bg-blue-50 text-blue-700 dark:bg-gray-700 dark:text-white' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            Manage Conferences
                        </a>

                        <div class="pt-4 pb-2">
                            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Workflow</p>
                        </div>

                        <a href="{{ route('admin.submissions.index') }}"
                            class="flex items-center px-4 py-2 text-sm font-medium rounded-md transition-colors duration-150 {{ request()->routeIs('admin.submissions.*') ? 'bg-blue-50 text-blue-700 dark:bg-gray-700 dark:text-white' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                </path>
                            </svg>
                            Submissions
                        </a>

                        <a href="{{ route('admin.subscriptions.index') }}"
                            class="flex items-center px-4 py-2 text-sm font-medium rounded-md transition-colors duration-150 {{ request()->routeIs('admin.subscriptions.*') ? 'bg-blue-50 text-blue-700 dark:bg-gray-700 dark:text-white' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke-width="2"></path>
                            </svg>
                            Subscriptions
                        </a>

                        <a href="{{ route('admin.payments.index') }}"
                            class="flex items-center px-4 py-2 text-sm font-medium rounded-md transition-colors duration-150 {{ request()->routeIs('admin.payments.*') ? 'bg-blue-50 text-blue-700 dark:bg-gray-700 dark:text-white' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2h10a2 2 0 002-2v-2m0-4h4a2 2 0 012 2v2a2 2 0 01-2 2h-4m0-6v6">
                                </path>
                            </svg>
                            Payments
                        </a>

                        <a href="{{ route('admin.contact-messages.index') }}"
                            class="flex items-center px-4 py-2 text-sm font-medium rounded-md transition-colors duration-150 {{ request()->routeIs('admin.contact-messages.*') ? 'bg-blue-50 text-blue-700 dark:bg-gray-700 dark:text-white' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" stroke-width="2"></path>
                            </svg>
                            Contact Messages
                        </a>

                        <div class="pt-4 pb-2">
                            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Content</p>
                        </div>

                        <a href="{{ route('admin.topics.index') }}"
                            class="flex items-center px-4 py-2 text-sm font-medium rounded-md transition-colors duration-150 {{ request()->routeIs('admin.topics.*') ? 'bg-blue-50 text-blue-700 dark:bg-gray-700 dark:text-white' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                </path>
                            </svg>
                            Manage Categories
                        </a>

                        <a href="{{ route('admin.countries.index') }}"
                            class="flex items-center px-4 py-2 text-sm font-medium rounded-md transition-colors duration-150 {{ request()->routeIs('admin.countries.*') ? 'bg-blue-50 text-blue-700 dark:bg-gray-700 dark:text-white' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M12 21l-8-4.5v-9L12 3l8 4.5v9L12 21z" stroke-width="2.5"></path>
                            </svg>
                            Manage Countries
                        </a>

                        <a href="{{ route('admin.pages.index') }}"
                            class="flex items-center px-4 py-2 text-sm font-medium rounded-md transition-colors duration-150 {{ request()->routeIs('admin.pages.*') ? 'bg-blue-50 text-blue-700 dark:bg-gray-700 dark:text-white' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Manage Pages
                        </a>

                        <a href="{{ route('admin.partners.index') }}"
                            class="flex items-center px-4 py-2 text-sm font-medium rounded-md transition-colors duration-150 {{ request()->routeIs('admin.partners.*') ? 'bg-blue-50 text-blue-700 dark:bg-gray-700 dark:text-white' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                                </path>
                            </svg>
                            Partners
                        </a>

                        <a href="{{ route('admin.news.index') }}"
                            class="flex items-center px-4 py-2 text-sm font-medium rounded-md transition-colors duration-150 {{ request()->routeIs('admin.news.*') ? 'bg-blue-50 text-blue-700 dark:bg-gray-700 dark:text-white' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 2v4a2 2 0 002 2h4">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h3m-3 4h6m-6 4h6">
                                </path>
                            </svg>
                            News & Announcements
                        </a>

                        <a href="{{ route('admin.reviews.index') }}"
                            class="flex items-center px-4 py-2 text-sm font-medium rounded-md transition-colors duration-150 {{ request()->routeIs('admin.reviews.*') ? 'bg-blue-50 text-blue-700 dark:bg-gray-700 dark:text-white' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            Review Management
                        </a>

                        <div class="pt-4 pb-2">
                            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">System</p>
                        </div>

                        <a href="{{ route('admin.analytics.index') }}"
                            class="flex items-center px-4 py-2 text-sm font-medium rounded-md transition-colors duration-150 {{ request()->routeIs('admin.analytics.*') ? 'bg-blue-50 text-blue-700 dark:bg-gray-700 dark:text-white' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                            Analytics
                        </a>

                        <a href="{{ route('admin.settings.index') }}"
                            class="flex items-center px-4 py-2 text-sm font-medium rounded-md transition-colors duration-150 {{ request()->routeIs('admin.settings.*') ? 'bg-blue-50 text-blue-700 dark:bg-gray-700 dark:text-white' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Settings
                        </a>
                    @endif
                @endauth

                {{-- Author Links --}}
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">My Workflow</p>
                </div>

                <a href="{{ route('submission.create') }}"
                    class="flex items-center px-4 py-2 text-sm font-medium rounded-md transition-colors duration-150 {{ request()->routeIs('submission.create') ? 'bg-blue-50 text-blue-700 dark:bg-gray-700 dark:text-white' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    New Submission
                </a>

                <a href="{{ route('submission.index') }}"
                    class="flex items-center px-4 py-2 text-sm font-medium rounded-md transition-colors duration-150 {{ request()->routeIs('submission.index') ? 'bg-blue-50 text-blue-700 dark:bg-gray-700 dark:text-white' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    My Submissions
                </a>

                @auth
                    @if(Auth::user()->isReviewer() || Auth::user()->isEditor())
                        <a href="{{ route('reviews.index') }}"
                            class="flex items-center px-4 py-2 text-sm font-medium rounded-md transition-colors duration-150 {{ request()->routeIs('reviews.*') ? 'bg-blue-50 text-blue-700 dark:bg-gray-700 dark:text-white' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                </path>
                            </svg>
                            My Review Assignments
                        </a>
                    @endif
                @endauth
            </nav>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden relative">
            <header
                class="flex justify-between items-center py-3 px-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm z-10">
                <div class="flex items-center flex-1">
                    <button @click="sidebarOpen = true"
                        class="text-gray-500 hover:text-gray-600 focus:outline-none lg:hidden mr-4">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>

                    <!-- Global Search Bar -->
                    <form action="{{ route('search') }}" method="GET" class="relative w-full max-w-xl">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" name="q"
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-gray-50 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 sm:text-sm transition duration-150 ease-in-out dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            placeholder="Search articles, journals, authors...">
                    </form>
                </div>

                <div class="flex items-center space-x-4">
                    <!-- Notifications Dropdown -->
                    <div class="relative" x-data="{ notificationOpen: false }">
                        <button @click="notificationOpen = !notificationOpen"
                            class="text-gray-400 hover:text-gray-600 focus:outline-none relative transition-colors">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                </path>
                            </svg>
                            @auth
                                @php
                                    $pendingReviewCount = Auth::user()->reviews()->whereNull('completed_at')->count();
                                    $activeSubmissionCount = Auth::user()->submissions()->whereNotIn('status', ['published', 'rejected'])->count();
                                    $totalNotifs = $pendingReviewCount + $activeSubmissionCount;
                                @endphp
                                @if($totalNotifs > 0)
                                    <span
                                        class="absolute top-0 right-0 block h-2.5 w-2.5 rounded-full ring-2 ring-white bg-red-500"></span>
                                @endif
                            @endauth
                        </button>

                        <div x-show="notificationOpen" @click.away="notificationOpen = false"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 w-80 mt-2 origin-top-right bg-white rounded-lg shadow-xl ring-1 ring-black ring-opacity-5 z-50 overflow-hidden dark:bg-gray-800"
                            x-cloak>
                            <div
                                class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                                <h3 class="text-sm font-bold text-gray-800 dark:text-white">Notifications</h3>
                            </div>
                            <div class="max-h-96 overflow-y-auto">
                                @auth
                                    @if($pendingReviewCount > 0)
                                        <a href="{{ route('reviews.index') }}"
                                            class="block px-4 py-4 hover:bg-blue-50 dark:hover:bg-gray-700 border-b border-gray-50 dark:border-gray-700 transition">
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0 bg-blue-100 text-blue-600 p-2 rounded-full">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-bold text-gray-800 dark:text-gray-200">Pending
                                                        Reviews</p>
                                                    <p class="text-xs text-gray-500">You have {{ $pendingReviewCount }}
                                                        assignments waiting for your feedback.</p>
                                                </div>
                                            </div>
                                        </a>
                                    @endif

                                    @if($activeSubmissionCount > 0)
                                        <a href="{{ route('submission.index') }}"
                                            class="block px-4 py-4 hover:bg-green-50 dark:hover:bg-gray-700 border-b border-gray-50 dark:border-gray-700 transition">
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0 bg-green-100 text-green-600 p-2 rounded-full">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-bold text-gray-800 dark:text-gray-200">Active
                                                        Submissions</p>
                                                    <p class="text-xs text-gray-500">You have {{ $activeSubmissionCount }}
                                                        manuscripts currently in the publishing workflow.</p>
                                                </div>
                                            </div>
                                        </a>
                                    @endif
                                    @if($totalNotifs == 0)
                                        <div class="px-4 py-12 text-center">
                                            <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <p class="text-sm text-gray-500">No new notifications</p>
                                        </div>
                                    @endif
                                @else
                                    <div class="px-4 py-8 text-center">
                                        <p class="text-sm text-gray-500">Please <a href="{{ route('login') }}"
                                                class="text-blue-600 font-bold">sign in</a> to view notifications</p>
                                    </div>
                                @endauth
                            </div>
                            @auth
                                <div
                                    class="px-4 py-2 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 text-center">
                                    <a href="javascript:void(0)"
                                        class="text-xs font-bold text-blue-600 hover:text-blue-800 uppercase tracking-widest transition-colors">Clear
                                        All</a>
                                </div>
                            @endauth
                        </div>
                    </div>

                    <!-- Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        @auth
                            <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                                <div
                                    class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-sm">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <span
                                    class="hidden md:block text-sm font-medium text-gray-700 dark:text-gray-300">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 w-48 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 py-1 z-50 dark:bg-gray-800"
                                style="display: none;">
                                <a href="{{ route('home') }}"
                                    class="block px-4 py-2 text-sm text-blue-600 font-bold hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-gray-700">Visit
                                    Website</a>
                                <div class="border-t border-gray-100 dark:border-gray-700"></div>
                                <a href="{{ route('profile.edit') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">Your
                                    Profile</a>
                                <a href="{{ route('admin.settings.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">Settings</a>
                                <div class="border-t border-gray-100 dark:border-gray-700"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">Sign
                                        out</button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('login') }}"
                                class="text-sm font-bold text-blue-600 hover:text-blue-800 transition">Sign In</a>
                        @endauth
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 dark:bg-gray-900 p-6">
                <div class="container mx-auto px-4">
                    <!-- Breadcrumbs -->
                    <nav class="flex mb-6" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            <li class="inline-flex items-center">
                                <a href="{{ route('dashboard') }}"
                                    class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                                        </path>
                                    </svg>
                                    Home
                                </a>
                            </li>
                            <li aria-current="page">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    <span
                                        class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">@yield('breadcrumb', 'Dashboard')</span>
                                </div>
                            </li>
                        </ol>
                    </nav>

                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm"
                            role="alert">
                            <p class="font-bold">Success</p>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>

</html>
