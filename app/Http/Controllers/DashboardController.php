<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Facture;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function getStats(): JsonResponse
    {
        $currentYear = Carbon::now()->year;

        // 1. Consultations par mois (Année en cours)
        // On initialise un tableau avec tous les mois à zéro pour Chart.js
        $months = collect(range(1, 12))->mapWithKeys(function ($month) {
            return [date('F', mktime(0, 0, 0, $month, 1)) => 0];
        });

        $consultationsPerMonth = Consultation::select(
                DB::raw('MONTH(date_heure) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('date_heure', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->mapWithKeys(function ($item) {
                return [date('F', mktime(0, 0, 0, $item->month, 1)) => $item->total];
            });

        $chartConsultations = $months->merge($consultationsPerMonth);

        // 2. Chiffre d'affaires total
        $totalRevenue = Facture::where('statut', 'payee')
            ->sum('montant_total_ttc');

        // 3. Top 5 des services les plus sollicités
        $topServices = Service::withCount('rendezvous')
            ->orderBy('rendezvous_count', 'desc')
            ->take(5)
            ->get();

        // 4. Structure JSON pour Chart.js
        return response()->json([
            'summary' => [
                'total_revenue' => number_format($totalRevenue, 2, '.', ''),
                'currency' => 'MAD',
                'year' => $currentYear
            ],
            'charts' => [
                'consultations' => [
                    'labels' => $chartConsultations->keys(),
                    'datasets' => [
                        [
                            'label' => 'Consultations ' . $currentYear,
                            'data' => $chartConsultations->values(),
                            'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                            'borderColor' => 'rgba(54, 162, 235, 1)',
                            'borderWidth' => 2
                        ]
                    ]
                ],
                'top_services' => [
                    'labels' => $topServices->pluck('nom'),
                    'datasets' => [
                        [
                            'label' => 'Nombre de RDV',
                            'data' => $topServices->pluck('rendezvous_count'),
                            'backgroundColor' => [
                                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'
                            ]
                        ]
                    ]
                ]
            ]
        ]);
    }
}
