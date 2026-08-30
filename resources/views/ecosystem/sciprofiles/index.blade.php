@extends('layouts.web')
@section('title', 'SciProfiles | Academic Researcher Profiles')

@section('content')
    <div class="bg-slate-50 min-h-screen py-12">
        <div class="container mx-auto px-4">
            <div class="mb-12 text-center max-w-2xl mx-auto">
                <h1 class="text-4xl font-serif font-bold text-slate-900 mb-4">Researcher Profiles</h1>
                <p class="text-lg text-slate-600">Connect with experts, explore their bibliographies, and discover
                    collaborative opportunities.</p>
            </div>

            <div class="grid md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($profiles as $profile)
                    <div
                        class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl transition-all text-center group">
                        <div
                            class="w-24 h-24 rounded-full bg-slate-100 mx-auto mb-6 flex items-center justify-center overflow-hidden border-2 border-slate-50 group-hover:border-blue-100 transition">
                            @if($profile->profile_photo)
                                <img src="{{ asset('storage/' . $profile->profile_photo) }}" alt="{{ $profile->user->name }}"
                                    class="w-full h-full object-cover">
                            @else
                                <span class="text-2xl font-bold text-slate-300">{{ substr($profile->user->name, 0, 1) }}</span>
                            @endif
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-1">
                            <a href="{{ route('sciprofiles.show', $profile->id) }}"
                                class="hover:text-blue-900">{{ $profile->user->name }}</a>
                        </h3>
                        <p class="text-xs text-blue-600 font-bold uppercase tracking-widest mb-4">Academic Expert</p>
                        <p class="text-xs text-slate-500 line-clamp-2 mb-6">{{ $profile->affiliations }}</p>
                        <a href="{{ route('sciprofiles.show', $profile->id) }}"
                            class="inline-block px-4 py-2 bg-slate-900 text-white text-[10px] font-bold uppercase tracking-widest rounded-full hover:bg-blue-900 transition">View
                            Profile</a>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center bg-white rounded-3xl border border-dashed border-slate-200">
                        <p class="text-slate-400 font-bold uppercase tracking-widest">No researcher profiles found.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-12 text-center">
                {{ $profiles->links() }}
                @guest
                    <div class="mt-8">
                        <a href="{{ route('register') }}" class="text-blue-900 font-bold hover:underline">Create your SciProfile
                            &rarr;</a>
                    </div>
                @endguest
            </div>
        </div>
    </div>
@endsection