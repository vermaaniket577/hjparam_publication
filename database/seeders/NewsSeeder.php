<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\News::create([
            'title' => 'HJParam Launches New AI Authenticity Tool',
            'slug' => 'news-ai-tool',
            'category' => 'Technology',
            'description' => 'We are proud to announce the release of our advanced AI detection algorithm, helping researchers verify content authenticity with 99% accuracy.',
            'content' => 'At HJPARAM, we are committed to maintaining the highest standards of academic integrity. To meet the challenges of the modern research landscape, we have officially launched our proprietary AI Authenticity Tool...',
            'published_at' => now(),
            'is_active' => true,
        ]);

        \App\Models\News::create([
            'title' => 'Global Research Partnership Announced',
            'slug' => 'news-partnership',
            'category' => 'Partnership',
            'description' => 'Leading universities across Europe and Asia have joined the HJParam Open Science initiative to promote transparent publishing.',
            'content' => 'We are thrilled to announce a strategic partnership with a consortium of fifteen Tier-1 research universities across Europe, Asia, and North America...',
            'published_at' => now(),
            'is_active' => true,
        ]);

        \App\Models\News::create([
            'title' => 'Call for Papers: Sustainable Energy 2026',
            'slug' => 'news-sustainable-energy',
            'category' => 'Call for Papers',
            'description' => 'Submit your groundbreaking research on renewable energy technologies. Special issue focused on solar and wind innovations.',
            'content' => 'Our upcoming special issue on Sustainable Energy aims to highlight breakthroughs that address the global climate crisis...',
            'published_at' => now(),
            'is_active' => true,
        ]);
    }
}
