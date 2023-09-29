<?php

namespace App\Http\Controllers;

use App\Models\Events;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EventsController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $event = new Events();
        $event->title = $request->input('title');
        $event->description = $request->input('description');
        $event->user_id = $request->input('user_id');
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = 'uploads/' . time() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();
            
            // Simpan gambar ke penyimpanan
            Storage::disk('public')->put($imagePath, file_get_contents($image));
            
            $event->image = url(Storage::url($imagePath)); // Mengambil URL lengkap gambar
        }

        $event->save();

      
        return redirect('http://127.0.0.1:8000/Article?success=true');
    }

    public function update(Request $request, $id)
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'user_id' => 'required|exists:users,id',
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 422);
        }
    
        // Find Ekskul by ID
        $event = Events::find($id);
    
        // Check if Ekskul exists
        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Ekskul Tidak Ditemukan!',
                'data' => (object)[],
            ], 404);
        }
        $event->fill($request->only([
            // 'name', 'category_id', 'description', 'price', 'discount', 'rating', 'brand', 'member_id', 'image'
            'title', 'image', 'description'
        ]));
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = 'uploads/' . time() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();
            
            // Simpan gambar ke penyimpanan
            Storage::disk('public')->put($imagePath, file_get_contents($image));
            
            $event->image = url(Storage::url($imagePath)); // Mengambil URL lengkap gambar
        }
        
        // Simpan data ekskul
        $event->save();
    
        return response()->json([
            'success' => true,
            'message' => 'Ekskul Berhasil Diupdate!',
            'data' => $event,
        ], 200);
    }

    public function list()
    {
        $events = Events::all();

        return response()->json([
            'success' => true,
            'message' => 'List Semua Events!',
            'data' => $events,
        ], 200);
    }
}
