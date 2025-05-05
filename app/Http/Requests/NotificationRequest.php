<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class NotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
         /** @var \App\Models\User $user */
      $user = auth()->guard('api')->user();
        return $user->hasRole('superadmin');
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'tenant_id' => 'nullable|exists:tenants,id',
            'type' => 'required|string',
            'data' => 'required|array',
            'channel' => 'required|in:internal,email,both',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'L\'identifiant de l\'utilisateur est obligatoire.',
            'user_id.exists' => 'L\'utilisateur spécifié n\'existe pas.',

            'tenant_id.exists' => 'Le tenant spécifié n\'existe pas.',

            'type.required' => 'Le type de notification est obligatoire.',
            'type.string' => 'Le type doit être une chaîne de caractères.',

            'data.required' => 'Les données de la notification sont obligatoires.',
            'data.array' => 'Les données doivent être un tableau.',

            'channel.required' => 'Le canal de notification est obligatoire.',
            'channel.in' => 'Le canal doit être l\'un des suivants : internal, email, both.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
        ], 422));
    }
}