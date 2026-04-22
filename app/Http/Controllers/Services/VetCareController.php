<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Models\Faq;

class VetCareController extends Controller
{
    public function index()
    {
        $faqs = Faq::active()->ordered()->where('category', 'vet-care')->get();

        return view('pages.services.vet-care', compact('faqs'));
    }
}
