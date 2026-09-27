<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

final class PricingController extends Controller
{
    public function index(Request $request): View
    {
        $requested = (string) $request->query('service', '');
        $aliases = ['boarding-dogs' => ['service' => 'boarding', 'variant' => 'dogs'], 'boarding-cats' => ['service' => 'boarding', 'variant' => 'cats'], 'vet' => ['service' => 'vet-care']];
        $resolved = $aliases[$requested] ?? ['service' => $requested, 'variant' => (string) $request->query('variant', '')];

        $metadata = [
            'title' => 'Pricing',
            'description' => 'Transparent, honest pricing for Waggies boarding, veterinary care, and relocation services in Abuja, Nigeria.',
            'canonical' => route('services.pricing'),
            'ogTitle' => 'Pet Care Pricing Abuja - Waggies',
            'ogDescription' => 'Transparent, honest pricing for Waggies boarding, veterinary care, and relocation services in Abuja, Nigeria.',
            'robots' => $request->query() === [] ? ['index', 'follow'] : ['noindex', 'follow'],
        ];
        $this->setPageHead($metadata);

        return view('pages.services.pricing', $metadata + [
            'navSection' => 'services', 'pricing' => config('waggies_pricing'), 'resolved' => ['service' => $resolved['service'], 'variant' => $resolved['variant'] ?? ''],
        ]);
    }
}
