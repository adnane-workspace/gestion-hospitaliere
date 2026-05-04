<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRendezVousRequest;
use App\Models\RendezVous;
use App\Notifications\RendezVousCreeNotification;
use App\Services\RendezVousService;
use Exception;
use Illuminate\Support\Facades\Auth;

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
        $this->authorize('viewAny', RendezVous::class);
        $patient = Auth::user()->patient;

        if (!$patient) {
            return redirect()->route('dashboard')->with('error', 'Profil patient non trouvé.');
        }

        $query = RendezVous::with('medecin.user', 'medecin.service')
            ->where('patient_id', $patient->id)
            ->orderBy('date_heure_debut', 'desc');

        if (request('statut')) {
            $query->where('statut', request('statut'));
        }

        if (request('date_debut')) {
            $query->whereDate('date_heure_debut', '>=', request('date_debut'));
        }

        if (request('date_fin')) {
            $query->whereDate('date_heure_debut', '<=', request('date_fin'));
        }

        if (request('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                    ->orWhere('motif', 'like', "%{$search}%");
            });
        }

        $appointments = $query->paginate(10)->withQueryString();

        return view('patient.rendezvous.index', compact('appointments'));
    }

    /**
     * Display a listing of the doctor's appointments.
     */
    public function medecinIndex()
    {
        $this->authorize('viewAny', RendezVous::class);
        $medecin = Auth::user()->medecin;

        if (!$medecin) {
            return redirect()->route('dashboard')->with('error', 'Profil médecin non trouvé.');
        }

        $query = RendezVous::with('patient.user')
            ->where('medecin_id', $medecin->id)
            ->orderBy('date_heure_debut', 'desc');

        if (request('statut')) {
            $query->where('statut', request('statut'));
        }

        if (request('date_debut')) {
            $query->whereDate('date_heure_debut', '>=', request('date_debut'));
        }

        if (request('date_fin')) {
            $query->whereDate('date_heure_debut', '<=', request('date_fin'));
        }

        if (request('search')) {
            $search = request('search');
            $query->whereHas('patient.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $appointments = $query->paginate(15)->withQueryString();

        return view('medecin.rendezvous.index', compact('appointments'));
    }

    /**
     * Confirm a scheduled appointment.
     */
    public function confirm(RendezVous $rendezvous)
    {
        $this->authorize('view', $rendezvous);

        $medecin = Auth::user()->medecin;
        if (!$medecin || $rendezvous->medecin_id !== $medecin->id) {
            return back()->with('error', 'Action non autorisée.');
        }

        if (!$rendezvous->canTransitionTo(RendezVous::STATUT_CONFIRME)) {
            return back()->with('error', 'Ce rendez-vous ne peut pas être confirmé dans son statut actuel.');
        }

        $rendezvous->update(['statut' => RendezVous::STATUT_CONFIRME]);

        return back()->with('success', 'Le rendez-vous a été confirmé.');
    }

    /**
     * Show the form for creating a new appointment.
     */
    public function create()
    {
        $this->authorize('create', RendezVous::class);
        $medecins = \App\Models\Medecin::with('user', 'service')->get();
        $services = \App\Models\Service::all();
        return view('patient.rendezvous.create', compact('medecins', 'services'));
    }

    /**
     * Store a newly created appointment.
     */
    public function store(StoreRendezVousRequest $request)
    {
        $this->authorize('create', RendezVous::class);
        $validated = $request->validated();

        $patient = Auth::user()->patient;

        if (!$patient) {
            return back()->with('error', 'Profil patient non trouvé.');
        }

        try {
            $rendezVous = $this->rendezVousService->reserver([
                'patient_id' => $patient->id,
                'medecin_id' => $validated['medecin_id'],
                'date_heure_debut' => $validated['date_heure_debut'],
                'duree_minutes' => $validated['duree_minutes'] ?? 30,
                'motif' => $validated['motif'] ?? 'Consultation',
                'type_rendez_vous' => $validated['type_rendez_vous'] ?? 'premiere_consultation',
                'canal_prise_rdv' => 'en_ligne',
            ]);
        } catch (Exception $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        }

        $medecinUser = optional($rendezVous->medecin)->user;
        if ($medecinUser) {
            $medecinUser->notify(new RendezVousCreeNotification($rendezVous, 'medecin'));
        }

        $patient->user?->notify(new RendezVousCreeNotification($rendezVous, 'patient'));

        return redirect()->route('patient.dashboard')->with('success', "Votre rendez-vous a été enregistré. Référence : {$rendezVous->reference}");
    }
}
