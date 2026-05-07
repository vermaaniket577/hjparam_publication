<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure Topics (Categories) exist
        $topics = [
            ['name' => 'Engineering', 'slug' => 'engineering'],
            ['name' => 'Medical & Health', 'slug' => 'medical-health'],
            ['name' => 'Business & Management', 'slug' => 'business-management'],
            ['name' => 'Education', 'slug' => 'education'],
            ['name' => 'Social Sciences', 'slug' => 'social-sciences'],
            ['name' => 'Technology', 'slug' => 'technology'],
            ['name' => 'Environmental Science', 'slug' => 'environmental-science'],
        ];

        foreach ($topics as $topic) {
            \App\Models\Topic::updateOrCreate(['slug' => $topic['slug']], $topic);
        }

        // 2. Ensure Countries exist
        $countries = [
            ['name' => 'USA', 'slug' => 'usa', 'code' => 'US'],
            ['name' => 'United Kingdom', 'slug' => 'uk', 'code' => 'GB'],
            ['name' => 'Germany', 'slug' => 'germany', 'code' => 'DE'],
            ['name' => 'India', 'slug' => 'india', 'code' => 'IN'],
            ['name' => 'Japan', 'slug' => 'japan', 'code' => 'JP'],
            ['name' => 'Australia', 'slug' => 'australia', 'code' => 'AU'],
            ['name' => 'Canada', 'slug' => 'canada', 'code' => 'CA'],
            ['name' => 'Singapore', 'slug' => 'singapore', 'code' => 'SG'],
        ];

        foreach ($countries as $country) {
            \App\Models\Country::updateOrCreate(['slug' => $country['slug']], $country);
        }

        // 3. Create sample Conferences
        $topicModels = \App\Models\Topic::all();
        $countryModels = \App\Models\Country::all();
        $user = \App\Models\User::first() ?: \App\Models\User::factory()->create(['role' => 'admin']);

        $conferences = [
            [
                'title' => 'International Conference on Sustainable Engineering 2026',
                'slug' => 'international-conference-on-sustainable-engineering-2026',
                'description' => 'The International Conference on Sustainable Engineering (ICSE) 2026 aims to bring together leading academic scientists, researchers, and research scholars to exchange and share their experiences and research results on all aspects of Sustainable Engineering.',
                'start_date' => now()->addMonths(3),
                'end_date' => now()->addMonths(3)->addDays(3),
                'venue' => 'Grand Hyatt',
                'city' => 'New York',
                'organizer_name' => 'Global Engineering Research Society',
                'external_link' => 'https://example.com/icse2026',
                'type' => 'offline',
                'status' => 'approved',
                'is_featured' => true,
                'early_bird_deadline' => now()->addMonths(1),
                'invitation_letter_support' => true,
            ],
            [
                'title' => 'Global Healthcare & Medical Innovation Summit',
                'slug' => 'global-healthcare-medical-innovation-summit',
                'description' => 'A premier event for healthcare professionals to discuss the latest innovations in medical technology and patient care.',
                'start_date' => now()->addMonths(4),
                'end_date' => now()->addMonths(4)->addDays(2),
                'venue' => 'London Convention Centre',
                'city' => 'London',
                'organizer_name' => 'Health Innovation Global Network',
                'external_link' => 'https://example.com/healthcare-summit',
                'type' => 'hybrid',
                'status' => 'approved',
                'is_featured' => true,
                'early_bird_deadline' => now()->addMonths(2),
                'invitation_letter_support' => true,
            ],
            [
                'title' => 'Digital Transformation in Business 2026',
                'slug' => 'digital-transformation-in-business-2026',
                'description' => 'Exploring how digital technologies are reshaping the modern business landscape and strategy.',
                'start_date' => now()->addMonths(5),
                'end_date' => now()->addMonths(5)->addDays(3),
                'venue' => 'Marina Bay Sands',
                'city' => 'Singapore',
                'organizer_name' => 'Digital Business Institute',
                'external_link' => 'https://example.com/dtb2026',
                'type' => 'offline',
                'status' => 'approved',
                'is_featured' => true,
                'early_bird_deadline' => now()->addMonths(3),
                'invitation_letter_support' => false,
            ],
            [
                'title' => 'Future of Education Technology Virtual Conference',
                'slug' => 'future-of-education-technology-virtual-conference',
                'description' => 'A fully online experience focusing on the next generation of educational tools and pedagogical approaches.',
                'start_date' => now()->addMonths(2),
                'end_date' => now()->addMonths(2)->addDays(1),
                'venue' => 'Online',
                'city' => 'Virtual',
                'organizer_name' => 'EdTech Global Alliance',
                'external_link' => 'https://example.com/edtech-virtual',
                'type' => 'online',
                'status' => 'approved',
                'is_featured' => false,
                'early_bird_deadline' => now()->addMonth(),
                'invitation_letter_support' => false,
            ],
            [
                'title' => 'Interdisciplinary Social Sciences Forum 2026',
                'slug' => 'interdisciplinary-social-sciences-forum-2026',
                'description' => 'Merging various social science perspectives to address modern global challenges.',
                'start_date' => now()->addMonths(6),
                'end_date' => now()->addMonths(6)->addDays(4),
                'venue' => 'Berlin Congress Center',
                'city' => 'Berlin',
                'organizer_name' => 'Social Research Institute',
                'external_link' => 'https://example.com/social-forum',
                'type' => 'offline',
                'status' => 'approved',
                'is_featured' => false,
                'early_bird_deadline' => now()->addMonths(4),
                'invitation_letter_support' => true,
            ],
        ];

        foreach ($conferences as $index => $conf) {
            $conf['country_id'] = $countryModels->random()->id;
            $conf['category_id'] = $topicModels->random()->id;
            $conf['organizer_id'] = $user->id;

            \App\Models\Conference::updateOrCreate(
                ['slug' => $conf['slug']],
                $conf
            );
        }
    }
}
