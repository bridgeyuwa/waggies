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
            ['heading' => '1. Services', 'paragraph' => 'Waggies provides pet boarding, grooming, veterinary care, training, transport, and relocation services. Services are subject to availability, and requests should be made in advance where possible.'],
            ['heading' => '2. Service Requests', 'paragraph' => 'A booking request or other enquiry is not a confirmed appointment or reservation. Waggies will review the request, confirm availability and agreed details directly, and explain any payment arrangements before service begins. This website does not currently provide online checkout or payment processing.'],
            ['heading' => '3. Changes and Cancellations', 'paragraph' => 'Please contact Waggies as soon as possible if you need to change or cancel a request. Any service-specific cancellation terms or payment arrangements will be agreed directly when the request is confirmed.'],
            ['heading' => '4. Pet Health Requirements', 'paragraph' => 'All pets using our boarding and grooming services must be up to date with core vaccinations. We reserve the right to refuse or terminate a booking if a pet presents a health risk to other animals or staff.'],
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
