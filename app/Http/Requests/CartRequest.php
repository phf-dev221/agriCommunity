<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CartRequest extends FormRequest
{
    public function authorize(): bool
    {
         /** @var \App\Models\User $user */
      $user = auth()->guard('api')->user();
      return $user->hasRoles('acheteur');
       
    }

    public function rules(): array
    {
        $rules = [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ];

        if ($this->route()->getName() === 'cart.update') {
            $rules['product_id'] = 'sometimes|required|exists:products,id';
            $rules['quantity'] = 'sometimes|required|integer|min:1';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'L\'identifiant du produit est obligatoire.',
            'product_id.exists' => 'Le produit spécifié n\'existe pas.',

            'quantity.required' => 'La quantité est obligatoire.',
            'quantity.integer' => 'La quantité doit être un entier.',
            'quantity.min' => 'La quantité doit être d\'au moins 1.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
        ], 422));
    }
}