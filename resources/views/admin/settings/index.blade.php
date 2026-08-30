@extends('layouts.admin')

@section('title', 'System Settings')
@section('breadcrumb', 'Settings')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">System Settings</h2>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-emerald-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-emerald-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- General Settings -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">General Information</h3>
                            <p class="text-sm text-gray-500">Basic platform configuration.</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="site_name" class="block text-sm font-medium text-gray-700">Site Name</label>
                            <input type="text" name="site_name" id="site_name" value="{{ $settings['site_name'] ?? '' }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="contact_email" class="block text-sm font-medium text-gray-700">Main Contact
                                Email</label>
                            <input type="email" name="contact_email" id="contact_email"
                                value="{{ $settings['contact_email'] ?? '' }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="footer_text" class="block text-sm font-medium text-gray-700">Footer Copyright
                                Text</label>
                            <input type="text" name="footer_text" id="footer_text"
                                value="{{ $settings['footer_text'] ?? '' }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="partners_section_title" class="block text-sm font-medium text-gray-700">Partners
                                Section Title</label>
                            <input type="text" name="partners_section_title" id="partners_section_title"
                                value="{{ $settings['partners_section_title'] ?? 'Affiliated Societies & Initiatives' }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div class="md:col-span-2">
                            <label for="manuscript_template_url" class="block text-sm font-medium text-gray-700">Manuscript
                                Template URL (.DOCX)</label>
                            <input type="text" name="manuscript_template_url" id="manuscript_template_url"
                                value="{{ $settings['manuscript_template_url'] ?? '#' }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                placeholder="Paste link to .docx template (e.g. from Google Drive or Dropbox)">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Page Details -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-emerald-100 text-emerald-600 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Contact & Support Details</h3>
                            <p class="text-sm text-gray-500">Manage information displayed on the contact page.</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Editorial -->
                        <div class="space-y-4">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Editorial Office</h4>
                            <div>
                                <label for="editorial_email" class="block text-sm font-medium text-gray-700">Email
                                    Address</label>
                                <input type="email" name="editorial_email" id="editorial_email"
                                    value="{{ $settings['editorial_email'] ?? '' }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="editorial_response_time"
                                    class="block text-sm font-medium text-gray-700">Response Description</label>
                                <input type="text" name="editorial_response_time" id="editorial_response_time"
                                    value="{{ $settings['editorial_response_time'] ?? '' }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>
                        </div>

                        <!-- Technical -->
                        <div class="space-y-4">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Technical Support</h4>
                            <div>
                                <label for="support_email" class="block text-sm font-medium text-gray-700">Email
                                    Address</label>
                                <input type="email" name="support_email" id="support_email"
                                    value="{{ $settings['support_email'] ?? '' }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="support_hours" class="block text-sm font-medium text-gray-700">Available
                                    Hours</label>
                                <input type="text" name="support_hours" id="support_hours"
                                    value="{{ $settings['support_hours'] ?? '' }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    <div>
                        <label for="office_address" class="block text-sm font-medium text-gray-700">Global Headquarters
                            Address</label>
                        <textarea name="office_address" id="office_address" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">{{ $settings['office_address'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Social Media & Connection -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-indigo-100 text-indigo-600 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Social Media & Connections</h3>
                            <p class="text-sm text-gray-500">Configure public links and messaging channels.</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- URLs -->
                        <div class="space-y-4">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Profiles</h4>
                            <div>
                                <label for="facebook_url" class="block text-sm font-medium text-gray-700">Facebook
                                    URL</label>
                                <input type="url" name="facebook_url" id="facebook_url"
                                    value="{{ $settings['facebook_url'] ?? '' }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="twitter_url" class="block text-sm font-medium text-gray-700">Twitter / X
                                    URL</label>
                                <input type="url" name="twitter_url" id="twitter_url"
                                    value="{{ $settings['twitter_url'] ?? '' }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="linkedin_url" class="block text-sm font-medium text-gray-700">LinkedIn
                                    URL</label>
                                <input type="url" name="linkedin_url" id="linkedin_url"
                                    value="{{ $settings['linkedin_url'] ?? '' }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>
                        </div>

                        <!-- More Profiles + Phone -->
                        <div class="space-y-4">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Visuals & Direct</h4>
                            <div>
                                <label for="youtube_url" class="block text-sm font-medium text-gray-700">YouTube Channel
                                    URL</label>
                                <input type="url" name="youtube_url" id="youtube_url"
                                    value="{{ $settings['youtube_url'] ?? '' }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="instagram_url" class="block text-sm font-medium text-gray-700">Instagram
                                    URL</label>
                                <input type="url" name="instagram_url" id="instagram_url"
                                    value="{{ $settings['instagram_url'] ?? '' }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>
                            <hr class="border-gray-100 my-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="contact_phone" class="block text-sm font-medium text-gray-700">Public
                                        Phone</label>
                                    <input type="text" name="contact_phone" id="contact_phone"
                                        value="{{ $settings['contact_phone'] ?? '' }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="whatsapp_number"
                                        class="block text-sm font-medium text-gray-700">WhatsApp</label>
                                    <input type="text" name="whatsapp_number" id="whatsapp_number"
                                        value="{{ $settings['whatsapp_number'] ?? '' }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mail Configuration -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden mb-8">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-rose-100 text-rose-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Mail Configuration</h3>
                                <p class="text-sm text-gray-500">Configure how the system sends emails (SMTP/Mailgun).</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="mail_mailer" class="block text-sm font-medium text-gray-700">Mail Driver</label>
                                <select name="mail_mailer" id="mail_mailer"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option value="smtp" {{ ($settings['mail_mailer'] ?? 'log') == 'smtp' ? 'selected' : '' }}>SMTP</option>
                                    <option value="mailgun" {{ ($settings['mail_mailer'] ?? 'log') == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                    <option value="sendmail" {{ ($settings['mail_mailer'] ?? 'log') == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                    <option value="log" {{ ($settings['mail_mailer'] ?? 'log') == 'log' ? 'selected' : '' }}>
                                        Log (Debug Only)</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label for="mail_host" class="block text-sm font-medium text-gray-700">Mail Host</label>
                                <input type="text" name="mail_host" id="mail_host"
                                    value="{{ $settings['mail_host'] ?? '' }}" placeholder="e.g. smtp.gmail.com"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="mail_port" class="block text-sm font-medium text-gray-700">Mail Port</label>
                                <input type="text" name="mail_port" id="mail_port"
                                    value="{{ $settings['mail_port'] ?? '587' }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="mail_encryption"
                                    class="block text-sm font-medium text-gray-700">Encryption</label>
                                <select name="mail_encryption" id="mail_encryption"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option value="tls" {{ ($settings['mail_encryption'] ?? 'tls') == 'tls' ? 'selected' : '' }}>TLS</option>
                                    <option value="ssl" {{ ($settings['mail_encryption'] ?? 'tls') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                    <option value="null" {{ ($settings['mail_encryption'] ?? 'tls') == 'null' ? 'selected' : '' }}>None</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="mail_from_address" class="block text-sm font-medium text-gray-700">From Email
                                    Address</label>
                                <input type="email" name="mail_from_address" id="mail_from_address"
                                    value="{{ $settings['mail_from_address'] ?? '' }}" placeholder="noreply@hjparam.com"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="mail_from_name" class="block text-sm font-medium text-gray-700">From
                                    Name</label>
                                <input type="text" name="mail_from_name" id="mail_from_name"
                                    value="{{ $settings['mail_from_name'] ?? '' }}" placeholder="HJPARAM Publication"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="mail_username" class="block text-sm font-medium text-gray-700">Mail
                                    Username</label>
                                <input type="text" name="mail_username" id="mail_username"
                                    value="{{ $settings['mail_username'] ?? '' }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="mail_password" class="block text-sm font-medium text-gray-700">Mail
                                    Password</label>
                                <input type="password" name="mail_password" id="mail_password"
                                    value="{{ $settings['mail_password'] ?? '' }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Toggles -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden mb-8">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-amber-100 text-amber-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m10 4a2 2 0 100-4m0 4a2 2 0 110-4m-4 1V4m0 16v-5"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Feature Management</h3>
                                <p class="text-sm text-gray-500">Enable or disable system modules.</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">New User Registration</h4>
                                <p class="text-xs text-gray-500">Allow new users to register on the platform.</p>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="allow_registration" value="1"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ ($settings['allow_registration'] ?? '0') == '1' ? 'checked' : '' }}>
                            </div>
                        </div>
                        <hr class="border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Submissions Open</h4>
                                <p class="text-xs text-gray-500">Allow authors to submit new manuscripts.</p>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="allow_submissions" value="1"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ ($settings['allow_submissions'] ?? '0') == '1' ? 'checked' : '' }}>
                            </div>
                        </div>
                        <hr class="border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Maintenance Mode</h4>
                                <p class="text-xs text-gray-500">Put the site into maintenance mode (admins only).</p>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="maintenance_mode" value="1"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ ($settings['maintenance_mode'] ?? '0') == '1' ? 'checked' : '' }}>
                            </div>
                        </div>
                        <hr class="border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Enable RSS Feed</h4>
                                <p class="text-xs text-gray-500">Allow users to subscribe via RSS.</p>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="enable_rss" value="1"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ ($settings['enable_rss'] ?? '0') == '1' ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pb-12">
                    <button type="button" onclick="window.history.back()"
                        class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-3 transition">
                        Cancel
                    </button>
                    <button type="submit"
                        class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-bold rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                        Save All Changes
                    </button>
                </div>
        </form>
    </div>
@endsection