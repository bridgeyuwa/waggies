<?php

namespace App\Http\Controllers;

use App\Actions\CreateBookingRequest;
use App\Http\Requests\StoreBookingRequest;
use App\Models\BusinessProfile;
use App\Support\BookingPricingCatalog;
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
            'description' => 'Send Waggies a booking request for boarding, veterinary care, or pet relocation in Abuja.',
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

        $requestedService = trim((string) $request->query('service', ''));
        $aliases = [
            'boarding-dogs' => ['service' => 'boarding', 'variant' => 'dogs'],
            'boarding-cats' => ['service' => 'boarding', 'variant' => 'cats'],
            'relocation-import' => ['service' => 'relocation', 'variant' => 'import'],
            'relocation-export' => ['service' => 'relocation', 'variant' => 'export'],
            'vet' => ['service' => 'vet-care'],
        ];
        $resolved = $aliases[$requestedService] ?? ['service' => $requestedService, 'variant' => (string) $request->query('variant', '')];
        $service = $resolved['service'];
        $variant = (string) ($request->query('variant', '') ?: ($resolved['variant'] ?? ''));
        $source = trim((string) $request->query('source', ''));
        $serviceOptions = app(BookingPricingCatalog::class)->serviceOptions();
        $whatsappUrl = BusinessProfile::current()->whatsapp_url;

        return view('pages.book', $metadata + [
            'navSection' => 'contact',
            'serviceOptions' => $serviceOptions,
            'selectedService' => array_key_exists($service, $serviceOptions) ? $service : null,
            'selectedVariant' => $variant,
            'source' => $source,
            'whatsappUrl' => $whatsappUrl.'?text='.rawurlencode('Hello Waggies, I submitted a booking request and would like to continue the conversation.'),
            'minimumDate' => now()->toDateString(),
            'bookingSubmitted' => (bool) session('booking_submitted'),
            'enableLivewire' => true,
            'bookingContext' => [
                'service' => array_key_exists($service, $serviceOptions) ? $service : null,
                'variant' => $variant !== '' ? $variant : null,
                'tier' => null,
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
            'phone_country',
            'phone_number',
            'phone_other_country_code',
            'idempotency_key',
            'preferred_contact_method',
            'service_key',
            'service_variant',
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
            'source' => $validated['source'] ?? null,
        ]);

        $createBookingRequest->handle([
            'idempotency_key' => $validated['idempotency_key'] ?? null,
            'contact' => [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'phone_country' => $validated['phone_country'],
                'phone_number' => $validated['phone_number'],
                'phone_other_country_code' => $validated['phone_other_country_code'] ?? null,
                'preferred_contact_method' => $validated['preferred_contact_method'] ?? null,
            ],
            'pets' => [[
                'name' => $validated['pet_name'],
                'species' => $validated['pet_type'],
            ]],
            'services' => [[
                'service_key' => $validated['service_key'],
                'service_variant' => $validated['service_variant'] ?? null,
                'pricing_tier' => null,
                'assigned_pet_ids' => [0],
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
