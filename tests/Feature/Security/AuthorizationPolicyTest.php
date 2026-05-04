<?php

namespace Tests\Feature\Security;

use App\Models\Medecin;
use App\Models\Patient;
use App\Models\RendezVous;
use App\Models\Service;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthorizationPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_cannot_view_another_patient_record(): void
    {
        $this->seed(DatabaseSeeder::class);

        $patientUser = User::where('email', 'patient@hospit.com')->firstOrFail();

        $otherPatientUser = User::create([
            'name' => 'Autre Patient',
            'email' => 'autre.patient@hospit.com',
            'password' => Hash::make('password'),
            'role' => 'patient',
            'is_active' => true,
        ]);

        $otherPatient = Patient::create([
            'user_id' => $otherPatientUser->id,
            'numero_dossier' => 'DOS-SEC-001',
            'nom' => 'Autre',
            'prenom' => 'Patient',
            'genre' => 'homme',
            'date_naissance' => '1991-01-01',
            'telephone' => '0600000001',
        ]);

        $response = $this->actingAs($patientUser)->get(route('patients.show', $otherPatient));

        $response->assertForbidden();
    }

    public function test_patient_cannot_message_admin_role(): void
    {
        $this->seed(DatabaseSeeder::class);

        $patientUser = User::where('email', 'patient@hospit.com')->firstOrFail();
        $adminUser = User::where('email', 'admin@hospit.com')->firstOrFail();

        $response = $this->actingAs($patientUser)->post(route('messages.store'), [
            'receiver_id' => $adminUser->id,
            'contenu' => 'Bonjour admin',
        ]);

        $response->assertSessionHasErrors('receiver_id');
    }

    public function test_medecin_cannot_start_consultation_for_other_medecin_rdv(): void
    {
        $this->seed(DatabaseSeeder::class);

        $service = Service::firstOrFail();

        $otherMedecinUser = User::create([
            'name' => 'Dr Second',
            'email' => 'dr.second@hospit.com',
            'password' => Hash::make('password'),
            'role' => 'medecin',
            'is_active' => true,
        ]);

        $otherMedecin = Medecin::create([
            'user_id' => $otherMedecinUser->id,
            'service_id' => $service->id,
            'matricule' => 'MED999',
            'nom' => 'Second',
            'prenom' => 'Docteur',
            'specialite' => 'Cardiologue',
            'email' => 'dr.second@hospit.com',
            'telephone' => '0600000009',
            'genre' => 'homme',
            'date_embauche' => now(),
        ]);

        $patient = Patient::firstOrFail();

        $rdv = RendezVous::create([
            'patient_id' => $patient->id,
            'medecin_id' => $otherMedecin->id,
            'service_id' => $service->id,
            'reference' => 'RDV-SEC-001',
            'date_heure_debut' => now()->addDay(),
            'date_heure_fin' => now()->addDay()->addMinutes(30),
            'duree_minutes' => 30,
            'statut' => RendezVous::STATUT_CONFIRME,
            'motif' => 'Test policy',
            'type_rendez_vous' => 'suivi',
            'canal_prise_rdv' => 'en_ligne',
        ]);

        $defaultMedecinUser = User::where('email', 'medecin@hospit.com')->firstOrFail();

        $response = $this->actingAs($defaultMedecinUser)
            ->post(route('medecin.consultations.start', $rdv));

        $response->assertForbidden();
    }
}
