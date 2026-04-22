<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;

class PricingController extends Controller
{
    public function index()
    {
        return view('pages.services.pricing');
    }
}
