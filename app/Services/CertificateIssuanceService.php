<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Certificate;
use App\Models\Submission;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Schema\Blueprint;

class CertificateIssuanceService
{
    /**
     * Ensure certificates table exists in database.
     */
    public static function ensureTableExists(): void
    {
        static $checked = false;
        if ($checked) {
            return;
        }

        try {
            if (!Schema::hasTable('certificates')) {
                Schema::create('certificates', function (Blueprint $table) {
                    $table->id();
                    $table->string('certificate_number')->unique();
                    $table->string('type')->default('publication');
                    $table->unsignedBigInteger('submission_id')->nullable()->index();
                    $table->unsignedBigInteger('article_id')->nullable()->index();
                    $table->unsignedBigInteger('user_id')->nullable()->index();
                    
                    // Recipient details
                    $table->string('recipient_name');
                    $table->string('recipient_role')->default('author');
                    $table->string('recipient_email')->nullable();
                    $table->string('designation')->nullable();
                    $table->string('department')->nullable();
                    $table->string('institution')->nullable();
                    $table->string('city_country')->nullable();
                    
                    // Publication details snapshot
                    $table->string('manuscript_id')->nullable();
                    $table->text('paper_title');
                    $table->string('journal_name')->nullable();
                    $table->string('volume')->nullable();
                    $table->string('issue')->nullable();
                    $table->string('month_year')->nullable();
                    $table->string('pages')->nullable();
                    $table->string('issn')->nullable();
                    $table->string('doi')->nullable();
                    $table->date('issue_date')->nullable();
                    
                    // Verification / security
                    $table->string('verification_hash')->nullable()->unique();
                    
                    $table->timestamps();

                    $table->index(['type', 'recipient_role']);
                    $table->index(['submission_id', 'article_id']);
                });
            } else {
                // Ensure pages column exists if table existed previously
                if (!Schema::hasColumn('certificates', 'pages')) {
                    Schema::table('certificates', function (Blueprint $table) {
                        $table->string('pages')->nullable()->after('month_year');
                    });
                }
            }
            $checked = true;
        } catch (\Throwable $e) {
            Log::warning('Certificate ensureTableExists: ' . $e->getMessage());
        }
    }

    /**
     * Issue certificates automatically for an accepted or published submission.
     * Generates one official certificate per author and co-author.
     */
    public static function issueForSubmission(Submission $submission): array
    {
        self::ensureTableExists();
        if (!Schema::hasTable('certificates')) {
            return [];
        }

        $submission->loadMissing(['journal', 'article', 'user']);
        $issued = [];

        $journalName = $submission->journal->title ?? 'HJPARAM International Journal';
        $issn = $submission->journal->issn ?? '2583-XXXX';
        $manuscriptId = 'HJP/' . ($submission->journal ? strtoupper(substr($submission->journal->slug ?? 'PUB', 0, 4)) : 'PUB') . '/' . date('Y') . '/' . str_pad($submission->id, 4, '0', STR_PAD_LEFT);
        $issueDate = now()->toDateString();
        $monthYear = now()->format('F Y');
        $doi = $submission->article->doi ?? ('10.XXXXX/hjparam.' . date('Y') . '.' . $submission->id);

        $authorsList = self::extractAuthors($submission);

        foreach ($authorsList as $idx => $person) {
            $code = 'HJP-' . date('Y') . '-' . str_pad($submission->id, 4, '0', STR_PAD_LEFT) . '-' . str_pad($idx + 1, 2, '0', STR_PAD_LEFT);

            $cert = Certificate::firstOrNew([
                'submission_id' => $submission->id,
                'recipient_name' => $person['name'],
            ]);

            if (!$cert->exists) {
                $cert->certificate_number = $code;
                $cert->verification_hash = hash('sha256', $code . '|' . $person['name'] . '|' . $submission->id . '|' . config('app.key'));
            }

            $cert->type = 'publication';
            $cert->article_id = $submission->article->id ?? null;
            $cert->user_id = $submission->user_id;
            $cert->recipient_role = $person['role'] ?? ($idx === 0 ? 'author' : 'co_author');
            $cert->recipient_email = $person['email'] ?? null;
            $cert->designation = $person['designation'] ?? null;
            $cert->department = $person['department'] ?? null;
            $cert->institution = $person['institution'] ?? null;
            $cert->city_country = $person['city_country'] ?? null;

            $cert->manuscript_id = $manuscriptId;
            $cert->paper_title = $submission->title;
            $cert->journal_name = $journalName;
            
            $rawVol = $submission->article?->issue?->volume?->volume_number ?? max(1, ((int)date('Y') - 2026 + 1));
            $rawIssue = $submission->article?->issue?->issue_number ?? date('n');
            $cert->volume = preg_replace('/^volume\s*/i', '', trim((string)$rawVol)) ?: '1';
            $cert->issue = preg_replace('/^issue\s*/i', '', trim((string)$rawIssue)) ?: '1';
            $cert->month_year = $monthYear;
            
            // Exact page numbers from published article or auto-calculated
            $articlePages = $submission->article ? $submission->article->formatted_pages : '01–08';
            $cert->pages = !empty($cert->pages) ? preg_replace('/^pp?\.?\s*/i', '', trim($cert->pages)) : $articlePages;
            
            $cert->issn = $issn;
            $cert->doi = $doi;
            $cert->issue_date = $cert->issue_date ?? $issueDate;

            $cert->save();
            $issued[] = $cert;
        }

        return $issued;
    }

