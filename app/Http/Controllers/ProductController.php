<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;

class ProductController extends Controller
{
    public function index(Request $request)
{
    $perPage = $request->query('limit', 10);

    try {
        $validator = Validator::make($request->all(), [
            'limit' => 'integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Request',
                'errors' => $validator->errors(),
            ], 400);
        }

        $products = Product::paginate($perPage);
        $products->makeHidden(['created_at','updated_at', 'deleted', 'deleted_at']);

        foreach ($products as $product) {
            $productStocks = ProductStock::where('product_id', $product->id)->get();
            $productStocks->makeHidden(['created_at','updated_at', 'deleted', 'deleted_at']);
            $product->loadMissing(['images']);
            $product->productStocks = $productStocks;
        }

        return response()->json([
            'success' => true,
            'message' => 'List Semua Product!',
            'data' => $products,
        ], 200);

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Internal Server Error',
        ], 500);
    }
}

    
    
    
public function add(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'categori_id' => 'required|exists:table_categories,id',
            'description' => 'required',
            'price' => 'required',
            'brand' => 'required',
            'user_id' => 'required|exists:users,id',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $Product = new Product;
        $Product->name = $validated['name'];
        $Product->categori_id = $validated['categori_id'];
        $Product->description = $validated['description'];
        $Product->price = $validated['price'];
        $Product->brand = $validated['brand'];
        $Product->user_id = $validated['user_id'];
    
        $Product->save();

        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $image) {
                $imagePath = $image->store('public/images');

                // Create an ProductImage model to associate the image with the Product
                $ProductImage = new ProductImage;
                $ProductImage->image = $imagePath;

                // Save the ProductImage with the Product relationship
                $Product->images()->save($ProductImage);
            }
        }

        // Hide 'updated_at' and 'deleted_at' columns
        $Product->makeHidden(['updated_at', 'deleted_at']);

        return response()->json([
            'success' => true,
            'message' => 'Product Berhasil Disimpan!',
            'data' => $Product->loadMissing('images'),
        ], 201);
    }
    /**
     * Store a newly created resource in storage.
     */

    /**
     * Display the specified resource.
     */

     /**
     * Get the details of a specific member.
     */
    public function detail($id)
    {
        $Product = Product::findOrFail($id);
             if ($Product) {
            return response()->json([
                'success' => true,
                'message' => 'Detail Product!',
                'data'    => $Product->loadMissing(['ProductStock', 'images']),
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Product Tidak Ditemukan!',
                'data' => (object)[],
            ], 401);
        }
    }

   

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    // Define validation rules
    $validator = Validator::make($request->all(), [
        'name' => 'sometimes|required|max:255',
        'categori_id' => 'sometimes|required|exists:table_categories,id',
        'description' => 'sometimes|required',
        'price' => 'sometimes|required',
        'brand' => 'sometimes|required',
        'user_id' => 'sometimes|required|exists:users,id',
        'image.*' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Check if validation fails
    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => $validator->errors(),
        ], 422);
    }

    // Find Product by ID
    $Product = Product::find($id);

    // Check if Product exists
    if (!$Product) {
        return response()->json([
            'success' => false,
            'message' => 'Product Tidak Ditemukan!',
            'data' => (object)[],
        ], 404);
    }

    $Product->fill($request->only([
        // 'name', 'category_id', 'description', 'price', 'discount', 'rating', 'brand', 'member_id', 'image'
        'name', 'categori_id', 'description'
    ]));

    // Save the changes
    $Product->save(); 
    // dd($Product);


    // Handle the image upload
    if ($request->hasFile('image')) {
        $images = $request->file('image');

        // Delete existing images (optional, if you want to replace all images)
        $Product->images()->delete();

        // Upload and save the new images
        foreach ($images as $image) {
            $imagePath = $image->store('public/images');

            // Create an ProductImage model to associate the image with the Product
            $ProductImage = new ProductImage;
            $ProductImage->product_id = $Product->id;
            $ProductImage->image = $imagePath;

            // Associate the image with the Product
            $Product->images()->save($ProductImage);
        }
    }

    // Load the missing image relationship if it exists
    $Product->loadMissing('images');

    // Make hidden any attributes you want to exclude from the JSON response
    $Product->makeHidden(['updated_at', 'deleted_at']);
    $Product->images->makeHidden(['created_at', 'updated_at', 'deleted_at']);
    return response()->json([
        'success' => true,
        'message' => 'Product Berhasil Diupdate!',
        'data' => $Product->loadMissing('images'),
    ], 200);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $Product = Product::find($id);
    
        if (!$Product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not Found !',
                'data' => (object)[],
            ], 404);
        }
    
        if ($Product->deleted == 1) {
            $Product->forceDelete();
    
            return response()->json([
                'success' => true,
                'message' => 'Product Berhasil Dihapus secara permanen!',
                'data' =>  $Product,
            ], 200);
        } else {
            $Product->deleted = 1;
            $Product->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Product Berhasil Dihapus!',
                'data' => (object)[],
            ], 200);
        }
    }
}