<?php

namespace App\Http\Controllers\Ecosystem;

use App\Http\Controllers\Controller;
use App\Models\SciforumEvent;
use Illuminate\Http\Request;

class SciforumController extends Controller
{
    public function index()
    {
        $events = SciforumEvent::latest()->paginate(10);
        return view('ecosystem.sciforum.index', compact('events'));
    }

    public function show($slug)
    {
        $event = SciforumEvent::where('slug', $slug)->firstOrFail();
        return view('ecosystem.sciforum.show', compact('event'));
    }
}
