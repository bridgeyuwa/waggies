<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Support\PricingQuote;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PricingController extends Controller
{
    public function index(Request $request): View
    {
        $resolved = PricingQuote::resolveServiceContext(
            $request->query('service'),
            $request->query('variant'),
        );

        return view('pages.services.pricing', [
            'calculator' => PricingQuote::calculatorPayload(
                $resolved['service'] ?: null,
                $resolved['variant'],
                $request->query('tier'),
            ),
        ]);
    }
}
