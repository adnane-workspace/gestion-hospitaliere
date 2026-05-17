<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Medecin;
use App\Models\Patient;
use App\Models\Service;
use App\Models\RendezVous;
use App\Models\Consultation;
use App\Models\Facture;
use App\Models\MedecinDisponibilite;
use App\Models\Message;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Désactivation temporaire des clés étrangères pour vider proprement la base SQLite
        Schema::disableForeignKeyConstraints();
        
        // Troncature de toutes les tables
        User::truncate();
        Medecin::truncate();
        Patient::truncate();
        Service::truncate();
        RendezVous::truncate();
        Consultation::truncate();
        Facture::truncate();
        MedecinDisponibilite::truncate();
        Message::truncate();
        DB::table('notifications')->truncate();
        DB::table('ordonnances')->truncate();
        DB::table('lignes_ordonnance')->truncate();
        DB::table('lignes_facture')->truncate();
        
        Schema::enableForeignKeyConstraints();

        // ==========================================
        // 1. CRÉATION DES SERVICES (DÉPARTEMENTS)
        // ==========================================
        $servicesData = [
            ['code' => 'CARD', 'nom' => 'Cardiologie'],
            ['code' => 'PED', 'nom' => 'Pédiatrie'],
            ['code' => 'GYN', 'nom' => 'Gynécologie-Obstétrique'],
            ['code' => 'MEDG', 'nom' => 'Médecine Générale'],
            ['code' => 'DERM', 'nom' => 'Dermatologie'],
            ['code' => 'OPH', 'nom' => 'Ophtalmologie'],
        ];

        $services = [];
        foreach ($servicesData as $serv) {
            $services[$serv['code']] = Service::create($serv);
        }

        // ==========================================
        // 2. CRÉATION DE L'ADMINISTRATEUR
        // ==========================================
        User::create([
            'name' => 'Administrateur Principal',
            'email' => 'admin@hospit.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // ==========================================
        // 3. CRÉATION DES MÉDECINS (COMPTE + PROFIL)
        // ==========================================
        $medecinsData = [
            [
                'email' => 'medecin@hospit.com', // Compte test médecin par défaut
                'name' => 'Dr. Ahmed El Fassi',
                'matricule' => 'MED-2026-0001',
                'prenom' => 'Ahmed',
                'nom' => 'El Fassi',
                'specialite' => 'Cardiologue Spécialisé',
                'telephone' => '0661122334',
                'genre' => 'homme',
                'service_code' => 'CARD',
            ],
            [
                'email' => 'bennani@hospit.com',
                'name' => 'Dr. Amine Bennani',
                'matricule' => 'MED-2026-0002',
                'prenom' => 'Amine',
                'nom' => 'Bennani',
                'specialite' => 'Cardiologue Rythmologue',
                'telephone' => '0661234567',
                'genre' => 'homme',
                'service_code' => 'CARD',
            ],
            [
                'email' => 'elamrani@hospit.com',
                'name' => 'Dr. Leila El Amrani',
                'matricule' => 'MED-2026-0003',
                'prenom' => 'Leila',
                'nom' => 'El Amrani',
                'specialite' => 'Pédiatre Néonatologue',
                'telephone' => '0662345678',
                'genre' => 'femme',
                'service_code' => 'PED',
            ],
            [
                'email' => 'alami@hospit.com',
                'name' => 'Dr. Youssef Alami',
                'matricule' => 'MED-2026-0004',
                'prenom' => 'Youssef',
                'nom' => 'Alami',
                'specialite' => 'Gynécologue Obstétricien',
                'telephone' => '0663456789',
                'genre' => 'homme',
                'service_code' => 'GYN',
            ],
            [
                'email' => 'chraibi@hospit.com',
                'name' => 'Dr. Fatim-Zahra Chraïbi',
                'matricule' => 'MED-2026-0005',
                'prenom' => 'Fatim-Zahra',
                'nom' => 'Chraïbi',
                'specialite' => 'Médecin Généraliste Familial',
                'telephone' => '0664567890',
                'genre' => 'femme',
                'service_code' => 'MEDG',
            ],
            [
                'email' => 'tazi@hospit.com',
                'name' => 'Dr. Mehdi Tazi',
                'matricule' => 'MED-2026-0006',
                'prenom' => 'Mehdi',
                'nom' => 'Tazi',
                'specialite' => 'Dermatologue Esthétique',
                'telephone' => '0665678901',
                'genre' => 'homme',
                'service_code' => 'DERM',
            ]
        ];

        $medecins = [];
        foreach ($medecinsData as $m) {
            $user = User::create([
                'name' => $m['name'],
                'email' => $m['email'],
                'password' => Hash::make('password'),
                'role' => 'medecin',
                'is_active' => true,
            ]);

            $medecin = Medecin::create([
                'user_id' => $user->id,
                'service_id' => $services[$m['service_code']]->id,
                'matricule' => $m['matricule'],
                'prenom' => $m['prenom'],
                'nom' => $m['nom'],
                'specialite' => $m['specialite'],
                'email' => $m['email'],
                'telephone' => $m['telephone'],
                'genre' => $m['genre'],
                'date_embauche' => Carbon::now()->subYears(rand(2, 8)),
            ]);

            $medecins[$m['matricule']] = $medecin;

            // ==========================================
            // 4. PLAGES DE DISPONIBILITÉS HEBDOMADAIRES
            // ==========================================
            // Disponible du lundi (1) au vendredi (5)
            for ($jour = 1; $jour <= 5; $jour++) {
                MedecinDisponibilite::create([
                    'medecin_id' => $medecin->id,
                    'jour_semaine' => $jour,
                    'heure_debut' => '09:00:00',
                    'heure_fin' => '17:00:00',
                    'is_active' => true,
                ]);
            }
        }

        // ==========================================
        // 5. CRÉATION DES PATIENTS (COMPTE + PROFIL)
        // ==========================================
        $patientsData = [
            [
                'email' => 'patient@hospit.com', // Patient test par défaut
                'name' => 'Yassine Mansouri',
                'nom' => 'Mansouri',
                'prenom' => 'Yassine',
                'telephone' => '0612345678',
                'genre' => 'homme',
                'date_naissance' => '1990-05-12',
                'adresse' => '24 Avenue de France, Agdal, Rabat',
                'cin' => 'AB123456',
                'numero_dossier' => 'DOS-2026-0001',
                'groupe_sanguin' => 'A+',
                'allergies' => ['Pénicilline', 'Pollen'],
                'antecedents_medicaux' => ['Appendicectomie en 2012', 'Asthme léger sous traitement'],
                'antecedents_chirurgicaux' => ['Chirurgie de la rotule (gauche) en 2018'],
                'maladies_chroniques' => ['Hypertension artérielle'],
                'medicaments_actuels' => ['Coversyl 5mg (1 comprimé/jour)', 'Ventoline au besoin'],
                'taille' => 178,
                'poids' => 82.5,
                'tension_arterielle' => '120/80',
                'frequence_cardiaque' => 74,
                'contact_urgence_nom' => 'Karima Mansouri (Épouse)',
                'telephone_urgence' => '0623456789',
                'antecedents_familiaux' => ['Père hypertendu', 'Mère diabétique (type 2)'],
                'mutuelle' => 'CNOPS',
                'numero_mutuelle' => 'CN-890123-A',
                'type_couverture' => 'cnops',
                'observations' => 'Patient sérieux dans son suivi thérapeutique.',
                'medecin_traitant_matricule' => 'MED-2026-0001', // Dr. Ahmed El Fassi
            ],
            [
                'email' => 'bourkia@hospit.com',
                'name' => 'Salma Bourkia',
                'nom' => 'Bourkia',
                'prenom' => 'Salma',
                'telephone' => '0671122334',
                'genre' => 'femme',
                'date_naissance' => '1995-11-20',
                'adresse' => 'Rond-point des Sports, Maarif, Casablanca',
                'cin' => 'BE789012',
                'numero_dossier' => 'DOS-2026-0002',
                'groupe_sanguin' => 'O+',
                'allergies' => ['Poussière', 'Aspirine'],
                'antecedents_medicaux' => ['Rhinite allergique', 'Césarienne en 2021'],
                'antecedents_chirurgicaux' => ['Césarienne'],
                'maladies_chroniques' => ['Diabète gestationnel antérieur'],
                'medicaments_actuels' => ['Glucophage 850mg (1/jour)'],
                'taille' => 165,
                'poids' => 64.0,
                'tension_arterielle' => '115/75',
                'frequence_cardiaque' => 68,
                'contact_urgence_nom' => 'Adnane Bourkia (Frère)',
                'telephone_urgence' => '0671155998',
                'antecedents_familiaux' => ['Grand-mère paternelle asthmatique'],
                'mutuelle' => 'CNSS',
                'numero_mutuelle' => 'CS-11223344-9',
                'type_couverture' => 'cnss',
                'observations' => 'Bilan glycémique à réévaluer.',
                'medecin_traitant_matricule' => 'MED-2026-0005', // Dr. Fatim-Zahra
            ],
            [
                'email' => 'haddad@hospit.com',
                'name' => 'Karim Haddad',
                'nom' => 'Haddad',
                'prenom' => 'Karim',
                'telephone' => '0654433221',
                'genre' => 'homme',
                'date_naissance' => '1982-08-04',
                'adresse' => 'Boulevard Mohamed V, Gueliz, Marrakech',
                'cin' => 'HA345678',
                'numero_dossier' => 'DOS-2026-0003',
                'groupe_sanguin' => 'B-',
                'allergies' => ['Lactose'],
                'antecedents_medicaux' => ['Fracture clavicule gauche (2015)'],
                'antecedents_chirurgicaux' => [],
                'maladies_chroniques' => [],
                'medicaments_actuels' => [],
                'taille' => 182,
                'poids' => 90.0,
                'tension_arterielle' => '130/85',
                'frequence_cardiaque' => 80,
                'contact_urgence_nom' => 'Mounir Haddad (Père)',
                'telephone_urgence' => '0654400011',
                'antecedents_familiaux' => ['Oncle paternel décédé d\'infarctus'],
                'mutuelle' => 'AMO',
                'numero_mutuelle' => 'AM-89776655-2',
                'type_couverture' => 'cnss',
                'observations' => 'Conseils d\'hygiène de vie et de sport dispensés.',
                'medecin_traitant_matricule' => 'MED-2026-0002', // Dr. Amine Bennani
            ],
            [
                'email' => 'bensouda@hospit.com',
                'name' => 'Amina Bensouda',
                'nom' => 'Bensouda',
                'prenom' => 'Amina',
                'telephone' => '0667788990',
                'genre' => 'femme',
                'date_naissance' => '1965-03-30',
                'adresse' => 'Résidence Yasmina, Route d\'Imouzzer, Fès',
                'cin' => 'CD901234',
                'numero_dossier' => 'DOS-2026-0004',
                'groupe_sanguin' => 'AB+',
                'allergies' => ['Produits de contraste iodés'],
                'antecedents_medicaux' => ['Cholécystectomie en 2008', 'Hypercholestérolémie'],
                'antecedents_chirurgicaux' => ['Cholécystectomie'],
                'maladies_chroniques' => ['Hypothyroïdie', 'Hypertension artérielle'],
                'medicaments_actuels' => ['Lévothyrox 75µg (1/jour)', 'Lisinopril 10mg'],
                'taille' => 160,
                'poids' => 70.0,
                'tension_arterielle' => '142/90',
                'frequence_cardiaque' => 76,
                'contact_urgence_nom' => 'Youssef Bensouda (Époux)',
                'telephone_urgence' => '0667700112',
                'antecedents_familiaux' => ['Mère atteinte d\'ostéoporose'],
                'mutuelle' => 'Wafa Assurance',
                'numero_mutuelle' => 'WF-998877-B',
                'type_couverture' => 'assurance_privee',
                'observations' => 'Tension à surveiller étroitement.',
                'medecin_traitant_matricule' => 'MED-2026-0001', // Dr. Ahmed El Fassi
            ],
            [
                'email' => 'glaoui@hospit.com',
                'name' => 'Reda El Glaoui',
                'nom' => 'El Glaoui',
                'prenom' => 'Reda',
                'telephone' => '0701234567',
                'genre' => 'homme',
                'date_naissance' => '2001-09-15',
                'adresse' => 'Avenue de la Ligue Arabe, Malabata, Tanger',
                'cin' => 'KB567890',
                'numero_dossier' => 'DOS-2026-0005',
                'groupe_sanguin' => 'O-',
                'allergies' => ['Plumes', 'Arachides'],
                'antecedents_medicaux' => ['Entorse cheville droite en 2022'],
                'antecedents_chirurgicaux' => [],
                'maladies_chroniques' => [],
                'medicaments_actuels' => [],
                'taille' => 176,
                'poids' => 69.2,
                'tension_arterielle' => '118/76',
                'frequence_cardiaque' => 70,
                'contact_urgence_nom' => 'Nadia El Glaoui (Mère)',
                'telephone_urgence' => '0701200000',
                'antecedents_familiaux' => ['Père asthmatique'],
                'mutuelle' => 'AXA Assurance',
                'numero_mutuelle' => 'AX-554433-X',
                'type_couverture' => 'assurance_privee',
                'observations' => 'Étudiant. Examen clinique général normal.',
                'medecin_traitant_matricule' => 'MED-2026-0006', // Dr. Mehdi Tazi
            ],
            [
                'email' => 'filali@hospit.com',
                'name' => 'Kenza Filali',
                'nom' => 'Filali',
                'prenom' => 'Kenza',
                'telephone' => '0669988776',
                'genre' => 'femme',
                'date_naissance' => '2018-05-05', // Enfant pour la pédiatrie
                'adresse' => 'Lotissement Al Qods, Hay Riad, Rabat',
                'cin' => 'AC456123',
                'numero_dossier' => 'DOS-2026-0006',
                'groupe_sanguin' => 'A-',
                'allergies' => ['Pénicilline', 'Fraises'],
                'antecedents_medicaux' => ['Varicelle en 2023'],
                'antecedents_chirurgicaux' => [],
                'maladies_chroniques' => [],
                'medicaments_actuels' => [],
                'taille' => 118,
                'poids' => 22.0,
                'tension_arterielle' => '95/60',
                'frequence_cardiaque' => 95,
                'contact_urgence_nom' => 'Leila Filali (Mère)',
                'telephone_urgence' => '0669900011',
                'antecedents_familiaux' => ['Diabète chez la tante maternelle'],
                'mutuelle' => 'CNOPS',
                'numero_mutuelle' => 'CN-334455-P',
                'type_couverture' => 'cnops',
                'observations' => 'Vaccination à jour. Croissance staturo-pondérale régulière.',
                'medecin_traitant_matricule' => 'MED-2026-0003', // Dr. Leila El Amrani (Pédiatre)
            ]
        ];

        $patients = [];
        foreach ($patientsData as $p) {
            $user = User::create([
                'name' => $p['name'],
                'email' => $p['email'],
                'password' => Hash::make('password'),
                'role' => 'patient',
                'is_active' => true,
            ]);

            $medTraitant = $medecins[$p['medecin_traitant_matricule']] ?? null;

            $patient = Patient::create([
                'user_id' => $user->id,
                'medecin_traitant_id' => $medTraitant ? $medTraitant->id : null,
                'nom' => $p['nom'],
                'prenom' => $p['prenom'],
                'email' => $p['email'],
                'telephone' => $p['telephone'],
                'genre' => $p['genre'],
                'date_naissance' => $p['date_naissance'],
                'adresse' => $p['adresse'],
                'cin' => $p['cin'],
                'numero_dossier' => $p['numero_dossier'],
                'groupe_sanguin' => $p['groupe_sanguin'],
                'allergies' => $p['allergies'],
                'antecedents_medicaux' => $p['antecedents_medicaux'],
                'antecedents_chirurgicaux' => $p['antecedents_chirurgicaux'],
                'maladies_chroniques' => $p['maladies_chroniques'],
                'medicaments_actuels' => $p['medicaments_actuels'],
                'taille' => $p['taille'],
                'poids' => $p['poids'],
                'tension_arterielle' => $p['tension_arterielle'],
                'frequence_cardiaque' => $p['frequence_cardiaque'],
                'contact_urgence_nom' => $p['contact_urgence_nom'],
                'telephone_urgence' => $p['telephone_urgence'],
                'antecedents_familiaux' => $p['antecedents_familiaux'],
                'mutuelle' => $p['mutuelle'],
                'numero_mutuelle' => $p['numero_mutuelle'],
                'type_couverture' => $p['type_couverture'],
                'observations_generales' => $p['observations'],
                'date_admission' => Carbon::now()->subMonths(rand(3, 12)),
            ]);

            $patients[$p['numero_dossier']] = $patient;
        }

        // ==========================================
        // 6. CRÉATION DES RENDEZ-VOUS (PLANIFIÉS / TERMINÉS)
        // ==========================================
        // On va créer des RDV à des dates différentes : passées (pour consultations), présentes et futures.
        $rdvs = [
            // RDV 1 : Terminé - Yassine Mansouri avec Dr. Ahmed El Fassi
            [
                'patient' => 'DOS-2026-0001',
                'medecin' => 'MED-2026-0001',
                'service' => 'CARD',
                'reference' => 'RDV-2026-0001',
                'debut' => Carbon::now()->subDays(10)->setHour(10)->setMinute(0),
                'fin' => Carbon::now()->subDays(10)->setHour(10)->setMinute(30),
                'motif' => 'Contrôle tension artérielle et fatigue passagère',
                'statut' => 'termine',
                'type' => 'suivi',
                'canal' => 'presentiel',
            ],
            // RDV 2 : Terminé - Salma Bourkia avec Dr. Fatim-Zahra Chraïbi
            [
                'patient' => 'DOS-2026-0002',
                'medecin' => 'MED-2026-0005',
                'service' => 'MEDG',
                'reference' => 'RDV-2026-0002',
                'debut' => Carbon::now()->subDays(5)->setHour(14)->setMinute(0),
                'fin' => Carbon::now()->subDays(5)->setHour(14)->setMinute(30),
                'motif' => 'Symptômes de grippe, fièvre élevée et céphalées',
                'statut' => 'termine',
                'type' => 'premiere_consultation',
                'canal' => 'telephone',
            ],
            // RDV 3 : Terminé - Kenza Filali (enfant) avec Dr. Leila El Amrani
            [
                'patient' => 'DOS-2026-0006',
                'medecin' => 'MED-2026-0003',
                'service' => 'PED',
                'reference' => 'RDV-2026-0003',
                'debut' => Carbon::now()->subDays(2)->setHour(11)->setMinute(0),
                'fin' => Carbon::now()->subDays(2)->setHour(11)->setMinute(30),
                'motif' => 'Vaccination obligatoire 6 ans et contrôle croissance',
                'statut' => 'termine',
                'type' => 'suivi',
                'canal' => 'presentiel',
            ],
            // RDV 4 : Aujourd'hui (En cours) - Yassine Mansouri avec Dr. Ahmed El Fassi
            [
                'patient' => 'DOS-2026-0001',
                'medecin' => 'MED-2026-0001',
                'service' => 'CARD',
                'reference' => 'RDV-2026-0004',
                'debut' => Carbon::now()->setHour(10)->setMinute(0),
                'fin' => Carbon::now()->setHour(10)->setMinute(30),
                'motif' => 'Suivi ECG suite à la modification du traitement',
                'statut' => 'en_cours',
                'type' => 'suivi',
                'canal' => 'presentiel',
            ],
            // RDV 5 : Aujourd'hui (Confirmé) - Amina Bensouda avec Dr. Ahmed El Fassi
            [
                'patient' => 'DOS-2026-0004',
                'medecin' => 'MED-2026-0001',
                'service' => 'CARD',
                'reference' => 'RDV-2026-0005',
                'debut' => Carbon::now()->setHour(15)->setMinute(30),
                'fin' => Carbon::now()->setHour(16)->setMinute(0),
                'motif' => 'Palpitations inhabituelles et oppression thoracique légère',
                'statut' => 'confirme',
                'type' => 'urgence',
                'canal' => 'presentiel',
            ],
            // RDV 6 : Demain (Planifié) - Karim Haddad avec Dr. Amine Bennani
            [
                'patient' => 'DOS-2026-0003',
                'medecin' => 'MED-2026-0002',
                'service' => 'CARD',
                'reference' => 'RDV-2026-0006',
                'debut' => Carbon::now()->addDay()->setHour(9)->setMinute(30),
                'fin' => Carbon::now()->addDay()->setHour(10)->setMinute(0),
                'motif' => 'Bilan cardiologique de routine',
                'statut' => 'planifie',
                'type' => 'bilan',
                'canal' => 'en_ligne',
            ],
            // RDV 7 : Dans 3 jours (Planifié) - Reda El Glaoui avec Dr. Mehdi Tazi
            [
                'patient' => 'DOS-2026-0005',
                'medecin' => 'MED-2026-0006',
                'service' => 'DERM',
                'reference' => 'RDV-2026-0007',
                'debut' => Carbon::now()->addDays(3)->setHour(16)->setMinute(0),
                'fin' => Carbon::now()->addDays(3)->setHour(16)->setMinute(30),
                'motif' => 'Consultation acné sévère et conseils soins',
                'statut' => 'planifie',
                'type' => 'suivi',
                'canal' => 'en_ligne',
            ],
            // RDV 8 : Passé (Annulé) - Karim Haddad avec Dr. Mehdi Tazi
            [
                'patient' => 'DOS-2026-0003',
                'medecin' => 'MED-2026-0006',
                'service' => 'DERM',
                'reference' => 'RDV-2026-0008',
                'debut' => Carbon::now()->subDays(15)->setHour(15)->setMinute(0),
                'fin' => Carbon::now()->subDays(15)->setHour(15)->setMinute(30),
                'motif' => 'Éruption cutanée inexpliquée',
                'statut' => 'annule',
                'type' => 'suivi',
                'canal' => 'presentiel',
            ]
        ];

        $createdRdvs = [];
        foreach ($rdvs as $r) {
            $p = $patients[$r['patient']];
            $m = $medecins[$r['medecin']];
            $s = $services[$r['service']];

            $rdv = RendezVous::create([
                'patient_id' => $p->id,
                'medecin_id' => $m->id,
                'service_id' => $s->id,
                'reference' => $r['reference'],
                'date_heure_debut' => $r['debut'],
                'date_heure_fin' => $r['fin'],
                'duree_minutes' => 30,
                'statut' => $r['statut'],
                'motif' => $r['motif'],
                'type_rendez_vous' => $r['type'],
                'canal_prise_rdv' => $r['canal'],
            ]);

            $createdRdvs[$r['reference']] = $rdv;
        }

        // ==========================================
        // 7. CRÉATION DES CONSULTATIONS ASSOCIÉES
        // ==========================================
        // Consultation 1: Pour le RDV 1 (Terminé, Yassine Mansouri avec Dr. Ahmed El Fassi)
        $c1 = Consultation::create([
            'rendezvous_id' => $createdRdvs['RDV-2026-0001']->id,
            'patient_id' => $patients['DOS-2026-0001']->id,
            'medecin_id' => $medecins['MED-2026-0001']->id,
            'service_id' => $services['CARD']->id,
            'reference' => 'CONS-2026-0001',
            'date_heure' => $createdRdvs['RDV-2026-0001']->date_heure_debut,
            'duree_reelle_minutes' => 25,
            'motif_consultation' => $createdRdvs['RDV-2026-0001']->motif,
            'histoire_maladie' => 'Patient de 36 ans suivi pour HTA essentielle sous Coversyl 5mg. Signale des pics de fatigue intermittents en fin de journée depuis 2 semaines.',
            'symptomes' => 'Fatigue, sensation de lourdeur nucale occasionnelle.',
            'examen_clinique' => 'Constantes hémodynamiques correctes. Auscultation cardiaque normale, pas de bruits surajoutés. Pouls périphériques bien perçus. Pas d\'œdème des membres inférieurs.',
            'temperature' => 36.6,
            'tension_arterielle' => '135/85',
            'frequence_cardiaque' => 72,
            'frequence_respiratoire' => 16,
            'saturation_oxygene' => 98.0,
            'poids_consultation' => 82.5,
            'taille_consultation' => 178,
            'imc' => round(82.5 / ((178/100) * (178/100)), 2),
            'diagnostic_principal' => 'Hypertension artérielle essentielle modérée',
            'diagnostics_secondaires' => 'Fatigue passagère liée au stress professionnel',
            'code_cim10' => 'I10', // Hypertension
            'examens_demandes' => 'Bilan biologique (créatininémie, ionogramme sanguin, glycémie à jeun, lipidogramme).',
            'resultats_examens' => 'Bilan biologique équilibré. Urée et créatinine normales.',
            'traitement_prescrit' => 'Continuer Coversyl 5mg. Ajouter un magnésium marin (1 comprimé/jour pendant 1 mois).',
            'recommandations' => 'Poursuivre un régime pauvre en sel. Pratiquer une activité physique régulière (marche rapide de 30 minutes, 3 fois par semaine). Éviter le surmenage.',
            'notes_medecin' => 'Patient très coopératif.',
            'certificat_medical' => false,
            'arret_travail' => false,
            'suivi_requis' => true,
            'delai_suivi_jours' => 30,
            'instructions_suivi' => 'Prendre rendez-vous dans un mois pour réévaluation clinique et lecture du bilan complet.',
            'statut' => 'terminee',
        ]);

        // Consultation 2: Pour le RDV 2 (Terminé, Salma Bourkia avec Dr. Fatim-Zahra Chraïbi)
        $c2 = Consultation::create([
            'rendezvous_id' => $createdRdvs['RDV-2026-0002']->id,
            'patient_id' => $patients['DOS-2026-0002']->id,
            'medecin_id' => $medecins['MED-2026-0005']->id,
            'service_id' => $services['MEDG']->id,
            'reference' => 'CONS-2026-0002',
            'date_heure' => $createdRdvs['RDV-2026-0002']->date_heure_debut,
            'duree_reelle_minutes' => 20,
            'motif_consultation' => $createdRdvs['RDV-2026-0002']->motif,
            'histoire_maladie' => 'Apparition brutale d\'un syndrome grippal avec fièvre à 39°C, frissons, courbatures sévères et toux sèche depuis 48 heures.',
            'symptomes' => 'Fièvre, frissons, céphalées intenses, douleurs musculaires diffuses et gorge irritée.',
            'examen_clinique' => 'Orpharynx congestif sans exsudat. Aires ganglionnaires cervicales libres. Auscultation pulmonaire normale (murmure vésiculaire bien perçu). Reste de l\'examen sans particularité.',
            'temperature' => 38.9,
            'tension_arterielle' => '110/70',
            'frequence_cardiaque' => 92,
            'frequence_respiratoire' => 18,
            'saturation_oxygene' => 97.0,
            'poids_consultation' => 64.0,
            'taille_consultation' => 165,
            'imc' => round(64.0 / ((165/100) * (165/100)), 2),
            'diagnostic_principal' => 'Grippe saisonnière (influenza)',
            'diagnostics_secondaires' => 'Déshydratation légère',
            'code_cim10' => 'J11', // Grippe
            'examens_demandes' => null,
            'resultats_examens' => null,
            'traitement_prescrit' => 'Doliprane 1g (1 comprimé toutes les 6 heures si fièvre/douleurs). Sirop antitussif (Clarix ou similaire, 3 cuillères à soupe/jour si toux gênante). Vitamine C 1g.',
            'recommandations' => 'Repos strict à la maison pendant 3 jours. Hydratation abondante (eau, tisanes). Aérer la chambre régulièrement. Port de masque en présence de proches.',
            'notes_medecin' => 'Arrêt de travail de 3 jours prescrit.',
            'certificat_medical' => true,
            'arret_travail' => true,
            'duree_arret_travail_jours' => 3,
            'debut_arret_travail' => $createdRdvs['RDV-2026-0002']->date_heure_debut->toDateString(),
            'suivi_requis' => false,
            'statut' => 'terminee',
        ]);

        // Consultation 3: Pour le RDV 3 (Terminé, Kenza Filali avec Dr. Leila El Amrani)
        $c3 = Consultation::create([
            'rendezvous_id' => $createdRdvs['RDV-2026-0003']->id,
            'patient_id' => $patients['DOS-2026-0006']->id,
            'medecin_id' => $medecins['MED-2026-0003']->id,
            'service_id' => $services['PED']->id,
            'reference' => 'CONS-2026-0003',
            'date_heure' => $createdRdvs['RDV-2026-0003']->date_heure_debut,
            'duree_reelle_minutes' => 30,
            'motif_consultation' => $createdRdvs['RDV-2026-0003']->motif,
            'histoire_maladie' => 'Visite systématique de pédiatrie pour la vaccination des 6 ans et examen morphologique général.',
            'symptomes' => 'Aucun symptôme. Enfant en excellente forme apparente.',
            'examen_clinique' => 'Développement psychomoteur normal. Examen neurologique, ORL et cardio-pulmonaire strictement normal. Examen de la marche normal. Dentition saine.',
            'temperature' => 36.7,
            'tension_arterielle' => '95/60',
            'frequence_cardiaque' => 95,
            'frequence_respiratoire' => 22,
            'saturation_oxygene' => 99.0,
            'poids_consultation' => 22.0,
            'taille_consultation' => 118,
            'imc' => round(22.0 / ((118/100) * (118/100)), 2),
            'diagnostic_principal' => 'Examen médical général périodique de l\'enfant',
            'diagnostics_secondaires' => 'Vaccination à jour',
            'code_cim10' => 'Z00.1', // Examen de santé de routine de l'enfant
            'examens_demandes' => null,
            'resultats_examens' => null,
            'traitement_prescrit' => 'Vaccin DTCaP (Dhtérie, Tétanos, Coqueluche, Poliomyélite) administré ce jour dans le deltoïde gauche (Lot VAX8902).',
            'recommandations' => 'Application de compresse froide en cas de légère rougeur locale. Donner du paracétamol pédiatrique en cas de fébricule (fièvre modérée) ou douleur au point d\'injection.',
            'notes_medecin' => 'Enfant tonique et éveillé.',
            'certificat_medical' => false,
            'arret_travail' => false,
            'suivi_requis' => true,
            'delai_suivi_jours' => 180,
            'instructions_suivi' => 'Prochaine visite systématique dans 6 mois.',
            'statut' => 'terminee',
        ]);

        // Consultation 4: Pour le RDV 4 (En cours, Yassine Mansouri avec Dr. Ahmed El Fassi)
        $c4 = Consultation::create([
            'rendezvous_id' => $createdRdvs['RDV-2026-0004']->id,
            'patient_id' => $patients['DOS-2026-0001']->id,
            'medecin_id' => $medecins['MED-2026-0001']->id,
            'service_id' => $services['CARD']->id,
            'reference' => 'CONS-2026-0004',
            'date_heure' => $createdRdvs['RDV-2026-0004']->date_heure_debut,
            'duree_reelle_minutes' => null,
            'motif_consultation' => $createdRdvs['RDV-2026-0004']->motif,
            'histoire_maladie' => 'Consultation de suivi en direct.',
            'symptomes' => 'Stable sous Coversyl 5mg.',
            'examen_clinique' => 'En cours...',
            'temperature' => 36.8,
            'tension_arterielle' => '125/80',
            'frequence_cardiaque' => 70,
            'statut' => 'en_cours',
        ]);

        // ==========================================
        // 8. CRÉATION DES ORDONNANCES ET LIGNES D'ORDONNANCE
        // ==========================================
        // Nous allons utiliser des requêtes brutes DB car il n'y a pas de modèles Eloquent associés
        
        // Ordonnance 1: Liée à la Consultation 1 (Dr. Ahmed El Fassi -> Yassine Mansouri)
        $ord1Id = DB::table('ordonnances')->insertGetId([
            'consultation_id' => $c1->id,
            'patient_id' => $patients['DOS-2026-0001']->id,
            'medecin_id' => $medecins['MED-2026-0001']->id,
            'numero' => 'ORD-2026-0001',
            'date_prescription' => $c1->date_heure->toDateString(),
            'date_validite' => $c1->date_heure->addMonths(3)->toDateString(),
            'type' => 'longue_duree', // ALD
            'prescriptions' => "1. Coversyl 5mg (Périndopril arginine) : 1 comprimé le matin.\n2. Magne B6 (Magnésium marin) : 1 comprimé midi et soir au cours du repas.",
            'instructions_patient' => 'Prendre le Coversyl à jeun le matin pour une efficacité maximale. Poursuivre le régime hyposodé.',
            'notes_pharmacien' => 'Substitution par générique autorisée si nécessaire.',
            'regime_alimentaire' => 'Régime hyposodé (pauvre en sel). Éviter la charcuterie, les conserves et le sel de table.',
            'statut' => 'active',
            'renouvelable' => true,
            'nombre_renouvellements' => 3,
            'renouvellements_effectues' => 0,
            'prise_en_charge' => true,
            'taux_remboursement' => 80.00,
            'imprimee' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Lignes d'ordonnance 1
        DB::table('lignes_ordonnance')->insert([
            [
                'ordonnance_id' => $ord1Id,
                'nom_medicament' => 'Coversyl',
                'forme_galénique' => 'comprimé',
                'dosage' => '5mg',
                'code_atc' => 'C09AA04',
                'posologie' => '1 comprimé par jour, le matin à jeun.',
                'frequence' => '1 fois par jour',
                'duree_traitement' => '3 mois (ALD)',
                'moment_prise' => 'le matin à jeun',
                'quantite' => 3,
                'unite_quantite' => 'boîte',
                'sans_substitution' => false,
                'ordre' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'ordonnance_id' => $ord1Id,
                'nom_medicament' => 'Magne B6',
                'forme_galénique' => 'comprimé pelliculé',
                'dosage' => '470mg/5mg',
                'code_atc' => 'A11JB',
                'posologie' => '1 comprimé midi et soir au cours du repas.',
                'frequence' => '2 fois par jour',
                'duree_traitement' => '30 jours',
                'moment_prise' => 'pendant le repas',
                'quantite' => 2,
                'unite_quantite' => 'boîte',
                'sans_substitution' => false,
                'ordre' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);

        // Ordonnance 2: Liée à la Consultation 2 (Dr. Fatim-Zahra -> Salma Bourkia)
        $ord2Id = DB::table('ordonnances')->insertGetId([
            'consultation_id' => $c2->id,
            'patient_id' => $patients['DOS-2026-0002']->id,
            'medecin_id' => $medecins['MED-2026-0005']->id,
            'numero' => 'ORD-2026-0002',
            'date_prescription' => $c2->date_heure->toDateString(),
            'date_validite' => $c2->date_heure->addDays(15)->toDateString(),
            'type' => 'standard',
            'prescriptions' => "1. Doliprane 1g : 1 comprimé à renouveler si besoin toutes les 6 heures (max 4g/jour).\n2. Rhinathiol Toux Sèche Sirop : 1 cuillère à soupe 3 fois par jour.\n3. Vitamine C Effervescente 1g : 1 comprimé le matin.",
            'instructions_patient' => 'Boire beaucoup d\'eau et de tisanes chaudes. Rester au chaud.',
            'statut' => 'dispensee',
            'renouvelable' => false,
            'prise_en_charge' => true,
            'taux_remboursement' => 70.00,
            'imprimee' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Lignes d'ordonnance 2
        DB::table('lignes_ordonnance')->insert([
            [
                'ordonnance_id' => $ord2Id,
                'nom_medicament' => 'Doliprane',
                'forme_galénique' => 'comprimé',
                'dosage' => '1g',
                'code_atc' => 'N02BE01',
                'posologie' => '1 comprimé en cas de fièvre, à renouveler toutes les 6h si nécessaire. Max 4 comprimés par jour.',
                'frequence' => 'toutes les 6 heures (si besoin)',
                'duree_traitement' => '5 jours',
                'moment_prise' => 'après les repas',
                'quantite' => 1,
                'unite_quantite' => 'boîte',
                'sans_substitution' => false,
                'ordre' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'ordonnance_id' => $ord2Id,
                'nom_medicament' => 'Rhinathiol Toux Sèche',
                'forme_galénique' => 'sirop',
                'dosage' => 'flacon 200ml',
                'code_atc' => 'R05DA09',
                'posologie' => '1 cuillère à soupe 3 fois par jour, à distance des repas.',
                'frequence' => '3 fois par jour',
                'duree_traitement' => '5 jours',
                'moment_prise' => 'à distance des repas',
                'quantite' => 1,
                'unite_quantite' => 'flacon',
                'sans_substitution' => false,
                'ordre' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);

        // ==========================================
        // 9. CRÉATION DES FACTURES (MAD - DIRHAM MAROCAIN)
        // ==========================================
        
        // Facture 1 : Liée à la Consultation 1 (Payée, par carte bancaire, remboursement CNOPS)
        $f1 = Facture::create([
            'consultation_id' => $c1->id,
            'patient_id' => $patients['DOS-2026-0001']->id,
            'medecin_id' => $medecins['MED-2026-0001']->id,
            'service_id' => $services['CARD']->id,
            'numero_facture' => 'FAC-2026-0001',
            'date_emission' => $c1->date_heure->toDateString(),
            'date_echeance' => $c1->date_heure->addDays(15)->toDateString(),
            'type_facture' => 'consultation',
            'montant_brut' => 450.00, // Consultation cardiologique + ECG
            'remise_montant' => 50.00,
            'remise_pourcentage' => 11.11,
            'montant_apres_remise' => 400.00,
            'montant_assurance' => 320.00, // 80% CNOPS
            'montant_patient' => 80.00,   // Reste à charge patient (ticket modérateur)
            'tva_taux' => 10.00,
            'tva_montant' => 40.00,
            'montant_total_ttc' => 440.00,
            'montant_paye' => 440.00,
            'montant_restant' => 0.00,
            'devise' => 'MAD',
            'statut' => 'payee',
            'mode_paiement' => 'carte_bancaire',
            'date_paiement' => $c1->date_heure->addHours(1),
            'reference_paiement' => 'CB-MAD-78904123',
            'organisme_assurance' => 'CNOPS',
            'numero_prise_en_charge' => 'PEC-CNOPS-89912',
            'designation_prestations' => 'Consultation Spécialisée en Cardiologie avec Électrocardiogramme (ECG).',
            'notes' => 'Paiement effectué en caisse principale. Télétransmission CNOPS complétée.',
            'imprimee' => true,
            'cree_par' => 1,
            'valide_par' => 1,
            'date_validation' => $c1->date_heure,
        ]);

        // Lignes de facture 1
        DB::table('lignes_facture')->insert([
            [
                'facture_id' => $f1->id,
                'code_acte' => 'CC-CARD',
                'designation' => 'Consultation de Cardiologie',
                'description' => 'Examen clinique cardiologique spécialisé',
                'categorie' => 'consultation',
                'prix_unitaire' => 300.00,
                'quantite' => 1.00,
                'montant_ht' => 300.00,
                'tva_taux' => 10.00,
                'tva_montant' => 30.00,
                'remise_pourcentage' => 0.00,
                'remise_montant' => 0.00,
                'montant_ttc' => 330.00,
                'part_assurance' => 240.00,
                'part_patient' => 60.00,
                'ordre' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'facture_id' => $f1->id,
                'code_acte' => 'ECG-01',
                'designation' => 'Tracé Électrocardiogramme (ECG)',
                'description' => 'Réalisation et interprétation d\'un ECG à 12 dérivations',
                'categorie' => 'acte_medical',
                'prix_unitaire' => 100.00,
                'quantite' => 1.00,
                'montant_ht' => 100.00,
                'tva_taux' => 10.00,
                'tva_montant' => 10.00,
                'remise_pourcentage' => 0.00,
                'remise_montant' => 0.00,
                'montant_ttc' => 110.00,
                'part_assurance' => 80.00,
                'part_patient' => 20.00,
                'ordre' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);

        // Facture 2 : Liée à la Consultation 2 (En retard, patient Salma Bourkia)
        $f2 = Facture::create([
            'consultation_id' => $c2->id,
            'patient_id' => $patients['DOS-2026-0002']->id,
            'medecin_id' => $medecins['MED-2026-0005']->id,
            'service_id' => $services['MEDG']->id,
            'numero_facture' => 'FAC-2026-0002',
            'date_emission' => $c2->date_heure->toDateString(),
            'date_echeance' => $c2->date_heure->addDays(10)->toDateString(),
            'type_facture' => 'consultation',
            'montant_brut' => 200.00, // Consultation médecine générale
            'remise_montant' => 0.00,
            'remise_pourcentage' => 0.00,
            'montant_apres_remise' => 200.00,
            'montant_assurance' => 140.00, // 70% CNSS
            'montant_patient' => 60.00,
            'tva_taux' => 10.00,
            'tva_montant' => 20.00,
            'montant_total_ttc' => 220.00,
            'montant_paye' => 0.00,
            'montant_restant' => 220.00,
            'devise' => 'MAD',
            'statut' => 'en_retard',
            'organisme_assurance' => 'CNSS',
            'designation_prestations' => 'Consultation de Médecine Générale et prescription.',
            'notes' => 'Patient à relancer pour régularisation de sa part.',
            'imprimee' => true,
            'cree_par' => 1,
        ]);

        // Ligne de facture 2
        DB::table('lignes_facture')->insert([
            'facture_id' => $f2->id,
            'code_acte' => 'CC-MEDG',
            'designation' => 'Consultation de Médecine Générale',
            'description' => 'Consultation clinique de routine ou syndrome grippal',
            'categorie' => 'consultation',
            'prix_unitaire' => 200.00,
            'quantite' => 1.00,
            'montant_ht' => 200.00,
            'tva_taux' => 10.00,
            'tva_montant' => 20.00,
            'remise_pourcentage' => 0.00,
            'remise_montant' => 0.00,
            'montant_ttc' => 220.00,
            'part_assurance' => 140.00,
            'part_patient' => 60.00,
            'ordre' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Facture 3 : Hospitalisation sans consultation liée (Facture manuelle, partially paid, Karim Haddad)
        $f3 = Facture::create([
            'consultation_id' => null,
            'patient_id' => $patients['DOS-2026-0003']->id,
            'medecin_id' => $medecins['MED-2026-0001']->id,
            'service_id' => $services['CARD']->id,
            'numero_facture' => 'FAC-2026-0003',
            'date_emission' => Carbon::now()->subDays(12)->toDateString(),
            'date_echeance' => Carbon::now()->addDays(18)->toDateString(),
            'type_facture' => 'hospitalisation',
            'montant_brut' => 3000.00, // Séjour 2 nuits + matériel
            'remise_montant' => 300.00,
            'remise_pourcentage' => 10.00,
            'montant_apres_remise' => 2700.00,
            'montant_assurance' => 2160.00, // 80% AMO
            'montant_patient' => 540.00,
            'tva_taux' => 10.00,
            'tva_montant' => 270.00,
            'montant_total_ttc' => 2970.00,
            'montant_paye' => 2160.00, // Mutuelle a payé, reste la part patient
            'montant_restant' => 810.00,
            'devise' => 'MAD',
            'statut' => 'partiellement_payee',
            'mode_paiement' => 'assurance',
            'organisme_assurance' => 'AMO',
            'numero_prise_en_charge' => 'PEC-AMO-778841',
            'designation_prestations' => 'Séjour d\'observation en unité de soins intensifs cardiologiques (2 nuits).',
            'notes' => 'Part mutuelle reçue par virement. Reste à charge patient à régler.',
            'imprimee' => true,
            'cree_par' => 1,
            'valide_par' => 1,
            'date_validation' => Carbon::now()->subDays(11),
        ]);

        // Lignes de facture 3
        DB::table('lignes_facture')->insert([
            [
                'facture_id' => $f3->id,
                'code_acte' => 'HOSP-NIGHT',
                'designation' => 'Nuité de Chambre Clinique Privée',
                'description' => 'Chambre individuelle climatisée en soins intensifs',
                'categorie' => 'chambre',
                'prix_unitaire' => 1200.00,
                'quantite' => 2.00,
                'montant_ht' => 2400.00,
                'tva_taux' => 10.00,
                'tva_montant' => 240.00,
                'remise_pourcentage' => 10.00,
                'remise_montant' => 240.00,
                'montant_ttc' => 2400.00,
                'part_assurance' => 1920.00,
                'part_patient' => 480.00,
                'ordre' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'facture_id' => $f3->id,
                'code_acte' => 'MAT-09',
                'designation' => 'Forfait consommables médicaux',
                'description' => 'Seringues, tubulures, cathéters et perfusions basiques',
                'categorie' => 'materiel',
                'prix_unitaire' => 300.00,
                'quantite' => 1.00,
                'montant_ht' => 300.00,
                'tva_taux' => 10.00,
                'tva_montant' => 30.00,
                'remise_pourcentage' => 10.00,
                'remise_montant' => 30.00,
                'montant_ttc' => 300.00,
                'part_assurance' => 240.00,
                'part_patient' => 60.00,
                'ordre' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);

        // ==========================================
        // 10. CRÉATION DES MESSAGES DE CHAT RÉALISTES
        // ==========================================
        // Yassine Mansouri avec son médecin Dr. Ahmed El Fassi
        $userPatientYassine = User::where('email', 'patient@hospit.com')->first();
        $userMedecinAhmed = User::where('email', 'medecin@hospit.com')->first();
        
        $messages = [
            [
                'sender_id' => $userPatientYassine->id,
                'receiver_id' => $userMedecinAhmed->id,
                'contenu' => 'Bonjour Dr. Ahmed, j\'espère que vous allez bien. Je vous contacte car je ressens une légère fatigue en fin de journée depuis le changement de dosage du Coversyl.',
                'created_at' => Carbon::now()->subDays(6)->setHour(9)->setMinute(15),
            ],
            [
                'sender_id' => $userMedecinAhmed->id,
                'receiver_id' => $userPatientYassine->id,
                'contenu' => 'Bonjour Yassine. C\'est une réaction fréquente en début de traitement due à la baisse de tension. Est-ce que vous prenez bien votre comprimé à jeun le matin ?',
                'created_at' => Carbon::now()->subDays(6)->setHour(11)->setMinute(40),
            ],
            [
                'sender_id' => $userPatientYassine->id,
                'receiver_id' => $userMedecinAhmed->id,
                'contenu' => 'Oui Docteur, je le prends tous les matins au réveil avec un grand verre d\'eau, 30 minutes avant le petit-déjeuner.',
                'created_at' => Carbon::now()->subDays(6)->setHour(12)->setMinute(5),
            ],
            [
                'sender_id' => $userMedecinAhmed->id,
                'receiver_id' => $userPatientYassine->id,
                'contenu' => 'Excellent. C\'est la bonne méthode. Pour pallier cette fatigue passagère, je vous ai prescrit du magnésium dans votre ordonnance. Prenez-le pendant les repas de midi et du soir. Tenez-moi au courant lors de notre prochain rendez-vous.',
                'created_at' => Carbon::now()->subDays(5)->setHour(10)->setMinute(10),
            ],
            [
                'sender_id' => $userPatientYassine->id,
                'receiver_id' => $userMedecinAhmed->id,
                'contenu' => 'Parfait Docteur. J\'ai commencé le magnésium et je me sens déjà beaucoup mieux. Merci beaucoup pour votre réactivité !',
                'created_at' => Carbon::now()->subDays(4)->setHour(18)->setMinute(30),
            ]
        ];

        foreach ($messages as $msg) {
            Message::create($msg);
        }

        // ==========================================
        // 11. CRÉATION DES NOTIFICATIONS IN-APP
        // ==========================================
        // Notification pour Yassine Mansouri (Nouveau rendez-vous confirmé)
        DB::table('notifications')->insert([
            [
                'id' => (string) Str::uuid(),
                'type' => 'App\\Notifications\\RendezVousConfirmeNotification',
                'notifiable_type' => 'App\\Models\User',
                'notifiable_id' => $userPatientYassine->id,
                'data' => json_encode([
                    'title' => 'Rendez-vous confirmé',
                    'reference' => 'RDV-2026-0004',
                    'motif' => 'Votre suivi de Cardiologie avec le Dr. Ahmed El Fassi a été planifié avec succès pour aujourd\'hui à 10:00.',
                    'created_at' => Carbon::now()->subHours(4)->toDateTimeString(),
                ]),
                'read_at' => null,
                'created_at' => Carbon::now()->subHours(4),
                'updated_at' => Carbon::now()->subHours(4),
            ],
            [
                'id' => (string) Str::uuid(),
                'type' => 'App\\Notifications\\FactureEmiseNotification',
                'notifiable_type' => 'App\\Models\User',
                'notifiable_id' => $userPatientYassine->id,
                'data' => json_encode([
                    'title' => 'Nouvelle facture émise',
                    'reference' => 'FAC-2026-0001',
                    'motif' => 'Votre facture d\'un montant de 440.00 MAD a été émise et validée.',
                    'created_at' => Carbon::now()->subDays(10)->toDateTimeString(),
                ]),
                'read_at' => Carbon::now()->subDays(10)->addMinutes(30),
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(10),
            ]
        ]);
        
        // Notification pour le Dr. Ahmed El Fassi (Demande de RDV en attente)
        DB::table('notifications')->insert([
            [
                'id' => (string) Str::uuid(),
                'type' => 'App\\Notifications\\NouveauRendezVousNotification',
                'notifiable_type' => 'App\\Models\User',
                'notifiable_id' => $userMedecinAhmed->id,
                'data' => json_encode([
                    'title' => 'Nouveau rendez-vous planifié',
                    'reference' => 'RDV-2026-0005',
                    'motif' => 'Le patient Amina Bensouda a pris rendez-vous pour aujourd\'hui à 15:30 (Cardiologie).',
                    'created_at' => Carbon::now()->subHours(2)->toDateTimeString(),
                ]),
                'read_at' => null,
                'created_at' => Carbon::now()->subHours(2),
                'updated_at' => Carbon::now()->subHours(2),
            ]
        ]);
    }
}
