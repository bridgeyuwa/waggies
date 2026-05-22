<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Models\Enquiry;
use App\Notifications\EnquiryReceivedNotification;
use App\Support\PricingQuote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(Request $request): View
    {
        $resolved = PricingQuote::resolveServiceContext(
            $request->query('service'),
            $request->query('variant'),
        );

        $context = [
            'service' => $resolved['service'] ?: $request->query('service'),
            'variant' => $resolved['variant'] ?: $request->query('variant'),
            'tier' => $request->query('tier'),
            'intent' => $request->query('intent', 'consult'),
            'quantity' => $request->query('quantity'),
            'summary' => $request->query('summary'),
        ];

        $prefillMessage = PricingQuote::buildPrefillMessage($context);

        if ($request->filled('message')) {
            $prefillMessage = $request->query('message');
        }

        return view('pages.contact', [
            'context' => $context,
            'prefillMessage' => $prefillMessage,
            'hasPricingContext' => filled($context['service']) || filled($context['summary']),
        ]);
    }

    public function store(StoreContactRequest $request)
    {
        $enquiry = Enquiry::create($request->validated());

        $adminAddress = config('mail.admin_address') ?: env('ADMIN_EMAIL');

        if ($adminAddress) {
            Notification::route('mail', $adminAddress)
                ->notify(new EnquiryReceivedNotification($enquiry));
        }

        return back()->with('success', 'Your message has been sent. We\'ll be in touch soon!');
    }
}
