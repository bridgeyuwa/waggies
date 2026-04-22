<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Models\Faq;

class GroomingController extends Controller
{
    public function index()
    {
        $faqs = Faq::active()->ordered()->where('category', 'grooming')->get();

        return view('pages.services.grooming', compact('faqs'));
    }
}
