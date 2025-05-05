<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategorieRequest;
use App\Models\Categorie;
use Illuminate\Support\Facades\DB;

class CategorieController extends Controller
{
    public function index()
    {
        try {
            $categories = Categorie::with('sousCategories')->get();

            if ($categories->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'Aucune catégorie pour l\'instant',
                ], 200);
            }

            return response()->json([
                'success' => true,
                'data' => $categories,
                'message' => 'Catégories récupérées avec succès',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des catégories',
                'error' => config('app.debug') ? $e->getMessage() : 'Veuillez réessayer plus tard',
            ], 500);
        }
    }

    public function store(CategorieRequest $request)
    {
        try {
            $categorie = Categorie::create($request->validated());

            return response()->json([
                'success' => true,
                'data' => $categorie,
                'message' => 'Catégorie créée avec succès',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la catégorie',
                'error' => config('app.debug') ? $e->getMessage() : 'Veuillez réessayer plus tard',
            ], 500);
        }
    }

    public function show(Categorie $categorie)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $categorie,
                'message' => 'Catégorie récupérée avec succès',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de la catégorie',
                'error' => config('app.debug') ? $e->getMessage() : 'Veuillez réessayer plus tard',
            ], 500);
        }
    }

    public function update(CategorieRequest $request, Categorie $categorie)
    {
        try {
            $categorie->update($request->validated());

            return response()->json([
                'success' => true,
                'data' => $categorie,
                'message' => 'Catégorie mise à jour avec succès',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de la catégorie',
                'error' => config('app.debug') ? $e->getMessage() : 'Veuillez réessayer plus tard',
            ], 500);
        }
    }

    public function destroy(Categorie $categorie)
    {
        try {
            if ($categorie->products()->exists() || $categorie->sousCategories()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de supprimer la catégorie : des produits ou sous-catégories y sont associés',
                ], 400);
            }

            $categorie->delete();

            return response()->json([
                'success' => true,
                'message' => 'Catégorie supprimée avec succès',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de la catégorie',
                'error' => config('app.debug') ? $e->getMessage() : 'Veuillez réessayer plus tard',
            ], 500);
        }
    }
}