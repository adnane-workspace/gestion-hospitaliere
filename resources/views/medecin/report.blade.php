@extends('layouts.app')

@section('title', 'Rapport Médecin')

@section('content')
<div class="mb-12 flex flex-col md:flex-row md:items-center justify-between gap-6">
    <div>
        <h1 class="text-4xl font-bold text-slate-800 tracking-tight mb-2">Rapport Médical</h1>
        <p class="text-slate-500 font-medium text-lg">Votre synthèse de consultation et vos rendez-vous.</p>
    </div>
    <a href="{{ route('medecin.dashboard') }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-indigo-600 text-white font-bold rounded-2xl shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition-all">
        Retour au tableau de bord
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
    <div class="bg-white rounded-4xl border border-slate-200/60 p-8 shadow-sm">
        <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-3">Rendez-vous aujourd'hui</p>
        <p class="text-5xl font-black text-slate-800">{{ $stats['rdv_today'] }}</p>
        <p class="text-slate-500 mt-3">Rendez-vous planifiés pour aujourd'hui.</p>
    </div>
    <div class="bg-white rounded-4xl border border-slate-200/60 p-8 shadow-sm">
        <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-3">Consultations ce mois</p>
        <p class="text-5xl font-black text-slate-800">{{ $stats['consultations_done'] }}</p>
        <p class="text-slate-500 mt-3">Consultations enregistrées ce mois.</p>
    </div>
    <div class="bg-white rounded-4xl border border-slate-200/60 p-8 shadow-sm">
        <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-3">Nouveaux patients</p>
        <p class="text-5xl font-black text-slate-800">{{ $stats['new_patients'] }}</p>
        <p class="text-slate-500 mt-3">Patients ajoutés ce mois.</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <div class="bg-white rounded-4xl border border-slate-200/60 p-8 shadow-sm">
        <h2 class="text-2xl font-bold text-slate-800 mb-6">Rendez-vous prévus aujourd'hui</h2>
        <div class="space-y-4">
            @forelse($appointments as $appointment)
                <div class="rounded-3xl border border-slate-100 p-5 bg-slate-50">
                    <div class="flex items-center justify-between gap-4 mb-3">
                        <div>
                            <p class="text-slate-500 text-xs uppercase tracking-widest">{{ $appointment->date_heure_debut->format('d/m/Y H:i') }}</p>
                            <p class="text-lg font-bold text-slate-800">{{ $appointment->patient->user->name }}</p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-xs font-bold uppercase tracking-widest">{{ ucfirst(str_replace('_', ' ', $appointment->statut)) }}</span>
                    </div>
                    <p class="text-slate-500">{{ $appointment->motif }}</p>
                </div>
            @empty
                <p class="text-slate-500">Aucun rendez-vous prévu aujourd'hui.</p>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-4xl border border-slate-200/60 p-8 shadow-sm">
        <h2 class="text-2xl font-bold text-slate-800 mb-6">Consultations récentes</h2>
        <div class="space-y-4">
            @forelse($recent_consultations as $consultation)
                <div class="rounded-3xl border border-slate-100 p-5 bg-slate-50">
                    <div class="flex items-center justify-between gap-4 mb-3">
                        <div>
                            <p class="text-slate-500 text-xs uppercase tracking-widest">{{ $consultation->created_at->format('d/m/Y H:i') }}</p>
                            <p class="text-lg font-bold text-slate-800">{{ $consultation->patient->user->name }}</p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-bold uppercase tracking-widest">{{ ucfirst(str_replace('_', ' ', $consultation->statut)) }}</span>
                    </div>
                    <p class="text-slate-500">{{ Str::limit($consultation->diagnostic_principal ?? 'Aucun diagnostic enregistré', 80) }}</p>
                </div>
            @empty
                <p class="text-slate-500">Aucune consultation récente.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection