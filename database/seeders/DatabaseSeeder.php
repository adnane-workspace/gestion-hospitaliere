<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Medecin;
use App\Models\Patient;
use App\Models\Service;
use App\Models\RendezVous;
use App\Models\Facture;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin
        User::updateOrCreate(
            ['email' => 'admin@hospit.com'],
            [
                'name' => 'Administrateur',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // 2. Medecin User
        $medecinUser = User::updateOrCreate(
            ['email' => 'medecin@hospit.com'],
            [
                'name' => 'Dr. Ahmed',
                'password' => Hash::make('password'),
                'role' => 'medecin',
                'is_active' => true,
            ]
        );

        // 3. Patient User
        $patientUser = User::updateOrCreate(
            ['email' => 'patient@hospit.com'],
            [
                'name' => 'Yassine Mansouri',
                'password' => Hash::make('password'),
                'role' => 'patient',
                'is_active' => true,
            ]
        );

        // 4. Services
        $service = Service::updateOrCreate(
            ['code' => 'CARD'],
            ['nom' => 'Cardiologie']
        );

        // 5. Medecin Profil
        $medecin = Medecin::updateOrCreate(
            ['user_id' => $medecinUser->id],
            [
                'service_id' => $service->id,
                'matricule' => 'MED001',
                'nom' => 'Ahmed',
                'prenom' => 'Dr',
                'specialite' => 'Cardiologue',
                'email' => 'medecin@hospit.com',
                'telephone' => '0600000000',
                'genre' => 'homme',
                'date_embauche' => now(),
            ]
        );

        // 6. Patient Profil
        $patient = Patient::updateOrCreate(
            ['user_id' => $patientUser->id],
            [
                'nom' => 'Mansouri',
                'prenom' => 'Yassine',
                'email' => 'patient@hospit.com',
                'telephone' => '0612345678',
                'genre' => 'homme',
                'date_naissance' => '1990-01-01',
                'adresse' => 'Rabat, Maroc',
                'cin' => 'AB123456',
                'numero_dossier' => 'DOS-2026-001',
                // Données médicales réalistes
                'groupe_sanguin' => 'A+',
                'allergies' => ['Pénicilline', 'Pollen', 'Arachides'],
                'antecedents_medicaux' => ['Asthme léger depuis l\'enfance', 'Fracture du bras droit en 2015'],
                'antecedents_chirurgicaux' => ['Appendicectomie en 2012'],
                'maladies_chroniques' => ['Hypertension artérielle'],
                'medicaments_actuels' => ['Lisinopril 10mg/jour', 'Ventoline au besoin'],
                'taille' => 175.5,
                'poids' => 78.2,
                'tension_arterielle' => '135/85',
                'frequence_cardiaque' => 72,
                'contact_urgence_nom' => 'Fatima Mansouri',
                'telephone_urgence' => '0623456789',
                'antecedents_familiaux' => ['Diabète chez le père', 'Cancer du sein chez la mère'],
                'mutuelle' => 'CNOPS',
                'numero_mutuelle' => 'CNOPS-123456',
                'type_couverture' => 'cnops',
                'observations_generales' => 'Patient suivi pour hypertension. Bonne observance thérapeutique.',
            ]
        );

        // 7. Quelques RDV pour aujourd'hui
        RendezVous::updateOrCreate(
            ['reference' => 'RDV-001'],
            [
                'patient_id' => $patient->id,
                'medecin_id' => $medecin->id,
                'service_id' => $service->id,
                'date_heure_debut' => now()->setHour(10)->setMinute(0),
                'motif' => 'Controle tension',
                'statut' => 'confirme',
            ]
        );

        RendezVous::updateOrCreate(
            ['reference' => 'RDV-002'],
            [
                'patient_id' => $patient->id,
                'medecin_id' => $medecin->id,
                'service_id' => $service->id,
                'date_heure_debut' => now()->setHour(14)->setMinute(30),
                'motif' => 'Consultation annuelle',
                'statut' => 'confirme',
            ]
        );

        $invoices = [
            [
                'numero_facture' => 'FAC-' . date('Y') . '-00001',
                'date_emission' => now()->subDays(3),
                'date_echeance' => now()->addDays(27),
                'type_facture' => 'consultation',
                'montant_brut' => 420.00,
                'remise_montant' => 20.00,
                'remise_pourcentage' => 5.00,
                'montant_apres_remise' => 400.00,
                'montant_assurance' => 160.00,
                'montant_patient' => 240.00,
                'tva_taux' => 10.00,
                'tva_montant' => 24.00,
                'montant_total_ttc' => 424.00,
                'montant_paye' => 424.00,
                'montant_restant' => 0.00,
                'devise' => 'MAD',
                'statut' => 'payee',
                'mode_paiement' => 'carte_bancaire',
                'date_paiement' => now()->subDays(2),
                'reference_paiement' => 'TRX-20260504-01',
                'organisme_assurance' => 'CNOPS',
                'numero_prise_en_charge' => 'CNOPS-789123',
                'designation_prestations' => 'Consultation cardiologique et ECG',
                'notes' => 'Prise en charge partielle par CNOPS.',
                'imprimee' => true,
                'cree_par' => $medecinUser->id,
                'valide_par' => $medecinUser->id,
                'date_validation' => now()->subDays(2),
                'patient_id' => $patient->id,
                'medecin_id' => $medecin->id,
                'service_id' => $service->id,
            ],
            [
                'numero_facture' => 'FAC-' . date('Y') . '-00002',
                'date_emission' => now()->subDays(8),
                'date_echeance' => now()->subDays(1),
                'type_facture' => 'biologie',
                'montant_brut' => 260.00,
                'remise_montant' => 0.00,
                'remise_pourcentage' => 0.00,
                'montant_apres_remise' => 260.00,
                'montant_assurance' => 0.00,
                'montant_patient' => 286.00,
                'tva_taux' => 10.00,
                'tva_montant' => 26.00,
                'montant_total_ttc' => 286.00,
                'montant_paye' => 0.00,
                'montant_restant' => 286.00,
                'devise' => 'MAD',
                'statut' => 'en_retard',
                'mode_paiement' => 'especes',
                'designation_prestations' => 'Bilan sanguin complet et analyse lipidique',
                'notes' => 'Patient à relancer pour paiement.',
                'imprimee' => false,
                'cree_par' => $medecinUser->id,
                'patient_id' => $patient->id,
                'medecin_id' => $medecin->id,
                'service_id' => $service->id,
            ],
            [
                'numero_facture' => 'FAC-' . date('Y') . '-00003',
                'date_emission' => now()->subDays(10),
                'date_echeance' => now()->addDays(20),
                'type_facture' => 'hospitalisation',
                'montant_brut' => 980.00,
                'remise_montant' => 98.00,
                'remise_pourcentage' => 10.00,
                'montant_apres_remise' => 882.00,
                'montant_assurance' => 400.00,
                'montant_patient' => 482.00,
                'tva_taux' => 10.00,
                'tva_montant' => 48.20,
                'montant_total_ttc' => 930.20,
                'montant_paye' => 241.10,
                'montant_restant' => 689.10,
                'devise' => 'MAD',
                'statut' => 'partiellement_payee',
                'mode_paiement' => 'assurance',
                'designation_prestations' => 'Séjour d’observation et examens de laboratoire',
                'notes' => 'Paiement partiel reçu via assurance.',
                'imprimee' => true,
                'cree_par' => $medecinUser->id,
                'patient_id' => $patient->id,
                'medecin_id' => $medecin->id,
                'service_id' => $service->id,
            ],
        ];

        foreach ($invoices as $invoiceData) {
            Facture::updateOrCreate(
                ['numero_facture' => $invoiceData['numero_facture']],
                $invoiceData
            );
        }
    }
}
