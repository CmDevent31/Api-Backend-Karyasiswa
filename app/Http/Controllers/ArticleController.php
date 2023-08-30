<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('limit', 10);

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

        $data = Article::with('images')->get(); // Load the associated images

        return response()->json($data);
    }

    public function add(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|max:255',
                'description' => 'required',
                'user_id' => 'required|exists:users,id',
                'categori_id' => 'required|exists:table_categories,id',
                'image.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $article = new Article;
            $article->title = $validated['title'];
            $article->description = $validated['description'];
            $article->user_id = $validated['user_id'];
            $article->categori_id = $validated['categori_id'];
            $article->total_comment = 0;

            $article->save();

            if ($request->hasFile('image')) {
                foreach ($request->file('image') as $image) {
                    $imagePath = $image->store('public/images');

                    // Get the public URL of the stored image
                    $imageUrl = asset('storage/' . str_replace('public/', '', $imagePath));

                    $articleImage = new ArticleImage;
                    $articleImage->image = $imageUrl;

                    // Save the article image with the article relationship
                    $article->images()->save($articleImage);
                }
            }

            $article->makeHidden(['updated_at', 'deleted_at']);

            return response()->json([
                'success' => true,
                'message' => 'Artikel Berhasil Disimpan!',
                'data' => $article->loadMissing('images'),
            ], 201);
        } catch (ValidationException $validationException) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi Gagal',
                'errors' => $validationException->errors(),
            ], 422);
        } catch (ModelNotFoundException $notFoundException) {
            return response()->json([
                'success' => false,
                'message' => 'Data Tidak Ditemukan',
                'errors' => [
                    'user_id' => ['User ID atau Category ID tidak ditemukan'],
                    'categori_id' => ['User ID atau Category ID tidak ditemukan'],
                ],
            ], 404);
        }
    }

    public function detail($id)
    {
        try {
            $article = Article::findOrFail($id);
            $article->makeHidden(['updated_at', 'deleted_at']);
            $article->images->makeHidden(['created_at', 'updated_at', 'deleted_at']);

            return response()->json([
                'success' => true,
                'message' => 'Detail Article!',
                'data' => $article->loadMissing('images'),
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Article Tidak Ditemukan!',
                'data' => (object)[],
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Pastikan pengguna sudah terotentikasi
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 401);
            }

            // Pastikan pengguna memiliki peran admin
            if (!Gate::allows('isAdmin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'sometimes|required|max:255',
                'description' => 'sometimes|required',
                'user_id' => 'sometimes|required|exists:user,id',
                'categori_id' => 'sometimes|required|exists:table_categories,id',
                'image.*' => 'image|sometimes|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors(),
                ], 422);
            }

            $article = Article::find($id);

            if (!$article) {
                return response()->json([
                    'success' => false,
                    'message' => 'Article Tidak Ditemukan!',
                    'data' => (object)[],
                ], 404);
            }

            $article->fill($request->only([
                'title', 'description', 'user_id', 'categori_id'
            ]));

            $article->save();

            if ($request->hasFile('image')) {
                $images = $request->file('image');

                $article->images()->delete();

                foreach ($images as $image) {
                    $imagePath = $image->store('public/images');
                    $imageUrl = asset('storage/' . str_replace('public/', '', $imagePath));
                    $articleImage = new ArticleImage;
                    $articleImage->image = $imageUrl;
                    $article->images()->save($articleImage);
                }
            }

            $article->loadMissing('images');
            $article->makeHidden(['updated_at', 'deleted_at']);
            $article->images->makeHidden(['created_at', 'updated_at', 'deleted_at']);

            return response()->json([
                'success' => true,
                'message' => 'Artikel Berhasil Diupdate!',
                'data' => $article->loadMissing('images'),
            ], 200);

        } catch (ValidationException $validationException) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi Gagal',
                'errors' => $validationException->errors(),
            ], 422);
        } catch (ModelNotFoundException $notFoundException) {
            return response()->json([
                'success' => false,
                'message' => 'Data Tidak Ditemukan',
                'errors' => [
                    'user_id' => ['User ID atau Category ID tidak ditemukan'],
                    'categori_id' => ['User ID atau Category ID tidak ditemukan'],
                ],
            ], 404);
        }
    }

    public function destroy($id)
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json([
                'success' => false,
                'message' => 'Article not Found !',
                'data' => (object)[],
            ], 404);
        }

        if ($article->deleted_at) {
            $article->forceDelete();

            return response()->json([
                'success' => true,
                'message' => 'Article Berhasil Dihapus secara permanen!',
                'data' => (object)[],
            ], 200);
        } else {
            $article->delete();

            return response()->json([
                'success' => true,
                'message' => 'Article Berhasil Dihapus!',
                'data' => (object)[],
            ], 200);
        }
    }
}
