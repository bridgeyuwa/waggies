<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $email = strtolower(trim($request->string('email')->toString()));
        $request->merge(['email' => $email]);
        $request->validate(['email' => ['required', 'email', 'max:255']]);

        NewsletterSubscription::firstOrCreate(['email' => $email]);

        return back()->with('newsletter_status', 'Thanks — you are subscribed to Waggies updates.');
    }
}
