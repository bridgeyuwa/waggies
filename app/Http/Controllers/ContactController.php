<?php

namespace App\Http\Controllers;

use App\Models\BusinessHour;
use App\Models\BusinessProfile;
use App\Support\ContactContextResolver;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\SchemaOrg\Schema;

class ContactController extends Controller
{
    public function __invoke(Request $request): View
    {
        $context = app(ContactContextResolver::class)->resolve($request);
        $rawIntent = $context['rawIntent'];
        $service = $context['service'];
        $resolvedService = $context['resolvedService'];
        $intent = $context['intent'];

        if ($this->isRemovedBookingContext($intent, $resolvedService)) {
            abort(404);
        }

        $product = $context['product'];
        unset($context['product']);
        $businessProfile = BusinessProfile::current();

        $metadata = [
            'title' => 'Contact Us — Waggies Pet Care Abuja',
            'description' => 'Get in touch with Waggies Pet Services in Abuja for general questions, partnerships, product enquiries, or support.',
            'ogTitle' => 'Contact Waggies — Abuja Pet Care',
            'ogDescription' => 'Get in touch with Waggies Pet Services in Abuja. Call, WhatsApp, email, or visit us.',
            'canonical' => route('contact'),
            'robots' => $request->query() === [] ? ['index', 'follow'] : ['noindex', 'follow'],
        ];

        $timezone = $businessProfile->timezone ?: config('app.timezone');
        $localBusiness = Schema::localBusiness()
            ->name($businessProfile->business_name)
            ->description($metadata['description'])
            ->url($metadata['canonical'])
            ->telephone($businessProfile->phone_international ?: $businessProfile->phone)
            ->email($businessProfile->primary_email)
            ->address(Schema::postalAddress()
                ->streetAddress($businessProfile->address_street)
                ->addressLocality($businessProfile->address_city)
                ->postalCode($businessProfile->address_postal_code)
                ->addressRegion($businessProfile->address_state)
                ->addressCountry($businessProfile->address_country))
            ->sameAs(array_values($businessProfile->socialLinks()))
            ->openingHoursSpecification(BusinessHour::openingHours($timezone)->asStructuredData(
                timezone: $timezone,
            ));

        $this->setPageHead($metadata, [
            Schema::contactPage()
                ->name('Contact Waggies - Abuja Pet Care')
                ->description($metadata['description'])
                ->url($metadata['canonical'])
                ->toArray(),
            $localBusiness->toArray(),
        ]);

        return view('pages.contact', $metadata + [
            'navSection' => 'contact',
            'mode' => $rawIntent === '' ? 'gateway' : 'form',
            'context' => $context,
            'schema' => $rawIntent === '' ? null : $this->schema($context, $product),
            'business' => $this->businessData($businessProfile),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function businessData(BusinessProfile $profile): array
    {
        return $profile->toPublicArray() + [
            'phoneLocal' => $profile->phone,
            'email' => $profile->primary_email,
            'hours' => BusinessHour::publicSchedule(),
            'openingHours' => BusinessHour::contactSchedule($profile->timezone ?: config('app.timezone')),
        ];
    }

    private function isRemovedBookingContext(?string $intent, ?string $resolvedService): bool
    {
        return in_array($intent, [
            'BOOKING_REQUEST',
            'SERVICE_REQUEST',
            'VETERINARY_REQUEST',
            'TRANSPORT_REQUEST',
            'QUOTE_REQUEST',
            'RELOCATION_REQUEST',
        ], true) || in_array($resolvedService, [
            'boarding',
            'boarding-dogs',
            'boarding-cats',
            'boarding-exotic',
            'grooming',
            'vet-care',
            'training',
            'relocation',
            'relocation-import',
            'relocation-export',
            'local-transport',
        ], true);
    }

    /**
     * @return array<string, mixed>
     */
    private function schema(array $context, ?array $product): array
    {
        $intent = $context['intent'];
        $fields = [];
        $title = 'General inquiry';
        $description = "Have a question? Send us a message and we'll get back to you on WhatsApp.";

        if ($intent === 'PRODUCT_INQUIRY') {
            $authoritative = $product['name'] ?? $context['productName'];
            $title = 'Product inquiry';
            $description = $authoritative
                ? 'Ask about: '.$authoritative
                : ($context['productName'] ? 'Ask about: '.$context['productName'] : 'Ask about a product in our shop.');
            $fields = [
                ['name' => 'productName', 'label' => 'Product', 'type' => 'text', 'required' => true, 'placeholder' => $authoritative ?: 'e.g. Royal Canin Puppy Food', 'defaultValue' => $authoritative, 'helperText' => $authoritative ? 'Pre-filled from the product page — edit if needed.' : ($context['productId'] ? 'Product not found — please specify the product you’re asking about.' : null)],
                ['name' => 'question', 'label' => 'Your question', 'type' => 'textarea', 'required' => true, 'placeholder' => 'e.g. Is this suitable for a 3-month-old puppy?'],
                ['name' => 'quantity', 'label' => 'Quantity (if relevant)', 'type' => 'number', 'required' => false, 'min' => 1, 'step' => 1],
            ];
        } elseif ($intent === 'CART_ORDER') {
            $title = 'Product enquiry';
            $description = 'Ask about the selected products and Waggies will confirm availability and final pricing with you.';
            $fields = [['name' => 'additionalNotes', 'label' => 'Questions or notes (optional)', 'type' => 'textarea', 'required' => false, 'placeholder' => 'What would you like to know about these products?']];
        } else {
            $fields = [
                ['name' => 'message', 'label' => 'Your message', 'type' => 'textarea', 'required' => true, 'placeholder' => 'How can we help you?'],
                ['name' => 'customerName', 'label' => 'Your name (optional)', 'type' => 'text', 'required' => false],
                ['name' => 'whatsappNumber', 'label' => 'WhatsApp number (optional)', 'type' => 'tel', 'required' => false],
            ];
        }

        if ($intent !== 'GENERAL_INQUIRY' && $intent !== 'CONTACT_REQUEST') {
            $fields = array_merge($fields, [
                ['name' => 'customerName', 'label' => 'Your name', 'type' => 'text', 'required' => true, 'placeholder' => 'e.g. Ada Obi'],
                ['name' => 'whatsappNumber', 'label' => 'WhatsApp number', 'type' => 'tel', 'required' => true, 'placeholder' => 'e.g. +234 800 000 0000', 'helperText' => "We'll use this to continue the conversation on WhatsApp."],
            ]);
        }

        return [
            'intent' => $intent,
            'title' => $title,
            'description' => $description,
            'fields' => $fields,
            'pricingMode' => 'NOT_APPLICABLE',
            'usesPets' => false,
            'supportsMultiService' => false,
            'allowedSpecies' => null,
            'contextSpecies' => null,
            'unresolvedProduct' => $intent === 'PRODUCT_INQUIRY' && $context['productId'] && ! $product,
        ];
    }
}
