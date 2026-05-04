<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - HospitManage</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    borderRadius: {
                        '4xl': '2rem',
                        '5xl': '2.5rem',
                    }
                }
            }
        }
    </script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-900 overflow-hidden">
    <div class="flex min-h-screen">
        <!-- Côté Gauche : Média / Image Immersive -->
        <div class="hidden lg:block lg:w-1/2 relative overflow-hidden">
            <img src="{{ asset('images/login-bg.png') }}" alt="Medical Background" class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-tr from-indigo-950/80 via-indigo-900/40 to-transparent"></div>
            
            <div class="absolute bottom-20 left-20 right-20 text-white z-10">
                <div class="w-16 h-1 w-16 bg-white mb-8 rounded-full"></div>
                <h2 class="text-5xl font-bold leading-tight mb-6">L'excellence au service de votre santé.</h2>
                <p class="text-xl text-indigo-100 font-medium leading-relaxed max-w-lg">
                    Une plateforme intuitive et performante pour une gestion hospitalière fluide et humaine.
                </p>
            </div>
            
            <!-- Floating Decorative Elements -->
            <div class="absolute top-20 left-20 w-32 h-32 bg-white/10 rounded-4xl blur-2xl animate-pulse"></div>
            <div class="absolute bottom-40 right-20 w-48 h-48 bg-indigo-500/20 rounded-full blur-3xl animate-bounce"></div>
        </div>

        <!-- Côté Droit : Formulaire de connexion -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 md:p-16 bg-white">
            <div class="w-full max-w-md">
                <!-- Logo & Header -->
                <div class="mb-12">
                    <div class="flex items-center gap-4 mb-10">
                        <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-2xl shadow-indigo-200">
                            <i data-lucide="activity" class="w-8 h-8"></i>
                        </div>
                        <span class="text-2xl font-bold tracking-tight text-slate-800">
                            Hospit<span class="text-indigo-600">Manage</span>
                        </span>
                    </div>
                    <h1 class="text-4xl font-black text-slate-800 tracking-tight mb-3">Ravi de vous revoir</h1>
                    <p class="text-slate-500 font-medium">Veuillez vous connecter à votre compte.</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-6 p-4 bg-indigo-50 text-indigo-700 rounded-2xl font-semibold text-sm" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div class="group">
                        <label for="email" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 px-1">Adresse Email</label>
                        <div class="relative">
                            <div class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-indigo-600 transition-colors">
                                <i data-lucide="mail" class="w-5 h-5"></i>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                                placeholder="nom@exemple.com"
                                class="w-full bg-slate-50 border-transparent border-2 rounded-2xl pl-14 pr-6 py-4 text-slate-700 font-medium placeholder:text-slate-300 focus:bg-white focus:border-indigo-600/10 focus:ring-4 focus:ring-indigo-600/5 transition-all outline-none">
                        </div>
                        @if($errors->has('email'))
                            <p class="mt-2 text-xs text-rose-500 font-bold px-1">{{ $errors->first('email') }}</p>
                        @endif
                    </div>

                    <!-- Password -->
                    <div class="group">
                        <div class="flex justify-between items-center mb-2 px-1">
                            <label for="password" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Mot de passe</label>
                            @if (Route::has('password.request'))
                                <a class="text-[10px] font-bold text-indigo-500 hover:text-indigo-700 uppercase tracking-widest transition-colors" href="{{ route('password.request') }}">
                                    Oublié ?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <div class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-indigo-600 transition-colors">
                                <i data-lucide="lock" class="w-5 h-5"></i>
                            </div>
                            <input id="password" type="password" name="password" required autocomplete="current-password"
                                placeholder="••••••••"
                                class="w-full bg-slate-50 border-transparent border-2 rounded-2xl pl-14 pr-6 py-4 text-slate-700 font-medium placeholder:text-slate-300 focus:bg-white focus:border-indigo-600/10 focus:ring-4 focus:ring-indigo-600/5 transition-all outline-none">
                        </div>
                        @if($errors->has('password'))
                            <p class="mt-2 text-xs text-rose-500 font-bold px-1">{{ $errors->first('password') }}</p>
                        @endif
                    </div>

                    <!-- Login Button -->
                    <div class="pt-4">
                        <button type="submit" class="w-full py-5 bg-indigo-600 text-white font-bold rounded-2xl shadow-xl shadow-indigo-100 hover:bg-indigo-700 hover:shadow-2xl hover:shadow-indigo-200 transition-all transform hover:scale-[1.01] active:scale-[0.99] flex items-center justify-center gap-3">
                            <span>Se connecter</span>
                            <i data-lucide="chevron-right" class="w-5 h-5"></i>
                        </button>
                    </div>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-slate-500 font-medium">
                        Pas encore de compte ? 
                        <a href="{{ route('register', ['role' => 'patient']) }}" class="text-indigo-600 font-bold hover:underline ml-1 transition-all">Créer un compte</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
