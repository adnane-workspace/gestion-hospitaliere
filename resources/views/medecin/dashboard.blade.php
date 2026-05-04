@extends('layouts.app')

@section('title', 'Tableau de bord Premium')

@section('content')
<div class="mb-12 flex flex-col md:flex-row md:items-center justify-between gap-6">
    <div>
        @php
            $name = Auth::user()->name;
            $displayName = str_starts_with(strtolower($name), 'dr.') ? $name : 'Dr. ' . $name;
        @endphp
        <h1 class="text-4xl font-bold text-slate-800 tracking-tight mb-2">Bonjour, {{ $displayName }} 👋</h1>
        <p class="text-slate-500 font-medium text-lg">Prêt pour vos consultations du jour ? Voici votre aperçu médical.</p>
    </div>
    <div class="flex items-center gap-4">
        <a href="{{ route('medecin.report') }}" class="inline-flex items-center gap-2 bg-white border border-slate-200 px-6 py-3.5 rounded-2xl text-slate-600 font-bold hover:bg-slate-50 transition-all shadow-sm">
            <i data-lucide="download" class="w-5 h-5"></i> Rapport
        </a>
        <div class="bg-indigo-600 px-6 py-3.5 rounded-2xl text-white font-bold shadow-xl shadow-indigo-200">
            {{ now()->translatedFormat('d M Y') }}
        </div>
    </div>
</div>

