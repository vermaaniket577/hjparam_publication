<?php

namespace App\Http\Controllers\Ecosystem;

use App\Http\Controllers\Controller;
use App\Models\Preprint;
use Illuminate\Http\Request;

class PreprintController extends Controller
{
    public function index()
    {
        $preprints = Preprint::where('status', 'published')->latest()->paginate(10);
        return view('ecosystem.preprints.index', compact('preprints'));
    }

    public function show($slug)
    {
        $preprint = Preprint::where('slug', $slug)->firstOrFail();
        return view('ecosystem.preprints.show', compact('preprint'));
    }
}
