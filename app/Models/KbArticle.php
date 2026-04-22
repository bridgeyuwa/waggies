<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class KbArticle extends Model
{
    /** @use HasFactory<\Database\Factories\KbArticleFactory> */
    use HasFactory;

    protected $fillable = [
        'category', 'title', 'slug', 'body', 'featured', 'published_at',
    ];

    protected $casts = [
        'featured'     => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (KbArticle $article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
        });
    }

    // ── Scopes ────────────────────────────────────────────────────

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public function scopeInCategory(Builder $query, string $slug): Builder
    {
        return $query->where('category', $slug);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('featured', true);
    }

    // ── Accessors ─────────────────────────────────────────────────

    public function getCategoryLabelAttribute(): string
    {
        return ucwords(str_replace('-', ' ', $this->category));
    }
}
