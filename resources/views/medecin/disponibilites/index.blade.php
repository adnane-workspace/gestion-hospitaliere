@extends('layouts.app')

@section('title', 'Disponibilites')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-slate-800">Planning intelligent</h1>
    <p class="text-slate-500">Definissez vos disponibilites hebdomadaires pour fiabiliser la prise de rendez-vous.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white border border-slate-200 rounded-2xl p-6">
        <h3 class="text-lg font-semibold mb-4">Ajouter un creneau</h3>
        <form method="POST" action="{{ route('medecin.disponibilites.store') }}" class="space-y-3">
            @csrf
            <select name="jour_semaine" class="w-full rounded-xl border-slate-200" required>
                <option value="">Jour</option>
                <option value="1">Lundi</option><option value="2">Mardi</option><option value="3">Mercredi</option>
                <option value="4">Jeudi</option><option value="5">Vendredi</option><option value="6">Samedi</option>
                <option value="7">Dimanche</option>
            </select>
            <div class="grid grid-cols-2 gap-3">
                <input type="time" name="heure_debut" class="rounded-xl border-slate-200" required>
                <input type="time" name="heure_fin" class="rounded-xl border-slate-200" required>
            </div>
            <button class="px-4 py-2 bg-indigo-600 text-white rounded-xl">Ajouter</button>
        </form>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-6">
        <h3 class="text-lg font-semibold mb-4">Vos disponibilites</h3>
        <div class="space-y-2">
            @forelse($disponibilites as $d)
                <div class="p-3 rounded-xl bg-slate-50 text-sm text-slate-700">
                    Jour {{ $d->jour_semaine }} - {{ substr($d->heure_debut, 0, 5) }} a {{ substr($d->heure_fin, 0, 5) }}
                </div>
            @empty
                <p class="text-sm text-slate-400">Aucun creneau defini.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
