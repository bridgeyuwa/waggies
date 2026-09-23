<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\BookingRequest;
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

        return view('pages.book', $metadata + [
            'navSection' => 'contact',
            'serviceOptions' => $serviceOptions,
            'selectedService' => array_key_exists($service, $serviceOptions) ? $service : null,
            'selectedVariant' => $variant,
            'selectedTier' => $tier,
            'source' => $source,
            'whatsappUrl' => config('waggies.whatsapp').'?text='.rawurlencode('Hello Waggies, I submitted a booking request and would like to continue the conversation.'),
            'minimumDate' => now()->toDateString(),
            'bookingSubmitted' => (bool) session('booking_submitted'),
        ]);
    }

    public function store(StoreBookingRequest $request): RedirectResponse
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

        $validated['context'] = array_filter([
            'service' => $validated['service_key'] ?? null,
            'variant' => $validated['service_variant'] ?? null,
            'tier' => $validated['pricing_tier'] ?? null,
            'source' => $validated['source'] ?? null,
        ]);

        BookingRequest::create($validated);

        return redirect()
            ->route('book')
            ->with('booking_submitted', true);
    }
}
