<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRendezVousRequest;
use App\Services\RendezVousService;
use Illuminate\Http\JsonResponse;
use Exception;

class RendezVousController extends Controller
{
    protected $rendezVousService;

    public function __construct(RendezVousService $rendezVousService)
    {
        $this->rendezVousService = $rendezVousService;
    }

    /**
     * Display a listing of the patient's appointments.
     */
    public function index()
    {
        $patient = \Illuminate\Support\Facades\Auth::user()->patient;

        if (!$patient) {
            return redirect()->route('dashboard')->with('error', 'Profil patient non trouvé.');
        }

        $appointments = \App\Models\RendezVous::with('medecin.user')
            ->where('patient_id', $patient->id)
            ->orderBy('date_heure_debut', 'desc')
            ->paginate(10);

        return view('patient.rendezvous.index', compact('appointments'));
    }

    /**
     * Display a listing of the doctor's appointments.
     */
    public function medecinIndex()
    {
        $medecin = \Illuminate\Support\Facades\Auth::user()->medecin;

        if (!$medecin) {
            return redirect()->route('dashboard')->with('error', 'Profil médecin non trouvé.');
        }

        $appointments = \App\Models\RendezVous::with('patient.user')
            ->where('medecin_id', $medecin->id)
            ->orderBy('date_heure_debut', 'desc')
            ->paginate(15);

        return view('medecin.rendezvous.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new appointment.
     */
    public function create()
    {
        $medecins = \App\Models\Medecin::with('user', 'service')->get();
        $services = \App\Models\Service::all();
        return view('patient.rendezvous.create', compact('medecins', 'services'));
    }

    /**
     * Store a newly created appointment.
     */
    public function store(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'medecin_id' => 'required|exists:medecins,id',
            'date_heure_debut' => 'required|date|after:now',
            'motif' => 'nullable|string|max:255',
        ]);

        $patient = \Illuminate\Support\Facades\Auth::user()->patient;

        if (!$patient) {
            return back()->with('error', 'Profil patient non trouvé.');
        }

        // Génération d'une référence unique
        $reference = 'RDV-' . date('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(5));

        \App\Models\RendezVous::create([
            'reference' => $reference,
            'patient_id' => $patient->id,
            'medecin_id' => $validated['medecin_id'],
            'date_heure_debut' => $validated['date_heure_debut'],
            'date_heure_fin' => \Carbon\Carbon::parse($validated['date_heure_debut'])->addMinutes(30),
            'motif' => $validated['motif'] ?? 'Consultation',
            'statut' => 'planifie',
            'type_rendez_vous' => 'premiere_consultation',
            'canal_prise_rdv' => 'en_ligne',
        ]);

        return redirect()->route('patient.dashboard')->with('success', "Votre rendez-vous a été enregistré. Référence : $reference");
    }
}
