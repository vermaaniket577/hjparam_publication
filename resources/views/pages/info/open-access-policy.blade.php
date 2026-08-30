@extends('layouts.web')
@section('title', 'Open Access Policy | HJPARAM Publication')

@section('content')
    <div class="bg-slate-50 py-24">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <span
                    class="inline-block px-3 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-bold uppercase tracking-[0.2em] rounded-full mb-6">Unrestricted
                    Access</span>
                <h1 class="text-4xl md:text-5xl font-serif font-bold text-slate-900 mb-8">Open Access <span
                        class="text-emerald-600">Policy</span></h1>

                <div
                    class="bg-white p-12 md:p-16 rounded-[3rem] shadow-xl shadow-slate-200/50 border border-slate-100 mb-12">
                    <div class="prose prose-slate max-w-none leading-[1.85]">
                        <div
                            class="flex items-center gap-6 mb-12 p-8 bg-emerald-50/50 rounded-3xl border border-emerald-100/50">
                            <div
                                class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-emerald-600 shrink-0 shadow-sm border border-emerald-100/50">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14.5v-9l6 4.5-6 4.5z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[11px] font-black text-emerald-800 uppercase tracking-[0.3em] mb-1">
                                    Institutional Standard</p>
                                <p class="text-sm font-bold text-emerald-600/80 uppercase tracking-widest leading-none">
                                    Built for the Global Research Community</p>
                            </div>
                        </div>

                        <h3 class="text-3xl font-serif font-black text-slate-900 mb-6 flex items-center gap-4">
                            <span class="w-8 h-1 bg-emerald-500 rounded-full"></span>
                            Gold Open Access
                        </h3>
                        <p class="text-slate-600 mb-10 text-lg">
                            HJPARAM Publication provides immediate gold open access to all its content on the principle that
                            making research freely available to the public supports a greater global exchange of knowledge.
                            All articles are published under the <strong>Creative Commons Attribution License (CC BY
                                4.0)</strong>.
                        </p>

                        <h3 class="text-2xl font-serif font-bold text-slate-800 mb-6">Rights & Permissions</h3>
                        <p class="text-slate-600 mb-8">Under this license, authors retain ownership of the copyright for
                            their content, but allow anyone to:</p>
                        <ul class="list-disc pl-8 space-y-6 text-slate-600 mb-16">
                            <li><strong class="text-slate-900">Share</strong> — copy and redistribute the material in any
                                medium or format.</li>
                            <li><strong class="text-slate-900">Adapt</strong> — remix, transform, and build upon the
                                material for any purpose, even commercially.</li>
                        </ul>

                        <div class="p-12 bg-slate-50/80 rounded-[2.5rem] border border-slate-100 relative overflow-hidden">
                            <div
                                class="absolute top-0 right-0 w-32 h-32 bg-slate-200/50 rounded-full blur-3xl -translate-y-12 translate-x-12">
                            </div>
                            <h4 class="text-xl font-serif font-bold text-slate-900 mb-6 relative z-10">Archiving &
                                Preservation</h4>
                            <p class="text-slate-500 text-base leading-relaxed relative z-10">
                                We utilize permanent digital identifiers (DOIs) and collaborate with major digital
                                repositories (including CLOCKSS and Portico) to ensure that your research remains accessible
                                even in the event of unforeseen system failures.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection