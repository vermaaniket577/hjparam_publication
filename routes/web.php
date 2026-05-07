<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CitationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

// Ecosystem Modules are handled in routes/ecosystem.php


// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/search', [\App\Http\Controllers\SearchController::class, 'index'])->name('search');
Route::get('/advanced-search', [\App\Http\Controllers\SearchController::class, 'advanced'])->name('search.advanced');

// Navigation Routes
Route::get('/journals', [JournalController::class, 'index'])->name('journals.index');
Route::get('/journals/subjects', [JournalController::class, 'subjects'])->name('journals.subjects'); // Needs method
Route::get('/journals/{slug}', [JournalController::class, 'show'])->name('journals.show');
Route::get('/journals/{slug}/v{volume}/i{issue}', [JournalController::class, 'issue'])->name('journals.issue');

Route::get('/topics', [\App\Http\Controllers\PageController::class, 'show'])->defaults('category', 'topics')->name('topics.index');
Route::get('/topics/{slug}', [\App\Http\Controllers\PageController::class, 'show'])->defaults('category', 'topics')->name('topics.show');

Route::get('/info/{slug}', [\App\Http\Controllers\PageController::class, 'show'])->defaults('category', 'info')->name('info.page');

Route::get('/author/guidelines', [\App\Http\Controllers\PageController::class, 'guidelines'])->name('author.guidelines');
Route::get('/author/submit', [SubmissionController::class, 'create'])->middleware('auth')->name('author.submit');
Route::get('/author/{slug}', [\App\Http\Controllers\PageController::class, 'show'])->defaults('category', 'author')->name('author.page');

Route::get('/publication-payment', [PaymentController::class, 'create'])->name('payments.create');
Route::post('/publication-payment', [PaymentController::class, 'store'])->name('payments.store');
Route::get('/api/payment-settings', [PaymentController::class, 'settings'])->name('payments.settings');

Route::get('/initiatives/{slug}', [\App\Http\Controllers\PageController::class, 'show'])->defaults('category', 'initiatives')->name('initiatives.show');
Route::get('/about/{slug}', [\App\Http\Controllers\PageController::class, 'show'])->defaults('category', 'about')->name('about.page');

// Article Routes
Route::get('/journals/{journalSlug}/{articleSlug}', [ArticleController::class, 'show'])->name('articles.show');
Route::get('/articles/{id}/download', [ArticleController::class, 'download'])->name('articles.download');
Route::get('/articles/{article}/bibtex', [CitationController::class, 'downloadBibtex'])->name('articles.bibtex');


// Dashboard & Authenticated Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        $counts = [
            'active_submissions' => $user->submissions()->whereNotIn('status', ['published', 'rejected'])->count(),
            'pending_reviews' => $user->reviews()->whereNull('completed_at')->count(),
            'published_articles' => $user->submissions()->where('status', 'published')->count(),
        ];
        return view('dashboard', compact('counts'));
    })->name('dashboard');

    Route::get('/submissions', [SubmissionController::class, 'index'])->name('submission.index');
    Route::get('/submit', [SubmissionController::class, 'create'])->name('submission.create');
    Route::post('/submit', [SubmissionController::class, 'store'])->name('submission.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Reviewer Routes
    Route::resource('reviews', \App\Http\Controllers\ReviewController::class)->only(['index', 'show', 'update']);
});

// Admin Routes
Route::redirect('/admin', '/admin/dashboard');
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::resource('journals', \App\Http\Controllers\Admin\JournalController::class);
    Route::resource('articles', \App\Http\Controllers\Admin\ArticleController::class);


    Route::get('submissions/{submission}/view', [\App\Http\Controllers\Admin\SubmissionController::class, 'view'])->name('submissions.view');
    Route::get('submissions/{submission}/download', [\App\Http\Controllers\Admin\SubmissionController::class, 'download'])->name('submissions.download');
    Route::post('submissions/{submission}/assign', [\App\Http\Controllers\Admin\SubmissionController::class, 'assign'])->name('submissions.assign');
    Route::post('submissions/{submission}/publish', [\App\Http\Controllers\Admin\SubmissionController::class, 'publish'])->name('submissions.publish');
    Route::resource('submissions', \App\Http\Controllers\Admin\SubmissionController::class);
    Route::resource('reviews', \App\Http\Controllers\Admin\ReviewController::class)->only(['index', 'destroy']);

    // System Routes
    Route::get('/analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');

    // Conference Management
    Route::resource('conferences', \App\Http\Controllers\Admin\ConferenceController::class);
    Route::patch('conferences/{conference}/toggle-featured', [\App\Http\Controllers\Admin\ConferenceController::class, 'toggleFeatured'])->name('conferences.toggle-featured');

    Route::resource('topics', \App\Http\Controllers\Admin\TopicController::class)->except(['show', 'create', 'edit']);
    Route::resource('countries', \App\Http\Controllers\Admin\CountryController::class)->except(['show', 'create', 'edit']);
    Route::resource('pages', \App\Http\Controllers\Admin\PageController::class)->except(['show']);
    Route::resource('partners', \App\Http\Controllers\Admin\PartnerController::class);
    Route::resource('news', \App\Http\Controllers\Admin\NewsController::class);
    Route::resource('subscriptions', \App\Http\Controllers\Admin\SubscriptionController::class)->only(['index', 'destroy', 'update']);
    Route::resource('contact-messages', \App\Http\Controllers\Admin\ContactController::class)->only(['index', 'show', 'destroy']);
    Route::get('payments/export', [\App\Http\Controllers\Admin\PaymentController::class, 'export'])->name('payments.export');
    Route::get('payments/{payment}/screenshot', [\App\Http\Controllers\Admin\PaymentController::class, 'downloadScreenshot'])->name('payments.screenshot');
    Route::patch('payments/{payment}/status', [\App\Http\Controllers\Admin\PaymentController::class, 'updateStatus'])->name('payments.status');
    Route::put('payments/settings', [\App\Http\Controllers\Admin\PaymentController::class, 'updateSettings'])->name('payments.settings');
    Route::get('payments', [\App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('payments.index');
});

// Organizer Panel
Route::middleware(['auth', 'verified', 'organizer'])->prefix('organizer')->name('organizer.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Organizer\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('conferences', \App\Http\Controllers\Organizer\ConferenceController::class);
});

// Public Search & Listings
Route::get('/conferences', [\App\Http\Controllers\Public\ConferenceController::class, 'index'])->name('conferences.index');
Route::get('/conferences/category/{slug}', [\App\Http\Controllers\Public\ConferenceController::class, 'category'])->name('conferences.category');
Route::get('/conferences/{slug}', [\App\Http\Controllers\Public\ConferenceController::class, 'show'])->name('conferences.show');

// Subscription
Route::get('/subscribe', [\App\Http\Controllers\Public\SubscriptionController::class, 'index'])->name('subscribe.page');
Route::post('/subscribe', [\App\Http\Controllers\Public\SubscriptionController::class, 'store'])->name('subscribe.store');

Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');
Route::get('/rss-feed', [\App\Http\Controllers\RSSFeedController::class, 'index'])->name('rss.feed');

// Contact
Route::get('/contact', [\App\Http\Controllers\Public\ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [\App\Http\Controllers\Public\ContactController::class, 'store'])->name('contact.store');

require __DIR__ . '/auth.php';
require __DIR__ . '/ecosystem.php';
