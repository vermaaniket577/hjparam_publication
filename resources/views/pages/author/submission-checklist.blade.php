@extends('layouts.web')
@section('title', 'Final Submission Checklist | HJPARAM')

@section('content')
    <div class="bg-slate-50 py-20 min-h-screen">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                {{-- Header Section --}}
                <div class="text-center mb-16">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-1.5 bg-blue-50 text-blue-600 rounded-full text-[10px] font-bold uppercase tracking-[0.2em] mb-6 border border-blue-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2"></path>
                        </svg>
                        Quality Assurance
                    </div>
                    <h1 class="text-4xl md:text-5xl font-serif font-bold text-slate-900 mb-6 italic">Submission <span
                            class="text-blue-600">Checklist</span></h1>
                    <p class="text-lg text-slate-500 max-w-2xl mx-auto leading-relaxed font-light">
                        To ensure a smooth peer-review process and avoid technical delays, please verify that your
                        manuscript complies with all the following requirements.
                    </p>
                </div>

                <div x-data="{ 
                        checked: [],
                        sections: [
                            { 
                                id: 'prep', 
                                title: 'Manuscript Preparation', 
                                icon: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                                items: [
                                    { id: 1, text: 'Title page includes a concise title, full author names, and affiliations.' },
                                    { id: 2, text: 'Structured abstract of 200–300 words is included.' },
                                    { id: 3, text: '3 to 7 keywords are provided for indexing purposes.' },
                                    { id: 4, text: 'All figures and tables are cited in the text in numerical order.' },
                                    { id: 5, text: 'References are formatted according to the journal style.' }
                                ]
                            },
                            { 
                                id: 'files', 
                                title: 'File & Format Requirements', 
                                icon: 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z',
                                items: [
                                    { id: 6, text: 'Manuscript is in Microsoft Word (.docx) or LaTeX format.' },
                                    { id: 7, text: 'All high-resolution images (min 300 dpi) are uploaded as separate files if required.' },
                                    { id: 8, text: 'Supplementary materials are clearly labeled and referenced.' },
                                    { id: 9, text: 'The text is double-spaced and uses a standard 12-point font.' }
                                ]
                            },
                            { 
                                id: 'ethics', 
                                title: 'Ethics & Declarations', 
                                icon: 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
                                items: [
                                    { id: 10, text: 'Conflict of Interest statement is included for all authors.' },
                                    { id: 11, text: 'Funding sources are explicitly mentioned in the acknowledgments.' },
                                    { id: 12, text: 'Institutional Review Board (IRB) approval is cited for human/animal studies.' },
                                    { id: 13, text: 'Informed consent was obtained from all participants.' }
                                ]
                            }
                        ],
                        toggle(id) {
                            if (this.checked.includes(id)) {
                                this.checked = this.checked.filter(i => i !== id);
                            } else {
                                this.checked.push(id);
                            }
                        },
                        get total() {
                            return this.sections.reduce((acc, s) => acc + s.items.length, 0);
                        },
                        get progress() {
                            return Math.round((this.checked.length / this.total) * 100);
                        }
                    }" class="space-y-12">

                    {{-- Progress Bar --}}
                    <div
                        class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 flex items-center gap-8 sticky top-24 z-30">
                        <div class="flex-grow">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Readiness
                                    Level</span>
                                <span class="text-lg font-bold text-blue-600 italic" x-text="progress + '%'"></span>
                            </div>
                            <div class="h-3 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-600 transition-all duration-500 rounded-full"
                                    :style="'width: ' + progress + '%'"></div>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <template x-if="progress === 100">
                                <div
                                    class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center animate-bounce">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M5 13l4 4L19 7" stroke-width="3"></path>
                                    </svg>
                                </div>
                            </template>
                            <template x-if="progress < 100">
                                <div
                                    class="w-12 h-12 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 8v4l3 3" stroke-width="2"></path>
                                    </svg>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Checklist Sections --}}
                    <template x-for="section in sections" :key="section.id">
                        <div
                            class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden">
                            <div class="bg-slate-50 px-10 py-6 border-b border-slate-100 flex items-center gap-4">
                                <div
                                    class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-blue-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path :d="section.icon" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                    </svg>
                                </div>
                                <h2 class="text-xl font-bold text-slate-900" x-text="section.title"></h2>
                            </div>
                            <div class="p-10 space-y-4">
                                <template x-for="item in section.items" :key="item.id">
                                    <div @click="toggle(item.id)"
                                        class="group flex items-start gap-4 p-5 rounded-2xl cursor-pointer transition-all duration-300 hover:bg-slate-50"
                                        :class="checked.includes(item.id) ? 'bg-blue-50/50 outline outline-2 outline-blue-100' : ''">
                                        <div class="w-6 h-6 rounded-md border-2 transition-all flex-shrink-0 mt-0.5 flex items-center justify-center"
                                            :class="checked.includes(item.id) ? 'bg-blue-600 border-blue-600' : 'border-slate-200 group-hover:border-blue-400'">
                                            <svg x-show="checked.includes(item.id)" class="w-4 h-4 text-white" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path d="M5 13l4 4L19 7" stroke-width="3" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                            </svg>
                                        </div>
                                        <p class="text-slate-600 leading-relaxed transition-colors"
                                            :class="checked.includes(item.id) ? 'text-slate-900 font-medium' : ''"
                                            x-text="item.text"></p>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    {{-- Action Footer --}}
                    <div class="text-center pt-8">
                        <p class="text-sm text-slate-400 mb-8 font-medium uppercase tracking-[0.2em]">Ready to contribute?
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="{{ route('author.submit') }}"
                                class="px-12 py-5 bg-blue-600 hover:bg-blue-700 text-white rounded-3xl font-bold transition shadow-2xl shadow-blue-600/30 group relative overflow-hidden">
                                <span class="relative z-10 flex items-center gap-2">
                                    PROCEED TO SUBMISSION
                                    <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M13 7l5 5m0 0l-5 5m5-5H6" stroke-width="2.5"></path>
                                    </svg>
                                </span>
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-blue-400/0 via-white/10 to-blue-400/0 -translate-x-full group-hover:translate-x-full transition-transform duration-1000">
                                </div>
                            </a>
                            <a href="{{ route('author.guidelines') }}"
                                class="px-12 py-5 bg-white hover:bg-slate-50 text-slate-600 rounded-3xl font-bold transition border border-slate-200 shadow-sm">
                                VIEW FULL GUIDELINES
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection