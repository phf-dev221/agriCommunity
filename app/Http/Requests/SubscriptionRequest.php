<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
         /** @var \App\Models\User $user */
      $user = auth()->guard('api')->user();
        if ($this->route()->getName() === 'subscriptions.subscribe') {
            
      
            return $user->hasRoles('agriculteur');
        }
        
        return $user->hasRoles('superadmin');
    }

    public function rules(): array
    {
        if ($this->route()->getName() === 'subscriptions.subscribe') {
            return [
                'plan_id' => 'required|exists:plans,id',
            ];
        }

        return [
            'status' => 'required|in:active,inactive,cancelled',
            'payment_status' => 'required|in:pending,paid,failed',
            'end_date' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'plan_id.required' => 'L\'identifiant du plan est obligatoire.',
            'plan_id.exists' => 'Le plan spécifié n\'existe pas.',

            'status.required' => 'Le statut de l\'abonnement est obligatoire.',
            'status.in' => 'Le statut doit être l\'un des suivants : active, inactive, cancelled.',

            'payment_status.required' => 'Le statut de paiement est obligatoire.',
            'payment_status.in' => 'Le statut de paiement doit être l\'un des suivants : pending, paid, failed.',

            'end_date.date' => 'La date de fin doit être une date valide.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
        ], 422));
    }
}