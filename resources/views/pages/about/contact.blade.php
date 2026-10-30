@extends('layouts.web')
@section('title', $page?->title ?? 'Contact Information')

@section('content')
    <div class="bg-slate-50 py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <!-- Header -->
                <div
                    class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 p-12 border border-slate-100 mb-8 relative overflow-hidden text-center">
                    <div class="absolute -top-10 -left-10 w-40 h-40 bg-blue-50 rounded-full opacity-50"></div>
                    <div class="relative z-10">
                        <span
                            class="inline-block px-3 py-1 bg-blue-50 text-blue-700 text-[10px] font-bold uppercase tracking-wider rounded-full mb-6">{{ $settings['contact_badge'] ?? 'Support Center' }}</span>
                        <h1 class="text-4xl md:text-5xl font-serif font-bold text-slate-900 mb-6">{{ $page?->title ?? ($settings['contact_title'] ?? 'Contact Information') }}</h1>
                        <p class="text-lg text-slate-500 leading-relaxed max-w-2xl mx-auto">
                            {{ $settings['contact_subtitle'] ?? 'Have questions about submission, formatting, or peer review? Our dedicated support team is here to help.' }}
                        </p>
                    </div>
                </div>

                @if(!empty($page?->content))
                    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 p-8 md:p-12 border border-slate-100 mb-12 prose prose-slate max-w-none text-slate-600 leading-relaxed">
                        {!! $page->content !!}
                    </div>
                @endif

                <!-- Contact Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                    <!-- Editorial Office -->
                    <div
                        class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 p-10 border border-slate-100 group hover:border-blue-200 transition">
                        <div
                            class="w-14 h-14 bg-blue-600 text-white rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-blue-100">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-900 mb-4 font-serif">{{ $settings['editorial_title'] ?? 'Editorial Office' }}</h2>
                        <p class="text-slate-500 mb-6 text-sm">{{ $settings['editorial_description'] ?? 'For questions related to article status, reviewer comments, or editorial policies.' }}</p>
                        <div class="space-y-3">
                            <a href="mailto:{{ $settings['editorial_email'] ?? 'editorial@hjparam.com' }}"
                                class="text-blue-600 font-bold hover:underline block">{{ $settings['editorial_email'] ??
                                'editorial@hjparam.com' }}</a>
                            <p class="text-slate-400 text-xs">
                                {{ $settings['editorial_response_time'] ?? 'Response time: Within 24-48 hours' }}</p>
                        </div>
                    </div>

                    <!-- Technical Support -->
                    <div
                        class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 p-10 border border-slate-100 group hover:border-blue-200 transition">
                        <div
                            class="w-14 h-14 bg-slate-800 text-white rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-slate-100">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-900 mb-4 font-serif">{{ $settings['support_title'] ?? 'Technical Support' }}</h2>
                        <p class="text-slate-500 mb-6 text-sm">{{ $settings['support_description'] ?? 'For issues regarding the submission portal, account login, or digital archiving.' }}</p>
                        <div class="space-y-3">
                            <a href="mailto:{{ $settings['support_email'] ?? 'support@hjparam.com' }}"
                                class="text-blue-600 font-bold hover:underline block">{{ $settings['support_email'] ??
                                'support@hjparam.com' }}</a>
                            <p class="text-slate-400 text-xs">
                                {{ $settings['support_hours'] ?? 'Available Mon-Fri, 9AM-5PM GMT' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Contact Direct Channels (Phone & WhatsApp if available) -->
                @if(!empty($settings['contact_phone']) || !empty($settings['whatsapp_number']) || !empty($settings['contact_email']))
                <div class="grid grid-cols-1 md:grid-cols-{{ (!empty($settings['contact_phone']) && !empty($settings['whatsapp_number'])) ? '2' : '1' }} gap-8 mb-12">
                    @if(!empty($settings['contact_phone']))
                    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 p-8 border border-slate-100 flex items-center gap-6">
                        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Phone Support</span>
                            <a href="tel:{{ $settings['contact_phone'] }}" class="text-lg font-bold text-slate-800 hover:text-blue-600 transition">{{ $settings['contact_phone'] }}</a>
                        </div>
                    </div>
                    @endif

                    @if(!empty($settings['whatsapp_number']))
                    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 p-8 border border-slate-100 flex items-center gap-6">
                        <div class="w-12 h-12 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">WhatsApp Support</span>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['whatsapp_number']) }}" target="_blank" rel="noopener noreferrer" class="text-lg font-bold text-green-700 hover:text-green-800 transition">{{ $settings['whatsapp_number'] }}</a>
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Physical Office -->
                <div
                    class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 p-12 border border-slate-100 flex flex-col md:flex-row items-center gap-10">
                    <div class="w-full md:w-1/3 bg-slate-100 h-48 rounded-2xl flex items-center justify-center">
                        <svg class="w-20 h-20 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="flex-1 text-center md:text-left">
                        <h3 class="text-2xl font-bold text-slate-900 mb-4 font-serif">{{ $settings['office_title'] ?? 'Global Headquarters' }}</h3>
                        <address class="not-italic text-slate-600 space-y-2 mb-6 leading-relaxed">
                            {!! nl2br(e($settings['office_address'] ?? "HJPARAM Publication Services\n124 Academic Way, Tech District\nInnovation Park, UK 50493")) !!}
                        </address>
                    </div>
                </div>

                <!-- Send Us a Message Quick Form Link -->
                <div class="mt-12 text-center">
                    <p class="text-sm text-slate-500 mb-4">Prefer to send us a direct message online?</p>
                    <a href="{{ route('contact.index') }}" class="inline-flex items-center gap-2 px-8 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/20 transition transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        Open Direct Message Form
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection