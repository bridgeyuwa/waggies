<?php

namespace App\Http\Controllers\Relocation;

use App\Http\Controllers\Controller;
use App\Models\Faq;

class RelocationController extends Controller
{
    public function index()
    {
        $faqs = Faq::active()->ordered()->where('category', 'relocation')->get();

        return view('pages.relocation.index', compact('faqs'));
    }

    public function import()
    {
        $faqs = Faq::active()->ordered()->where('category', 'relocation')->forSubcategory('import')->get();

        return view('pages.relocation.import', compact('faqs'));
    }

    public function export()
    {
        $faqs = Faq::active()->ordered()->where('category', 'relocation')->forSubcategory('export')->get();

        return view('pages.relocation.export', compact('faqs'));
    }
}
