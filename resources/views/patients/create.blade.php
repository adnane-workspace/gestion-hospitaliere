@extends('layouts.app')

@section('title', 'Nouveau Patient')

@section('content')
<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 shadow-sm border border-indigo-100">
                <i data-lucide="user-plus" class="w-5 h-5"></i>
            </div>
            Enregistrer un Nouveau Patient
        </h1>
        <p class="text-slate-500 mt-1">Création d'un nouveau dossier médical et administratif.</p>
    </div>
    <div>
        <a href="{{ route('patients.index') }}" class="flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 rounded-xl text-slate-500 hover:text-indigo-600 hover:border-indigo-100 hover:shadow-sm transition-all duration-300 font-semibold text-sm">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Annuler
        </a>
    </div>
</div>

<!-- Card Container -->
<div class="bg-white/80 backdrop-blur-xl border border-slate-200/50 rounded-3xl p-8 shadow-2xl shadow-indigo-100/30 max-w-4xl">
    <form action="{{ route('patients.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Error Alerts -->
        @if($errors->any())
            <div class="p-4 bg-rose-50 border border-rose-100 rounded-2xl flex gap-3 text-rose-700 shadow-sm">
                <i data-lucide="alert-circle" class="w-5 h-5 mt-0.5 flex-shrink-0"></i>
                <div>
                    <strong class="font-bold text-sm">Erreurs de validation :</strong>
                    <ul class="mt-2 text-xs list-disc list-inside space-y-1 font-medium">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Form Sections -->
        <div class="space-y-6">
            <!-- Section 1: Identité civile -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-widest text-indigo-600 mb-4 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-600"></span>
                    Identité Civile
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block mb-2 font-semibold text-slate-700 text-sm">Nom</label>
                        <input type="text" name="nom" value="{{ old('nom') }}" required placeholder="ex: Bennani"
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-200 placeholder-slate-400 font-medium text-slate-800 text-sm">
                        @error('nom') <small class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</small> @enderror
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold text-slate-700 text-sm">Prénom</label>
                        <input type="text" name="prenom" value="{{ old('prenom') }}" required placeholder="ex: Amine"
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-200 placeholder-slate-400 font-medium text-slate-800 text-sm">
                        @error('prenom') <small class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</small> @enderror
                    </div>
                </div>
            </div>

            <hr class="border-slate-100 my-2">

            <!-- Section 2: Identifiants Administratifs -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-widest text-indigo-600 mb-4 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-600"></span>
                    Identifiants & Dossier
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block mb-2 font-semibold text-slate-700 text-sm">N° Dossier</label>
                        <input type="text" name="numero_dossier" value="{{ old('numero_dossier', 'DOS-'.date('Y').'-') }}" required
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-200 font-bold text-slate-800 text-sm tracking-wider">
                        @error('numero_dossier') <small class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</small> @enderror
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold text-slate-700 text-sm">CIN</label>
                        <input type="text" name="cin" value="{{ old('cin') }}" placeholder="ex: AB123456"
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-200 placeholder-slate-400 font-semibold text-slate-800 text-sm uppercase">
                        @error('cin') <small class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</small> @enderror
                    </div>
                </div>
            </div>

            <hr class="border-slate-100 my-2">

            <!-- Section 3: Informations Supplémentaires -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-widest text-indigo-600 mb-4 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-600"></span>
                    Informations Cliniques & Contact
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block mb-2 font-semibold text-slate-700 text-sm">Genre</label>
                        <select name="genre" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-200 font-medium text-slate-800 text-sm">
                            <option value="homme" @selected(old('genre') === 'homme')>Homme</option>
                            <option value="femme" @selected(old('genre') === 'femme')>Femme</option>
                            <option value="autre" @selected(old('genre') === 'autre')>Autre</option>
                        </select>
                        @error('genre') <small class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</small> @enderror
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold text-slate-700 text-sm">Date de Naissance</label>
                        <input type="date" name="date_naissance" value="{{ old('date_naissance') }}" required max="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-200 font-medium text-slate-800 text-sm">
                        @error('date_naissance') <small class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</small> @enderror
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold text-slate-700 text-sm">Téléphone</label>
                        <input type="text" name="telephone" value="{{ old('telephone') }}" required placeholder="ex: 0661234567"
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-200 placeholder-slate-400 font-medium text-slate-800 text-sm">
                        @error('telephone') <small class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</small> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="pt-6 border-t border-slate-100 flex justify-end">
            <button type="submit" class="flex items-center gap-2 px-6 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-lg shadow-indigo-100 font-bold text-sm tracking-wide transition-all duration-300 active:scale-[0.98] cursor-pointer">
                <i data-lucide="user-plus" class="w-5 h-5"></i>
                Créer le dossier patient
            </button>
        </div>
    </form>
</div>
@endsection
