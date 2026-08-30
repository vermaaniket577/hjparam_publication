@extends('layouts.web')
@section('title', 'Article Editing Services | HJPARAM Publication')

@section('content')
    <div class="bg-slate-50 py-24">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <span
                    class="inline-block px-3 py-1 bg-blue-100 text-blue-700 text-[10px] font-bold uppercase tracking-[0.2em] rounded-full mb-6">Editorial
                    Support</span>
                <h1 class="text-4xl md:text-5xl font-serif font-bold text-slate-900 mb-8">Article Editing <span
                        class="text-blue-600">Services</span></h1>

                <div class="bg-white p-8 md:p-12 rounded-[2rem] shadow-sm border border-slate-100 mb-12">
                    <div class="prose prose-slate max-w-none">
                        <p class="text-lg text-slate-600 mb-12 leading-relaxed">
                            Clear communication is vital to scientific impact. Our professional editing services help
                            non-native speakers and busy researchers polish their manuscripts to ensure clarity, flow, and
                            technical accuracy.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
                            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 hover:shadow-md transition-all">
                                <h4 class="font-bold text-slate-900 mb-2">Proofreading</h4>
                                <p class="text-xs text-slate-500">Grammar, spelling, and punctuation checks for a final
                                    polish before submission.</p>
                            </div>
                            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 hover:shadow-md transition-all">
                                <h4 class="font-bold text-slate-900 mb-2">Substantive Editing</h4>
                                <p class="text-xs text-slate-500">In-depth review of structure, logic, and clarity of the
                                    scientific argument.</p>
                            </div>
                            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 hover:shadow-md transition-all">
                                <h4 class="font-bold text-slate-900 mb-2">Rapid Turnaround</h4>
                                <p class="text-xs text-slate-500">Express services available for urgent conference or
                                    journal deadlines.</p>
                            </div>
                        </div>

                        <div
                            class="p-8 bg-blue-900 rounded-[3rem] text-white flex flex-col md:flex-row items-center justify-between gap-8">
                            <div>
                                <h3 class="text-xl font-bold mb-2">Request a Quote</h3>
                                <p class="text-blue-200 text-sm">Upload your manuscript for a free assessment and price
                                    estimate.</p>
                            </div>
                            <a href="{{ route('about.page', 'contact') }}"
                                class="px-8 py-3 bg-white text-blue-900 font-bold rounded-full hover:bg-blue-50 transition-all shadow-xl">Get
                                Started</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection