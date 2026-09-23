<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

final class TestimonialController extends Controller
{
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'website' => ['nullable', 'string', 'max:0'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'service' => ['required', 'string', Rule::in(array_merge(array_keys(Testimonial::serviceOptions()), ['Boarding', 'Grooming', 'Vet Care', 'Training', 'Transport', 'Relocation']))],
            'title' => ['required', 'string', 'max:80'],
            'story' => ['required', 'string', 'min:50', 'max:2000'],
            'author_name' => ['required', 'string', 'min:2', 'max:60'],
            'author_location' => ['required', 'string', 'min:2', 'max:80'],
            'contact_method' => ['nullable', 'string', Rule::in(['phone', 'email', 'whatsapp'])],
            'contact_value' => ['nullable', 'string', 'required_with:contact_method', 'max:255'],
            'pet_name' => ['nullable', 'string', 'max:60'],
            'pet_type' => ['required', 'string', 'in:Dog,Cat,Bird,Rabbit,Reptile,Other'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'consent' => ['accepted'],
        ]);

        $photo = $request->file('photo');

        $testimonial = Testimonial::create([
            'rating' => $validated['rating'],
            'service' => Testimonial::normalizeService($validated['service']),
            'title' => $validated['title'],
            'story' => $validated['story'],
            'author_name' => $validated['author_name'],
            'author_location' => $validated['author_location'],
            'contact_method' => $validated['contact_method'],
            'contact_value' => $validated['contact_value'],
            'pet_name' => $validated['pet_name'] ?? null,
            'pet_type' => $validated['pet_type'],
            'consented_at' => now(),
        ]);

        if ($photo instanceof UploadedFile) {
            $testimonial->addMedia($photo)->toMediaCollection('photo');
        }

        $message = "Thank you! Your testimonial has been submitted. We'll review it and share it with the Waggies community soon.";

        if ($request->expectsJson()) {
            return response()->json(['message' => $message], 201);
        }

        return back()->with('testimonial_status', $message);
    }
}
