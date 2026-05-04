<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'allergies' => 'array',
        'antecedents_medicaux' => 'array',
        'antecedents_familiaux' => 'array',
        'antecedents_chirurgicaux' => 'array',
        'maladies_chroniques' => 'array',
        'medicaments_actuels' => 'array',
        'date_naissance' => 'date',
        'date_admission' => 'date',
    ];

    /**
     * Get the user that owns the patient profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function medecinTraitant()
    {
        return $this->belongsTo(Medecin::class, 'medecin_traitant_id');
    }

    public function rendezvous()
    {
        return $this->hasMany(RendezVous::class);
    }

    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    /**
     * Calcul automatique de l'IMC
     */
    public function getImcAttribute()
    {
        if ($this->taille && $this->poids && $this->taille > 0) {
            $tailleEnMetres = $this->taille / 100;
            return round($this->poids / ($tailleEnMetres * $tailleEnMetres), 2);
        }
        return null;
    }

    /**
     * Catégorie IMC
     */
    public function getImcCategoryAttribute()
    {
        $imc = $this->imc;
        if (!$imc) return null;

        if ($imc < 18.5) return 'Insuffisance pondérale';
        if ($imc < 25) return 'Poids normal';
        if ($imc < 30) return 'Surpoids';
        if ($imc < 35) return 'Obésité modérée';
        if ($imc < 40) return 'Obésité sévère';
        return 'Obésité morbide';
    }
}
