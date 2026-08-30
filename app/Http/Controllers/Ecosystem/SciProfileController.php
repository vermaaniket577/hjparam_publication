<?php

namespace App\Http\Controllers\Ecosystem;

use App\Http\Controllers\Controller;
use App\Models\SciProfile;
use App\Models\User;
use Illuminate\Http\Request;

class SciProfileController extends Controller
{
    public function index()
    {
        $profiles = SciProfile::with('user')->paginate(12);
        return view('ecosystem.sciprofiles.index', compact('profiles'));
    }

    public function show($identifier)
    {
        // Allow search by profile ID or ORCID
        $profile = SciProfile::with(['user', 'articles.journal', 'preprints', 'encyclopediaEntries'])
            ->where('id', $identifier)
            ->orWhere('orcid', $identifier)
            ->firstOrFail();

        return view('ecosystem.sciprofiles.show', compact('profile'));
    }
}
