@extends('layouts.app')

@section('title', 'Réserver un Rendez-vous')

@section('content')
<div class="header">
    <div>
        <h1 style="color: white; margin-bottom: 0.5rem;">Prendre un Rendez-vous</h1>
        <p style="color: rgba(255,255,255,0.7); font-size: 1.1rem;">Planifiez votre prochaine consultation en quelques clics.</p>
    </div>
    <a href="{{ route('patient.dashboard') }}" class="btn-primary" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(10px);">
        <i data-lucide="arrow-left"></i> Retour au Dashboard
    </a>
</div>

<div style="max-width: 900px; margin: 0 auto; display: grid; grid-template-columns: 1fr 350px; gap: 2rem;">
    <!-- Formulaire -->
    <div class="card" style="padding: 2.5rem;">
        <form action="{{ route('patient.rendezvous.store') }}" method="POST">
            @csrf

            <div style="margin-bottom: 2rem;">
                <label style="display: block; margin-bottom: 0.75rem; font-weight: 600; color: var(--text-main); font-size: 0.95rem;">
                    1. Spécialité / Service
                </label>
                <div style="position: relative;">
                    <i data-lucide="search" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 18px; color: #94a3b8;"></i>
                    <select id="service_select" style="width: 100%; padding: 0.875rem 1rem 0.875rem 2.75rem; border: 1px solid #e2e8f0; border-radius: 14px; font-family: inherit; appearance: none; background: white;">
                        <option value="">Tous les services médicaux</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}">{{ $service->nom }}</option>
                        @endforeach
                    </select>
                    <i data-lucide="chevron-down" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); width: 18px; color: #94a3b8; pointer-events: none;"></i>
                </div>
            </div>

            <div style="margin-bottom: 2rem;">
                <label style="display: block; margin-bottom: 0.75rem; font-weight: 600; color: var(--text-main); font-size: 0.95rem;">
                    2. Choisir votre Médecin
                </label>
                <div style="position: relative;">
                    <i data-lucide="user" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 18px; color: #94a3b8;"></i>
                    <select name="medecin_id" id="medecin_select" required style="width: 100%; padding: 0.875rem 1rem 0.875rem 2.75rem; border: 1px solid #e2e8f0; border-radius: 14px; font-family: inherit; appearance: none; background: white;">
                        <option value="">Sélectionnez un praticien...</option>
                        @foreach($medecins as $medecin)
                            <option value="{{ $medecin->id }}" data-service="{{ $medecin->service_id }}">
                                Dr. {{ $medecin->user->name }} ({{ $medecin->service->nom ?? 'Généraliste' }})
                            </option>
                        @endforeach
                    </select>
                    <i data-lucide="chevron-down" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); width: 18px; color: #94a3b8; pointer-events: none;"></i>
                </div>
                @error('medecin_id') <small style="color: var(--danger); margin-top: 5px; display: block;">{{ $message }}</small> @enderror
            </div>

            <div style="margin-bottom: 2rem;">
                <label style="display: block; margin-bottom: 0.75rem; font-weight: 600; color: var(--text-main); font-size: 0.95rem;">
                    3. Date et Heure souhaitée
                </label>
                <div style="position: relative;">
                    <i data-lucide="calendar" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 18px; color: #94a3b8;"></i>
                    <input type="datetime-local" name="date_heure_debut" required 
                           min="{{ date('Y-m-d\TH:i') }}"
                           style="width: 100%; padding: 0.875rem 1rem 0.875rem 2.75rem; border: 1px solid #e2e8f0; border-radius: 14px; font-family: inherit; background: white;">
                </div>
                @error('date_heure_debut') <small style="color: var(--danger); margin-top: 5px; display: block;">{{ $message }}</small> @enderror
            </div>

            <div style="margin-bottom: 2.5rem;">
                <label style="display: block; margin-bottom: 0.75rem; font-weight: 600; color: var(--text-main); font-size: 0.95rem;">
                    4. Motif de la consultation (optionnel)
                </label>
                <textarea name="motif" rows="3" placeholder="Décrivez brièvement vos symptômes ou le but de votre visite..." 
                          style="width: 100%; padding: 1rem; border: 1px solid #e2e8f0; border-radius: 14px; font-family: inherit; resize: vertical; min-height: 100px;"></textarea>
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; padding: 1.125rem;">
                <i data-lucide="check-circle"></i> Confirmer ma Réservation
            </button>
        </form>
    </div>

    <!-- Info Panel -->
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <div class="card" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); color: white; border: none;">
            <h4 style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px;">
                <i data-lucide="info" style="width: 20px;"></i> Rappels Importants
            </h4>
            <ul style="list-style: none; display: flex; flex-direction: column; gap: 1rem; font-size: 0.875rem; opacity: 0.9;">
                <li style="display: flex; gap: 10px;">
                    <i data-lucide="clock" style="width: 16px; flex-shrink: 0;"></i>
                    <span>Veuillez arriver 15 minutes avant l'heure de votre rendez-vous.</span>
                </li>
                <li style="display: flex; gap: 10px;">
                    <i data-lucide="file-text" style="width: 16px; flex-shrink: 0;"></i>
                    <span>N'oubliez pas d'apporter votre CIN et votre carte de mutuelle.</span>
                </li>
                <li style="display: flex; gap: 10px;">
                    <i data-lucide="x-circle" style="width: 16px; flex-shrink: 0;"></i>
                    <span>Toute annulation doit être faite au moins 24h à l'avance.</span>
                </li>
            </ul>
        </div>

        <div class="card" style="text-align: center; border: 2px dashed #e2e8f0; background: transparent; box-shadow: none;">
            <div style="background: #f1f5f9; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: var(--primary);">
                <i data-lucide="shield-check"></i>
            </div>
            <h4 style="margin-bottom: 0.5rem;">Sécurité & Confidentialité</h4>
            <p style="font-size: 0.8125rem; color: #64748b;">Vos données médicales sont protégées et ne sont accessibles qu'à votre praticien.</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
        
        const serviceSelect = document.getElementById('service_select');
        const medecinSelect = document.getElementById('medecin_select');
        const options = medecinSelect.querySelectorAll('option');

        serviceSelect.addEventListener('change', function() {
            const serviceId = this.value;

            options.forEach(option => {
                if (serviceId === "" || option.value === "" || option.getAttribute('data-service') === serviceId) {
                    option.style.display = "";
                } else {
                    option.style.display = "none";
                }
            });
            
            if (medecinSelect.selectedOptions[0].style.display === "none") {
                medecinSelect.value = "";
            }
        });
    });
</script>
@endsection
