<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table : services
     * Entité représentant les services hospitaliers (Cardiologie, Urgences, Pédiatrie, etc.)
     */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();

            $table->string('nom', 150)->comment('Nom du service (ex: Cardiologie, Pédiatrie)');
            $table->string('code', 20)->unique()->comment('Code unique du service (ex: CARD, PED)');
            $table->text('description')->nullable()->comment('Description détaillée du service');
            $table->string('localisation', 100)->nullable()->comment('Localisation physique (bâtiment, étage, salle)');
            $table->string('telephone', 20)->nullable()->comment('Numéro de téléphone interne du service');
            $table->string('email', 100)->nullable()->comment('Adresse email du service');
            $table->integer('capacite_lits')->default(0)->comment('Nombre total de lits disponibles');
            $table->enum('statut', ['actif', 'inactif', 'en_maintenance'])
                  ->default('actif')
                  ->comment('État opérationnel du service');

            $table->timestamps();
            $table->softDeletes()->comment('Suppression logique du service');

            // Index pour les recherches fréquentes
            $table->index('statut');
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
