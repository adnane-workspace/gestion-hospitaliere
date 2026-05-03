@extends('layouts.app')

@section('title', 'Gestion des Patients')

@section('content')
<div class="header" style="margin-bottom: 2rem;">
    <div>
        <h1 style="font-size: 2rem;">Patients</h1>
        <p style="color: var(--secondary);">Liste globale des patients enregistrés dans l'établissement.</p>
    </div>
    <a href="{{ route('patients.create') }}" class="badge badge-success" style="padding: 0.75rem 1.5rem; text-decoration: none; font-size: 1rem;">
        + Nouveau Patient
    </a>
</div>

<div class="card" style="margin-bottom: 2rem;">
    <form action="{{ route('patients.index') }}" method="GET" style="display: flex; gap: 1rem;">
        <input type="text" name="search" placeholder="Rechercher par nom, CIN ou N° dossier..." 
               value="{{ request('search') }}"
               style="flex-grow: 1; padding: 0.75rem 1rem; border-radius: 0.5rem; border: 1px solid #e2e8f0; font-family: inherit;">
        <button type="submit" class="badge" style="background: var(--primary); color: white; border: none; padding: 0 1.5rem; cursor: pointer;">
            Rechercher
        </button>
    </form>
</div>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>N° Dossier</th>
                    <th>Nom & Prénom</th>
                    <th>CIN</th>
                    <th>Téléphone</th>
                    <th>Ville</th>
                    <th>Statut</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($patients as $patient)
                <tr>
                    <td><code style="background: #f1f5f9; padding: 0.2rem 0.5rem; border-radius: 4px;">{{ $patient->numero_dossier }}</code></td>
                    <td>
                        <strong>{{ $patient->nom }} {{ $patient->prenom }}</strong><br>
                        <small style="color: var(--secondary);">{{ $patient->date_naissance }}</small>
                    </td>
                    <td>{{ $patient->cin ?? '---' }}</td>
                    <td>{{ $patient->telephone }}</td>
                    <td>{{ $patient->ville ?? '---' }}</td>
                    <td>
                        <span class="badge {{ $patient->statut === 'actif' ? 'badge-success' : 'badge-warning' }}">
                            {{ ucfirst($patient->statut) }}
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <div style="display: flex; gap: 5px; justify-content: flex-end;">
                            <a href="{{ route('patients.show', $patient) }}" class="badge" style="background: var(--primary-light); color: var(--primary); text-decoration:none;">Dossier</a>
                            <a href="{{ route('patients.edit', $patient) }}" class="badge" style="background: #f1f5f9; color: var(--text-main); text-decoration:none;">Modifier</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 3rem; color: var(--secondary);">
                        Aucun patient trouvé.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top: 1.5rem;">
        {{ $patients->appends(request()->input())->links() }}
    </div>
</div>
@endsection
