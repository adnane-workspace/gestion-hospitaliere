<?php

namespace App\Services;

use App\Models\RendezVous;
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
        $duree = $data['duree_minutes'] ?? 30;
        $fin = (clone $debut)->addMinutes($duree);

        // 1. Vérifier si le médecin est disponible
        if ($this->medecinEstOccupe($data['medecin_id'], $debut, $fin)) {
            throw new Exception("Le médecin est déjà occupé sur cette plage horaire.");
        }

        // 2. Générer une référence unique
        $data['reference'] = 'RDV-' . date('Y') . '-' . strtoupper(Str::random(6));
        $data['date_heure_fin'] = $fin;
        $data['statut'] = 'planifie';

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
}
