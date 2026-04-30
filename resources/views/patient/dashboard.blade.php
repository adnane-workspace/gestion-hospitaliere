@extends('layouts.app')

@section('title', 'Mon Espace Patient')

@section('content')
<div class="header">
    <h1>Bienvenue, {{ Auth::user()->name }}</h1>
    <button style="background: var(--primary); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 0.5rem; font-weight: 600; cursor: pointer;">
        Prendre un Rendez-vous
    </button>
</div>

<div class="stats-grid">
    <div class="card" style="border-left: 4px solid var(--primary);">
        <div class="stat-label">Prochain RDV</div>
        <div class="stat-value" style="font-size: 1.1rem;">15 Mai 2024 - 10:30</div>
        <div class="stat-label">Dr. Ahmed Alami</div>
    </div>
    <div class="card">
        <div class="stat-label">Dernière Consultation</div>
        <div class="stat-label">12 Avril 2024</div>
    </div>
    <div class="card">
        <div class="stat-label">Groupe Sanguin</div>
        <div class="stat-value" style="color: var(--danger);">O+</div>
    </div>
</div>

<div class="card">
    <h3 style="margin-bottom: 1.5rem;">Mon Historique Médical</h3>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Médecin</th>
                    <th>Service</th>
                    <th>Document</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>12/04/2024</td>
                    <td>Dr. Alami</td>
                    <td>Cardiologie</td>
                    <td><a href="#" style="color: var(--primary);">Ordonnance.pdf</a></td>
                </tr>
                <tr>
                    <td>15/02/2024</td>
                    <td>Dr. Bennani</td>
                    <td>Généraliste</td>
                    <td><a href="#" style="color: var(--primary);">Facture.pdf</a></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
