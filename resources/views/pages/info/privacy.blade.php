@extends('layouts.web')

@section('title', 'Privacy Policy | HJParam Publication')
@section('meta_description', 'Official privacy policy of HJParam Publication. Learn how we collect, use, and protect your personal and research data.')

@section('content')
    <div class="bg-slate-50 min-h-screen py-20 font-sans">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="max-w-5xl mx-auto">

                <!-- Header Section -->
                <div class="mb-12 text-center">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-1.5 bg-blue-50 text-blue-700 text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-6 border border-blue-100 shadow-sm">
                        Legal & Ethics
                    </div>
                    <h1 class="text-4xl md:text-6xl font-serif font-black text-slate-900 mb-6 tracking-tight">
                        Privacy <span class="text-blue-900 border-b-8 border-blue-100/50">Policy</span>
                    </h1>
                    <p class="text-slate-500 text-lg font-medium max-w-2xl mx-auto leading-relaxed">
                        Transparency and data integrity are the pillars of scientific publication. This policy outlines our
                        commitment to protecting the data of our global academic community.
                    </p>
                    <div
                        class="mt-8 flex items-center justify-center gap-6 text-xs font-bold text-slate-400 uppercase tracking-widest">
                        <span>Effective Date: February 01, 2024</span>
                        <span class="w-1.5 h-1.5 bg-slate-300 rounded-full"></span>
                        <span>Version 2.1</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">

                    <!-- Sidebar Navigation -->
                    <div class="hidden lg:block lg:col-span-1">
                        <div class="sticky top-32 space-y-2">
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6 px-4">
                                Navigation</h4>
                            <nav class="space-y-1">
                                <a href="#intro"
                                    class="block px-4 py-3 text-sm font-bold text-slate-600 hover:text-blue-900 hover:bg-blue-50 rounded-xl transition-all border-l-4 border-transparent hover:border-blue-600">Introduction</a>
                                <a href="#collection"
                                    class="block px-4 py-3 text-sm font-bold text-slate-600 hover:text-blue-900 hover:bg-blue-50 rounded-xl transition-all border-l-4 border-transparent hover:border-blue-600">Information
                                    We Collect</a>
                                <a href="#usage"
                                    class="block px-4 py-3 text-sm font-bold text-slate-600 hover:text-blue-900 hover:bg-blue-50 rounded-xl transition-all border-l-4 border-transparent hover:border-blue-600">Usage
                                    Information</a>
                                <a href="#cookies"
                                    class="block px-4 py-3 text-sm font-bold text-slate-600 hover:text-blue-900 hover:bg-blue-50 rounded-xl transition-all border-l-4 border-transparent hover:border-blue-600">Cookies
                                    & Tracking</a>
                                <a href="#security"
                                    class="block px-4 py-3 text-sm font-bold text-slate-600 hover:text-blue-900 hover:bg-blue-50 rounded-xl transition-all border-l-4 border-transparent hover:border-blue-600">Data
                                    Protection</a>
                                <a href="#rights"
                                    class="block px-4 py-3 text-sm font-bold text-slate-600 hover:text-blue-900 hover:bg-blue-50 rounded-xl transition-all border-l-4 border-transparent hover:border-blue-600">User
                                    Rights</a>
                                <a href="#retention"
                                    class="block px-4 py-3 text-sm font-bold text-slate-600 hover:text-blue-900 hover:bg-blue-50 rounded-xl transition-all border-l-4 border-transparent hover:border-blue-600">Data
                                    Retention</a>
                                <a href="#contact"
                                    class="block px-4 py-3 text-sm font-bold text-slate-600 hover:text-blue-900 hover:bg-blue-50 rounded-xl transition-all border-l-4 border-transparent hover:border-blue-600">Contact
                                    Details</a>
                            </nav>

                            <div class="mt-12 p-6 bg-blue-900 rounded-3xl text-white shadow-xl shadow-blue-900/20">
                                <h5 class="text-xs font-black uppercase tracking-widest mb-3">Expert Support</h5>
                                <p class="text-[11px] text-blue-100/70 leading-relaxed mb-4">Have questions regarding your
                                    data or GDPR compliance?</p>
                                <a href="mailto:privacy@hjparam.com"
                                    class="inline-block text-[10px] font-black uppercase tracking-widest text-white border-b border-blue-400 pb-0.5 hover:text-blue-200 transition-colors">Contact
                                    DPO</a>
                            </div>
                        </div>
                    </div>

                    <!-- Content Area -->
                    <div class="lg:col-span-3">
                        <div
                            class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                            <div class="p-10 md:p-16 space-y-16">

                                <!-- Introduction -->
                                <section id="intro" class="scroll-mt-32">
                                    <h2 class="text-2xl font-serif font-black text-slate-900 mb-6 flex items-center gap-4">
                                        <span
                                            class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm">01</span>
                                        Introduction
                                    </h2>
                                    <div class="prose prose-slate prose-lg max-w-none text-slate-600 leading-relaxed">
                                        <p>Your privacy is important to us. This Privacy Policy outlines how <strong>HJParam
                                                Publication</strong> collects, uses, stores, and protects personal
                                            information provided by authors, reviewers, editors, and website visitors. We
                                            are committed to maintaining the highest standards of data protection in
                                            alignment with international publishing ethics and legal frameworks such as
                                            GDPR.</p>
                                    </div>
                                </section>

                                <!-- Information We Collect -->
                                <section id="collection" class="scroll-mt-32">
                                    <h2 class="text-2xl font-serif font-black text-slate-900 mb-6 flex items-center gap-4">
                                        <span
                                            class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">02</span>
                                        Information We Collect
                                    </h2>
                                    <div class="prose prose-slate prose-lg max-w-none text-slate-600 leading-relaxed">
                                        <p>As an academic publisher, we collect data necessary for the scholarly review and
                                            publication process. This includes:</p>
                                        <ul class="grid grid-cols-1 md:grid-cols-2 gap-4 list-none pl-0">
                                            <li
                                                class="flex items-center gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                                <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                                                <span class="text-sm font-bold text-slate-700">Full Name &
                                                    Credentials</span>
                                            </li>
                                            <li
                                                class="flex items-center gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                                <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                                                <span class="text-sm font-bold text-slate-700">Institutional Affiliation
                                                    (ORCID)</span>
                                            </li>
                                            <li
                                                class="flex items-center gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                                <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                                                <span class="text-sm font-bold text-slate-700">Official Contact Email</span>
                                            </li>
                                            <li
                                                class="flex items-center gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                                <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                                                <span class="text-sm font-bold text-slate-700">Manuscript Metadata</span>
                                            </li>
                                        </ul>
                                    </div>
                                </section>

                                <!-- How We Use Information -->
                                <section id="usage" class="scroll-mt-32">
                                    <h2 class="text-2xl font-serif font-black text-slate-900 mb-6 flex items-center gap-4">
                                        <span
                                            class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm">03</span>
                                        How We Use Your Information
                                    </h2>
                                    <div class="prose prose-slate prose-lg max-w-none text-slate-600 leading-relaxed">
                                        <p>Collected data is used strictly to facilitate the publication lifecycle:</p>
                                        <div class="bg-blue-50/50 rounded-3xl p-8 border border-blue-100/50 space-y-4">
                                            <div class="flex gap-4">
                                                <svg class="w-6 h-6 text-blue-600 mt-1 shrink-0" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="M5 13l4 4L19 7" stroke-width="3" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                                <p class="text-sm font-medium"><span
                                                        class="font-black text-slate-900">Editorial Management:</span>
                                                    Overseeing manuscript submissions, peer review assignments, and
                                                    editorial decisions.</p>
                                            </div>
                                            <div class="flex gap-4">
                                                <svg class="w-6 h-6 text-blue-600 mt-1 shrink-0" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="M5 13l4 4L19 7" stroke-width="3" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                                <p class="text-sm font-medium"><span
                                                        class="font-black text-slate-900">Communication:</span> Notifying
                                                    authors of status updates, reviewer feedback, and final publication
                                                    proofs.</p>
                                            </div>
                                            <div class="flex gap-4">
                                                <svg class="w-6 h-6 text-blue-600 mt-1 shrink-0" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="M5 13l4 4L19 7" stroke-width="3" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                                <p class="text-sm font-medium"><span
                                                        class="font-black text-slate-900">Transparency:</span> Publicly
                                                    attributing research to the correct scholars and institutions in the
                                                    final cross-indexed articles.</p>
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <!-- Cookies Table -->
                                <section id="cookies" class="scroll-mt-32">
                                    <h2 class="text-2xl font-serif font-black text-slate-900 mb-6 flex items-center gap-4">
                                        <span
                                            class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-sm">04</span>
                                        Cookies & Tracking
                                    </h2>
                                    <div class="prose prose-slate prose-lg max-w-none text-slate-600 leading-relaxed">
                                        <p>Our website uses persistent and session cookies to improve system performance and
                                            analytics. </p>
                                        <div class="overflow-hidden border border-slate-200 rounded-2xl">
                                            <table class="min-w-full divide-y divide-slate-200">
                                                <thead class="bg-slate-50">
                                                    <tr>
                                                        <th
                                                            class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                                            Type</th>
                                                        <th
                                                            class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                                            Purpose</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-slate-100 text-sm">
                                                    <tr>
                                                        <td class="px-6 py-4 font-bold text-slate-700">Essential</td>
                                                        <td class="px-6 py-4">Required for secure manuscript uploads and
                                                            user login.</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="px-6 py-4 font-bold text-slate-700">Analytics</td>
                                                        <td class="px-6 py-4">Anonymized tracking to understand global
                                                            readership impact.</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </section>

                                <!-- Data Security -->
                                <section id="security" class="scroll-mt-32">
                                    <div
                                        class="bg-slate-900 rounded-[2.5rem] p-10 md:p-14 text-white relative overflow-hidden">
                                        <div
                                            class="absolute top-0 right-0 w-64 h-64 bg-blue-600/10 rounded-full blur-3xl -mr-32 -mt-32">
                                        </div>
                                        <div class="relative z-10">
                                            <h2 class="text-2xl font-serif font-black mb-6 flex items-center gap-4">
                                                <span
                                                    class="w-8 h-8 rounded-lg bg-white/10 text-white flex items-center justify-center text-sm border border-white/20">05</span>
                                                Data Protection & Security
                                            </h2>
                                            <p class="text-slate-300 leading-relaxed mb-8">We implement administrative,
                                                technical, and physical safeguards designed to protect personal data against
                                                accidental, unlawful, or unauthorized destruction, loss, alteration, access,
                                                disclosure, or use.</p>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <div class="flex items-center gap-3">
                                                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path
                                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                                            stroke-width="2" />
                                                    </svg>
                                                    <span
                                                        class="text-xs font-black uppercase tracking-widest text-blue-200">SSL
                                                        Encryption</span>
                                                </div>
                                                <div class="flex items-center gap-3">
                                                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path
                                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                                            stroke-width="2" />
                                                    </svg>
                                                    <span
                                                        class="text-xs font-black uppercase tracking-widest text-blue-200">Cross-Platform
                                                        Integrity</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <!-- User Rights -->
                                <section id="rights" class="scroll-mt-32">
                                    <h2 class="text-2xl font-serif font-black text-slate-900 mb-6 flex items-center gap-4">
                                        <span
                                            class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">06</span>
                                        User Rights
                                    </h2>
                                    <div class="prose prose-slate prose-lg max-w-none text-slate-600 leading-relaxed">
                                        <p>Users maintain full ownership and control over their personal data. You have the
                                            right to request access, rectification, or erasure of your scholarly profile
                                            data at any time.</p>
                                        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                                            <div class="p-6 rounded-3xl bg-slate-50 border border-slate-100">
                                                <h4
                                                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">
                                                    Access</h4>
                                                <p class="text-[11px] font-bold text-slate-600">Request a full data dump of
                                                    your profile.</p>
                                            </div>
                                            <div class="p-6 rounded-3xl bg-slate-50 border border-slate-100">
                                                <h4
                                                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">
                                                    Correction</h4>
                                                <p class="text-[11px] font-bold text-slate-600">Update affiliations or
                                                    contact details.</p>
                                            </div>
                                            <div class="p-6 rounded-3xl bg-slate-50 border border-slate-100">
                                                <h4
                                                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">
                                                    Deletion</h4>
                                                <p class="text-[11px] font-bold text-slate-600">Remove your profile from
                                                    active databases.</p>
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <!-- Third-Party Services -->
                                <section id="thirdparty" class="scroll-mt-32">
                                    <h2 class="text-2xl font-serif font-black text-slate-900 mb-6 flex items-center gap-4">
                                        <span
                                            class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm">07</span>
                                        Third-Party Services
                                    </h2>
                                    <div class="prose prose-slate prose-lg max-w-none text-slate-600 leading-relaxed">
                                        <p>We may share your data with third-party service providers strictly for
                                            operational purposes, such as DOI registration (Crossref), indexing services,
                                            and secure hosting providers. These partners are legally bound to uphold the
                                            same levels of data confidentiality as HJParam Publication.</p>
                                    </div>
                                </section>

                                <!-- Data Retention -->
                                <section id="retention" class="scroll-mt-32">
                                    <h2 class="text-2xl font-serif font-black text-slate-900 mb-6 flex items-center gap-4">
                                        <span
                                            class="w-8 h-8 rounded-lg bg-slate-50 text-slate-600 flex items-center justify-center text-sm">08</span>
                                        Data Retention
                                    </h2>
                                    <div class="prose prose-slate prose-lg max-w-none text-slate-600 leading-relaxed">
                                        <p>Personal data is retained only for as long as necessary to fulfill academic,
                                            legal, and operational requirements. Scholarly publication records (metadata)
                                            are managed as a permanent record of scientific discourse.</p>
                                    </div>
                                </section>

                                <!-- Policy Updates -->
                                <section id="updates" class="scroll-mt-32">
                                    <h2 class="text-2xl font-serif font-black text-slate-900 mb-6 flex items-center gap-4">
                                        <span
                                            class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center text-sm">09</span>
                                        Policy Updates
                                    </h2>
                                    <div class="prose prose-slate prose-lg max-w-none text-slate-600 leading-relaxed">
                                        <p>This Privacy Policy may be updated periodically to reflect changes in legal
                                            requirements or our data handling practices. Any significant changes will be
                                            communicated via our homepage or direct email to registered members.</p>
                                    </div>
                                </section>

                                <!-- Contact -->
                                <section id="contact" class="scroll-mt-32">
                                    <div
                                        class="bg-blue-50 rounded-3xl border border-blue-100 p-8 md:p-12 flex flex-col md:flex-row items-center gap-8">
                                        <div
                                            class="w-20 h-20 bg-blue-600 text-white rounded-full flex items-center justify-center shrink-0 shadow-lg shadow-blue-600/20">
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                                    stroke-width="2" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-serif font-black text-slate-900 mb-2">Privacy &
                                                Compliance Office</h3>
                                            <p class="text-slate-500 text-sm mb-4 leading-relaxed font-medium">For GDPR
                                                enquiries or data deletion requests, please contact our dedicated Data
                                                Protection Officer.</p>
                                            <div class="flex flex-wrap gap-4">
                                                <a href="mailto:privacy@hjparam.com"
                                                    class="text-sm font-black text-blue-600 uppercase tracking-widest hover:text-blue-800 flex items-center gap-2">
                                                    privacy@hjparam.com
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path d="M17 8l4 4m0 0l-4 4m4-4H3" stroke-width="2.5" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </section>

                            </div>
                        </div>

                        <div
                            class="mt-12 flex justify-between items-center text-xs font-bold text-slate-400 uppercase tracking-widest px-8">
                            <span>Latest Update: June 2024</span>
                            <a href="#" class="hover:text-blue-600 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                                        stroke-width="2.5" />
                                </svg>
                                Download PDF Version
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection