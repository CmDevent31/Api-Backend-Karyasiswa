<?php

namespace App\Http\Controllers;

use App\Models\ArticleImage;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreArticleImageRequest;
use App\Http\Requests\UpdateArticleImageRequest;

class ArticleImageController
{
   
        public function storeImage(UploadedFile $file)
        {
            $request = new Request(['image' => $file]);
            $validator = $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
    
            $imagePath = $file->store('public/images');
    
            // Dapatkan URL dari path gambar
            $imageLink = url(Storage::url($imagePath));
    
            // Simpan informasi gambar ke tabel 'table_article_image'
            $articleImage = new ArticleImage;
            $articleImage->image_path = $imagePath;
            $articleImage->image_link = $imageLink;
            $articleImage->save();
    
            return $articleImage->image_path;
        }
}
