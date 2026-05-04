@extends('layouts.app')

@section('title', 'Nouveau Patient')

@section('content')
<div class="header" style="margin-bottom: 2rem;">
    <div>
        <h1 style="font-size: 2rem;">Enregistrer un Nouveau Patient</h1>
        <p style="color: var(--secondary);">Création d'un nouveau dossier médical.</p>
    </div>
    <a href="{{ route('patients.index') }}" class="badge" style="background: var(--secondary); color: white; text-decoration:none; padding: 0.75rem 1.5rem;">Annuler</a>
</div>

<div class="card" style="max-width: 800px;">
    <form action="{{ route('patients.store') }}" method="POST">
        @csrf

        @if($errors->any())
            <div style="margin-bottom: 1.5rem; padding: 1rem; border: 1px solid #fca5a5; background: #fef2f2; border-radius: 0.75rem; color: #b91c1c;">
                <strong>Erreur de validation :</strong>
                <ul style="margin-top: 0.75rem; padding-left: 1.25rem; list-style: disc;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Nom</label>
                <input type="text" name="nom" value="{{ old('nom') }}" required 
                       style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem;">
                @error('nom') <small style="color: var(--danger);">{{ $message }}</small> @enderror
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Prénom</label>
                <input type="text" name="prenom" value="{{ old('prenom') }}" required 
                       style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem;">
                @error('prenom') <small style="color: var(--danger);">{{ $message }}</small> @enderror
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">N° Dossier</label>
                <input type="text" name="numero_dossier" value="{{ old('numero_dossier', 'DOS-'.date('Y').'-') }}" required 
                       style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem;">
                @error('numero_dossier') <small style="color: var(--danger);">{{ $message }}</small> @enderror
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">CIN</label>
                <input type="text" name="cin" value="{{ old('cin') }}" 
                       style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem;">
                @error('cin') <small style="color: var(--danger);">{{ $message }}</small> @enderror
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Genre</label>
                <select name="genre" required style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem;">
                    <option value="homme" @selected(old('genre') === 'homme')>Homme</option>
                    <option value="femme" @selected(old('genre') === 'femme')>Femme</option>
                    <option value="autre" @selected(old('genre') === 'autre')>Autre</option>
                </select>
                @error('genre') <small style="color: var(--danger);">{{ $message }}</small> @enderror
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Date de Naissance</label>
                <input type="date" name="date_naissance" value="{{ old('date_naissance') }}" required max="{{ date('Y-m-d') }}"
                       style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem;">
                @error('date_naissance') <small style="color: var(--danger);">{{ $message }}</small> @enderror
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Téléphone</label>
                <input type="text" name="telephone" value="{{ old('telephone') }}" required 
                       style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem;">
                @error('telephone') <small style="color: var(--danger);">{{ $message }}</small> @enderror
            </div>
        </div>

        <div style="margin-top: 2rem; display: flex; justify-content: flex-end;">
            <button type="submit" class="badge badge-success" style="border: none; padding: 1rem 2rem; font-size: 1rem; cursor: pointer;">
                Créer le dossier patient
            </button>
        </div>
    </form>
</div>
@endsection
