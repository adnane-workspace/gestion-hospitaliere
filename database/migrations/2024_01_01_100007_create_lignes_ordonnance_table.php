<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table : lignes_ordonnance
     * Table de détail des médicaments prescrits dans une ordonnance.
     * Un médicament par ligne avec sa posologie complète.
     * Relations :
     *   - belongsTo Ordonnance (cascade delete)
     */
    public function up(): void
    {
        Schema::create('lignes_ordonnance', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ordonnance_id')
                  ->constrained('ordonnances')
                  ->onDelete('cascade')
                  ->comment('Ordonnance parente');

            // Médicament
            $table->string('nom_medicament', 200)->comment('Dénomination commune internationale (DCI) ou nom commercial');
            $table->string('forme_galénique', 80)->nullable()->comment('Forme du médicament (comprimé, sirop, injectable, crème...)');
            $table->string('dosage', 80)->nullable()->comment('Dosage unitaire (ex: 500mg, 10mg/5ml)');
            $table->string('code_atc', 20)->nullable()->comment('Code ATC de classification du médicament');

            // Posologie
            $table->text('posologie')->comment('Posologie complète (ex: 1 comprimé matin et soir pendant 7 jours)');
            $table->string('frequence', 100)->nullable()->comment('Fréquence de prise (ex: 2 fois/jour, toutes les 8h)');
            $table->string('duree_traitement', 80)->nullable()->comment('Durée du traitement (ex: 10 jours, 1 mois)');
            $table->string('moment_prise', 100)->nullable()->comment('Moment de la prise (avant repas, au coucher, etc.)');
            $table->integer('quantite')->default(1)->comment('Quantité à délivrer');
            $table->string('unite_quantite', 30)->default('boîte')->comment('Unité (boîte, flacon, tube, ampoule...)');

            // Instructions spéciales
            $table->text('instructions_speciales')->nullable()->comment('Instructions particulières ou mises en garde');
            $table->boolean('sans_substitution')->default(false)->comment('NS - Ne pas substituer par un générique');

            // Ordre dans l'ordonnance
            $table->integer('ordre')->default(1)->comment('Numéro d\'ordre d\'affichage dans l\'ordonnance');

            $table->timestamps();

            // Index
            $table->index('ordonnance_id');
            $table->index('nom_medicament');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lignes_ordonnance');
    }
};
