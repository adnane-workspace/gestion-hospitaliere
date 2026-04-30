<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['nom', 'code', 'description', 'statut'];

    public function medecins(): HasMany
    {
        return $this->hasMany(Medecin::class);
    }

    public function rendezvous(): HasMany
    {
        return $this->hasMany(RendezVous::class);
    }
}
