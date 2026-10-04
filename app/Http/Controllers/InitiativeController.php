<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;

class InitiativeController extends Controller
{
    /**
     * Store a new Join Us application / submission from the Initiatives page.
     */
    public function storeJoinUs(Request $request)
    {
        $validated = $request->validate([
            'role_type' => 'required|string|in:author,reviewer,editor_societies',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'institution' => 'required|string|max:255',
            'field' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'profile_url' => 'nullable|url|max:255',
            'message' => 'nullable|string|max:5000',
        ]);

        $roleLabels = [
            'author' => 'Author / Researcher Contributor',
            'reviewer' => 'Peer Reviewer Panel',
            'editor_societies' => 'Editorial Board / Society Partnership',
        ];

        $roleTitle = $roleLabels[$validated['role_type']] ?? ucfirst($validated['role_type']);

        $formattedMessage = "Application Type: {$roleTitle}\n"
            . "Applicant Name: {$validated['name']}\n"
            . "Email: {$validated['email']}\n"
            . "Institution: {$validated['institution']}\n"
            . "Field / Specialty: {$validated['field']}\n"
            . "Country: {$validated['country']}\n"
            . (!empty($validated['profile_url']) ? "Profile / ORCID URL: {$validated['profile_url']}\n" : "")
            . "\nStatement / Details:\n"
            . ($validated['message'] ?? 'None provided');

        ContactMessage::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => "Join Us Application: [{$roleTitle}] - {$validated['name']} ({$validated['field']})",
            'message' => $formattedMessage,
            'is_read' => false,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Your application has been received successfully.'
            ]);
        }

        return back()->with('success', 'Your application has been received successfully.');
    }
}
