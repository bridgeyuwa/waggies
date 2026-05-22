<?php

namespace App\Models;

use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Post extends Model
{
    /** @use HasFactory<PostFactory> */
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

    protected function imageUrl(): Attribute
    {
        return Attribute::get(function (): ?string {
            if (empty($this->image)) {
                return null;
            }

            if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
                return $this->image;
            }

            return Storage::disk('public')->url($this->image);
        });
    }

    protected function heroImageUrl(): Attribute
    {
        return Attribute::get(function (): string {
            if ($this->image_url) {
                return $this->image_url;
            }

            $key = $this->type === 'guide' ? 'guide' : 'blog';

            return config("waggies.hero_images.{$key}", config('waggies.hero_images.default'));
        });
    }
}
