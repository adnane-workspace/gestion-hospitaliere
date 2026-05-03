@extends('layouts.app')

@section('title', 'Dossier Patient - ' . $patient->nom)

@section('content')
<div class="header" style="margin-bottom: 2rem;">
    <div>
        <h1 style="font-size: 2rem;">Dossier Médical : {{ $patient->nom }} {{ $patient->prenom }}</h1>
        <p style="color: var(--secondary);">N° Dossier : <strong>{{ $patient->numero_dossier }}</strong> | CIN : {{ $patient->cin ?? 'N/A' }}</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('patients.edit', $patient) }}" class="badge" style="background: #f1f5f9; color: var(--text-main); text-decoration:none; padding: 0.75rem 1.5rem;">Modifier le dossier</a>
        <a href="{{ route('patients.index') }}" class="badge" style="background: var(--secondary); color: white; text-decoration:none; padding: 0.75rem 1.5rem;">Retour à la liste</a>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; align-items: start;">
    <!-- Infos Civiles -->
    <div class="card">
        <h3 style="margin-bottom: 1.5rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.5rem;">👤 Informations Personnelles</h3>
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <div>
                <small style="color: var(--secondary); text-transform: uppercase; font-size: 0.7rem;">Genre & Naissance</small>
                <p><strong>{{ ucfirst($patient->genre) }}</strong>, né(e) le {{ \Carbon\Carbon::parse($patient->date_naissance)->format('d/m/Y') }}</p>
            </div>
            <div>
                <small style="color: var(--secondary); text-transform: uppercase; font-size: 0.7rem;">Contact</small>
                <p>📞 {{ $patient->telephone }}</p>
                <p>📧 {{ $patient->email ?? 'Non renseigné' }}</p>
            </div>
            <div>
                <small style="color: var(--secondary); text-transform: uppercase; font-size: 0.7rem;">Adresse</small>
                <p>{{ $patient->adresse ?? 'Non renseignée' }}</p>
                <p>{{ $patient->ville }} {{ $patient->code_postal }}</p>
            </div>
            <div style="background: var(--primary-light); padding: 1rem; border-radius: 0.5rem;">
                <small style="color: var(--primary); text-transform: uppercase; font-size: 0.7rem;">Médecin Traitant</small>
                <p><strong>Dr. {{ $patient->medecinTraitant->user->name ?? 'Non assigné' }}</strong></p>
            </div>
        </div>
    </div>

    <!-- Infos Médicales & Historique -->
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <!-- Résumé Médical -->
        <div class="card">
            <h3 style="margin-bottom: 1.5rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.5rem;">🩺 Données Vitales & Médicales</h3>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem;">
                <div style="text-align: center; border-right: 1px solid #f1f5f9;">
                    <small style="color: var(--secondary);">Groupe Sanguin</small>
                    <p style="font-size: 1.25rem; font-weight: 700; color: var(--danger);">{{ $patient->groupe_sanguin ?? '??' }}</p>
                </div>
                <div style="text-align: center; border-right: 1px solid #f1f5f9;">
                    <small style="color: var(--secondary);">Poids</small>
                    <p style="font-size: 1.25rem; font-weight: 700;">{{ $patient->poids ?? '--' }} kg</p>
                </div>
                <div style="text-align: center;">
                    <small style="color: var(--secondary);">Taille</small>
                    <p style="font-size: 1.25rem; font-weight: 700;">{{ $patient->taille ?? '--' }} cm</p>
                </div>
            </div>
            <div style="margin-top: 1.5rem;">
                <small style="color: var(--secondary); text-transform: uppercase; font-size: 0.7rem;">Allergies</small>
                <p style="color: var(--danger);">{{ $patient->allergies ?? 'Aucune allergie connue' }}</p>
            </div>
        </div>

        <!-- Historique Consultations -->
        <div class="card">
            <h3 style="margin-bottom: 1.5rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.5rem;">📄 Historique des Consultations</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Médecin</th>
                            <th>Diagnostic</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patient->consultations as $consultation)
                        <tr>
                            <td>{{ $consultation->created_at->format('d/m/Y') }}</td>
                            <td>Dr. {{ $consultation->medecin->user->name }}</td>
                            <td>{{ Str::limit($consultation->diagnostic_principal, 50) }}</td>
                            <td style="text-align: right;">
                                <a href="#" class="badge" style="background: var(--primary-light); color: var(--primary); text-decoration:none;">Détails</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 2rem; color: var(--secondary);">Aucune consultation passée.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
