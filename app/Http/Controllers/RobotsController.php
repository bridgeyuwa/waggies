<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

final class RobotsController extends Controller
{
    public function __invoke(): Response
    {
        return response(
            "User-agent: *\nAllow: /\nDisallow: /admin/\nDisallow: /api/\nSitemap: ".route('sitemap')."\n",
            200,
            ['Content-Type' => 'text/plain'],
        );
    }
}
