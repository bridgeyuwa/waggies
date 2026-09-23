<?php

namespace App\Policies;

use App\Models\ClinicalSource;
use App\Models\User;

final class ClinicalSourcePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_clinical_reviewer === true;
    }

    public function view(User $user, ClinicalSource $source): bool
    {
        return $user->is_clinical_reviewer === true;
    }

    public function create(User $user): bool
    {
        return $user->is_clinical_reviewer === true;
    }

    public function update(User $user, ClinicalSource $source): bool
    {
        return $user->is_clinical_reviewer === true;
    }

    public function delete(User $user, ClinicalSource $source): bool
    {
        return false;
    }
}
