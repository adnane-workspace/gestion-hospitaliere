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
     * Store a newly created appointment.
     */
    public function store(StoreRendezVousRequest $request): JsonResponse
    {
        try {
            // On délègue toute la logique complexe au Service
            $rendezVous = $this->rendezVousService->reserver($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Rendez-vous réservé avec succès.',
                'data' => $rendezVous
            ], 201);

        } catch (Exception $e) {
            // En cas de conflit (médecin occupé) ou autre erreur métier
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
