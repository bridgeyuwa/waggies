<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Spatie\SchemaOrg\Schema;

final class LegalController extends Controller
{
    public function privacy(): View
    {
        return $this->page('Privacy Policy', 'privacy-policy', [
            ['heading' => null, 'paragraph' => 'At Waggies, we are committed to protecting your personal information and your right to privacy. This Privacy Policy explains how we collect, use, and protect the information you share with us when you use our services or visit our website.'],
            ['heading' => '1. Information We Collect', 'paragraph' => 'We may collect the following types of information:', 'list' => ['<strong>Contact information</strong> - your name, email address, and phone number when you contact us or make a booking.', '<strong>Pet information</strong> - details about your pet(s) including species, breed, age, and health records where relevant to the service.', '<strong>Usage data</strong> - optional analytics are reserved for a future privacy-reviewed integration and are not loaded in this build.']],
            ['heading' => '2. How We Use Your Information', 'paragraph' => 'We use the information we collect to:', 'list' => ['Provide and manage the pet care services you have requested.', 'Communicate with you about your bookings, enquiries, and updates.', 'Send you newsletters and marketing communications where you have opted in.', 'Improve our website and services.']],
            ['heading' => '3. Data Sharing', 'paragraph' => 'We do not sell or rent your personal data to third parties. We may share data with trusted service providers (such as payment processors or email platforms) solely to operate our services, and only under strict confidentiality obligations.'],
            ['heading' => '4. Data Retention', 'paragraph' => 'We retain your personal data for as long as necessary to provide our services and comply with legal obligations. You may request deletion of your data at any time by contacting us.'],
            ['heading' => '5. Your Rights', 'paragraph' => 'You have the right to access, correct, or request deletion of your personal data. To exercise these rights, please contact us at the address below.'],
            ['heading' => '6. Cookies', 'paragraph' => 'This build does not set Waggies cookies and does not load non-essential analytics, marketing, profiling, or tracking scripts. Functional browser storage used by the cart, request builder, and selected tools is described in our <a href="'.route('cookies-policy').'">Cookies Policy</a>.'],
            ['heading' => '7. Contact', 'paragraph' => 'If you have any questions about this Privacy Policy, please <a href="'.route('contact').'">contact us</a>.'],
        ]);
    }

    public function terms(): View
    {
        return $this->page('Terms of Service', 'terms-of-service', [
            ['heading' => null, 'paragraph' => 'These Terms of Service govern your use of Waggies\' services and website. By using our services, you agree to these terms in full. Please read them carefully.'],
            ['heading' => '1. Services', 'paragraph' => 'Waggies provides boarding, veterinary care, and dog and cat relocation services. Services are subject to availability and manual review.'],
            ['heading' => '2. Service Requests', 'paragraph' => 'A booking request or other enquiry is not a confirmed appointment or reservation. Waggies will review the request, confirm availability and agreed details directly, and explain any payment arrangements before service begins. This website does not currently provide online checkout or payment processing.'],
            ['heading' => '3. Changes and Cancellations', 'paragraph' => 'Please contact Waggies as soon as possible if you need to change or cancel a request. Any service-specific cancellation terms or payment arrangements will be agreed directly when the request is confirmed.'],
            ['heading' => '4. Pet Health Requirements', 'paragraph' => 'Waggies may request relevant health information or records during review and may refuse or end a booking when a pet presents a health or safety risk. Specific operational admission requirements are set out in the applicable policy pages and confirmed with the owner.'],
            ['heading' => '5. Owner Responsibilities', 'paragraph' => 'You are responsible for providing accurate information about your pet\'s health, temperament, and any special requirements. Failure to disclose relevant information may result in cancellation without refund.'],
            ['heading' => '6. Liability', 'paragraph' => 'Waggies takes every precaution to ensure the safety and wellbeing of all pets in our care. However, we cannot accept liability for illness or injury arising from pre-existing undisclosed conditions. We carry appropriate insurance for the services we provide.'],
            ['heading' => '7. Use of Our Website', 'paragraph' => 'You may use our website for lawful purposes only. You must not attempt to gain unauthorised access to any part of our website or its systems.'],
            ['heading' => '8. Changes to These Terms', 'paragraph' => 'We may update these Terms of Service from time to time. Continued use of our services following any update constitutes acceptance of the revised terms.'],
            ['heading' => '9. Contact', 'paragraph' => 'If you have any questions about these Terms of Service, please <a href="'.route('contact').'">contact us</a>.'],
        ]);
    }

    public function cookies(): View
    {
        return $this->page('Cookies Policy', 'cookies-policy', [
            ['heading' => null, 'paragraph' => 'This Cookies Policy explains how Waggies uses browser storage and similar technologies on our website.'],
            ['heading' => '1. What Are Cookies?', 'paragraph' => 'Cookies are small text files placed on your device when you visit a website. They allow the website to recognise your device and remember certain information about your visit.'],
            ['heading' => '2. How We Use Cookies', 'paragraph' => 'Waggies currently uses browser storage for essential product functionality:', 'list' => ['<strong>Saved product list</strong> - the <code>waggies-cart</code> localStorage entry preserves products selected for an enquiry.', '<strong>Request Builder</strong> - the <code>waggies-request-draft</code> sessionStorage entry preserves an in-progress request draft for the browser session.', '<strong>Product history</strong> - the <code>waggies-recently-viewed</code> localStorage entry supports the recently viewed products section.', '<strong>Checklist progress</strong> - the <code>waggies-new-pet-checklist</code> localStorage entry preserves checked checklist items.', '<strong>Search history</strong> - the <code>waggies-recent-searches</code> localStorage entry preserves recent search terms.', '<strong>Accessibility</strong> - reduced-motion behavior follows the browser\'s <code>prefers-reduced-motion</code> setting; Waggies does not create a separate preference cookie in this build.']],
            ['heading' => '3. Third-Party Cookies', 'paragraph' => 'No Waggies analytics, marketing, profiling, or tracking pixel is loaded in this build. The Contact page includes a lazy Google Maps iframe to display the business location; it is a functional location embed, not a Waggies analytics tag.'],
            ['heading' => '4. Managing Cookies', 'paragraph' => 'This build does not display a banner or preferences dialog because no non-essential Waggies cookies or scripts are active. Most browsers allow you to manage site storage through their settings. Disabling browser storage may prevent the cart, request draft, product history, checklist, or search history from persisting.'],
            ['heading' => '5. Changes to This Policy', 'paragraph' => 'We may update this Cookies Policy from time to time. Please check back periodically to stay informed.'],
            ['heading' => '6. Contact', 'paragraph' => 'If you have any questions about how we use cookies, please <a href="'.route('contact').'">contact us</a>.'],
        ]);
    }

    public function boardingPolicy(): View
    {
        return $this->policyPage('Boarding Requirements & Admission', 'boarding-policy', [
            ['heading' => null, 'paragraph' => 'This policy explains the practical requirements for a boarding request. Boarding is available for dogs and cats, is priced per pet per overnight stay, and is confirmed only after staff review.'],
            ['heading' => 'Accepted species and admission', 'paragraph' => 'The public boarding service accepts dogs and cats only. Every boarding pet receives an individual enclosure. Waggies may decline admission when a pet appears ill, has a contagious condition, has an active parasite concern, or cannot be cared for safely in the available setting.'],
            ['heading' => 'Information to share before arrival', 'paragraph' => 'Please provide accurate information so the team can review suitability and prepare for the stay.', 'list' => [
                'Relevant health information or records, where applicable.',
                'Feeding instructions and the owner-supplied food the pet normally eats.',
                'Medication, mobility needs, handling instructions, or other special-care needs.',
                'One primary and one secondary emergency contact.',
                'Any aggression, escape behaviour, bite history, severe anxiety, resource guarding, or handling difficulty.',
            ]],
            ['heading' => 'Food and routine care', 'paragraph' => 'Owner-supplied food is the default. If food is not supplied, Waggies may provide approved food only after confirmation, with any applicable cost handled manually. Standard boarding includes water, routine cleaning, basic welfare checks, and a light bath or wash for boarded dogs before pickup where safe and appropriate. The light wash is not professional grooming and does not include styling, clipping, or spa treatment.'],
            ['heading' => 'Medication, special care and emergency veterinary care', 'paragraph' => 'Medication, special handling, intensive supervision, mobility needs, severe anxiety, and other special-care needs require staff review before confirmation and may require a separate charge or quote. Provide clear written instructions and supplies where applicable. Boarding requests should include emergency veterinary authorization, or a clear instruction to discuss it during review. If an emergency occurs, Waggies may contact the listed emergency contacts and facilitate veterinary care where reasonably possible. Veterinary treatment and third-party charges remain separate unless confirmed otherwise.'],
            ['heading' => 'Dog size guidance', 'paragraph' => 'Customers select the dog size during the request. These bands are operational guidance for the request and are not a medical or legal classification:', 'list' => [
                'Small: up to 10kg.',
                'Medium: over 10kg through 25kg.',
                'Large: over 25kg through 40kg.',
                'Above 40kg or an unusual size: manual review is required.',
            ]],
            ['heading' => 'Check-in, check-out and late pickup', 'paragraph' => 'Agreed arrival and pickup times are confirmed during review. The checkout date is not another overnight stay unless the pet remains past the agreed cutoff. Waggies will define the applicable grace period in the confirmation. After that period, a late fee or an additional night may apply. Tell the team as early as possible if your timing changes so availability and care can be reassessed.'],
            ['heading' => 'What is not a standard boarding promise', 'paragraph' => 'Boarding does not automatically include daily photos, scheduled owner updates, outdoor walks, structured play, enrichment programmes, daily veterinary checks, or 24/7 supervision. Any such arrangement must be discussed and confirmed separately.'],
        ]);
    }

