<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Models\Image;

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

        $product = Product::create([
            'name' => $request->name,
            'location' => $request->location,
            'status' => $request->status,
            'sous_category_id' => $request->sous_category_id,
            'user_id' => $request->user_id
        ]);
       
        if($product->save()){
            foreach ($request->file('image') as $image) {
                $images = new Image();
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $imagePath = public_path('images');
                $image->move($imagePath, $imageName);
                $images->image = 'images/' . $imageName;
                $images->product_id = $product->id;
                $images->save();
    
               
            }
           
           
            return response()->json([
                'product' => $product,
                'message' => 'Product created successfully'
            ], 201);
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
    public function update(ProductRequest $request, Product $product)
    {
        $product->update($request->validated());
        return response()->json([
            'product' => $product,
           'message' => 'Product updated successfully'
        ], 200);
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
