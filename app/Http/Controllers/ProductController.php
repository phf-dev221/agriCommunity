<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::where('user_id', auth()->user()->id)
            ->where('is_deleted', false)->get();

        if ($products->isNotEmpty()) {
            return response()->json([
                'products' => $products
            ], 200);
        } else {
            return response()->json([
                'message' => 'No product for now',
            ], 204);
        }
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
    public function store(ProductRequest $request)
    {

        $product = new Product([
            'name' => $request->name,
            'location' => $request->location,
            'status' => $request->status,
            'sous_category_id' => $request->sous_category_id,
            'user_id' => auth()->user()->id
        ]);

        try {
            $product->save();

            $imagesData = [];
            foreach ($request->file('image') as $file) {
                $imageName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('/ProductImg'), $imageName);

                $image = new Image([
                    'name' => $imageName,
                    'product_id' => $product->id
                ]);
                $image->save();
                $imagesData[] = $image;
            }

            if (count($imagesData) > 0) {
                return response()->json([
                    'message' => "Bien enregistré avec succès",
                    'bien' => $product,
                    'images' => $imagesData,
                ], 201);
            } else {
                throw new \Exception('Aucune image enregistrée');
            }
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'status_message' => 'Erreur lors de l\'enregistrement du bien : ' . $e->getMessage(),
            ]);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return response()->json([
            'product' => $product
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updat(Request $request, Product $product)
    {
        // dd($request);
        // dd($product->id);
        try {

            $product->update([
                'name' => $request->name,
                'location' => $request->location,
                'status' => $request->status,
                'sous_category_id' => $request->sous_category_id,
            ]);
    
            if ($request->hasFile('image')) {
                foreach ($product->images as $image) {
                    Storage::delete('/ProductImg/' . $image->name);
                    $image->delete();
                }
    
                $imagesData = [];
                foreach ($request->file('image') as $file) {
                    $imageName = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('/ProductImg'), $imageName);
    
                    $image = new Image([
                        'name' => $imageName,
                        'product_id' => $product->id
                    ]);
                    $image->save();
                    $imagesData[] = $image;
                }
            }
    
            return response()->json([
                'message' => "Bien mis à jour avec succès",
                'bien' => $product,
                'images' => isset($imagesData) ? $imagesData : [],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'status_message' => 'Erreur lors de la mise à jour du bien : ' . $e->getMessage(),
            ]);
        }
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->is_deleted = true;
        $product->save();
        return response()->json([

            'message' => 'Product deleted successfully'
        ], 200);
    }
}
