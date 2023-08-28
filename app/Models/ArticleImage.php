<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleImage extends Model
{
    protected $table = 'table_article_images'; // Specify the correct table name here

    protected $fillable = ['image', 'article_id'];

    // Define the inverse relationship with Article model (one-to-one)
    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
