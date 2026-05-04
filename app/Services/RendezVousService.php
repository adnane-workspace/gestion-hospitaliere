<?php

namespace App\Services;

use App\Models\RendezVous;
use App\Models\MedecinDisponibilite;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Exception;

class RendezVousService
{
    /**
     * Tente de réserver un rendez-vous.
     * 
     * @param array $data Les données validées du RDV
     * @return RendezVous
     * @throws Exception Si le créneau est déjà pris
     */
    public function reserver(array $data): RendezVous
    {
        $debut = Carbon::parse($data['date_heure_debut']);
        $duree = (int) ($data['duree_minutes'] ?? 30);
        $fin = (clone $debut)->addMinutes($duree);

        $medecinId = (int) $data['medecin_id'];
        $patientId = (int) $data['patient_id'];

        if (!$this->medecinEstDisponibleSelonPlanning($medecinId, $debut, $fin)) {
            throw new Exception("Le medecin n'est pas disponible sur ce creneau.");
        }

        // 1. Vérifier si le médecin est disponible
        if ($this->medecinEstOccupe($medecinId, $debut, $fin)) {
            throw new Exception("Le médecin est déjà occupé sur cette plage horaire.");
        }

        // 2. Générer une référence unique
        $data['reference'] = 'RDV-' . date('Y') . '-' . strtoupper(Str::random(6));
        $data['date_heure_fin'] = $fin;
        $data['statut'] = 'planifie';
        $data['medecin_id'] = $medecinId;
        $data['patient_id'] = $patientId;

        // 3. Création en base de données
        return RendezVous::create($data);
    }

    /**
     * Vérifie si un médecin a un chevauchement de RDV.
     */
    private function medecinEstOccupe(int $medecinId, Carbon $debut, Carbon $fin): bool
    {
        return RendezVous::where('medecin_id', $medecinId)
            ->whereIn('statut', ['planifie', 'confirme', 'en_attente', 'en_cours'])
            ->where(function ($query) use ($debut, $fin) {
                $query->where(function ($q) use ($debut, $fin) {
                    // Le nouveau RDV commence pendant un RDV existant
                    $q->where('date_heure_debut', '<', $fin)
                      ->where('date_heure_fin', '>', $debut);
                });
            })
            ->exists();
    }

    private function medecinEstDisponibleSelonPlanning(int $medecinId, Carbon $debut, Carbon $fin): bool
    {
        $jour = (int) $debut->dayOfWeekIso;
        $debutHeure = $debut->format('H:i:s');
        $finHeure = $fin->format('H:i:s');

        $planningExiste = MedecinDisponibilite::where('medecin_id', $medecinId)->exists();
        if (!$planningExiste) {
            return true;
        }

        return MedecinDisponibilite::where('medecin_id', $medecinId)
            ->where('jour_semaine', $jour)
            ->where('is_active', true)
            ->where('heure_debut', '<=', $debutHeure)
            ->where('heure_fin', '>=', $finHeure)
            ->exists();
    }
}
