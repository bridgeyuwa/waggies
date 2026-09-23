<?php

namespace App\Policies;

use App\Models\ClinicalReview;
use App\Models\User;

final class ClinicalReviewPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_clinical_reviewer === true;
    }

    public function view(User $user, ClinicalReview $review): bool
    {
        return $user->is_clinical_reviewer === true;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, ClinicalReview $review): bool
    {
        return false;
    }

    public function delete(User $user, ClinicalReview $review): bool
    {
        return false;
    }
}
