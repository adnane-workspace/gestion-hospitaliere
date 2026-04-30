<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table : consultations
     * Enregistrement médical d'une consultation. Liée à un rendez-vous (1-to-1),
     * à un patient et à un médecin.
     * Relations :
     *   - belongsTo Rendezvous (cascade delete)
     *   - belongsTo Patient    (cascade delete)
     *   - belongsTo Medecin    (cascade delete)
     *   - hasMany   Ordonnance
     *   - hasOne    Facture
     */
    public function up(): void
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();

            // Clé étrangère vers le rendez-vous source (relation 1:1)
            $table->foreignId('rendezvous_id')
                  ->unique()
                  ->constrained('rendezvous')
                  ->onDelete('cascade')
                  ->comment('Rendez-vous à l\'origine de cette consultation (relation 1:1)');

            // Dénormalisation utile pour les requêtes directes
            $table->foreignId('patient_id')
                  ->constrained('patients')
                  ->onDelete('cascade')
                  ->comment('Patient consulté');

            $table->foreignId('medecin_id')
                  ->constrained('medecins')
                  ->onDelete('cascade')
                  ->comment('Médecin ayant effectué la consultation');

            $table->foreignId('service_id')
                  ->nullable()
                  ->constrained('services')
                  ->onDelete('set null')
                  ->comment('Service dans lequel la consultation s\'est déroulée');

            // Référence et horodatage
            $table->string('reference', 30)->unique()->comment('Référence unique de la consultation (ex: CONS-2024-00001)');
            $table->dateTime('date_heure')->comment('Date et heure exacte de la consultation');
            $table->integer('duree_reelle_minutes')->nullable()->comment('Durée réelle de la consultation en minutes');

            // Anamnèse
            $table->text('motif_consultation')->comment('Motif exprimé par le patient');
            $table->text('histoire_maladie')->nullable()->comment('Histoire de la maladie actuelle (anamnèse)');
            $table->text('symptomes')->nullable()->comment('Symptômes décrits par le patient');

            // Examen clinique
            $table->text('examen_clinique')->nullable()->comment('Résultats de l\'examen physique');
            $table->decimal('temperature', 4, 1)->nullable()->comment('Température corporelle en °C');
            $table->string('tension_arterielle', 15)->nullable()->comment('Tension artérielle (ex: 120/80 mmHg)');
            $table->integer('frequence_cardiaque')->nullable()->comment('Fréquence cardiaque en bpm');
            $table->integer('frequence_respiratoire')->nullable()->comment('Fréquence respiratoire (cycles/min)');
            $table->decimal('saturation_oxygene', 5, 2)->nullable()->comment('SpO2 en %');
            $table->decimal('poids_consultation', 5, 2)->nullable()->comment('Poids lors de la consultation en kg');
            $table->decimal('taille_consultation', 5, 2)->nullable()->comment('Taille lors de la consultation en cm');
            $table->decimal('imc', 5, 2)->nullable()->comment('Indice de masse corporelle calculé');

            // Diagnostic
            $table->text('diagnostic_principal')->nullable()->comment('Diagnostic médical principal établi');
            $table->text('diagnostics_secondaires')->nullable()->comment('Diagnostics secondaires ou différentiels');
            $table->string('code_cim10', 20)->nullable()->comment('Code CIM-10 / ICD-10 du diagnostic principal');

            // Examens complémentaires
            $table->text('examens_demandes')->nullable()->comment('Examens complémentaires demandés (biologie, imagerie, etc.)');
            $table->text('resultats_examens')->nullable()->comment('Résultats des examens complémentaires');

            // Traitement et suivi
            $table->text('traitement_prescrit')->nullable()->comment('Résumé du traitement prescrit lors de cette consultation');
            $table->text('recommandations')->nullable()->comment('Recommandations hygiéno-diététiques et conseils');
            $table->text('notes_medecin')->nullable()->comment('Notes confidentielles du médecin');

            // Documents
            $table->boolean('certificat_medical')->default(false)->comment('Un certificat médical a-t-il été délivré ?');
            $table->text('contenu_certificat')->nullable()->comment('Contenu du certificat médical si délivré');
            $table->boolean('arret_travail')->default(false)->comment('Un arrêt de travail a-t-il été prescrit ?');
            $table->integer('duree_arret_travail_jours')->nullable()->comment('Durée de l\'arrêt de travail en jours');
            $table->date('debut_arret_travail')->nullable()->comment('Date de début de l\'arrêt de travail');

            // Suivi
            $table->boolean('suivi_requis')->default(false)->comment('Un suivi est-il nécessaire ?');
            $table->integer('delai_suivi_jours')->nullable()->comment('Délai recommandé pour le prochain rendez-vous en jours');
            $table->text('instructions_suivi')->nullable()->comment('Instructions pour le prochain rendez-vous');

            // Statut de la consultation
            $table->enum('statut', ['en_cours', 'terminee', 'annulee'])
                  ->default('en_cours')
                  ->comment('Statut de la consultation');

            $table->timestamps();
            $table->softDeletes();

            // Index de performance
            $table->index('date_heure');
            $table->index(['patient_id', 'date_heure']);
            $table->index(['medecin_id', 'date_heure']);
            $table->index('statut');
            $table->index('code_cim10');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
