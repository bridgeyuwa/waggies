<?php

namespace App\Http\Controllers;

use App\Actions\CreateBookingRequest;
use App\Http\Requests\StoreBookingRequest;
use App\Models\BusinessProfile;
use App\Support\BookingPricingCatalog;
use App\Support\BookingWhatsAppMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\SchemaOrg\Schema;

final class BookingRequestsController extends Controller
{
    public function create(Request $request, BookingWhatsAppMessage $bookingWhatsAppMessage): View
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

        $source = $this->queryString($request->query('source')) ?? '';
        $serviceOptions = app(BookingPricingCatalog::class)->serviceOptions();
        $whatsappUrl = session('booking_whatsapp_url')
            ?? $bookingWhatsAppMessage->genericUrl(BusinessProfile::current()->whatsapp_url);
        $context = $this->bookingContextFromRequest($request, $serviceOptions);
        $service = $context['service'];
        $variant = $context['variant'];

        return view('pages.book', $metadata + [
            'navSection' => 'contact',
            'serviceOptions' => $serviceOptions,
            'selectedService' => $service,
            'selectedVariant' => $variant,
            'source' => $source,
            'whatsappUrl' => $whatsappUrl,
            'minimumDate' => now()->toDateString(),
            'bookingSubmitted' => (bool) session('booking_submitted'),
            'enableLivewire' => true,
            'bookingContext' => [
                'service' => $service,
                'variant' => $variant,
                'pet_type' => $context['pet_type'],
                'pet_size' => $context['pet_size'],
                'care_needs' => $context['care_needs'],
                'tier' => null,
                'source' => $source !== '' ? $source : null,
                'whatsappUrl' => $whatsappUrl,
                'notice' => $context['notice'],
            ],
        ]);
    }

    /**
     * @param  array<string, string>  $serviceOptions
     * @return array{service: ?string, variant: ?string, pet_type: ?string, pet_size: ?string, care_needs: list<string>, notice: ?string}
     */
    private function bookingContextFromRequest(Request $request, array $serviceOptions): array
    {
        $requestedService = $this->queryString($request->query('service')) ?? '';
        $service = array_key_exists($requestedService, $serviceOptions) ? $requestedService : null;
        $catalog = app(BookingPricingCatalog::class);
        $petType = $this->queryString($request->query('pet_type'));
        $petSize = $this->queryString($request->query('pet_size'));
        $direction = $this->queryString($request->query('direction'));
        $legacyVariant = $this->queryString($request->query('variant')) ?? '';
        $careNeeds = $this->queryValues($request->query('care_needs', []));
        $careNeed = $this->queryString($request->query('care_need')) ?? '';
        $noticeParts = [];

        if ($legacyVariant !== '') {
            $noticeParts[] = 'This booking link used an older format, so its option was not preselected.';
        }

        if ($requestedService !== '' && $service === null) {
            $noticeParts[] = 'That booking link is no longer available, so please choose a service below.';
        }

        if ($service === 'boarding') {
            if ($petType !== null && ! $catalog->isPetCompatible($service, null, $petType)) {
                $petType = null;
                $noticeParts[] = 'We could not use the pet type in that link, so you can choose it on the next step.';
            }

            if ($petSize !== null && ($petType === 'cat' || ! array_key_exists($petSize, $catalog->sizeOptions($service, 'dogs')))) {
                $petSize = null;
                $noticeParts[] = 'We could not use the dog size in that link, so you can choose it on the next step.';
            } elseif ($petSize !== null) {
                $petType ??= 'dog';
            }
        } elseif ($petType !== null) {
            $petType = null;
            $noticeParts[] = 'We could not use that pet type for this service, so it was left unselected.';
        } elseif ($petSize !== null) {
            $petSize = null;
            $noticeParts[] = 'We could not use that dog size for this service.';
        }

        $variant = null;

        if ($service === 'relocation') {
            if ($direction !== null && array_key_exists($direction, $catalog->variantOptions($service))) {
                $variant = $direction;
            } elseif ($direction !== null) {
                $noticeParts[] = 'We could not use that relocation direction, so you can choose it below.';
            }
        } elseif ($direction !== null) {
            $noticeParts[] = 'We could not use that relocation direction for this service.';
        }

        if ($service === 'vet-care') {
            if ($careNeed !== '') {
                $careNeeds[] = $careNeed;
            }

            $validCareNeeds = $catalog->careNeeds($careNeeds);

            if (count($validCareNeeds) !== count(array_unique(array_filter($careNeeds, 'is_string')))) {
                $noticeParts[] = 'One or more care options in that link were not available, so they were left unselected.';
            }

            $careNeeds = $validCareNeeds;
        } elseif ($careNeeds !== [] || $careNeed !== '') {
            $noticeParts[] = 'Those care options are only available for veterinary care.';
            $careNeeds = [];
        }

        return [
            'service' => $service,
            'variant' => $variant,
            'pet_type' => $petType,
            'pet_size' => $petSize,
            'care_needs' => $careNeeds,
            'notice' => $noticeParts === [] ? null : implode(' ', $noticeParts),
        ];
    }

    /**
     * @return list<string>
     */
    private function queryValues(mixed $value): array
    {
        $values = is_array($value) ? $value : [$value];

        return collect($values)
            ->filter(static fn (mixed $item): bool => is_string($item) && trim($item) !== '')
            ->map(static fn (string $item): string => trim($item))
            ->unique()
            ->values()
            ->all();
    }

    private function queryString(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $value = trim($value);

        return $value === '' ? null : $value;
    }

    public function store(
        StoreBookingRequest $request,
        CreateBookingRequest $createBookingRequest,
        BookingWhatsAppMessage $bookingWhatsAppMessage,
    ): RedirectResponse {
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

        $bookingData = [
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
        ];

        $createBookingRequest->handle($bookingData);

        $whatsappUrl = $bookingWhatsAppMessage->bookingUrl(
            BusinessProfile::current()->whatsapp_url,
            $bookingData['contact'],
            $bookingData['pets'],
            $bookingData['services'],
        );

        return redirect()
            ->route('book')
            ->with([
                'booking_submitted' => true,
                'booking_whatsapp_url' => $whatsappUrl,
            ]);
    }
}
