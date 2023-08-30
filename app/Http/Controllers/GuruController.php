<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\GuruController;
use Illuminate\Support\Facades\Validator;

class GuruController extends Controller
{
	public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $guru = new Guru();
        $guru->title = $request->input('title');
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = 'uploads/' . time() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();
            
            // Simpan gambar ke penyimpanan
            Storage::disk('public')->put($imagePath, file_get_contents($image));
            
            $guru->image = url(Storage::url($imagePath)); // Mengambil URL lengkap gambar
        }

        $guru->save();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil Menambahkan Guru!',
            'data' => $guru
        ], 201);
    }

    public function update(Request $request, $id)
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 422);
        }
    
        // Find Ekskul by ID
        $guru = Guru::find($id);
    
        // Check if Ekskul exists
        if (!$guru) {
            return response()->json([
                'success' => false,
                'message' => 'Guru Tidak Ditemukan!',
                'data' => (object)[],
            ], 404);
        }
        $guru->fill($request->only([
            // 'name', 'category_id', 'description', 'price', 'discount', 'rating', 'brand', 'member_id', 'image'
            'title', 'image', 'description'
        ]));
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = 'uploads/' . time() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();
            
            // Simpan gambar ke penyimpanan
            Storage::disk('public')->put($imagePath, file_get_contents($image));
            
            $guru->image = url(Storage::url($imagePath)); // Mengambil URL lengkap gambar
        }
        
        // Simpan data ekskul
        $guru->save();
    
        return response()->json([
            'success' => true,
            'message' => 'Guru Berhasil Diupdate!',
            'data' => $guru,
        ], 200);
    }

    public function list()
    {
        $guru = Guru::all();

        return response()->json([
            'success' => true,
            'message' => 'List Semua Guru!',
            'data' => $guru,
        ], 200);
    }
}
