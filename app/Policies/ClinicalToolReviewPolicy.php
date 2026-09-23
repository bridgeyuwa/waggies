<?php

namespace App\Policies;

use App\Models\ClinicalToolReview;
use App\Models\User;

final class ClinicalToolReviewPolicy
{
    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, ClinicalToolReview $review): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, ClinicalToolReview $review): bool
    {
        return false;
    }

    public function delete(User $user, ClinicalToolReview $review): bool
    {
        return false;
    }
}
