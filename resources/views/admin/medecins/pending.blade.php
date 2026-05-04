@extends('layouts.app')

@section('title', 'Médecins en attente')

@section('content')
<div class="animate-fade-in">
    <!-- Header Section -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                Médecins en attente
            </h2>
            <p class="mt-1 text-slate-500 font-medium">
                Gérez les nouvelles demandes d'inscription des professionnels de santé.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-700 text-sm font-bold rounded-xl border border-indigo-100">
                {{ $pendingUsers->count() }} demande(s)
            </span>
        </div>
    </div>

    <!-- Content Card -->
    <div class="bg-white rounded-[2rem] shadow-premium border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Médecin</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Email</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Date d'inscription</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($pendingUsers as $user)
                        <tr class="hover:bg-slate-50/30 transition-colors">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-11 h-11 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center font-bold text-lg">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800">{{ $user->name }}</div>
                                        <div class="text-xs text-slate-400 font-medium">Nouveau compte</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-2 text-slate-600 font-medium">
                                    <i data-lucide="mail" class="w-4 h-4 text-slate-300"></i>
                                    {{ $user->email }}
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-slate-600 font-medium">
                                    {{ $user->created_at->format('d M Y à H:i') }}
                                </div>
                                <div class="text-[10px] text-slate-400 font-bold uppercase">
                                    {{ $user->created_at->diffForHumans() }}
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <form action="{{ route('admin.medecins.activate', $user) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-200 hover:bg-indigo-700 hover:-translate-y-0.5 transition-all active:scale-[0.98]">
                                        <i data-lucide="user-check" class="w-4 h-4"></i>
                                        Approuver
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <div class="w-20 h-20 bg-slate-50 text-slate-200 rounded-full flex items-center justify-center">
                                        <i data-lucide="users" class="w-10 h-10"></i>
                                    </div>
                                    <div>
                                        <p class="text-slate-400 font-bold text-lg">Aucune demande en attente</p>
                                        <p class="text-slate-300 text-sm">Tous les comptes médecins sont à jour.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
