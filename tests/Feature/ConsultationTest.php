<?php

namespace Tests\Feature;

use App\Models\Medecin;
use App\Models\Patient;
use App\Models\RendezVous;
use App\Models\User;
use App\Models\Consultation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConsultationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_a_doctor_can_view_their_consultations()
    {
        $user = User::factory()->create(['role' => 'medecin']);
        Medecin::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('medecin.consultations'));

        $response->assertStatus(200);
        $response->assertViewIs('medecin.consultations.index');
    }

    /** @test */
    public function test_a_doctor_can_start_consultation_from_rendezvous()
    {
        $user = User::factory()->create(['role' => 'medecin']);
        $medecin = Medecin::factory()->create(['user_id' => $user->id]);
        $rendezvous = RendezVous::factory()->create([
            'medecin_id' => $medecin->id,
            'statut' => RendezVous::STATUT_PLANIFIE
        ]);

        $response = $this->actingAs($user)->post(route('medecin.consultations.start', $rendezvous));

        $response->assertRedirect();
        $this->assertDatabaseHas('consultations', [
            'rendezvous_id' => $rendezvous->id,
            'medecin_id' => $medecin->id,
        ]);
    }
}
