@extends('layouts.web')
@section('title', 'Global Research Partnership | HJPARAM News')

@section('content')
    <div class="bg-white py-20">
        <div class="container mx-auto px-4 max-w-4xl">
            <div class="mb-12">
                <span
                    class="inline-block px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase tracking-wider rounded-full mb-6">Partnership</span>
                <h1 class="text-4xl md:text-5xl font-serif font-bold text-slate-900 mb-6">Global Research Partnership
                    Announced</h1>
                <div
                    class="flex items-center gap-4 text-slate-400 text-sm font-bold uppercase tracking-widest border-y border-slate-100 py-4">
                    <span>09 Jan 2026</span>
                    <span>•</span>
                    <span>International Relations</span>
                </div>
            </div>

            <div class="prose prose-slate max-w-none prose-lg leading-[1.85]">
                <p class="lead text-2xl text-slate-600 mb-12">Uniting leading academic institutions to create a more
                    transparent and impactful publishing ecosystem.</p>

                <p class="mb-10 text-slate-600">We are thrilled to announce a strategic partnership with a consortium of
                    fifteen Tier-1 research
                    universities across Europe, Asia, and North America. This alliance marks a significant milestone in our
                    mission to democratize scientific knowledge.</p>

                <h3 class="text-3xl font-serif font-black mt-20 mb-8 text-slate-900 border-l-4 border-blue-500 pl-6">
                    Partnership Objectives:</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10 my-12">
                    <div
                        class="border border-slate-100 p-10 rounded-[2.5rem] shadow-sm bg-white hover:shadow-xl hover:shadow-slate-100 transition-all duration-500">
                        <h4 class="text-xl font-bold text-slate-900 mb-4">Resource Sharing</h4>
                        <p class="text-slate-500 leading-relaxed">Joint access to peer-review databases and specialized
                            editorial expertise across disciplines.</p>
                    </div>
                    <div
                        class="border border-slate-100 p-10 rounded-[2.5rem] shadow-sm bg-white hover:shadow-xl hover:shadow-slate-100 transition-all duration-500">
                        <h4 class="text-xl font-bold text-slate-900 mb-4">OA Advocacy</h4>
                        <p class="text-slate-500 leading-relaxed">Collaborative initiatives to reduce barriers for
                            researchers
                            in developing nations.</p>
                    </div>
                </div>

                <p class="mb-10 text-slate-600">Through this partnership, members of participating institutions will benefit
                    from subsidized APCs
                    (Article Processing Charges) and priority access to our upcoming Preprint server features.</p>

                <div class="bg-emerald-50/50 border border-emerald-100 rounded-[3rem] p-16 my-16 relative overflow-hidden">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-emerald-100/30 rounded-full blur-3xl -translate-y-12 translate-x-12">
                    </div>
                    <blockquote class="border-l-4 border-emerald-500 pl-10 m-0 relative z-10">
                        <p class="text-slate-700 italic text-xl leading-[1.8]">"Scientific progress thrives on
                            collaboration, not competition.
                            This partnership creates a bridge between East and West, ensuring high-quality research from
                            every corner of the globe finds a home."</p>
                        <cite class="text-[11px] font-black uppercase tracking-[0.3em] text-emerald-600 mt-8 block">— Dr.
                            Helena Varkis,
                            Editorial Director</cite>
                    </blockquote>
                </div>

                <p class="text-slate-600">Interested in joining the network? Institutions can reach out to our institutional
                    relations team via the
                    <a href="{{ route('info.page', 'contact') }}"
                        class="text-blue-600 font-bold underline decoration-blue-100 decoration-4 underline-offset-4 hover:decoration-blue-500 transition-all">Contact
                        Page</a>.
                </p>
            </div>

            <div class="mt-20 pt-10 border-t border-slate-100 flex justify-between">
                <a href="{{ route('info.page', 'news') }}"
                    class="text-slate-400 hover:text-blue-600 font-bold uppercase tracking-widest text-xs flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                    Back to News
                </a>
                <a href="{{ route('info.page', 'proposals') }}"
                    class="text-emerald-600 hover:text-slate-900 font-bold uppercase tracking-widest text-xs">Partner with
                    us →</a>
            </div>
        </div>
    </div>
@endsection