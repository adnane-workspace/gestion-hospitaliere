<?php

namespace Tests\Feature;

use App\Models\Medecin;
use App\Models\Patient;
use App\Models\RendezVous;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RendezVousTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * Authentification & Rôles : Vérifie qu'un patient peut accéder à ses rendez-vous.
     */
    public function test_a_patient_can_access_their_appointments()
    {
        // 1. Arrange
        $user = User::factory()->create(['role' => 'patient']);
        $patient = Patient::factory()->create(['user_id' => $user->id]);

        // 2. Act
        $response = $this->actingAs($user)->get(route('patient.rendezvous.index'));

        // 3. Assert
        $response->assertStatus(200);
        $response->assertViewIs('patient.rendezvous.index');
    }

    /**
     * @test
     * Authentification & Rôles : Vérifie qu'un utilisateur non authentifié est redirigé.
     */
    public function test_a_guest_cannot_access_appointments()
    {
        // Act
        $response = $this->get(route('patient.rendezvous.index'));

        // Assert
        $response->assertRedirect(route('login'));
    }

    /**
     * @test
     * Opérations CRUD : Un patient peut prendre un rendez-vous.
     */
    public function test_a_patient_can_create_an_appointment()
    {
        // 1. Arrange
        $user = User::factory()->create(['role' => 'patient']);
        $patient = Patient::factory()->create(['user_id' => $user->id]);
        $medecin = Medecin::factory()->create();

        $data = [
            'medecin_id' => $medecin->id,
            'date_heure_debut' => now()->addDays(1)->format('Y-m-d H:i:s'),
            'duree_minutes' => 30,
            'motif' => 'Consultation de routine',
            'type_rendez_vous' => 'premiere_consultation',
        ];

        // 2. Act
        $response = $this->actingAs($user)->post(route('patient.rendezvous.store'), $data);

        // 3. Assert
        $response->assertRedirect();
        $this->assertDatabaseHas('rendezvous', [
            'patient_id' => $patient->id,
            'medecin_id' => $medecin->id,
            'motif' => 'Consultation de routine',
        ]);
    }

    /**
     * @test
     * Validation : Le système rejette une date dans le passé.
     */
    public function test_an_appointment_cannot_be_scheduled_in_the_past()
    {
        // 1. Arrange
        $user = User::factory()->create(['role' => 'patient']);
        Patient::factory()->create(['user_id' => $user->id]);
        $medecin = Medecin::factory()->create();

        $data = [
            'medecin_id' => $medecin->id,
            'date_heure_debut' => now()->subDays(1)->format('Y-m-d H:i:s'), // Date passée
            'motif' => 'Test validation',
        ];

        // 2. Act
        $response = $this->actingAs($user)->post(route('patient.rendezvous.store'), $data);

        // 3. Assert
        $response->assertSessionHasErrors(['date_heure_debut']);
        $this->assertDatabaseMissing('rendezvous', ['motif' => 'Test validation']);
    }

    /**
     * @test
     * Opérations CRUD : Un médecin peut confirmer un rendez-vous.
     */
    public function test_a_doctor_can_confirm_their_appointment()
    {
        // 1. Arrange
        $doctorUser = User::factory()->create(['role' => 'medecin']);
        $medecin = Medecin::factory()->create(['user_id' => $doctorUser->id]);
        $rendezvous = RendezVous::factory()->create([
            'medecin_id' => $medecin->id,
            'statut' => RendezVous::STATUT_PLANIFIE
        ]);

        // 2. Act
        $response = $this->actingAs($doctorUser)->post(route('medecin.rendezvous.confirm', $rendezvous));

        // 3. Assert
        $response->assertRedirect();
        $this->assertEquals(RendezVous::STATUT_CONFIRME, $rendezvous->fresh()->statut);
    }
}
