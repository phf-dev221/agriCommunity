<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SousCategorieRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255|unique:sous_categories,name',
            'description' => 'nullable|string',
            'categorie_id' => 'required|exists:categories,id',
        ];

        if ($this->method() === 'PUT') {
            $rules['name'] = 'sometimes|required|string|max:255|unique:sous_categories,name,' . $this->route('sousCategorie')->id;
            $rules['description'] = 'sometimes|nullable|string';
            $rules['categorie_id'] = 'sometimes|required|exists:categories,id';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom de la sous-catégorie est obligatoire.',
            'name.string' => 'Le nom de la sous-catégorie doit être une chaîne de caractères.',
            'name.max' => 'Le nom de la sous-catégorie ne doit pas dépasser 255 caractères.',
            'name.unique' => 'Ce nom de sous-catégorie est déjà utilisé.',

            'description.string' => 'La description doit être une chaîne de caractères.',

            'categorie_id.required' => 'La catégorie associée est obligatoire.',
            'categorie_id.exists' => 'La catégorie spécifiée n\'existe pas.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors' => $validator->errors(),
        ], 422));
    }
}