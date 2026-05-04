<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['admin', 'medecin'], true);
    }

    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:100'],
            'prenom' => ['required', 'string', 'max:100'],
            'telephone' => ['required', 'string', 'max:20'],
            'statut' => ['required', 'in:actif,inactif,decede,transfere'],

            // Données médicales
            'groupe_sanguin' => ['nullable', 'in:A+,A-,B+,B-,AB+,AB-,O+,O-'],
            'allergies' => ['nullable', 'array'],
            'allergies.*' => ['string', 'max:255'],
            'antecedents_medicaux' => ['nullable', 'array'],
            'antecedents_medicaux.*' => ['string', 'max:500'],
            'antecedents_chirurgicaux' => ['nullable', 'array'],
            'antecedents_chirurgicaux.*' => ['string', 'max:500'],
            'maladies_chroniques' => ['nullable', 'array'],
            'maladies_chroniques.*' => ['string', 'max:255'],
            'medicaments_actuels' => ['nullable', 'array'],
            'medicaments_actuels.*' => ['string', 'max:255'],
            'taille' => ['nullable', 'numeric', 'min:50', 'max:250'], // cm
            'poids' => ['nullable', 'numeric', 'min:2', 'max:300'], // kg
            'tension_arterielle' => ['nullable', 'regex:/^\d{2,3}\/\d{2,3}$/'],
            'frequence_cardiaque' => ['nullable', 'integer', 'min:40', 'max:200'],
            'contact_urgence_nom' => ['nullable', 'string', 'max:150'],
            'telephone_urgence' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'taille.min' => 'La taille doit être d\'au moins 50 cm.',
            'taille.max' => 'La taille ne peut pas dépasser 250 cm.',
            'poids.min' => 'Le poids doit être d\'au moins 2 kg.',
            'poids.max' => 'Le poids ne peut pas dépasser 300 kg.',
            'tension_arterielle.regex' => 'Le format de la tension artérielle doit être XXX/XXX (ex: 120/80).',
            'frequence_cardiaque.min' => 'La fréquence cardiaque doit être d\'au moins 40 bpm.',
            'frequence_cardiaque.max' => 'La fréquence cardiaque ne peut pas dépasser 200 bpm.',
        ];
    }
}
