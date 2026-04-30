<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table : ordonnances
     * Prescriptions médicales générées lors d'une consultation.
     * Relations :
     *   - belongsTo Consultation (cascade delete)
     *   - belongsTo Patient      (cascade delete)
     *   - belongsTo Medecin      (cascade delete)
     *   - hasMany   LigneOrdonnance (table pivot pour les médicaments)
     */
    public function up(): void
    {
        Schema::create('ordonnances', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('consultation_id')
                  ->constrained('consultations')
                  ->onDelete('cascade')
                  ->comment('Consultation lors de laquelle l\'ordonnance a été émise');

            $table->foreignId('patient_id')
                  ->constrained('patients')
                  ->onDelete('cascade')
                  ->comment('Patient bénéficiaire de l\'ordonnance');

            $table->foreignId('medecin_id')
                  ->constrained('medecins')
                  ->onDelete('cascade')
                  ->comment('Médecin prescripteur');

            // Identification de l'ordonnance
            $table->string('numero', 30)->unique()->comment('Numéro unique de l\'ordonnance (ex: ORD-2024-00001)');
            $table->date('date_prescription')->comment('Date d\'émission de l\'ordonnance');
            $table->date('date_validite')->nullable()->comment('Date de fin de validité de l\'ordonnance');

            // Type d'ordonnance
            $table->enum('type', [
                'standard',        // Ordonnance standard
                'bizon',           // Ordonnance sécurisée (médicaments à risque)
                'longue_duree',    // ALD - Affection de longue durée
                'libre',           // Ordonnance de médicaments en vente libre
                'hospitaliere'     // Ordonnance hospitalière
            ])->default('standard')->comment('Type d\'ordonnance');

            // Contenu textuel global (utile si structure libre)
            $table->text('prescriptions')->comment('Contenu structuré des prescriptions (médicaments, posologie, durée)');
            $table->text('instructions_patient')->nullable()->comment('Instructions et conseils pour le patient');
            $table->text('notes_pharmacien')->nullable()->comment('Notes à l\'attention du pharmacien');

            // Régime et alimentation
            $table->text('regime_alimentaire')->nullable()->comment('Prescriptions diététiques associées');

            // Actes paramédicaux prescrits
            $table->text('kinesitherapie')->nullable()->comment('Séances de kinésithérapie prescrites');
            $table->text('soins_infirmiers')->nullable()->comment('Soins infirmiers prescrits à domicile');

            // Statut de l'ordonnance
            $table->enum('statut', [
                'active',     // En cours, non encore dispensée entièrement
                'dispensee',  // Dispensée en pharmacie
                'expiree',    // Date de validité dépassée
                'annulee'     // Annulée par le médecin
            ])->default('active')->comment('Statut de dispensation de l\'ordonnance');

            // Renouvellement
            $table->boolean('renouvelable')->default(false)->comment('L\'ordonnance est-elle renouvelable ?');
            $table->integer('nombre_renouvellements')->default(0)->comment('Nombre de renouvellements autorisés');
            $table->integer('renouvellements_effectues')->default(0)->comment('Nombre de renouvellements déjà effectués');

            // Prise en charge
            $table->boolean('prise_en_charge')->default(false)->comment('Prise en charge par la mutuelle/CNSS ?');
            $table->decimal('taux_remboursement', 5, 2)->nullable()->comment('Taux de remboursement en %');

            $table->text('signature_medecin')->nullable()->comment('Signature numérique du médecin (hash ou base64)');
            $table->boolean('imprimee')->default(false)->comment('L\'ordonnance a-t-elle été imprimée ?');

            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index('numero');
            $table->index('statut');
            $table->index('date_prescription');
            $table->index(['patient_id', 'date_prescription']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordonnances');
    }
};
