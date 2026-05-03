<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Medecin;
use App\Models\Patient;
use App\Models\Service;
use App\Models\RendezVous;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin
        User::create([
            'name' => 'Administrateur',
            'email' => 'admin@hospit.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Medecin User
        $medecinUser = User::create([
            'name' => 'Dr. Ahmed',
            'email' => 'medecin@hospit.com',
            'password' => Hash::make('password'),
            'role' => 'medecin',
        ]);

        // 3. Patient User
        $patientUser = User::create([
            'name' => 'Yassine Mansouri',
            'email' => 'patient@hospit.com',
            'password' => Hash::make('password'),
            'role' => 'patient',
        ]);

        // 4. Services
        $service = Service::create(['nom' => 'Cardiologie', 'code' => 'CARD']);

        // 5. Medecin Profil
        $medecin = Medecin::create([
            'user_id' => $medecinUser->id,
            'service_id' => $service->id,
            'matricule' => 'MED001',
            'nom' => 'Ahmed',
            'prenom' => 'Dr',
            'specialite' => 'Cardiologue',
            'email' => 'medecin@hospit.com',
            'telephone' => '0600000000',
            'date_embauche' => now(),
        ]);

        // 6. Patient Profil
        $patient = Patient::create([
            'user_id' => $patientUser->id,
            'nom' => 'Mansouri',
            'prenom' => 'Yassine',
            'email' => 'patient@hospit.com',
            'telephone' => '0612345678',
            'genre' => 'homme',
            'date_naissance' => '1990-01-01',
            'adresse' => 'Rabat, Maroc',
            'cin' => 'AB123456',
            'numero_dossier' => 'DOS-2026-001'
        ]);

        // 7. Quelques RDV pour aujourd'hui
        RendezVous::create([
            'patient_id' => $patient->id,
            'medecin_id' => $medecin->id,
            'service_id' => $service->id,
            'date_heure_debut' => now()->setHour(10)->setMinute(0),
            'motif' => 'Contrôle tension',
            'statut' => 'confirmé',
            'reference' => 'RDV-001'
        ]);

        RendezVous::create([
            'patient_id' => $patient->id,
            'medecin_id' => $medecin->id,
            'service_id' => $service->id,
            'date_heure_debut' => now()->setHour(14)->setMinute(30),
            'motif' => 'Consultation annuelle',
            'statut' => 'confirmé',
            'reference' => 'RDV-002'
        ]);
    }
}