<!-- Premium KPIs Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
    <!-- RDV Today -->
    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200/60 shadow-premium group hover:shadow-indigo-200/40 transition-all duration-500 relative overflow-hidden">
        <div class="relative z-10">
            <div class="flex justify-between items-start mb-6">
                <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 transition-transform group-hover:scale-110">
                    <i data-lucide="calendar" class="w-7 h-7"></i>
                </div>
                <div class="flex flex-col items-end">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Aujourd'hui</span>
                    <span class="text-emerald-500 text-xs font-bold flex items-center gap-1">
                        <i data-lucide="trending-up" class="w-3 h-3"></i> +12%
                    </span>
                </div>
            </div>
            <p class="text-slate-500 font-semibold mb-1">Rendez-vous</p>
            <div class="flex items-end justify-between">
                <h3 class="text-5xl font-black text-slate-800 tracking-tighter">{{ $stats['rdv_today'] }}</h3>
                <div id="sparkline-rdv" class="w-24 h-12 -mb-2"></div>
            </div>
        </div>
    </div>

    <!-- Consultations Done -->
    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200/60 shadow-premium group hover:shadow-blue-200/40 transition-all duration-500 relative overflow-hidden">
        <div class="relative z-10">
            <div class="flex justify-between items-start mb-6">
                <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 transition-transform group-hover:scale-110">
                    <i data-lucide="activity" class="w-7 h-7"></i>
                </div>
                <div class="flex flex-col items-end">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Ce mois</span>
                    <span class="text-blue-500 text-xs font-bold flex items-center gap-1">
                        <i data-lucide="trending-up" class="w-3 h-3"></i> +5%
                    </span>
                </div>
            </div>
            <p class="text-slate-500 font-semibold mb-1">Consultations</p>
            <div class="flex items-end justify-between">
                <h3 class="text-5xl font-black text-slate-800 tracking-tighter">{{ $stats['consultations_done'] }}</h3>
                <div id="sparkline-consult" class="w-24 h-12 -mb-2"></div>
            </div>
        </div>
    </div>

    <!-- New Patients -->
    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200/60 shadow-premium group hover:shadow-emerald-200/40 transition-all duration-500 relative overflow-hidden">
        <div class="relative z-10">
            <div class="flex justify-between items-start mb-6">
                <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 transition-transform group-hover:scale-110">
                    <i data-lucide="users" class="w-7 h-7"></i>
                </div>
                <div class="flex flex-col items-end">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Global</span>
                    <span class="text-emerald-500 text-xs font-bold flex items-center gap-1">
                        <i data-lucide="trending-up" class="w-3 h-3"></i> +24%
                    </span>
                </div>
            </div>
            <p class="text-slate-500 font-semibold mb-1">Patients</p>
            <div class="flex items-end justify-between">
                <h3 class="text-5xl font-black text-slate-800 tracking-tighter">{{ $stats['new_patients'] }}</h3>
                <div id="sparkline-patients" class="w-24 h-12 -mb-2"></div>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
    <!-- Area Chart : Activité Hebdomadaire -->
    <div class="lg:col-span-2 bg-white rounded-[2.5rem] border border-slate-200/60 shadow-premium p-10">
        <div class="flex justify-between items-center mb-10">
            <div>
                <h3 class="text-2xl font-bold text-slate-800 tracking-tight">Activité Hebdomadaire</h3>
                <p class="text-slate-400 font-medium mt-1">Flux de patients sur les 7 derniers jours</p>
            </div>
            <div class="flex bg-slate-50 p-1 rounded-xl">
                <button class="px-4 py-2 bg-white shadow-sm rounded-lg text-sm font-bold text-slate-800 transition-all">Semaine</button>
                <button class="px-4 py-2 text-sm font-bold text-slate-400 hover:text-slate-600 transition-all">Mois</button>
            </div>
        </div>
        <div id="activity-area-chart" class="h-80 w-full"></div>
    </div>

    <!-- Right Column : Next Appointment Spotlight -->
    <div class="bg-indigo-900 rounded-[2.5rem] p-10 text-white relative overflow-hidden shadow-glow">
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-indigo-500/20 rounded-full blur-[80px]"></div>
        <div class="relative z-10 flex flex-col h-full">
            <h3 class="text-xl font-bold mb-8">Prochain Patient</h3>
            
            @php $nextRdv = $appointments->first(); @endphp
            @if($nextRdv)
                <div class="flex items-center gap-6 mb-8">
                    <div class="w-20 h-20 bg-white/10 rounded-3xl backdrop-blur-xl flex items-center justify-center text-3xl font-black border border-white/10">
                        {{ substr($nextRdv->patient->user->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-2xl font-bold tracking-tight">{{ $nextRdv->patient->user->name }}</p>
                        <p class="text-indigo-300 font-medium">{{ \Carbon\Carbon::parse($nextRdv->date_heure_debut)->format('H:i') }} • {{ $nextRdv->motif }}</p>
                    </div>
                </div>
                <div class="mt-auto space-y-4">
                    <form method="POST" action="{{ route('medecin.consultations.start', $nextRdv) }}">
                        @csrf
                        <button type="submit" class="w-full py-4 bg-white text-indigo-900 font-black rounded-2xl shadow-xl transition-transform hover:scale-[1.02] active:scale-95">
                            Demarrer la Consultation
                        </button>
                    </form>
                    <a href="{{ route('patients.show', $nextRdv->patient) }}" class="block text-center w-full py-4 bg-white/5 border border-white/10 text-white font-bold rounded-2xl hover:bg-white/10 transition-all">
                        Voir le dossier complet
                    </a>
                </div>
            @else
                <div class="flex flex-col items-center justify-center flex-grow text-center opacity-50">
                    <i data-lucide="calendar-off" class="w-16 h-16 mb-4"></i>
                    <p class="font-bold">Aucun rendez-vous restant</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Agenda Table -->
<div class="bg-white rounded-[2.5rem] border border-slate-200/60 shadow-premium overflow-hidden mb-12">
    <div class="p-10 border-b border-slate-100 flex justify-between items-center">
        <h3 class="text-2xl font-bold text-slate-800 tracking-tight">Agenda du Jour</h3>
        <button class="text-indigo-600 font-bold hover:underline transition-all flex items-center gap-2">
            Voir l'agenda complet <i data-lucide="chevron-right" class="w-4 h-4"></i>
        </button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-10 py-6 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Heure</th>
                    <th class="px-10 py-6 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Patient</th>
                    <th class="px-10 py-6 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Motif de visite</th>
                    <th class="px-10 py-6 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($appointments as $rdv)
                <tr class="hover:bg-indigo-50/20 transition-all duration-300 group">
                    <td class="px-10 py-8">
                        <span class="inline-flex items-center px-4 py-1.5 bg-slate-100 text-slate-700 rounded-xl text-sm font-black tracking-tight group-hover:bg-white group-hover:shadow-sm transition-all">
                            {{ \Carbon\Carbon::parse($rdv->date_heure_debut)->format('H:i') }}
                        </span>
                    </td>
                    <td class="px-10 py-8">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gradient-to-tr from-slate-100 to-slate-50 rounded-2xl flex items-center justify-center text-slate-400 font-black text-lg shadow-sm group-hover:from-indigo-600 group-hover:to-indigo-500 group-hover:text-white transition-all">
                                {{ substr($rdv->patient->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-base font-bold text-slate-800">{{ $rdv->patient->user->name }}</p>
                                <p class="text-xs font-medium text-slate-400">{{ $rdv->patient->telephone }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-10 py-8">
                        @php
                            $isUrgency = str_contains(strtolower($rdv->motif), 'urgent') || str_contains(strtolower($rdv->motif), 'grave');
                            $motifColor = $isUrgency ? 'bg-rose-50 text-rose-600 border-rose-100' : 'bg-blue-50 text-blue-600 border-blue-100';
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $motifColor }}">
                            {{ $rdv->motif ?? 'Consultation' }}
                        </span>
                    </td>
                    <td class="px-10 py-8 text-right">
                        <form method="POST" action="{{ route('medecin.consultations.start', $rdv) }}" class="inline">
                            @csrf
                            <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white text-xs font-black rounded-xl shadow-lg shadow-indigo-100 hover:bg-indigo-700 hover:shadow-indigo-200 transition-all transform group-hover:scale-105">
                                Consulter
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-10 py-32 text-center text-slate-400 font-medium">
                        <div class="flex flex-col items-center opacity-20">
                            <i data-lucide="calendar-check-2" class="w-20 h-20 mb-6"></i>
                            <h4 class="text-2xl font-bold">Journée calme</h4>
                            <p>Aucun rendez-vous programmé pour le moment.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();

        // ApexCharts : Area Chart Activité Hebdomadaire
        const activityOptions = {
            series: [{
                name: 'Patients',
                data: [31, 40, 28, 51, 42, 109, 100]
            }],
            chart: {
                height: 320,
                type: 'area',
                toolbar: { show: false },
                fontFamily: 'Outfit, sans-serif'
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3, colors: ['#4f46e5'] },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.45,
                    opacityTo: 0.05,
                    stops: [20, 100, 100],
                    colorStops: [
                        { offset: 0, color: "#4f46e5", opacity: 0.4 },
                        { offset: 100, color: "#4f46e5", opacity: 0 }
                    ]
                }
            },
            xaxis: {
                categories: ["Lun", "Mar", "Mer", "Jeu", "Ven", "Sam", "Dim"],
                axisBorder: { show: false },
                axisTicks: { show: false },
            },
            yaxis: { show: false },
            grid: {
                show: true,
                borderColor: '#f1f5f9',
                strokeDashArray: 4,
                padding: { left: 0, right: 0 }
            },
            colors: ['#4f46e5']
        };
        new ApexCharts(document.querySelector("#activity-area-chart"), activityOptions).render();

        // Sparklines
        const sparklineConfig = (color) => ({
            series: [{ data: [12, 14, 2, 47, 42, 15, 47, 75, 65, 19, 14] }],
            chart: { type: 'area', height: 48, sparkline: { enabled: true } },
            stroke: { curve: 'smooth', width: 2 },
            fill: { opacity: 0.3 },
            colors: [color]
        });

        new ApexCharts(document.querySelector("#sparkline-rdv"), sparklineConfig('#4f46e5')).render();
        new ApexCharts(document.querySelector("#sparkline-consult"), sparklineConfig('#3b82f6')).render();
        new ApexCharts(document.querySelector("#sparkline-patients"), sparklineConfig('#10b981')).render();
    });
</script>
@endsection
