<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Medecin;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_admin_can_access_admin_dashboard()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
    }

    /** @test */
    public function test_doctor_can_access_medecin_dashboard()
    {
        $user = User::factory()->create(['role' => 'medecin']);
        Medecin::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('medecin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('medecin.dashboard');
    }

    /** @test */
    public function test_patient_can_access_patient_dashboard()
    {
        $user = User::factory()->create(['role' => 'patient']);
        Patient::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('patient.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('patient.dashboard');
    }
}
