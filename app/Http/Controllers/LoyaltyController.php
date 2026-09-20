<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Spatie\SchemaOrg\Schema;

final class LoyaltyController extends Controller
{
    public function __invoke(): View
    {
        $page = config('waggies_loyalty');

        $metadata = [
            'title' => 'Loyalty Programme - Waggies',
            'description' => 'Earn points on every Waggies visit and redeem them for free services, discounts, and exclusive member perks.',
            'canonical' => route('loyalty'),
            'ogTitle' => 'Loyalty Programme - Waggies Pet Care',
            'ogDescription' => 'Earn points on every Waggies visit and redeem them for free services, discounts, and exclusive member perks.',
        ];
        $this->setPageHead($metadata, [Schema::webPage()->name('Loyalty Programme - Waggies Pet Care')->description($metadata['description'])->url($metadata['canonical'])->toArray()]);

        return view('pages.loyalty', $metadata + [
            'navSection' => '',
            'page' => $page,
        ]);
    }
}
