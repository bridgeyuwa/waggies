<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Models\Faq;

class TrainingController extends Controller
{
    public function index()
    {
        $faqs = Faq::active()->ordered()->where('category', 'training')->get();

        return view('pages.services.training', compact('faqs'));
    }
}
