<?php

namespace App\Models;

use App\Models\ArticleImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'table_articles'; // Specify the correct table name here
    protected $fillable = [
        'title',
        'description',
        'member_id',
        'categori_id',
        'image', 
    ];

     /**
     * Get the writer that owns the post
     *
     * @return BelongsTo*/


    public function category()
    {
        return $this->belongsTo(TableCategory::class, 'categori_id');
    }

    public function images()
    {
        return $this->hasMany(ArticleImage::class);
    }
}
