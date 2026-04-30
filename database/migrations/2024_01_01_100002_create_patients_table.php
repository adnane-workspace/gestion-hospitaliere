<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table : patients
     * Entité centrale de l'application. Chaque patient possède un dossier médical unique.
     * Un patient peut être rattaché à un médecin traitant et à un service.
     */
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();

            // Clés étrangères optionnelles (médecin traitant, service d'admission)
            $table->foreignId('medecin_traitant_id')
                  ->nullable()
                  ->constrained('medecins')
                  ->onDelete('set null')
                  ->comment('Médecin traitant principal (nullable)');

            $table->foreignId('service_id')
                  ->nullable()
                  ->constrained('services')
                  ->onDelete('set null')
                  ->comment('Service d\'admission ou de suivi principal');

            // Dossier médical - identifiant unique du patient
            $table->string('numero_dossier', 30)->unique()->comment('Numéro de dossier médical unique (ex: DOS-2024-00001)');

            // Identité civile
            $table->string('nom', 100)->comment('Nom de famille du patient');
            $table->string('prenom', 100)->comment('Prénom du patient');
            $table->enum('genre', ['homme', 'femme', 'autre'])->comment('Genre du patient');
            $table->date('date_naissance')->comment('Date de naissance');
            $table->string('lieu_naissance', 150)->nullable()->comment('Lieu de naissance');
            $table->string('nationalite', 80)->nullable()->default('Marocaine')->comment('Nationalité');
            $table->string('cin', 20)->unique()->nullable()->comment('Numéro de CIN ou pièce d\'identité');
            $table->string('numero_securite_sociale', 30)->nullable()->comment('Numéro de sécurité sociale / CNSS / CNOPS');

            // Coordonnées
            $table->string('telephone', 20)->comment('Téléphone principal');
            $table->string('telephone_urgence', 20)->nullable()->comment('Téléphone d\'urgence (proche)');
            $table->string('contact_urgence_nom', 150)->nullable()->comment('Nom du contact d\'urgence');
            $table->string('email', 150)->nullable()->comment('Adresse email du patient');
            $table->text('adresse')->nullable()->comment('Adresse complète du domicile');
            $table->string('ville', 100)->nullable()->comment('Ville de résidence');
            $table->string('code_postal', 10)->nullable()->comment('Code postal');

            // Informations médicales
            $table->enum('groupe_sanguin', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])
                  ->nullable()
                  ->comment('Groupe sanguin ABO et Rhésus');
            $table->text('allergies')->nullable()->comment('Liste des allergies connues');
            $table->text('antecedents_medicaux')->nullable()->comment('Antécédents médicaux personnels');
            $table->text('antecedents_familiaux')->nullable()->comment('Antécédents médicaux familiaux');
            $table->text('medicaments_actuels')->nullable()->comment('Traitements médicamenteux en cours');
            $table->decimal('taille', 5, 2)->nullable()->comment('Taille en cm');
            $table->decimal('poids', 5, 2)->nullable()->comment('Poids en kg');

            // Couverture sociale et assurance
            $table->string('mutuelle', 100)->nullable()->comment('Mutuelle ou compagnie d\'assurance');
            $table->string('numero_mutuelle', 50)->nullable()->comment('Numéro de contrat mutuelle');
            $table->enum('type_couverture', ['cnss', 'cnops', 'ramed', 'assurance_privee', 'aucune', 'autre'])
                  ->nullable()
                  ->comment('Type de couverture sociale');

            // Statut administratif
            $table->enum('statut', ['actif', 'inactif', 'decede', 'transfere'])
                  ->default('actif')
                  ->comment('Statut administratif du patient');
            $table->date('date_admission')->nullable()->comment('Date de première admission dans l\'établissement');
            $table->text('observations_generales')->nullable()->comment('Notes et observations générales');
            $table->string('photo', 255)->nullable()->comment('Chemin vers la photo du patient');

            $table->timestamps();
            $table->softDeletes()->comment('Suppression logique du dossier');

            // Index de performance pour les recherches courantes
            $table->index('numero_dossier');
            $table->index('cin');
            $table->index('statut');
            $table->index(['nom', 'prenom']);
            $table->index('date_naissance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
