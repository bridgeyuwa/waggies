<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Spatie\SchemaOrg\Schema;

final class ServicesController extends Controller
{
    public function index(): View
    {
        $metadata = ['title' => 'Our Services - Waggies', 'description' => 'Pet services in Abuja: boarding, grooming, vet care, training, relocation and local transport.', 'canonical' => route('services.index'), 'ogTitle' => 'Our Services - Waggies Pet Care Abuja', 'ogDescription' => 'Pet services in Abuja: boarding, grooming, vet care, training, relocation and local transport.'];
        $this->setPageHead($metadata, [Schema::webPage()->name('Our Services - Waggies Pet Care Abuja')->description($metadata['description'])->url($metadata['canonical'])->toArray()]);

        return view('pages.services.index', $metadata + [
            'navSection' => 'services',
            'hero' => ['imageSrc' => 'https://images.unsplash.com/photo-1548199973-03cce0bbc87b?w=1200&h=600&fit=crop&q=80', 'imageAlt' => 'Dog outdoors', 'eyebrow' => 'Everything Your Pet Needs', 'title' => 'Pet Care Services,<br/>All Under One Roof', 'description' => 'From overnight boarding to international relocation - Waggies handles it all from one facility.', 'actions' => [['label' => 'View Our Services', 'href' => route('services.index').'#services']]],
            'cards' => [
                ['title' => 'Boarding', 'description' => 'Spacious, climate-controlled suites with 24/7 supervision and daily photo updates.', 'href' => route('services.boarding'), 'imageSrc' => 'https://images.unsplash.com/photo-1596492784531-6e6eb5ea9993?w=600&h=400&fit=crop&q=80', 'imageAlt' => 'Boarding', 'icon' => 'grooming'],
                ['title' => 'Grooming', 'description' => 'Breed-specific cuts, baths, and styling by experienced groomers using pet-safe products.', 'href' => route('services.grooming'), 'imageSrc' => 'https://images.unsplash.com/photo-1516734212186-a967f81ad0d7?w=600&h=400&fit=crop&q=80', 'imageAlt' => 'Pet being groomed', 'icon' => 'grooming'],
                ['title' => 'Vet Care', 'description' => 'On-site veterinary consultations, vaccinations, and routine wellness check-ups.', 'href' => route('services.vet-care'), 'imageSrc' => 'https://images.unsplash.com/photo-1628009368231-7bb7cfcb0def?w=600&h=400&fit=crop&q=80', 'imageAlt' => 'Veterinarian with dog', 'icon' => 'veterinary-care'],
                ['title' => 'Dog Training', 'description' => 'Positive-reinforcement programmes for puppies and adult dogs of all breeds.', 'href' => route('services.training'), 'imageSrc' => 'https://images.unsplash.com/photo-1583511655857-d19b40a7a54e?w=600&h=400&fit=crop&q=80', 'imageAlt' => 'Dog training session', 'icon' => 'training'],
                ['title' => 'Pet Relocation', 'description' => 'International moves with full documentation and airline coordination.', 'route' => 'services.relocation', 'imageSrc' => 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=600&h=400&fit=crop&q=80', 'imageAlt' => 'Pet travel', 'icon' => 'airport-departure'],
            ],
            'stats' => [['icon' => 'veterinary-care', 'title' => 'Vet-Supervised Care', 'subtitle' => 'Health-first handling'], ['icon' => 'hours', 'title' => '24/7 Monitoring', 'subtitle' => 'Constant supervision'], ['icon' => 'boarding', 'title' => 'Climate-Controlled Suites', 'subtitle' => 'Comfort in every season'], ['icon' => 'verified', 'title' => 'PCSA Certified', 'subtitle' => 'Licensed pet care facility']],
            'standards' => [['title' => 'Structured Daily Routines', 'desc' => 'Professionally trained handlers and protocols.'], ['title' => '24/7 Supervision', 'desc' => 'Continuous monitoring, day and night.'], ['title' => 'Daily Updates', 'desc' => 'Owners receive real-time pet updates.']],
            'faqs' => $this->serviceIndexFaqs(),
        ]);
    }

    public function boarding(): View
    {
        $page = config('waggies_boarding.index');
        $metadata = ['title' => $page['title'], 'description' => $page['description'], 'canonical' => route('services.boarding'), 'ogTitle' => 'Pet Boarding Abuja - Waggies', 'ogDescription' => $page['description']];
        $this->setPageHead($metadata, [$this->serviceSchema('Pet Boarding Abuja - Waggies', $metadata)]);

        return view('pages.services.boarding.index', $metadata + [
            'navSection' => 'services', 'page' => $page,
            'faqs' => array_values(array_filter(config('waggies_faqs'), fn (array $faq): bool => $faq['category'] === 'boarding' && empty($faq['subcategory'] ?? null))),
        ]);
    }

    public function boardingSpecies(string $species): View
    {
        abort_unless(array_key_exists($species, config('waggies_boarding.species')), 404);
        $page = config("waggies_boarding.species.{$species}");
        $faqs = array_values(array_filter(config('waggies_faqs'), fn (array $faq): bool => $faq['category'] === 'boarding' && in_array($faq['subcategory'] ?? null, [null, $species], true)));
        $metadata = ['title' => $page['title'], 'description' => $page['description'], 'canonical' => route('services.boarding.species', ['species' => $species]), 'ogTitle' => $page['title'].' Abuja - Waggies', 'ogDescription' => $page['description']];
        $this->setPageHead($metadata, [$this->serviceSchema($page['title'].' Abuja - Waggies', $metadata)]);

        return view('pages.services.boarding.species', $metadata + [
            'navSection' => 'services', 'species' => $species, 'page' => $page,
            'tiers' => config("waggies_boarding.tiers.{$species}"), 'faqs' => $faqs,
        ]);
    }

    public function grooming(): View
    {
        return $this->serviceDetail('grooming');
    }

    public function training(): View
    {
        return $this->serviceDetail('training');
    }

    public function vetCare(): View
    {
        return $this->serviceDetail('vet-care');
    }

    private function serviceDetail(string $service): View
    {
        $page = config("waggies_service_details.{$service}");
        $faqs = array_values(array_filter(config('waggies_faqs'), fn (array $faq): bool => $faq['category'] === $service));
        $metadata = ['title' => $page['meta']['title'].' - Waggies', 'description' => $page['meta']['description'], 'canonical' => route("services.{$service}"), 'ogTitle' => $page['meta']['title'].' - Waggies', 'ogDescription' => $page['meta']['description']];
        $this->setPageHead($metadata, [$this->serviceSchema($metadata['ogTitle'], $metadata)]);

        return view('pages.services.detail', $metadata + [
            'navSection' => 'services', 'service' => $service, 'page' => $page, 'faqs' => $faqs,
        ]);
    }

    private function serviceIndexFaqs(): array
    {
        return [
            ['question' => 'How do I know which service my pet needs?', 'answer' => "If you're unsure, start with a consultation or contact us. Our team will recommend the right service based on your pet’s age, health, and behavior."],
            ['question' => 'Are all services safe for my pet?', 'answer' => 'Yes. Every service follows strict vet-supervised safety standards and trained handlers. Safety is built into every part of our system.'],
            ['question' => 'Can I switch or combine services later?', 'answer' => 'Yes. Many clients combine grooming, boarding, and vet care depending on their pet’s needs. We can adjust plans anytime.'],
            ['question' => 'Do I need a consultation before booking?', 'answer' => 'Not always. Some services can be booked directly, but consultations help us recommend the safest and most effective care plan.'],
            ['question' => 'How do I get updates about my pet?', 'answer' => 'You receive regular updates including photos and status reports depending on the service you choose.'],
            ['question' => 'What happens after I book a service?', 'answer' => 'Our team contacts you to confirm details, prepare your pet’s care plan, and guide you through the next steps.'],
        ];
    }

    private function serviceSchema(string $name, array $metadata): array
    {
        return Schema::service()
            ->name($name)
            ->description($metadata['description'])
            ->url($metadata['canonical'])
            ->provider(Schema::organization()->name('Waggies')->url(route('home')))
            ->toArray();
    }
}
