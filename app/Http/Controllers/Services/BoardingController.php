<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Models\Faq;

class BoardingController extends Controller
{
    public function index()
    {
        $faqs = Faq::active()->ordered()->where('category', 'boarding')->get();

        return view('pages.services.boarding.index', compact('faqs'));
    }

    public function dogs()
    {
        $faqs = Faq::active()->ordered()->where('category', 'boarding')->forSubcategory('dogs')->get();

        return view('pages.services.boarding.dogs', compact('faqs'));
    }

    public function cats()
    {
        $faqs = Faq::active()->ordered()->where('category', 'boarding')->forSubcategory('cats')->get();

        return view('pages.services.boarding.cats', compact('faqs'));
    }

    public function exotic()
    {
        $faqs = Faq::active()->ordered()->where('category', 'boarding')->forSubcategory('exotic')->get();

        return view('pages.services.boarding.exotic', compact('faqs'));
    }
}
