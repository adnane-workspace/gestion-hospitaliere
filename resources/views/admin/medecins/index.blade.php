@extends('layouts.app')

@section('title', 'Gestion des Médecins')

@section('content')
<div class="animate-fade-in">
    <!-- Header Section -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                Gestion des Médecins
            </h2>
            <p class="mt-1 text-slate-500 font-medium">
                Consultez et gérez l'ensemble des praticiens de l'établissement.
            </p>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.medecins.pending') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-slate-600 text-sm font-bold rounded-xl border border-slate-200 hover:bg-slate-50 transition-all shadow-sm">
                <i data-lucide="user-plus" class="w-4 h-4"></i>
                Voir les demandes
                @php
                    $pendingCount = \App\Models\User::where('role', 'medecin')->where('is_active', false)->count();
                @endphp
                @if($pendingCount > 0)
                    <span class="ml-1 w-5 h-5 bg-rose-500 text-white text-[10px] rounded-full flex items-center justify-center">
                        {{ $pendingCount }}
                    </span>
                @endif
            </a>
        </div>
    </div>

    <!-- Stats Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-5">
            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total</p>
                <p class="text-2xl font-black text-slate-800">{{ $medecins->count() }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-5">
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center">
                <i data-lucide="check-circle" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Actifs</p>
                <p class="text-2xl font-black text-slate-800">{{ $medecins->where('is_active', true)->count() }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-5">
            <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center">
                <i data-lucide="clock" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">En attente</p>
                <p class="text-2xl font-black text-slate-800">{{ $medecins->where('is_active', false)->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Main Table -->
    <div class="bg-white rounded-[2rem] shadow-premium border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Médecin</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Spécialité</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">État</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Inscrit le</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($medecins as $user)
                        <tr class="hover:bg-slate-50/30 transition-colors">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-11 h-11 {{ $user->is_active ? 'bg-indigo-100 text-indigo-600' : 'bg-slate-100 text-slate-400' }} rounded-xl flex items-center justify-center font-bold text-lg">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800">{{ $user->name }}</div>
                                        <div class="text-xs text-slate-400 font-medium">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-slate-600 font-medium italic">
                                    {{ $user->medecin->specialite ?? 'Non défini' }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                @if($user->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase tracking-wider rounded-full border border-emerald-100">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                        Actif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-50 text-rose-600 text-[10px] font-bold uppercase tracking-wider rounded-full border border-rose-100">
                                        <span class="w-1.5 h-1.5 bg-rose-500 rounded-full animate-pulse"></span>
                                        En attente
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-slate-600 font-medium text-sm">
                                    {{ $user->created_at->format('d/m/Y') }}
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                @if(!$user->is_active)
                                    <form action="{{ route('admin.medecins.activate', $user) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="p-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition-all shadow-sm" title="Approuver">
                                            <i data-lucide="user-check" class="w-5 h-5"></i>
                                        </button>
                                    </form>
                                @else
                                    <button class="p-2 bg-slate-50 text-slate-400 rounded-lg cursor-not-allowed" title="Déjà actif">
                                        <i data-lucide="check" class="w-5 h-5"></i>
                                    </button>
                                @endif
                                <button class="p-2 bg-slate-50 text-slate-600 rounded-lg hover:bg-slate-100 transition-all ml-1" title="Voir profil">
                                    <i data-lucide="eye" class="w-5 h-5"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
