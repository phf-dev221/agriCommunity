<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductRequest extends FormRequest
{
      
    public function authorize(): bool
    {
      /** @var \App\Models\User $user */
      $user = auth()->guard('api')->user();
        return $user->hasRoles('agriculteur');
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:available,unavailable',
            'categorie_id' => 'required|exists:categories,id',
            'sous_categorie_id' => 'nullable|exists:sous_categories,id',
        ];

        if ($this->method() === 'PUT') {
            $rules = array_map(function ($rule) {
                return str_replace('required', 'sometimes|required', $rule);
            }, $rules);
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom du produit est obligatoire.',
            'name.string' => 'Le nom du produit doit être une chaîne de caractères.',
            'name.max' => 'Le nom du produit ne doit pas dépasser 255 caractères.',

            'description.string' => 'La description doit être une chaîne de caractères.',

            'price.required' => 'Le prix est obligatoire.',
            'price.numeric' => 'Le prix doit être un nombre.',
            'price.min' => 'Le prix ne peut pas être négatif.',

            'stock.required' => 'Le stock est obligatoire.',
            'stock.integer' => 'Le stock doit être un entier.',
            'stock.min' => 'Le stock ne peut pas être négatif.',

            'status.required' => 'Le statut est obligatoire.',
            'status.in' => 'Le statut doit être "available" ou "unavailable".',

            'category_id.required' => 'La catégorie est obligatoire.',
            'category_id.exists' => 'La catégorie spécifiée n\'existe pas.',

            'sous_category_id.exists' => 'La sous-catégorie spécifiée n\'existe pas.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
        ], 422));
    }
}