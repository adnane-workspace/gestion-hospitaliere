@extends('layouts.app')

@section('title', 'Gestion des Patients')

@section('content')
<div class="header">
    <h1>Patients</h1>
    <button style="background: var(--primary); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 0.5rem; font-weight: 600; cursor: pointer;">
        + Nouveau Patient
    </button>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Dossier #</th>
                <th>Patient</th>
                <th>Genre</th>
                <th>Téléphone</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>DOS-2024-001</strong></td>
                <td>
                    <div style="font-weight: 600;">Adnane EL KHALOUFI</div>
                    <div style="font-size: 0.75rem; color: var(--secondary);">adnane@example.com</div>
                </td>
                <td>Homme</td>
                <td>+212 600 000 000</td>
                <td><span class="badge badge-success">Actif</span></td>
                <td>
                    <a href="#" style="color: var(--primary); text-decoration: none; font-weight: 600;">Voir</a>
                </td>
            </tr>
            <!-- D'autres lignes ici -->
        </tbody>
    </table>
</div>
@endsection
