<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'limit' => 'integer|min:1|max:100', // Validasi input limit
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Request',
                    'errors' => $validator->errors(),
                ], 400);
            }
    
            $limit = $request->query('limit', 10);
    
            $comments = Comment::all();
            $comments->each(function ($comment) {
                $comment->makeHidden(['updated_at', 'deleted']);
            });
    
            return response()->json([
                'success' => true,
                'message' => 'List Semua Comment!',
                'data' => $comments,
            ], 200);
    
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error',
            ], 500);
        }
    }
    

    public function create(Request $request)
    {
        $validated = $request->validate([
            'article_id' => 'required|exists:table_articles,id',
            'comment' => 'required',
            'member_id' => 'required|exists:users,id'
        ]);
    
        $Comment = new Comment;
        $Comment->article_id = $validated['article_id'];
        $Comment->comment = $validated['comment'];
        $Comment->member_id = $validated['member_id'];
    
        $Comment->save();
    
        // Update total_comments in the related article
        $article = Article::find($validated['article_id']);
        if ($article) {
            $article->total_comment = $article->total_comment + 1;
            $article->save();
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Comment Berhasil Disimpan!',
            'data' => $Comment,
        ], 201);
    }
    
    

    /**
     * Store a newly created resource in storage.
     */

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $Comment= Comment::with('writer:id,username')->findOrFail($id);
        $Comment->makeHidden(['updated_at', 'deleted_at']);
             if ($Comment) {
            return response()->json([
                'success' => true,
                'message' => 'Detail Post!',
                'data'    => $Comment
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Post Tidak Ditemukan!',
                'data' => (object)[],
            ], 401);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'article_id' => 'required',
            'comment' => 'required',
            'member_id' => 'required'
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 422);
        }
    
        // Find Comment by ID
        $Comment = Comment::find($id);
    
        // Check if Comment exists
        if (!$Comment) {
            return response()->json([
                'success' => false,
                'message' => 'Comment Tidak Ditemukan!',
                'data' => (object)[],    
            ], 404);
        }
    
        // Update the Comment fields
        $Comment->article_id= $validated['article_id'];
        $Comment->comment= $validated['comment'];
        $Comment->member_id = $validated['member_id'];
        $Comment->total_comment = 0;
        // Create a new article record in the 'article' table.
        $article = new Article(); // Assuming 'article' is the correct model name
        $article->total_comment= $total_comment;
        $article->save();
    
        // Save the changes
        $Comment->save();
        return response()->json([
            'success' => true,
            'message' => 'Comment Berhasil Diupdate!',
            'data' => $Comment,
        ], 200);
    } 

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $Comment = Comment::find($id);
    
        if (!$Comment) {
            return response()->json([
                'success' => false,
                'message' => 'Comment not Found !',
                'data' => (object)[],
            ], 404);
        }
    
        if ($Comment->deleted == 1) {
            $Comment->forceDelete();
    
            return response()->json([
                'success' => true,
                'message' => 'Comment Berhasil Dihapus secara permanen!',
                'data' => (object)[],
            ], 200);
        } else {
            $Comment->deleted = 1;
            $Comment->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Comment Berhasil Dihapus!',
                'data' => $Comment,
            ], 200);
        }
    }
}