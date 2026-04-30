<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRendezVousRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {
        return true; // À adapter selon votre système d'authentification
    }

    /**
     * Règles de validation.
     */
    public function rules(): array
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'medecin_id' => 'required|exists:medecins,id',
            'date_heure_debut' => 'required|date|after:now',
            'duree_minutes' => 'integer|min:10|max:120',
            'motif' => 'required|string|max:255',
            'type_rendez_vous' => 'required|in:premiere_consultation,suivi,urgence,controle,bilan,acte_medical',
        ];
    }

    /**
     * Messages d'erreur personnalisés.
     */
    public function messages(): array
    {
        return [
            'date_heure_debut.after' => 'Le rendez-vous doit être programmé dans le futur.',
            'patient_id.exists' => 'Le patient sélectionné n\'existe pas.',
            'medecin_id.exists' => 'Le médecin sélectionné n\'existe pas.',
        ];
    }
}
