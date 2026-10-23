<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

final class RobotsController extends Controller
{
    public function __invoke(): Response
    {
        $publicCrawlingRule = app()->environment('production')
            ? "Allow: /\n"
            : "Disallow: /\n";

        return response(
            "User-agent: *\n".$publicCrawlingRule."Disallow: /admin/\nDisallow: /api/\nSitemap: ".route('sitemap')."\n",
            200,
            ['Content-Type' => 'text/plain'],
        );
    }
}
