<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method']);

        // Handle checkboxes (if not present, they are false/0)
        $checkboxes = ['allow_registration', 'allow_submissions', 'maintenance_mode', 'enable_rss'];
        foreach ($checkboxes as $checkbox) {
            if (!isset($data[$checkbox])) {
                $data[$checkbox] = '0';
            } else {
                $data[$checkbox] = '1';
            }
        }

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        // Clear settings cache
        \Illuminate\Support\Facades\Cache::forget('site_settings');
        \Illuminate\Support\Facades\Cache::forget('mail_settings');

        return back()->with('success', 'Settings updated successfully.');
    }
}
