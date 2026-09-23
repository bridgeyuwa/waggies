<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\View\View;
use LogicException;
use Spatie\SchemaOrg\Schema;

final class ServicesController extends Controller
{
    public function index(): View
    {
        $comparison = $this->serviceComparison();
        $metadata = ['title' => 'Our Services - Waggies', 'description' => 'Pet services in Abuja: boarding, grooming, vet care, training, relocation and local transport.', 'canonical' => route('services.index'), 'ogTitle' => 'Our Services - Waggies Pet Care Abuja', 'ogDescription' => 'Pet services in Abuja: boarding, grooming, vet care, training, relocation and local transport.'];
        $this->setPageHead($metadata, [Schema::webPage()->name('Our Services - Waggies Pet Care Abuja')->description($metadata['description'])->url($metadata['canonical'])->toArray()]);

        return view('pages.services.index', $metadata + [
            'navSection' => 'services',
            'hero' => ['imageSrc' => '/media/editorial/photo-1548199973-03cce0bbc87b.jpg', 'imageAlt' => 'Dog outdoors', 'eyebrow' => 'Everything Your Pet Needs', 'title' => 'Pet Care Services,<br/>All Under One Roof', 'description' => 'From overnight boarding to international relocation - Waggies handles it all from one facility.', 'actions' => [['label' => 'View Our Services', 'url' => route('services.index').'#services']]],
            'cards' => [
                ['title' => 'Boarding', 'description' => 'Spacious, climate-controlled suites with 24/7 supervision and daily photo updates.', 'href' => route('services.boarding'), 'imageSrc' => '/media/editorial/photo-1596492784531-6e6eb5ea9993.jpg', 'imageAlt' => 'Boarding', 'icon' => 'grooming'],
                ['title' => 'Grooming', 'description' => 'Breed-specific cuts, baths, and styling by experienced groomers using pet-safe products.', 'href' => route('services.grooming'), 'imageSrc' => '/media/editorial/photo-1516734212186-a967f81ad0d7.jpg', 'imageAlt' => 'Pet being groomed', 'icon' => 'grooming'],
                ['title' => 'Vet Care', 'description' => 'On-site veterinary consultations, vaccinations, and routine wellness check-ups.', 'href' => route('services.vet-care'), 'imageSrc' => '/media/editorial/photo-1628009368231-7bb7cfcb0def.jpg', 'imageAlt' => 'Veterinarian with dog', 'icon' => 'veterinary-care'],
                ['title' => 'Dog Training', 'description' => 'Positive-reinforcement programmes for puppies and adult dogs of all breeds.', 'href' => route('services.training'), 'imageSrc' => '/media/editorial/photo-1583511655857-d19b40a7a54e.jpg', 'imageAlt' => 'Dog training session', 'icon' => 'training'],
                ['title' => 'Pet Relocation', 'description' => 'International moves with full documentation and airline coordination.', 'route' => 'services.relocation', 'imageSrc' => '/media/editorial/photo-1436491865332-7a61a109cc05.jpg', 'imageAlt' => 'Pet travel', 'icon' => 'airport-departure'],
            ],
            'stats' => [['icon' => 'veterinary-care', 'title' => 'Vet-Supervised Care', 'subtitle' => 'Health-first handling'], ['icon' => 'hours', 'title' => '24/7 Monitoring', 'subtitle' => 'Constant supervision'], ['icon' => 'boarding', 'title' => 'Climate-Controlled Suites', 'subtitle' => 'Comfort in every season'], ['icon' => 'verified', 'title' => 'Safety-First Routine', 'subtitle' => 'Consistent care standards']],
            'standards' => [['title' => 'Structured Daily Routines', 'desc' => 'Professionally trained handlers and protocols.'], ['title' => '24/7 Supervision', 'desc' => 'Continuous monitoring, day and night.'], ['title' => 'Daily Updates', 'desc' => 'Owners receive real-time pet updates.']],
            'comparisonServices' => $comparison['services'], 'comparisonFeatures' => $comparison['features'],
            'faqs' => $this->serviceIndexFaqs(),
        ]);
    }

    public function boarding(): View
    {
        $page = config('waggies_boarding.index');
        $page['hero']['actions'] = $this->normalizeActionLinks($page['hero']['actions'] ?? []);
        $metadata = ['title' => $page['title'], 'description' => $page['description'], 'canonical' => route('services.boarding'), 'ogTitle' => 'Pet Boarding Abuja - Waggies', 'ogDescription' => $page['description']];
        $this->setPageHead($metadata, [$this->serviceSchema('Pet Boarding Abuja - Waggies', $metadata)]);

        return view('pages.services.boarding.index', $metadata + [
            'navSection' => 'services', 'page' => $page,
            'faqs' => $this->publishedFaqs('boarding', onlyGeneral: true),
        ]);
    }

