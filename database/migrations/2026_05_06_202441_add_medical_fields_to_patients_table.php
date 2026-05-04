<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->text('antecedents_chirurgicaux')->nullable()->after('antecedents_familiaux')->comment('Antécédents chirurgicaux');
            $table->text('maladies_chroniques')->nullable()->after('antecedents_chirurgicaux')->comment('Maladies chroniques (diabète, hypertension, etc.)');
            $table->string('tension_arterielle', 20)->nullable()->after('poids')->comment('Tension artérielle (ex: 120/80)');
            $table->integer('frequence_cardiaque')->nullable()->after('tension_arterielle')->comment('Fréquence cardiaque en bpm');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn(['antecedents_chirurgicaux', 'maladies_chroniques', 'tension_arterielle', 'frequence_cardiaque']);
        });
    }
};
