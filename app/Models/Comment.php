<?php

namespace App\Models;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use SoftDeletes ;
    use HasFactory ;

    /**
     * fillable
     *
     * @var array
     */

     protected $table = 'table_comments';
    protected $fillable = [
        'id',
        'article_id',
        'comment',
        'user_id'
      


    ];
}
