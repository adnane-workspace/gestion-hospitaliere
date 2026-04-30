@extends('layouts.app')

@section('title', 'Dashboard Médecin')

@section('content')
<div class="header" style="margin-bottom: 2rem;">
    <div>
        <h1 style="font-size: 2rem;">Bonjour, {{ Auth::user()->name }}</h1>
        <p style="color: var(--secondary);">Voici votre activité pour aujourd'hui.</p>
    </div>
    <div class="badge badge-success" style="padding: 0.5rem 1rem;">Session Active</div>
</div>

<!-- Stats en ligne -->
<div class="stats-grid" style="margin-bottom: 2rem;">
    <div class="card">
        <div class="stat-label">RDV Aujourd'hui</div>
        <div class="stat-value">8</div>
    </div>
    <div class="card">
        <div class="stat-label">Consultations Terminées</div>
        <div class="stat-value">5</div>
    </div>
    <div class="card">
        <div class="stat-label">Nouveaux Patients (Mois)</div>
        <div class="stat-value">12</div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 2rem; align-items: start;">
    <!-- Liste des RDV du jour -->
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
                    <tr>
                        <td>09:00</td>
                        <td><strong>Yassine Mansouri</strong></td>
                        <td>Suivi Tension</td>
                        <td style="text-align: right;"><button class="badge badge-success" style="border:none; cursor:pointer; padding: 6px 12px;">Consulter</button></td>
                    </tr>
                    <tr>
                        <td>10:30</td>
                        <td><strong>Sara Bennani</strong></td>
                        <td>Contrôle</td>
                        <td style="text-align: right;"><button class="badge badge-success" style="border:none; cursor:pointer; padding: 6px 12px;">Consulter</button></td>
                    </tr>
                    <tr>
                        <td>11:15</td>
                        <td><strong>Karim Tazi</strong></td>
                        <td>Urgence</td>
                        <td style="text-align: right;"><button class="badge badge-success" style="border:none; cursor:pointer; padding: 6px 12px;">Consulter</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Graphique d'activité -->
    <div class="card" style="min-height: 400px;">
        <h3 style="margin-bottom: 1.5rem;">📈 Volume de Consultations</h3>
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
                    label: 'Nombre de patients',
                    data: [12, 19, 15, 8, 12],
                    backgroundColor: '#3b82f6',
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, grid: { display: false } },
                    x: { grid: { display: false } }
                }
            }
        });
    });
</script>
@endsection
