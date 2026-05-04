<?php

namespace App\Policies;

use App\Models\Patient;
use App\Models\User;

class PatientPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isMedecin();
    }

    public function view(User $user, Patient $patient): bool
    {
        if ($user->isAdmin() || $user->isMedecin()) {
            return true;
        }

        return $user->isPatient() && $user->patient && $user->patient->id === $patient->id;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isMedecin();
    }

    public function update(User $user, Patient $patient): bool
    {
        return $user->isAdmin() || $user->isMedecin();
    }

    public function delete(User $user, Patient $patient): bool
    {
        return $user->isAdmin();
    }
}
