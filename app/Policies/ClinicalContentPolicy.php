<?php

namespace App\Policies;

use App\Models\ClinicalContent;
use App\Models\User;

final class ClinicalContentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->exists;
    }

    public function view(User $user, ClinicalContent $content): bool
    {
        return $user->exists;
    }

    public function create(User $user): bool
    {
        return $user->exists;
    }

    public function update(User $user, ClinicalContent $content): bool
    {
        return $user->exists;
    }

    public function approve(User $user, ClinicalContent $content): bool
    {
        return $user->is_clinical_reviewer === true;
    }

    public function withdraw(User $user, ClinicalContent $content): bool
    {
        return $user->is_clinical_reviewer === true;
    }
}