    /**
     * Issue certificates directly for an Article.
     */
    public static function issueForArticle(Article $article): array
    {
        self::ensureTableExists();
        if (!Schema::hasTable('certificates')) {
            return [];
        }

        $article->loadMissing(['journal', 'authors', 'issue.volume', 'submission']);
        $issued = [];

        $journalName = $article->journal->title ?? 'HJPARAM International Journal';
        $issn = $article->journal->issn ?? '2583-XXXX';
        $manuscriptId = 'HJP/' . ($article->journal ? strtoupper(substr($article->journal->slug ?? 'PUB', 0, 4)) : 'PUB') . '/' . ($article->published_at ? $article->published_at->format('Y') : date('Y')) . '/' . str_pad($article->id, 4, '0', STR_PAD_LEFT);
        $issueDate = $article->published_at ? $article->published_at->toDateString() : now()->toDateString();
        $monthYear = $article->published_at ? $article->published_at->format('F Y') : now()->format('F Y');
        $volNum = $article->issue?->volume?->volume_number ?? ('Volume ' . max(1, ((int)date('Y') - 2026 + 1)));
        $issueNum = $article->issue?->issue_number ?? ('Issue ' . date('n'));
        $doi = $article->doi ?? ('10.XXXXX/hjparam.' . date('Y') . '.' . $article->id);

        $authors = $article->authors;

        if ($authors->isEmpty() && $article->submission) {
            return self::issueForSubmission($article->submission);
        }

        foreach ($authors as $idx => $author) {
            $code = 'HJP-' . date('Y') . '-ART' . str_pad($article->id, 4, '0', STR_PAD_LEFT) . '-' . str_pad($idx + 1, 2, '0', STR_PAD_LEFT);

            $cert = Certificate::firstOrNew([
                'article_id' => $article->id,
                'recipient_name' => $author->name,
            ]);

            if (!$cert->exists) {
                $cert->certificate_number = $code;
                $cert->verification_hash = hash('sha256', $code . '|' . $author->name . '|' . $article->id . '|' . config('app.key'));
            }

            // Find matching submission author metadata if available
            $meta = null;
            if ($article->submission) {
                $subAuthors = self::extractAuthors($article->submission);
                foreach ($subAuthors as $sa) {
                    if (strcasecmp(trim($sa['name'] ?? ''), trim($author->name)) === 0) {
                        $meta = $sa;
                        break;
                    }
                }
                if (!$meta && isset($subAuthors[$idx])) {
                    $meta = $subAuthors[$idx];
                }
            }

            $cert->type = 'publication';
            $cert->submission_id = $article->submission_id;
            $cert->user_id = $article->user_id ?? $article->submission?->user_id;
            $cert->recipient_role = $author->is_corresponding ? 'author' : 'co_author';
            $cert->recipient_email = $author->email ?? ($meta['email'] ?? null);
            $cert->designation = $meta['designation'] ?? ($cert->designation ?? null);
            $cert->department = $meta['department'] ?? ($cert->department ?? null);
            $cert->institution = $author->affiliation ?? ($meta['institution'] ?? ($cert->institution ?? null));
            $cert->city_country = $meta['city_country'] ?? ($cert->city_country ?? null);

            $cert->manuscript_id = $manuscriptId;
            $cert->paper_title = $article->title;
            $cert->journal_name = $journalName;
            
            $cert->volume = preg_replace('/^volume\s*/i', '', trim((string)$volNum)) ?: '1';
            $cert->issue = preg_replace('/^issue\s*/i', '', trim((string)$issueNum)) ?: '1';
            $cert->month_year = $monthYear;
            
            // Exact page numbers from article record
            $articlePages = $article->formatted_pages ?: '01–08';
            $cert->pages = !empty($cert->pages) ? preg_replace('/^pp?\.?\s*/i', '', trim($cert->pages)) : $articlePages;
            
            $cert->issn = $issn;
            $cert->doi = $doi;
            $cert->issue_date = $cert->issue_date ?? $issueDate;

            $cert->save();
            $issued[] = $cert;
        }

        return $issued;
    }

