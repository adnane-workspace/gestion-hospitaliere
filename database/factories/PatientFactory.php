<?php

namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $groupesSanguins = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $allergiesPossibles = ['Pénicilline', 'Amoxicilline', 'Pollen', 'Acariens', 'Arachides', 'Lait', 'Gluten', 'Oeufs', 'Fruits de mer', 'Latex'];
        $maladiesChroniques = ['Diabète de type 2', 'Hypertension', 'Asthme', 'Bronchopneumopathie chronique obstructive', 'Insuffisance cardiaque', 'Arthrose', 'Dépression', 'Anxiété', 'Migraines', 'Hypothyroïdie'];
        $medicaments = ['Metformine', 'Lisinopril', 'Amlodipine', 'Simvastatine', 'Omeprazole', 'Paracétamol', 'Ibuprofène', 'Ventoline', 'Seretide', 'Losartan'];

        $taille = $this->faker->numberBetween(150, 190);
        $poids = $this->faker->numberBetween(50, 120);
        $imc = round($poids / (($taille / 100) ** 2), 2);

        return [
            'numero_dossier' => 'DOS-' . date('Y') . '-' . str_pad($this->faker->unique()->numberBetween(1, 99999), 5, '0', STR_PAD_LEFT),
            'nom' => $this->faker->lastName,
            'prenom' => $this->faker->firstName,
            'genre' => $this->faker->randomElement(['homme', 'femme']),
            'date_naissance' => $this->faker->dateTimeBetween('-80 years', '-18 years'),
            'lieu_naissance' => $this->faker->city,
            'nationalite' => 'Marocaine',
            'cin' => strtoupper($this->faker->bothify('??######')),
            'numero_securite_sociale' => $this->faker->numerify('##########'),
            'telephone' => $this->faker->phoneNumber,
            'telephone_urgence' => $this->faker->phoneNumber,
            'contact_urgence_nom' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'adresse' => $this->faker->address,
            'ville' => $this->faker->city,
            'code_postal' => $this->faker->postcode,
            'groupe_sanguin' => $this->faker->randomElement($groupesSanguins),
            'allergies' => $this->faker->randomElements($allergiesPossibles, $this->faker->numberBetween(0, 3)),
            'antecedents_medicaux' => $this->faker->randomElements([
                'Fracture du bras', 'Appendicectomie', 'Accouchement par césarienne', 'Chirurgie dentaire',
                'Hospitalisation pour pneumonie', 'IVG', 'Accident de voiture', 'Chute avec traumatisme crânien'
            ], $this->faker->numberBetween(0, 2)),
            'antecedents_chirurgicaux' => $this->faker->randomElements([
                'Appendicectomie', 'Cholécystectomie', 'Hernie inguinale', 'Arthroscopie du genou',
                'Pontage coronarien', 'Prostatectomie', 'Thyroïdectomie', 'Césarienne'
            ], $this->faker->numberBetween(0, 2)),
            'maladies_chroniques' => $this->faker->randomElements($maladiesChroniques, $this->faker->numberBetween(0, 2)),
            'medicaments_actuels' => $this->faker->randomElements($medicaments, $this->faker->numberBetween(0, 4)),
            'taille' => $taille,
            'poids' => $poids,
            'tension_arterielle' => $this->faker->numberBetween(110, 160) . '/' . $this->faker->numberBetween(70, 100),
            'frequence_cardiaque' => $this->faker->numberBetween(60, 100),
            'antecedents_familiaux' => $this->faker->randomElements([
                'Diabète chez le père', 'Cancer du sein chez la mère', 'Hypertension familiale',
                'Maladie cardiovasculaire précoce', 'Alzheimer chez les grands-parents'
            ], $this->faker->numberBetween(0, 2)),
            'mutuelle' => $this->faker->randomElement(['CNOPS', 'CNSS', 'Assurance Privée', null]),
            'numero_mutuelle' => $this->faker->numerify('########'),
            'type_couverture' => $this->faker->randomElement(['cnss', 'cnops', 'ramed', 'assurance_privee', 'aucune']),
            'statut' => 'actif',
            'date_admission' => $this->faker->dateTimeBetween('-5 years', 'now'),
            'observations_generales' => $this->faker->optional(0.3)->sentence,
        ];
    }
}
