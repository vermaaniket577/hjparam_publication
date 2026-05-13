@extends('layouts.web')

@section('title', 'About HJPARAM Publication')
@section('meta_description', 'Learn about HJPARAM Publication, our mission, publishing ethics, and our commitment to advancing open access research globally.')

@section('content')
    <section class="bg-slate-950 text-white py-24">
        <div class="container mx-auto px-6 lg:px-12">
            <p class="text-xs font-black uppercase tracking-[0.3em] text-emerald-400 mb-6">About HJPARAM Publication</p>
            <h1 class="text-4xl md:text-7xl font-serif font-black tracking-tight max-w-5xl leading-tight">
                Advancing the Frontiers of <span class="text-blue-400">Open Science</span> through Integrity and Innovation.
            </h1>
            <p class="mt-8 max-w-4xl text-xl leading-relaxed text-slate-300 font-light">
                HJPARAM is a premier academic publishing platform dedicated to accelerating the global dissemination of peer-reviewed research. We empower scholars with the tools and visibility needed to make a lasting impact on their disciplines.
            </p>
        </div>
    </section>

    <section class="py-24 bg-white">
        <div class="container mx-auto px-6 lg:px-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-20">
                <div class="lg:col-span-8 space-y-16">
                    {{-- Introduction --}}
                    <article class="prose prose-slate prose-lg max-w-none">
                        <h2 class="text-3xl font-serif font-bold text-slate-900 mb-8 border-l-4 border-blue-600 pl-6">Our Vision for Academic Excellence</h2>
                        <p class="text-slate-600 leading-relaxed text-lg">
                            Founded on the principles of transparency and accessibility, HJPARAM Publication serves as a vital bridge between groundbreaking research and the global scientific community. In an era where information is abundant but quality is paramount, we maintain a steadfast commitment to rigorous peer review and ethical publishing standards.
                        </p>
                        <p class="text-slate-600 leading-relaxed text-lg">
                            Our platform is designed to meet the evolving needs of modern researchers. By integrating advanced digital workflows with traditional scholarly values, we ensure that every article published under the HJPARAM banner undergoes a thorough evaluation by experts in the field. This commitment to quality ensures that our contributors gain the recognition they deserve while providing readers with trustworthy, actionable knowledge.
                        </p>

                        <h2 class="text-3xl font-serif font-bold text-slate-900 mt-16 mb-8 border-l-4 border-emerald-600 pl-6">Publishing Ethics & Integrity</h2>
                        <p class="text-slate-600 leading-relaxed text-lg">
                            Integrity is the cornerstone of academic publishing. HJPARAM strictly adheres to the guidelines set forth by the Committee on Publication Ethics (COPE). We implement robust plagiarism detection systems and double-blind peer-review processes to safeguard the originality and technical accuracy of our publications.
                        </p>
                        <p class="text-slate-600 leading-relaxed text-lg">
                            Our editorial board consists of distinguished scholars who provide oversight and strategic direction, ensuring that our journals remain at the forefront of their respective fields. We advocate for reviewer accountability and provide authors with constructive, detailed feedback to help refine and enhance their scholarly work.
                        </p>

                        <h2 class="text-3xl font-serif font-bold text-slate-900 mt-16 mb-8 border-l-4 border-amber-600 pl-6">Commitment to Open Access</h2>
                        <p class="text-slate-600 leading-relaxed text-lg">
                            We believe that knowledge should have no boundaries. As a champion of the Open Access movement, HJPARAM ensures that all published research is freely available to the public immediately upon publication. This model maximizes the reach and impact of research, fostering collaboration across borders and disciplines.
                        </p>
                        <p class="text-slate-600 leading-relaxed text-lg">
                            By removing paywalls, we enable researchers from developing regions, policy makers, and the general public to engage with the latest scientific discoveries. Our Open Access policy is compliant with major funding mandates, including Plan S and various national research council requirements.
                        </p>

                        <h2 class="text-3xl font-serif font-bold text-slate-900 mt-16 mb-8 border-l-4 border-purple-600 pl-6">Global Research Scope</h2>
                        <p class="text-slate-600 leading-relaxed text-lg">
                            HJPARAM hosts a diverse portfolio of journals covering Science, Engineering, Medicine, Social Sciences, and the Humanities. Our interdisciplinary approach encourages the cross-pollination of ideas, leading to innovative solutions for global challenges. Whether it is sustainable energy, advanced medical diagnostics, or socio-economic policy, our journals provide a platform for research that matters.
                        </p>
                    </article>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 py-12 border-y border-slate-100">
                        <div class="flex gap-6">
                            <div class="flex-shrink-0 w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center font-bold">01</div>
                            <div>
                                <h4 class="font-bold text-slate-900 mb-2">Rapid Publication</h4>
                                <p class="text-sm text-slate-500">Efficient editorial workflows ensure timely review and publication without compromising quality.</p>
                            </div>
                        </div>
                        <div class="flex gap-6">
                            <div class="flex-shrink-0 w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center font-bold">02</div>
                            <div>
                                <h4 class="font-bold text-slate-900 mb-2">Global Indexing</h4>
                                <p class="text-sm text-slate-500">Integration with Google Scholar, Scopus, and DOAJ to maximize your research visibility.</p>
                            </div>
                        </div>
                        <div class="flex gap-6">
                            <div class="flex-shrink-0 w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center font-bold">03</div>
                            <div>
                                <h4 class="font-bold text-slate-900 mb-2">Expert Board</h4>
                                <p class="text-sm text-slate-500">Guided by an international panel of experts dedicated to scholarly excellence.</p>
                            </div>
                        </div>
                        <div class="flex gap-6">
                            <div class="flex-shrink-0 w-12 h-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center font-bold">04</div>
                            <div>
                                <h4 class="font-bold text-slate-900 mb-2">Impact Analytics</h4>
                                <p class="text-sm text-slate-500">Real-time metrics for views, downloads, and citations to track your research influence.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-4">
                    <aside class="sticky top-24 space-y-8">
                        {{-- Trust Signals --}}
                        <div class="rounded-3xl bg-slate-50 border border-slate-100 p-8">
                            <h3 class="text-lg font-black text-slate-900 mb-6 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Trust & Standards
                            </h3>
                            <ul class="space-y-4">
                                <li class="flex items-center gap-3 text-sm font-bold text-slate-600">
                                    <span class="w-1.5 h-1.5 bg-blue-600 rounded-full"></span>
                                    COPE Compliant
                                </li>
                                <li class="flex items-center gap-3 text-sm font-bold text-slate-600">
                                    <span class="w-1.5 h-1.5 bg-blue-600 rounded-full"></span>
                                    Crossref Member
                                </li>
                                <li class="flex items-center gap-3 text-sm font-bold text-slate-600">
                                    <span class="w-1.5 h-1.5 bg-blue-600 rounded-full"></span>
                                    ORCID Integration
                                </li>
                                <li class="flex items-center gap-3 text-sm font-bold text-slate-600">
                                    <span class="w-1.5 h-1.5 bg-blue-600 rounded-full"></span>
                                    Creative Commons (CC BY)
                                </li>
                            </ul>
                        </div>

                        {{-- Quick Navigation --}}
                        <div class="rounded-3xl bg-[#0f172a] p-8 text-white">
                            <h3 class="text-sm font-black uppercase tracking-widest mb-6">Resources</h3>
                            <div class="space-y-3">
                                <a href="{{ route('author.guidelines') }}" class="flex items-center justify-between p-4 rounded-xl bg-white/5 border border-white/5 hover:bg-white/10 transition-all text-sm font-bold">
                                    Author Guidelines
                                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2.5"></path></svg>
                                </a>
                                <a href="{{ route('info.page', 'peer-review-process') }}" class="flex items-center justify-between p-4 rounded-xl bg-white/5 border border-white/5 hover:bg-white/10 transition-all text-sm font-bold">
                                    Review Process
                                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2.5"></path></svg>
                                </a>
                                <a href="{{ route('about.page', 'editorial-board') }}" class="flex items-center justify-between p-4 rounded-xl bg-white/5 border border-white/5 hover:bg-white/10 transition-all text-sm font-bold">
                                    Editorial Board
                                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2.5"></path></svg>
                                </a>
                            </div>
                        </div>

                        {{-- Institutional Contact --}}
                        <div class="rounded-3xl bg-blue-600 p-8 text-white shadow-2xl shadow-blue-600/30">
                            <h3 class="text-lg font-black mb-4">Partner with Us</h3>
                            <p class="text-blue-100 text-sm leading-relaxed mb-8">We offer specialized publishing solutions for academic societies and institutions.</p>
                            <a href="{{ route('contact.index') }}" class="block w-full py-4 bg-white text-blue-600 rounded-xl font-black text-xs uppercase tracking-widest text-center hover:bg-blue-50 transition-all">Contact Partnerships</a>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </section>
@endsection

