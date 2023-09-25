<?php

namespace App\Http\Controllers;

use App\Models\Galeries;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\GaleriSekolahController;

class GaleriSekolahController extends Controller
{
    public function index(){
		return view('GaleriSekolah');
	}

	public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $galeri = new Galeries();
        
      
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = 'uploads/' . time() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();

            Storage::disk('public')->put($imagePath, file_get_contents($image));

            $galeri->image = url(Storage::url($imagePath));
        }
        
        
        // Simpan data galeri
            $galeri->save();


        return response()->json([
            'success' => true,
            'message' => 'Berhasil Menambahkan galeri!',
            'data' => $galeri
        ], 201);
    }

    public function list()
    {
        $data = Galeries::all();
        
        return response()->json($data);
    }
}

