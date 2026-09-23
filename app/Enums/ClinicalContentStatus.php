<?php

namespace App\Enums;

enum ClinicalContentStatus: string
{
    case Draft = 'draft';
    case PendingReview = 'pending_review';
    case Approved = 'approved';
    case ChangesRequested = 'changes_requested';
    case Deprecated = 'deprecated';
    case Withdrawn = 'withdrawn';
}
