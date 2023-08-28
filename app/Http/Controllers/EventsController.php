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
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $event = new Events();
        $event->title = $request->input('title');
        $event->description = $request->input('description');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = 'uploads/' . time() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();
            
            // Simpan gambar ke penyimpanan
            Storage::disk('public')->put($imagePath, file_get_contents($image));

            $event->image = $imagePath;
        }

        $event->save();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil Menambahkan Ekskul!',
            'data' => $event
        ], 201);
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
