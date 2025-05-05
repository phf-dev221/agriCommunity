<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderRequest extends FormRequest
{
    public function authorize(): bool
    {
         /** @var \App\Models\User $user */
      $user = auth()->guard('api')->user();
     
        if ($this->route()->getName() === 'orders.store') {
            
            return $user->hasRoles('acheteur');
        }
       
        return  $user->hasRoles('agriculteur');
    }

    public function rules(): array
    {
        if ($this->route()->getName() === 'orders.store') {
            return [
                'coupon_id' => 'nullable|exists:coupons,id',
            ];
        }

        return [
            'status' => 'required|in:pending,confirmed,shipped,delivered,cancelled',
        ];
    }

    public function messages(): array
    {
        return [
            'coupon_id.exists' => 'Le coupon spécifié n\'existe pas.',

            'status.required' => 'Le statut de la commande est obligatoire.',
            'status.in' => 'Le statut doit être l\'un des suivants : pending, confirmed, shipped, delivered, cancelled.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
        ], 422));
    }
}