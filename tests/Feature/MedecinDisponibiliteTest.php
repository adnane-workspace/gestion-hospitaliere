<?php

namespace Tests\Feature;

use App\Models\Medecin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MedecinDisponibiliteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_a_doctor_can_view_their_availabilities()
    {
        $user = User::factory()->create(['role' => 'medecin']);
        Medecin::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('medecin.disponibilites.index'));

        $response->assertStatus(200);
        $response->assertViewIs('medecin.disponibilites.index');
    }

    /** @test */
    public function test_a_doctor_can_add_availability()
    {
        $user = User::factory()->create(['role' => 'medecin']);
        $medecin = Medecin::factory()->create(['user_id' => $user->id]);

        $data = [
            'jour_semaine' => 1,
            'heure_debut' => '09:00',
            'heure_fin' => '12:00',
            'est_disponible' => 1,
        ];

        $response = $this->actingAs($user)->post(route('medecin.disponibilites.store'), $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('medecin_disponibilites', [
            'medecin_id' => $medecin->id,
            'jour_semaine' => 1,
        ]);
    }
}
