<?php

namespace App\Actions;

use App\Enums\ClinicalContentStatus;
use App\Enums\ClinicalReviewDecision;
use App\Models\ClinicalContent;
use App\Models\ClinicalReview;
use App\Models\ClinicalSource;
use App\Models\User;
use App\Support\ClinicalPublicationGate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use LogicException;

final class ClinicalContentWorkflow
{
    public function __construct(private readonly ClinicalPublicationGate $publicationGate) {}

    public function approve(ClinicalContent $content, User $reviewer, ?string $notes = null): ClinicalReview
    {
        $this->assertReviewer($reviewer);
        $source = $this->activeSource($content);

        if ($source === null) {
            throw new LogicException('An active, non-conflicted clinical source is required before approval.');
        }

        return DB::transaction(function () use ($content, $reviewer, $source, $notes): ClinicalReview {
            $review = $this->createReview(
                $content,
                $reviewer,
                $source,
                ClinicalReviewDecision::Approved,
                $notes,
            );

            $content->forceFill([
                'clinical_status' => ClinicalContentStatus::Approved,
            ])->save();

            return $review;
        });
    }

    public function requestChanges(ClinicalContent $content, User $reviewer, string $notes): ClinicalReview
    {
        $this->assertReviewer($reviewer);

        return DB::transaction(function () use ($content, $reviewer, $notes): ClinicalReview {
            $review = $this->createReview(
                $content,
                $reviewer,
                $this->activeSource($content),
                ClinicalReviewDecision::ChangesRequested,
                $notes,
            );

            $content->forceFill([
                'clinical_status' => ClinicalContentStatus::ChangesRequested,
            ])->save();

            return $review;
        });
    }

    public function publish(ClinicalContent $content): void
    {
        $content->publication_status = 'published';
        $blockers = $this->publicationGate->blockers($content);

        if ($blockers !== []) {
            $content->publication_status = 'unpublished';

            throw new LogicException(implode('; ', $blockers).'.');
        }

        $content->forceFill([
            'publication_status' => 'published',
            'published_at' => now(),
        ])->save();
    }

    public function withdraw(ClinicalContent $content, string $reason, User $reviewer): void
    {
        $this->assertReviewer($reviewer);
        $content->withdraw($reason, $reviewer);
    }

    private function assertReviewer(User $reviewer): void
    {
        if ($reviewer->is_clinical_reviewer !== true) {
            throw new LogicException('Only a clinical reviewer may change the clinical workflow.');
        }
    }

    private function activeSource(ClinicalContent $content): ?ClinicalSource
    {
        return ClinicalSource::query()
            ->whereHas('clinicalContents', fn (Builder $query): Builder => $query->whereKey($content->getKey()))
            ->where('status', 'active')
            ->where('has_conflict', false)
            ->first();
    }

    private function createReview(
        ClinicalContent $content,
        User $reviewer,
        ?ClinicalSource $source,
        ClinicalReviewDecision $decision,
        ?string $notes,
    ): ClinicalReview {
        return ClinicalReview::create([
            'clinical_content_id' => $content->getKey(),
            'clinical_source_id' => $source?->getKey(),
            'reviewer_id' => $reviewer->getKey(),
            'reviewer_name' => $reviewer->name,
            'decision' => $decision,
            'review_notes' => $notes,
            'reviewed_at' => now(),
            'next_review_at' => $content->review_due_at,
            'content_version' => $content->version,
            'source_version' => $source?->version,
        ]);
    }
}
