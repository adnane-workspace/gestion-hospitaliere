<?php

namespace Database\Factories;

use App\Models\Medecin;
use App\Models\Patient;
use App\Models\RendezVous;
use Illuminate\Database\Eloquent\Factories\Factory;

class RendezVousFactory extends Factory
{
    protected $model = RendezVous::class;

    public function definition(): array
    {
        return [
            'reference' => 'RDV-' . strtoupper($this->faker->bothify('??###')),
            'patient_id' => Patient::factory(),
            'medecin_id' => Medecin::factory(),
            'date_heure_debut' => $this->faker->dateTimeBetween('now', '+1 month'),
            'duree_minutes' => 30,
            'statut' => RendezVous::STATUT_PLANIFIE,
            'motif' => $this->faker->sentence,
            'type_rendez_vous' => 'premiere_consultation',
            'canal_prise_rdv' => 'en_ligne',
        ];
    }
}
