<?php

namespace Database\Factories;

use App\Models\Medecin;
use App\Models\MedecinDisponibilite;
use Illuminate\Database\Eloquent\Factories\Factory;

class MedecinDisponibiliteFactory extends Factory
{
    protected $model = MedecinDisponibilite::class;

    public function definition(): array
    {
        return [
            'medecin_id' => Medecin::factory(),
            'jour_semaine' => $this->faker->numberBetween(1, 7),
            'heure_debut' => '08:00:00',
            'heure_fin' => '17:00:00',
            'est_disponible' => true,
        ];
    }
}
