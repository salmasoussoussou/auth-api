<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
   
    public function authorize(): bool
    {
        return true;
    }

        public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|max:64|confirmed',
        ];
    }
    public function messages(): array
    {
        return [
            'first_name.required' => 'Le champ prénom est vide : veuillez saisir votre prénom.',
            'first_name.max' => 'Le prénom ne doit pas dépasser 50 caractères.',
            'last_name.required' => 'Le champ nom est vide : veuillez saisir votre nom.',
            'last_name.max' => 'Le nom ne doit pas dépasser 50 caractères.',
            'email.required' => 'Le champ email est vide : veuillez saisir votre email.',
            'email.email' => "Le format de l'email est invalide (exemple : nom@domaine.com).",
            'email.max' => "L'email ne doit pas dépasser 255 caractères.",
            'email.unique' => 'Cet email est déjà utilisé par un autre compte.',
            'password.required' => 'Le champ mot de passe est vide : veuillez saisir un mot de passe.',
            'password.min' => 'Le mot de passe est trop court : 8 caractères minimum.',
            'password.max' => 'Le mot de passe est trop long : 64 caractères maximum.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ];
    }
}
