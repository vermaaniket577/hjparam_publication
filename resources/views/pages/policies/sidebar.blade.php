@php
    $currentSlug = request()->route('slug') ?? (request()->is('policies') ? 'index' : '');
    $policyLinks = [
        [
            'slug' => 'disclaimer',
            'title' => 'Disclaimer',
            'desc' => 'Liability, opinions & legal terms',
            'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'
        ],
        [
            'slug' => 'open-access-policy',
            'title' => 'Open Access Policy',
            'desc' => 'CC BY 4.0 & unrestricted access',
            'icon' => 'M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z'
        ],
        [
            'slug' => 'peer-review-policy',
            'title' => 'Peer Review Policy',
            'desc' => 'Double-blind review process',
            'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'
        ],
        [
            'slug' => 'crossmark-policy',
            'title' => 'CrossMark Policy',
            'desc' => 'Version of record & persistent updates',
            'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'
        ],
        [
            'slug' => 'archiving-policies',
            'title' => 'Archiving Policies',
            'desc' => 'CLOCKSS, Portico & self-archiving',
            'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4'
        ],
        [
            'slug' => 'license-terms',
            'title' => 'License Terms',
            'desc' => 'Creative Commons & author rights',
            'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'
        ],
        [
            'slug' => 'other-policies',
            'title' => 'Other Policies',
            'desc' => 'COPE ethics, conflicts & conduct',
            'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'
        ]
    ];
@endphp

<div class="space-y-6">
    <!-- Policy Navigation Card -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 p-5 overflow-hidden">
        <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
            <div>
                <span class="text-[10px] font-bold text-blue-600 uppercase tracking-widest block">Governance</span>
                <h3 class="font-serif font-bold text-lg text-slate-900">Journal Policies</h3>
            </div>
            <a href="{{ route('policies.index') }}" 
               class="text-xs font-semibold text-slate-500 hover:text-blue-600 transition flex items-center gap-1"
               title="View all policies hub">
                Overview
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <nav class="space-y-1">
            @foreach($policyLinks as $item)
                @php $isActive = ($currentSlug === $item['slug']); @endphp
                <a href="{{ route('policies.show', $item['slug']) }}"
                   class="group flex items-start gap-3 p-3 rounded-2xl transition-all duration-200 {{ $isActive ? 'bg-blue-50 text-blue-900 font-bold border border-blue-200/70 shadow-xs' : 'text-slate-700 hover:bg-slate-50 hover:text-blue-700' }}">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 mt-0.5 transition-colors {{ $isActive ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 text-slate-500 group-hover:bg-blue-100 group-hover:text-blue-700' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"></path>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <span class="block text-sm font-semibold truncate {{ $isActive ? 'text-blue-950 font-bold' : 'text-slate-800' }}">
                            {{ $item['title'] }}
                        </span>
                        <span class="block text-[11px] text-slate-500 truncate mt-0.5">
                            {{ $item['desc'] }}
                        </span>
                    </div>
                    @if($isActive)
                        <span class="w-1.5 h-5 bg-blue-600 rounded-full self-center ml-auto shrink-0"></span>
                    @endif
                </a>
            @endforeach
        </nav>
    </div>

    <!-- Editorial Integrity & Standards Card -->
    <div class="bg-gradient-to-br from-slate-900 to-blue-950 rounded-3xl p-6 text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-blue-600/20 rounded-full blur-2xl"></div>
        <div class="relative z-10 space-y-4">
            <div class="inline-flex items-center gap-2 px-2.5 py-1 bg-white/10 rounded-full text-[10px] font-bold tracking-wider uppercase text-blue-200 backdrop-blur-xs">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                International Standards
            </div>
            <div>
                <h4 class="font-serif font-bold text-base text-white mb-1">Ethical Publishing</h4>
                <p class="text-xs text-slate-300 leading-relaxed">
                    HJPARAM journals strictly adhere to COPE, OASPA, DOAJ, and Crossref guidelines to ensure transparent and reliable scholarly records.
                </p>
            </div>
            <div class="pt-2 border-t border-white/10 flex items-center justify-between text-xs">
                <span class="text-slate-400">Need clarification?</span>
                <a href="{{ route('contact.index') }}" class="font-bold text-blue-300 hover:text-white transition flex items-center gap-1">
                    Contact Office &rarr;
                </a>
            </div>
        </div>
    </div>
</div>
