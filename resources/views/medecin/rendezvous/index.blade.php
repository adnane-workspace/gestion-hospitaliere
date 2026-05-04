@extends('layouts.app')

@section('title', 'Gestion des Rendez-vous')

@section('content')
<div class="mb-12">
    <h1 class="text-4xl font-bold text-slate-800 tracking-tight mb-2">Gestion des Rendez-vous</h1>
    <p class="text-slate-500 font-medium">Consultez et gérez vos prochaines consultations patients.</p>
</div>

<div class="bg-white rounded-4xl border border-slate-200/60 shadow-sm overflow-hidden">
    <form method="GET" class="p-6 border-b border-slate-100 grid grid-cols-1 md:grid-cols-5 gap-3 bg-slate-50/40">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom patient" class="rounded-xl border-slate-200 text-sm">
        <select name="statut" class="rounded-xl border-slate-200 text-sm">
            <option value="">Tous statuts</option>
            @foreach(['planifie','confirme','en_attente','en_cours','termine','annule','reporte','patient_absent'] as $statut)
                <option value="{{ $statut }}" @selected(request('statut') === $statut)>{{ ucfirst(str_replace('_', ' ', $statut)) }}</option>
            @endforeach
        </select>
        <input type="date" name="date_debut" value="{{ request('date_debut') }}" class="rounded-xl border-slate-200 text-sm">
        <input type="date" name="date_fin" value="{{ request('date_fin') }}" class="rounded-xl border-slate-200 text-sm">
        <button class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-semibold">Filtrer</button>
    </form>
    <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/30">
        <h3 class="text-xl font-bold text-slate-800 flex items-center gap-3">
            <i data-lucide="calendar-check" class="text-indigo-600"></i> Liste des Rendez-vous
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100">
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Référence</th>
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Date & Heure</th>
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Patient</th>
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Motif</th>
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Statut</th>
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($appointments as $rdv)
                <tr class="hover:bg-indigo-50/30 transition-all group">
                    <td class="px-8 py-6">
                        <span class="inline-flex items-center px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold font-mono">
                            {{ $rdv->reference }}
                        </span>
                    </td>
                    <td class="px-8 py-6">
                        <div class="text-sm font-bold text-slate-800">{{ $rdv->date_heure_debut->format('d/m/Y') }}</div>
                        <div class="text-xs font-bold text-indigo-500 uppercase">{{ $rdv->date_heure_debut->format('H:i') }}</div>
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                {{ substr($rdv->patient->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800">{{ $rdv->patient->user->name }}</p>
                                <p class="text-[11px] font-medium text-slate-400">{{ $rdv->patient->telephone }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6 text-sm text-slate-500 font-medium italic">
                        "{{ Str::limit($rdv->motif, 40) }}"
                    </td>
                    <td class="px-8 py-6">
                        @php
                            $badgeClass = match($rdv->statut) {
                                'planifie' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                                'confirme' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                'annule' => 'bg-rose-50 text-rose-600 border-rose-100',
                                'en_attente' => 'bg-amber-50 text-amber-600 border-amber-100',
                                default => 'bg-slate-50 text-slate-600 border-slate-100',
                            };
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $badgeClass }}">
                            {{ ucfirst(str_replace('_', ' ', $rdv->statut)) }}
                        </span>
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('patients.show', $rdv->patient) }}" class="p-2.5 bg-white border border-slate-200 text-slate-400 rounded-xl hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all shadow-sm" title="Dossier Patient">
                                <i data-lucide="folder-heart" class="w-4 h-4"></i>
                            </a>
                            @if($rdv->statut === 'planifie')
                            <form action="{{ route('medecin.rendezvous.confirm', $rdv) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" class="p-2.5 bg-white border border-slate-200 text-slate-400 rounded-xl hover:bg-emerald-500 hover:text-white hover:border-emerald-500 transition-all shadow-sm" title="Confirmer">
                                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-8 py-32 text-center text-slate-400">
                        <i data-lucide="calendar-off" class="w-20 h-20 mx-auto mb-6 opacity-20"></i>
                        <h3 class="text-2xl font-bold">Aucun rendez-vous trouvé</h3>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($appointments->hasPages())
    <div class="px-8 py-6 border-t border-slate-100 bg-slate-50/30">
        {{ $appointments->links() }}
    </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>
@endsection
