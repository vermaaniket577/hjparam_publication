@extends('layouts.admin')

@section('title', 'Scholarly Archive')
@section('breadcrumb', 'Articles / Archive')

@section('content')
    <div class="max-w-7xl mx-auto">
        {{-- Header Section --}}
        <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <h1 class="text-3xl font-serif font-bold text-slate-800">Manuscript <span
                        class="text-blue-600">Archive</span></h1>
                <p class="text-slate-500 mt-1">Manage, index, and monitor the global scholarly output of HJPARAM.</p>
            </div>
            <a href="{{ route('admin.articles.create') }}"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-black px-6 py-3.5 rounded-2xl shadow-xl shadow-blue-500/20 hover:shadow-blue-600/30 transition-all duration-300 uppercase tracking-widest text-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M12 4v16m8-8H4" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                Commence Submission
            </a>
        </div>

        {{-- Repository Card --}}
        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                Research Identity</th>
                            <th class="px-6 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                Journal & Status</th>
                            <th
                                class="px-6 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                Engagement</th>
                            <th
                                class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                Editorial Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($articles as $article)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-8 py-6">
                                    <div class="max-w-md">
                                        <h3
                                            class="text-sm font-bold text-slate-800 leading-tight mb-1 group-hover:text-blue-600 transition-colors">
                                            {{ Str::limit($article->title, 100) }}
                                        </h3>
                                        <div class="flex items-center gap-3">
                                            <span
                                                class="text-[10px] font-mono text-slate-400 bg-slate-50 px-2 py-0.5 rounded border border-slate-100">DOI:
                                                {{ $article->doi ?? 'Pending Assignment' }}</span>
                                            <span class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">ID:
                                                #{{ $article->id }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    <div class="text-xs font-bold text-slate-700 mb-1.5">{{ $article->journal->title }}</div>
                                    <div class="flex items-center gap-2">
                                        @php
                                            $statusColors = [
                                                'published' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                                'under_review' => 'bg-amber-50 text-amber-600 border-amber-100',
                                                'accepted' => 'bg-blue-50 text-blue-600 border-blue-100',
                                                'rejected' => 'bg-red-50 text-red-600 border-red-100',
                                                'submitted' => 'bg-slate-50 text-slate-600 border-slate-100',
                                            ];
                                            $colorClass = $statusColors[$article->status] ?? $statusColors['submitted'];
                                        @endphp
                                        <span
                                            class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest border {{ $colorClass }}">
                                            {{ str_replace('_', ' ', $article->status) }}
                                        </span>
                                        <span
                                            class="text-[9px] text-slate-400 font-bold uppercase">{{ $article->published_at ? $article->published_at->format('M Y') : 'Pre-release' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-6 text-center">
                                    <div class="flex items-center justify-center gap-6">
                                        <div class="text-center">
                                            <div class="text-xs font-black text-slate-800">
                                                {{ number_format($article->views_count) }}</div>
                                            <div class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Views
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-xs font-black text-slate-800">
                                                {{ number_format($article->downloads_count) }}</div>
                                            <div class="text-[8px] font-black text-slate-400 uppercase tracking-widest">PDF
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap text-right text-sm">
                                    <div
                                        class="flex items-center justify-end gap-3 opacity-50 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('articles.show', [$article->journal->slug, $article->slug]) }}"
                                            target="_blank" class="p-2 text-slate-400 hover:text-blue-600 transition-colors"
                                            title="Public View">
                                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                                                    stroke-width="2.5"></path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.articles.edit', $article->id) }}"
                                            class="p-2 text-slate-400 hover:text-emerald-600 transition-colors"
                                            title="Modify Registry">
                                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                                    stroke-width="2.5"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.articles.destroy', $article->id) }}" method="POST"
                                            onsubmit="return confirm('Attention: Digital deletion is permanent. Archive article?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-slate-300 hover:text-red-600 transition-colors"
                                                title="De-index Research">
                                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                        stroke-width="2.5"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-200 mb-4">
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                                    stroke-width="2"></path>
                                            </svg>
                                        </div>
                                        <h4 class="text-lg font-bold text-slate-400">Scholarly Repository is Empty</h4>
                                        <p class="text-slate-400 text-sm mt-1">Commence by registering your first research
                                            manuscript.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($articles->hasPages())
                <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
                    {{ $articles->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection