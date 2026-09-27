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

    public function policies(): View
    {
        $metadata = [
            'title' => 'Policies & Requirements - Waggies',
            'description' => 'A practical overview of Waggies boarding, service-request, payment, cancellation, and relocation requirements.',
            'canonical' => route('policies'),
            'ogTitle' => 'Waggies Policies & Requirements',
            'ogDescription' => 'A practical overview of the policies that guide Waggies requests and manual confirmations.',
        ];
        $this->setPageHead($metadata);

        return view('pages.policies', $metadata + [
            'summary' => [
                ['title' => 'Requests are not confirmations', 'description' => 'Waggies reviews availability, suitability, final pricing, special care, payment, and confirmation manually.'],
                ['title' => 'Boarding is for dogs and cats', 'description' => 'Owner-supplied food is the default. Health, behaviour, medication, and emergency details should be shared early.'],
                ['title' => 'Timing and refunds matter', 'description' => 'More than 48 hours before check-in generally allows a full refund; no-shows are not refundable.'],
                ['title' => 'Relocation is custom quoted', 'description' => 'Owners supply original documents, and a deposit is needed before non-refundable third-party commitments.'],
            ],
            'policyGroups' => [
                [
                    'id' => 'boarding',
                    'eyebrow' => 'BOARDING',
                    'title' => 'Boarding stay',
                    'description' => 'The information that helps Waggies review admission, care, and pickup safely.',
                    'items' => [
                        ['heading' => 'Before admission', 'paragraph' => 'Boarding accepts dogs and cats. Share relevant health information or records, feeding instructions, medication, special-care needs, emergency contacts, and any aggression, escape, bite, anxiety, or handling concerns.'],
                        ['heading' => 'During the stay', 'paragraph' => 'Owner-supplied food is the default. Medication, special handling, intensive supervision, and other special-care needs require staff review and may need a separate charge or quote.'],
                        ['heading' => 'Check-in and pickup', 'paragraph' => 'Agreed arrival and pickup times are confirmed with you. The checkout date is not another overnight stay unless the pet remains past the agreed cutoff; late pickup may incur a fee or an additional night.'],
                    ],
                    'links' => [
                        ['label' => 'Boarding Requirements & Admission', 'route' => 'policies.boarding'],
                        ['label' => 'Check-in, Check-out & Late Pickup', 'route' => 'policies.check-in'],
                        ['label' => 'Medication & Special Care', 'route' => 'policies.medication'],
                        ['label' => 'Emergency Veterinary Care', 'route' => 'policies.emergency-veterinary'],
                        ['label' => 'Pet Behaviour & Safety', 'route' => 'policies.behaviour-safety'],
                    ],
                ],
                [
                    'id' => 'changes-and-payment',
                    'eyebrow' => 'CHANGES & PAYMENT',
                    'title' => 'Cancellations, refunds, and confirmation',
                    'description' => 'The practical money and timing rules to know before you commit to a request.',
                    'items' => [
                        ['heading' => 'Cancellation and rescheduling', 'paragraph' => 'More than 48 hours before check-in: full refund. Within 48 hours: a partial refund or credit may apply after staff review. Contact Waggies as early as possible if plans change.'],
                        ['heading' => 'No-shows and early pickup', 'paragraph' => 'No-shows are not refundable. An early pickup remains chargeable for confirmed nights unless Waggies can resell the released nights.'],
                        ['heading' => 'Quotes and payment', 'paragraph' => 'The staff-entered quote is final for confirmation. Boarding and ordinary service requests are confirmed after required payment; relocation needs a deposit before non-refundable third-party bookings.'],
                    ],
                    'links' => [
                        ['label' => 'Cancellation, Rescheduling & Refunds', 'route' => 'policies.cancellation'],
                        ['label' => 'General Service Terms', 'route' => 'policies.general-terms'],
                    ],
                ],
                [
                    'id' => 'relocation',
                    'eyebrow' => 'RELOCATION',
                    'title' => 'Import and export',
                    'description' => 'Relocation requests are coordinated case by case for dogs and cats.',
                    'items' => [
                        ['heading' => 'What to prepare', 'paragraph' => 'Share the direction, origin, destination, travel date, pet species, microchip status, documentation status, airline or airport details, pickup and destination details, and route notes.'],
                        ['heading' => 'Documents and microchips', 'paragraph' => 'The owner supplies original documents. Waggies coordinates and facilitates the process. Existing chips are scanned and recorded; implantation is handled only when applicable to the route or destination.'],
                        ['heading' => 'Quotes and airport transfers', 'paragraph' => 'Airline, airport, veterinary, documentation, equipment, transfer, and coordination costs are itemized manually where practical. Airport transport is part of an import or export request, not a standalone service.'],
                    ],
                    'links' => [
                        ['label' => 'Read the Relocation Policy', 'route' => 'policies.relocation'],
                        ['label' => 'Open the Relocation Checklist', 'route' => 'relocation.checklist'],
                    ],
                ],
                [
                    'id' => 'general-terms',
                    'eyebrow' => 'GENERAL TERMS',
                    'title' => 'How service requests work',
                    'description' => 'The shared expectations that apply across Waggies services.',
                    'items' => [
                        ['heading' => 'Manual review', 'paragraph' => 'Submitting a request does not reserve availability or set the final price. Waggies confirms service suitability, availability, pricing, care requirements, payment instructions, and status directly with you.'],
                        ['heading' => 'Owner information', 'paragraph' => 'Provide accurate details about your pet, health, temperament, documents, timing, and special requirements so the team can assess the request safely.'],
                    ],
                    'links' => [
                        ['label' => 'Read the General Service Terms', 'route' => 'policies.general-terms'],
                        ['label' => 'Submit a Booking Request', 'route' => 'book'],
                    ],
                ],
            ],
        ]);
    }

    public function boardingPolicy(): View
    {
        return $this->policyPage('Boarding Requirements & Admission', 'policies.boarding', [
            ['heading' => 'Accepted species', 'paragraph' => 'The public boarding service accepts dogs and cats only. Each pet receives an individual enclosure.'],
            ['heading' => 'Information for review', 'paragraph' => 'Please share relevant health information or records, feeding instructions, medication, special-care needs, emergency contacts, and any behaviour or handling concerns. Waggies will confirm any operational requirements that remain pending.'],
            ['heading' => 'Illness and parasite concerns', 'paragraph' => 'Waggies may decline admission or require treatment or clearance when a pet appears ill, has a contagious condition, or has an active parasite concern.'],
            ['heading' => 'Feeding', 'paragraph' => 'Owner-supplied food is the default. If food is not supplied, approved food may be provided only after confirmation and any applicable cost is handled manually.'],
            ['heading' => 'Admission and pickup', 'paragraph' => 'Admission is subject to staff review. Check-in and pickup must follow the agreed times and any instructions sent with the confirmation.'],
        ]);
    }

    public function cancellationPolicy(): View
    {
        return $this->policyPage('Cancellation, Rescheduling & Refunds', 'policies.cancellation', [
            ['heading' => 'Starting policy', 'paragraph' => 'More than 48 hours before check-in: full refund. Within 48 hours: a partial refund or credit may apply after staff review. No-show: no refund.'],
            ['heading' => 'Early pickup', 'paragraph' => 'An agreed booking remains chargeable for the confirmed nights unless Waggies can resell the released nights.'],
            ['heading' => 'Relocation requests', 'paragraph' => 'Relocation deposits and third-party charges may be non-refundable once committed. The applicable terms are confirmed with the itemized quote before commitment.'],
            ['heading' => 'Changes', 'paragraph' => 'Contact Waggies as soon as possible to request a reschedule or cancellation. Availability and any credit or refund are confirmed manually.'],
        ]);
    }

    public function checkInPolicy(): View
    {
        return $this->policyPage('Check-in, Check-out & Late Pickup', 'policies.check-in', [
            ['heading' => 'Agreed times', 'paragraph' => 'Check-in and check-out times are agreed during review. The checkout date is not another overnight stay unless the pet remains past the agreed cutoff.'],
            ['heading' => 'Late pickup', 'paragraph' => 'Waggies will define a grace period in the confirmation. After that period, a late fee or an additional night may apply.'],
            ['heading' => 'Changes on the day', 'paragraph' => 'Tell the team as early as possible if your arrival or pickup timing changes so availability and care can be reassessed.'],
        ]);
    }

    public function medicationPolicy(): View
    {
        return $this->policyPage('Medication & Special Care', 'policies.medication', [
            ['heading' => 'Tell us early', 'paragraph' => 'Medication, special handling, intensive supervision, mobility needs, severe anxiety, and other special-care needs require staff review before confirmation.'],
            ['heading' => 'Instructions and charges', 'paragraph' => 'Provide clear written instructions and supplies where applicable. A separate charge or quote may apply, and Waggies may decline a need it cannot safely support.'],
            ['heading' => 'Not standard boarding', 'paragraph' => 'Daily veterinary checks, 24/7 supervision, structured play, enrichment programmes, outdoor walks, daily photos, and scheduled owner updates are not standard boarding promises.'],
        ]);
    }

    public function emergencyVeterinaryPolicy(): View
    {
        return $this->policyPage('Emergency Veterinary Care', 'policies.emergency-veterinary', [
            ['heading' => 'Authorization', 'paragraph' => 'Boarding requests should include emergency veterinary authorization or a clear instruction to discuss authorization during review.'],
            ['heading' => 'If an emergency occurs', 'paragraph' => 'Waggies may contact the primary and secondary emergency contacts and facilitate veterinary care where reasonably possible. Veterinary treatment and third-party charges remain separate from the boarding request unless confirmed otherwise.'],
            ['heading' => 'Limits', 'paragraph' => 'Boarding is not a substitute for emergency veterinary care. Waggies may decline admission when a pet appears too unwell or unsafe to board.'],
        ]);
    }

    public function behaviourSafetyPolicy(): View
    {
        return $this->policyPage('Pet Behaviour & Safety', 'policies.behaviour-safety', [
            ['heading' => 'Disclose relevant behaviour', 'paragraph' => 'Owners must disclose aggression, escape behaviour, bite history, severe anxiety, resource guarding, or handling difficulties.'],
            ['heading' => 'Case-by-case review', 'paragraph' => 'Difficult behaviour is reviewed case by case. Waggies may require additional handling arrangements, a separate quote, or may decline admission when safe care cannot be provided.'],
            ['heading' => 'Contacts', 'paragraph' => 'Provide one primary and one secondary emergency contact for boarding requests.'],
        ]);
    }

    public function relocationPolicy(): View
    {
        return $this->policyPage('Relocation', 'policies.relocation', [
            ['heading' => 'Import and export', 'paragraph' => 'Relocation is available for dogs and cats as a request-based, custom-quoted service. Airport transport is an internal component of an import or export booking, not a standalone service.'],
            ['heading' => 'What to share', 'paragraph' => 'Provide direction, origin, destination, travel date, pet species, chip status, documentation status, airline or airport details, pickup and destination details, and route notes.'],
            ['heading' => 'Microchips', 'paragraph' => 'Export includes microchipping by default when no existing chip is found. If a chip exists, Waggies scans and records it rather than implanting another. Import includes microchipping only when required by the destination or route. Where the provider supports registration, Waggies handles it; otherwise the owner receives the chip number and registration instructions. Microchipping is not modelled as dependent on a government licence in Nigeria.'],
            ['heading' => 'Documents and deposits', 'paragraph' => 'The owner supplies original documents. Waggies coordinates and facilitates required steps. A deposit is required before Waggies commits to airline or other non-refundable third-party bookings.'],
        ]);
    }

    public function generalTermsPolicy(): View
    {
        return $this->policyPage('General Service Terms', 'policies.general-terms', [
            ['heading' => 'Requests are not confirmations', 'paragraph' => 'Submitting a request does not reserve availability, set the final price, or create a confirmed booking.'],
            ['heading' => 'Manual confirmation', 'paragraph' => 'Waggies confirms availability, final pricing, special-care requirements, payment instructions, and the booking status directly with the customer.'],
            ['heading' => 'Payment', 'paragraph' => 'Boarding and ordinary service requests are confirmed after the staff quote and required payment. Relocation requires a deposit before Waggies commits to non-refundable third-party bookings.'],
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
        $this->setPageHead($metadata);

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
