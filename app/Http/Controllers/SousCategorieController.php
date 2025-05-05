<?php

namespace App\Http\Controllers;

use App\Http\Requests\SousCategorieRequest;
use App\Models\SousCategorie;
use Illuminate\Support\Facades\DB;

class SousCategorieController extends Controller
{
    public function index()
    {
        try {
            $sousCategories = SousCategorie::with('categorie')->get();

            if ($sousCategories->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'Aucune sous-catégorie pour l\'instant',
                ], 200);
            }

            return response()->json([
                'success' => true,
                'data' => $sousCategories,
                'message' => 'Sous-catégories récupérées avec succès',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des sous-catégories',
                'error' => config('app.debug') ? $e->getMessage() : 'Veuillez réessayer plus tard',
            ], 500);
        }
    }

    public function store(SousCategorieRequest $request)
    {
        try {
            $sousCategorie = SousCategorie::create($request->validated());

            return response()->json([
                'success' => true,
                'data' => $sousCategorie,
                'message' => 'Sous-catégorie créée avec succès',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la sous-catégorie',
                'error' => config('app.debug') ? $e->getMessage() : 'Veuillez réessayer plus tard',
            ], 500);
        }
    }

    public function show(SousCategorie $sousCategorie)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $sousCategorie->load('categorie'),
                'message' => 'Sous-catégorie récupérée avec succès',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de la sous-catégorie',
                'error' => config('app.debug') ? $e->getMessage() : 'Veuillez réessayer plus tard',
            ], 500);
        }
    }

    public function update(SousCategorieRequest $request, SousCategorie $sousCategorie)
    {
        try {
            $sousCategorie->update($request->validated());

            return response()->json([
                'success' => true,
                'data' => $sousCategorie,
                'message' => 'Sous-catégorie mise à jour avec succès',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de la sous-catégorie',
                'error' => config('app.debug') ? $e->getMessage() : 'Veuillez réessayer plus tard',
            ], 500);
        }
    }

    public function destroy(SousCategorie $sousCategorie)
    {
        try {
            if ($sousCategorie->products()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de supprimer la sous-catégorie : des produits y sont associés',
                ], 400);
            }

            $sousCategorie->delete();

            return response()->json([
                'success' => true,
                'message' => 'Sous-catégorie supprimée avec succès',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de la sous-catégorie',
                'error' => config('app.debug') ? $e->getMessage() : 'Veuillez réessayer plus tard',
            ], 500);
        }
    }
}