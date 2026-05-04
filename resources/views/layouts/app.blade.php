<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HospitManage - @yield('title', 'Gestion Hospitalière')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
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
                    },
                    boxShadow: {
                        'premium': '0 20px 50px -12px rgba(79, 70, 229, 0.12)',
                        'glow': '0 0 20px rgba(79, 70, 229, 0.2)',
                    }
                }
            }
        }
    </script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="font-sans antialiased bg-slate-50 text-slate-900 overflow-x-hidden min-h-screen flex" x-data="{ sidebarOpen: true }">
    <!-- Background Elements -->
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] rounded-full bg-indigo-600/10 blur-[120px]"></div>
        <div class="absolute top-[20%] -right-[10%] w-[30%] h-[50%] rounded-full bg-blue-600/5 blur-[100px]"></div>
        <div class="absolute -bottom-[10%] left-[20%] w-[50%] h-[30%] rounded-full bg-purple-600/10 blur-[120px]"></div>
    </div>

    <!-- Sidebar -->
    <aside 
        class="fixed left-0 top-0 h-screen bg-white/80 backdrop-blur-xl border-r border-slate-200/50 transition-all duration-500 z-50 flex flex-col p-6 shadow-2xl shadow-indigo-100/50"
        :class="sidebarOpen ? 'w-72' : 'w-24'"
    >
        <!-- Logo -->
        <div class="flex items-center gap-4 px-2 mb-10 overflow-hidden whitespace-nowrap">
            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-tr from-indigo-600 to-indigo-500 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-indigo-200">
                <i data-lucide="activity" class="w-6 h-6"></i>
            </div>
            <span class="text-xl font-bold tracking-tight text-slate-800" x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                Hospit<span class="text-indigo-600">Manage</span>
            </span>
        </div>

        <!-- Nav -->
        <nav class="flex-grow space-y-1.5">
            <a href="{{ route('dashboard') }}" 
               class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-200' : 'text-slate-500 hover:bg-indigo-50 hover:text-indigo-600' }}">
                <i data-lucide="layout-dashboard" class="w-5 h-5 flex-shrink-0"></i>
                <span class="font-semibold text-[15px]" x-show="sidebarOpen" x-transition>Tableau de bord</span>
            </a>

            @if(auth()->user()->isMedecin())
                <div class="pt-6 pb-2 px-4 uppercase text-[10px] font-bold tracking-widest text-slate-400" x-show="sidebarOpen">Médecin</div>
                <a href="{{ route('medecin.rendezvous.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group {{ request()->routeIs('medecin.rendezvous.*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-500 hover:bg-indigo-50 hover:text-indigo-600' }}">
                    <i data-lucide="calendar" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="font-semibold text-[15px]" x-show="sidebarOpen" x-transition>Mes Rendez-vous</span>
                </a>
                <a href="{{ route('medecin.consultations') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group {{ request()->routeIs('medecin.consultations') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-500 hover:bg-indigo-50 hover:text-indigo-600' }}">
                    <i data-lucide="clipboard-list" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="font-semibold text-[15px]" x-show="sidebarOpen" x-transition>Mes Consultations</span>
                </a>
                <a href="{{ route('patients.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group {{ request()->routeIs('patients.*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-500 hover:bg-indigo-50 hover:text-indigo-600' }}">
                    <i data-lucide="users" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="font-semibold text-[15px]" x-show="sidebarOpen" x-transition>Mes Patients</span>
                </a>
            @endif

            @if(auth()->user()->isPatient())
                <div class="pt-6 pb-2 px-4 uppercase text-[10px] font-bold tracking-widest text-slate-400" x-show="sidebarOpen">Espace Patient</div>
                <a href="{{ route('patient.rendezvous.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group {{ request()->routeIs('patient.rendezvous.index') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-500 hover:bg-indigo-50 hover:text-indigo-600' }}">
                    <i data-lucide="calendar" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="font-semibold text-[15px]" x-show="sidebarOpen" x-transition>Mes Rendez-vous</span>
                </a>
                <a href="{{ route('patient.rendezvous.create') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group {{ request()->routeIs('patient.rendezvous.create') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-500 hover:bg-indigo-50 hover:text-indigo-600' }}">
                    <i data-lucide="calendar-plus" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="font-semibold text-[15px]" x-show="sidebarOpen" x-transition>Prendre RDV</span>
                </a>
                <a href="{{ route('patient.profile') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group {{ request()->routeIs('patient.profile') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-500 hover:bg-indigo-50 hover:text-indigo-600' }}">
                    <i data-lucide="folder-heart" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="font-semibold text-[15px]" x-show="sidebarOpen" x-transition>Mon Dossier</span>
                </a>
            @endif

            @if(auth()->user()->isAdmin())
                <div class="pt-6 pb-2 px-4 uppercase text-[10px] font-bold tracking-widest text-slate-400" x-show="sidebarOpen">Administration</div>
                <a href="{{ route('patients.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group {{ request()->routeIs('patients.index') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-500 hover:bg-indigo-50 hover:text-indigo-600' }}">
                    <i data-lucide="users" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="font-semibold text-[15px]" x-show="sidebarOpen" x-transition>Gestion Patients</span>
                </a>
                <a href="{{ route('admin.medecins.pending') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group {{ request()->routeIs('admin.medecins.pending') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-500 hover:bg-indigo-50 hover:text-indigo-600' }}">
                    <i data-lucide="user-cog" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="font-semibold text-[15px]" x-show="sidebarOpen" x-transition>Gestion Médecins</span>
                    @php
                        $pendingCount = \App\Models\User::where('role', 'medecin')->where('is_active', false)->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span class="ml-auto w-5 h-5 bg-rose-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center animate-pulse" x-show="sidebarOpen">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group text-slate-500 hover:bg-indigo-50 hover:text-indigo-600">
                    <i data-lucide="banknote" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="font-semibold text-[15px]" x-show="sidebarOpen" x-transition>Comptabilité</span>
                </a>
            @endif
        </nav>

        <!-- Logout -->
        <div class="pt-6 border-t border-slate-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3.5 rounded-2xl text-rose-500 hover:bg-rose-50 transition-all group overflow-hidden">
                    <i data-lucide="log-out" class="w-5 h-5 group-hover:translate-x-1 transition-transform"></i>
                    <span class="font-semibold text-[15px]" x-show="sidebarOpen" x-transition>Déconnexion</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Content -->
    <main class="flex-grow transition-all duration-500 py-10 px-12" :class="sidebarOpen ? 'ml-72' : 'ml-24'">
        <!-- Topbar -->
        <div class="flex justify-between items-center mb-12">
            <div class="flex items-center gap-6">
                <button @click="sidebarOpen = !sidebarOpen" class="w-12 h-12 flex items-center justify-center bg-white rounded-2xl shadow-sm border border-slate-200/60 text-slate-400 hover:text-indigo-600 hover:border-indigo-100 transition-all">
                    <i data-lucide="menu" class="w-5 h-5" x-show="!sidebarOpen"></i>
                    <i data-lucide="chevron-left" class="w-5 h-5" x-show="sidebarOpen"></i>
                </button>
                <div class="hidden md:block">
                    <h2 class="text-sm font-semibold text-slate-400 uppercase tracking-widest">Aujourd'hui</h2>
                    <p class="text-lg font-bold text-slate-800">{{ now()->translatedFormat('l d F Y') }}</p>
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                <button class="w-12 h-12 flex items-center justify-center bg-white rounded-2xl shadow-sm border border-slate-200/60 text-slate-400 hover:text-indigo-600 transition-all relative">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    <span class="absolute top-3 right-3 w-2 h-2 bg-rose-500 rounded-full border-2 border-white"></span>
                </button>

                <div class="flex items-center gap-3 px-4 py-2 bg-white rounded-2xl border border-slate-200/60 shadow-sm">
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-indigo-600 to-blue-500 flex items-center justify-center text-white font-bold text-xs shadow-lg shadow-indigo-100">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="hidden lg:block text-left">
                        <p class="text-sm font-bold text-slate-800 leading-none">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] font-semibold text-slate-400 uppercase mt-1">{{ auth()->user()->role }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-8 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-3 text-emerald-700 shadow-sm">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                <p class="font-semibold">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-8 p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-center gap-3 text-rose-700 shadow-sm">
                <i data-lucide="alert-circle" class="w-5 h-5"></i>
                <p class="font-semibold">{{ session('error') }}</p>
            </div>
        @endif

        @yield('content')
    </main>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>