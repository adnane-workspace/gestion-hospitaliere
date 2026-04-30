<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table : factures
     * Facturation des consultations et actes médicaux.
     * Relations :
     *   - belongsTo Consultation (set null si consultation supprimée)
     *   - belongsTo Patient      (cascade delete)
     *   - belongsTo Medecin      (set null)
     *   - belongsTo Service      (set null)
     */
    public function up(): void
    {
        Schema::create('factures', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('consultation_id')
                  ->nullable()
                  ->unique()
                  ->constrained('consultations')
                  ->onDelete('set null')
                  ->comment('Consultation facturée (relation 1:1, nullable si facture manuelle)');

            $table->foreignId('patient_id')
                  ->constrained('patients')
                  ->onDelete('cascade')
                  ->comment('Patient redevable de la facture');

            $table->foreignId('medecin_id')
                  ->nullable()
                  ->constrained('medecins')
                  ->onDelete('set null')
                  ->comment('Médecin ayant réalisé l\'acte facturé');

            $table->foreignId('service_id')
                  ->nullable()
                  ->constrained('services')
                  ->onDelete('set null')
                  ->comment('Service ayant fourni la prestation');

            // Identification de la facture
            $table->string('numero_facture', 30)->unique()->comment('Numéro unique de facture (ex: FAC-2024-00001)');
            $table->date('date_emission')->comment('Date d\'émission de la facture');
            $table->date('date_echeance')->nullable()->comment('Date limite de paiement');

            // Type et nature de la facture
            $table->enum('type_facture', [
                'consultation',     // Facturation d'une consultation standard
                'hospitalisation',  // Séjour hospitalier
                'acte_chirurgical', // Acte chirurgical
                'biologie',         // Examens de laboratoire
                'imagerie',         // Radiologie, scanner, IRM
                'pharmacie',        // Médicaments dispensés en interne
                'autre'             // Autre prestation
            ])->default('consultation')->comment('Nature de la prestation facturée');

            // Montants (en MAD - Dirham Marocain, adaptable)
            $table->decimal('montant_brut', 10, 2)->default(0.00)->comment('Montant total avant remises et prise en charge');
            $table->decimal('remise_montant', 10, 2)->default(0.00)->comment('Montant de la remise accordée');
            $table->decimal('remise_pourcentage', 5, 2)->default(0.00)->comment('Remise en pourcentage');
            $table->decimal('montant_apres_remise', 10, 2)->default(0.00)->comment('Montant brut - remise');

            // Prise en charge
            $table->decimal('montant_assurance', 10, 2)->default(0.00)->comment('Part prise en charge par l\'assurance/mutuelle');
            $table->decimal('montant_patient', 10, 2)->default(0.00)->comment('Reste à charge pour le patient');

            // TVA (si applicable)
            $table->decimal('tva_taux', 5, 2)->default(0.00)->comment('Taux de TVA en %');
            $table->decimal('tva_montant', 10, 2)->default(0.00)->comment('Montant TVA calculé');

            // Montant final
            $table->decimal('montant_total_ttc', 10, 2)->default(0.00)->comment('Montant total TTC après tous les calculs');
            $table->decimal('montant_paye', 10, 2)->default(0.00)->comment('Montant effectivement encaissé');
            $table->decimal('montant_restant', 10, 2)->default(0.00)->comment('Solde restant dû');

            // Devise
            $table->string('devise', 5)->default('MAD')->comment('Devise de la facturation');

            // Statut de la facture
            $table->enum('statut', [
                'brouillon',         // En cours de création
                'emise',             // Envoyée au patient
                'partiellement_payee', // Paiement partiel reçu
                'payee',             // Entièrement réglée
                'en_retard',         // Date d'échéance dépassée, impayée
                'annulee',           // Facture annulée
                'remboursee'         // Remboursement effectué
            ])->default('brouillon')->comment('État du paiement de la facture');

            // Paiements
            $table->enum('mode_paiement', [
                'especes',
                'cheque',
                'carte_bancaire',
                'virement',
                'assurance',
                'mixte'
            ])->nullable()->comment('Mode de paiement utilisé');

            $table->dateTime('date_paiement')->nullable()->comment('Date et heure du paiement complet');
            $table->string('reference_paiement', 100)->nullable()->comment('Référence de transaction / numéro de chèque');

            // Assurance / Mutuelle
            $table->string('organisme_assurance', 100)->nullable()->comment('Nom de l\'organisme assureur');
            $table->string('numero_prise_en_charge', 50)->nullable()->comment('Numéro de dossier de prise en charge mutuelle');

            // Notes et observation
            $table->text('designation_prestations')->nullable()->comment('Détail des prestations (si pas de table lignes_facture)');
            $table->text('notes')->nullable()->comment('Notes comptables ou observations diverses');
            $table->boolean('imprimee')->default(false)->comment('La facture a-t-elle été imprimée ou envoyée ?');

            // Audit
            $table->unsignedBigInteger('cree_par')->nullable()->comment('ID de l\'utilisateur ayant créé la facture');
            $table->unsignedBigInteger('valide_par')->nullable()->comment('ID de l\'utilisateur ayant validé la facture');
            $table->dateTime('date_validation')->nullable()->comment('Date de validation de la facture');

            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index('numero_facture');
            $table->index('statut');
            $table->index('date_emission');
            $table->index(['patient_id', 'statut']);
            $table->index(['date_emission', 'statut']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};
