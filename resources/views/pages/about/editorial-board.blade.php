@extends('layouts.web')
@section('title', 'Editorial Board')
@section('meta_description', 'Meet the distinguished members of the HJPARAM Editorial Board, featuring international scholars and experts committed to research integrity.')

@section('content')
<div class="bg-slate-50 py-24">
    <div class="container mx-auto px-6 max-w-7xl">
        <div class="max-w-5xl mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 p-12 lg:p-20 border border-slate-100 mb-20 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-blue-50 rounded-bl-full opacity-50"></div>
                <div class="relative z-10">
                    <span class="inline-block px-4 py-1.5 bg-blue-50 text-blue-700 text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-8 border border-blue-100">Scholarly Leadership</span>
                    <h1 class="text-4xl md:text-6xl font-serif font-black text-slate-900 mb-8 tracking-tight leading-tight">Editorial Board</h1>
                    <p class="text-xl text-slate-500 leading-relaxed max-w-2xl font-light">
                        Guided by an international collective of distinguished scholars, our board ensures the highest standards of scientific rigor, ethics, and academic impact.
                    </p>
                </div>
            </div>

            <!-- Editor in Chief (Elite Profile) -->
            <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 p-10 lg:p-16 border border-slate-100 mb-20 flex flex-col lg:flex-row items-center gap-12 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-2 h-full bg-blue-600"></div>
                <div class="w-48 h-48 bg-[#0f172a] rounded-3xl flex-shrink-0 flex items-center justify-center text-white text-5xl font-serif font-black relative overflow-hidden group">
                    <div class="absolute inset-0 bg-blue-600 opacity-0 group-hover:opacity-10 transition-opacity"></div>
                    JS
                </div>
                <div class="flex-grow text-center lg:text-left">
                    <span class="text-[11px] font-black text-blue-600 uppercase tracking-[0.3em] mb-4 block">Editor-in-Chief</span>
                    <h2 class="text-3xl md:text-4xl font-black text-slate-900 mb-4 font-serif">Prof. Dr. James Sterling</h2>
                    <p class="text-slate-600 text-lg leading-relaxed mb-6 font-medium">Department of Bioengineering & Molecular Sciences, University of Oxford, UK.</p>
                    
                    <div class="flex flex-wrap justify-center lg:justify-start gap-4 mb-8">
                        <span class="px-4 py-2 bg-slate-50 text-slate-600 text-[11px] font-bold rounded-xl border border-slate-100 uppercase tracking-tight">ORCID: 0000-0002-1825-0097</span>
                        <span class="px-4 py-2 bg-slate-50 text-slate-600 text-[11px] font-bold rounded-xl border border-slate-100 uppercase tracking-tight">Scopus ID: 57193245600</span>
                    </div>

                    <div class="flex flex-wrap justify-center lg:justify-start gap-3">
                        <span class="px-3 py-1 bg-blue-50 text-blue-700 text-[10px] font-black rounded-lg uppercase tracking-widest border border-blue-100">Bioengineering</span>
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-black rounded-lg uppercase tracking-widest border border-emerald-100">Cellular Biology</span>
                        <span class="px-3 py-1 bg-purple-50 text-purple-700 text-[10px] font-black rounded-lg uppercase tracking-widest border border-purple-100">Ethics in AI</span>
                    </div>
                </div>
            </div>

            <!-- Section Editors (Refined Grid) -->
            <div class="flex items-center justify-between mb-12 px-4">
                <h2 class="text-3xl font-serif font-black text-slate-900 tracking-tight">Section <span class="text-blue-600">Editors</span></h2>
                <div class="h-px flex-grow mx-8 bg-slate-200 hidden md:block"></div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Global Representation</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-24">
                @php
                    $editors = [
                        [
                            'name' => 'Prof. Sarah Jenkins', 
                            'field' => 'Environmental Sciences', 
                            'uni' => 'Stanford University, USA',
                            'orcid' => '0000-0001-5123-4567',
                            'color' => 'emerald',
                            'initials' => 'SJ'
                        ],
                        [
                            'name' => 'Dr. Michael Chen', 
                            'field' => 'Inorganic Chemistry', 
                            'uni' => 'Tsinghua University, China',
                            'orcid' => '0000-0003-9876-5432',
                            'color' => 'blue',
                            'initials' => 'MC'
                        ],
                        [
                            'name' => 'Prof. Elena Rosetti', 
                            'field' => 'Public Health', 
                            'uni' => 'University of Bologna, Italy',
                            'orcid' => '0000-0002-4455-6677',
                            'color' => 'purple',
                            'initials' => 'ER'
                        ],
                        [
                            'name' => 'Dr. David Kumar', 
                            'field' => 'Quantum Physics', 
                            'uni' => 'Indian Institute of Science, India',
                            'orcid' => '0000-0001-8899-0011',
                            'color' => 'amber',
                            'initials' => 'DK'
                        ],
                    ];
                @endphp

                @foreach($editors as $editor)
                    <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-100 p-8 border border-slate-100 hover:-translate-y-2 transition-all duration-500 group">
                        <div class="flex items-center gap-6 mb-6">
                            <div class="w-16 h-16 bg-{{ $editor['color'] }}-50 text-{{ $editor['color'] }}-600 rounded-2xl flex items-center justify-center font-black text-lg shadow-inner group-hover:bg-{{ $editor['color'] }}-600 group-hover:text-white transition-all duration-500">{{ $editor['initials'] }}</div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900 group-hover:text-blue-600 transition-colors">{{ $editor['name'] }}</h3>
                                <p class="text-[10px] text-blue-600 font-black tracking-widest uppercase mb-1">{{ $editor['field'] }}</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <p class="text-sm text-slate-500 leading-relaxed font-medium">Senior faculty member at {{ $editor['uni'] }}. Specialist in high-impact research and cross-disciplinary methodology.</p>
                            <div class="pt-4 border-t border-slate-50 flex items-center justify-between">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">ORCID: {{ $editor['orcid'] }}</span>
                                <a href="#" class="text-[9px] font-black text-blue-600 uppercase tracking-widest hover:underline">Institutional Profile</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Join the Board CTA (Premium) -->
            <div class="bg-[#0f172a] rounded-[3rem] p-16 text-center text-white relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-full bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-10"></div>
                <div class="relative z-10">
                    <h3 class="text-3xl md:text-4xl font-black mb-6 font-serif">Contribute to Global <span class="text-blue-400">Research Integrity</span></h3>
                    <p class="text-slate-400 max-w-2xl mx-auto mb-10 text-lg font-light leading-relaxed">
                        We invite distinguished scholars with a proven track record of research excellence to join our international editorial team.
                    </p>
                    <div class="flex flex-col sm:flex-row justify-center items-center gap-6">
                        <a href="{{ route('about.page', 'contact-information') }}" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 px-12 py-5 rounded-2xl font-black text-xs uppercase tracking-[0.2em] transition-all shadow-2xl shadow-blue-600/20 active:scale-95">Apply to Join</a>
                        <a href="{{ route('info.page', 'reviewers') }}" class="w-full sm:w-auto bg-white/5 hover:bg-white/10 px-12 py-5 rounded-2xl font-black text-xs uppercase tracking-[0.2em] transition-all border border-white/10 active:scale-95">Reviewer Guidelines</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

