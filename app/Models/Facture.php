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
        'consultation_id', 'patient_id', 'numero_facture',
        'montant_total_ttc', 'statut', 'date_emission'
    ];

    protected $casts = [
        'date_emission' => 'date',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
