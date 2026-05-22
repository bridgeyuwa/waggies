<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Models\Enquiry;
use App\Notifications\EnquiryReceivedNotification;
use Illuminate\Support\Facades\Notification;

class ContactController extends Controller
{
    public function index()
    {
        return view('pages.contact');
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
