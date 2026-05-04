<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['admin', 'medecin'], true);
    }

    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:100'],
            'prenom' => ['required', 'string', 'max:100'],
            'numero_dossier' => ['required', 'string', 'max:50', 'unique:patients,numero_dossier'],
            'telephone' => ['required', 'string', 'max:20'],
            'date_naissance' => ['required', 'date', 'before:today'],
            'genre' => ['required', 'in:homme,femme,autre'],
        ];
    }
}
