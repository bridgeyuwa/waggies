<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Post;

class GuidesController extends Controller
{
    public function index()
    {
        $featured = Post::published()->guide()->latest('published_at')->first();

        $guides = Post::published()->guide()
            ->when($featured, fn ($query) => $query->where('id', '!=', $featured->id))
            ->latest('published_at')
            ->paginate(12);

        $categories = Post::published()->guide()
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('pages.guides.index', compact('featured', 'guides', 'categories'));
    }

    public function category(string $slug)
    {
        $featured = Post::published()->guide()->inCategory($slug)->latest('published_at')->first();

        $guides = Post::published()->guide()
            ->inCategory($slug)
            ->when($featured, fn ($query) => $query->where('id', '!=', $featured->id))
            ->latest('published_at')
            ->paginate(12);

        $categories = Post::published()->guide()
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('pages.guides.category', compact('featured', 'guides', 'categories', 'slug'));
    }

    public function show(string $slug)
    {
        $guide = Post::published()->guide()->where('slug', $slug)->firstOrFail();

        $related = Post::published()->guide()
            ->inCategory($guide->category)
            ->where('id', '!=', $guide->id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('pages.guides.show', compact('guide', 'related'));
    }
}
