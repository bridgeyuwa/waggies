<?php

use App\Models\KbArticle;
use App\Models\Post;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

/**
 * Waggies breadcrumbs — trail segments only (home icon is rendered by the component).
 *
 * @param  non-empty-string  $slug
 */
if (! function_exists('breadcrumb_category_label')) {
    function breadcrumb_category_label(string $slug): string
    {
        return ucwords(str_replace('-', ' ', $slug));
    }
}

// ---------------------------------------------------------------------------
// Services
// ---------------------------------------------------------------------------

Breadcrumbs::for('services.index', function (BreadcrumbTrail $trail): void {
    $trail->push('Services', route('services.index'));
});

Breadcrumbs::for('services.boarding.index', function (BreadcrumbTrail $trail): void {
    $trail->push('Services', route('services.index'));
    $trail->push('Boarding', route('services.boarding.index'));
});

Breadcrumbs::for('services.boarding.dogs', function (BreadcrumbTrail $trail): void {
    $trail->parent('services.boarding.index');
    $trail->push('Dog Boarding', route('services.boarding.dogs'));
});

Breadcrumbs::for('services.boarding.cats', function (BreadcrumbTrail $trail): void {
    $trail->parent('services.boarding.index');
    $trail->push('Cat Boarding', route('services.boarding.cats'));
});

Breadcrumbs::for('services.boarding.exotic', function (BreadcrumbTrail $trail): void {
    $trail->parent('services.boarding.index');
    $trail->push('Exotic Pet Boarding', route('services.boarding.exotic'));
});

Breadcrumbs::for('services.grooming', function (BreadcrumbTrail $trail): void {
    $trail->push('Services', route('services.index'));
    $trail->push('Grooming', route('services.grooming'));
});

Breadcrumbs::for('services.vet-care', function (BreadcrumbTrail $trail): void {
    $trail->push('Services', route('services.index'));
    $trail->push('Veterinary Care', route('services.vet-care'));
});

Breadcrumbs::for('services.training', function (BreadcrumbTrail $trail): void {
    $trail->push('Services', route('services.index'));
    $trail->push('Dog Training', route('services.training'));
});

Breadcrumbs::for('services.transport', function (BreadcrumbTrail $trail): void {
    $trail->push('Services', route('services.index'));
    $trail->push('Pet Transport', route('services.transport'));
});

Breadcrumbs::for('services.pricing', function (BreadcrumbTrail $trail): void {
    $trail->push('Services', route('services.index'));
    $trail->push('Pricing', route('services.pricing'));
});

// ---------------------------------------------------------------------------
// Relocation
// ---------------------------------------------------------------------------

Breadcrumbs::for('relocation.index', function (BreadcrumbTrail $trail): void {
    $trail->push('Pet Relocation', route('relocation.index'));
});

Breadcrumbs::for('relocation.import', function (BreadcrumbTrail $trail): void {
    $trail->parent('relocation.index');
    $trail->push('Import to Nigeria', route('relocation.import'));
});

Breadcrumbs::for('relocation.export', function (BreadcrumbTrail $trail): void {
    $trail->parent('relocation.index');
    $trail->push('Export from Nigeria', route('relocation.export'));
});

Breadcrumbs::for('relocation.checklist', function (BreadcrumbTrail $trail): void {
    $trail->parent('relocation.index');
    $trail->push('Relocation Checklist', route('relocation.checklist'));
});

// ---------------------------------------------------------------------------
// About
// ---------------------------------------------------------------------------

Breadcrumbs::for('about.index', function (BreadcrumbTrail $trail): void {
    $trail->push('About', route('about.index'));
});

Breadcrumbs::for('about.testimonials', function (BreadcrumbTrail $trail): void {
    $trail->parent('about.index');
    $trail->push('Testimonials', route('about.testimonials'));
});

Breadcrumbs::for('about.gallery', function (BreadcrumbTrail $trail): void {
    $trail->parent('about.index');
    $trail->push('Gallery', route('about.gallery'));
});

