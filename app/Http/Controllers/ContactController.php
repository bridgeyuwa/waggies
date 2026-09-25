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
        $variant = $context['variant'];
        $tier = $context['tier'];
        $resolvedService = $context['resolvedService'];
        $intent = $context['intent'];
        $mode = $rawIntent === '' ? 'gateway' : ($rawIntent === 'booking' && ! $service ? 'booking' : 'form');
        $product = $context['product'];
        unset($context['product']);
        $businessProfile = BusinessProfile::current();

        $metadata = [
            'title' => 'Contact Us — Waggies Pet Care Abuja',
            'description' => 'Get in touch with Waggies Pet Services in Abuja. Book boarding, grooming, vet care, training, or relocation. Call, WhatsApp, email, or visit us.',
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
            'navSection' => 'contact', 'mode' => $mode, 'context' => $context,
            'schema' => $mode === 'form' ? $this->schema($context, $product, $businessProfile) : null,
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

    private function variant(?string $service, ?string $variant): ?string
    {
        return match ($service) {
            'boarding-dogs' => 'dogs', 'boarding-cats' => 'cats', 'boarding-exotic' => 'exotic', default => $variant
        };
    }

    private function common(): array
    {
        return [['name' => 'customerName', 'label' => 'Your name', 'type' => 'text', 'required' => true, 'placeholder' => 'e.g. Ada Obi'], ['name' => 'whatsappNumber', 'label' => 'WhatsApp number', 'type' => 'tel', 'required' => true, 'placeholder' => 'e.g. +234 800 000 0000', 'helperText' => "We'll use this to continue the conversation on WhatsApp."]];
    }

    private function schema(array $ctx, ?array $product, BusinessProfile $businessProfile): array
    {
        $intent = $ctx['intent'];
        $service = $ctx['service'];
        $resolved = $ctx['resolvedService'];
        $variant = $this->variant($service, $ctx['variant']);
        $fields = [];
        $title = 'General inquiry';
        $description = "Have a question? Send us a message and we'll get back to you on WhatsApp.";
        $pricingMode = 'NOT_APPLICABLE';
        $usesPets = in_array($intent, ['BOOKING_REQUEST', 'SERVICE_REQUEST', 'VETERINARY_REQUEST', 'TRANSPORT_REQUEST', 'QUOTE_REQUEST'], true);
        $supportsMultiService = in_array($intent, ['BOOKING_REQUEST', 'SERVICE_REQUEST'], true);
        $isGeneralSchema = in_array($intent, ['GENERAL_INQUIRY', 'CONTACT_REQUEST', 'RELOCATION_REQUEST', 'TOOL_ASSISTANCE'], true);
        if ($intent === 'BOOKING_REQUEST' || ($intent === 'SERVICE_REQUEST' && $resolved === 'boarding')) {
            $inquiry = $intent === 'SERVICE_REQUEST';
            $label = $this->serviceName($service ?: 'boarding');
            $title = ucfirst($label).' '.($inquiry ? 'enquiry' : 'booking');
            $description = $inquiry ? "Ask us anything about boarding — requirements, vaccinations, packages, or your pet's specific needs. We'll answer on WhatsApp." : "Request a boarding stay. We'll confirm availability, package, and pricing on WhatsApp.";
            $pricingMode = 'ESTIMATED';
            $fields = [['name' => 'checkIn', 'label' => 'Check-in date', 'type' => 'date', 'required' => ! $inquiry, 'group' => 'Stay dates'], ['name' => 'checkOut', 'label' => 'Check-out date', 'type' => 'date', 'required' => ! $inquiry, 'group' => 'Stay dates'], ['name' => 'boardingPackage', 'label' => 'Boarding package', 'type' => 'select', 'required' => false, 'options' => $this->boardingOptions($variant), 'defaultValue' => $this->defaultTier($ctx['tier'], $this->boardingOptions($variant)), 'helperText' => "Exact packages and pricing vary by pet type — we'll confirm on WhatsApp."], ['name' => 'feedingRequirements', 'label' => 'Feeding requirements (optional)', 'type' => 'textarea', 'required' => false, 'placeholder' => 'Diet, portion sizes, feeding schedule, allergies...'], ['name' => 'medications', 'label' => 'Medications (optional)', 'type' => 'textarea', 'required' => false, 'placeholder' => 'Name, dose, frequency, administration notes...'], ['name' => 'behavioralConsiderations', 'label' => 'Behavioral considerations (optional)', 'type' => 'textarea', 'required' => false, 'placeholder' => 'Anxiety, reactivity, separation issues, triggers...'], ['name' => 'specialCareNeeds', 'label' => 'Special care needs (optional)', 'type' => 'textarea', 'required' => false, 'placeholder' => 'Mobility, recovery, senior care, enrichment preferences...'], ['name' => 'additionalNotes', 'label' => 'Additional notes (optional)', 'type' => 'textarea', 'required' => false, 'placeholder' => "Anything else we should know about your pet's stay..."]];
        } elseif ($intent === 'SERVICE_REQUEST' && $resolved === 'grooming') {
            $title = 'Grooming request';
            $description = "Tell us about your pet and the grooming you'd like — we'll confirm the package and slot on WhatsApp.";
            $pricingMode = 'ESTIMATED';
            $fields = [['name' => 'groomingPackage', 'label' => 'Grooming package', 'type' => 'select', 'required' => false, 'options' => [['value' => 'bath-brush', 'label' => 'Bath & Brush'], ['value' => 'full-groom', 'label' => 'Full Groom'], ['value' => 'luxury-spa', 'label' => 'Luxury Spa']], 'helperText' => "Not sure? Pick the closest match — we'll confirm on WhatsApp."], ['name' => 'addOns', 'label' => 'Add-ons (optional)', 'type' => 'text', 'required' => false, 'placeholder' => 'e.g. nail trim, teeth cleaning, de-shed treatment'], ['name' => 'preferredDate', 'label' => 'Preferred date', 'type' => 'date', 'required' => false], ['name' => 'additionalNotes', 'label' => 'Additional notes', 'type' => 'textarea', 'required' => false, 'placeholder' => 'Coat condition, matting, temperament, sensitivities, dog size...']];
        } elseif ($intent === 'SERVICE_REQUEST' && $resolved === 'training') {
            $title = 'Training request';
            $description = "Tell us about your dog and your training goals — we'll recommend the right programme on WhatsApp.";
            $pricingMode = 'ESTIMATED';
            $fields = [['name' => 'trainingObjective', 'label' => 'Training objective', 'type' => 'select', 'required' => true, 'options' => [['value' => 'basic-obedience', 'label' => 'Basic obedience'], ['value' => 'puppy-socialization', 'label' => 'Puppy socialization'], ['value' => 'behavioural-correction', 'label' => 'Behavioral correction'], ['value' => 'advanced-training', 'label' => 'Advanced training']], 'helperText' => "Not sure? Pick the closest match and we'll advise on WhatsApp."], ['name' => 'behaviouralConcern', 'label' => 'Behavioral concern (optional)', 'type' => 'textarea', 'required' => false, 'placeholder' => 'e.g. leash pulling, recall issues, reactivity to other dogs...'], ['name' => 'priorTrainingExperience', 'label' => 'Prior training experience (optional)', 'type' => 'textarea', 'required' => false, 'placeholder' => "Has your pet had any previous training? What worked / didn't work?"], ['name' => 'preferredDate', 'label' => 'Preferred start date', 'type' => 'date', 'required' => false], ['name' => 'additionalNotes', 'label' => 'Additional notes', 'type' => 'textarea', 'required' => false, 'placeholder' => 'Anything else that would help us plan the right programme...']];
        } elseif ($intent === 'SERVICE_REQUEST') {
            $label = $this->serviceName($service);
            $title = ucfirst($label).' request';
            $description = "Tell us about your pet and we'll get back to you on WhatsApp to confirm details and scheduling.";
            $fields = [['name' => 'preferredDate', 'label' => 'Preferred date', 'type' => 'date', 'required' => false], ['name' => 'additionalNotes', 'label' => 'Additional notes', 'type' => 'textarea', 'required' => false, 'placeholder' => "Tell us anything specific about your {$label} request..."]];
            $pricingMode = in_array($resolved, ['grooming', 'training'], true) ? 'ESTIMATED' : 'QUOTE_REQUIRED';
        } elseif ($intent === 'QUOTE_REQUEST') {
            $isImport = $service === 'relocation-import';
            $isExport = $service === 'relocation-export';
            $title = $isImport ? 'Pet import quote' : ($isExport ? 'Pet export quote' : ucfirst($this->serviceName($service)).' quote');
            $description = $isImport ? "Importing a pet into Nigeria involves permits, health checks, and airport collection. Share your details and we'll provide a quote on WhatsApp." : ($isExport ? "Exporting a pet from Nigeria involves permits, airline coordination, and destination compliance. Share your details and we'll provide a quote on WhatsApp." : "Import/export permits take several weeks. Share your travel details and we'll provide a quote on WhatsApp.");
            $pricingMode = 'QUOTE_REQUIRED';
            $fields = [['name' => 'origin', 'label' => $isExport ? 'Origin (where the pet is now)' : 'Origin country/city', 'type' => 'text', 'required' => true, 'placeholder' => $isExport ? 'Abuja, Nigeria' : 'e.g. London, UK', 'defaultValue' => $isExport ? 'Abuja, Nigeria' : null, 'helperText' => $isExport ? 'Default origin is our facility in Abuja — change if your pet starts elsewhere.' : null], ['name' => 'destination', 'label' => $isImport ? 'Destination (where the pet is going)' : 'Destination country/city', 'type' => 'text', 'required' => true, 'placeholder' => $isImport ? 'Abuja, Nigeria' : 'e.g. London, UK', 'defaultValue' => $isImport ? 'Abuja, Nigeria' : null, 'helperText' => $isImport ? 'Default destination is our facility in Abuja — change if your pet is going elsewhere.' : null], ['name' => 'travelDate', 'label' => 'Travel / move date', 'type' => 'date', 'required' => true, 'helperText' => 'Contact us at least 6-8 weeks before your travel date.'], ['name' => 'documentationStatus', 'label' => 'Pet documentation status (optional)', 'type' => 'textarea', 'required' => false, 'placeholder' => 'Vaccination records, microchip, existing permits, health certificates...'], ['name' => 'additionalNotes', 'label' => 'Additional context (optional)', 'type' => 'textarea', 'required' => false, 'placeholder' => 'Breed-specific concerns, airline preferences, special handling...']];
        } elseif ($intent === 'VETERINARY_REQUEST') {
            $title = 'Veterinary appointment';
            $description = 'Our vet team will review your request and confirm an appointment on WhatsApp.';
            $pricingMode = 'QUOTE_REQUIRED';
            $fields = [['name' => 'reasonForVisit', 'label' => 'Reason for visit', 'type' => 'textarea', 'required' => true, 'placeholder' => 'e.g. Annual checkup, vaccinations, not eating well...'], ['name' => 'urgency', 'label' => 'Urgency', 'type' => 'radio', 'required' => true, 'options' => [['value' => 'routine', 'label' => 'Routine'], ['value' => 'urgent', 'label' => 'Urgent (within 48h)'], ['value' => 'emergency', 'label' => 'Emergency']], 'helperText' => "For emergencies, call us directly at {$businessProfile->phone} or use the WhatsApp button."], ['name' => 'preferredDate', 'label' => 'Preferred date', 'type' => 'date', 'required' => false], ['name' => 'preferredTime', 'label' => 'Preferred time', 'type' => 'time', 'required' => false], ['name' => 'additionalNotes', 'label' => 'Additional notes (optional)', 'type' => 'textarea', 'required' => false, 'placeholder' => 'Symptoms, duration, existing conditions, current medications...']];
        } elseif ($intent === 'TRANSPORT_REQUEST') {
            $title = 'Local transport';
            $description = 'Door-to-door, climate-controlled pet transport across Abuja.';
            $pricingMode = 'ESTIMATED';
            $fields = [['name' => 'transportProduct', 'label' => 'Transport service', 'type' => 'select', 'required' => true, 'options' => [['value' => 'transport-city-transfer', 'label' => 'City Pet Transfer'], ['value' => 'transport-vet-transfer', 'label' => 'Vet Transfer'], ['value' => 'transport-airport-transfer', 'label' => 'Airport Transfer']], 'defaultValue' => $ctx['transportProduct']], ['name' => 'pickup', 'label' => 'Pickup location', 'type' => 'text', 'required' => true, 'placeholder' => 'e.g. Maitama, Abuja'], ['name' => 'dropoff', 'label' => 'Drop-off location', 'type' => 'text', 'required' => true, 'placeholder' => 'e.g. Nnamdi Azikiwe Airport'], ['name' => 'preferredDate', 'label' => 'Date', 'type' => 'date', 'required' => true], ['name' => 'preferredTime', 'label' => 'Preferred time', 'type' => 'time', 'required' => false], ['name' => 'tripType', 'label' => 'Trip type', 'type' => 'select', 'required' => false, 'options' => [['value' => 'one-way', 'label' => 'One-way'], ['value' => 'return', 'label' => 'Return']], 'defaultValue' => 'one-way'], ['name' => 'distanceKm', 'label' => 'Route distance (km)', 'type' => 'number', 'required' => true, 'min' => 1, 'step' => 1], ['name' => 'petSpecies', 'label' => 'Pet species', 'type' => 'text', 'required' => true], ['name' => 'petCount', 'label' => 'Number of pets', 'type' => 'number', 'required' => true, 'min' => 1, 'defaultValue' => '1'], ['name' => 'additionalPetSafe', 'label' => 'Shared vehicle/crate plan is safe for additional pets', 'type' => 'select', 'required' => false, 'options' => [['value' => 'false', 'label' => 'Needs assessment'], ['value' => 'true', 'label' => 'Confirmed safe']], 'defaultValue' => 'false'], ['name' => 'waitingMinutes', 'label' => 'Expected waiting time (minutes)', 'type' => 'number', 'required' => false, 'min' => 0, 'defaultValue' => '0'], ['name' => 'stopCount', 'label' => 'Additional stops', 'type' => 'number', 'required' => false, 'min' => 0, 'defaultValue' => '0'], ['name' => 'stopsWithinCorridor', 'label' => 'Additional stops are within the route corridor', 'type' => 'select', 'required' => false, 'options' => [['value' => 'false', 'label' => 'No / not confirmed'], ['value' => 'true', 'label' => 'Yes']], 'defaultValue' => 'false'], ['name' => 'transportUrgency', 'label' => 'Timing', 'type' => 'select', 'required' => false, 'options' => [['value' => 'standard', 'label' => 'Standard'], ['value' => 'same-day', 'label' => 'Same-day urgent']], 'defaultValue' => 'standard'], ['name' => 'afterHours', 'label' => 'After-hours request', 'type' => 'select', 'required' => false, 'options' => [['value' => 'false', 'label' => 'No'], ['value' => 'true', 'label' => 'Yes']], 'defaultValue' => 'false'], ['name' => 'airportDetails', 'label' => 'Airport details', 'type' => 'text', 'required' => false, 'requiredWhen' => ['field' => 'transportProduct', 'value' => 'transport-airport-transfer'], 'conditionalOn' => ['field' => 'transportProduct', 'value' => 'transport-airport-transfer']], ['name' => 'specialRequirements', 'label' => 'Special requirements (optional)', 'type' => 'textarea', 'required' => false, 'placeholder' => 'Crate size, medication during transit, motion sickness, etc.']];
        } elseif ($intent === 'PRODUCT_INQUIRY') {
            $authoritative = $product['name'] ?? $ctx['productName'];
            $title = 'Product inquiry';
            $description = $authoritative ? 'Ask about: '.$authoritative : ($ctx['productName'] ? 'Ask about: '.$ctx['productName'] : 'Ask about a product in our shop.');
            $usesPets = false;
            $fields = [['name' => 'productName', 'label' => 'Product', 'type' => 'text', 'required' => true, 'placeholder' => $authoritative ?: 'e.g. Royal Canin Puppy Food', 'defaultValue' => $authoritative, 'helperText' => $authoritative ? 'Pre-filled from the product page — edit if needed.' : ($ctx['productId'] ? 'Product not found — please specify the product you’re asking about.' : null)], ['name' => 'question', 'label' => 'Your question', 'type' => 'textarea', 'required' => true, 'placeholder' => 'e.g. Is this suitable for a 3-month-old puppy?'], ['name' => 'quantity', 'label' => 'Quantity (if relevant)', 'type' => 'number', 'required' => false, 'min' => 1, 'step' => 1]];
        } elseif ($intent === 'CART_ORDER') {
            $title = 'Product enquiry';
            $description = 'Ask about the selected products and Waggies will confirm availability and final pricing with you.';
            $usesPets = false;
            $fields = [['name' => 'additionalNotes', 'label' => 'Questions or notes (optional)', 'type' => 'textarea', 'required' => false, 'placeholder' => 'What would you like to know about these products?']];
        } else {
            $usesPets = false;
            $supportsMultiService = false;
            $fields = [['name' => 'message', 'label' => 'Your message', 'type' => 'textarea', 'required' => true, 'placeholder' => 'How can we help you?'], ['name' => 'customerName', 'label' => 'Your name (optional)', 'type' => 'text', 'required' => false], ['name' => 'whatsappNumber', 'label' => 'WhatsApp number (optional)', 'type' => 'tel', 'required' => false]];
        }
        $fields = $isGeneralSchema ? $fields : array_merge($fields, $this->common());

        return ['intent' => $isGeneralSchema ? 'GENERAL_INQUIRY' : $intent, 'title' => $title, 'description' => $description, 'fields' => $fields, 'pricingMode' => $pricingMode, 'usesPets' => $usesPets, 'supportsMultiService' => $supportsMultiService, 'allowedSpecies' => $resolved === 'training' ? ['dog'] : null, 'contextSpecies' => $variant === 'dogs' ? 'dog' : ($variant === 'cats' ? 'cat' : ($variant === 'exotic' ? 'exotic' : null)), 'unresolvedProduct' => $intent === 'PRODUCT_INQUIRY' && $ctx['productId'] && ! $product, 'pricingData' => config('waggies_pricing')];
    }

    private function defaultTier(?string $tier, array $options): ?string
    {
        return $tier && collect($options)->contains('value', $tier) ? $tier : null;
    }

    private function boardingOptions(?string $variant): array
    {
        $variant ??= 'dogs';
        $variants = config('waggies_pricing.services.boarding.variants', []);
        $tiers = $variants[$variant]['tiers'] ?? $variants['dogs']['tiers'];

        return collect($tiers)->map(fn (array $tier, string $key): array => ['value' => $key, 'label' => $tier['label']])->values()->all();
    }

    private function serviceName(?string $service): string
    {
        return ['boarding' => 'boarding', 'boarding-dogs' => 'dog boarding', 'boarding-cats' => 'cat boarding', 'boarding-exotic' => 'exotic pet boarding', 'grooming' => 'grooming', 'vet-care' => 'veterinary care', 'training' => 'training', 'relocation' => 'relocation', 'relocation-import' => 'pet import', 'relocation-export' => 'pet export', 'local-transport' => 'local transport', 'transport' => 'local transport'][$service] ?? str_replace('-', ' ', $service ?: 'a service');
    }
}
