<?php

namespace App\Http\Controllers;

use App\Models\Pengumumant;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class PengumumanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->query('limit', 10); // Menentukan jumlah item per halaman, defaultnya 10
        
        try {
            $validator = Validator::make($request->all(), [
                'limit' => 'integer|min:1|max:100' // Validasi input limit
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Invalid Request',
                    'errors' => $validator->errors()
                ], 400);
            }
            $article = Pengumumant::paginate($perPage);
            $article->makeHidden(['updated_at', 'deleted','deleted_at']);
            return response()->json([
                'success' => true,
                'message' => 'List Semua Article!',
                'data' => $pengumumans->loadMissing('images'),
            ], 200);
    
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error',
                'data'    => [],
            ], 500);
        }
    
                
            }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
     try {
        $validated = $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'title' => 'required',
            'description' => 'required',
            'waktu'       => 'required',
        ]);

        $pengumumans = new Pengumumant;
        $pengumumans->image = $validated['image'];
        $pengumumans->title = $validated['title'];
        $pengumumans->description = $validated['description'];
        $pengumumans->waktu= $validated['waktu'];
       

        $pengumumans->save();

        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $image) {
                $imagePath = $image->store('public/images');

                // Assuming you have symlink set up for storage folder
                // Get the public URL of the stored image
                $imageUrl = asset('storage/' . str_replace('public/', '', $imagePath));

               
            }
        }

        
        return response()->json([
            'success' => true,
            'message' => 'sipp mantap',
            'data' => $pengumumans->loadMissing('images'),
        ], 201);
    } catch (ValidationException $validationException) {
        return response()->json([
            'success' => false,
            'message' => 'Validasi Gagal',
            'errors' => $validationException->errors(),
        ], 422);
    } 
}

        

    /**
     * Display the specified resource.
     */
    public function detail($id)
    {
        try {
            $pengumumans = Pengumumant::findOrFail($id);
           
    
            return response()->json([
                'success' => true,
                'message' => 'Detail Article!',
                'data'    => $pengumumans
            ], 200);
    
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Article Tidak Ditemukan!',
                'data' => (object)[],
            ], 404);
        }
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:20480',
            'title' => 'sometimes|required',
            'description' => 'sometimes|required',
            
            
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 422);
        }
    
        // Find Product by ID
        $pengumumans = Pengumumant::find($id);
    
        // Check if Product exists
        if (!$Product) {
            return response()->json([
                'success' => false,
                'message' => 'Product Tidak Ditemukan!',
                'data' => (object)[],
            ], 404);
        }
    
        $Pengumumans->fill($request->only([
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
}
   

    

    