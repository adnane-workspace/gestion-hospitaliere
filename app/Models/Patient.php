<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'numero_dossier',
        'nom',
        'prenom',
        'genre',
        'date_naissance',
        'telephone',
        'email'
    ];

    /**
     * Un patient a plusieurs rendez-vous.
     */
    public function rendezvous(): HasMany
    {
        return $this->hasMany(RendezVous::class);
    }
}
