<?php

namespace App\Http\Controllers\About;

use App\Http\Controllers\Controller;

class AboutController extends Controller
{
    public function index()
    {
        return view('pages.about.index');
    }

    public function testimonials()
    {
        return view('pages.about.testimonials');
    }

    public function gallery()
    {
        return view('pages.about.gallery');
    }

    public function careers()
    {
        return view('pages.about.careers');
    }

    public function partnerships()
    {
        return view('pages.about.partnerships');
    }
}
