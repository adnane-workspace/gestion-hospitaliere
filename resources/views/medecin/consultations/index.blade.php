@extends('layouts.app')

@section('title', 'Mes Consultations')

@section('content')
<div class="header" style="margin-bottom: 2rem;">
    <div>
        <h1 style="font-size: 2rem;">Mes Consultations</h1>
        <p style="color: var(--secondary);">Historique complet de vos actes médicaux.</p>
    </div>
</div>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Date & Heure</th>
                    <th>Patient</th>
                    <th>Service</th>
                    <th>Diagnostic Principal</th>
                    <th>Statut</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($consultations as $consultation)
                <tr>
                    <td>{{ $consultation->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <strong>{{ $consultation->patient->user->name }}</strong><br>
                        <small style="color: var(--secondary);">{{ $consultation->patient->telephone }}</small>
                    </td>
                    <td>{{ $consultation->service->nom ?? 'N/A' }}</td>
                    <td>{{ Str::limit($consultation->diagnostic_principal, 60) }}</td>
                    <td>
                        <span class="badge {{ $consultation->statut === 'Terminé' ? 'badge-success' : 'badge-warning' }}">
                            {{ $consultation->statut }}
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <div style="display: flex; gap: 5px; justify-content: flex-end;">
                            <a href="#" class="badge" style="background: var(--primary-light); color: var(--primary); text-decoration:none;">Voir</a>
                            <a href="#" class="badge" style="background: #f1f5f9; color: var(--text-main); text-decoration:none;">Modifier</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 3rem; color: var(--secondary);">
                        Aucune consultation trouvée.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top: 1.5rem;">
        {{ $consultations->links() }}
    </div>
</div>
@endsection
