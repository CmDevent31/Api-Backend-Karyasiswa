<?php

namespace App\Http\Controllers;

use App\Models\Ekskul;
use GuzzleHttp\Client;
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

    public function getListEkskul()
    {
        $client = new Client(); // Buat instance Guzzle Client
    
        try {
            // Panggil route API yang sesuai
            $response = $client->get('http://192.168.1.10:8000/listekskul2'); // Ganti dengan URL backend Anda
            $data = json_decode($response->getBody(), true); // Ambil data dari respons
    
            // Jika Anda ingin menyimpan data yang diambil dari API ke dalam tabel Ekskul Anda, Anda dapat melakukannya seperti ini:
            // Ekskul::insert($data);
    
            // Anda juga dapat memanggil data dari tabel Ekskul jika Anda telah menyimpannya sebelumnya
            $data = Ekskul::all();
    
            // Anda dapat melakukan apa yang Anda inginkan dengan data di sini
            // Misalnya, mengirimkan data ke frontend
            return response()->json($data);
        } catch (\Exception $e) {
            // Tangani kesalahan jika terjadi
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    
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
    
            // Simpan gambar ke penyimpanan yang dapat diakses secara publik
            $image->storeAs('public/' . $imagePath);
    
            // Dapatkan URL gambar
            $imageUrl = asset('storage/' . $imagePath);
    
            // Set URL gambar ke model Ekskul
            $ekskul->image = $imageUrl;
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
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
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
    
        // Update Ekskul data
        $ekskul->fill($request->only(['title', 'description', 'image']));
    
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