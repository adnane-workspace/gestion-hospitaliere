<?php

namespace App\Policies;

use App\Models\RendezVous;
use App\Models\User;

class RendezVousPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isMedecin() || $user->isPatient();
    }

    public function view(User $user, RendezVous $rendezVous): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isMedecin() && $user->medecin) {
            return $rendezVous->medecin_id === $user->medecin->id;
        }

        return $user->isPatient() && $user->patient && $rendezVous->patient_id === $user->patient->id;
    }

    public function create(User $user): bool
    {
        return $user->isPatient() && (bool) $user->patient;
    }
}
