@extends('layouts.web')
@section('title', $pageTitle ?? 'Editorial Board')

@section('content')
<div class="bg-slate-50 py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-5xl mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 p-10 md:p-12 border border-slate-100 mb-10 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50 rounded-bl-full opacity-50"></div>
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="max-w-2xl">
                        <span class="inline-block px-3 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-bold uppercase tracking-wider rounded-full mb-5">
                            {{ $badge ?? 'Leadership' }}
                        </span>
                        <h1 class="text-4xl md:text-5xl font-serif font-bold text-slate-900 mb-5">
                            {{ $pageTitle ?? 'Editorial Board' }}
                        </h1>
                        <p class="text-lg text-slate-500 leading-relaxed">
                            {{ $pageSubtitle ?? 'Our board consists of world-renowned scholars and researchers dedicated to upholding the highest standards of academic excellence.' }}
                        </p>
                    </div>

                    @auth
                        @if(Auth::user()->isAdmin())
                            <div class="flex-shrink-0">
                                <a href="{{ route('admin.editorial-board.index') }}" 
                                   class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-blue-50 text-blue-700 text-xs font-bold hover:bg-blue-100 transition border border-blue-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit from Admin Panel
                                </a>
                            </div>
                        @endif
                    @endauth
                </div>

                <!-- Journal Filters -->
                @if(isset($journals) && $journals->count() > 0)
                    <div class="mt-8 pt-6 border-t border-slate-100 flex flex-wrap items-center gap-2">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mr-2">Filter by Journal:</span>
                        <a href="{{ route('about.page', 'editorial-board') }}" 
                           class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ empty($selectedJournalId) ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            All Publications
                        </a>
                        <a href="{{ route('about.page', 'editorial-board') }}?journal=global" 
                           class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ $selectedJournalId === 'global' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            Publisher Leadership
                        </a>
                        @foreach($journals as $j)
                            <a href="{{ route('about.page', 'editorial-board') }}?journal={{ $j->id }}" 
                               class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ (string)$selectedJournalId === (string)$j->id ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                {{ $j->title }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Editor-in-Chief / Leadership Section -->
            @if(isset($chiefEditors) && $chiefEditors->count() > 0)
                @foreach($chiefEditors as $chief)
                    @php
                        // Generate initials for avatar
                        $names = explode(' ', trim($chief->name));
                        $initials = '';
                        if (count($names) >= 2) {
                            $initials = strtoupper(substr($names[0], 0, 1) . substr(end($names), 0, 1));
                        } else {
                            $initials = strtoupper(substr($chief->name, 0, 2));
                        }

                        // Extract tags/domains from bio if present
                        $tags = [];
                        if ($chief->bio && preg_match('/(?:areas|domains|specializing in|field:)\s*([^.]+)/i', $chief->bio, $matches)) {
                            $tags = array_map('trim', explode(',', $matches[1]));
                            $tags = array_slice($tags, 0, 3);
                        }
                    @endphp

                    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 p-8 border border-slate-100 mb-12 flex flex-col md:flex-row items-center md:items-start gap-8">
                        @if($chief->photo)
                            <img src="{{ asset('storage/' . $chief->photo) }}" alt="{{ $chief->name }}" 
                                 class="w-32 h-32 rounded-2xl object-cover flex-shrink-0 border border-slate-200 shadow-md">
                        @else
                            <div class="w-32 h-32 bg-slate-900 rounded-2xl flex-shrink-0 flex items-center justify-center text-white text-3xl font-bold font-serif shadow-md">
                                {{ $initials }}
                            </div>
                        @endif

                        <div class="flex-1 text-center md:text-left">
                            <div class="flex flex-wrap items-center justify-center md:justify-start gap-2 mb-2">
                                <span class="text-[11px] font-extrabold text-blue-600 uppercase tracking-widest block">
                                    {{ $chief->role }}
                                </span>
                                @if($chief->journal)
                                    <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-100">
                                        {{ $chief->journal->title }}
                                    </span>
                                @endif
                            </div>

                            <h2 class="text-2xl md:text-3xl font-bold text-slate-900 mb-2 font-serif">
                                {{ $chief->name }}
                            </h2>

                            <p class="text-slate-600 font-medium text-sm mb-2">
                                {{ $chief->affiliation }}
                            </p>

                            @if($chief->bio)
                                <p class="text-slate-500 text-sm leading-relaxed mb-4">
                                    {{ $chief->bio }}
                                </p>
                            @endif

                            <div class="flex flex-wrap items-center justify-center md:justify-start gap-2">
                                @if(count($tags) > 0)
                                    @foreach($tags as $t)
                                        <span class="px-3 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-lg uppercase tracking-tight">
                                            {{ $t }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="px-3 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-lg uppercase tracking-tight">Academic Publishing</span>
                                    <span class="px-3 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-lg uppercase tracking-tight">Peer Review</span>
                                @endif

                                @if($chief->email)
                                    <a href="mailto:{{ $chief->email }}" class="inline-flex items-center gap-1 px-3 py-1 bg-blue-50 text-blue-700 text-[11px] font-semibold rounded-lg hover:bg-blue-100 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        Contact Editor
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            <!-- Section Editors Grid -->
            @if(isset($sectionEditors) && $sectionEditors->count() > 0)
                <h2 class="text-2xl font-bold text-slate-900 mb-8 font-serif px-4 border-l-4 border-blue-600">
                    Section Editors
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-16">
                    @foreach($sectionEditors as $editor)
                        @php
                            $initial = strtoupper(substr($editor->name, 0, 1));
                            // Extract field/domain if noted
                            $field = $editor->role;
                            if (preg_match('/field:\s*([^.]+)/i', $editor->bio ?? '', $m)) {
                                $field = trim($m[1]);
                            } elseif ($editor->journal) {
                                $field = $editor->journal->title;
                            }
                        @endphp

                        <div class="bg-white rounded-2xl shadow-lg shadow-slate-100 p-6 border border-slate-50 hover:-translate-y-1 transition duration-300">
                            <div class="flex items-center gap-4 mb-4">
                                @if($editor->photo)
                                    <img src="{{ asset('storage/' . $editor->photo) }}" alt="{{ $editor->name }}" 
                                         class="w-12 h-12 rounded-xl object-cover border border-slate-200">
                                @else
                                    <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center font-bold text-slate-500 font-serif text-lg">
                                        {{ $initial }}
                                    </div>
                                @endif
                                <div>
                                    <h3 class="font-bold text-slate-900 hover:text-blue-600 transition">{{ $editor->name }}</h3>
                                    <p class="text-xs text-blue-600 font-bold tracking-tight uppercase">{{ $field }}</p>
                                    @if($editor->journal && $field !== $editor->journal->title)
                                        <span class="text-[11px] text-slate-400 font-medium">{{ $editor->journal->title }}</span>
                                    @endif
                                </div>
                            </div>

                            <p class="text-[13px] text-slate-600 leading-relaxed font-medium mb-1">
                                {{ $editor->affiliation }}
                            </p>

                            @if($editor->bio)
                                <p class="text-[13px] text-slate-500 leading-relaxed">
                                    {{ $editor->bio }}
                                </p>
                            @endif

                            @if($editor->email)
                                <div class="mt-3 pt-3 border-t border-slate-100 text-right">
                                    <a href="mailto:{{ $editor->email }}" class="text-xs text-blue-600 hover:underline inline-flex items-center gap-1 font-medium">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        {{ $editor->email }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Other Editorial & Advisory Board Members -->
            @if(isset($otherMembers) && $otherMembers->count() > 0)
                <h2 class="text-2xl font-bold text-slate-900 mb-8 font-serif px-4 border-l-4 border-emerald-600">
                    Editorial & Advisory Board Members
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-16">
                    @foreach($otherMembers as $member)
                        <div class="bg-white rounded-2xl shadow-sm p-5 border border-slate-100 hover:shadow-md transition">
                            <div class="flex items-center gap-3 mb-3">
                                @if($member->photo)
                                    <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->name }}" 
                                         class="w-10 h-10 rounded-xl object-cover border border-slate-200">
                                @else
                                    <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center font-bold text-slate-500 font-serif text-base">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <h4 class="font-bold text-sm text-slate-900">{{ $member->name }}</h4>
                                    <span class="text-[11px] font-semibold text-emerald-700 uppercase tracking-tight block">
                                        {{ $member->role }}
                                    </span>
                                </div>
                            </div>

                            <p class="text-xs text-slate-600 font-medium">
                                {{ $member->affiliation }}
                            </p>

                            @if($member->journal)
                                <p class="text-[11px] text-blue-600 mt-1 font-semibold">
                                    {{ $member->journal->title }}
                                </p>
                            @endif

                            @if($member->bio)
                                <p class="text-xs text-slate-500 mt-2 line-clamp-2">
                                    {{ $member->bio }}
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Empty State Fallback -->
            @if((!isset($chiefEditors) || $chiefEditors->count() === 0) && (!isset($sectionEditors) || $sectionEditors->count() === 0) && (!isset($otherMembers) || $otherMembers->count() === 0))
                <div class="bg-white rounded-3xl p-12 text-center border border-slate-100 mb-12">
                    <p class="text-slate-500">Editorial board appointments are currently being updated.</p>
                </div>
            @endif

            <!-- Call to Action Card -->
            <div class="bg-slate-900 rounded-3xl p-12 text-center text-white relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-blue-600/10 rounded-full blur-2xl"></div>
                <div class="relative z-10">
                    <h3 class="text-2xl font-bold mb-4 font-serif">
                        {{ $joinTitle ?? 'Join Our Editorial Board' }}
                    </h3>
                    <p class="text-slate-400 max-w-xl mx-auto mb-8 leading-relaxed">
                        {{ $joinText ?? 'We are always looking for distinguished scholars to join our editorial team. If you are interested in becoming a section editor or reviewer, please contact us.' }}
                    </p>
                    <a href="{{ $joinUrl ?? route('about.page', 'contact-information') }}" 
                       class="bg-blue-600 hover:bg-blue-700 px-8 py-3 rounded-xl font-bold text-sm transition inline-block shadow-lg shadow-blue-600/30">
                        Apply to Join
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
