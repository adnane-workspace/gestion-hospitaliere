<?php

namespace App\Services;

use App\Models\Consultation;
use App\Models\RendezVous;
use Exception;

class ConsultationWorkflowService
{
    /**
     * @throws Exception
     */
    public function startFromRendezVous(RendezVous $rendezvous): Consultation
    {
        if (!$rendezvous->patient || !$rendezvous->medecin) {
            throw new Exception('Le rendez-vous est invalide: patient ou medecin manquant.');
        }

        $allowedCurrentStatus = [
            RendezVous::STATUT_PLANIFIE,
            RendezVous::STATUT_CONFIRME,
            RendezVous::STATUT_EN_ATTENTE,
            RendezVous::STATUT_EN_COURS,
        ];

        if (!in_array($rendezvous->statut, $allowedCurrentStatus, true)) {
            throw new Exception('Impossible de demarrer une consultation pour ce statut de rendez-vous.');
        }

        $consultation = Consultation::firstOrCreate(
            ['rendezvous_id' => $rendezvous->id],
            [
                'reference' => 'CONS-' . now()->format('Ymd') . '-' . str_pad((string) $rendezvous->id, 4, '0', STR_PAD_LEFT),
                'patient_id' => $rendezvous->patient_id,
                'medecin_id' => $rendezvous->medecin_id,
                'service_id' => $rendezvous->service_id,
                'date_heure' => $rendezvous->date_heure_debut,
                'motif_consultation' => $rendezvous->motif ?? 'Consultation',
                'diagnostic_principal' => null,
                'statut' => 'en_cours',
            ]
        );

        if ($rendezvous->statut !== RendezVous::STATUT_EN_COURS) {
            if (!$rendezvous->canTransitionTo(RendezVous::STATUT_EN_COURS)) {
                throw new Exception('Transition de statut non autorisee vers en_cours.');
            }
            $rendezvous->update(['statut' => RendezVous::STATUT_EN_COURS]);
        }

        return $consultation;
    }
}
