<?php

namespace App\Support;

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
            ['key' => 'home', 'type' => 'page', 'title' => 'Home', 'excerpt' => 'Waggies pet boarding, grooming, veterinary care, training, relocation and local transport in Abuja.', 'route' => 'home', 'section' => 'Waggies', 'category' => 'Page', 'keywords' => 'pet care Abuja Nigeria', 'boost' => 10],
            ['key' => 'services', 'type' => 'service', 'title' => 'Services', 'excerpt' => 'Browse Waggies boarding, grooming, vet care, training, relocation and local transport services.', 'route' => 'services.index', 'section' => 'Services', 'category' => 'Service', 'keywords' => 'pet services care', 'boost' => 9],
            ['key' => 'boarding', 'type' => 'service', 'title' => 'Boarding', 'excerpt' => 'Comfortable boarding for dogs, cats and exotic pets with supervision, routines and daily updates.', 'route' => 'services.boarding', 'section' => 'Services', 'category' => 'Service', 'keywords' => 'pet boarding dog cat overnight stay kennel', 'boost' => 10],
            ['key' => 'grooming', 'type' => 'service', 'title' => 'Grooming', 'excerpt' => 'Breed-aware baths, haircuts, nail care and styling using pet-safe products.', 'route' => 'services.grooming', 'section' => 'Services', 'category' => 'Service', 'keywords' => 'dog grooming cat grooming bath haircut', 'boost' => 10],
            ['key' => 'veterinary-care', 'type' => 'service', 'title' => 'Veterinary Care', 'excerpt' => 'Routine wellness consultations, vaccinations and veterinary support for pets.', 'route' => 'services.vet-care', 'section' => 'Services', 'category' => 'Service', 'keywords' => 'vet veterinary vaccination health clinic', 'boost' => 10],
            ['key' => 'training', 'type' => 'service', 'title' => 'Dog Training', 'excerpt' => 'Positive-reinforcement training programmes for puppies and adult dogs.', 'route' => 'services.training', 'section' => 'Services', 'category' => 'Service', 'keywords' => 'dog training puppy behaviour obedience', 'boost' => 8],
            ['key' => 'relocation', 'type' => 'service', 'title' => 'Pet Relocation', 'excerpt' => 'International and domestic pet relocation with documentation and travel coordination.', 'route' => 'services.relocation', 'section' => 'Services', 'category' => 'Service', 'keywords' => 'pet relocation import export travel airport Nigeria', 'boost' => 9],
            ['key' => 'pricing', 'type' => 'page', 'title' => 'Pricing', 'excerpt' => 'Explore Waggies service pricing and request a confirmed quote where needed.', 'route' => 'services.pricing', 'section' => 'Services', 'category' => 'Page', 'keywords' => 'pricing cost rates quote fees', 'boost' => 9],
            ['key' => 'faq', 'type' => 'page', 'title' => 'FAQ', 'excerpt' => 'Answers to common questions about Waggies services, preparation, care and bookings.', 'route' => 'faq', 'section' => 'Support', 'category' => 'Page', 'keywords' => 'questions answers help', 'boost' => 8],
            ['key' => 'contact', 'type' => 'page', 'title' => 'Contact', 'excerpt' => 'Contact Waggies in Abuja for a service request, booking request, quote or general question.', 'route' => 'contact', 'section' => 'Support', 'category' => 'Page', 'keywords' => 'contact phone WhatsApp booking request', 'boost' => 8],
            ['key' => 'careers', 'type' => 'page', 'title' => 'Careers at Waggies', 'excerpt' => 'See current job openings and learn how to apply to join the Waggies team.', 'route' => 'about.careers', 'section' => 'About', 'category' => 'Page', 'keywords' => 'jobs careers vacancies employment', 'boost' => 6],
            ['key' => 'loyalty', 'type' => 'page', 'title' => 'Loyalty Programme', 'excerpt' => 'Learn about Waggies loyalty benefits and ask the team about current eligibility.', 'route' => 'loyalty', 'section' => 'About', 'category' => 'Page', 'keywords' => 'loyalty rewards benefits', 'boost' => 6],
            ['key' => 'shop', 'type' => 'page', 'title' => 'Pet Shop', 'excerpt' => 'Browse pet food, toys, grooming supplies, health products and accessories.', 'route' => 'shop.index', 'section' => 'Shop', 'category' => 'Page', 'keywords' => 'products pet shop supplies food toys', 'boost' => 7],
        ];

        foreach (config('waggies_tools.catalogue', []) as $tool) {
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
