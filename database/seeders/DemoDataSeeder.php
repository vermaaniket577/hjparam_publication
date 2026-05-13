<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Journal;
use App\Models\Volume;
use App\Models\Issue;
use App\Models\Article;
use App\Models\Partner;
use App\Models\Author;
use App\Models\Submission;
use App\Models\SciProfile;
use App\Models\SciforumEvent;
use App\Models\Preprint;
use App\Models\EncyclopediaEntry;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data to avoid collisions
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        \App\Models\Review::truncate();
        \App\Models\Submission::truncate();
        \App\Models\Author::truncate();
        \App\Models\Article::truncate();
        \App\Models\Issue::truncate();
        \App\Models\Volume::truncate();
        \App\Models\Journal::truncate();
        \App\Models\SciProfile::truncate();
        \App\Models\Preprint::truncate();
        \App\Models\EncyclopediaEntry::truncate();
        \App\Models\SciforumEvent::truncate();
        \App\Models\Topic::truncate();
        \App\Models\Page::truncate();
        // Keep users but clear non-admins for fresh start
        User::where('role', '!=', 'admin')->delete();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        // 1. Topics
        $topics = [
            'Physical Sciences',
            'Chemical Sciences',
            'Biological Sciences',
            'Engineering',
            'Medicine',
            'Social Sciences',
            'Humanities'
        ];
        foreach ($topics as $index => $name) {
            \App\Models\Topic::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'active' => true,
                'sort_order' => $index + 1
            ]);
        }

        // 2. Pages
        $pages = [
            ['title' => 'Open Access Policy', 'category' => 'info'],
            ['title' => 'Peer Review Process', 'category' => 'info'],
            ['title' => 'Editorial Board', 'category' => 'about'],
            ['title' => 'Contact Information', 'category' => 'about'],
            ['title' => 'Instructions for Authors', 'category' => 'author'],
            ['title' => 'Submission Checklist', 'category' => 'author'],
            ['title' => 'Global Sustainability Initiative', 'category' => 'initiatives'],
        ];
        foreach ($pages as $index => $pData) {
            \App\Models\Page::create([
                'title' => $pData['title'],
                'slug' => Str::slug($pData['title']),
                'category' => $pData['category'],
                'content' => fake()->paragraphs(5, true),
                'active' => true,
                'sort_order' => $index + 1
            ]);
        }

        // 3. Create a variety of Users
        $roles = ['admin', 'editor', 'author', 'reviewer'];
        foreach ($roles as $role) {
            User::factory()->count(3)->create(['role' => $role]);
        }

        $allAuthors = User::where('role', 'author')->get();
        $allEditors = User::where('role', 'editor')->get();

        $journalsData = [
            [
                'title' => 'Sensors & Actuators: Open Access',
                'slug' => 'sensors-actuators',
                'description' => 'A leading journal for sensor technologies and applications.',
                'issn' => 'XXXX-XXXX',
                'impact_factor' => 3.9,
                'aims_and_scope' => 'Covers chemical sensors, physical sensors, and biosensors.',
                'is_active' => true,
            ],
            [
                'title' => 'International Journal of Molecular Sciences',
                'slug' => 'ijms',
                'description' => 'Forum for scholarly research in biochemistry and molecular biology.',
                'issn' => 'XXXX-XXXX',
                'impact_factor' => 5.6,
                'aims_and_scope' => 'Intermolecular interactions, molecular electronics, and more.',
                'is_active' => true,
            ],
            [
                'title' => 'Sustainability & Innovation',
                'slug' => 'sustainability-innovation',
                'description' => 'Research focused on environmental, social, and economic sustainability.',
                'issn' => 'XXXX-XXXX',
                'impact_factor' => 4.2,
                'aims_and_scope' => 'Green technology, social responsibility, and climate change.',
                'is_active' => true,
            ],
            [
                'title' => 'Applied Engineering and Tech',
                'slug' => 'applied-eng-tech',
                'description' => 'Engineering applications in real-world scenarios.',
                'issn' => 'XXXX-XXXX',
                'impact_factor' => 3.1,
                'aims_and_scope' => 'Civil, mechanical, and electrical engineering innovations.',
                'is_active' => true,
            ],
            [
                'title' => 'Global Health Review',
                'slug' => 'global-health-review',
                'description' => 'Scholarly articles on public health and medical research.',
                'issn' => 'XXXX-XXXX',
                'impact_factor' => 4.8,
                'aims_and_scope' => 'Epidemiology, health policy, and environmental health.',
                'is_active' => true,
            ],
        ];

        $articleTitles = [
            'Recent Advances in Smart Sensor Technologies for Precision Agriculture',
            'Molecular Mechanisms of Neurodegenerative Diseases: A Comprehensive Review',
            'Sustainable Urban Development: Strategies for Carbon-Neutral Cities',
            'Impact of Artificial Intelligence on Modern Surgical Procedures',
            'Green Synthesis of Metallic Nanoparticles using Plant Extracts',
            'Evaluation of Renewable Energy Storage Systems for Grid Stability',
            'Machine Learning Models for Early Detection of Cardiovascular Anomalies',
            'Blockchain Applications in Secure Supply Chain Management',
            'Biodiversity Conservation in Fragmented Tropical Ecosystems',
            'Quantum Computing: Prospects for Cryptographic Resilience',
            'The Role of MicroRNAs in Regulating Cancer Cell Proliferation',
            'Internet of Things (IoT) Frameworks for Smart Home Energy Optimization',
            'Experimental Analysis of Composite Materials in Aerospace Engineering',
            'Socio-Economic Impacts of Digital Transformation in Developing Regions',
            'Advanced Photocatalytic Materials for Wastewater Remediation',
        ];

        $allTopics = \App\Models\Topic::all();
        foreach ($journalsData as $data) {
            $data['topic_id'] = $allTopics->random()->id;
            $journal = Journal::create($data);

            // 3. Volumes for each Journal
            for ($v = 1; $v <= 2; $v++) {
                $volume = Volume::create([
                    'journal_id' => $journal->id,
                    'volume_number' => $v,
                    'year' => 2024 + $v, // 2025, 2026
                    'is_published' => true,
                ]);

                // 4. Issues for each Volume
                for ($i = 1; $i <= 4; $i++) {
                    $issue = Issue::create([
                        'volume_id' => $volume->id,
                        'issue_number' => $i,
                        'publication_date' => Carbon::now()->subMonths(rand(1, 12)),
                        'is_published' => true,
                    ]);

                    // 5. Articles for each Issue (Adding 3-5 published articles)
                    for ($a = 1; $a <= rand(3, 5); $a++) {
                        $articleTitle = $articleTitles[array_rand($articleTitles)] . " (Part " . $a . ")";
                        $article = Article::create([
                            'journal_id' => $journal->id,
                            'issue_id' => $issue->id,
                            'title' => $articleTitle,
                            'slug' => Str::slug($articleTitle),
                            'abstract' => "This detailed research paper investigates the recent breakthroughs in scientific methodology and application, specifically looking at how modern " . strtolower($journal->slug) . " technologies affect contemporary results.",
                            'keywords' => 'Research, Innovation, Science, Global, Impact',
                            'doi' => '10.3390/' . $journal->slug . '.' . $v . '.' . $i . '.' . $a,
                            'status' => 'published',
                            'published_at' => $issue->publication_date,
                            'views_count' => rand(100, 5000),
                            'downloads_count' => rand(10, 1500),
                        ]);

                        // 6. Authors for each Article
                        $numAuthors = rand(1, 4);
                        for ($au = 0; $au < $numAuthors; $au++) {
                            Author::create([
                                'article_id' => $article->id,
                                'name' => fake()->name(),
                                'email' => fake()->email(),
                                'affiliation' => fake()->company() . ' University',
                                'is_corresponding' => ($au === 0),
                            ]);
                        }
                    }
                }
            }

            // 7. Submissions for each Journal (Various statuses)
            $statuses = ['submitted', 'under_review', 'revision', 'rejected'];
            foreach ($statuses as $status) {
                $submissionTitle = "Exploring " . $articleTitles[array_rand($articleTitles)];
                $submission = Submission::create([
                    'user_id' => $allAuthors->random()->id,
                    'journal_id' => $journal->id,
                    'title' => $submissionTitle,
                    'abstract' => "Draft submission exploring preliminary results in " . $journal->title . " ecosystem.",
                    'status' => $status,
                    'file_path' => 'submissions/sample_manuscript.pdf',
                    'created_at' => Carbon::now()->subDays(rand(1, 60)),
                ]);

                // 7.5 Create Reviews for under_review or revision submissions
                if (in_array($status, ['under_review', 'revision'])) {
                    $allReviewers = User::whereIn('role', ['reviewer', 'editor'])->get();
                    $numReviews = rand(1, 3);
                    for ($r = 0; $r < $numReviews; $r++) {
                        $isCompleted = (rand(1, 10) > 4); // 60% chance of completion
                        \App\Models\Review::create([
                            'submission_id' => $submission->id,
                            'reviewer_id' => $allReviewers->random()->id,
                            'comments' => $isCompleted ? fake()->paragraphs(2, true) : null,
                            'recommendation' => $isCompleted ? fake()->randomElement(['accept', 'minor_revision', 'major_revision', 'reject']) : null,
                            'completed_at' => $isCompleted ? Carbon::now()->subDays(rand(1, 15)) : null,
                        ]);
                    }
                }
            }
        }

        // 8. Ecosystem Data
        foreach ($allAuthors as $user) {
            SciProfile::create([
                'user_id' => $user->id,
                'bio' => fake()->paragraph(),
                'affiliations' => fake()->company() . ", Researcher",
                'orcid' => "0000-000" . rand(1, 9) . "-" . rand(1000, 9999) . "-" . rand(1000, 9999),
                'google_scholar' => "https://scholar.google.com/citations?user=" . Str::random(12)
            ]);

            $preprintTitle = "Preliminary Findings: " . $articleTitles[array_rand($articleTitles)];
            Preprint::create([
                'user_id' => $user->id,
                'title' => $preprintTitle,
                'slug' => Str::slug($preprintTitle),
                'abstract' => fake()->paragraph(),
                'version' => 1,
                'status' => 'published',
                'created_at' => Carbon::now()->subMonths(rand(1, 6))
            ]);
        }

        // 9. Sciforum Events
        $eventTypes = ['conference', 'workshop', 'webinar'];
        foreach ($eventTypes as $type) {
            $eventTitle = "Global " . Str::title($type) . " on " . Str::replace('Recent Advances in ', '', $articleTitles[array_rand($articleTitles)]);
            SciforumEvent::create([
                'title' => $eventTitle,
                'slug' => Str::slug($eventTitle),
                'description' => fake()->paragraph(),
                'start_date' => Carbon::now()->addMonths(rand(1, 6)),
                'end_date' => Carbon::now()->addMonths(7),
                'location' => fake()->city() . ", " . fake()->country(),
                'type' => $type
            ]);
        }

        // 10. Encyclopedia Entries
        for ($e = 0; $e < 5; $e++) {
            $entryTitle = Str::replace('Recent Advances in ', '', $articleTitles[array_rand($articleTitles)]);
            EncyclopediaEntry::create([
                'user_id' => $allAuthors->random()->id,
                'title' => $entryTitle,
                'slug' => Str::slug($entryTitle),
                'content' => fake()->paragraphs(3, true),
                'category' => "Science & Technology",
                'status' => 'published'
            ]);
        }

        // 11. Partners & Affiliations
        $partners = [
            ['name' => 'WHO', 'url' => 'https://www.who.int'],
            ['name' => 'EPA', 'url' => 'https://www.epa.gov'],
            ['name' => 'SfN', 'url' => 'https://www.sfn.org'],
            ['name' => 'RSC', 'url' => 'https://www.rsc.org'],
            ['name' => 'APS', 'url' => 'https://www.aps.org'],
        ];

        foreach ($partners as $index => $partner) {
            \App\Models\Partner::create([
                'name' => $partner['name'],
                'url' => $partner['url'],
                'logo_path' => null,
                'is_active' => true,
                'sort_order' => $index,
            ]);
        }
    }
}
