<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubmissionController extends Controller
{
    public function index()
    {
        $submissions = Submission::where('user_id', Auth::id())
            ->with('journal')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('submissions.index', compact('submissions'));
    }

    public function create()
    {
        $journals = Journal::where('is_active', true)->get();
        return view('submissions.create', compact('journals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'journal_id' => 'required|exists:journals,id',
            'title' => 'required|string|max:255',
            'abstract' => 'required|string',
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:512000', // 500MB limit
        ]);

        $file = $request->file('file');
        $path = $file->store('submissions', 'local'); // Maintain file system path for now

        // Read binary content
        $binaryData = file_get_contents($file->getRealPath());

        Submission::create([
            'user_id' => Auth::id(),
            'journal_id' => $request->journal_id,
            'title' => $request->title,
            'abstract' => $request->abstract,
            'file_path' => $path,
            'status' => 'submitted',
            'filename' => $file->getClientOriginalName(),
            'extension' => $file->getClientOriginalExtension(),
            'mime_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'binary_content' => $binaryData,
        ]);

        return redirect()->route('dashboard')->with('success', 'Manuscript submitted successfully.');
    }

    public function show(Submission $submission)
    {
        abort_unless($submission->user_id === Auth::id() || (Auth::check() && in_array(Auth::user()->role, ['admin', 'editor'])), 403);
        $submission->load(['journal', 'conference', 'user', 'reviews.reviewer']);
        return view('submissions.show', compact('submission'));
    }

    public function download(Submission $submission)
    {
        abort_unless($submission->user_id === Auth::id() || (Auth::check() && in_array(Auth::user()->role, ['admin', 'editor'])), 403);
        if ($submission->binary_content) {
            $filename = $submission->filename ?? 'manuscript_' . $submission->id . '.' . ($submission->extension ?? 'docx');
            return response($submission->binary_content)
                ->header('Content-Type', $submission->mime_type ?? 'application/octet-stream')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        }
        if ($submission->file_path && \Illuminate\Support\Facades\Storage::disk('local')->exists($submission->file_path)) {
            return \Illuminate\Support\Facades\Storage::disk('local')->download($submission->file_path, $submission->filename ?? 'manuscript_' . $submission->id . '.docx');
        }
        return back()->with('error', 'Manuscript file not found.');
    }
}
