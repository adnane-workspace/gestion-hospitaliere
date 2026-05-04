@extends('layouts.app')

@section('title', 'Comptabilité')

@section('content')
<div class="space-y-8 animate-fade-in">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-8 rounded-[2rem] shadow-premium border border-slate-100">
            <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Factures totales</p>
            <p class="text-3xl font-black text-slate-800 mt-4">{{ $stats['total_factures'] }}</p>
        </div>

        <div class="bg-white p-8 rounded-[2rem] shadow-premium border border-slate-100">
            <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Revenus totaux</p>
            <p class="text-3xl font-black text-slate-800 mt-4">{{ number_format($stats['revenus_totaux'], 2, ',', ' ') }} MAD</p>
        </div>

        <div class="bg-white p-8 rounded-[2rem] shadow-premium border border-slate-100">
            <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Factures payées</p>
            <p class="text-3xl font-black text-slate-800 mt-4">{{ $stats['factures_payees'] }}</p>
        </div>

        <div class="bg-white p-8 rounded-[2rem] shadow-premium border border-slate-100">
            <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Factures en retard</p>
            <p class="text-3xl font-black text-slate-800 mt-4">{{ $stats['factures_en_retard'] }}</p>
        </div>
    </div>

    <div class="bg-white p-8 rounded-[2rem] shadow-premium border border-slate-100">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Tableau de bord comptable</h2>
                <p class="text-slate-500 mt-2">Suivez les factures, surveillez les retards et conservez une vision claire des flux de trésorerie.</p>
            </div>
        </div>

        @if($factures->isEmpty())
            <div class="rounded-[1.75rem] border border-dashed border-slate-200 p-10 text-center text-slate-500">
                Aucune facture enregistrée pour le moment.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-left border-separate border-spacing-y-3">
                    <thead>
                        <tr class="text-slate-500 uppercase text-xs tracking-wider">
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">Facture</th>
                            <th class="px-4 py-3">Patient</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Montant</th>
                            <th class="px-4 py-3">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($factures as $facture)
                            <tr class="bg-slate-50 rounded-3xl">
                                <td class="px-4 py-4 font-semibold text-slate-700">{{ $loop->iteration }}</td>
                                <td class="px-4 py-4 text-slate-700">{{ $facture->numero_facture }}</td>
                                <td class="px-4 py-4 text-slate-700">{{ optional($facture->patient->user)->name ?? 'N/A' }}</td>
                                <td class="px-4 py-4 text-slate-500">{{ $facture->date_emission?->translatedFormat('d M Y') ?? '—' }}</td>
                                <td class="px-4 py-4 text-slate-700">{{ number_format($facture->montant_total_ttc, 2, ',', ' ') }} MAD</td>
                                <td class="px-4 py-4">
                                    @php
                                        $statusClass = match ($facture->statut) {
                                            'payee' => 'bg-emerald-100 text-emerald-700',
                                            'en_retard' => 'bg-rose-100 text-rose-700',
                                            'partiellement_payee' => 'bg-amber-100 text-amber-700',
                                            'emise' => 'bg-sky-100 text-sky-700',
                                            'annulee' => 'bg-slate-100 text-slate-700',
                                            default => 'bg-indigo-100 text-indigo-700',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold {{ $statusClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $facture->statut)) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
