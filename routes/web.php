<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CitationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\{
    ConferenceController,
    CountryController,
    DashboardController,
    UserController,
    JournalController as AdminJournalController,
    ArticleController as AdminArticleController,
    SubmissionController as AdminSubmissionController,
    ReviewController as AdminReviewController,
    AnalyticsController,
    SettingsController,
    TopicController,
    PageController as AdminPageController,
    PartnerController,
    NewsController,
    SubscriptionController,
    ContactController,
    PaymentController as AdminPaymentController
};
use Illuminate\Support\Facades\Route;

// Ecosystem Modules are handled in routes/ecosystem.php


// SEO Redirects for Duplicate URLs
Route::redirect('/about/about', '/about/about-hjparam', 301);
Route::redirect('/info/review-process', '/info/peer-review-process', 301);
Route::redirect('/info/contact', '/contact', 301);
Route::redirect('/about/contact-information', '/contact', 301);

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', function() { return redirect()->route('about.page', 'about-hjparam'); });

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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class);
    Route::resource('journals', JournalController::class);
    Route::resource('articles', ArticleController::class);


    Route::get('submissions/{submission}/view', [SubmissionController::class, 'view'])->name('submissions.view');
    Route::get('submissions/{submission}/download', [SubmissionController::class, 'download'])->name('submissions.download');
    Route::post('submissions/{submission}/assign', [SubmissionController::class, 'assign'])->name('submissions.assign');
    Route::post('submissions/{submission}/publish', [SubmissionController::class, 'publish'])->name('submissions.publish');
    Route::resource('submissions', SubmissionController::class);
    Route::resource('reviews', ReviewController::class)->only(['index', 'destroy']);

    // System Routes
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');

    // Conference Management
    Route::resource('conferences', ConferenceController::class);
    Route::patch('conferences/{conference}/toggle-featured', [ConferenceController::class, 'toggleFeatured'])->name('conferences.toggle-featured');

    Route::resource('topics', TopicController::class)->except(['show', 'create', 'edit']);
    Route::resource('countries', CountryController::class)->except(['show', 'create', 'edit']);
    Route::resource('pages', PageController::class)->except(['show']);
    Route::resource('partners', PartnerController::class);
    Route::resource('news', NewsController::class);
    Route::resource('subscriptions', SubscriptionController::class)->only(['index', 'destroy', 'update']);
    Route::resource('contact-messages', ContactController::class)->only(['index', 'show', 'destroy']);
    Route::get('payments/export', [AdminPaymentController::class, 'export'])->name('payments.export');
    Route::get('payments/{payment}/screenshot', [AdminPaymentController::class, 'downloadScreenshot'])->name('payments.screenshot');
    Route::patch('payments/{payment}/status', [AdminPaymentController::class, 'updateStatus'])->name('payments.status');
    Route::put('payments/settings', [AdminPaymentController::class, 'updateSettings'])->name('payments.settings');
    Route::get('payments', [AdminPaymentController::class, 'index'])->name('payments.index');
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
