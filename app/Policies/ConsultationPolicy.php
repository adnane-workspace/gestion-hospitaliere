<?php

namespace App\Policies;

use App\Models\Consultation;
use App\Models\RendezVous;
use App\Models\User;

class ConsultationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isMedecin();
    }

    public function view(User $user, Consultation $consultation): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isMedecin() && $user->medecin) {
            return $consultation->medecin_id === $user->medecin->id;
        }

        return $user->isPatient() && $user->patient && $consultation->patient_id === $user->patient->id;
    }

    public function startFromRendezVous(User $user, RendezVous $rendezvous): bool
    {
        return $user->isMedecin()
            && $user->medecin
            && $rendezvous->medecin_id === $user->medecin->id;
    }

    public function exportOwnHistory(User $user): bool
    {
        return $user->isPatient() && (bool) $user->patient;
    }
}
