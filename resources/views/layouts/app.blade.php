<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HospitManage - @yield('title', 'Gestion Hospitalière')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --primary-light: #eef2ff;
            --secondary: #64748b;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --bg-body: #f1f5f9;
            --bg-sidebar: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --glass-bg: rgba(255, 255, 255, 0.8);
            --glass-border: rgba(255, 255, 255, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            transition: all 0.2s ease-in-out;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg-body);
            background-image: radial-gradient(at 0% 0%, hsla(253,16%,7%,1) 0, transparent 50%), 
                              radial-gradient(at 50% 0%, hsla(225,39%,30%,1) 0, transparent 50%), 
                              radial-gradient(at 100% 0%, hsla(339,49%,30%,1) 0, transparent 50%);
            background-attachment: fixed;
            background-size: cover;
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Glassmorphism */
        .sidebar {
            width: 280px;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            height: 100vh;
            border-right: 1px solid var(--glass-border);
            padding: 2.5rem 1.5rem;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 50;
            display: flex;
            flex-direction: column;
        }

        .logo {
            padding: 0 1rem;
            margin-bottom: 3.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nav-links {
            list-style: none;
            flex-grow: 1;
        }

        .nav-item {
            margin-bottom: 0.75rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.875rem 1.25rem;
            text-decoration: none;
            color: #475569;
            border-radius: 12px;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .nav-link i {
            width: 20px;
            height: 20px;
        }

        .nav-link:hover {
            background: rgba(79, 70, 229, 0.08);
            color: var(--primary);
            transform: translateX(5px);
        }

        .nav-link.active {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            width: calc(100% - 280px);
            padding: 3rem 4rem;
            flex-grow: 1;
            min-height: 100vh;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3rem;
            color: white;
        }

        .header h1 {
            font-size: 2.25rem;
            font-weight: 700;
            letter-spacing: -0.025em;
        }

        /* Cards & Glassmorphism */
        .card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 2rem;
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            padding: 1.75rem;
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
        }

        .stat-value {
            font-size: 1.875rem;
            font-weight: 700;
            margin-top: 0.5rem;
        }

        .stat-label {
            opacity: 0.8;
            font-size: 0.875rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Tables */
        .table-container {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f8fafc;
            padding: 1.25rem 1.5rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            color: #64748b;
            border-bottom: 1px solid #f1f5f9;
        }

        td {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.9375rem;
            color: #334155;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background: #fbfcfe;
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.375rem 1rem;
            border-radius: 12px;
            font-size: 0.8125rem;
            font-weight: 600;
        }

        .badge-success { background: #ecfdf5; color: #059669; }
        .badge-primary { background: #eef2ff; color: #4f46e5; }
        .badge-danger { background: #fef2f2; color: #dc2626; }

        .btn-primary {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.875rem 1.75rem;
            border-radius: 14px;
            font-weight: 600;
            font-size: 0.9375rem;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(79, 70, 229, 0.3);
        }

        .logout-btn {
            margin-top: auto;
            padding: 0.875rem 1.25rem;
            color: #ef4444;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
            border-radius: 12px;
        }

        .logout-btn:hover {
            background: #fef2f2;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="logo">
            <div style="background: var(--primary); width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white;">
                <i data-lucide="activity"></i>
            </div>
            <span style="font-size: 1.25rem; font-weight: 700; color: var(--text-main);">Hospit<span style="color: var(--primary);">Manage</span></span>
        </div>
        <ul class="nav-links">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}"
                    class="nav-link {{ request()->routeIs('dashboard') || request()->routeIs('*.dashboard') ? 'active' : '' }}">
                    <i data-lucide="layout-dashboard"></i> Tableau de bord
                </a>
            </li>

            @if(auth()->user()->isMedecin())
                <li class="nav-item">
                    <a href="{{ route('medecin.consultations') }}" class="nav-link {{ request()->routeIs('medecin.consultations') ? 'active' : '' }}">
                        <i data-lucide="clipboard-list"></i> Mes Consultations
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('patients.index') }}" class="nav-link {{ request()->routeIs('patients.*') ? 'active' : '' }}">
                        <i data-lucide="users"></i> Mes Patients
                    </a>
                </li>
            @endif

            @if(auth()->user()->isPatient())
                <li class="nav-item">
                    <a href="{{ route('patient.rendezvous.index') }}" class="nav-link {{ request()->routeIs('patient.rendezvous.index') ? 'active' : '' }}">
                        <i data-lucide="calendar"></i> Mes Rendez-vous
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('patient.rendezvous.create') }}" class="nav-link {{ request()->routeIs('patient.rendezvous.create') ? 'active' : '' }}">
                        <i data-lucide="calendar-plus"></i> Prendre RDV
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('patient.profile') }}" class="nav-link {{ request()->routeIs('patient.profile') ? 'active' : '' }}">
                        <i data-lucide="folder-heart"></i> Mon Dossier
                    </a>
                </li>
            @endif

            @if(auth()->user()->isAdmin())
                <li class="nav-item">
                    <a href="{{ route('patients.index') }}" class="nav-link {{ request()->routeIs('patients.*') ? 'active' : '' }}">
                        <i data-lucide="users"></i> Gestion Patients
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i data-lucide="user-cog"></i> Gestion Médecins
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i data-lucide="banknote"></i> Comptabilité
                    </a>
                </li>
            @endif
        </ul>

        <form method="POST" action="{{ route('logout') }}" style="margin-top: 20px;">
            @csrf
            <a href="{{ route('logout') }}" class="logout-btn"
                onclick="event.preventDefault(); this.closest('form').submit();">
                <i data-lucide="log-out"></i> Déconnexion
            </a>
        </form>
    </div>

    <div class="main-content">
        @yield('content')
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>