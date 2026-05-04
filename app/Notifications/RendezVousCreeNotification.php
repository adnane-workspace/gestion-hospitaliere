<?php

namespace App\Notifications;

use App\Models\RendezVous;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RendezVousCreeNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly RendezVous $rendezVous,
        private readonly string $audience = 'patient'
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->audience === 'medecin' ? 'Nouveau rendez-vous planifie' : 'Rendez-vous confirme',
            'reference' => $this->rendezVous->reference,
            'date_heure_debut' => optional($this->rendezVous->date_heure_debut)?->format('Y-m-d H:i:s'),
            'medecin' => optional(optional($this->rendezVous->medecin)->user)->name,
            'patient' => optional(optional($this->rendezVous->patient)->user)->name,
            'motif' => $this->rendezVous->motif,
            'statut' => $this->rendezVous->statut,
        ];
    }
}