    /**
     * Extract normalized array of authors and co-authors from a submission.
     */
    public static function extractAuthors(Submission $submission): array
    {
        $list = [];

        // Check if submission has authors_data
        if (!empty($submission->authors_data) && is_array($submission->authors_data)) {
            foreach ($submission->authors_data as $i => $item) {
                $name = $item['name'] ?? trim(($item['first_name'] ?? '') . ' ' . ($item['last_name'] ?? ''));
                if (!empty($name)) {
                    $list[] = [
                        'name' => $name,
                        'role' => (!empty($item['is_corresponding']) || $i === 0) ? 'author' : 'co_author',
                        'email' => $item['email'] ?? null,
                        'designation' => $item['designation'] ?? null,
                        'department' => $item['department'] ?? null,
                        'institution' => $item['institution'] ?? ($item['affiliation'] ?? null),
                        'city_country' => $item['city_state_country'] ?? ($item['city_country'] ?? null),
                    ];
                }
            }
        }

        // Check scholars_data if authors_data was empty
        if (empty($list) && !empty($submission->scholars_data) && is_array($submission->scholars_data)) {
            foreach ($submission->scholars_data as $i => $item) {
                $name = trim(($item['first_name'] ?? '') . ' ' . ($item['last_name'] ?? ''));
                if (!empty($name)) {
                    $list[] = [
                        'name' => $name,
                        'role' => $i === 0 ? 'author' : 'co_author',
                        'email' => $item['email'] ?? null,
                        'designation' => $item['designation'] ?? null,
                        'department' => $item['department'] ?? null,
                        'institution' => $item['institution'] ?? ($item['affiliation'] ?? null),
                        'city_country' => $item['country'] ?? null,
                    ];
                }
            }
        }

        // Fallback to primary submission submitter user
        if (empty($list)) {
            $user = $submission->user;
            $list[] = [
                'name' => $user->name ?? 'Lead Author',
                'role' => 'author',
                'email' => $user->email ?? null,
                'designation' => $user->designation ?? 'Author / Scholar',
                'department' => $user->department ?? null,
                'institution' => $user->affiliation ?? 'Academic Institution',
                'city_country' => $user->country ?? null,
            ];
        }

        return $list;
    }
}
