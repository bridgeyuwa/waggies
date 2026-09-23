<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $email = strtolower(trim($request->string('email')->toString()));
        $request->merge(['email' => $email]);
        $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'website' => ['nullable', 'string', 'max:0'],
        ]);

        try {
            NewsletterSubscriber::query()->updateOrCreate(
                ['email' => NewsletterSubscriber::normalizeEmail($email)],
                [
                    'status' => NewsletterSubscriber::STATUS_SUBSCRIBED,
                    'subscribed_at' => now(),
                    'unsubscribed_at' => null,
                ],
            );
        } catch (QueryException $exception) {
            report($exception);

            return back()
                ->withInput()
                ->with('newsletter_error', 'We could not complete your subscription right now. Please try again shortly.');
        }

        return back()->with('newsletter_status', 'Thanks — you are subscribed to Waggies updates.');
    }
}
