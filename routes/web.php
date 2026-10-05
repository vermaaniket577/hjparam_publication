<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CitationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\Public\ConferenceController as PublicConferenceController;
use App\Http\Controllers\CertificateController;
use Illuminate\Support\Facades\Route;

// Ecosystem Modules are handled in routes/ecosystem.php

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/search', [\App\Http\Controllers\SearchController::class, 'index'])->name('search');
Route::get('/advanced-search', [\App\Http\Controllers\SearchController::class, 'advanced'])->name('search.advanced');

// Navigation Routes
Route::get('/journals', [JournalController::class, 'index'])->name('journals.index');
Route::get('/journals/subjects', [JournalController::class, 'subjects'])->name('journals.subjects');
Route::get('/journals/{slug}', [JournalController::class, 'show'])->name('journals.show');
Route::get('/journals/{slug}/v{volume}/i{issue}', [JournalController::class, 'issue'])->name('journals.issue');

// Public Conference Routes
Route::get('/conferences', [PublicConferenceController::class, 'index'])->name('conferences.index');
Route::get('/conferences/category/{slug}', [PublicConferenceController::class, 'category'])->name('conferences.category');
Route::get('/conferences/search/live', [PublicConferenceController::class, 'search'])->name('conferences.search.live');
Route::get('/conferences/{slug}', [PublicConferenceController::class, 'show'])->name('conferences.show');
Route::post('/conferences/{slug}/enquiry', [PublicConferenceController::class, 'enquire'])->name('conferences.enquiry');
Route::get('/conferences/{slug}/submit', [PublicConferenceController::class, 'submitPaper'])->name('conferences.submit');
Route::post('/conferences/{slug}/submit', [PublicConferenceController::class, 'storeSubmission'])->name('conferences.submit.store');

// Certificates Public Routes
Route::get('/certificates/attendee/{submission}', [CertificateController::class, 'attendee'])->name('certificates.attendee');
Route::get('/certificates/presentation/{submission}', [CertificateController::class, 'presentation'])->name('certificates.presentation');
Route::get('/certificates/verify/{code}', [CertificateController::class, 'verify'])->name('certificates.verify');

Route::get('/topics', [\App\Http\Controllers\PageController::class, 'show'])->defaults('category', 'topics')->name('topics.index');
Route::get('/topics/{slug}', [\App\Http\Controllers\PageController::class, 'show'])->defaults('category', 'topics')->name('topics.show');

Route::get('/info/{slug}', [\App\Http\Controllers\PageController::class, 'show'])->defaults('category', 'info')->name('info.page');

Route::get('/author/guidelines', [\App\Http\Controllers\PageController::class, 'guidelines'])->name('author.guidelines');
Route::get('/author/submit', [SubmissionController::class, 'create'])->middleware('auth')->name('author.submit');

// Author Downloads
Route::get('/author/download/copyright-form', [\App\Http\Controllers\Public\DownloadController::class, 'copyrightForm'])->name('author.download.copyright-form');
Route::get('/author/download/article-template', [\App\Http\Controllers\Public\DownloadController::class, 'articleTemplate'])->name('author.download.article-template');
Route::get('/downloads/copyright-form', [\App\Http\Controllers\Public\DownloadController::class, 'copyrightForm'])->name('downloads.copyright-form');
Route::get('/downloads/article-template', [\App\Http\Controllers\Public\DownloadController::class, 'articleTemplate'])->name('downloads.article-template');

Route::get('/author/{slug}', [\App\Http\Controllers\PageController::class, 'show'])->defaults('category', 'author')->name('author.page');

// Journal Policies Routes
Route::get('/policies', [\App\Http\Controllers\PageController::class, 'show'])->defaults('category', 'policies')->defaults('slug', 'index')->name('policies.index');
Route::redirect('/policies/disclamier', '/policies/disclaimer');
Route::get('/policies/{slug}', [\App\Http\Controllers\PageController::class, 'show'])->defaults('category', 'policies')->name('policies.show');

// Initiatives Routes
Route::get('/initiatives/join-us', [\App\Http\Controllers\PageController::class, 'show'])->defaults('category', 'initiatives')->defaults('slug', 'join-us')->name('initiatives.join-us');
Route::post('/initiatives/join-us', [\App\Http\Controllers\InitiativeController::class, 'storeJoinUs'])->name('initiatives.join-us.store');
Route::get('/initiatives/{slug}', [\App\Http\Controllers\PageController::class, 'show'])->defaults('category', 'initiatives')->name('initiatives.show');
Route::get('/about/{slug}', [\App\Http\Controllers\PageController::class, 'show'])->defaults('category', 'about')->name('about.page');

