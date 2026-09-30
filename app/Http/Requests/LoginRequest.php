<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|max:255',
            'password' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => "Le champ email est vide : veuillez saisir votre email.",
            'email.email' => "Le format de l'email est invalide (exemple : nom@domaine.com).",
            'email.max' => "L'email ne doit pas dépasser 255 caractères.",
            'password.required' => 'Le champ mot de passe est vide : veuillez saisir votre mot de passe.',
        ];
    }
}