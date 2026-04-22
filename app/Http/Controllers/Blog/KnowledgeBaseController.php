<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\KbArticle;

class KnowledgeBaseController extends Controller
{
    public function index()
    {
        $featured = KbArticle::published()->featured()->latest('published_at')->limit(6)->get();

        $categories = KbArticle::published()
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $articles = KbArticle::published()->latest('published_at')->paginate(20);

        return view('pages.kb.index', compact('featured', 'categories', 'articles'));
    }

    public function category(string $slug)
    {
        $articles = KbArticle::published()->inCategory($slug)->latest('published_at')->paginate(20);

        $categories = KbArticle::published()
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('pages.kb.category', compact('articles', 'categories', 'slug'));
    }

    public function show(string $slug)
    {
        $article = KbArticle::published()->where('slug', $slug)->firstOrFail();

        $related = KbArticle::published()
            ->inCategory($article->category)
            ->where('id', '!=', $article->id)
            ->limit(5)
            ->get();

        return view('pages.kb.show', compact('article', 'related'));
    }
}
