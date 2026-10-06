<?php

namespace App\Support;

use App\Http\Controllers\ToolData;

final class SearchCatalog
{
    /**
     * Return the code-owned public pages that participate in global search.
     *
     * @return array<int, array{key: string, type: string, title: string, excerpt: string, route: string, section: string, category: string, keywords: string, boost: int}>
     */
    public function entries(): array
    {
        $entries = [
            ['key' => 'home', 'type' => 'page', 'title' => 'Home', 'excerpt' => 'Waggies boarding, veterinary care, and dog and cat relocation in Abuja.', 'route' => 'home', 'section' => 'Waggies', 'category' => 'Page', 'keywords' => 'pet care Abuja Nigeria', 'boost' => 10],
            ['key' => 'services', 'type' => 'service', 'title' => 'Services', 'excerpt' => 'Browse Waggies boarding, veterinary care, and relocation services.', 'route' => 'services.index', 'section' => 'Services', 'category' => 'Service', 'keywords' => 'pet services care', 'boost' => 9],
            ['key' => 'boarding', 'type' => 'service', 'title' => 'Boarding', 'excerpt' => 'Overnight boarding for dogs and cats, priced per pet per night and reviewed before confirmation.', 'route' => 'services.boarding', 'section' => 'Services', 'category' => 'Service', 'keywords' => 'pet boarding dog cat overnight stay kennel', 'boost' => 10],
            ['key' => 'veterinary-care', 'type' => 'service', 'title' => 'Veterinary Care', 'excerpt' => 'Wellness consultations, comprehensive examinations, vaccination requests, and microchipping for dogs and cats.', 'route' => 'services.vet-care', 'section' => 'Services', 'category' => 'Service', 'keywords' => 'vet veterinary vaccination microchip health clinic', 'boost' => 10],
            ['key' => 'relocation', 'type' => 'service', 'title' => 'Pet Relocation', 'excerpt' => 'Custom-quoted import and export coordination for dogs and cats.', 'route' => 'services.relocation', 'section' => 'Services', 'category' => 'Service', 'keywords' => 'pet relocation import export travel airport Nigeria', 'boost' => 9],
            ['key' => 'boarding-policy', 'type' => 'page', 'title' => 'Boarding Requirements & Admission', 'excerpt' => 'Prepare your pet for boarding, including health information, feeding, behaviour, medication, and emergency contacts.', 'route' => 'boarding-policy', 'section' => 'Policies', 'category' => 'Policy', 'keywords' => 'boarding admission requirements health feeding medication', 'boost' => 7],
            ['key' => 'cancellation-policy', 'type' => 'page', 'title' => 'Cancellation, Rescheduling & Refunds', 'excerpt' => 'Understand Waggies cancellation, rescheduling, refund, no-show, early pickup, and late pickup guidance.', 'route' => 'cancellation-policy', 'section' => 'Policies', 'category' => 'Policy', 'keywords' => 'cancellation refund reschedule no show late pickup', 'boost' => 7],
            ['key' => 'relocation-policy', 'type' => 'page', 'title' => 'Relocation Policy', 'excerpt' => 'Import and export relocation requests, document coordination, deposits, and third-party travel arrangements.', 'route' => 'relocation-policy', 'section' => 'Policies', 'category' => 'Policy', 'keywords' => 'relocation import export policy deposit documents', 'boost' => 7],
            ['key' => 'faq', 'type' => 'page', 'title' => 'FAQ', 'excerpt' => 'Answers to common questions about Waggies services, preparation, care and bookings.', 'route' => 'faq', 'section' => 'Support', 'category' => 'Page', 'keywords' => 'questions answers help', 'boost' => 8],
            ['key' => 'contact', 'type' => 'page', 'title' => 'Contact', 'excerpt' => 'Contact Waggies in Abuja for a service request, booking request, quote or general question.', 'route' => 'contact', 'section' => 'Support', 'category' => 'Page', 'keywords' => 'contact phone WhatsApp booking request', 'boost' => 8],
            ['key' => 'careers', 'type' => 'page', 'title' => 'Careers at Waggies', 'excerpt' => 'See current job openings and learn how to apply to join the Waggies team.', 'route' => 'about.careers', 'section' => 'About', 'category' => 'Page', 'keywords' => 'jobs careers vacancies employment', 'boost' => 6],
            ['key' => 'loyalty', 'type' => 'page', 'title' => 'Loyalty Programme', 'excerpt' => 'Learn about Waggies loyalty benefits and ask the team about current eligibility.', 'route' => 'loyalty', 'section' => 'About', 'category' => 'Page', 'keywords' => 'loyalty rewards benefits', 'boost' => 6],
        ];

        if (config('services.waggies_shop.enabled', false)) {
            $entries[] = ['key' => 'shop', 'type' => 'page', 'title' => 'Pet Shop', 'excerpt' => 'Browse pet food, toys, grooming supplies, health products and accessories.', 'route' => 'shop.index', 'section' => 'Shop', 'category' => 'Page', 'keywords' => 'products pet shop supplies food toys', 'boost' => 7];
        }

        foreach (ToolData::catalogue() as $tool) {
            $entries[] = [
                'key' => "tool-{$tool['id']}",
                'type' => 'tool',
                'title' => $tool['name'],
                'excerpt' => $tool['description'],
                'route' => $tool['route'],
                'section' => 'Tools',
                'category' => 'Tool',
                'keywords' => $tool['name'].' '.$tool['description'],
                'boost' => 5,
            ];
        }

        return $entries;
    }
}
