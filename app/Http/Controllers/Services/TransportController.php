<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Models\Faq;

class TransportController extends Controller
{
    public function index()
    {
        $faqs = Faq::active()->ordered()->where('category', 'transport')->get();

        return view('pages.services.transport', compact('faqs'));
    }
}
