@extends('layouts.app')

@section('title', 'Consultation')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-slate-800">Consultation {{ $consultation->reference }}</h1>
        <p class="text-slate-500">Detail de la consultation en cours.</p>
    </div>
    <a href="{{ route('patients.show', $consultation->patient) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700">
        Voir dossier patient
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl p-6">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Informations medicales</h3>
        <div class="space-y-4 text-sm">
            <div>
                <p class="text-slate-400 uppercase text-xs font-semibold">Motif</p>
                <p class="text-slate-700">{{ $consultation->motif_consultation ?? 'Non renseigne' }}</p>
            </div>
            <div>
                <p class="text-slate-400 uppercase text-xs font-semibold">Diagnostic principal</p>
                <p class="text-slate-700">{{ $consultation->diagnostic_principal ?? 'A completer' }}</p>
            </div>
            <div>
                <p class="text-slate-400 uppercase text-xs font-semibold">Statut</p>
                <p class="text-slate-700">{{ ucfirst(str_replace('_', ' ', $consultation->statut)) }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-6">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Patient</h3>
        <p class="font-semibold text-slate-800">{{ $consultation->patient->user->name ?? 'N/A' }}</p>
        <p class="text-sm text-slate-500 mt-1">{{ $consultation->patient->telephone ?? 'N/A' }}</p>
        <p class="text-xs text-slate-400 mt-4">Date: {{ optional($consultation->date_heure)->format('d/m/Y H:i') }}</p>
    </div>
</div>
@endsection
