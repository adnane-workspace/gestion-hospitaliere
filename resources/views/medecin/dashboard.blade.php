@extends('layouts.app')

@section('title', 'Dashboard Médecin')

@section('content')
<div class="header" style="margin-bottom: 2rem;">
    <div>
        <h1 style="font-size: 2rem;">Bonjour, {{ Auth::user()->name }}</h1>
        <p style="color: var(--secondary);">Voici votre activité pour aujourd'hui, {{ now()->format('d/m/Y') }}.</p>
    </div>
    <div class="badge badge-success" style="padding: 0.5rem 1rem;">Session Active</div>
</div>

<!-- Stats Réelles -->
<div class="stats-grid" style="margin-bottom: 2rem;">
    <div class="card">
        <div class="stat-label">RDV Aujourd'hui</div>
        <div class="stat-value">{{ $stats['rdv_today'] }}</div>
    </div>
    <div class="card">
        <div class="stat-label">Consultations (Ce mois)</div>
        <div class="stat-value">{{ $stats['consultations_done'] }}</div>
    </div>
    <div class="card">
        <div class="stat-label">Nouveaux Patients (Hôpital)</div>
        <div class="stat-value">{{ $stats['new_patients'] }}</div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 2rem; align-items: start;">
    <!-- Agenda Réel -->
    <div class="card" style="min-height: 400px;">
        <h3 style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px;">
            📅 Agenda du Jour
        </h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Heure</th>
                        <th>Patient</th>
                        <th>Motif</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $rdv)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($rdv->date_heure_debut)->format('H:i') }}</td>
                        <td><strong>{{ $rdv->patient->user->name }}</strong></td>
                        <td>{{ $rdv->motif ?? 'Consultation' }}</td>
                        <td style="text-align: right;">
                            <a href="#" class="badge badge-success" style="text-decoration:none;">Consulter</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 2rem; color: var(--secondary);">
                            Aucun rendez-vous pour aujourd'hui.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Graphique (à brancher plus tard sur l'API) -->
    <div class="card" style="min-height: 400px;">
        <h3 style="margin-bottom: 1.5rem;">📈 Activité Hebdomadaire</h3>
        <div style="height: 300px;">
            <canvas id="activityChart"></canvas>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('activityChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven'],
                datasets: [{
                    label: 'Consultations',
                    data: [5, 8, 4, 10, 6], // Données exemple pour l'instant
                    backgroundColor: '#2563eb',
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { display: false } },
                    x: { grid: { display: false } }
                }
            }
        });
    });
</script>
@endsection
