<?php

namespace App\Http\Controllers\Ecosystem;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ScilitController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::query();

        if ($request->has('q')) {
            $search = $request->get('q');
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('abstract', 'like', "%{$search}%");
        }

        $articles = $query->latest()->paginate(15);
        return view('ecosystem.scilit.index', compact('articles'));
    }
}
