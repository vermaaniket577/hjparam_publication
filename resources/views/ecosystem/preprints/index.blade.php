@extends('layouts.web')
@section('title', 'Preprints | Open-Access Repository')

@section('content')
    <div class="bg-white min-h-screen py-12">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 border-b border-slate-100 pb-8">
                <div class="max-w-2xl">
                    <h1 class="text-4xl font-serif font-bold text-slate-900 mb-4">Preprints</h1>
                    <p class="text-lg text-slate-600">Early access to multi-disciplinary research. Share your work and
                        gather feedback from the global community.</p>
                </div>
                <div class="mt-6 md:mt-0">
                    <a href="#"
                        class="px-6 py-3 bg-blue-900 text-white font-bold rounded-lg hover:bg-blue-800 transition shadow-lg">Submit
                        Preprint</a>
                </div>
            </div>

            <div class="grid lg:grid-cols-4 gap-8">
                <div class="lg:col-span-1">
                    <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200">
                        <h4 class="font-bold text-slate-900 mb-4 uppercase text-xs tracking-widest">Filter by Subject</h4>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-sm text-slate-600 hover:text-blue-900">Physical Sciences</a></li>
                            <li><a href="#" class="text-sm text-slate-600 hover:text-blue-900">Life Sciences</a></li>
                            <li><a href="#" class="text-sm text-slate-600 hover:text-blue-900">Social Sciences</a></li>
                            <li><a href="#" class="text-sm text-slate-600 hover:text-blue-900">Medicine & Health</a></li>
                        </ul>
                    </div>
                </div>

                <div class="lg:col-span-3 space-y-6">
                    @forelse($preprints as $preprint)
                        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition">
                            <div class="flex items-center space-x-2 mb-3">
                                <span
                                    class="px-2 py-0.5 bg-blue-50 text-blue-700 text-[10px] font-bold rounded uppercase">v{{ $preprint->version }}</span>
                                <span class="text-xs text-slate-400 font-medium italic">Posted:
                                    {{ $preprint->created_at->format('M d, Y') }}</span>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 mb-2">
                                <a href="{{ route('preprints.show', $preprint->slug) }}"
                                    class="hover:text-blue-900 hover:underline">{{ $preprint->title }}</a>
                            </h3>
                            <p class="text-sm text-slate-600 mb-4 italic">by {{ $preprint->user->name }}</p>
                            <p class="text-slate-600 text-sm line-clamp-3 mb-6">{{ $preprint->abstract }}</p>
                            <div class="flex space-x-4 text-xs font-bold uppercase tracking-wider">
                                <a href="{{ route('preprints.show', $preprint->slug) }}"
                                    class="text-blue-900 hover:underline">View Abstract</a>
                                <a href="#" class="text-slate-400 hover:text-slate-900">PDF ({{ rand(1, 5) }} MB)</a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-20 bg-slate-50 rounded-2xl border border-dashed border-slate-300">
                            <p class="text-slate-500">No preprints available.</p>
                        </div>
                    @endforelse

                    <div class="mt-8">
                        {{ $preprints->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection