@extends('layouts.app')

@section('title', 'Dossier Patient - ' . $patient->nom)

@section('content')
<div class="mb-12">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-3 text-slate-400 font-bold text-xs uppercase tracking-widest mb-2">
                <i data-lucide="user" class="w-4 h-4"></i> Dossier Patient #{{ $patient->numero_dossier }}
            </div>
            <h1 class="text-4xl font-bold text-slate-800 tracking-tight">{{ $patient->nom }} {{ $patient->prenom }}</h1>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('patients.edit', $patient) }}" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 font-bold rounded-2xl hover:bg-slate-50 transition-all flex items-center gap-2">
                <i data-lucide="edit-3" class="w-4 h-4"></i> Modifier
            </a>
            <a href="{{ route('patients.index') }}" class="px-6 py-3 bg-slate-800 text-white font-bold rounded-2xl hover:bg-slate-900 transition-all flex items-center gap-2">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Retour
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Sidebar Patient Info -->
    <div class="space-y-8">
        <div class="bg-white rounded-4xl border border-slate-200/60 p-8 shadow-sm">
            <div class="flex flex-col items-center text-center mb-8">
                <div class="w-24 h-24 rounded-3xl bg-indigo-600 flex items-center justify-center text-white text-3xl font-bold shadow-2xl shadow-indigo-200 mb-4">
                    {{ substr($patient->nom, 0, 1) }}
                </div>
                <h2 class="text-xl font-bold text-slate-800">{{ $patient->nom }} {{ $patient->prenom }}</h2>
                <p class="text-slate-400 font-medium">{{ ucfirst($patient->genre) }} • {{ \Carbon\Carbon::parse($patient->date_naissance)->age }} ans</p>
            </div>

            <div class="space-y-6">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Contact</label>
                    <div class="space-y-2">
                        <div class="flex items-center gap-3 text-slate-600 font-medium">
                            <i data-lucide="phone" class="w-4 h-4 text-slate-300"></i> {{ $patient->telephone }}
                        </div>
                        <div class="flex items-center gap-3 text-slate-600 font-medium">
                            <i data-lucide="mail" class="w-4 h-4 text-slate-300"></i> {{ $patient->email ?? 'N/A' }}
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Localisation</label>
                    <div class="flex items-start gap-3 text-slate-600 font-medium">
                        <i data-lucide="map-pin" class="w-4 h-4 text-slate-300 mt-1"></i>
                        <span>{{ $patient->adresse ?? 'N/A' }}<br>{{ $patient->ville }} {{ $patient->code_postal }}</span>
                    </div>
                </div>
                <div class="pt-6 border-t border-slate-100">
                    <label class="block text-[10px] font-bold text-indigo-400 uppercase tracking-widest mb-2">Médecin Traitant</label>
                    <div class="bg-indigo-50 p-4 rounded-2xl border border-indigo-100">
                        <p class="text-indigo-700 font-bold">Dr. {{ $patient->medecinTraitant->user->name ?? 'Non assigné' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-rose-600 rounded-4xl p-8 text-white shadow-2xl shadow-rose-200">
            <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                <i data-lucide="alert-triangle"></i> Allergies & Alertes
            </h3>
            <p class="text-rose-100 font-medium leading-relaxed italic">
                "{{ $patient->allergies ?? 'Aucune allergie connue' }}"
            </p>
        </div>
    </div>

    <!-- Main Medical Data -->
    <div class="lg:col-span-2 space-y-8">
        <!-- Vitals -->
        <div class="bg-white rounded-4xl border border-slate-200/60 p-8 shadow-sm">
            <h3 class="text-xl font-bold text-slate-800 mb-8 flex items-center gap-2">
                <i data-lucide="activity" class="text-indigo-600"></i> Données Vitales
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100 text-center">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Groupe Sanguin</p>
                    <p class="text-3xl font-black text-rose-500">{{ $patient->groupe_sanguin ?? '??' }}</p>
                </div>
                <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100 text-center">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Poids</p>
                    <p class="text-3xl font-black text-slate-800">{{ $patient->poids ?? '--' }} <span class="text-sm">kg</span></p>
                </div>
                <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100 text-center">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Taille</p>
                    <p class="text-3xl font-black text-slate-800">{{ $patient->taille ?? '--' }} <span class="text-sm">cm</span></p>
                </div>
            </div>
        </div>

        <!-- Consultations History -->
        <div class="bg-white rounded-4xl border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="p-8 border-b border-slate-100 flex justify-between items-center">
                <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                    <i data-lucide="file-text" class="text-indigo-600"></i> Historique des Consultations
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Date</th>
                            <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Médecin</th>
                            <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Diagnostic</th>
                            <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($patient->consultations as $consultation)
                            <tr class="hover:bg-indigo-50/30 transition-colors group">
                                <td class="px-8 py-5 font-bold text-slate-700">{{ $consultation->created_at->format('d/m/Y') }}</td>
                                <td class="px-8 py-5 font-semibold text-slate-600">Dr. {{ $consultation->medecin->user->name }}</td>
                                <td class="px-8 py-5 text-slate-500 font-medium">{{ Str::limit($consultation->diagnostic_principal, 50) }}</td>
                                <td class="px-8 py-5 text-right">
                                    <button class="p-2 bg-slate-100 text-slate-400 rounded-xl group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-20 text-center text-slate-400 font-medium italic">
                                    Aucune consultation enregistrée.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>
@endsection
