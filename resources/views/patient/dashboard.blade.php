@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div class="mb-12">
    <h1 class="text-4xl font-bold text-slate-800 tracking-tight mb-2">Bonjour, {{ Auth::user()->name }} 👋</h1>
    <p class="text-slate-500 font-medium">Voici un aperçu de votre santé et de vos prochains rendez-vous.</p>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
    <!-- Prochain RDV -->
    <div class="relative group">
        <div class="absolute inset-0 bg-indigo-600 rounded-3xl blur-xl opacity-20 group-hover:opacity-30 transition-opacity"></div>
        <div class="relative bg-white p-8 rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden h-full flex flex-col">
            <div class="flex justify-between items-start mb-6">
                <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600">
                    <i data-lucide="calendar" class="w-7 h-7"></i>
                </div>
                <span class="px-3 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-bold uppercase tracking-widest rounded-full">Prochain RDV</span>
            </div>
            
            @if($nextAppointment)
                <h3 class="text-2xl font-bold text-slate-800 mb-1">{{ $nextAppointment->date_heure_debut->format('d M Y') }}</h3>
                <p class="text-slate-500 font-medium mb-6">À {{ $nextAppointment->date_heure_debut->format('H:i') }} avec Dr. {{ $nextAppointment->medecin->user->name }}</p>
                <div class="mt-auto">
                    <a href="{{ route('patient.rendezvous.index') }}" class="text-indigo-600 font-bold text-sm flex items-center gap-2 group-hover:gap-3 transition-all">
                        Gérer mes RDV <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
            @else
                <h3 class="text-xl font-bold text-slate-400 mb-6 italic">Aucun rendez-vous prévu</h3>
                <div class="mt-auto">
                    <a href="{{ route('patient.rendezvous.create') }}" class="inline-flex items-center justify-center w-full py-3 bg-indigo-600 text-white font-bold rounded-2xl shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-colors gap-2">
                        <i data-lucide="plus" class="w-4 h-4"></i> Prendre RDV
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Groupe Sanguin -->
    <div class="bg-white p-8 rounded-3xl border border-slate-200/60 shadow-sm flex flex-col">
        <div class="flex justify-between items-start mb-6">
            <div class="w-14 h-14 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-500">
                <i data-lucide="droplet" class="w-7 h-7"></i>
            </div>
            <span class="px-3 py-1 bg-rose-50 text-rose-500 text-[10px] font-bold uppercase tracking-widest rounded-full">Vital</span>
        </div>
        <p class="text-slate-500 font-medium mb-1">Groupe Sanguin</p>
        <h3 class="text-4xl font-black text-slate-800">{{ $patient->groupe_sanguin ?? 'Non défini' }}</h3>
        <div class="mt-auto pt-6">
            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                <div class="w-full h-full bg-rose-500"></div>
            </div>
        </div>
    </div>

    <!-- Santé Score (Mockup) -->
    <div class="bg-white p-8 rounded-3xl border border-slate-200/60 shadow-sm flex flex-col">
        <div class="flex justify-between items-start mb-6">
            <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-500">
                <i data-lucide="activity" class="w-7 h-7"></i>
            </div>
            <span class="px-3 py-1 bg-emerald-50 text-emerald-500 text-[10px] font-bold uppercase tracking-widest rounded-full">État</span>
        </div>
        <p class="text-slate-500 font-medium mb-1">Score de Santé</p>
        <h3 class="text-4xl font-black text-slate-800">92<span class="text-xl text-slate-400 font-bold">/100</span></h3>
        <div class="mt-auto pt-6 text-emerald-500 text-xs font-bold flex items-center gap-1">
            <i data-lucide="trending-up" class="w-3 h-3"></i> +5% depuis le mois dernier
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Historique Médical -->
    <div class="lg:col-span-2 bg-white rounded-4xl border border-slate-200/60 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-slate-100 flex justify-between items-center">
            <h3 class="text-xl font-bold text-slate-800">Historique Médical</h3>
            <a href="#" class="text-indigo-600 font-bold text-sm hover:underline">Voir tout</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Date</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Médecin</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Diagnostic</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($history as $item)
                        <tr class="hover:bg-indigo-50/30 transition-colors group">
                            <td class="px-8 py-5 font-bold text-slate-700">{{ $item->created_at->format('d/m/Y') }}</td>
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs">
                                        {{ substr($item->medecin->user->name, 0, 1) }}
                                    </div>
                                    <span class="font-semibold text-slate-600">Dr. {{ $item->medecin->user->name }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-slate-500 font-medium">{{ Str::limit($item->diagnostic_principal, 30) }}</td>
                            <td class="px-8 py-5">
                                <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-100 text-slate-400 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center opacity-20">
                                    <i data-lucide="folder-open" class="w-16 h-16 mb-4"></i>
                                    <p class="font-bold">Aucun historique trouvé</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Documents & Sidebar Actions -->
    <div class="space-y-8">
        <div class="bg-indigo-600 rounded-4xl p-8 text-white relative overflow-hidden shadow-2xl shadow-indigo-200">
            <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <h3 class="text-xl font-bold mb-2">Besoin d'aide ?</h3>
            <p class="text-indigo-100 text-sm mb-8 leading-relaxed">Consultez notre FAQ ou contactez directement le secrétariat pour toute urgence.</p>
            <a href="#" class="inline-flex items-center justify-center px-6 py-3 bg-white text-indigo-600 font-bold rounded-2xl shadow-lg transition-transform hover:scale-105">
                Nous Contacter
            </a>
        </div>

        <div class="bg-white rounded-4xl border border-slate-200/60 p-8 shadow-sm">
            <h3 class="text-xl font-bold text-slate-800 mb-6">Documents Récents</h3>
            <div class="space-y-4">
                <div class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 group hover:bg-indigo-50 transition-colors cursor-pointer">
                    <div class="w-12 h-12 bg-rose-100 rounded-xl flex items-center justify-center text-rose-500 group-hover:bg-rose-500 group-hover:text-white transition-all">
                        <i data-lucide="file-text" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-700">Ordonnance_0405.pdf</p>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">PDF • 1.2 MB</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 group hover:bg-indigo-50 transition-colors cursor-pointer">
                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-500 group-hover:bg-indigo-500 group-hover:text-white transition-all">
                        <i data-lucide="image" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-700">Radio_Poumons.jpg</p>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">JPG • 4.5 MB</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
