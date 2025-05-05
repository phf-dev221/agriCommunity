<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AuthRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Tout le monde peut s'inscrire/connexion, vérifié par middleware auth:api pour login
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            
        ];

        // if ($this->route()->getName() === 'login') {
        //     return [
        //         'email' => 'required|string|email',
        //         'password' => 'required|string',
        //     ];
        // }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le prénom est obligatoire.',
            'name.string' => 'Le prénom doit être une chaîne de caractères.',
            'name.max' => 'Le prénom ne doit pas dépasser 255 caractères.',

           

            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'email.string' => 'L\'email doit être une chaîne de caractères.',

            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',

            'role_id.required' => 'Le rôle est obligatoire.',
            'role_id.exists' => 'Le rôle spécifié n\'existe pas.'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
        ], 422));
    }
}