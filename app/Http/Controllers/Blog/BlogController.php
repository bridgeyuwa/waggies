<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Post;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Post::published()->blog()->latest('published_at')->paginate(12);

        $categories = Post::published()->blog()
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('pages.blog.index', compact('posts', 'categories'));
    }

    public function category(string $slug)
    {
        $posts = Post::published()->blog()->inCategory($slug)->latest('published_at')->paginate(12);

        $categories = Post::published()->blog()
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('pages.blog.category', compact('posts', 'categories', 'slug'));
    }

    public function show(string $slug)
    {
        $post = Post::published()->blog()->where('slug', $slug)->firstOrFail();

        $related = Post::published()->blog()
            ->inCategory($post->category)
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('pages.blog.show', compact('post', 'related'));
    }
}
