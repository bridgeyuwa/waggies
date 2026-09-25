<?php

namespace App\Http\Controllers;

use App\Actions\CreateBookingRequest;
use App\Http\Requests\StoreBookingRequest;
use App\Models\BookingRequest;
use App\Models\BusinessProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\SchemaOrg\Schema;

final class BookingRequestsController extends Controller
{
    public function create(Request $request): View
    {
        $metadata = [
            'title' => 'Request a Service - Waggies Pet Care Abuja',
            'description' => 'Send Waggies a booking request for boarding, grooming, veterinary care, training, relocation or local transport in Abuja.',
            'canonical' => route('book'),
            'ogTitle' => 'Request a Service - Waggies Pet Care Abuja',
            'ogDescription' => 'Tell Waggies what your pet needs and our team will confirm the details with you.',
        ];

        $this->setPageHead($metadata, [
            Schema::webPage()
                ->name($metadata['title'])
                ->description($metadata['description'])
                ->url($metadata['canonical'])
                ->toArray(),
        ]);

        $service = trim((string) $request->query('service', ''));
        $variant = trim((string) $request->query('variant', ''));
        $tier = trim((string) $request->query('tier', ''));
        $source = trim((string) $request->query('source', ''));
        $serviceOptions = BookingRequest::serviceOptions();
        $whatsappUrl = BusinessProfile::current()->whatsapp_url;

        return view('pages.book', $metadata + [
            'navSection' => 'contact',
            'serviceOptions' => $serviceOptions,
            'selectedService' => array_key_exists($service, $serviceOptions) ? $service : null,
            'selectedVariant' => $variant,
            'selectedTier' => $tier,
            'source' => $source,
            'whatsappUrl' => $whatsappUrl.'?text='.rawurlencode('Hello Waggies, I submitted a booking request and would like to continue the conversation.'),
            'minimumDate' => now()->toDateString(),
            'bookingSubmitted' => (bool) session('booking_submitted'),
            'enableLivewire' => true,
            'bookingContext' => [
                'service' => array_key_exists($service, $serviceOptions) ? $service : null,
                'variant' => $variant !== '' ? $variant : null,
                'tier' => $tier !== '' ? $tier : null,
                'source' => $source !== '' ? $source : null,
                'whatsappUrl' => $whatsappUrl.'?text='.rawurlencode('Hello Waggies, I submitted a booking request and would like to continue the conversation.'),
            ],
        ]);
    }

    public function store(StoreBookingRequest $request, CreateBookingRequest $createBookingRequest): RedirectResponse
    {
        $validated = $request->safe()->only([
            'name',
            'email',
            'phone',
            'preferred_contact_method',
            'service_key',
            'service_variant',
            'pricing_tier',
            'source',
            'requested_date',
            'requested_time',
            'pet_name',
            'pet_type',
            'location',
            'message',
        ]);

        $context = array_filter([
            'service' => $validated['service_key'] ?? null,
            'variant' => $validated['service_variant'] ?? null,
            'tier' => $validated['pricing_tier'] ?? null,
            'source' => $validated['source'] ?? null,
        ]);

        $createBookingRequest->handle([
            'contact' => [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'preferred_contact_method' => $validated['preferred_contact_method'] ?? null,
            ],
            'pets' => [[
                'name' => $validated['pet_name'],
                'species' => $validated['pet_type'],
            ]],
            'services' => [[
                'service_key' => $validated['service_key'],
                'service_variant' => $validated['service_variant'] ?? null,
                'pricing_tier' => $validated['pricing_tier'] ?? null,
                'requested_date' => $validated['requested_date'],
                'requested_time' => $validated['requested_time'] ?? null,
                'location' => $validated['location'] ?? null,
                'details' => [
                    'message' => $validated['message'] ?? null,
                ],
            ]],
            'source' => $validated['source'] ?? null,
            'context' => $context,
        ]);

        return redirect()
            ->route('book')
            ->with('booking_submitted', true);
    }
}
