<?php

namespace App\Models;

use App\Enums\ClinicalSourceType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class ClinicalSource extends Model
{
    protected $fillable = ['title', 'organization', 'author', 'source_type', 'reference', 'jurisdiction', 'species', 'topic', 'published_on', 'revised_on', 'accessed_at', 'version', 'identifier', 'evidence_level', 'status', 'notes', 'source_hash', 'has_conflict'];

    protected function casts(): array
    {
        return ['source_type' => ClinicalSourceType::class, 'species' => 'array', 'published_on' => 'date', 'revised_on' => 'date', 'accessed_at' => 'datetime', 'has_conflict' => 'boolean'];
    }

    public function clinicalContents(): BelongsToMany
    {
        return $this->belongsToMany(ClinicalContent::class, 'clinical_content_source')->withPivot(['purpose', 'source_version'])->withTimestamps();
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ClinicalReview::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active')->where('has_conflict', false);
    }
}
