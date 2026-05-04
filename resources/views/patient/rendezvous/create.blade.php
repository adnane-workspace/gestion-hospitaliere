@extends('layouts.app')

@section('title', 'Prendre un Rendez-vous')

@section('content')
<div class="mb-12">
    <a href="{{ route('patient.dashboard') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-indigo-600 transition-colors mb-4 group">
        <i data-lucide="arrow-left" class="w-4 h-4 group-hover:-translate-x-1 transition-transform"></i> Retour au Tableau de bord
    </a>
    <h1 class="text-4xl font-bold text-slate-800 tracking-tight mb-2">Réserver une consultation</h1>
    <p class="text-slate-500 font-medium">Choisissez votre spécialité, votre médecin et votre créneau horaire.</p>
</div>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-4xl border border-slate-200/60 shadow-2xl shadow-indigo-100/50 overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-5">
            <!-- Info Panel -->
            <div class="md:col-span-2 bg-indigo-600 p-10 text-white relative">
                <div class="absolute -top-12 -left-12 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                <div class="relative z-10">
                    <h3 class="text-2xl font-bold mb-6">Informations Utiles</h3>
                    <ul class="space-y-6">
                        <li class="flex gap-4">
                            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="clock" class="w-5 h-5 text-indigo-200"></i>
                            </div>
                            <p class="text-sm text-indigo-50 leading-relaxed">Prévoyez d'arriver 10 minutes avant l'heure de votre consultation.</p>
                        </li>
                        <li class="flex gap-4">
                            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="file-check" class="w-5 h-5 text-indigo-200"></i>
                            </div>
                            <p class="text-sm text-indigo-50 leading-relaxed">Munissez-vous de votre carte d'identité et de vos derniers résultats d'analyse.</p>
                        </li>
                    </ul>
                </div>
                <div class="absolute bottom-10 left-10 right-10 p-6 bg-white/5 backdrop-blur-md rounded-2xl border border-white/10">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-indigo-200">Disponibilité</span>
                    </div>
                    <p class="text-sm font-bold">Plus de 20 médecins disponibles aujourd'hui.</p>
                </div>
            </div>

            <!-- Form Panel -->
            <div class="md:col-span-3 p-10">
                <form action="{{ route('patient.rendezvous.store') }}" method="POST" class="space-y-8" x-data="{ service: '' }">
                    @csrf

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-3 uppercase tracking-wider text-[10px]">1. Spécialité médicale</label>
                        <div class="relative">
                            <select id="service_select" x-model="service" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-slate-600 font-medium focus:ring-2 focus:ring-indigo-500 transition-all appearance-none cursor-pointer">
                                <option value="">Toutes les spécialités</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}">{{ $service->nom }}</option>
                                @endforeach
                            </select>
                            <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                <i data-lucide="chevron-down" class="w-5 h-5"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-3 uppercase tracking-wider text-[10px]">2. Choisir un praticien</label>
                        <div class="relative">
                            <select name="medecin_id" id="medecin_select" required class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-slate-600 font-medium focus:ring-2 focus:ring-indigo-500 transition-all appearance-none cursor-pointer">
                                <option value="">Sélectionnez un médecin...</option>
                                @foreach($medecins as $medecin)
                                    <option value="{{ $medecin->id }}" data-service="{{ $medecin->service_id }}" x-show="!service || service == '{{ $medecin->service_id }}'">
                                        Dr. {{ $medecin->user->name }} ({{ $medecin->service->nom ?? 'Généraliste' }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                <i data-lucide="user" class="w-5 h-5"></i>
                            </div>
                        </div>
                        @error('medecin_id') <p class="text-rose-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-8">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-3 uppercase tracking-wider text-[10px]">3. Date et Heure</label>
                            <div class="relative">
                                <input type="datetime-local" name="date_heure_debut" required min="{{ date('Y-m-d\TH:i') }}" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-slate-600 font-medium focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>
                            @error('date_heure_debut') <p class="text-rose-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-3 uppercase tracking-wider text-[10px]">Type de rendez-vous</label>
                            <select name="type_rendez_vous" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-slate-600 font-medium focus:ring-2 focus:ring-indigo-500 transition-all">
                                <option value="premiere_consultation">Premiere consultation</option>
                                <option value="suivi">Suivi</option>
                                <option value="urgence">Urgence</option>
                                <option value="controle">Controle</option>
                                <option value="bilan">Bilan</option>
                                <option value="acte_medical">Acte medical</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-3 uppercase tracking-wider text-[10px]">Duree (minutes)</label>
                            <input type="number" min="10" max="120" step="5" name="duree_minutes" value="30" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-slate-600 font-medium focus:ring-2 focus:ring-indigo-500 transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-3 uppercase tracking-wider text-[10px]">4. Motif de consultation</label>
                        <textarea name="motif" rows="3" placeholder="Ex: Contrôle annuel, douleurs..." class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-slate-600 font-medium focus:ring-2 focus:ring-indigo-500 transition-all resize-none"></textarea>
                    </div>

                    <button type="submit" class="w-full py-5 bg-indigo-600 text-white font-bold rounded-2xl shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition-all transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-3">
                        <i data-lucide="check-circle" class="w-6 h-6"></i> Confirmer le Rendez-vous
                    </button>
                </form>
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
