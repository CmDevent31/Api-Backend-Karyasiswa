<?php

namespace App\Http\Controllers;

use App\Models\Ekskul;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;



class EkskulController extends Controller
{
    public function index(){
        return view('ekskul');
    }

    // public function getDataFromApi()
    // {
    //     // Panggil API atau gunakan data dari Postman
    //     // Proses data jika diperlukan
    
    //     $data = []; // Data yang akan ditampilkan di view
    
    //     return view('ekskul', ['data' => $data]);
    // }

    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $ekskul = new Ekskul();
        $ekskul->title = $request->input('title');
        $ekskul->description = $request->input('description');
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = 'uploads/' . time() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();
            
            // Simpan gambar ke penyimpanan
            Storage::disk('public')->put($imagePath, file_get_contents($image));
            
            $ekskul->image = url(Storage::url($imagePath)); // Mengambil URL lengkap gambar
        }
        
        // Simpan data ekskul
            $ekskul->save();


        return response()->json([
            'success' => true,
            'message' => 'Berhasil Menambahkan Ekskul!',
            'data' => $ekskul
        ], 201);
    }


    public function update(Request $request, $id)
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
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
        $ekskul = Ekskul::find($id);
    
        // Check if Ekskul exists
        if (!$ekskul) {
            return response()->json([
                'success' => false,
                'message' => 'Ekskul Tidak Ditemukan!',
                'data' => (object)[],
            ], 404);
        }
        $ekskul->fill($request->only([
            // 'name', 'category_id', 'description', 'price', 'discount', 'rating', 'brand', 'member_id', 'image'
            'name', 'image', 'description'
        ]));
        // Update Ekskul data
        $ekskul->title = $request->input('title');
        $ekskul->description = $request->input('description');
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = 'uploads/' . time() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();
            
            // Simpan gambar ke penyimpanan
            Storage::disk('public')->put($imagePath, file_get_contents($image));
            
            $ekskul->image = url(Storage::url($imagePath)); // Mengambil URL lengkap gambar
        }
        
        // Simpan data ekskul
        $ekskul->save();
    
        return response()->json([
            'success' => true,
            'message' => 'Ekskul Berhasil Diupdate!',
            'data' => $ekskul,
        ], 200);
    }

    
    public function list()
    {
        $data = Ekskul::all();
        
        return response()->json($data);
    }
}

