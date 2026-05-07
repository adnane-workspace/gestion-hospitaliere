<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Medecin;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_an_admin_can_view_the_list_of_doctors()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)->get(route('admin.medecins.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.medecins.index');
    }

    /** @test */
    public function test_an_admin_can_activate_a_doctor_account()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $doctorUser = User::factory()->create(['role' => 'medecin', 'is_active' => false]);
        
        // Ensure at least one service exists for the auto-creation logic
        Service::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin.medecins.activate', $doctorUser));

        $response->assertRedirect();
        $this->assertTrue($doctorUser->fresh()->is_active);
        $this->assertNotNull($doctorUser->fresh()->medecin);
    }
}
