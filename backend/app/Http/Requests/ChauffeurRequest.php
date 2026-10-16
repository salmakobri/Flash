<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChauffeurRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $chauffeur = $this->route('chauffeur');

        return [
            'nom' => 'required|string|max:255',
            'email' => ['required', 'email',
                Rule::unique('users', 'email')->ignore($chauffeur?->user_id)],
            'password' => [$chauffeur ? 'nullable' : 'required', 'string', 'min:8'],
        ];
    }
}