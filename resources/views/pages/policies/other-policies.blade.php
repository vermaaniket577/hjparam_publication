@extends('layouts.web')
@section('title', 'Other Policies & Publication Ethics | HJPARAM Publication')

@section('content')
<div class="bg-slate-50/60 py-12 md:py-16">
    <div class="container mx-auto px-4 lg:px-8 max-w-7xl">
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-8">
            <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Home</a>
            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('policies.index') }}" class="hover:text-blue-600 transition">Journal Policies</a>
            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-blue-900 font-bold">Other Policies</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-4 lg:sticky lg:top-24">
                @include('pages.policies.sidebar')
            </div>

            <!-- Policy Content -->
            <div class="lg:col-span-8 space-y-8">
                <!-- Header Banner -->
                <div class="bg-white rounded-3xl p-8 md:p-12 border border-slate-200/80 shadow-sm relative overflow-hidden">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-teal-50 text-teal-700 text-[10px] font-bold uppercase tracking-wider rounded-full mb-4">
                        <span class="w-1.5 h-1.5 rounded-full bg-teal-600"></span>
                        Publication Ethics & Editorial Governance
                    </div>
                    <h1 class="text-3xl md:text-4xl font-serif font-black text-slate-900 mb-4">
                        Other Editorial Policies & <span class="text-teal-600">Ethics</span>
                    </h1>
                    <p class="text-slate-600 text-sm md:text-base leading-relaxed">
                        Comprehensive ethical governance, authorship standards, clinical trial registration, data availability protocols, and whistleblowing safeguards governing all publications under HJPARAM.
                    </p>
                </div>

                <!-- Main Content Body -->
                <div class="bg-white rounded-3xl p-8 md:p-12 border border-slate-200/80 shadow-sm space-y-10 text-slate-700 text-sm md:text-base leading-relaxed">
                    <!-- Section 1: Authorship Criteria -->
                    <div>
                        <h2 class="text-xl font-serif font-bold text-slate-900 mb-3 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-teal-100 text-teal-700 font-bold text-sm flex items-center justify-center shrink-0">1</span>
                            ICMJE Authorship & Contributorship Standards
                        </h2>
                        <p class="text-slate-600 leading-relaxed mb-4">
                            HJPARAM strictly follows the <strong>International Committee of Medical Journal Editors (ICMJE)</strong> criteria. To qualify as an author, every individual must meet all four conditions:
                        </p>
                        <ol class="list-decimal pl-6 space-y-2 text-slate-600 text-sm mb-4">
                            <li>Substantial contributions to the conception or design of the work; or the acquisition, analysis, or interpretation of data.</li>
                            <li>Drafting the work or revising it critically for important intellectual content.</li>
                            <li>Final approval of the version to be published.</li>
                            <li>Agreement to be accountable for all aspects of the work in ensuring that questions related to accuracy or integrity are appropriately resolved.</li>
                        </ol>
                        <div class="p-4 bg-teal-50/50 rounded-2xl border border-teal-100 text-xs text-slate-700">
                            <strong>Ghost & Gift Authorship Prohibition:</strong> "Ghost authorship" (omitting true contributors) and "gift/honorary authorship" (listing individuals who did not meet the criteria) are classified as research misconduct. Non-author contributors should be acknowledged in the Acknowledgments section.
                        </div>
                    </div>

                    <!-- Section 2: Research Misconduct & Plagiarism -->
                    <div>
                        <h2 class="text-xl font-serif font-bold text-slate-900 mb-3 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-teal-100 text-teal-700 font-bold text-sm flex items-center justify-center shrink-0">2</span>
                            Zero Tolerance for Plagiarism & Data Falsification
                        </h2>
                        <p class="text-slate-600 leading-relaxed mb-3">
                            Plagiarism in any form—including verbatim copying, self-plagiarism/redundant publication without attribution, image duplication, or data fabrication—is strictly unacceptable:
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/70 text-xs">
                                <strong class="text-slate-900 block mb-1">Automated Screening</strong>
                                Every submission is scanned using iThenticate prior to peer review. Submissions exceeding acceptable similarity benchmarks or containing uncredited blocks are rejected.
                            </div>
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/70 text-xs">
                                <strong class="text-slate-900 block mb-1">Image Forensics</strong>
                                Figures and western blots are inspected for digital splicing, contrast manipulation, or duplicate panels. Raw unedited blot data may be requested during review.
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Human & Animal Ethics -->
                    <div>
                        <h2 class="text-xl font-serif font-bold text-slate-900 mb-3 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-teal-100 text-teal-700 font-bold text-sm flex items-center justify-center shrink-0">3</span>
                            Human Participants, Animal Welfare & Informed Consent
                        </h2>
                        <ul class="list-disc pl-6 space-y-2 text-slate-600 text-sm">
                            <li><strong>Human Subjects:</strong> Research must comply with the <em>Declaration of Helsinki</em>. Authors must state the name of the Institutional Review Board (IRB) or independent ethics committee and provide the approval reference number in the manuscript methods.</li>
                            <li><strong>Informed Consent:</strong> Written informed consent must be obtained from all patients/participants before inclusion. Any identifying personal details, patient names, or recognizable photographs must be omitted unless explicit written consent for publication is provided.</li>
                            <li><strong>Animal Welfare:</strong> Animal experiments must adhere to the <em>ARRIVE guidelines</em> and national/institutional animal welfare acts. Clear statements regarding anesthetic protocols and humane endpoints are mandatory.</li>
                        </ul>
                    </div>

                    <!-- Section 4: Conflict of Interest & Funding -->
                    <div>
                        <h2 class="text-xl font-serif font-bold text-slate-900 mb-3 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-teal-100 text-teal-700 font-bold text-sm flex items-center justify-center shrink-0">4</span>
                            Conflict of Interest & Funding Disclosures
                        </h2>
                        <p class="text-slate-600 leading-relaxed mb-3">
                            All authors, reviewers, and editors must disclose any financial, personal, or professional relationships that could be perceived as influencing the objectivity of the research or its evaluation. All sources of funding, grants, and sponsors must be explicitly stated in the manuscript.
                        </p>
                    </div>

                    <!-- Section 5: Data Availability Statement -->
                    <div>
                        <h2 class="text-xl font-serif font-bold text-slate-900 mb-3 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-teal-100 text-teal-700 font-bold text-sm flex items-center justify-center shrink-0">5</span>
                            Open Data & Code Reproducibility
                        </h2>
                        <p class="text-slate-600 leading-relaxed mb-3">
                            HJPARAM endorses the <strong>FAIR (Findable, Accessible, Interoperable, and Reusable) Data Principles</strong>. All published papers must include a dedicated <em>Data Availability Statement</em> detailing where raw experimental datasets, simulation code, or genomic sequences can be accessed (e.g., DOI, repository link, or upon reasonable request).
                        </p>
                    </div>

                    <!-- Section 6: Whistleblower & Complaints -->
                    <div>
                        <h2 class="text-xl font-serif font-bold text-slate-900 mb-3 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-teal-100 text-teal-700 font-bold text-sm flex items-center justify-center shrink-0">6</span>
                            Handling Allegations of Misconduct & Whistleblower Protection
                        </h2>
                        <p class="text-slate-600 leading-relaxed text-xs md:text-sm">
                            HJPARAM takes all allegations of misconduct seriously. When concerns are raised regarding data fabrication, duplicate submission, or authorship disputes, the Editor-in-Chief initiates a formal investigation following <strong>COPE Flowcharts</strong>. Confidentiality of whistleblowers is strictly safeguarded. Contact the Ethics Oversight Committee directly at <a href="mailto:ethics@hjparam.com" class="text-teal-700 font-bold hover:underline">ethics@hjparam.com</a>.
                        </p>
                    </div>

                    <!-- Bottom Nav -->
                    <div class="pt-6 border-t border-slate-100 flex flex-wrap items-center justify-between gap-4">
                        <a href="{{ route('policies.show', 'license-terms') }}" class="text-xs font-bold text-slate-500 hover:text-blue-600 transition flex items-center gap-1">
                            &larr; Previous: License Terms
                        </a>
                        <a href="{{ route('policies.index') }}" class="text-xs font-bold text-teal-700 hover:text-teal-900 transition flex items-center gap-1">
                            Complete Overview: Policies Hub &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
