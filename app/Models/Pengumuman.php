<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumumant extends Model
{
    use HasFactory;
    protected $table = 'pengumumans';

    protected $fillable = [
            'image' ,
            'title' ,
            'description' ,
            'waktu' , 
    ];
}
