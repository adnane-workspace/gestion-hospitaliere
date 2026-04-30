<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table : lignes_facture
     * Détail des prestations facturées ligne par ligne.
     * Relations :
     *   - belongsTo Facture (cascade delete)
     */
    public function up(): void
    {
        Schema::create('lignes_facture', function (Blueprint $table) {
            $table->id();

            $table->foreignId('facture_id')
                  ->constrained('factures')
                  ->onDelete('cascade')
                  ->comment('Facture parente');

            // Prestation
            $table->string('code_acte', 30)->nullable()->comment('Code de l\'acte médical ou prestation');
            $table->string('designation', 255)->comment('Désignation de la prestation (ex: Consultation générale, ECG...)');
            $table->text('description')->nullable()->comment('Description détaillée de la prestation');
            $table->enum('categorie', [
                'consultation',
                'acte_medical',
                'medicament',
                'analyse',
                'imagerie',
                'chambre',
                'materiel',
                'autre'
            ])->default('acte_medical')->comment('Catégorie de la prestation');

            // Prix et quantité
            $table->decimal('prix_unitaire', 10, 2)->comment('Prix unitaire HT de la prestation');
            $table->decimal('quantite', 8, 2)->default(1.00)->comment('Quantité facturée');
            $table->decimal('montant_ht', 10, 2)->comment('Montant HT (prix_unitaire × quantité)');
            $table->decimal('tva_taux', 5, 2)->default(0.00)->comment('Taux de TVA applicable à cette ligne');
            $table->decimal('tva_montant', 10, 2)->default(0.00)->comment('Montant de TVA pour cette ligne');
            $table->decimal('remise_pourcentage', 5, 2)->default(0.00)->comment('Remise en % sur cette ligne');
            $table->decimal('remise_montant', 10, 2)->default(0.00)->comment('Montant de remise sur cette ligne');
            $table->decimal('montant_ttc', 10, 2)->comment('Montant TTC final de la ligne');

            // Prise en charge
            $table->decimal('part_assurance', 10, 2)->default(0.00)->comment('Portion prise en charge par l\'assurance');
            $table->decimal('part_patient', 10, 2)->default(0.00)->comment('Portion restant à charge du patient');

            // Ordre d'affichage
            $table->integer('ordre')->default(1)->comment('Ordre de la ligne dans la facture');

            $table->timestamps();

            // Index
            $table->index('facture_id');
            $table->index('code_acte');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lignes_facture');
    }
};
