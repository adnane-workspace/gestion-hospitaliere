<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RendezVous extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUT_PLANIFIE = 'planifie';
    public const STATUT_CONFIRME = 'confirme';
    public const STATUT_EN_ATTENTE = 'en_attente';
    public const STATUT_EN_COURS = 'en_cours';
    public const STATUT_TERMINE = 'termine';
    public const STATUT_ANNULE = 'annule';
    public const STATUT_REPORTE = 'reporte';
    public const STATUT_PATIENT_ABSENT = 'patient_absent';

    protected $table = 'rendezvous';

    protected $fillable = [
        'patient_id',
        'medecin_id',
        'service_id',
        'reference',
        'date_heure_debut',
        'date_heure_fin',
        'duree_minutes',
        'statut',
        'motif',
        'type_rendez_vous',
        'canal_prise_rdv'
    ];

    protected $casts = [
        'date_heure_debut' => 'datetime',
        'date_heure_fin' => 'datetime',
    ];

    /**
     * Un rendez-vous appartient à un patient.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Un rendez-vous appartient à un médecin.
     */
    public function medecin(): BelongsTo
    {
        return $this->belongsTo(Medecin::class);
    }

    public function canTransitionTo(string $newStatut): bool
    {
        $allowedTransitions = [
            self::STATUT_PLANIFIE => [self::STATUT_CONFIRME, self::STATUT_ANNULE, self::STATUT_REPORTE, self::STATUT_EN_ATTENTE],
            self::STATUT_CONFIRME => [self::STATUT_EN_ATTENTE, self::STATUT_ANNULE, self::STATUT_REPORTE, self::STATUT_EN_COURS],
            self::STATUT_EN_ATTENTE => [self::STATUT_EN_COURS, self::STATUT_ANNULE, self::STATUT_PATIENT_ABSENT],
            self::STATUT_EN_COURS => [self::STATUT_TERMINE, self::STATUT_ANNULE],
            self::STATUT_TERMINE => [],
            self::STATUT_ANNULE => [],
            self::STATUT_REPORTE => [self::STATUT_PLANIFIE, self::STATUT_CONFIRME],
            self::STATUT_PATIENT_ABSENT => [],
        ];

        return in_array($newStatut, $allowedTransitions[$this->statut] ?? [], true);
    }
}
