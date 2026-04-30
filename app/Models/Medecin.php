<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Medecin extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'service_id',
        'matricule',
        'nom',
        'prenom',
        'specialite',
        'email',
        'telephone',
        'statut'
    ];

    /**
     * Un médecin appartient à un service.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Un médecin a plusieurs rendez-vous.
     */
    public function rendezvous(): HasMany
    {
        return $this->hasMany(RendezVous::class);
    }
}
