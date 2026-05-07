<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_an_admin_can_view_patients_list()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Patient::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get(route('patients.index'));

        $response->assertStatus(200);
        $response->assertViewIs('patients.index');
    }

    /** @test */
    public function test_a_patient_can_view_their_own_profile()
    {
        $user = User::factory()->create(['role' => 'patient']);
        $patient = Patient::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('patient.profile'));

        $response->assertStatus(200);
        $response->assertViewIs('patients.show');
        $response->assertSee($patient->nom);
    }
}
