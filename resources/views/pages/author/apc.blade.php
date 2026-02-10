@extends('layouts.web')
@section('title', 'Article Processing Charges (APC) | HJPARAM Publication')

@section('content')
    <div class="bg-slate-50 py-24">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <span
                    class="inline-block px-3 py-1 bg-blue-100 text-blue-700 text-[10px] font-bold uppercase tracking-[0.2em] rounded-full mb-6">Transparency</span>
                <h1 class="text-4xl md:text-5xl font-serif font-bold text-slate-900 mb-8">Article Processing <span
                        class="text-blue-600">Charges</span></h1>

                <div class="bg-white p-8 md:p-12 rounded-[2rem] shadow-sm border border-slate-100 mb-12">
                    <div class="prose prose-slate max-w-none">
                        <p class="text-lg text-slate-600 mb-8 leading-relaxed">
                            HJPARAM Publication is committed to making all of its research output immediately available
                            through open access. To support this mission, we operate a transparent Article Processing Charge
                            (APC) model.
                        </p>

                        <h3 class="text-2xl font-bold text-slate-900 mb-4">Why do we charge APCs?</h3>
                        <p class="text-slate-600 mb-6">APC revenues are used to cover the costs associated with the
                            editorial process, technological infrastructure, and long-term archiving of articles. This
                            includes:</p>
                        <ul class="list-disc pl-6 space-y-2 text-slate-600 mb-8">
                            <li>Managing the peer-review process</li>
                            <li>Professional typesetting and formatting</li>
                            <li>Digital preservation and indexing in major databases</li>
                            <li>Marketing and promotion of published work</li>
                        </ul>

                        <div class="bg-blue-50 border-l-4 border-blue-600 p-6 rounded-r-2xl mb-12">
                            <h4 class="font-bold text-blue-900 mb-2">Current Rate</h4>
                            <p class="text-slate-700 font-medium">Standard APC: <span class="text-2xl font-bold">$1,200
                                    USD</span> per published article.</p>
                            <p class="text-xs text-blue-600 mt-2 font-bold uppercase tracking-widest">No hidden submission
                                fees</p>
                        </div>

                        <h3 class="text-2xl font-bold text-slate-900 mb-4">Waiver Policy</h3>
                        <p class="text-slate-600 mb-8">
                            We believe that financial constraints should not be a barrier to high-quality research. HJPARAM
                            offers full or partial waivers for authors from Low-Income Countries as defined by the World
                            Bank.
                        </p>
                    </div>
                </div>

                <div class="text-center">
                    <p class="text-slate-500 text-sm mb-6">Have questions regarding payments or waivers?</p>
                    <a href="{{ route('about.page', 'contact') }}"
                        class="inline-flex px-8 py-3 bg-slate-900 text-white font-bold rounded-full hover:bg-blue-600 transition-all shadow-lg">Contact
                        Billing Support</a>
                </div>
            </div>
        </div>
    </div>
@endsection