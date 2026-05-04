@extends('layouts.app')

@section('title', 'Tableau de bord Admin')

@section('content')
<div class="space-y-8 animate-fade-in">
    <!-- Header with Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-8 rounded-[2rem] shadow-premium border border-slate-100 flex items-center gap-6 group hover:border-indigo-100 transition-all">
            <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <i data-lucide="users" class="w-8 h-8"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Total Patients</p>
                <p class="text-3xl font-black text-slate-800">{{ $stats['total_patients'] }}</p>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2rem] shadow-premium border border-slate-100 flex items-center gap-6 group hover:border-indigo-100 transition-all">
            <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <i data-lucide="stethoscope" class="w-8 h-8"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Médecins Actifs</p>
                <p class="text-3xl font-black text-slate-800">{{ $stats['medecins_actifs'] }}</p>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2rem] shadow-premium border border-slate-100 flex items-center gap-6 group hover:border-indigo-100 transition-all">
            <div class="w-16 h-16 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <i data-lucide="user-plus" class="w-8 h-8"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">En attente</p>
                <p class="text-3xl font-black text-slate-800">{{ $stats['medecins_en_attente'] }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-8 rounded-[2rem] shadow-premium border border-slate-100">
            <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Rendez-vous ce mois</p>
            <p class="text-3xl font-black text-slate-800 mt-2">{{ $stats['rdv_mois'] }}</p>
        </div>
        <div class="bg-white p-8 rounded-[2rem] shadow-premium border border-slate-100">
            <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Revenus ce mois</p>
            <p class="text-3xl font-black text-slate-800 mt-2">{{ number_format($stats['revenus_mois'], 2, ',', ' ') }} MAD</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white p-10 rounded-[2.5rem] shadow-premium border border-slate-100">
        <div class="flex flex-col md:flex-row justify-between items-center gap-8">
            <div class="text-center md:text-left">
                <h3 class="text-3xl font-black text-slate-800 tracking-tight mb-2">Actions d'administration</h3>
                <p class="text-slate-500 font-medium text-lg">Gérez les validations et les accès des nouveaux praticiens.</p>
            </div>
            
            <a href="{{ route('admin.medecins.pending') }}" class="w-full md:w-auto inline-flex items-center justify-center gap-4 px-8 py-5 bg-indigo-600 text-white font-bold text-lg rounded-2xl shadow-xl shadow-indigo-100 hover:bg-indigo-700 hover:-translate-y-1 transition-all active:scale-[0.98] group">
                <i data-lucide="user-check" class="w-6 h-6"></i>
                <span>Approuver les Médecins</span>
                <i data-lucide="chevron-right" class="w-5 h-5 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>

    <!-- Bottom Info -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-slate-800 p-8 rounded-[2rem] text-white overflow-hidden relative">
            <i data-lucide="shield" class="absolute -right-8 -bottom-8 w-48 h-48 text-white/5 -rotate-12"></i>
            <h4 class="text-xl font-bold mb-4 flex items-center gap-2">
                <i data-lucide="info" class="w-5 h-5 text-indigo-400"></i>
                Note de sécurité
            </h4>
            <p class="text-slate-400 leading-relaxed">
                Toutes les inscriptions de médecins nécessitent une vérification manuelle de leurs pièces justificatives avant l'activation du compte pour garantir la sécurité des données patients.
            </p>
        </div>
        
        <div class="bg-indigo-50 p-8 rounded-[2rem] border border-indigo-100">
            <h4 class="text-xl font-bold text-indigo-900 mb-4 flex items-center gap-2">
                <i data-lucide="activity" class="w-5 h-5"></i>
                État du système
            </h4>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-indigo-600 font-semibold">Serveur</span>
                    <span class="px-3 py-1 bg-emerald-500 text-white text-[10px] font-black rounded-full uppercase tracking-tighter">Opérationnel</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-indigo-600 font-semibold">Base de données</span>
                    <span class="px-3 py-1 bg-emerald-500 text-white text-[10px] font-black rounded-full uppercase tracking-tighter">Synchronisée</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
