<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HospitManage - @yield('title', 'Gestion Hospitalière')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #2563eb;
            --primary-light: #eff6ff;
            --secondary: #64748b;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --bg-body: #f8fafc;
            --bg-sidebar: #ffffff;
            --text-main: #1e293b;
            --card-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg-body);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: var(--bg-sidebar);
            height: 100vh;
            border-right: 1px solid #e2e8f0;
            padding: 2rem 1.5rem;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 50;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 3rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-links {
            list-style: none;
        }

        .nav-item {
            margin-bottom: 0.5rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem 1rem;
            text-decoration: none;
            color: var(--secondary);
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .nav-link:hover,
        .nav-link.active {
            background: var(--primary-light);
            color: var(--primary);
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            width: calc(100% - 260px);
            padding: 3rem 4rem;
            flex-grow: 1;
            min-height: 100vh;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .header h1 {
            font-size: 1.875rem;
            font-weight: 700;
        }

        /* Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .card {
            background: white;
            padding: 1.5rem;
            border-radius: 1rem;
            border: 1px solid #f1f5f9;
            box-shadow: var(--card-shadow);
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            margin-top: 0.5rem;
        }

        .stat-label {
            color: var(--secondary);
            font-size: 0.875rem;
        }

        /* Tables */
        .table-container {
            background: white;
            border-radius: 1rem;
            overflow: hidden;
            border: 1px solid #f1f5f9;
            box-shadow: var(--card-shadow);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f8fafc;
            padding: 1rem;
            text-align: left;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--secondary);
        }

        td {
            padding: 1rem;
            border-top: 1px solid #f1f5f9;
            font-size: 0.875rem;
        }

        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-success {
            background: #dcfce7;
            color: #166534;
        }

        .logout-btn {
            margin-top: auto;
            padding: 10px;
            color: var(--danger);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="logo" style="margin-bottom: 3rem; display: flex; justify-content: center; width: 100%;">
            <x-application-logo style="height: 80px; width: auto; max-width: 100%;" />
        </div>
        <ul class="nav-links">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}"
                    class="nav-link {{ request()->routeIs('dashboard') || request()->routeIs('*.dashboard') ? 'active' : '' }}">
                    🏠 Tableau de bord
                </a>
            </li>

            @if(auth()->user()->isMedecin())
                <li class="nav-item">
                    <a href="{{ route('medecin.consultations') }}" class="nav-link {{ request()->routeIs('medecin.consultations') ? 'active' : '' }}">
                        📅 Mes Consultations
                    </a>
                </li>
                <li class="nav-item"><a href="/patients" class="nav-link">👥 Mes Patients</a></li>
            @endif

            @if(auth()->user()->isPatient())
                <li class="nav-item"><a href="#" class="nav-link">➕ Prendre RDV</a></li>
                <li class="nav-item"><a href="#" class="nav-link">📂 Mon Dossier</a></li>
            @endif

            @if(auth()->user()->isAdmin())
                <li class="nav-item"><a href="/patients" class="nav-link">👥 Gestion Patients</a></li>
                <li class="nav-item"><a href="#" class="nav-link">👨‍⚕️ Gestion Médecins</a></li>
                <li class="nav-item"><a href="#" class="nav-link">💰 Comptabilité</a></li>
            @endif
        </ul>

        <form method="POST" action="{{ route('logout') }}" style="margin-top: 20px;">
            @csrf
            <a href="{{ route('logout') }}" class="logout-btn"
                onclick="event.preventDefault(); this.closest('form').submit();">
                Déconnexion
            </a>
        </form>
    </div>

    <div class="main-content">
        @yield('content')
    </div>
</body>

</html>