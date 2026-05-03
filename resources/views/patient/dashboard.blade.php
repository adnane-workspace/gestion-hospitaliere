@extends('layouts.app')

@section('title', 'Mon Espace Santé')

@section('content')
<div class="header">
    <div>
        <h1 style="color: white; margin-bottom: 0.5rem;">Bienvenue, {{ Auth::user()->name }}</h1>
        <p style="color: rgba(255,255,255,0.7); font-size: 1.1rem;">Ravi de vous revoir. Prenez soin de votre santé aujourd'hui.</p>
    </div>
    <a href="{{ route('patient.rendezvous.create') }}" class="btn-primary">
        <i data-lucide="calendar-plus"></i> Prendre un Rendez-vous
    </a>
</div>

<div class="stats-grid">
    <!-- Prochain RDV Card -->
    <div class="stat-card" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <div class="stat-label">Prochain Rendez-vous</div>
                @if($nextAppointment)
                    <div class="stat-value">{{ $nextAppointment->date_heure_debut->format('d M Y') }}</div>
                    <div style="margin-top: 0.5rem; display: flex; align-items: center; gap: 8px; font-size: 0.9rem; opacity: 0.9;">
                        <i data-lucide="clock" style="width: 16px;"></i> {{ $nextAppointment->date_heure_debut->format('H:i') }}
                    </div>
                @else
                    <div class="stat-value" style="font-size: 1.25rem; opacity: 0.7;">Aucun RDV prévu</div>
                @endif
            </div>
            <div style="background: rgba(255,255,255,0.2); padding: 12px; border-radius: 16px;">
                <i data-lucide="calendar"></i>
            </div>
        </div>
        @if($nextAppointment)
            <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.2); font-size: 0.9rem;">
                Avec <strong>Dr. {{ $nextAppointment->medecin->user->name }}</strong>
            </div>
        @endif
    </div>

    <!-- Dernière Consultation -->
    <div class="stat-card" style="background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%);">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <div class="stat-label">Dernière Consultation</div>
                <div class="stat-value">
                    {{ $history->first() ? $history->first()->created_at->format('d M Y') : '---' }}
                </div>
            </div>
            <div style="background: rgba(255,255,255,0.2); padding: 12px; border-radius: 16px;">
                <i data-lucide="history"></i>
            </div>
        </div>
        <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.2); font-size: 0.9rem;">
            {{ $history->first() ? 'Service : ' . ($history->first()->service->nom ?? 'Général') : 'Aucun historique' }}
        </div>
    </div>

    <!-- Groupe Sanguin -->
    <div class="stat-card" style="background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <div class="stat-label">Groupe Sanguin</div>
                <div class="stat-value" style="font-size: 2.5rem;">{{ $patient->groupe_sanguin ?? '??' }}</div>
            </div>
            <div style="background: rgba(255,255,255,0.2); padding: 12px; border-radius: 16px;">
                <i data-lucide="droplet"></i>
            </div>
        </div>
        <div style="margin-top: 0.5rem; font-size: 0.9rem; opacity: 0.8;">Information vitale</div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
    <!-- Historique -->
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h3 style="font-size: 1.25rem; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                <i data-lucide="file-text" style="color: var(--primary);"></i> Mon Historique Médical
            </h3>
            <a href="#" style="color: var(--primary); text-decoration: none; font-size: 0.875rem; font-weight: 600;">Voir tout</a>
        </div>
        
        <div class="table-container" style="box-shadow: none; border: 1px solid #f1f5f9;">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Médecin</th>
                        <th>Service</th>
                        <th>Diagnostic</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($history as $item)
                    <tr>
                        <td style="font-weight: 600;">{{ $item->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 32px; height: 32px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700; color: var(--primary);">
                                    {{ substr($item->medecin->user->name, 0, 1) }}
                                </div>
                                Dr. {{ $item->medecin->user->name }}
                            </div>
                        </td>
                        <td><span class="badge badge-primary">{{ $item->service->nom ?? 'Général' }}</span></td>
                        <td style="color: #64748b;">{{ Str::limit($item->diagnostic_principal, 30) }}</td>
                        <td>
                            <button style="background: none; border: none; color: var(--primary); cursor: pointer;">
                                <i data-lucide="eye" style="width: 18px;"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 3rem; color: #94a3b8;">
                            <i data-lucide="folder-open" style="width: 48px; height: 48px; margin-bottom: 1rem; opacity: 0.5;"></i>
                            <p>Aucun historique médical disponible.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions / Info -->
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <div class="card" style="background: var(--primary); color: white;">
            <h4 style="margin-bottom: 1rem;">Besoin d'aide ?</h4>
            <p style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 1.5rem;">Notre équipe médicale est disponible 24/7 pour vos urgences.</p>
            <a href="tel:0500000000" class="btn-primary" style="background: white; color: var(--primary); width: 100%; justify-content: center;">
                <i data-lucide="phone-call"></i> Appeler l'Hôpital
            </a>
        </div>

        <div class="card">
            <h4 style="margin-bottom: 1rem;">Mes Documents</h4>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <div style="display: flex; align-items: center; gap: 12px; padding: 12px; background: #f8fafc; border-radius: 12px;">
                    <i data-lucide="file-plus" style="color: var(--danger);"></i>
                    <div style="flex-grow: 1;">
                        <div style="font-size: 0.875rem; font-weight: 600;">Dernière Ordonnance</div>
                        <div style="font-size: 0.75rem; color: #64748b;">PDF - 1.2 MB</div>
                    </div>
                    <i data-lucide="download" style="width: 16px; color: #94a3b8; cursor: pointer;"></i>
                </div>
                <div style="display: flex; align-items: center; gap: 12px; padding: 12px; background: #f8fafc; border-radius: 12px;">
                    <i data-lucide="file-text" style="color: var(--primary);"></i>
                    <div style="flex-grow: 1;">
                        <div style="font-size: 0.875rem; font-weight: 600;">Résultats Analyse</div>
                        <div style="font-size: 0.75rem; color: #64748b;">PDF - 0.8 MB</div>
                    </div>
                    <i data-lucide="download" style="width: 16px; color: #94a3b8; cursor: pointer;"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
