@extends('layouts.app')

@section('title', 'Mes Consultations')

@section('content')
<div class="mb-12">
    <h1 class="text-4xl font-bold text-slate-800 tracking-tight mb-2">Historique des Consultations</h1>
    <p class="text-slate-500 font-medium">Consultez l'ensemble de vos actes médicaux passés.</p>
</div>

<div class="bg-white rounded-4xl border border-slate-200/60 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100">
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Date & Heure</th>
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Patient</th>
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Service</th>
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Diagnostic Principal</th>
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Statut</th>
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($consultations as $consultation)
                <tr class="hover:bg-indigo-50/30 transition-all group">
                    <td class="px-8 py-6">
                        <div class="text-sm font-bold text-slate-700">{{ $consultation->created_at->format('d/m/Y') }}</div>
                        <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ $consultation->created_at->format('H:i') }}</div>
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                {{ substr($consultation->patient->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800">{{ $consultation->patient->user->name }}</p>
                                <p class="text-[11px] font-medium text-slate-400">{{ $consultation->patient->telephone }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <span class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded-lg text-[10px] font-bold uppercase tracking-wider">
                            {{ $consultation->service->nom ?? 'Général' }}
                        </span>
                    </td>
                    <td class="px-8 py-6 text-sm text-slate-500 font-medium italic">
                        "{{ Str::limit($consultation->diagnostic_principal, 60) }}"
                    </td>
                    <td class="px-8 py-6">
                        @php
                            $statusClass = match($consultation->statut) {
                                'terminee' => 'bg-emerald-50 text-emerald-600',
                                'annulee' => 'bg-rose-50 text-rose-600',
                                default => 'bg-amber-50 text-amber-600',
                            };
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $statusClass }}">
                            {{ ucfirst(str_replace('_', ' ', $consultation->statut)) }}
                        </span>
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex justify-end gap-2">
                            <button class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                            <button class="p-2.5 bg-slate-50 text-slate-400 rounded-xl hover:bg-slate-800 hover:text-white transition-all shadow-sm">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-8 py-32 text-center text-slate-400">
                        <i data-lucide="search-x" class="w-16 h-16 mx-auto mb-4 opacity-20"></i>
                        <p class="text-xl font-bold">Aucune consultation enregistrée</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($consultations->hasPages())
    <div class="px-8 py-6 border-t border-slate-100 bg-slate-50/30">
        {{ $consultations->links() }}
    </div>
    @endif
</div>
@endsection
