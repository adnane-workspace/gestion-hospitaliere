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
    <script>
        // Check local storage theme on page load and apply 'dark' class early
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
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
    <style>
        /* Premium Global Dark Mode Styles */
        .dark body {
            background-color: #0b0f19 !important;
            color: #f1f5f9 !important;
        }
        .dark aside {
            background-color: #0f172a !important;
            border-color: #1e293b !important;
        }
        .dark main {
            background-color: #0b0f19 !important;
        }
        .dark .bg-white {
            background-color: #0f172a !important;
            border-color: #1e293b !important;
            color: #f1f5f9 !important;
        }
        .dark .text-slate-800, .dark .text-slate-950, .dark .text-slate-900 {
            color: #f1f5f9 !important;
        }
        .dark .text-slate-700 {
            color: #cbd5e1 !important;
        }
        .dark .text-slate-600, .dark .text-slate-500 {
            color: #94a3b8 !important;
        }
        .dark .text-slate-400 {
            color: #64748b !important;
        }
        .dark .border-slate-200, .dark .border-slate-100, .dark .border-slate-200\/60, .dark .border-slate-100\/50, .dark .border-slate-200\/50 {
            border-color: #1e293b !important;
        }
        .dark input, .dark select, .dark textarea {
            background-color: #1e293b !important;
            border-color: #334155 !important;
            color: #f8fafc !important;
        }
        .dark input::placeholder {
            color: #64748b !important;
        }
        .dark table th {
            background-color: #1e293b !important;
            color: #94a3b8 !important;
        }
        .dark table td {
            border-color: #1e293b !important;
            color: #cbd5e1 !important;
        }
        .dark tr:hover {
            background-color: rgba(30, 41, 59, 0.4) !important;
        }
        .dark .hover\:bg-indigo-50:hover {
            background-color: #1e293b !important;
            color: #818cf8 !important;
        }
        .dark .hover\:bg-slate-50:hover {
            background-color: #1e293b !important;
        }
        .dark .bg-slate-50 {
            background-color: #1e293b !important;
        }
        .dark .apexcharts-text {
            fill: #94a3b8 !important;
        }
        .dark .apexcharts-legend-text {
            color: #cbd5e1 !important;
        }
        /* Custom sidebar styles for dark mode */
        .dark aside a.text-slate-500:not(.bg-indigo-600) {
            color: #94a3b8 !important;
        }
        .dark aside a.text-slate-500:not(.bg-indigo-600):hover {
            background-color: #1e293b !important;
            color: #818cf8 !important;
        }
    </style>
</head>

