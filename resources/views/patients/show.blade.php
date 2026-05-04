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
            @if($patient->allergies && count($patient->allergies) > 0)
                <div class="space-y-2">
                    @foreach($patient->allergies as $allergie)
                        <div class="flex items-center gap-2 bg-rose-500/20 px-3 py-2 rounded-lg">
                            <i data-lucide="alert-circle" class="w-4 h-4"></i>
                            <span class="font-medium">{{ $allergie }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-rose-100 font-medium leading-relaxed italic">
                    "Aucune allergie connue"
                </p>
            @endif
        </div>

        <!-- Contact d'urgence -->
        <div class="bg-blue-600 rounded-4xl p-8 text-white shadow-2xl shadow-blue-200">
            <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                <i data-lucide="phone"></i> Contact d'Urgence
            </h3>
            <div class="space-y-2">
                <p class="font-bold text-lg">{{ $patient->contact_urgence_nom ?? 'Non spécifié' }}</p>
                <p class="text-blue-100">{{ $patient->telephone_urgence ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <!-- Main Medical Data -->
    <div class="lg:col-span-2 space-y-8">
        <!-- Dossier Médical Complet -->
        <div class="bg-white rounded-4xl border border-slate-200/60 p-8 shadow-sm">
            <h3 class="text-xl font-bold text-slate-800 mb-8 flex items-center gap-2">
                <i data-lucide="file-text" class="text-indigo-600"></i> Dossier Médical
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Informations Médicales de Base -->
                <div class="space-y-6">
                    <h4 class="text-lg font-bold text-slate-700 border-b border-slate-200 pb-2">Informations Médicales</h4>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Groupe Sanguin</p>
                            <p class="text-xl font-black text-rose-500">{{ $patient->groupe_sanguin ?? 'Non spécifié' }}</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Tension Artérielle</p>
                            <p class="text-xl font-black text-blue-600">{{ $patient->tension_arterielle ?? 'Non mesurée' }}</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Fréquence Cardiaque</p>
                            <p class="text-xl font-black text-green-600">{{ $patient->frequence_cardiaque ?? '--' }} <span class="text-sm">bpm</span></p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">IMC</p>
                            <p class="text-xl font-black text-purple-600">{{ $patient->imc ?? '--' }}</p>
                            @if($patient->imc_category)
                                <p class="text-xs text-slate-500 mt-1">{{ $patient->imc_category }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Traitements et Maladies -->
                <div class="space-y-6">
                    <h4 class="text-lg font-bold text-slate-700 border-b border-slate-200 pb-2">Traitements & Pathologies</h4>

                    @if($patient->maladies_chroniques && count($patient->maladies_chroniques) > 0)
                        <div>
                            <p class="text-sm font-bold text-amber-600 uppercase tracking-widest mb-3">Maladies Chroniques</p>
                            <div class="space-y-2">
                                @foreach($patient->maladies_chroniques as $maladie)
                                    <div class="flex items-center gap-2 bg-amber-50 px-3 py-2 rounded-lg border border-amber-200">
                                        <i data-lucide="activity" class="w-4 h-4 text-amber-500"></i>
                                        <span class="text-amber-800 font-medium">{{ $maladie }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($patient->medicaments_actuels && count($patient->medicaments_actuels) > 0)
                        <div>
                            <p class="text-sm font-bold text-green-600 uppercase tracking-widest mb-3">Traitements Actuels</p>
                            <div class="space-y-2">
                                @foreach($patient->medicaments_actuels as $medicament)
                                    <div class="flex items-center gap-2 bg-green-50 px-3 py-2 rounded-lg border border-green-200">
                                        <i data-lucide="pill" class="w-4 h-4 text-green-500"></i>
                                        <span class="text-green-800 font-medium">{{ $medicament }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Antécédents -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                @if($patient->antecedents_medicaux && count($patient->antecedents_medicaux) > 0)
                    <div class="bg-blue-50 p-6 rounded-3xl border border-blue-200">
                        <h5 class="text-sm font-bold text-blue-600 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <i data-lucide="history" class="w-4 h-4"></i> Antécédents Médicaux
                        </h5>
                        <ul class="space-y-2">
                            @foreach($patient->antecedents_medicaux as $antecedent)
                                <li class="text-blue-800 text-sm">• {{ $antecedent }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($patient->antecedents_chirurgicaux && count($patient->antecedents_chirurgicaux) > 0)
                    <div class="bg-purple-50 p-6 rounded-3xl border border-purple-200">
                        <h5 class="text-sm font-bold text-purple-600 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <i data-lucide="scissors" class="w-4 h-4"></i> Antécédents Chirurgicaux
                        </h5>
                        <ul class="space-y-2">
                            @foreach($patient->antecedents_chirurgicaux as $antecedent)
                                <li class="text-purple-800 text-sm">• {{ $antecedent }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($patient->antecedents_familiaux && count($patient->antecedents_familiaux) > 0)
                    <div class="bg-indigo-50 p-6 rounded-3xl border border-indigo-200">
                        <h5 class="text-sm font-bold text-indigo-600 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <i data-lucide="users" class="w-4 h-4"></i> Antécédents Familiaux
                        </h5>
                        <ul class="space-y-2">
                            @foreach($patient->antecedents_familiaux as $antecedent)
                                <li class="text-indigo-800 text-sm">• {{ $antecedent }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
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
