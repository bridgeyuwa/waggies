<?php

namespace App\Http\Controllers;

use App\Models\ContactEnquiry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ContactEnquiryController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:40'],
            'subject' => ['required', 'string', 'max:160'],
            'service' => ['nullable', 'string', 'max:80'],
            'intent' => ['nullable', 'string', 'max:80'],
            'message' => ['required', 'string', 'max:12000'],
            'reference' => ['nullable', 'string', 'max:40'],
            'website' => ['nullable', 'max:0'],
        ]);

        $enquiry = ContactEnquiry::create([
            'name' => $validated['name'] ?? null,
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'subject' => trim($validated['subject']),
            'service' => isset($validated['service']) ? trim($validated['service']) : null,
            'intent' => isset($validated['intent']) ? trim($validated['intent']) : null,
            'message' => trim($validated['message']),
            'reference' => isset($validated['reference']) ? trim($validated['reference']) : null,
        ]);

        return response()->json([
            'message' => 'Your request has been saved. Waggies will continue the conversation on WhatsApp.',
            'id' => $enquiry->getKey(),
        ], 201);
    }
}
