<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table : rendezvous
     * Gestion des rendez-vous entre patients et médecins.
     * Relations :
     *   - belongsTo Patient  (cascade delete)
     *   - belongsTo Medecin  (cascade delete)
     *   - belongsTo Service  (set null)
     * Un rendez-vous peut avoir plusieurs statuts successifs.
     */
    public function up(): void
    {
        Schema::create('rendezvous', function (Blueprint $table) {
            $table->id();

            // Relations obligatoires
            $table->foreignId('patient_id')
                  ->constrained('patients')
                  ->onDelete('cascade')
                  ->comment('Patient concerné par le rendez-vous');

            $table->foreignId('medecin_id')
                  ->constrained('medecins')
                  ->onDelete('cascade')
                  ->comment('Médecin responsable du rendez-vous');

            $table->foreignId('service_id')
                  ->nullable()
                  ->constrained('services')
                  ->onDelete('set null')
                  ->comment('Service dans lequel se déroule le rendez-vous');

            // Référence interne du rendez-vous
            $table->string('reference', 30)->unique()->comment('Référence unique du RDV (ex: RDV-2024-00001)');

            // Planification temporelle
            $table->dateTime('date_heure_debut')->comment('Date et heure de début du rendez-vous');
            $table->dateTime('date_heure_fin')->nullable()->comment('Date et heure de fin prévue');
            $table->integer('duree_minutes')->default(30)->comment('Durée prévue en minutes');

            // Statut du rendez-vous - champ crucial
            $table->enum('statut', [
                'planifie',     // Rendez-vous planifié, en attente
                'confirme',     // Confirmé par le médecin ou la secrétaire
                'en_attente',   // Patient arrivé, en salle d'attente
                'en_cours',     // Consultation en cours
                'termine',      // Consultation terminée
                'annule',       // Annulé (avant la date)
                'reporte',      // Reporté à une autre date
                'patient_absent' // Patient ne s'est pas présenté
            ])->default('planifie')->comment('Statut actuel du rendez-vous');

            // Motif et type
            $table->string('motif', 255)->comment('Motif principal de la consultation');
            $table->enum('type_rendez_vous', [
                'premiere_consultation', // Première visite
                'suivi',                 // Consultation de suivi
                'urgence',               // Urgence
                'controle',              // Visite de contrôle
                'bilan',                 // Bilan de santé
                'acte_medical'           // Acte médical spécifique (soins, pansement...)
            ])->default('premiere_consultation')->comment('Nature du rendez-vous');

            // Canal de prise de rendez-vous
            $table->enum('canal_prise_rdv', ['telephone', 'en_ligne', 'presentiel', 'transfert'])
                  ->default('telephone')
                  ->comment('Comment le rendez-vous a été pris');

            // Gestion des annulations/reports
            $table->string('motif_annulation', 255)->nullable()->comment('Raison de l\'annulation ou du report');
            $table->dateTime('date_annulation')->nullable()->comment('Date à laquelle l\'annulation a été effectuée');
            $table->string('annule_par', 100)->nullable()->comment('Qui a annulé (patient, médecin, admin)');

            // Notes
            $table->text('notes_preparation')->nullable()->comment('Instructions de préparation pour le patient');
            $table->text('notes_internes')->nullable()->comment('Notes internes (non visibles par le patient)');

            // Rappels
            $table->boolean('rappel_envoye')->default(false)->comment('Indique si un rappel a été envoyé au patient');
            $table->dateTime('date_rappel')->nullable()->comment('Date et heure d\'envoi du rappel');

            $table->timestamps();
            $table->softDeletes();

            // Index pour les requêtes courantes
            $table->index('statut');
            $table->index('date_heure_debut');
            $table->index(['medecin_id', 'date_heure_debut']);
            $table->index(['patient_id', 'statut']);
            $table->index('reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rendezvous');
    }
};
