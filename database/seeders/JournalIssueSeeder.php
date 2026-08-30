<?php

namespace Database\Seeders;

use App\Models\Issue;
use App\Models\Journal;
use App\Models\Volume;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JournalIssueSeeder extends Seeder
{
    public function run(): void
    {
        $journals = [
            'International Journal of Advanced Research' => [
                'issn' => '2349-5677',
                'description' => 'Peer-reviewed research across science, technology, education, and applied studies.',
                'issues' => [
                    ['volume' => '1', 'year' => 2025, 'issue' => '1', 'title' => 'General Research Articles'],
                    ['volume' => '1', 'year' => 2025, 'issue' => '2', 'title' => 'Interdisciplinary Studies'],
                    ['volume' => '2', 'year' => 2026, 'issue' => '1', 'title' => 'Current Research Trends'],
                ],
            ],
            'Journal of Medical Sciences and Public Health' => [
                'issn' => '2456-1203',
                'description' => 'Clinical research, public health studies, medical reviews, and case reports.',
                'issues' => [
                    ['volume' => '1', 'year' => 2025, 'issue' => '1', 'title' => 'Clinical Studies'],
                    ['volume' => '2', 'year' => 2026, 'issue' => '1', 'title' => 'Public Health Research'],
                ],
            ],
            'Applied Engineering and Technology Review' => [
                'issn' => '2583-4421',
                'description' => 'Engineering applications, computing, innovation, and emerging technologies.',
                'issues' => [
                    ['volume' => '1', 'year' => 2025, 'issue' => '1', 'title' => 'Engineering Innovations'],
                    ['volume' => '2', 'year' => 2026, 'issue' => '1', 'title' => 'Computing and Automation'],
                ],
            ],
        ];

        foreach ($journals as $title => $data) {
            $journal = Journal::updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'title' => $title,
                    'description' => $data['description'],
                    'issn' => $data['issn'],
                    'impact_factor' => null,
                    'aims_and_scope' => $data['description'],
                    'is_active' => true,
                ]
            );

            foreach ($data['issues'] as $issueData) {
                $volume = Volume::updateOrCreate(
                    [
                        'journal_id' => $journal->id,
                        'volume_number' => $issueData['volume'],
                    ],
                    [
                        'year' => $issueData['year'],
                        'is_published' => true,
                    ]
                );

                Issue::updateOrCreate(
                    [
                        'volume_id' => $volume->id,
                        'issue_number' => $issueData['issue'],
                    ],
                    [
                        'special_issue_title' => $issueData['title'],
                        'publication_date' => now(),
                        'is_published' => true,
                    ]
                );
            }
        }
    }
}
