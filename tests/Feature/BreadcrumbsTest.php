<?php

use App\Models\Post;

it('renders breadcrumbs on a service page', function () {
    $this->get(route('services.boarding.dogs'))
        ->assertOk()
        ->assertSee('aria-label="Breadcrumb"', false)
        ->assertSee('Services', false)
        ->assertSee('Boarding', false)
        ->assertSee('Dog Boarding', false);
});

it('renders breadcrumbs before the page hero heading', function () {
    $response = $this->get(route('services.boarding.dogs'))->assertOk();

    $content = $response->getContent();
    $breadcrumbPos = strpos($content, 'aria-label="Breadcrumb"');
    $headingPos = strpos($content, '<h1');

    expect($breadcrumbPos)->not->toBeFalse()
        ->and($headingPos)->not->toBeFalse()
        ->and($breadcrumbPos)->toBeLessThan($headingPos);
});

it('renders breadcrumbs on a blog post', function () {
    $post = Post::factory()->blog()->create([
        'slug' => 'test-breadcrumb-post',
        'category' => 'dog-care',
        'published_at' => now()->subDay(),
    ]);

    $this->get(route('blog.show', $post->slug))
        ->assertOk()
        ->assertSee('Blog', false)
        ->assertSee('Dog Care', false)
        ->assertSee($post->title, false);
});

it('does not render breadcrumbs on the home page', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertDontSee('aria-label="Breadcrumb"', false);
});
