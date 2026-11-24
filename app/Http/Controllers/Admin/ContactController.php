<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactMessage;

class ContactController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::latest()->paginate(15);
        return view('admin.contact_messages.index', compact('messages'));
    }

    public function show(ContactMessage $contactMessage)
    {
        if (!$contactMessage->is_read) {
            $contactMessage->update(['is_read' => true]);
        }
        $journals = \App\Models\Journal::where('is_active', true)->orderBy('title')->get();
        $editorialRoles = \App\Http\Controllers\Admin\EditorialBoardController::ROLES;
        return view('admin.contact_messages.show', compact('contactMessage', 'journals', 'editorialRoles'));
    }

    /**
     * Accept a Join Us application, appoint to Editorial Board or Reviewer, and auto-issue Appointment Certificate.
     */
    public function acceptApplication(Request $request, ContactMessage $contactMessage)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'affiliation' => 'required|string|max:500',
            'country' => 'nullable|string|max:100',
            'journal_id' => 'nullable',
        ]);

        $journalId = ($validated['journal_id'] === 'global' || empty($validated['journal_id'])) ? null : (int) $validated['journal_id'];
        $isReviewer = str_contains(strtolower($validated['role']), 'reviewer');

        // 1. Create or update User account
        $user = \App\Models\User::where('email', $validated['email'])->first();
        if (!$user) {
            $user = \App\Models\User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt(\Illuminate\Support\Str::random(12)),
                'role' => $isReviewer ? 'reviewer' : 'editor',
                'affiliation' => $validated['affiliation'],
            ]);
        } else {
            $user->update([
                'role' => $isReviewer ? 'reviewer' : 'editor',
                'affiliation' => $user->affiliation ?: $validated['affiliation'],
            ]);
        }

        // 2. Create or update EditorialBoard record
        \App\Http\Controllers\Admin\EditorialBoardController::ensureSchemaExists();
        $editorUid = 'ed_' . str_replace('-', '', (string) \Illuminate\Support\Str::uuid());

        $member = \App\Models\EditorialBoard::firstOrNew([
            'email' => $validated['email'],
            'journal_id' => $journalId,
        ]);

        $member->editor_uid = $member->editor_uid ?: $editorUid;
        $member->name = $validated['name'];
        $member->designation = $validated['designation'] ?? null;
        $member->department = $validated['department'] ?? null;
        $member->affiliation = $validated['affiliation'];
        $member->country = $validated['country'] ?? null;
        $member->role = $validated['role'];
        $member->journal_id = $journalId;
        $member->save();

        // 3. Automatically issue Appointment Certificate
        $cert = \App\Services\CertificateIssuanceService::issueForEditorialMember($member);

        // 4. Mark inquiry as processed / read
        $contactMessage->update(['is_read' => true]);

        return redirect()->route('certificates.editorial.member', $member)
            ->with('success', "Application approved! {$validated['name']} has been officially appointed as {$validated['role']}, and the appointment certificate has been generated.");
    }

    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();
        return back()->with('success', 'Intelligence record has been neutralized.');
    }
}
