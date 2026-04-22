<?php

namespace App\Http\Controllers;

use App\Models\Faq;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::active()->ordered()->get();

        $grouped = $faqs->groupBy('category');

        $categories = $grouped->keys()->map(fn (string $slug) => [
            'slug'  => $slug,
            'label' => ucwords(str_replace('-', ' ', $slug)),
        ])->values();

        return view('pages.faq', compact('grouped', 'categories'));
    }
}
