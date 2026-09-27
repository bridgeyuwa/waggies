<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

final class PricingController extends Controller
{
    public function index(Request $request): View
    {
        $requested = (string) $request->query('service', '');
        $aliases = [
            'boarding-dogs' => ['service' => 'boarding', 'variant' => 'dogs'],
            'boarding-cats' => ['service' => 'boarding', 'variant' => 'cats'],
            'vet' => ['service' => 'vet-care', 'variant' => ''],
        ];
        $resolved = $aliases[$requested] ?? ['service' => $requested, 'variant' => (string) $request->query('variant', '')];

        $metadata = [
            'title' => 'Pricing',
            'description' => 'Indicative pricing guidance for Waggies boarding and veterinary care, plus request paths for custom-quoted relocation in Abuja, Nigeria.',
            'canonical' => route('services.pricing'),
            'ogTitle' => 'Pet Care Pricing Abuja - Waggies',
            'ogDescription' => 'Use the same pricing guidance as the booking request flow, then let Waggies confirm the final quote manually.',
            'robots' => $request->query() === [] ? ['index', 'follow'] : ['noindex', 'follow'],
        ];
        $this->setPageHead($metadata);

        return view('pages.services.pricing', $metadata + [
            'navSection' => 'services', 'pricing' => config('waggies_pricing'), 'resolved' => ['service' => $resolved['service'], 'variant' => $resolved['variant'] ?? ''],
        ]);
    }
}
