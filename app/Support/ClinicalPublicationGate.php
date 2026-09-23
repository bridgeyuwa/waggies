<?php

namespace App\Support;

use App\Models\ClinicalContent;
use Carbon\CarbonInterface;

final class ClinicalPublicationGate
{
    /**
     * @return array<int, string>
     */
    public function blockers(ClinicalContent $content): array
    {
        if ($content->enumValue('content_type') === 'non_clinical') {
            return [];
        }

        $blockers = [];

        if ($content->enumValue('clinical_status') !== 'approved') {
            $blockers[] = 'clinical approval is required';
        }

        if ($content->enumValue('publication_status') !== 'published') {
            $blockers[] = 'publication approval is required';
        }

        if ($content->withdrawn_at !== null || $content->enumValue('clinical_status') === 'withdrawn') {
            $blockers[] = 'content is withdrawn';
        }

        if ($content->source_conflict) {
            $blockers[] = 'conflicting sources require resolution';
        }

        if (! $content->sources()->where('clinical_sources.status', 'active')->where('clinical_sources.has_conflict', false)->exists()) {
            $blockers[] = 'an active clinical source is required';
        }

        if (! $content->reviews()->where('decision', 'approved')->where('content_version', $content->version)->exists()) {
            $blockers[] = 'an approval for the current content version is required';
        }

        $reviewDueAt = $content->getAttribute('review_due_at');

        if ($reviewDueAt instanceof CarbonInterface && $reviewDueAt->isPast()) {
            $blockers[] = 'clinical review is overdue';
        }

        return $blockers;
    }

    public function allows(ClinicalContent $content): bool
    {
        return $this->blockers($content) === [];
    }
}
