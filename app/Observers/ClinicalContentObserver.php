<?php

namespace App\Observers;

use App\Models\ClinicalContent;
use App\Support\SearchDocumentSynchronizer;

final class ClinicalContentObserver
{
    public function __construct(private readonly SearchDocumentSynchronizer $synchronizer) {}

    public function saved(ClinicalContent $content): void
    {
        if ($content->contentable !== null) {
            $this->synchronizer->syncModel($content->contentable);
        }
    }

    public function deleted(ClinicalContent $content): void
    {
        if ($content->contentable !== null) {
            $this->synchronizer->syncModel($content->contentable);
        }
    }
}
