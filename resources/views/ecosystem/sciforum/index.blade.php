@extends('layouts.web')
@section('title', 'Sciforum | Academic Conferences')

@section('content')
    <div class="bg-slate-50 min-h-screen py-12">
        <div class="container mx-auto px-4">
            <div class="mb-12 text-center max-w-3xl mx-auto">
                <h1 class="text-4xl font-serif font-bold text-slate-900 mb-4">Academic Conferences & Events</h1>
                <p class="text-lg text-slate-600">Join the discussion with global researchers at our peer-reviewed forums
                    and workshops.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($events as $event)
                    <div
                        class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-lg transition group">
                        <div class="h-48 bg-slate-200 relative overflow-hidden">
                            @if($event->banner_image)
                                <img src="{{ asset('storage/' . $event->banner_image) }}" alt="{{ $event->title }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div
                                    class="w-full h-full flex items-center justify-center bg-blue-900 text-white text-4xl p-8 font-serif">
                                    {{ $event->title }}
                                </div>
                            @endif
                            <div class="absolute top-4 left-4">
                                <span
                                    class="px-3 py-1 bg-white/90 backdrop-blur rounded-full text-[10px] font-bold text-blue-900 uppercase tracking-widest">{{ $event->type }}</span>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-slate-900 mb-2 group-hover:text-blue-900 transition">
                                <a href="{{ route('sciforum.show', $event->slug) }}">{{ $event->title }}</a>
                            </h3>
                            <div class="flex items-center text-sm text-slate-500 mb-4">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                {{ $event->start_date->format('M d, Y') }}
                            </div>
                            <p class="text-slate-600 text-sm line-clamp-3 mb-6">{{ $event->description }}</p>
                            <a href="{{ route('sciforum.show', $event->slug) }}"
                                class="inline-flex items-center text-blue-900 font-bold text-sm hover:underline">
                                View Details
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                    </path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-20 bg-white rounded-2xl border border-dashed border-slate-300">
                        <p class="text-slate-500">No events scheduled at this time.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-12">
                {{ $events->links() }}
            </div>
        </div>
    </div>
@endsection