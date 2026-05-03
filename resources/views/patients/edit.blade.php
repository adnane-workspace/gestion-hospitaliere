@extends('layouts.app')

@section('title', 'Modifier Patient - ' . $patient->nom)

@section('content')
<div class="header" style="margin-bottom: 2rem;">
    <div>
        <h1 style="font-size: 2rem;">Modifier le Dossier Patient</h1>
        <p style="color: var(--secondary);">Patient : {{ $patient->nom }} {{ $patient->prenom }} ({{ $patient->numero_dossier }})</p>
    </div>
    <a href="{{ route('patients.show', $patient) }}" class="badge" style="background: var(--secondary); color: white; text-decoration:none; padding: 0.75rem 1.5rem;">Annuler</a>
</div>

<div class="card" style="max-width: 800px;">
    <form action="{{ route('patients.update', $patient) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Nom</label>
                <input type="text" name="nom" value="{{ old('nom', $patient->nom) }}" 
                       style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem;">
                @error('nom') <small style="color: var(--danger);">{{ $message }}</small> @enderror
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Prénom</label>
                <input type="text" name="prenom" value="{{ old('prenom', $patient->prenom) }}" 
                       style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem;">
                @error('prenom') <small style="color: var(--danger);">{{ $message }}</small> @enderror
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Téléphone</label>
                <input type="text" name="telephone" value="{{ old('telephone', $patient->telephone) }}" 
                       style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem;">
                @error('telephone') <small style="color: var(--danger);">{{ $message }}</small> @enderror
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Statut</label>
                <select name="statut" style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem;">
                    <option value="actif" {{ $patient->statut == 'actif' ? 'selected' : '' }}>Actif</option>
                    <option value="inactif" {{ $patient->statut == 'inactif' ? 'selected' : '' }}>Inactif</option>
                    <option value="decede" {{ $patient->statut == 'decede' ? 'selected' : '' }}>Décédé</option>
                    <option value="transfere" {{ $patient->statut == 'transfere' ? 'selected' : '' }}>Transféré</option>
                </select>
            </div>
        </div>

        <div style="margin-top: 2rem; display: flex; justify-content: flex-end;">
            <button type="submit" class="badge badge-success" style="border: none; padding: 1rem 2rem; font-size: 1rem; cursor: pointer;">
                Enregistrer les modifications
            </button>
        </div>
    </form>
</div>
@endsection
