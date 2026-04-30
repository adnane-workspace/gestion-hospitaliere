<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table : medecins
     * Entité représentant les médecins de l'hôpital.
     * Un médecin est rattaché à un service (belongsTo Service).
     */
    public function up(): void
    {
        Schema::create('medecins', function (Blueprint $table) {
            $table->id();

            // Clé étrangère vers le service d'appartenance
            $table->foreignId('service_id')
                  ->constrained('services')
                  ->onDelete('restrict') // Ne pas supprimer un service qui a des médecins
                  ->comment('Service auquel appartient le médecin');

            // Identité et identification professionnelle
            $table->string('matricule', 20)->unique()->comment('Matricule unique du médecin (ex: MED-2024-001)');
            $table->string('nom', 100)->comment('Nom de famille du médecin');
            $table->string('prenom', 100)->comment('Prénom du médecin');
            $table->string('cin', 20)->unique()->nullable()->comment('Carte nationale d\'identité');
            $table->enum('genre', ['homme', 'femme'])->comment('Genre du médecin');
            $table->date('date_naissance')->nullable()->comment('Date de naissance');

            // Coordonnées
            $table->string('telephone', 20)->comment('Numéro de téléphone principal');
            $table->string('telephone_urgence', 20)->nullable()->comment('Numéro d\'urgence ou secondaire');
            $table->string('email', 150)->unique()->comment('Adresse email professionnelle');
            $table->text('adresse')->nullable()->comment('Adresse personnelle du médecin');

            // Informations professionnelles
            $table->string('specialite', 100)->comment('Spécialité médicale principale (ex: Cardiologue, Pédiatre)');
            $table->string('sous_specialite', 100)->nullable()->comment('Sous-spécialité ou compétence complémentaire');
            $table->string('numero_ordre', 50)->unique()->nullable()->comment('Numéro d\'inscription à l\'Ordre des Médecins');
            $table->year('annee_diplome')->nullable()->comment('Année d\'obtention du diplôme principal');
            $table->string('grade', 50)->nullable()->comment('Grade médical (Interne, Résident, Spécialiste, Professeur...)');
            $table->date('date_embauche')->comment('Date d\'embauche dans l\'établissement');

            // Disponibilité et état
            $table->enum('statut', ['actif', 'inactif', 'conge', 'suspendu'])
                  ->default('actif')
                  ->comment('Statut professionnel actuel du médecin');
            $table->text('biographie')->nullable()->comment('Présentation / biographie professionnelle');
            $table->string('photo', 255)->nullable()->comment('Chemin vers la photo de profil');

            $table->timestamps();
            $table->softDeletes()->comment('Suppression logique');

            // Index de performance
            $table->index('matricule');
            $table->index('specialite');
            $table->index('statut');
            $table->index(['nom', 'prenom']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medecins');
    }
};
