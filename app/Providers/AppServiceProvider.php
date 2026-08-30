<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share settings, topics and menu data with all views
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            // Settings
            $settings = \Illuminate\Support\Facades\Cache::remember('site_settings', 3600, function () {
                return \App\Models\Setting::pluck('value', 'key')->toArray();
            });
            $view->with('settings', $settings);

            // Topics
            $topics = \Illuminate\Support\Facades\Cache::remember('global_topics', 3600, function () {
                return \App\Models\Topic::where('active', true)->orderBy('sort_order')->orderBy('name')->get();
            });
            $view->with('global_topics', $topics);

            // Dynamic menu pages
            $allPages = \Illuminate\Support\Facades\Cache::remember('global_menu_pages', 3600, function () {
                return \App\Models\Page::where('active', true)->orderBy('sort_order')->orderBy('title')->get();
            });

            $view->with('menu_info', $allPages->where('category', 'info'));
            $view->with('menu_author', $allPages->where('category', 'author'));
            $view->with('menu_initiatives', $allPages->where('category', 'initiatives'));
            $view->with('menu_about', $allPages->where('category', 'about'));

            // Featured Journals for layout dropdowns
            $featured_journals = \Illuminate\Support\Facades\Cache::remember('featured_journals_global', 3600, function () {
                return \App\Models\Journal::where('is_active', true)->take(5)->get();
            });
            $view->with('featured_journals', $featured_journals);
        });

        // Dynamic Mail Configuration from Database
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $mailSettings = \Illuminate\Support\Facades\Cache::remember('mail_settings', 3600, function () {
                    return \App\Models\Setting::where('key', 'like', 'mail_%')->pluck('value', 'key')->toArray();
                });

                if (!empty($mailSettings)) {
                    config([
                        'mail.mailers.smtp.host' => $mailSettings['mail_host'] ?? config('mail.mailers.smtp.host'),
                        'mail.mailers.smtp.port' => $mailSettings['mail_port'] ?? config('mail.mailers.smtp.port'),
                        'mail.mailers.smtp.encryption' => ($mailSettings['mail_encryption'] ?? config('mail.mailers.smtp.encryption')) === 'null' ? null : ($mailSettings['mail_encryption'] ?? config('mail.mailers.smtp.encryption')),
                        'mail.mailers.smtp.username' => $mailSettings['mail_username'] ?? config('mail.mailers.smtp.username'),
                        'mail.mailers.smtp.password' => $mailSettings['mail_password'] ?? config('mail.mailers.smtp.password'),
                        'mail.default' => $mailSettings['mail_mailer'] ?? config('mail.default'),
                        'mail.from.address' => $mailSettings['mail_from_address'] ?? config('mail.from.address'),
                        'mail.from.name' => $mailSettings['mail_from_name'] ?? config('app.name'),
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Avoid failing if table doesn't exist yet
        }
    }
}
