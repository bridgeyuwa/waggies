<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNewsletterRequest;
use App\Models\Subscriber;

class NewsletterController extends Controller
{
    public function store(StoreNewsletterRequest $request)
    {
        Subscriber::firstOrCreate(['email' => $request->validated('email')]);

        return back()->with('newsletter_success', true);
    }
}
