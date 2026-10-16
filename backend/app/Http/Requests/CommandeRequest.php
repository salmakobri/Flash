<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommandeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'date' => 'required|date',
            'adresse_livraison' => 'required|string|max:255',
            'details' => 'nullable|string|max:2000',
        ];
    }
}