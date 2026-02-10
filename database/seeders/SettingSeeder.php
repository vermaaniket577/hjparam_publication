<?php

namespace database\seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'site_name' => 'HJPARAM Publication',
            'contact_email' => 'info@hjparam.com',
            'footer_text' => '&copy; 1996-2024 HJPARAM Publication. All rights reserved.',

            // Contact Information
            'editorial_email' => 'editorial@hjparam.com',
            'editorial_response_time' => 'Response time: Within 24-48 hours',

            'support_email' => 'support@hjparam.com',
            'support_hours' => 'Available Mon-Fri, 9AM-5PM GMT',

            'office_address' => "HJPARAM Publication Services\n124 Academic Way, Tech District\nInnovation Park, UK 50493",

            // Social Media
            'facebook_url' => 'https://facebook.com/hjparam',
            'twitter_url' => 'https://twitter.com/hjparam',
            'linkedin_url' => 'https://linkedin.com/company/hjparam',
            'youtube_url' => 'https://youtube.com/@hjparam',
            'instagram_url' => 'https://instagram.com/hjparam',

            // Global Contacts
            'contact_phone' => '+41 61 683 77 34',
            'whatsapp_number' => '+41 61 683 77 35',

            // Toggles
            'allow_registration' => '1',
            'allow_submissions' => '1',
            'maintenance_mode' => '0',
        ];

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }
    }
}
