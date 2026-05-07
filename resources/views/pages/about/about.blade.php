@extends('layouts.web')

@section('title', 'About HJPARAM Publication')

@section('content')
    <section class="bg-slate-950 text-white py-16">
        <div class="container mx-auto px-6 lg:px-12">
            <p class="text-xs font-black uppercase tracking-[0.3em] text-emerald-400 mb-4">About HJPARAM</p>
            <h1 class="text-4xl md:text-6xl font-serif font-black tracking-tight max-w-4xl">
                Advancing ethical, accessible, peer-reviewed academic publishing.
            </h1>
            <p class="mt-6 max-w-3xl text-lg leading-8 text-slate-300">
                HJPARAM Publication supports researchers, authors, reviewers, and institutions through a transparent
                publication workflow focused on quality, originality, and global research visibility.
            </p>
        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="container mx-auto px-6 lg:px-12 grid grid-cols-1 lg:grid-cols-3 gap-10">
            <div class="lg:col-span-2 space-y-8">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 mb-4">Who We Are</h2>
                    <p class="text-slate-600 leading-8">
                        HJPARAM Publication is an academic publishing platform created to help researchers share
                        high-quality scholarly work across disciplines. Our platform provides journal discovery,
                        manuscript submission, peer-review support, conference visibility, and publication resources for
                        authors at every stage of their research journey.
                    </p>
                </div>

                <div>
                    <h2 class="text-2xl font-black text-slate-900 mb-4">Our Mission</h2>
                    <p class="text-slate-600 leading-8">
                        Our mission is to make research publication more transparent, responsible, and accessible. We
                        encourage ethical publishing practices, originality, reviewer accountability, and clear
                        communication between authors, editors, and readers.
                    </p>
                </div>

                <div>
                    <h2 class="text-2xl font-black text-slate-900 mb-4">What We Support</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach(['Peer-reviewed journals', 'Author submission guidance', 'Publication ethics', 'Research visibility', 'Editorial workflows', 'Academic conferences'] as $item)
                            <div class="rounded-xl border border-slate-100 bg-slate-50 p-5 font-bold text-slate-700">
                                {{ $item }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <aside class="rounded-2xl border border-slate-100 bg-slate-50 p-6 h-fit">
                <h3 class="text-lg font-black text-slate-900 mb-4">Quick Links</h3>
                <div class="space-y-3">
                    <a href="{{ route('author.guidelines') }}" class="block rounded-lg bg-white px-4 py-3 text-sm font-bold text-slate-700 hover:text-blue-700">Author Guidelines</a>
                    <a href="{{ route('info.page', 'peer-review-process') }}" class="block rounded-lg bg-white px-4 py-3 text-sm font-bold text-slate-700 hover:text-blue-700">Peer Review Process</a>
                    <a href="{{ route('contact.index') }}" class="block rounded-lg bg-white px-4 py-3 text-sm font-bold text-slate-700 hover:text-blue-700">Contact Us</a>
                </div>
            </aside>
        </div>
    </section>
@endsection
