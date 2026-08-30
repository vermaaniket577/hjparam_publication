@extends('layouts.web')
@section('title', 'Editorial Board')

@section('content')
<div class="bg-slate-50 py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-5xl mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 p-12 border border-slate-100 mb-12 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50 rounded-bl-full opacity-50"></div>
                <div class="relative z-10">
                    <span class="inline-block px-3 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-bold uppercase tracking-wider rounded-full mb-6">Leadership</span>
                    <h1 class="text-4xl md:text-5xl font-serif font-bold text-slate-900 mb-6">Editorial Board</h1>
                    <p class="text-lg text-slate-500 leading-relaxed max-w-2xl">
                        Our board consists of world-renowned scholars and researchers dedicated to upholding the highest standards of academic excellence.
                    </p>
                </div>
            </div>

            <!-- Editor in Chief -->
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 p-8 border border-slate-100 mb-12 flex flex-col md:flex-row items-center gap-8">
                <div class="w-32 h-32 bg-slate-900 rounded-2xl flex-shrink-0 flex items-center justify-center text-white text-3xl font-bold font-serif">JS</div>
                <div>
                    <span class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mb-2 block">Editor-in-Chief</span>
                    <h2 class="text-2xl font-bold text-slate-900 mb-2 font-serif">Prof. Dr. James Sterling</h2>
                    <p class="text-slate-500 text-sm leading-relaxed mb-4">Department of Bioengineering, University of Excellence, UK. Specializing in molecular dynamics and cellular automation.</p>
                    <div class="flex gap-3">
                        <span class="px-3 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-lg uppercase tracking-tight">Bioengineering</span>
                        <span class="px-3 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-lg uppercase tracking-tight">Molecular Biology</span>
                    </div>
                </div>
            </div>

            <!-- Section Editors Grid -->
            <h2 class="text-2xl font-bold text-slate-900 mb-8 font-serif px-4 border-l-4 border-blue-600">Section Editors</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-16">
                @php
                    $editors = [
                        ['name' => 'Prof. Sarah Jenkins', 'field' => 'Environmental Sciences', 'uni' => 'Stanford University'],
                        ['name' => 'Dr. Michael Chen', 'field' => 'Inorganic Chemistry', 'uni' => 'MIT'],
                        ['name' => 'Prof. Elena Rosetti', 'field' => 'Public Health', 'uni' => 'Oxford University'],
                        ['name' => 'Dr. David Kumar', 'field' => 'Quantum Physics', 'uni' => 'ETH Zurich'],
                    ];
                @endphp

                @foreach($editors as $editor)
                    <div class="bg-white rounded-2xl shadow-lg shadow-slate-100 p-6 border border-slate-50 hover:-translate-y-1 transition duration-300">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center font-bold text-slate-400">{{ substr($editor['name'], 6, 1) }}</div>
                            <div>
                                <h3 class="font-bold text-slate-900 group-hover:text-blue-600 transition">{{ $editor['name'] }}</h3>
                                <p class="text-xs text-blue-600 font-medium tracking-tight uppercase">{{ $editor['field'] }}</p>
                            </div>
                        </div>
                        <p class="text-[13px] text-slate-500 leading-relaxed">{{ $editor['uni'] }}. Author of over 50 peer-reviewed articles in prestigious journals.</p>
                    </div>
                @endforeach
            </div>

            <div class="bg-slate-900 rounded-3xl p-12 text-center text-white">
                <h3 class="text-2xl font-bold mb-4 font-serif">Join Our Editorial Board</h3>
                <p class="text-slate-400 max-w-xl mx-auto mb-8">We are always looking for distinguished scholars to join our editorial team. If you are interested in becoming a section editor or reviewer, please contact us.</p>
                <a href="{{ route('about.page', 'contact-information') }}" class="bg-blue-600 hover:bg-blue-700 px-8 py-3 rounded-xl font-bold text-sm transition inline-block">Apply to Join</a>
            </div>
        </div>
    </div>
</div>
@endsection