    public function boardingSpecies(string $species): View
    {
        abort_unless(array_key_exists($species, config('waggies_boarding.species')), 404);
        $page = config("waggies_boarding.species.{$species}");
        $page['hero']['actions'] = $this->normalizeActionLinks($page['hero']['actions'] ?? []);
        $faqs = $this->publishedFaqs('boarding', $species);
        $metadata = ['title' => $page['title'], 'description' => $page['description'], 'canonical' => route('services.boarding.species', ['species' => $species]), 'ogTitle' => $page['title'].' Abuja - Waggies', 'ogDescription' => $page['description']];
        $this->setPageHead($metadata, [$this->serviceSchema($page['title'].' Abuja - Waggies', $metadata)]);

        return view('pages.services.boarding.species', $metadata + [
            'navSection' => 'services', 'species' => $species, 'page' => $page,
            'serviceKey' => 'boarding-'.$species, 'tiers' => $this->boardingTiers($species), 'faqs' => $faqs,
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
        $page = $this->withCanonicalPackagePricing($service, config("waggies_service_details.{$service}"));
        $faqs = $this->publishedFaqs($service);
        $metadata = ['title' => $page['meta']['title'].' - Waggies', 'description' => $page['meta']['description'], 'canonical' => route("services.{$service}"), 'ogTitle' => $page['meta']['title'].' - Waggies', 'ogDescription' => $page['meta']['description']];
        $this->setPageHead($metadata, [$this->serviceSchema($metadata['ogTitle'], $metadata)]);

        return view('pages.services.detail', $metadata + [
            'navSection' => 'services', 'service' => $service, 'page' => $page, 'faqs' => $faqs,
        ]);
    }

    private function boardingTiers(string $species): array
    {
        $tiers = config("waggies_pricing.services.boarding.variants.{$species}.tiers", []);

        return collect($tiers)->map(function (array $tier, string $key): array {
            return array_merge($tier, [
                'key' => $key,
                'price' => $this->formatTierPrice($tier),
                'unit' => config('waggies_pricing.services.boarding.unit', '/night'),
                'featured' => $tier['featured'] ?? false,
            ]);
        })->values()->all();
    }

    private function withCanonicalPackagePricing(string $service, array $page): array
    {
        $tiers = config("waggies_pricing.services.{$service}.tiers", []);

        $page['packages'] = array_map(function (array $package) use ($service, $tiers): array {
            if (! isset($package['pricingKey'])) {
                return $package;
            }

            $pricingKey = $package['pricingKey'];
            $tier = $tiers[$pricingKey] ?? null;

            if ($tier === null) {
                throw new LogicException("Missing canonical pricing tier [{$service}.{$pricingKey}].");
            }

            return array_merge($package, ['price' => $this->formatTierPrice($tier)]);
        }, $page['packages']);

        return $page;
    }

    private function serviceComparison(): array
    {
        $comparison = config('waggies.service_comparison');
        $services = config('waggies_pricing.services');

        $comparison['services'] = array_map(function (array $service) use ($services): array {
            $pricing = $service['pricing'];

            if ($pricing['type'] === 'from') {
                $amounts = $this->serviceAmounts($service['key'], $services);
                $service['price'] = 'From ₦'.number_format(min($amounts)).$pricing['unit'];
            } else {
                $service['price'] = $pricing['label'];
            }

            unset($service['pricing']);

            return $service;
        }, $comparison['services']);

        return $comparison;
    }

    private function serviceAmounts(string $service, array $services): array
    {
        $tiers = $service === 'boarding'
            ? collect($services['boarding']['variants'])->flatMap(fn (array $variant): array => $variant['tiers'])->all()
            : ($services[$service]['tiers'] ?? []);
        $amounts = array_values(array_filter(array_map(
            fn (array $tier): ?int => ($tier['type'] ?? 'fixed') === 'quote' ? null : ($tier['amount'] ?? null),
            $tiers,
        ), fn (?int $amount): bool => $amount !== null));

        if ($amounts === []) {
            throw new LogicException("Missing canonical comparison pricing for [{$service}].");
        }

        return $amounts;
    }

    private function formatTierPrice(array $tier): string
    {
        if (($tier['type'] ?? 'fixed') === 'quote') {
            return 'Quote';
        }

        $amount = '₦'.number_format($tier['amount']);

        if (($tier['type'] ?? 'fixed') === 'estimate' && isset($tier['max_amount']) && $tier['max_amount'] !== $tier['amount']) {
            return $amount.' - ₦'.number_format($tier['max_amount']);
        }

        return $amount;
    }

    private function serviceIndexFaqs(): array
    {
        return $this->publishedFaqs('services');
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function publishedFaqs(string $category, ?string $subcategory = null, bool $onlyGeneral = false): array
    {
        $query = Faq::query()
            ->published()
            ->where('category', $category);

        if ($onlyGeneral) {
            $query->whereNull('subcategory');
        } elseif ($subcategory !== null) {
            $query->where(function ($query) use ($subcategory): void {
                $query
                    ->whereNull('subcategory')
                    ->orWhere('subcategory', $subcategory);
            });
        }

        return $query
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (Faq $faq): array => $faq->toPublicArray())
            ->all();
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
