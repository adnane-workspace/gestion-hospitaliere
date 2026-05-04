<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique medical patient</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1f2937; }
        .title { font-size: 18px; font-weight: bold; margin-bottom: 6px; }
        .subtitle { margin-bottom: 18px; color: #6b7280; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #e5e7eb; padding: 8px; vertical-align: top; }
        th { background: #f3f4f6; text-align: left; font-size: 11px; text-transform: uppercase; }
    </style>
</head>
<body>
    <div class="title">Historique medical</div>
    <div class="subtitle">
        Patient: {{ $patient->nom }} {{ $patient->prenom }} |
        Dossier: {{ $patient->numero_dossier ?? 'N/A' }} |
        Genere le: {{ $generatedAt->format('d/m/Y H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Medecin</th>
                <th>Service</th>
                <th>Diagnostic</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($consultations as $consultation)
                <tr>
                    <td>{{ optional($consultation->date_heure)->format('d/m/Y H:i') ?? $consultation->created_at?->format('d/m/Y H:i') }}</td>
                    <td>Dr. {{ $consultation->medecin->user->name ?? 'N/A' }}</td>
                    <td>{{ $consultation->service->nom ?? 'General' }}</td>
                    <td>{{ $consultation->diagnostic_principal ?? 'Non renseigne' }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $consultation->statut)) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Aucune consultation enregistree.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