Breadcrumbs::for('about.careers', function (BreadcrumbTrail $trail): void {
    $trail->parent('about.index');
    $trail->push('Careers', route('about.careers'));
});

Breadcrumbs::for('about.partnerships', function (BreadcrumbTrail $trail): void {
    $trail->parent('about.index');
    $trail->push('Partnerships', route('about.partnerships'));
});

// ---------------------------------------------------------------------------
// Blog
// ---------------------------------------------------------------------------

Breadcrumbs::for('blog.index', function (BreadcrumbTrail $trail): void {
    $trail->push('Blog', route('blog.index'));
});

Breadcrumbs::for('blog.category', function (BreadcrumbTrail $trail, string $slug): void {
    $trail->parent('blog.index');
    $trail->push(breadcrumb_category_label($slug), route('blog.category', $slug));
});

Breadcrumbs::for('blog.show', function (BreadcrumbTrail $trail, string $slug): void {
    $post = Post::published()->blog()->where('slug', $slug)->first();

    $trail->parent('blog.index');

    if ($post) {
        $trail->push($post->category_label, route('blog.category', $post->category));
        $trail->push($post->title, route('blog.show', $post->slug));
    }
});

// ---------------------------------------------------------------------------
// Guides
// ---------------------------------------------------------------------------

Breadcrumbs::for('guides.index', function (BreadcrumbTrail $trail): void {
    $trail->push('Guides', route('guides.index'));
});

Breadcrumbs::for('guides.category', function (BreadcrumbTrail $trail, string $slug): void {
    $trail->parent('guides.index');
    $trail->push(breadcrumb_category_label($slug), route('guides.category', $slug));
});

Breadcrumbs::for('guides.show', function (BreadcrumbTrail $trail, string $slug): void {
    $guide = Post::published()->guide()->where('slug', $slug)->first();

    $trail->parent('guides.index');

    if ($guide) {
        $trail->push($guide->category_label, route('guides.category', $guide->category));
        $trail->push($guide->title, route('guides.show', $guide->slug));
    }
});

// ---------------------------------------------------------------------------
// Knowledge base
// ---------------------------------------------------------------------------

Breadcrumbs::for('kb.index', function (BreadcrumbTrail $trail): void {
    $trail->push('Knowledge Base', route('kb.index'));
});

Breadcrumbs::for('kb.category', function (BreadcrumbTrail $trail, string $slug): void {
    $trail->parent('kb.index');
    $trail->push(breadcrumb_category_label($slug), route('kb.category', $slug));
});

Breadcrumbs::for('kb.show', function (BreadcrumbTrail $trail, string $slug): void {
    $article = KbArticle::published()->where('slug', $slug)->first();

    $trail->parent('kb.index');

    if ($article) {
        $trail->push($article->category_label, route('kb.category', $article->category));
        $trail->push($article->title, route('kb.show', $article->slug));
    }
});

// ---------------------------------------------------------------------------
// Other pages
// ---------------------------------------------------------------------------

Breadcrumbs::for('loyalty', function (BreadcrumbTrail $trail): void {
    $trail->push('Loyalty Programme', route('loyalty'));
});

Breadcrumbs::for('faq', function (BreadcrumbTrail $trail): void {
    $trail->push('FAQ', route('faq'));
});

Breadcrumbs::for('contact', function (BreadcrumbTrail $trail): void {
    $trail->push('Contact', route('contact'));
});

Breadcrumbs::for('shop.index', function (BreadcrumbTrail $trail): void {
    $trail->push('Shop', route('shop.index'));
});

Breadcrumbs::for('privacy', function (BreadcrumbTrail $trail): void {
    $trail->push('Privacy Policy', route('privacy'));
});

Breadcrumbs::for('terms', function (BreadcrumbTrail $trail): void {
    $trail->push('Terms of Service', route('terms'));
});

Breadcrumbs::for('cookies', function (BreadcrumbTrail $trail): void {
    $trail->push('Cookies Policy', route('cookies'));
});
