<?php

namespace App\Enums;

enum ClinicalReviewDecision: string
{
    case Approved = 'approved';
    case ChangesRequested = 'changes_requested';
    case Rejected = 'rejected';
}
