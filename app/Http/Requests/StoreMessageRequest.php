<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['patient', 'medecin'], true);
    }

    public function rules(): array
    {
        $user = $this->user();
        $allowedReceiverRoles = $user && $user->isPatient() ? ['medecin'] : ['patient'];

        return [
            'receiver_id' => [
                'required',
                Rule::exists('users', 'id')->where(function ($query) use ($allowedReceiverRoles) {
                    $query->whereIn('role', $allowedReceiverRoles);
                }),
            ],
            'contenu' => ['required', 'string', 'max:2000'],
        ];
    }
}
