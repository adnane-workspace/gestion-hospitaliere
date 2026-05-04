<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medecin_disponibilites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medecin_id')->constrained('medecins')->cascadeOnDelete();
            $table->unsignedTinyInteger('jour_semaine');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['medecin_id', 'jour_semaine']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medecin_disponibilites');
    }
};
