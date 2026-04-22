<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

    protected $fillable = [
        'type', 'category', 'title', 'slug', 'excerpt', 'body', 'image', 'author', 'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Post $post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });
    }

    // ── Scopes ────────────────────────────────────────────────────

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public function scopeBlog(Builder $query): Builder
    {
        return $query->where('type', 'blog');
    }

    public function scopeGuide(Builder $query): Builder
    {
        return $query->where('type', 'guide');
    }

    public function scopeInCategory(Builder $query, string $slug): Builder
    {
        return $query->where('category', $slug);
    }

    // ── Accessors ─────────────────────────────────────────────────

    public function getCategoryLabelAttribute(): string
    {
        return ucwords(str_replace('-', ' ', $this->category));
    }

    public function getUrlAttribute(): string
    {
        return $this->type === 'guide'
            ? route('guides.show', $this->slug)
            : route('blog.show', $this->slug);
    }

    public function getReadingTimeAttribute(): int
    {
        $words = str_word_count(strip_tags((string) $this->body));
        return max(1, (int) ceil($words / 200));
    }
}
