<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Journal;
use App\Models\Volume;
use App\Models\Issue;
use App\Models\Article;
use App\Models\Author;
use App\Models\Submission; // unused but imported
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(DemoDataSeeder::class);
        $this->call(EcosystemSeeder::class);
        $this->call(PagesSeeder::class);
        // Users
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@hjparam.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $editor = User::create([
            'name' => 'Editor User',
            'email' => 'editor@hjparam.com',
            'password' => bcrypt('password'),
            'role' => 'editor',
        ]);

        $author = User::create([
            'name' => 'Dr. Author',
            'email' => 'author@hjparam.com',
            'password' => bcrypt('password'),
            'role' => 'author',
            'affiliation' => 'University of Science',
        ]);

        // Journals
        $journal = Journal::create([
            'title' => 'International Journal of Advanced Research',
            'slug' => 'ijar',
            'description' => 'A premier journal for advanced research bridging multiple disciplines.',
            'issn' => '1234-5678',
            'impact_factor' => 4.5,
            'aims_and_scope' => 'Publishes high quality research in all fields of science and technology.',
            'is_active' => true,
        ]);

        $journal2 = Journal::create([
            'title' => 'Journal of Medical Sciences',
            'slug' => 'jms',
            'description' => 'Leading journal in medical breakthroughs.',
            'issn' => '9876-5432',
            'impact_factor' => 3.2,
            'aims_and_scope' => 'Clinical studies, medical reviews, and case reports.',
            'is_active' => true,
        ]);

        // Volumes & Issues for Journal 1
        $volume = Volume::create([
            'journal_id' => $journal->id,
            'volume_number' => '1',
            'year' => 2025,
            'is_published' => true,
        ]);

        $issue = Issue::create([
            'volume_id' => $volume->id,
            'issue_number' => '1',
            'publication_date' => now()->subMonths(1),
            'is_published' => true,
        ]);

        // Articles
        $article = Article::create([
            'journal_id' => $journal->id,
            'issue_id' => $issue->id,
            'submission_id' => null,
            'title' => 'The Future of AI in Publishing',
            'slug' => 'future-of-ai-publishing',
            'abstract' => 'This paper explores how AI agents will revolutionize academic publishing by automating peer review and layout.',
            'status' => 'published',
            'published_at' => now()->subDays(10),
            'views_count' => 120,
            'downloads_count' => 45,
            'doi' => '10.1000/ijar.2025.1.1',
        ]);

        Author::create([
            'article_id' => $article->id,
            'name' => 'Dr. Author',
            'email' => 'author@hjparam.com',
            'affiliation' => 'University of Science',
            'is_corresponding' => true,
        ]);

        Author::create([
            'article_id' => $article->id,
            'name' => 'Jane Scientist',
            'email' => 'jane@university.edu',
            'affiliation' => 'Tech Institute',
            'is_corresponding' => false,
        ]);

        // Another Article
        $article2 = Article::create([
            'journal_id' => $journal->id,
            'issue_id' => $issue->id,
            'title' => 'Sustainable Energy Solutions',
            'slug' => 'sustainable-energy',
            'abstract' => 'A review of recent advancements in solar and wind energy technologies.',
            'status' => 'published',
            'published_at' => now()->subDays(5),
            'views_count' => 85,
            'downloads_count' => 20,
            'doi' => '10.1000/ijar.2025.1.2',
        ]);

        Author::create([
            'article_id' => $article2->id,
            'name' => 'John Engineer',
            'affiliation' => 'Energy Corp',
        ]);

        // Submission
        $submission = Submission::create([
            'user_id' => $author->id,
            'journal_id' => $journal2->id,
            'title' => 'New Virus Strain Analysis',
            'abstract' => 'Detailed analysis of the new strain.',
            'status' => 'submitted',
            'file_path' => 'submissions/sample.pdf',
        ]);
    }
}
