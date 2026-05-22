<?php

use App\Models\Post;

it('shows published blog posts', function () {
    $post = Post::factory()->blog()->create([
        'slug' => 'published-post',
        'published_at' => now()->subDay(),
    ]);

    $this->get(route('blog.show', $post->slug))
        ->assertOk()
        ->assertSee($post->title);
});

it('returns 404 for draft blog posts', function () {
    $post = Post::factory()->blog()->draft()->create([
        'slug' => 'draft-post',
    ]);

    $this->get(route('blog.show', $post->slug))
        ->assertNotFound();
});

it('returns 404 for future-dated blog posts', function () {
    $post = Post::factory()->blog()->create([
        'slug' => 'future-post',
        'published_at' => now()->addWeek(),
    ]);

    $this->get(route('blog.show', $post->slug))
        ->assertNotFound();
});
