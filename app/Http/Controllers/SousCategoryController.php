<?php

namespace App\Http\Controllers;

use App\Http\Requests\SousCategoryRequest;
use App\Models\SousCategory;
use Illuminate\Http\Request;

class SousCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'sousCategories' => SousCategory::all()
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SousCategoryRequest $request)
    {
        $sousCategory = SousCategory::create($request->validated());
        return response()->json([
            'sousCategory' => $sousCategory,
            'message' => 'SousCategory created successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(SousCategory $sousCategory)
    {
        return response()->json([
            'sousCategory' => $sousCategory
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SousCategory $sousCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SousCategory $sousCategory)
    {
        $sousCategory->updated($request->validated());
        return response()->json([
            'sousCategory' => $sousCategory,
           'message' => 'sousCategory updated successfully'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SousCategory $sousCategory)
    {
        if($sousCategory->delete()){
            return response()->json([
               
               'message' => 'sousCategory deleted successfully'
            ], 204);
        }else{
            return response()->json([
               
                'error' => 'something went wrong'
             ], 500);
        }
    }
}
