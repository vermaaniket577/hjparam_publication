<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Page;

class PagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            // Journals
            [
                'title' => 'Journals',
                'slug' => 'journals',
                'category' => 'info',
                'content' => '<h1>Our Journals</h1><p>Explore our diverse collection of peer-reviewed journals covering a wide range of academic disciplines. We are committed to publishing high-quality research that contributes to the advancement of knowledge. <a href="/journals">Browse all journals</a>.</p>',
                'meta_title' => 'Academic Journals | HJParam Publication',
                'meta_description' => 'Browse our collection of peer-reviewed academic journals across various disciplines.',
            ],
            // Call for Papers
            [
                'title' => 'Call for Papers',
                'slug' => 'call-for-papers',
                'category' => 'author', // Mapped to author category
                'content' => '<h1>Call for Papers</h1><p>We invite researchers and scholars to submit their original work for publication in our upcoming issues. Review our submission deadlines and specific journal requirements.</p><h2>Current Opportunities</h2><ul><li>Special Issue on Sustainable Development</li><li>Advances in Medical Technology</li></ul><p><a href="/author/submit">Submit your manuscript today</a>.</p>',
                'meta_title' => 'Call for Papers | HJParam Publication',
                'meta_description' => 'Open calls for research papers. Submit your manuscript to our upcoming journal issues.',
            ],
            // Editorial Board
            [
                'title' => 'Editorial Board',
                'slug' => 'editorial-board',
                'category' => 'about',
                'content' => '<h1>Editorial Board</h1><p>Our editorial board consists of distinguished scholars and experts from around the globe who ensure the quality and integrity of our publications.</p><p>List of editors coming soon...</p>',
                'meta_title' => 'Editorial Board | HJParam Publication',
                'meta_description' => 'Meet the distinguished scholars and experts on our editorial board.',
            ],
            // Publication Ethics
            [
                'title' => 'Publication Ethics',
                'slug' => 'ethics',
                'category' => 'info',
                'content' => '<h1>Publication Ethics and Malpractice Statement</h1><p>HJParam Publication is committed to upholding the highest standards of publication ethics. We adhere to the guidelines set forth by the Committee on Publication Ethics (COPE).</p><h2> duties of Editors</h2><p>...</p><h2>Duties of Reviewers</h2><p>...</p><h2>Duties of Authors</h2><p>...</p>',
                'meta_title' => 'Publication Ethics | HJParam Publication',
                'meta_description' => 'Our commitment to the highest standards of publication ethics and malpractice statements.',
            ],
            // Submission Guidelines
            [
                'title' => 'Submission Guidelines',
                'slug' => 'guidelines',
                'category' => 'author',
                'content' => '<h1>Submission Guidelines</h1><p>Please read these guidelines carefully before submitting your manuscript. Adherence to these instructions will ensure a smooth review process.</p><h2>Formatting</h2><p>...</p><h2>Structure</h2><p>...</p>',
                'meta_title' => 'Author Submission Guidelines | HJParam Publication',
                'meta_description' => 'Comprehensive guidelines for authors submitting manuscripts for publication.',
            ],
            // Publication Fees
            [
                'title' => 'Publication Fees',
                'slug' => 'apc',
                'category' => 'author',
                'content' => '<h1>Article Processing Charges (APC)</h1><p>As an Open Access publisher, we charge an Article Processing Charge (APC) to cover the costs of publication. This ensures that your work is freely available to everyone immediately upon publication.</p><p>Current APC rates...</p>',
                'meta_title' => 'Article Processing Charges (APC) | HJParam Publication',
                'meta_description' => 'Information about our Article Processing Charges and open access funding.',
            ],
            // Review Process
            [
                'title' => 'Peer Review Process',
                'slug' => 'review-process',
                'category' => 'info',
                'content' => '<h1>Peer Review Process</h1><p>All manuscripts submitted to our journals undergo a rigorous double-blind peer review process to ensure scientific quality and integrity.</p><ol><li>Initial Screening</li><li>Peer Review</li><li>Editorial Decision</li></ol>',
                'meta_title' => 'Peer Review Process | HJParam Publication',
                'meta_description' => 'Understanding our rigorous double-blind peer review process for quality assurance.',
            ],
            // Copyright & Licensing
            [
                'title' => 'Copyright & Licensing',
                'slug' => 'copyright',
                'category' => 'info',
                'content' => '<h1>Copyright & Licensing</h1><p>Articles published by HJParam Publication are licensed under the Creative Commons Attribution 4.0 International License (CC BY 4.0). Authors retain the copyright of their work.</p>',
                'meta_title' => 'Copyright & Licensing | HJParam Publication',
                'meta_description' => 'Details on copyright retention and Creative Commons licensing for our publications.',
            ],
            // Plagiarism Policy
            [
                'title' => 'Plagiarism Policy',
                'slug' => 'plagiarism',
                'category' => 'info',
                'content' => '<h1>Plagiarism Policy</h1><p>We have a zero-tolerance policy towards plagiarism. All submissions are checked using industry-standard plagiarism detection software prior to review.</p>',
                'meta_title' => 'Plagiarism Policy | HJParam Publication',
                'meta_description' => '我们的 zero-tolerance policy on plagiarism and how we ensure content originality.',
            ],
            // Open Access Policy
            [
                'title' => 'Open Access Policy',
                'slug' => 'open-access-policy',
                'category' => 'info',
                'content' => '<h1>Open Access Policy</h1><p>We are fully committed to Open Access publishing. All articles are freely available to read, download, and share immediately upon publication.</p>',
                'meta_title' => 'Open Access Policy | HJParam Publication',
                'meta_description' => 'Our commitment to making research freely available to the global community.',
            ],
            // Privacy Policy
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy',
                'category' => 'info',
                'content' => '<h1>Privacy Policy</h1><p>Your privacy is important to us. This policy outlines how we collect, use, and protect your personal information.</p>',
                'meta_title' => 'Privacy Policy | HJParam Publication',
                'meta_description' => 'How we collect, use, and protect your personal data.',
            ],
            // Terms & Conditions
            [
                'title' => 'Terms & Conditions',
                'slug' => 'terms',
                'category' => 'info',
                'content' => '<h1>Terms and Conditions</h1><p>By using this website, you agree to comply with and be bound by the following terms and conditions of use.</p>',
                'meta_title' => 'Terms & Conditions | HJParam Publication',
                'meta_description' => 'Terms and conditions for using the HJParam Publication website and services.',
            ],
            // Disclaimer
            [
                'title' => 'Disclaimer',
                'slug' => 'disclaimer',
                'category' => 'info',
                'content' => '<h1>Disclaimer</h1><p>The information contained in this website is for general information purposes only. While we endeavor to keep the information up to date, we make no representations or warranties...</p>',
                'meta_title' => 'Disclaimer | HJParam Publication',
                'meta_description' => 'Legal disclaimer regarding the information provided on our website.',
            ],
            // Retraction Policy
            [
                'title' => 'Retraction Policy',
                'slug' => 'retraction',
                'category' => 'info',
                'content' => '<h1>Retraction Policy</h1><p>We follow the COPE guidelines for retracting articles. Retractions are considered in cases of evidence of unreliable data, plagiarism, or unethical research.</p>',
                'meta_title' => 'Retraction Policy | HJParam Publication',
                'meta_description' => 'Guidelines and procedures for article retraction in accordance with COPE standards.',
            ],
            // Conflict of Interest
            [
                'title' => 'Conflict of Interest',
                'slug' => 'conflict-interest',
                'category' => 'info',
                'content' => '<h1>Conflict of Interest</h1><p>Authors, reviewers, and editors are required to declare any potential conflicts of interest that could influence the review or publication process.</p>',
                'meta_title' => 'Conflict of Interest Policy | HJParam Publication',
                'meta_description' => 'Policy on declaring and managing conflicts of interest in the publication process.',
            ],
            // About Us (if not already handled by a specific controller)
            [
                'title' => 'About Us',
                'slug' => 'about',
                'category' => 'about',
                'content' => '<h1>About HJParam Publication</h1><p>HJParam Publication is a leading platform for scientific research...</p>',
                'meta_title' => 'About Us | HJParam Publication',
                'meta_description' => 'Learn more about our mission, vision, and commitment to scientific advancement.',
            ]
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(
                ['slug' => $page['slug'], 'category' => $page['category']],
                $page
            );
        }
    }
}
