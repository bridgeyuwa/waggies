<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

final class SearchDocument extends Model
{
    use Searchable;

    protected $fillable = [
        'source_type',
        'source_key',
        'title',
        'excerpt',
        'body',
        'url',
        'section',
        'category',
        'keywords',
        'published_at',
        'searchable',
        'boost',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'searchable' => 'boolean',
            'boost' => 'integer',
        ];
    }

    public function searchableAs(): string
    {
        return 'waggies_search_documents';
    }

    public function shouldBeSearchable(): bool
    {
        return $this->searchable;
    }

    /**
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        return [
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'body' => $this->body,
            'section' => $this->section,
            'category' => $this->category,
            'keywords' => $this->keywords,
        ];
    }

    /**
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopePublic(Builder $query): Builder
    {
        return $query->where('searchable', true);
    }

    /**
     * @return array<string, string>
     */
    public function toPublicSearchResult(): array
    {
        return [
            'key' => hash('sha256', "{$this->source_type}|{$this->source_key}"),
            'type' => $this->source_type,
            'title' => $this->title,
            'description' => str($this->excerpt ?: $this->body)
                ->stripTags()
                ->squish()
                ->limit(180)
                ->toString(),
            'href' => $this->url,
            'category' => $this->category ?: $this->section ?: 'Waggies',
        ];
    }
}
