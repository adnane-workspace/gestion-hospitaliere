@extends('layouts.app')

@section('title', 'Mes Rendez-vous')

@section('content')
<div class="header">
    <div>
        <h1 style="color: white; margin-bottom: 0.5rem;">Mes Rendez-vous</h1>
        <p style="color: rgba(255,255,255,0.7); font-size: 1.1rem;">Suivez et gérez vos rendez-vous médicaux.</p>
    </div>
    <a href="{{ route('patient.rendezvous.create') }}" class="btn-primary">
        <i data-lucide="calendar-plus"></i> Nouveau Rendez-vous
    </a>
</div>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h3 style="font-size: 1.25rem; font-weight: 700; display: flex; align-items: center; gap: 10px;">
            <i data-lucide="calendar" style="color: var(--primary);"></i> Liste de mes rendez-vous
        </h3>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Référence</th>
                    <th>Date & Heure</th>
                    <th>Médecin</th>
                    <th>Motif</th>
                    <th>Statut</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($appointments as $rdv)
                <tr>
                    <td><code style="background: #f1f5f9; padding: 0.2rem 0.5rem; border-radius: 4px;">{{ $rdv->reference }}</code></td>
                    <td>
                        <div style="font-weight: 600;">{{ $rdv->date_heure_debut->format('d/m/Y') }}</div>
                        <div style="font-size: 0.75rem; color: #64748b;">{{ $rdv->date_heure_debut->format('H:i') }}</div>
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="width: 32px; height: 32px; background: var(--primary-light); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700; color: var(--primary);">
                                {{ substr($rdv->medecin->user->name, 0, 1) }}
                            </div>
                            Dr. {{ $rdv->medecin->user->name }}
                        </div>
                    </td>
                    <td>{{ Str::limit($rdv->motif, 40) }}</td>
                    <td>
                        @php
                            $badgeClass = match($rdv->statut) {
                                'planifie' => 'badge-primary',
                                'confirme' => 'badge-success',
                                'annule' => 'badge-danger',
                                default => 'badge-primary',
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}">
                            {{ ucfirst(str_replace('_', ' ', $rdv->statut)) }}
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <div style="display: flex; gap: 8px; justify-content: flex-end;">
                            <button style="background: none; border: none; color: var(--secondary); cursor: pointer;" title="Détails">
                                <i data-lucide="info" style="width: 18px;"></i>
                            </button>
                            @if($rdv->statut === 'planifie' || $rdv->statut === 'confirme')
                                <button style="background: none; border: none; color: var(--danger); cursor: pointer;" title="Annuler">
                                    <i data-lucide="x-circle" style="width: 18px;"></i>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 4rem; color: #94a3b8;">
                        <div style="background: #f8fafc; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                            <i data-lucide="calendar-off" style="width: 40px; height: 40px; opacity: 0.3;"></i>
                        </div>
                        <p style="font-size: 1.1rem; font-weight: 500;">Aucun rendez-vous trouvé.</p>
                        <p style="font-size: 0.875rem; margin-top: 0.5rem;">Vous n'avez pas encore planifié de consultation.</p>
                        <a href="{{ route('patient.rendezvous.create') }}" class="btn-primary" style="margin-top: 1.5rem;">Prendre mon premier RDV</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div style="margin-top: 2rem;">
        {{ $appointments->links() }}
    </div>
</div>
@endsection
