<?php

namespace App\Http\Controllers;

use App\Support\PublicUrlCatalog;
use Spatie\Sitemap\Sitemap;

final class SitemapController extends Controller
{
    public function __invoke(): Sitemap
    {
        return Sitemap::create()->add((new PublicUrlCatalog)->urls());
    }
}
