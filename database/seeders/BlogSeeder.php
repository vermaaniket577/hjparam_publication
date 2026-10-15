<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::first();
        $adminId = $admin ? $admin->id : 1;

        $posts = [
            [
                'title' => 'Advancing Open Access: Democratizing Global Scientific Research',
                'slug' => 'advancing-open-access-democratizing-global-scientific-research',
                'category' => 'Open Access Insights',
                'summary' => 'How open-access publication models break down financial barriers, accelerate cross-border collaboration, and empower researchers worldwide.',
                'tags' => 'open access, research equity, peer review, scientific publishing',
                'featured_image' => 'https://images.unsplash.com/photo-1532012164546-f432f2e3edd4?auto=format&fit=crop&w=1200&q=80',
                'author_id' => $adminId,
                'author_name' => 'Dr. Eleanor Vance',
                'views_count' => 142,
                'is_published' => true,
                'published_at' => now()->subDays(2),
                'content' => '
<h2>The Evolution of Scientific Communication</h2>
<p>For centuries, the dissemination of scholarly findings was tethered to print journals and costly institutional subscriptions. In the 21st century, the digital revolution and open-access movement have transformed this paradigm, positioning scientific knowledge as a global public good.</p>

<p>At HJPARAM Publication, our open-access mandate ensures that high-impact scientific discoveries reach researchers, practitioners, and policymakers without subscription paywalls.</p>

<h2>Key Advantages for Authors and Institutions</h2>
<ul>
  <li><strong>Accelerated Citation Velocity:</strong> Openly accessible articles receive significantly higher visibility and citations compared to restricted-access publications.</li>
  <li><strong>Global South Representation:</strong> Removes disproportionate fee barriers for researchers in emerging scientific economies.</li>
  <li><strong>Interdisciplinary Synergy:</strong> Enables seamless cross-pollination between biological sciences, engineering, and environmental research.</li>
</ul>

<blockquote>"True progress in human knowledge requires that foundational discoveries be accessible not just to well-funded universities, but to every curious mind across the globe."</blockquote>

<h2>Looking Ahead: The Next Decade in Scholarly Publishing</h2>
<p>As metadata standards like Crossref, ORCID, and persistent digital identifiers become universally integrated, the focus turns toward transparent peer review and machine-readable research data. We invite our scholarly community to join us in championing rigorous, inclusive, and open research.</p>
',
            ],
            [
                'title' => 'AI in Peer Review: Balancing Automated Screening with Human Scholarly Rigor',
                'slug' => 'ai-in-peer-review-balancing-automated-screening-with-human-scholarly-rigor',
                'category' => 'Scientific Innovation',
                'summary' => 'Examining how modern editorial workflows leverage machine learning for manuscript integrity checks while preserving the indispensable human judgment of domain experts.',
                'tags' => 'artificial intelligence, peer review, publishing ethics, editorial workflows',
                'featured_image' => 'https://images.unsplash.com/photo-1507413245164-6160d8298b31?auto=format&fit=crop&w=1200&q=80',
                'author_id' => $adminId,
                'author_name' => 'Prof. Marcus Brody',
                'views_count' => 98,
                'is_published' => true,
                'published_at' => now()->subDays(5),
                'content' => '
<h2>The Rise of Algorithmic Editorial Assistance</h2>
<p>Artificial Intelligence tools have rapidly advanced from rudimentary text check utilities to sophisticated systems capable of parsing statistical methodologies, flagging image manipulations, and identifying potential conflicts of interest before manuscripts reach peer review.</p>

<h2>Where Automation Excels</h2>
<p>Automated screening shines in technical pre-flight evaluation:</p>
<ul>
  <li>Instant verification of formatting, reference consistency, and citation accuracy.</li>
  <li>Cross-referencing datasets against public repositories to detect duplicate publications.</li>
  <li>Matching reviewer expertise with specialized manuscript keywords using NLP semantic networks.</li>
</ul>

<h2>The Indispensable Value of Expert Peer Review</h2>
<p>Despite algorithmic sophistication, assessing novel hypothesis formulation, methodological soundess, and contextual nuance remains uniquely human. Automated tools serve as force multipliers for editorial boards, never replacements for domain-expert scrutiny.</p>

<blockquote>"Machine intelligence can assist in catching anomalies, but only critical human scholarship can evaluate truth, ingenuity, and ethical responsibility in research."</blockquote>
',
            ],
            [
                'title' => 'Global Sustainability Initiative: Translating Scholarly Research Into Environmental Action',
                'slug' => 'global-sustainability-initiative-translating-scholarly-research-into-action',
                'category' => 'Global Initiatives',
                'summary' => 'Our commitment to the United Nations Sustainable Development Goals through dedicated research tracks, carbon-conscious publishing, and academic outreach.',
                'tags' => 'sustainability, environmental science, green publishing, SDGs',
                'featured_image' => 'https://images.unsplash.com/photo-1497435334941-8c899ee9e8e9?auto=format&fit=crop&w=1200&q=80',
                'author_id' => $adminId,
                'author_name' => 'HJPARAM Editorial Council',
                'views_count' => 215,
                'is_published' => true,
                'published_at' => now()->subDays(9),
                'content' => '
<h2>Aligning Academic Publishing with the 2030 Agenda</h2>
<p>The global climate crisis and environmental degradation necessitate proactive contributions from the academic publishing ecosystem. The HJPARAM Global Sustainability Initiative represents our comprehensive framework to support multidisciplinary research tackling ecological challenges.</p>

<h2>Core Pillars of the Initiative</h2>
<ul>
  <li><strong>Targeted Special Issues:</strong> Dedicated journal issues focusing on renewable energy storage, circular economy frameworks, and urban resilience.</li>
  <li><strong>Digital-First Archiving:</strong> Drastically lowering the environmental footprint of academic distribution through zero-waste, energy-efficient cloud hosting and digital preservation networks.</li>
  <li><strong>Community Engagement:</strong> Connecting research authors with regional environmental councils and non-governmental organizations to translate data into local impact.</li>
</ul>

<p>We welcome submissions from researchers exploring innovative solutions in ecological preservation, clean technologies, and resource optimization.</p>
',
            ],
        ];

        foreach ($posts as $postData) {
            Blog::updateOrCreate(
                ['slug' => $postData['slug']],
                $postData
            );
        }
    }
}
