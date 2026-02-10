<?php

namespace App\Http\Controllers\Ecosystem;

use App\Http\Controllers\Controller;
use App\Models\EncyclopediaEntry;
use Illuminate\Http\Request;

class EncyclopediaController extends Controller
{
    public function index()
    {
        $entries = EncyclopediaEntry::where('status', 'published')->latest()->paginate(15);
        return view('ecosystem.encyclopedia.index', compact('entries'));
    }

    public function show($slug)
    {
        $entry = EncyclopediaEntry::where('slug', $slug)->firstOrFail();
        return view('ecosystem.encyclopedia.show', compact('entry'));
    }
}
