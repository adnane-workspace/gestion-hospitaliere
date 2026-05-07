<?php

namespace Database\Factories;

use App\Models\Consultation;
use App\Models\Medecin;
use App\Models\Patient;
use App\Models\RendezVous;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConsultationFactory extends Factory
{
    protected $model = Consultation::class;

    public function definition(): array
    {
        return [
            'reference' => 'CONS-' . $this->faker->unique()->numberBetween(1000, 9999),
            'patient_id' => Patient::factory(),
            'medecin_id' => Medecin::factory(),
            'service_id' => Service::factory(),
            'rendezvous_id' => RendezVous::factory(),
            'date_heure' => now(),
            'motif' => $this->faker->sentence,
            'observations' => $this->faker->paragraph,
            'diagnostic' => $this->faker->sentence,
            'traitement' => $this->faker->sentence,
            'poids' => $this->faker->numberBetween(50, 100),
            'taille' => $this->faker->numberBetween(150, 200),
            'temperature' => $this->faker->randomFloat(1, 36, 40),
            'tension_arterielle' => '120/80',
            'frequence_cardiaque' => 75,
            'statut' => 'complete',
        ];
    }
}
