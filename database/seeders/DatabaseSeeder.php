<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Service;
use App\Models\Medecin;
use App\Models\Patient;
use App\Models\RendezVous;
use App\Models\Consultation;
use App\Models\Facture;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Users
        User::create(['name' => 'Admin', 'email' => 'admin@hospit.com', 'password' => Hash::make('password'), 'role' => 'admin']);
        User::create(['name' => 'Dr. Ahmed', 'email' => 'medecin@hospit.com', 'password' => Hash::make('password'), 'role' => 'medecin']);
        User::create(['name' => 'Patient Yassine', 'email' => 'patient@hospit.com', 'password' => Hash::make('password'), 'role' => 'patient']);

        // 2. Services
        $cardio = Service::create(['nom' => 'Cardiologie', 'code' => 'CARD', 'statut' => 'actif']);
        Service::create(['nom' => 'Pédiatrie', 'code' => 'PED', 'statut' => 'actif']);

        // 3. Medecin
        $medecin = Medecin::create([
            'service_id' => $cardio->id,
            'matricule' => 'MED-2024-001',
            'nom' => 'Alami', 'prenom' => 'Ahmed',
            'specialite' => 'Cardiologue',
            'email' => 'ahmed@hospit.com',
            'telephone' => '0661223344',
            'date_embauche' => now(),
            'statut' => 'actif'
        ]);

        // 4. Patient
        $patient = Patient::create([
            'numero_dossier' => 'DOS-2024-001',
            'nom' => 'Mansouri', 'prenom' => 'Yassine',
            'genre' => 'homme',
            'date_naissance' => '1990-05-15',
            'telephone' => '0665443322'
        ]);

        // 5. Historique
        for ($i = 1; $i <= 10; $i++) {
            $date = Carbon::now()->subMonths(11 - $i)->startOfMonth()->addDays(rand(1, 25));
            
            $rdv = RendezVous::create([
                'patient_id' => $patient->id,
                'medecin_id' => $medecin->id,
                'service_id' => $cardio->id,
                'reference' => 'RDV-2024-' . $i,
                'date_heure_debut' => $date,
                'date_heure_fin' => (clone $date)->addMinutes(30),
                'statut' => 'termine',
                'motif' => 'Contrôle',
                'type_rendez_vous' => 'suivi'
            ]);

            $cons = Consultation::create([
                'rendezvous_id' => $rdv->id,
                'patient_id' => $patient->id,
                'medecin_id' => $medecin->id,
                'service_id' => $cardio->id,
                'reference' => 'CONS-2024-' . $i,
                'date_heure' => $date,
                'motif_consultation' => 'Contrôle de routine', // Ajouté
                'diagnostic_principal' => 'Normal',
                'statut' => 'terminee'
            ]);

            Facture::create([
                'consultation_id' => $cons->id,
                'patient_id' => $patient->id,
                'numero_facture' => 'FAC-2024-' . $i,
                'date_emission' => $date,
                'montant_total_ttc' => rand(200, 600),
                'statut' => 'payee'
            ]);
        }
    }
}
