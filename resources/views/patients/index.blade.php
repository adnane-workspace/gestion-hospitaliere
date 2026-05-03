@extends('layouts.app')

@section('title', 'Gestion des Patients')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-12">
    <div>
        <h1 class="text-4xl font-bold text-slate-800 tracking-tight mb-2">Annuaire des Patients</h1>
        <p class="text-slate-500 font-medium">Gérez et consultez les dossiers de l'ensemble de vos patients.</p>
    </div>
    <a href="{{ route('patients.create') }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-indigo-600 text-white font-bold rounded-2xl shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition-all transform hover:scale-105 active:scale-95">
        <i data-lucide="plus" class="w-5 h-5"></i> Nouveau Patient
    </a>
</div>

<!-- Search & Filter Bar -->
<div class="bg-white p-6 rounded-3xl border border-slate-200/60 shadow-sm mb-8">
    <form action="{{ route('patients.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
        <div class="relative flex-grow group">
            <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 group-focus-within:text-indigo-600 transition-colors"></i>
            <input type="text" name="search" placeholder="Rechercher par nom, CIN ou N° de dossier..." 
                   value="{{ request('search') }}"
                   class="w-full bg-slate-50 border-none rounded-2xl pl-12 pr-6 py-4 text-slate-600 font-medium focus:ring-2 focus:ring-indigo-500 transition-all">
        </div>
        <button type="submit" class="px-8 py-4 bg-slate-800 text-white font-bold rounded-2xl hover:bg-slate-900 transition-all flex items-center justify-center gap-2">
            <i data-lucide="sliders-horizontal" class="w-5 h-5"></i> Filtrer
        </button>
    </form>
</div>

<!-- Patients List -->
<div class="bg-white rounded-4xl border border-slate-200/60 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100">
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">N° Dossier</th>
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Patient</th>
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Identité</th>
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Contact</th>
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Statut</th>
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($patients as $patient)
                <tr class="hover:bg-indigo-50/30 transition-all group">
                    <td class="px-8 py-6">
                        <span class="inline-flex items-center justify-center px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold font-mono">
                            #{{ $patient->numero_dossier }}
                        </span>
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-slate-100 to-slate-50 flex items-center justify-center text-slate-400 font-bold group-hover:from-indigo-600 group-hover:to-indigo-500 group-hover:text-white transition-all shadow-sm">
                                {{ substr($patient->nom, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800">{{ $patient->nom }} {{ $patient->prenom }}</p>
                                <p class="text-[11px] font-medium text-slate-400">{{ $patient->date_naissance }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6 text-sm font-semibold text-slate-600">
                        {{ $patient->cin ?? 'Non renseigné' }}
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex flex-col gap-1">
                            <span class="text-sm font-semibold text-slate-600 flex items-center gap-1.5">
                                <i data-lucide="phone" class="w-3.5 h-3.5 text-slate-400"></i> {{ $patient->telephone }}
                            </span>
                            <span class="text-[11px] font-medium text-slate-400 flex items-center gap-1.5">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400"></i> {{ $patient->ville ?? 'Inconnue' }}
                            </span>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $patient->statut === 'actif' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $patient->statut === 'actif' ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                            {{ $patient->statut }}
                        </span>
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('patients.show', $patient) }}" class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition-all shadow-sm shadow-indigo-100" title="Voir Dossier">
                                <i data-lucide="folder-open" class="w-4 h-4"></i>
                            </a>
                            <a href="{{ route('patients.edit', $patient) }}" class="p-2.5 bg-slate-50 text-slate-400 rounded-xl hover:bg-slate-800 hover:text-white transition-all shadow-sm" title="Modifier">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-8 py-32 text-center">
                        <div class="flex flex-col items-center opacity-20">
                            <i data-lucide="users" class="w-20 h-20 mb-6"></i>
                            <h3 class="text-2xl font-bold">Aucun patient trouvé</h3>
                            <p class="mt-2 font-medium">Essayez de modifier vos critères de recherche.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($patients->hasPages())
    <div class="px-8 py-6 border-t border-slate-100 bg-slate-50/30">
        {{ $patients->appends(request()->input())->links() }}
    </div>
    @endif
</div>
@endsection
