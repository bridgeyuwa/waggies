<?php

namespace App\Console\Commands;

use App\Models\KbArticle;
use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Generate the XML sitemap for public pages';

    public function handle(): int
    {
        $sitemap = Sitemap::create();

        foreach (RouteFacade::getRoutes() as $route) {
            if (! $this->shouldIncludeRoute($route)) {
                continue;
            }

            $sitemap->add(Url::create(url($route->uri()))
                ->setLastModificationDate(now()));
        }

        Post::published()->blog()->get()->each(function (Post $post) use ($sitemap): void {
            $sitemap->add(Url::create(route('blog.show', $post->slug))
                ->setLastModificationDate($post->updated_at));
        });

        Post::published()->guide()->get()->each(function (Post $post) use ($sitemap): void {
            $sitemap->add(Url::create(route('guides.show', $post->slug))
                ->setLastModificationDate($post->updated_at));
        });

        KbArticle::published()->get()->each(function (KbArticle $article) use ($sitemap): void {
            $sitemap->add(Url::create(route('kb.show', $article->slug))
                ->setLastModificationDate($article->updated_at));
        });

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap written to public/sitemap.xml');

        return self::SUCCESS;
    }

    private function shouldIncludeRoute(Route $route): bool
    {
        if (! in_array('GET', $route->methods(), true)) {
            return false;
        }

        $name = $route->getName();

        if ($name === null) {
            return false;
        }

        if (str_contains($route->uri(), '{')) {
            return false;
        }

        $middleware = $route->gatherMiddleware();

        if (in_array('auth', $middleware, true)) {
            return false;
        }

        return ! str_starts_with($name, 'filament.')
            && ! str_starts_with($name, 'livewire.')
            && ! str_starts_with($name, 'storage.')
            && $name !== 'contact.store'
            && $name !== 'newsletter.subscribe';
    }
}
