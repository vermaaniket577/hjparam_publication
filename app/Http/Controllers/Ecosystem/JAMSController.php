<?php

namespace App\Http\Controllers\Ecosystem;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JAMSController extends Controller
{
    public function index()
    {
        return view('ecosystem.jams.index');
    }

    public function submit()
    {
        return view('ecosystem.jams.submit');
    }

    public function dashboard()
    {
        $user = Auth::user();
        $submissions = $user->submissions()->latest()->get();
        return view('ecosystem.jams.dashboard', compact('submissions'));
    }
}