// Public Contact Routes
Route::get('/contact', [\App\Http\Controllers\Public\ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [\App\Http\Controllers\Public\ContactController::class, 'store'])->name('contact.store');

// Article Routes
Route::get('/journals/{journalSlug}/{articleSlug}', [ArticleController::class, 'show'])->name('articles.show');
Route::get('/articles/{id}/download', [ArticleController::class, 'download'])->name('articles.download');
Route::get('/articles/{article}/bibtex', [CitationController::class, 'downloadBibtex'])->name('articles.bibtex');

// Payment Gateway & Offline APC / Fee Routes
Route::get('/pay-fee', [\App\Http\Controllers\PaymentController::class, 'create'])->name('payments.create');
Route::post('/pay-fee', [\App\Http\Controllers\PaymentController::class, 'store'])->name('payments.store');
Route::post('/pay-fee/initiate', [\App\Http\Controllers\PaymentController::class, 'initiateOnlinePayment'])->name('payments.online.initiate');
Route::post('/pay-fee/verify-online', [\App\Http\Controllers\PaymentController::class, 'verifyOnlinePayment'])->name('payments.online.verify');
Route::get('/pay-fee/receipt/{payment}', [\App\Http\Controllers\PaymentController::class, 'receipt'])->name('payments.receipt');
Route::get('/pay-fee/settings', [\App\Http\Controllers\PaymentController::class, 'settings'])->name('payments.settings');

// Dashboard & Authenticated Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        $counts = [
            'active_submissions' => $user->submissions()->whereNotIn('status', ['published', 'rejected'])->count(),
            'pending_reviews' => $user->reviews()->whereNull('completed_at')->count(),
            'published_articles' => $user->submissions()->where('status', 'published')->count(),
        ];
        $recentSubmissions = $user->submissions()->with(['conference', 'journal'])->latest()->limit(10)->get();
        return view('dashboard', compact('counts', 'recentSubmissions'));
    })->name('dashboard');

    Route::get('/submissions', [SubmissionController::class, 'index'])->name('submission.index');
    Route::get('/submissions/{submission}', [SubmissionController::class, 'show'])->name('submission.show');
    Route::get('/submissions/{submission}/download', [SubmissionController::class, 'download'])->name('submission.download');
    Route::get('/submit', [SubmissionController::class, 'create'])->name('submission.create');
    Route::post('/submit', [SubmissionController::class, 'store'])->name('submission.store');

    // Author Fee Payment & Resubmission
    Route::get('/submissions/{submission}/payment', [PublicConferenceController::class, 'showPayment'])->name('submissions.payment');
    Route::post('/submissions/{submission}/payment', [PublicConferenceController::class, 'storePayment'])->name('submissions.payment.store');
    Route::get('/submissions/{submission}/resubmit', [PublicConferenceController::class, 'resubmit'])->name('submissions.resubmit');
    Route::post('/submissions/{submission}/resubmit', [PublicConferenceController::class, 'storeResubmit'])->name('submissions.resubmit.store');

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
    Route::match(['get', 'post'], 'users/{user}/accept-reviewer', [\App\Http\Controllers\Admin\UserController::class, 'acceptReviewer'])->name('users.accept-reviewer');
    Route::match(['get', 'post'], 'users/{user}/reject-reviewer', [\App\Http\Controllers\Admin\UserController::class, 'rejectReviewer'])->name('users.reject-reviewer');
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::resource('journals', \App\Http\Controllers\Admin\JournalController::class);
    // Journal Volumes & Issues Management
    Route::get('journals/{journal}/volumes', [\App\Http\Controllers\Admin\VolumeController::class, 'index'])->name('journals.volumes.index');
    Route::post('journals/{journal}/volumes', [\App\Http\Controllers\Admin\VolumeController::class, 'store'])->name('journals.volumes.store');
    Route::delete('journals/{journal}/volumes/{volume}', [\App\Http\Controllers\Admin\VolumeController::class, 'destroy'])->name('journals.volumes.destroy');
    Route::post('volumes/{volume}/issues', [\App\Http\Controllers\Admin\VolumeController::class, 'storeIssue'])->name('volumes.issues.store');
    Route::delete('volumes/{volume}/issues/{issue}', [\App\Http\Controllers\Admin\VolumeController::class, 'destroyIssue'])->name('volumes.issues.destroy');
    Route::resource('articles', \App\Http\Controllers\Admin\ArticleController::class);

    // Admin Conference Management
    Route::resource('conferences', \App\Http\Controllers\Admin\ConferenceController::class);
    Route::post('conferences/{conference}/toggle-featured', [\App\Http\Controllers\Admin\ConferenceController::class, 'toggleFeatured'])->name('conferences.toggle-featured');

    // Admin Submissions & 8-Step Lifecycle
    Route::get('submissions/{submission}/view', [\App\Http\Controllers\Admin\SubmissionController::class, 'view'])->name('submissions.view');
    Route::get('submissions/{submission}/download', [\App\Http\Controllers\Admin\SubmissionController::class, 'download'])->name('submissions.download');
    Route::post('submissions/{submission}/assign', [\App\Http\Controllers\Admin\SubmissionController::class, 'assign'])->name('submissions.assign');
    Route::post('submissions/{submission}/decision', [\App\Http\Controllers\Admin\SubmissionController::class, 'decision'])->name('submissions.decision');
    Route::post('submissions/{submission}/verify-payment', [\App\Http\Controllers\Admin\SubmissionController::class, 'verifyPayment'])->name('submissions.verify-payment');
    Route::post('submissions/{submission}/schedule-presentation', [\App\Http\Controllers\Admin\SubmissionController::class, 'schedulePresentation'])->name('submissions.schedule-presentation');
    Route::post('submissions/{submission}/mark-attendance', [\App\Http\Controllers\Admin\SubmissionController::class, 'markAttendance'])->name('submissions.mark-attendance');
    Route::post('submissions/{submission}/generate-certificate', [\App\Http\Controllers\Admin\SubmissionController::class, 'generateCertificate'])->name('submissions.generate-certificate');
    Route::resource('submissions', \App\Http\Controllers\Admin\SubmissionController::class);
    
    Route::resource('reviews', \App\Http\Controllers\Admin\ReviewController::class)->only(['index', 'destroy']);

    // System Routes
    Route::get('/analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');

    // Content & Taxonomy Management
    Route::resource('topics', \App\Http\Controllers\Admin\TopicController::class)->except(['show', 'create', 'edit']);
    Route::resource('pages', \App\Http\Controllers\Admin\PageController::class)->except(['show']);
    Route::resource('partners', \App\Http\Controllers\Admin\PartnerController::class);
    Route::resource('news', \App\Http\Controllers\Admin\NewsController::class);
    Route::resource('countries', \App\Http\Controllers\Admin\CountryController::class);
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('contacts', \App\Http\Controllers\Admin\ContactController::class)->names('contact-messages')->only(['index', 'show', 'destroy']);
    Route::get('contact-messages-alias', fn() => redirect()->route('admin.contact-messages.index'))->name('contacts.index');
    Route::resource('subscriptions', \App\Http\Controllers\Admin\SubscriptionController::class)->only(['index', 'update', 'destroy']);

    // System Migration Runner Helper
    Route::match(['get', 'post'], '/run-migrations', function () {
        try {
            \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            $output = \Illuminate\Support\Facades\Artisan::output();
            return back()->with('success', 'Database migrations executed successfully: ' . ($output ?: 'Done'));
        } catch (\Exception $e) {
            return back()->with('error', 'Migration error: ' . $e->getMessage());
        }
    })->name('run-migrations');

    // System Cache Clear Helper
    Route::match(['get', 'post'], '/clear-cache', function () {
        try {
            \Illuminate\Support\Facades\Artisan::call('optimize:clear');
            $output = \Illuminate\Support\Facades\Artisan::output();
            return back()->with('success', 'System cache cleared successfully! ' . ($output ?: 'Done'));
        } catch (\Exception $e) {
            return back()->with('error', 'Cache clear error: ' . $e->getMessage());
        }
    })->name('clear-cache');

    // Admin Payment Management
    Route::get('payments', [\App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('payments.index');
    Route::post('payments/settings', [\App\Http\Controllers\Admin\PaymentController::class, 'updateSettings'])->name('payments.settings');
    Route::post('payments/{payment}/status', [\App\Http\Controllers\Admin\PaymentController::class, 'updateStatus'])->name('payments.status');
    Route::get('payments/{payment}/screenshot', [\App\Http\Controllers\Admin\PaymentController::class, 'downloadScreenshot'])->name('payments.screenshot');
    Route::get('payments/export', [\App\Http\Controllers\Admin\PaymentController::class, 'export'])->name('payments.export');
});

Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');
Route::get('/rss-feed', [\App\Http\Controllers\RSSFeedController::class, 'index'])->name('rss.feed');

require __DIR__ . '/auth.php';
require __DIR__ . '/ecosystem.php';

// Asset fallback handler for shared hosting (when docroot is not set to /public)
Route::get('/build/{path}', function ($path) {
    $filePath = public_path('build/' . $path);
    if (!file_exists($filePath)) {
        $filePath = base_path('build/' . $path);
    }
    if (file_exists($filePath) && !is_dir($filePath)) {
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $mimes = [
            'css'   => 'text/css; charset=utf-8',
            'js'    => 'application/javascript; charset=utf-8',
            'json'  => 'application/json',
            'svg'   => 'image/svg+xml',
            'png'   => 'image/png',
            'jpg'   => 'image/jpeg',
            'jpeg'  => 'image/jpeg',
            'gif'   => 'image/gif',
            'ico'   => 'image/x-icon',
            'woff'  => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf'   => 'font/ttf',
            'eot'   => 'application/vnd.ms-fontobject',
        ];
        $contentType = $mimes[$ext] ?? 'text/plain';

        return response()->file($filePath, [
            'Content-Type' => $contentType,
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }
    abort(404);
})->where('path', '.*');

