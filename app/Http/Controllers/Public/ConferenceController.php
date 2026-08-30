<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Conference;
use App\Models\Country;
use App\Models\Topic;
use App\Models\ConferenceEnquiry;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ConferenceController extends Controller
{
    public function index(Request $request)
    {
        $query = Conference::query()->where('status', 'approved');

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->has('country')) {
            $query->whereHas('country', function($q) use ($request) {
                $q->where('slug', $request->country);
            });
        }

        if ($request->has('topic')) {
            $query->where(function($q) use ($request) {
                $q->whereHas('categories', function($sq) use ($request) {
                    $sq->where('slug', $request->topic);
                })->orWhereHas('category', function($sq) use ($request) {
                    $sq->where('slug', $request->topic);
                });
            });
        }

        if ($request->filled('filter')) {
            $filter = $request->filter;
            if ($filter === 'upcoming') {
                $query->where('start_date', '>=', now()->startOfDay())->orderBy('start_date', 'asc');
            } elseif ($filter === 'past') {
                $query->where('start_date', '<', now()->startOfDay())->orderBy('start_date', 'desc');
            } elseif ($filter === 'archived') {
                $query->where(function($q) {
                    $q->where('start_date', '<', now()->subYears(1))
                      ->orWhere('status', 'archived');
                })->orderBy('start_date', 'desc');
            }
        } else {
            $query->orderBy('start_date', 'asc');
        }

        $conferences = $query->with(['country', 'category', 'categories'])->paginate(12);
        
        $countries = Country::orderBy('name')->get();
        $topics = Topic::where('active', true)->get();

        return view('conferences.index', compact('conferences', 'countries', 'topics'));
    }

    public function show($slug)
    {
        $conference = Conference::with(['country', 'category', 'categories', 'organizer'])->where('slug', $slug)->firstOrFail();
        return view('conferences.show', compact('conference'));
    }

    public function category($slug)
    {
        $topic = Topic::where('slug', $slug)->firstOrFail();
        $conferences = Conference::where(function($q) use ($topic) {
                $q->whereHas('categories', function($sq) use ($topic) {
                    $sq->where('topics.id', $topic->id);
                })->orWhere('category_id', $topic->id);
            })
            ->where('status', 'approved')
            ->with(['country', 'category', 'categories'])
            ->orderBy('start_date')
            ->paginate(12);
        
        $countries = Country::orderBy('name')->get();
        $topics = Topic::where('active', true)->get();

        return view('conferences.index', compact('conferences', 'countries', 'topics', 'topic'));
    }

    public function search(Request $request)
    {
        $query = Conference::query()->where('status', 'approved');

        if ($request->q) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }

        $conferences = $query->take(10)->get(['title', 'slug', 'city']);

        return response()->json($conferences);
    }

    /**
     * Handle rapid conference enquiry (Name, Email, Mobile).
     */
    public function enquire(Request $request, $slug)
    {
        $conference = Conference::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile' => 'required|string|max:50',
            'message' => 'nullable|string|max:2000',
        ]);

        ConferenceEnquiry::create([
            'conference_id' => $conference->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'mobile' => $validated['mobile'],
            'message' => $validated['message'] ?? 'Enquiry submitted from conference detail page.',
            'status' => 'pending',
        ]);

        return back()->with('enquiry_success', 'Thank you! Your enquiry has been received. Our conference coordinator will call or email you shortly.');
    }

    /**
     * Show dedicated conference paper submission form.
     */
    public function submitPaper($slug)
    {
        $conference = Conference::where('slug', $slug)->firstOrFail();
        return view('conferences.submit', compact('conference'));
    }

    /**
     * Process conference paper submission with Paper Details & Scholar Details.
     */
    public function storeSubmission(Request $request, $slug)
    {
        $conference = Conference::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            // Paper Details
            'title' => 'required|string|max:500',
            'article_type' => 'required|in:research_paper,review_paper,short_communication',
            'abstract' => 'required|string|min:50',
            'keywords' => 'nullable|string|max:500',
            'manuscript_file' => 'required|file|mimes:pdf,doc,docx|max:102400', // 100MB

            // Scholar Details (Author 1 + dynamic Authors)
            'authors' => 'required|array|min:1',
            'authors.*.first_name' => 'required|string|max:100',
            'authors.*.last_name' => 'required|string|max:100',
            'authors.*.designation' => 'nullable|string|max:150',
            'authors.*.department' => 'nullable|string|max:150',
            'authors.*.institute' => 'nullable|string|max:255',
            'authors.*.orcid' => 'nullable|string|max:50',
            'authors.*.email' => 'required|email|max:255',
            'authors.*.mobile' => 'required|string|max:50',
        ]);

        // Resolve or create user account for corresponding author
        $primaryAuthor = $validated['authors'][0];
        $user = Auth::user();

        if (!$user) {
            $user = User::where('email', $primaryAuthor['email'])->first();
            if (!$user) {
                $user = User::create([
                    'name' => $primaryAuthor['first_name'] . ' ' . $primaryAuthor['last_name'],
                    'email' => $primaryAuthor['email'],
                    'password' => Hash::make(Str::random(16)),
                    'role' => 'author',
                    'affiliation' => $primaryAuthor['institute'] ?? null,
                ]);
            }
            Auth::login($user);
        }

        $file = $request->file('manuscript_file');
        $path = $file->store('submissions/conferences', 'public');
        $binaryData = file_get_contents($file->getRealPath());

        $submission = Submission::create([
            'user_id' => $user->id,
            'conference_id' => $conference->id,
            'title' => $validated['title'],
            'article_type' => $validated['article_type'],
            'abstract' => $validated['abstract'],
            'keywords' => $validated['keywords'] ?? '',
            'scholars_data' => $validated['authors'],
            'authors_data' => $validated['authors'],
            'file_path' => $path,
            'status' => 'submitted',
            'filename' => $file->getClientOriginalName(),
            'extension' => $file->getClientOriginalExtension(),
            'mime_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'binary_content' => $binaryData,
        ]);

        return redirect()->route('conferences.show', $conference->slug)->with('success', 'Manuscript submitted successfully! Your submission ID is #SUB-' . str_pad($submission->id, 5, '0', STR_PAD_LEFT) . '. You can track review progress in your dashboard.');
    }

    /**
     * Show Author Fee Payment submission page.
     */
    public function showPayment(Submission $submission)
    {
        $submission->load(['conference', 'user']);
        return view('submissions.payment', compact('submission'));
    }

    /**
     * Store Author Fee Payment Proof & Details.
     */
    public function storePayment(Request $request, Submission $submission)
    {
        $validated = $request->validate([
            'payment_fee_type' => 'required|string|max:100',
            'payment_amount' => 'required|string|max:50',
            'payment_transaction_id' => 'required|string|max:100',
            'payment_date' => 'required|date',
            'payment_receipt' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $receiptPath = $request->file('payment_receipt')->store('submissions/payments', 'public');

        $submission->update([
            'payment_fee_type' => $validated['payment_fee_type'],
            'payment_amount' => $validated['payment_amount'],
            'payment_transaction_id' => $validated['payment_transaction_id'],
            'payment_date' => $validated['payment_date'],
            'payment_receipt_path' => $receiptPath,
            'payment_status' => 'pending_verification',
        ]);

        return back()->with('success', 'Payment proof submitted successfully! Administrator will verify and schedule your presentation slot.');
    }

    /**
     * Author Re-submission after review correction request.
     */
    public function resubmit(Submission $submission)
    {
        $submission->load(['conference', 'reviews']);
        return view('submissions.resubmit', compact('submission'));
    }

    public function storeResubmit(Request $request, Submission $submission)
    {
        $request->validate([
            'manuscript_file' => 'required|file|mimes:pdf,doc,docx|max:102400',
            'abstract' => 'nullable|string',
        ]);

        $file = $request->file('manuscript_file');
        $path = $file->store('submissions/conferences', 'public');
        $binaryData = file_get_contents($file->getRealPath());

        $submission->update([
            'file_path' => $path,
            'abstract' => $request->abstract ?? $submission->abstract,
            'status' => 'under_review',
            'filename' => $file->getClientOriginalName(),
            'extension' => $file->getClientOriginalExtension(),
            'mime_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'binary_content' => $binaryData,
        ]);

        return redirect()->route('dashboard')->with('success', 'Revised manuscript re-submitted successfully for final editorial review.');
    }
}
