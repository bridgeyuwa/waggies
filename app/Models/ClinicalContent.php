<?php

namespace App\Models;

use App\Enums\ClinicalContentStatus;
use App\Enums\ClinicalContentType;
use App\Enums\ClinicalPublicationStatus;
use App\Enums\ClinicalRiskLevel;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

final class ClinicalContent extends Model
{
    protected $fillable = ['content_key', 'contentable_type', 'contentable_id', 'content_type', 'clinical_status', 'publication_status', 'risk_level', 'jurisdiction', 'version', 'published_at', 'review_due_at', 'withdrawn_at', 'withdrawn_by', 'withdrawal_reason', 'source_conflict', 'conflict_notes', 'metadata'];

    protected function casts(): array
    {
        return ['content_type' => ClinicalContentType::class, 'clinical_status' => ClinicalContentStatus::class, 'publication_status' => ClinicalPublicationStatus::class, 'risk_level' => ClinicalRiskLevel::class, 'published_at' => 'datetime', 'review_due_at' => 'datetime', 'withdrawn_at' => 'datetime', 'source_conflict' => 'boolean', 'metadata' => 'array'];
    }

    protected static function booted(): void
    {
        self::saving(function (self $content): void {
            if ($content->enumValue('content_type') === 'non_clinical') {
                return;
            }

            if ($content->enumValue('clinical_status') === 'approved' && ! $content->reviews()->where('decision', 'approved')->where('content_version', $content->version)->exists()) {
                throw new \LogicException('Clinical content cannot be approved without an approval record for its current version.');
            }

            if ($content->enumValue('publication_status') === 'published' && ! $content->isPubliclyEligible()) {
                throw new \LogicException('Clinical content cannot be published until the clinical publication gate passes.');
            }
        });
    }

    public function contentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function sources(): BelongsToMany
    {
        return $this->belongsToMany(ClinicalSource::class, 'clinical_content_source')->withPivot(['purpose', 'source_version'])->withTimestamps();
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ClinicalReview::class);
    }

    public function versions(): HasMany
    {
        return $this->hasMany(ClinicalContentVersion::class);
    }

    public function withdrawnBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'withdrawn_by');
    }

    public function enumValue(string $attribute): string
    {
        $value = $this->getAttribute($attribute);

        return $value instanceof \BackedEnum ? $value->value : (string) $value;
    }

    public function scopePublic(Builder $query): Builder
    {
        return $query->where('publication_status', 'published')->where('clinical_status', 'approved')->whereNull('withdrawn_at')->where('source_conflict', false)->where(function (Builder $query): void {
            $query->whereNull('review_due_at')->orWhere('review_due_at', '>', now());
        })->whereHas('sources', function (Builder $query): void {
            $query->where('clinical_sources.status', 'active')->where('clinical_sources.has_conflict', false);
        })->whereHas('reviews', function (Builder $query): void {
            $query->where('decision', 'approved')->whereColumn('content_version', 'clinical_contents.version');
        });
    }

    public function isPubliclyEligible(): bool
    {
        if ($this->enumValue('content_type') === 'non_clinical') {
            return true;
        }

        $reviewDueAt = $this->getAttribute('review_due_at');

        return $this->enumValue('publication_status') === 'published' && $this->enumValue('clinical_status') === 'approved' && $this->withdrawn_at === null && ! $this->source_conflict && ($reviewDueAt === null || $reviewDueAt instanceof CarbonInterface && $reviewDueAt->isFuture()) && $this->sources()->where('clinical_sources.status', 'active')->where('clinical_sources.has_conflict', false)->exists() && $this->reviews()->where('decision', 'approved')->where('content_version', $this->version)->exists();
    }

    public function requiresClinicalApproval(): bool
    {
        return $this->enumValue('content_type') !== 'non_clinical';
    }

    public function withdraw(string $reason, ?User $user = null): void
    {
        $this->forceFill(['clinical_status' => 'withdrawn', 'publication_status' => 'unpublished', 'withdrawn_at' => now(), 'withdrawn_by' => $user?->getKey(), 'withdrawal_reason' => $reason])->save();
    }
}
