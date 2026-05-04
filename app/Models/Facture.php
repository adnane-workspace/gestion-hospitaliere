<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Facture extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'consultation_id', 'patient_id', 'medecin_id', 'service_id',
        'numero_facture', 'date_emission', 'date_echeance',
        'type_facture', 'montant_brut', 'remise_montant', 'remise_pourcentage',
        'montant_apres_remise', 'montant_assurance', 'montant_patient',
        'tva_taux', 'tva_montant', 'montant_total_ttc', 'montant_paye',
        'montant_restant', 'devise', 'statut', 'mode_paiement',
        'date_paiement', 'reference_paiement', 'organisme_assurance',
        'numero_prise_en_charge', 'designation_prestations', 'notes',
        'imprimee', 'cree_par', 'valide_par', 'date_validation',
    ];

    protected $casts = [
        'date_emission' => 'date',
        'date_echeance' => 'date',
        'date_paiement' => 'datetime',
        'date_validation' => 'datetime',
        'montant_brut' => 'decimal:2',
        'remise_montant' => 'decimal:2',
        'remise_pourcentage' => 'decimal:2',
        'montant_apres_remise' => 'decimal:2',
        'montant_assurance' => 'decimal:2',
        'montant_patient' => 'decimal:2',
        'tva_taux' => 'decimal:2',
        'tva_montant' => 'decimal:2',
        'montant_total_ttc' => 'decimal:2',
        'montant_paye' => 'decimal:2',
        'montant_restant' => 'decimal:2',
        'imprimee' => 'boolean',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
