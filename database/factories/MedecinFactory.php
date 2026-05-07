<?php

namespace Database\Factories;

use App\Models\Medecin;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MedecinFactory extends Factory
{
    protected $model = Medecin::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => 'medecin']),
            'service_id' => Service::factory(),
            'matricule' => $this->faker->unique()->bothify('MED-####'),
            'nom' => $this->faker->lastName,
            'prenom' => $this->faker->firstName,
            'genre' => $this->faker->randomElement(['homme', 'femme']),
            'telephone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'specialite' => $this->faker->word,
            'date_embauche' => now(),
        ];
    }
}
