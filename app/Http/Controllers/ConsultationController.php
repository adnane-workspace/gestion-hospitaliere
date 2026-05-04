<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\RendezVous;
use App\Services\ConsultationWorkflowService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Exception;

class ConsultationController extends Controller
{
    public function __construct(
        private readonly ConsultationWorkflowService $consultationWorkflowService
    ) {
    }

    /**
     * Display a listing of the doctor's consultations.
     */
    public function index()
    {
        $this->authorize('viewAny', Consultation::class);
        $medecin = Auth::user()->medecin;

        if (!$medecin) {
            return redirect()->route('dashboard')->with('error', 'Profil médecin non trouvé.');
        }

        $consultations = Consultation::with('patient.user', 'service')
            ->where('medecin_id', $medecin->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('medecin.consultations.index', compact('consultations'));
    }

    public function show(Consultation $consultation)
    {
        $this->authorize('view', $consultation);
        $medecin = Auth::user()->medecin;

        if (!$medecin || $consultation->medecin_id !== $medecin->id) {
            abort(403, 'Acces non autorise.');
        }

        $consultation->load(['patient.user', 'service', 'rendezvous']);

        return view('medecin.consultations.show', compact('consultation'));
    }

    public function startFromRendezVous(RendezVous $rendezvous)
    {
        $this->authorize('startFromRendezVous', [Consultation::class, $rendezvous]);
        $medecin = Auth::user()->medecin;

        if (!$medecin || $rendezvous->medecin_id !== $medecin->id) {
            abort(403, 'Acces non autorise.');
        }

        try {
            $consultation = $this->consultationWorkflowService->startFromRendezVous($rendezvous);
        } catch (Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('medecin.consultations.show', $consultation)
            ->with('success', 'Consultation ouverte avec succes.');
    }

    public function exportPatientHistoryPdf()
    {
        $this->authorize('exportOwnHistory', Consultation::class);
        $patient = Auth::user()->patient;

        if (!$patient) {
            return redirect()->route('dashboard')->with('error', 'Profil patient non trouvé.');
        }

        $consultations = Consultation::with(['medecin.user', 'service'])
            ->where('patient_id', $patient->id)
            ->latest('date_heure')
            ->get();

        $pdf = Pdf::loadView('patient.exports.history-pdf', [
            'patient' => $patient,
            'consultations' => $consultations,
            'generatedAt' => now(),
        ]);

        return $pdf->download('historique-medical-' . now()->format('Ymd-His') . '.pdf');
    }
}
