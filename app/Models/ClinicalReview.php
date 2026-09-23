<?php

namespace App\Models;

use App\Enums\ClinicalReviewDecision;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ClinicalReview extends Model
{
    protected $fillable = ['clinical_content_id', 'clinical_source_id', 'reviewer_id', 'reviewer_name', 'reviewer_credential', 'organization', 'review_reference', 'decision', 'review_notes', 'reviewed_at', 'next_review_at', 'content_version', 'source_version'];

    protected function casts(): array
    {
        return ['decision' => ClinicalReviewDecision::class, 'reviewed_at' => 'datetime', 'next_review_at' => 'datetime'];
    }

    protected static function booted(): void
    {
        self::saving(function (self $review): void {
            $decision = $review->getAttribute('decision');

            if (($decision instanceof \BackedEnum ? $decision->value : (string) $decision) !== 'approved') {
                return;
            }

            $reviewer = $review->reviewer()->first();

            if (! $reviewer instanceof User || $reviewer->is_clinical_reviewer !== true) {
                throw new \LogicException('Only a designated clinical reviewer can approve clinical content.');
            }
        });
    }

    /**
     * @return BelongsTo<ClinicalContent, $this>
     */
    public function content(): BelongsTo
    {
        return $this->belongsTo(ClinicalContent::class, 'clinical_content_id');
    }

    /**
     * @return BelongsTo<ClinicalSource, $this>
     */
    public function source(): BelongsTo
    {
        return $this->belongsTo(ClinicalSource::class, 'clinical_source_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