    public function cancellationPolicy(): View
    {
        return $this->policyPage('Cancellation, Rescheduling & Refunds', 'cancellation-policy', [
            ['heading' => null, 'paragraph' => 'This policy explains how Waggies handles cancellations, rescheduling, refunds, and changes after a request has been reviewed and confirmed. A booking request is not a confirmed booking until Waggies confirms the arrangements with you.'],
            ['heading' => 'More than 48 hours before check-in', 'paragraph' => 'A cancellation made more than 48 hours before the agreed check-in time generally qualifies for a full refund. Any refund is processed against the payment arrangement confirmed for the booking.'],
            ['heading' => 'Within 48 hours of check-in', 'paragraph' => 'A cancellation made within 48 hours may qualify for a partial refund or credit after staff review. The outcome depends on the timing, arrangements already made, and whether the released space can be used.'],
            ['heading' => 'No-show', 'paragraph' => 'A no-show is not refundable. Contact Waggies as early as possible if you may not be able to arrive so the team can review the situation before the agreed check-in time.'],
            ['heading' => 'Rescheduling and early pickup', 'paragraph' => 'Rescheduling is subject to availability and must be confirmed by Waggies. An early pickup remains chargeable for the confirmed nights unless Waggies can resell the released nights. Any credit or refund is confirmed manually.'],
            ['heading' => 'Relocation deposits and third-party costs', 'paragraph' => 'Relocation is custom quoted. A deposit is required before Waggies commits to airline or other non-refundable third-party bookings. Deposits and third-party charges may be non-refundable once committed; the applicable terms are included in the itemized quote before commitment.'],
            ['heading' => 'Request a change', 'paragraph' => 'Send the request as soon as your plans change, including the booking details, the requested change, and any new dates or timing. Waggies will review availability, charges, credits, and refunds manually before confirming the outcome.'],
        ]);
    }

    public function relocationPolicy(): View
    {
        return $this->policyPage('Relocation Policy', 'relocation-policy', [
            ['heading' => null, 'paragraph' => 'Relocation is a request-based, custom-quoted service for dogs and cats. Waggies manually reviews route feasibility, requirements, timing, providers, and the information supplied before confirming the next step.'],
            ['heading' => 'Import', 'paragraph' => 'Import support may include arrival coordination, pickup from the arrival airport, required document coordination, veterinary facilitation, and third-party provider coordination. Microchipping is included only when required by the destination or route. If a chip already exists, Waggies scans and records it rather than implanting another.'],
            ['heading' => 'Export', 'paragraph' => 'Export support may include airline booking, transport to the departure airport, required document coordination, veterinary facilitation, and third-party provider coordination. If no existing chip is found, microchipping is included by default. If a chip exists, Waggies scans and records it rather than implanting another. Where the provider supports registration, Waggies handles it; otherwise the owner receives the chip number and registration instructions.'],
            ['heading' => 'Information to provide', 'paragraph' => 'Provide the following details as early as possible:', 'list' => [
                'Import or export direction, origin country, and destination country.',
                'Preferred travel date, route or timing notes, and known airline or airport details.',
                'Pet species and existing microchip status.',
                'Documentation status, pickup details, and destination details.',
                'Any route constraints, timing concerns, or additional requirements.',
            ]],
            ['heading' => 'Coordination, documents and costs', 'paragraph' => 'The owner supplies original documents. Waggies helps coordinate and facilitate the required process with airlines, airports, veterinarians, and other relevant providers. Airport transport is an internal component of an Import or Export relocation booking, not a standalone Waggies service. Where practical, the quote itemizes airline costs, airport or third-party charges, veterinary and documentation costs, crate or travel equipment, airport transfer costs, and Waggies coordination fees.'],
            ['heading' => 'Quotes and deposits', 'paragraph' => 'Relocation remains subject to route and provider confirmation. A deposit is required before Waggies commits to airline or other non-refundable third-party bookings. The final quote, payment instructions, and confirmation status are handled manually.'],
        ]);
    }

    /**
     * @param  array<int, array<string, mixed>>  $sections
     */
    private function policyPage(string $title, string $routeName, array $sections): View
    {
        $metadata = [
            'title' => $title.' - Waggies',
            'description' => $title.' for Waggies pet care requests.',
            'canonical' => route($routeName),
            'ogTitle' => $title.' - Waggies',
            'ogDescription' => $title.' for Waggies pet care requests.',
        ];
        $this->setPageHead($metadata, [Schema::webPage()->name($title.' - Waggies')->description($metadata['description'])->url($metadata['canonical'])->toArray()]);

        return view('pages.legal', $metadata + [
            'sections' => $sections,
            'legalTitle' => $title,
            'legalRoute' => $routeName,
        ]);
    }

    private function page(string $title, string $slug, array $sections): View
    {
        $metadata = [
            'title' => $title.' - Waggies', 'description' => $title.' - Waggies',
            'canonical' => route($slug), 'ogTitle' => $title.' - Waggies', 'ogDescription' => $title.' - Waggies',
        ];
        $this->setPageHead($metadata, [Schema::webPage()->name($title.' - Waggies')->description($metadata['description'])->url($metadata['canonical'])->toArray()]);

        return view('pages.legal', compact('sections') + $metadata + [
            'legalTitle' => $title, 'legalRoute' => $slug,
        ]);
    }
}
