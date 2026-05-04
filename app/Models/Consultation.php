<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Consultation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'rendezvous_id', 'patient_id', 'medecin_id', 'service_id',
        'reference', 'date_heure', 'motif_consultation', 'diagnostic_principal', 'statut'
    ];

    protected $casts = [
        'date_heure' => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function medecin(): BelongsTo
    {
        return $this->belongsTo(Medecin::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function rendezvous(): BelongsTo
    {
        return $this->belongsTo(RendezVous::class, 'rendezvous_id');
    }
}
