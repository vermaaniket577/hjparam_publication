<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\SciProfile;
use App\Models\SciforumEvent;
use App\Models\Preprint;
use App\Models\EncyclopediaEntry;
use App\Models\Journal;
use App\Models\Article;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EcosystemSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create a few users
        $authors = User::factory()->count(5)->create(['role' => 'author']);

        foreach ($authors as $user) {
            // 2. Create SciProfile for each user
            SciProfile::create([
                'user_id' => $user->id,
                'bio' => "Academic researcher specializing in advanced scientific studies and global impact research. Dedicated to open access and knowledge dissemination.",
                'affiliations' => "Department of Science, International University",
                'orcid' => "0000-0002-" . rand(1000, 9999) . "-" . rand(1000, 9999),
                'google_scholar' => "https://scholar.google.com/citations?user=" . Str::random(12)
            ]);

            // 3. Create a Preprints for some users
            Preprint::create([
                'user_id' => $user->id,
                'title' => "Advanced Methodologies in " . Str::title(Str::random(10)) . " Research v1",
                'slug' => Str::slug("advanced-methodologies-" . Str::random(5)),
                'abstract' => "This preprint explores the latest trends and methodologies in scientific research, focusing on modularity and open access principles.",
                'version' => 1,
                'status' => 'published',
                'created_at' => Carbon::now()->subDays(rand(1, 10))
            ]);
        }

        // 4. Create Sciforum Events
        SciforumEvent::create([
            'title' => "International Conference on Open Access 2026",
            'slug' => "int-conf-open-access-2026",
            'description' => "Join global leaders in discussions about the future of open access publishing and academic reach.",
            'start_date' => Carbon::now()->addMonths(2),
            'end_date' => Carbon::now()->addMonths(2)->addDays(3),
            'location' => "Geneva, Switzerland",
            'type' => 'conference'
        ]);

        SciforumEvent::create([
            'title' => "Workshop: Peer Review Best Practices",
            'slug' => "workshop-peer-review-best-practices",
            'description' => "An intensive workshop for editors and reviewers on maintaining academic integrity.",
            'start_date' => Carbon::now()->addMonth(),
            'end_date' => Carbon::now()->addMonth()->addHours(4),
            'location' => "Online",
            'type' => 'workshop'
        ]);

        // 5. Create Encyclopedia Entries
        EncyclopediaEntry::create([
            'user_id' => $authors[0]->id,
            'title' => "Open Access Publishing",
            'slug' => "open-access-publishing",
            'content' => "Open Access is a set of principles and a range of practices through which research outputs are distributed online, free of cost or other access barriers.",
            'category' => "Academic Publishing",
            'status' => 'published'
        ]);

        // 6. Ensure some Articles exist (linking to Journals)
        $journal = Journal::first() ?? Journal::create([
            'title' => 'International Journal of Research',
            'slug' => 'int-j-research',
            'issn' => '1234-5678'
        ]);

        Article::create([
            'journal_id' => $journal->id,
            'user_id' => $authors[0]->id,
            'title' => "The Impact of Modular Systems in Web Platforms",
            'slug' => Str::slug("impact-of-modular-systems"),
            'abstract' => "This article analyzes how modular architectures improve scalability and maintainability in large-scale academic platforms.",
            'status' => 'published'
        ]);
    }
}
