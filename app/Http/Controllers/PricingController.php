<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

final class PricingController extends Controller
{
    public function index(Request $request): View
    {
        $requested = (string) $request->query('service', '');
        $aliases = ['boarding-dogs' => ['service' => 'boarding', 'variant' => 'dogs'], 'boarding-cats' => ['service' => 'boarding', 'variant' => 'cats'], 'boarding-exotic' => ['service' => 'boarding', 'variant' => 'exotic'], 'vet' => ['service' => 'vet-care']];
        $aliases['transport'] = ['service' => 'local-transport', 'variant' => ''];
        $resolved = $aliases[$requested] ?? ['service' => $requested, 'variant' => (string) $request->query('variant', '')];
        if ($requested === 'relocation' && $request->query('tier') === 'local') {
            $resolved = ['service' => 'local-transport', 'variant' => ''];
        }

        $requestedTier = (string) $request->query('tier', '');
        $initialTier = $requested === 'relocation' && $requestedTier === 'local' ? '' : $requestedTier;

        $metadata = [
            'title' => 'Pricing',
            'description' => 'Transparent, honest pricing for Waggies pet boarding, grooming, and vet care services in Abuja, Nigeria. No hidden fees.',
            'canonical' => route('services.pricing'),
            'ogTitle' => 'Pet Care Pricing Abuja - Waggies',
            'ogDescription' => 'Transparent, honest pricing for Waggies pet boarding, grooming, and vet care services in Abuja, Nigeria. No hidden fees.',
            'robots' => $request->query() === [] ? ['index', 'follow'] : ['noindex', 'follow'],
        ];
        $this->setPageHead($metadata);

        return view('pages.services.pricing', $metadata + [
            'navSection' => 'services', 'pricing' => config('waggies_pricing'), 'resolved' => ['service' => $resolved['service'], 'variant' => $resolved['variant'] ?? '', 'tier' => $initialTier],
        ]);
    }
}