<body class="font-sans antialiased bg-slate-50 text-slate-900 overflow-x-hidden min-h-screen flex transition-colors duration-300" 
      x-data="{ 
          sidebarOpen: true,
          darkMode: localStorage.getItem('theme') === 'dark',
          toggleTheme() {
              this.darkMode = !this.darkMode;
              if (this.darkMode) {
                  document.documentElement.classList.add('dark');
                  localStorage.setItem('theme', 'dark');
              } else {
                  document.documentElement.classList.remove('dark');
                  localStorage.setItem('theme', 'light');
              }
              setTimeout(() => { lucide.createIcons(); }, 50);
          }
      }">
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
        <div class="flex items-center gap-4 px-2 mb-10 whitespace-nowrap">
            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-tr from-indigo-700 to-indigo-500 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-indigo-200 font-extrabold text-sm tracking-wide">
                HM
            </div>
            <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                <p class="text-xl font-extrabold tracking-tight text-slate-800 leading-none">
                    Hospit<span class="text-indigo-600">Manage</span>
                </p>
                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 mt-1">Gestion hospitaliere</p>
            </div>
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
                <a href="{{ route('medecin.disponibilites.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group {{ request()->routeIs('medecin.disponibilites.*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-500 hover:bg-indigo-50 hover:text-indigo-600' }}">
                    <i data-lucide="clock-3" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="font-semibold text-[15px]" x-show="sidebarOpen" x-transition>Disponibilites</span>
                </a>
                <a href="{{ route('patients.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group {{ request()->routeIs('patients.*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-500 hover:bg-indigo-50 hover:text-indigo-600' }}">
                    <i data-lucide="users" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="font-semibold text-[15px]" x-show="sidebarOpen" x-transition>Mes Patients</span>
                </a>
                <a href="{{ route('messages.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group {{ request()->routeIs('messages.*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-500 hover:bg-indigo-50 hover:text-indigo-600' }}">
                    <i data-lucide="messages-square" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="font-semibold text-[15px]" x-show="sidebarOpen" x-transition>Messagerie</span>
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
                <a href="{{ route('messages.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group {{ request()->routeIs('messages.*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-500 hover:bg-indigo-50 hover:text-indigo-600' }}">
                    <i data-lucide="messages-square" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="font-semibold text-[15px]" x-show="sidebarOpen" x-transition>Messagerie</span>
                </a>
            @endif

            @if(auth()->user()->isAdmin())
                <div class="pt-6 pb-2 px-4 uppercase text-[10px] font-bold tracking-widest text-slate-400" x-show="sidebarOpen">Administration</div>
                <a href="{{ route('patients.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group {{ request()->routeIs('patients.index') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-500 hover:bg-indigo-50 hover:text-indigo-600' }}">
                    <i data-lucide="users" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="font-semibold text-[15px]" x-show="sidebarOpen" x-transition>Gestion Patients</span>
                </a>
                <a href="{{ route('admin.medecins.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group {{ request()->routeIs('admin.medecins.*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-500 hover:bg-indigo-50 hover:text-indigo-600' }}">
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
                <a href="{{ route('admin.comptabilite') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group {{ request()->routeIs('admin.comptabilite') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-500 hover:bg-indigo-50 hover:text-indigo-600' }}">
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
            
            <div class="flex items-center gap-4" x-data="{ notificationsOpen: false, notifications: [] }">
                <!-- Dark Mode Switcher Button -->
                <button
                    @click="toggleTheme()"
                    class="w-12 h-12 flex items-center justify-center bg-white rounded-2xl shadow-sm border border-slate-200/60 text-slate-400 hover:text-indigo-600 transition-all relative dark:bg-slate-900 dark:border-slate-800 dark:text-slate-300 dark:hover:text-indigo-400"
                    title="Changer de thème"
                >
                    <i data-lucide="sun" class="w-5 h-5 hidden dark:block"></i>
                    <i data-lucide="moon" class="w-5 h-5 block dark:hidden"></i>
                </button>

                <button
                    @click="notificationsOpen = !notificationsOpen"
                    class="w-12 h-12 flex items-center justify-center bg-white rounded-2xl shadow-sm border border-slate-200/60 text-slate-400 hover:text-indigo-600 transition-all relative"
                >
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    @if($unreadNotificationsCount > 0)
                        <span id="notifications-dot" class="absolute top-3 right-3 w-2 h-2 bg-rose-500 rounded-full border-2 border-white"></span>
                    @endif
                </button>

                <div
                    x-cloak
                    x-show="notificationsOpen"
                    @click.away="notificationsOpen = false"
                    class="absolute right-10 top-24 w-[24rem] max-h-96 overflow-auto bg-white rounded-2xl border border-slate-200 shadow-2xl z-50"
                >
                    <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                        <p class="text-sm font-bold text-slate-800">Notifications</p>
                        <button id="mark-all-read" class="text-xs text-indigo-600 font-semibold hover:underline">Tout marquer lu</button>
                    </div>
                    <div id="notifications-list" class="divide-y divide-slate-100">
                        <p class="p-4 text-sm text-slate-400">Chargement...</p>
                    </div>
                </div>

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

        async function loadNotifications() {
            try {
                const response = await fetch('{{ route('notifications.index') }}');
                const data = await response.json();
                const list = document.getElementById('notifications-list');
                const dot = document.getElementById('notifications-dot');

                if (!list) return;

                if (!data.notifications.length) {
                    list.innerHTML = '<p class="p-4 text-sm text-slate-400">Aucune notification recente.</p>';
                } else {
                    list.innerHTML = data.notifications.map((item) => `
                        <div class="p-4 hover:bg-slate-50 transition">
                            <p class="text-sm font-semibold text-slate-800">${item.title}</p>
                            <p class="text-xs text-slate-500 mt-1">${item.reference ? `Ref: ${item.reference}` : ''} ${item.motif ?? ''}</p>
                            <p class="text-[11px] text-slate-400 mt-2">${item.created_at ?? ''}</p>
                        </div>
                    `).join('');
                }

                if (dot) {
                    dot.style.display = data.unread_count > 0 ? 'block' : 'none';
                }
            } catch (error) {
                // Silent fail to keep layout stable
            }
        }

        document.getElementById('mark-all-read')?.addEventListener('click', async () => {
            await fetch('{{ route('notifications.readAll') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            loadNotifications();
        });

        loadNotifications();
        setInterval(loadNotifications, 30000);
    </script>
    @include('partials.chatbot')
</body>

</html>