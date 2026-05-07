<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Topic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NavigationMenuSeeder extends Seeder
{
    public function run(): void
    {
        $topics = [
            'Engineering',
            'Medical & Health Sciences',
            'Business & Management',
            'Education',
            'Social Sciences',
            'Technology',
            'Environmental Science',
        ];

        foreach ($topics as $index => $name) {
            Topic::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'active' => true,
                    'sort_order' => $index + 1,
                ]
            );
        }

        $pages = [
            'info' => [
                ['Open Access Policy', 'open-access-policy'],
                ['Peer Review Process', 'peer-review-process'],
                ['Publication Process', 'process'],
                ['Plagiarism Check', 'plagiarism-check'],
                ['Reviewer Guidelines', 'reviewers-guidelines'],
                ['Volunteer Reviewers', 'volunteer-reviewers'],
                ['News', 'news'],
            ],
            'about' => [
                ['About HJPARAM', 'about'],
                ['Editorial Board', 'editorial-board'],
                ['Contact', 'contact'],
            ],
            'author' => [
                ['Instructions for Authors', 'instructions-for-authors'],
                ['Submission Checklist', 'submission-checklist'],
                ['Article Processing Charges', 'apc'],
            ],
        ];

        foreach ($pages as $category => $items) {
            foreach ($items as $index => [$title, $slug]) {
                Page::updateOrCreate(
                    [
                        'category' => $category,
                        'slug' => $slug,
                    ],
                    [
                        'title' => $title,
                        'content' => '<p>Information for this section is maintained by the HJPARAM editorial office.</p>',
                        'active' => true,
                        'sort_order' => $index + 1,
                    ]
                );
            }
        }
    }
}
