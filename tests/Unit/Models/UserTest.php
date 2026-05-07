<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_it_can_check_if_user_is_admin()
    {
        $admin = User::factory()->make(['role' => 'admin']);
        $patient = User::factory()->make(['role' => 'patient']);

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($patient->isAdmin());
    }

    /** @test */
    public function test_it_can_check_if_user_is_medecin()
    {
        $medecin = User::factory()->make(['role' => 'medecin']);
        $this->assertTrue($medecin->isMedecin());
    }

    /** @test */
    public function test_it_can_check_if_user_is_patient()
    {
        $patient = User::factory()->make(['role' => 'patient']);
        $this->assertTrue($patient->isPatient());
    }
}
