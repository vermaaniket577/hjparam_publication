<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use Illuminate\Support\Str;

class PageDataSeeder extends Seeder
{
    public function run(): void
    {
        $infoPages = [
            'Open Access Policy',
            'Peer Review Process',
        ];

        foreach ($infoPages as $index => $title) {
            Page::updateOrCreate(
                ['slug' => Str::slug($title), 'category' => 'info'],
                [
                    'title' => $title,
                    'content' => 'Static View Fallback', // Controller will prefer static view if exists
                    'active' => true,
                    'sort_order' => $index + 1
                ]
            );
        }

        // Also ensure some author pages exist
        $authorPages = [
            'Author Guidelines',
        ];

        foreach ($authorPages as $index => $title) {
            Page::updateOrCreate(
                ['slug' => Str::slug($title), 'category' => 'author'],
                [
                    'title' => $title,
                    'content' => 'Guidelines for authors...',
                    'active' => true,
                    'sort_order' => $index + 1
                ]
            );
        }

        // Initiatives Pages
        $initiativesPages = [
            'Global Sustainability Initiative',
        ];

        foreach ($initiativesPages as $index => $title) {
            Page::updateOrCreate(
                ['slug' => Str::slug($title), 'category' => 'initiatives'],
                [
                    'title' => $title,
                    'content' => 'Static View Fallback',
                    'active' => true,
                    'sort_order' => $index + 1
                ]
            );
        }
    }
}
