<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

final class TestimonialController extends Controller
{
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'website' => ['nullable', 'string', 'max:0'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'service' => ['required', 'string', Rule::in(array_merge(array_keys(Testimonial::serviceOptions()), ['Boarding', 'Grooming', 'Vet Care', 'Training', 'Transport', 'Relocation']))],
            'story' => ['required', 'string', 'min:50', 'max:2000'],
            'author_name' => ['required', 'string', 'min:2', 'max:60'],
            'author_location' => ['required', 'string', 'min:2', 'max:80'],
            'consent' => ['accepted'],
        ]);

        $testimonial = Testimonial::create([
            'rating' => $validated['rating'],
            'service' => Testimonial::normalizeService($validated['service']),
            'story' => $validated['story'],
            'author_name' => $validated['author_name'],
            'author_location' => $validated['author_location'],
            'consented_at' => now(),
        ]);

        $message = "Thank you! Your testimonial has been submitted. We'll review it and share it with the Waggies community soon.";

        if ($request->expectsJson()) {
            return response()->json(['message' => $message], 201);
        }

        return back()->with('testimonial_status', $message);
    }
}
