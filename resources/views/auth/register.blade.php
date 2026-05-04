<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - HospitManage</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        indigo: {
                            50: '#f5f7ff',
                            100: '#ebf0fe',
                            200: '#dae3fd',
                            300: '#bccaf9',
                            400: '#95a7f4',
                            500: '#6a7ced',
                            600: '#4c57e1',
                            700: '#3c42cb',
                            800: '#3538a6',
                            900: '#2f3284',
                            950: '#1c1d4d',
                        },
                    }
                }
            }
        }
    </script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        [x-cloak] { display: none !important; }
        .bg-pattern {
            background-color: #ffffff;
            background-image: radial-gradient(#e5e7eb 0.5px, transparent 0.5px);
            background-size: 24px 24px;
        }
    </style>
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-900 bg-pattern" x-data="{ role: '{{ old('role', request('role', 'patient')) }}' }">
    <div class="min-h-screen flex items-center justify-center p-4 md:p-8">
        <div class="w-full max-w-5xl flex flex-col lg:flex-row bg-white rounded-[2rem] shadow-[0_32px_64px_-16px_rgba(0,0,0,0.1)] overflow-hidden border border-slate-100">
            
            <!-- Left Side: Branding & Visuals -->
            <div class="lg:w-2/5 bg-indigo-600 p-8 md:p-12 flex flex-col justify-between relative overflow-hidden">
                <!-- Decorative Circles -->
                <div class="absolute -top-24 -left-24 w-64 h-64 bg-indigo-500 rounded-full opacity-50"></div>
                <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-indigo-700 rounded-full opacity-50"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-16">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-indigo-600 shadow-lg">
                            <i data-lucide="activity" class="w-6 h-6"></i>
                        </div>
                        <span class="text-xl font-bold tracking-tight text-white">HospitManage</span>
                    </div>

                    <h2 class="text-4xl font-extrabold text-white leading-tight mb-6">
                        L'excellence au service de <span class="text-indigo-200">votre santé.</span>
                    </h2>
                    <p class="text-indigo-100 text-lg leading-relaxed mb-12 opacity-90">
                        Gérez vos rendez-vous, consultez vos dossiers médicaux et restez en contact avec vos médecins.
                    </p>

                    <div class="space-y-6">
                        <div class="flex items-center gap-4 text-white/90">
                            <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center">
                                <i data-lucide="check" class="w-4 h-4"></i>
                            </div>
                            <span class="font-medium">Simple & Intuitif</span>
                        </div>
                        <div class="flex items-center gap-4 text-white/90">
                            <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center">
                                <i data-lucide="shield-check" class="w-4 h-4"></i>
                            </div>
                            <span class="font-medium">Sécurité Totale (RGPD)</span>
                        </div>
                        <div class="flex items-center gap-4 text-white/90">
                            <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center">
                                <i data-lucide="zap" class="w-4 h-4"></i>
                            </div>
                            <span class="font-medium">Accès Immédiat</span>
                        </div>
                    </div>
                </div>

                <div class="relative z-10 mt-12 pt-12 border-t border-white/10">
                    <p class="text-indigo-200 text-sm font-medium">Déjà plus de 10,000 patients nous font confiance à travers le pays.</p>
                </div>
            </div>

            <!-- Right Side: Form -->
            <div class="lg:w-3/5 p-8 md:p-12 lg:p-16">
                <div class="max-w-md mx-auto">
                    <div class="mb-10 text-center">
                        <h1 class="text-3xl font-bold text-slate-800 mb-2">Créer un compte</h1>
                        <p class="text-slate-500">Inscrivez-vous pour accéder à votre espace de santé.</p>
                    </div>

                    @if ($errors->any())
                        <div class="mb-8 p-4 bg-rose-50 border border-rose-100 rounded-2xl">
                            <ul class="list-disc list-inside text-sm text-rose-600 font-medium">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" class="space-y-6">
                        @csrf

                        <!-- Role Selection Tabs -->
                        <div class="flex p-1 bg-slate-100 rounded-2xl mb-8">
                            <button type="button" 
                                    @click="role = 'patient'"
                                    :class="role === 'patient' ? 'bg-white shadow-sm text-indigo-600' : 'text-slate-500 hover:text-slate-700'"
                                    class="flex-1 py-3 px-4 rounded-xl text-sm font-bold transition-all duration-200 flex items-center justify-center gap-2">
                                <i data-lucide="user" class="w-4 h-4"></i>
                                Patient
                            </button>
                            <button type="button" 
                                    @click="role = 'medecin'"
                                    :class="role === 'medecin' ? 'bg-white shadow-sm text-indigo-600' : 'text-slate-500 hover:text-slate-700'"
                                    class="flex-1 py-3 px-4 rounded-xl text-sm font-bold transition-all duration-200 flex items-center justify-center gap-2">
                                <i data-lucide="stethoscope" class="w-4 h-4"></i>
                                Médecin
                            </button>
                            <input type="hidden" name="role" :value="role">
                        </div>

                        <!-- Main Info -->
                        <div class="space-y-4">
                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 block ml-1">Nom Complet</label>
                                <input type="text" name="name" value="{{ old('name') }}" required 
                                       class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/5 transition-all outline-none text-slate-700 font-medium placeholder:text-slate-300"
                                       placeholder="Ex: Jean Dupont">
                                @error('name') <p class="text-xs text-rose-500 mt-2 ml-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 block ml-1">Adresse Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                       class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/5 transition-all outline-none text-slate-700 font-medium placeholder:text-slate-300"
                                       placeholder="nom@exemple.com">
                                @error('email') <p class="text-xs text-rose-500 mt-2 ml-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Patient Specific (Conditionnal) -->
                        <div x-show="role === 'patient'" x-transition x-cloak class="space-y-4 pt-4 border-t border-slate-100">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 block ml-1">Prénom</label>
                                    <input type="text" name="prenom" :required="role === 'patient'" value="{{ old('prenom') }}"
                                           class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-indigo-500 transition-all outline-none text-slate-700 font-medium"
                                           placeholder="Prénom">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 block ml-1">Téléphone</label>
                                    <input type="text" name="telephone" :required="role === 'patient'" value="{{ old('telephone') }}"
                                           class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-indigo-500 transition-all outline-none text-slate-700 font-medium"
                                           placeholder="06...">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 block ml-1">Naissance</label>
                                    <input type="date" name="date_naissance" :required="role === 'patient'" value="{{ old('date_naissance') }}"
                                           class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-indigo-500 transition-all outline-none text-slate-700 font-medium">
                                    @error('date_naissance') <p class="text-xs text-rose-500 mt-2 ml-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 block ml-1">Genre</label>
                                    <select name="genre" :required="role === 'patient'"
                                            class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-indigo-500 transition-all outline-none text-slate-700 font-medium appearance-none">
                                        <option value="homme" {{ old('genre') == 'homme' ? 'selected' : '' }}>Homme</option>
                                        <option value="femme" {{ old('genre') == 'femme' ? 'selected' : '' }}>Femme</option>
                                        <option value="autre" {{ old('genre') == 'autre' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                    @error('genre') <p class="text-xs text-rose-500 mt-2 ml-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Passwords -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 block ml-1">Mot de passe</label>
                                <input type="password" name="password" required
                                       class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-indigo-500 transition-all outline-none text-slate-700 font-medium placeholder:text-slate-300"
                                       placeholder="••••••••">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 block ml-1">Confirmer</label>
                                <input type="password" name="password_confirmation" required
                                       class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-indigo-500 transition-all outline-none text-slate-700 font-medium placeholder:text-slate-300"
                                       placeholder="••••••••">
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="pt-6">
                            <button type="submit" 
                                    class="w-full py-5 bg-indigo-600 text-white font-bold text-lg rounded-2xl shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-[0.98] flex items-center justify-center gap-3">
                                <span>Créer mon compte</span>
                                <i data-lucide="chevron-right" class="w-5 h-5"></i>
                            </button>
                            <p class="mt-8 text-center text-slate-500 font-medium">
                                Déjà inscrit ? 
                                <a href="{{ route('login') }}" class="text-indigo-600 font-bold hover:underline ml-1">Se connecter</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
