@extends('layouts.web')
@section('title', $profile->user->name . ' | SciProfile')

@section('content')
    <div class="bg-slate-50 min-h-screen py-12">
        <div class="container mx-auto px-4">
            <!-- Profile Header -->
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-100 mb-8">
                <div class="h-48 bg-gradient-to-r from-blue-900 via-blue-800 to-indigo-900"></div>
                <div class="px-8 pb-8 flex flex-col md:flex-row items-center md:items-end -mt-20 gap-8">
                    <div
                        class="w-40 h-40 rounded-full border-8 border-white bg-slate-100 shadow-lg overflow-hidden flex-shrink-0">
                        @if($profile->profile_photo)
                            <img src="{{ asset('storage/' . $profile->profile_photo) }}" alt="{{ $profile->user->name }}"
                                class="w-full h-full object-cover">
                        @else
                            <div
                                class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-300 font-bold text-5xl italic font-serif">
                                {{ substr($profile->user->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 text-center md:text-left">
                        <h1 class="text-4xl font-serif font-bold text-slate-900">{{ $profile->user->name }}</h1>
                        <p class="text-blue-600 font-bold uppercase tracking-widest mt-1">Academic Researcher & Expert</p>
                        <div
                            class="flex flex-wrap justify-center md:justify-start gap-4 mt-4 text-sm text-slate-500 font-medium">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ $profile->affiliations }}
                            </span>
                            @if($profile->orcid)
                                <span class="flex items-center text-green-600">
                                    <img src="https://orcid.org/assets/vectors/orcid.logo.icon.svg" class="w-4 h-4 mr-2"
                                        alt="ORCID">
                                    {{ $profile->orcid }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="flex gap-2">
                        @auth
                            @if(Auth::id() === $profile->user_id)
                                <a href="{{ route('profile.edit') }}"
                                    class="px-6 py-3 bg-slate-900 text-white font-bold text-xs uppercase tracking-widest rounded-full hover:bg-blue-900 transition shadow-lg">Edit
                                    Profile</a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>

            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Sidebar Info -->
                <div class="lg:col-span-1 space-y-8">
                    <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                        <h3 class="text-lg font-serif font-bold text-slate-900 mb-6 flex items-center">
                            <span
                                class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center mr-3 font-bold">i</span>
                            Biography
                        </h3>
                        <div
                            class="prose prose-slate max-w-none text-slate-600 leading-relaxed italic border-l-4 border-blue-50 pl-6">
                            {{ $profile->bio }}
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                        <h3 class="text-lg font-serif font-bold text-slate-900 mb-6 flex items-center">
                            <span
                                class="w-8 h-8 rounded-lg bg-green-50 text-green-600 flex items-center justify-center mr-3 font-bold">L</span>
                            Connect
                        </h3>
                        <div class="space-y-4">
                            @if($profile->google_scholar)
                                <a href="{{ $profile->google_scholar }}" target="_blank"
                                    class="flex items-center p-3 rounded-xl bg-slate-50 text-slate-700 hover:bg-blue-50 hover:text-blue-900 transition">
                                    <span class="text-sm font-bold truncate">Google Scholar Profile</span>
                                    <svg class="w-4 h-4 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                        </path>
                                    </svg>
                                </a>
                            @endif
                            <a href="mailto:{{ $profile->user->email }}"
                                class="flex items-center p-3 rounded-xl bg-slate-50 text-slate-700 hover:bg-blue-50 hover:text-blue-900 transition">
                                <span class="text-sm font-bold truncate">Send Message (Email)</span>
                                <svg class="w-4 h-4 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Research Output Tabs -->
                    <div x-data="{ activeTab: 'articles' }" class="space-y-6">
                        <div class="flex flex-wrap gap-4 border-b border-slate-200 pb-4">
                            <button @click="activeTab = 'articles'"
                                :class="activeTab === 'articles' ? 'text-blue-900 border-b-2 border-blue-900' : 'text-slate-400'"
                                class="pb-2 font-bold uppercase tracking-widest text-[10px] transition-all">Published
                                Articles</button>
                            <button @click="activeTab = 'preprints'"
                                :class="activeTab === 'preprints' ? 'text-blue-900 border-b-2 border-blue-900' : 'text-slate-400'"
                                class="pb-2 font-bold uppercase tracking-widest text-[10px] transition-all">Preprints</button>
                            <button @click="activeTab = 'encyclopedia'"
                                :class="activeTab === 'encyclopedia' ? 'text-blue-900 border-b-2 border-blue-900' : 'text-slate-400'"
                                class="pb-2 font-bold uppercase tracking-widest text-[10px] transition-all">Encyclopedia
                                Contributions</button>
                        </div>

                        <!-- Articles List -->
                        <div x-show="activeTab === 'articles'" class="space-y-4">
                            @forelse($profile->articles as $article)
                                <div
                                    class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:border-blue-200 transition group">
                                    <h4
                                        class="text-lg font-serif font-bold text-slate-900 group-hover:text-blue-900 transition mb-2">
                                        <a
                                            href="{{ route('articles.show', [$article->journal->slug, $article->slug]) }}">{{ $article->title }}</a>
                                    </h4>
                                    <div
                                        class="flex items-center text-[10px] font-bold uppercase tracking-wider text-slate-400 gap-4 mb-4">
                                        <span class="text-blue-600">{{ $article->journal->title }}</span>
                                        <span>{{ $article->published_at ? $article->published_at->format('M Y') : 'Recent' }}</span>
                                        <span>DOI: {{ $article->doi }}</span>
                                    </div>
                                    <p class="text-sm text-slate-600 line-clamp-2 leading-relaxed mb-4">{{ $article->abstract }}
                                    </p>
                                    <a href="{{ route('articles.show', [$article->journal->slug, $article->slug]) }}"
                                        class="text-xs font-bold text-blue-900 hover:underline">Read Full Article &rarr;</a>
                                </div>
                            @empty
                                <div class="p-12 text-center bg-white rounded-3xl border border-dashed border-slate-200">
                                    <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">No published articles
                                        listed.</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Preprints List -->
                        <div x-show="activeTab === 'preprints'" class="space-y-4" style="display: none;">
                            @forelse($profile->preprints as $preprint)
                                <div
                                    class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:border-orange-200 transition group">
                                    <h4
                                        class="text-lg font-serif font-bold text-slate-900 group-hover:text-orange-900 transition mb-2">
                                        <a href="#">{{ $preprint->title }}</a>
                                    </h4>
                                    <div
                                        class="flex items-center text-[10px] font-bold uppercase tracking-wider text-slate-400 gap-4 mb-4">
                                        <span class="text-orange-600">Preprint v{{ $preprint->version }}</span>
                                        <span>Recent</span>
                                    </div>
                                    <p class="text-sm text-slate-600 line-clamp-2 leading-relaxed mb-4">
                                        {{ $preprint->abstract }}</p>
                                </div>
                            @empty
                                <div class="p-12 text-center bg-white rounded-3xl border border-dashed border-slate-200">
                                    <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">No preprints found.
                                    </p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Encyclopedia List -->
                        <div x-show="activeTab === 'encyclopedia'" class="space-y-4" style="display: none;">
                            @forelse($profile->encyclopediaEntries as $entry)
                                <div
                                    class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:border-indigo-200 transition group">
                                    <h4
                                        class="text-lg font-serif font-bold text-slate-900 group-hover:text-indigo-900 transition mb-2">
                                        <a href="#">{{ $entry->title }}</a>
                                    </h4>
                                    <div
                                        class="flex items-center text-[10px] font-bold uppercase tracking-wider text-slate-400 gap-4 mb-4">
                                        <span class="text-indigo-600">{{ $entry->category }}</span>
                                    </div>
                                    <p class="text-sm text-slate-600 line-clamp-2 leading-relaxed mb-4">
                                        {{ Str::limit($entry->content, 200) }}</p>
                                </div>
                            @empty
                                <div class="p-12 text-center bg-white rounded-3xl border border-dashed border-slate-200">
                                    <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">No encyclopedia
                                        contributions.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection