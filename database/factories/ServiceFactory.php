<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        return [
            'nom' => $this->faker->word,
            'code' => strtoupper($this->faker->unique()->lexify('????')),
            'description' => $this->faker->sentence,
            'statut' => 'actif',
        ];
    }
}
