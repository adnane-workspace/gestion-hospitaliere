<?php

namespace App\Http\Controllers;

use App\Models\RendezVous;
use App\Models\Consultation;
use App\Models\Patient;
use App\Models\User;
use App\Models\Facture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $stats = [
                'total_patients' => Patient::count(),
                'medecins_actifs' => User::where('role', 'medecin')->where('is_active', true)->count(),
                'medecins_en_attente' => User::where('role', 'medecin')->where('is_active', false)->count(),
                'rdv_mois' => RendezVous::whereMonth('date_heure_debut', Carbon::now()->month)->count(),
                'revenus_mois' => Facture::whereMonth('date_emission', Carbon::now()->month)->sum('montant_total_ttc'),
            ];

            return view('admin.dashboard', compact('stats'));
        }

        if ($user->isMedecin()) {
            $medecin = $user->medecin;

            if (!$medecin) {
                return redirect('/')->with('error', 'Profil medecin non trouve pour cet utilisateur.');
            }

            $stats = [
                'rdv_today' => RendezVous::where('medecin_id', $medecin->id)
                    ->whereDate('date_heure_debut', Carbon::today())
                    ->count(),
                'consultations_done' => Consultation::where('medecin_id', $medecin->id)
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->count(),
                'new_patients' => Patient::whereMonth('created_at', Carbon::now()->month)->count(),
            ];

            $appointments = RendezVous::with('patient.user')
                ->where('medecin_id', $medecin->id)
                ->whereDate('date_heure_debut', Carbon::today())
                ->orderBy('date_heure_debut', 'asc')
                ->get();

            $recent_consultations = Consultation::with('patient.user')
                ->where('medecin_id', $medecin->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            return view('medecin.dashboard', compact('stats', 'appointments', 'recent_consultations'));
        }

        if ($user->isPatient()) {
            $patient = $user->patient;

            if (!$patient) {
                return redirect('/')->with('error', 'Profil patient non trouve pour cet utilisateur.');
            }

            $nextAppointment = RendezVous::with('medecin.user')
                ->where('patient_id', $patient->id)
                ->where('date_heure_debut', '>=', Carbon::now())
                ->orderBy('date_heure_debut', 'asc')
                ->first();

            $history = Consultation::with('medecin.user', 'service')
                ->where('patient_id', $patient->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return view('patient.dashboard', compact('patient', 'nextAppointment', 'history'));
        }

        return redirect('/');
    }

    public function report()
    {
        $user = Auth::user();

        if (!$user->isMedecin()) {
            return redirect()->route('dashboard')->with('error', 'Accès refusé.');
        }

        $medecin = $user->medecin;
        if (!$medecin) {
            return redirect()->route('dashboard')->with('error', 'Profil médecin non trouvé.');
        }

        $stats = [
            'rdv_today' => RendezVous::where('medecin_id', $medecin->id)
                ->whereDate('date_heure_debut', Carbon::today())
                ->count(),
            'consultations_done' => Consultation::where('medecin_id', $medecin->id)
                ->whereMonth('created_at', Carbon::now()->month)
                ->count(),
            'new_patients' => Patient::whereMonth('created_at', Carbon::now()->month)->count(),
        ];

        $appointments = RendezVous::with('patient.user')
            ->where('medecin_id', $medecin->id)
            ->whereDate('date_heure_debut', Carbon::today())
            ->orderBy('date_heure_debut', 'asc')
            ->get();

        $recent_consultations = Consultation::with('patient.user')
            ->where('medecin_id', $medecin->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('medecin.report', compact('stats', 'appointments', 'recent_consultations'));
    }

    public function comptabilite()
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Accès refusé.');
        }

        $factures = Facture::with(['patient.user'])
            ->orderBy('date_emission', 'desc')
            ->limit(10)
            ->get();

        $stats = [
            'total_factures' => Facture::count(),
            'revenus_totaux' => Facture::sum('montant_total_ttc'),
            'factures_payees' => Facture::where('statut', 'payee')->count(),
            'factures_en_retard' => Facture::where('statut', 'en_retard')->count(),
        ];

        return view('admin.comptabilite', compact('stats', 'factures'));
    }

    public function getStats(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($user->isAdmin()) {
            return response()->json([
                'role' => 'admin',
                'patients' => Patient::count(),
                'medecins_actifs' => User::where('role', 'medecin')->where('is_active', true)->count(),
                'rdv_mois' => RendezVous::whereMonth('date_heure_debut', Carbon::now()->month)->count(),
                'revenus_mois' => (float) Facture::whereMonth('date_emission', Carbon::now()->month)->sum('montant_total_ttc'),
            ]);
        }

        if ($user->isMedecin() && $user->medecin) {
            $medecinId = $user->medecin->id;

            return response()->json([
                'role' => 'medecin',
                'rdv_today' => RendezVous::where('medecin_id', $medecinId)->whereDate('date_heure_debut', Carbon::today())->count(),
                'consultations_mois' => Consultation::where('medecin_id', $medecinId)->whereMonth('created_at', Carbon::now()->month)->count(),
                'patients_uniques_mois' => Consultation::where('medecin_id', $medecinId)
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->distinct('patient_id')
                    ->count('patient_id'),
            ]);
        }

        if ($user->isPatient() && $user->patient) {
            $patientId = $user->patient->id;

            return response()->json([
                'role' => 'patient',
                'rdv_futurs' => RendezVous::where('patient_id', $patientId)->where('date_heure_debut', '>=', Carbon::now())->count(),
                'consultations_total' => Consultation::where('patient_id', $patientId)->count(),
                'derniere_consultation' => Consultation::where('patient_id', $patientId)
                    ->latest('date_heure')
                    ->value('date_heure'),
            ]);
        }

        return response()->json(['message' => 'Profil incomplet'], 422);
    }
}
